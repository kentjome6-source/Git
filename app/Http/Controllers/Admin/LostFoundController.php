<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LostFound;
use App\Models\LostFoundClaim;
use App\Models\LostFoundMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LostFoundController extends Controller
{
    public function index(Request $request)
    {
        $pendingListings = LostFound::with(['user'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $approvedListings = LostFound::with(['user'])
            ->withCount('claims')
            ->where('status', 'approved')
            ->where('is_resolved', false)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $resolvedListings = LostFound::with(['user'])
            ->where('is_resolved', true)
            ->orderBy('updated_at', 'desc')
            ->paginate(12);

        $pendingCount = LostFound::where('status', 'pending')->count();
        $activeCount = LostFound::where('status', 'approved')->where('is_resolved', false)->count();
        $resolvedCount = LostFound::where('is_resolved', true)->count();
        $claimsCount = LostFoundClaim::where('status', 'pending')->count();

        return view('admin.lost-found.index', compact(
            'pendingListings',
            'approvedListings',
            'resolvedListings',
            'pendingCount',
            'activeCount',
            'resolvedCount',
            'claimsCount'
        ));
    }

    public function show(LostFound $lostFound)
    {
        $lostFound->load(['user', 'adminReviewer', 'claims.claimer', 'claims.adminReviewer', 'claims.vetVerifier']);
        
        // Find potential matches if this is a lost/found pet
        $potentialMatches = collect();
        if ($lostFound->type === 'lost') {
            $potentialMatches = LostFound::where('type', 'found')
                ->where('status', 'approved')
                ->where('is_resolved', false)
                ->where('pet_type', $lostFound->pet_type)
                ->get();
        } elseif ($lostFound->type === 'found') {
            $potentialMatches = LostFound::where('type', 'lost')
                ->where('status', 'approved')
                ->where('is_resolved', false)
                ->where('pet_type', $lostFound->pet_type)
                ->get();
        }
        
        return view('admin.lost-found.show', compact('lostFound', 'potentialMatches'));
    }

    public function approve(Request $request, LostFound $lostFound)
    {
        $request->validate([
            'notes' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
        ]);

        $lostFound->update([
            'status' => 'approved',
            'admin_reviewer_id' => Auth::id(),
            'admin_reviewed_at' => now(),
            'admin_notes' => $request->notes,
            'is_featured' => $request->is_featured ?? false,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Listing approved successfully.'
            ]);
        }

        return redirect()->back()->with('success', 'Listing approved successfully.');
    }

    public function reject(Request $request, LostFound $lostFound)
    {
        $request->validate([
            'notes' => 'required|string',
        ]);

        $lostFound->update([
            'status' => 'rejected',
            'admin_reviewer_id' => Auth::id(),
            'admin_reviewed_at' => now(),
            'admin_notes' => $request->notes,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Listing rejected.'
            ]);
        }

        return redirect()->back()->with('success', 'Listing rejected.');
    }

    public function toggleFeatured(LostFound $lostFound)
    {
        $lostFound->update([
            'is_featured' => !$lostFound->is_featured
        ]);

        return response()->json([
            'success' => true,
            'is_featured' => $lostFound->is_featured
        ]);
    }

    public function viewClaims(LostFound $lostFound)
    {
        $lostFound->load(['user', 'claims']);
        
        $claims = LostFoundClaim::with(['claimer', 'adminReviewer', 'vetVerifier'])
            ->where('lost_found_id', $lostFound->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Debug: Log the data to check
        \Log::info('Lost Found Claims Data:', [
            'lostFound' => $lostFound->toArray(),
            'claims_count' => $claims->count(),
            'claims' => $claims->toArray()
        ]);

        return view('admin.lost-found.claims', compact('lostFound', 'claims'));
    }

    public function claims(Request $request)
    {
        $filter = $request->get('filter', 'pending');
        
        $query = LostFoundClaim::with(['lostFound.user', 'claimer', 'adminReviewer', 'vetVerifier']);
        
        if ($filter !== 'all') {
            $query->where('status', $filter);
        }
        
        $claims = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.lost-found.claims', compact('claims', 'filter'));
    }

    public function showClaim(LostFoundClaim $claim)
    {
        $claim->load(['lostFound.user', 'claimer', 'adminReviewer', 'vetVerifier']);
        return view('admin.lost-found.claim-details', compact('claim'));
    }

    public function approveClaim(Request $request, LostFoundClaim $claim)
    {
        $request->validate([
            'notes' => 'nullable|string',
        ]);

        $claim->update([
            'status' => 'approved',
            'admin_reviewer_id' => Auth::id(),
            'admin_reviewed_at' => now(),
            'admin_notes' => $request->notes,
        ]);

        // Mark the lost/found item as resolved
        $claim->lostFound->update([
            'status' => 'reunited',
            'is_resolved' => true,
            'resolved_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Claim approved and pet reunited successfully.'
        ]);
    }

    public function rejectClaim(Request $request, LostFoundClaim $claim)
    {
        $request->validate([
            'notes' => 'required|string',
        ]);

        $claim->update([
            'status' => 'rejected',
            'admin_reviewer_id' => Auth::id(),
            'admin_reviewed_at' => now(),
            'admin_notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Claim rejected.'
        ]);
    }
}