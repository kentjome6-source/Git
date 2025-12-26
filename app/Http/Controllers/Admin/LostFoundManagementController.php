<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LostFound;
use App\Models\LostFoundClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LostFoundManagementController extends Controller
{
    public function index()
    {
        $pendingListings = LostFound::where('status', 'pending')
            ->with('user')
            ->latest()
            ->paginate(12);

        $approvedListings = LostFound::where('status', 'approved')
            ->where('is_resolved', false)
            ->with('user')
            ->latest()
            ->paginate(12);

        $resolvedListings = LostFound::where('is_resolved', true)
            ->with('user')
            ->latest()
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
        $lostFound->load('user', 'claims.claimer');
        return view('admin.lost-found.show', compact('lostFound'));
    }

    public function approve(LostFound $lostFound)
    {
        $lostFound->update([
            'status' => 'approved',
            'admin_reviewer_id' => Auth::id(),
            'admin_reviewed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Listing approved successfully'
        ]);
    }

    public function reject(Request $request, LostFound $lostFound)
    {
        $request->validate([
            'reason' => 'required|string'
        ]);

        $lostFound->update([
            'status' => 'rejected',
            'admin_reviewer_id' => Auth::id(),
            'admin_reviewed_at' => now(),
            'admin_notes' => $request->reason,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Listing rejected'
        ]);
    }

    public function toggleFeature(LostFound $lostFound)
    {
        $lostFound->update([
            'is_featured' => !$lostFound->is_featured
        ]);

        return response()->json([
            'success' => true,
            'message' => $lostFound->is_featured ? 'Listing featured' : 'Listing unfeatured'
        ]);
    }

    public function claims()
    {
        $pendingClaims = LostFoundClaim::where('status', 'pending')
            ->with(['lostFound', 'claimer'])
            ->latest()
            ->paginate(12);

        $underReviewClaims = LostFoundClaim::where('status', 'under_review')
            ->with(['lostFound', 'claimer'])
            ->latest()
            ->paginate(12);

        $completedClaims = LostFoundClaim::whereIn('status', ['approved', 'rejected', 'completed'])
            ->with(['lostFound', 'claimer'])
            ->latest()
            ->paginate(12);

        return view('admin.lost-found.claims', compact(
            'pendingClaims',
            'underReviewClaims',
            'completedClaims'
        ));
    }

    public function showClaim(LostFoundClaim $claim)
    {
        $claim->load('lostFound', 'claimer', 'adminReviewer', 'vetVerifier');
        return view('admin.lost-found.claim-show', compact('claim'));
    }

    public function reviewClaim(Request $request, LostFoundClaim $claim)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,request_vet',
            'notes' => 'nullable|string',
            'rejection_reason' => 'required_if:action,reject|string'
        ]);

        if ($request->action === 'approve') {
            $claim->update([
                'status' => 'approved',
                'admin_reviewer_id' => Auth::id(),
                'admin_reviewed_at' => now(),
                'admin_notes' => $request->notes,
            ]);
        } elseif ($request->action === 'reject') {
            $claim->update([
                'status' => 'rejected',
                'admin_reviewer_id' => Auth::id(),
                'admin_reviewed_at' => now(),
                'admin_notes' => $request->notes,
                'rejection_reason' => $request->rejection_reason,
            ]);
        } elseif ($request->action === 'request_vet') {
            $claim->update([
                'status' => 'under_review',
                'admin_reviewer_id' => Auth::id(),
                'admin_reviewed_at' => now(),
                'admin_notes' => $request->notes,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Claim updated successfully'
        ]);
    }

    public function completeClaim(LostFoundClaim $claim)
    {
        $claim->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        $claim->lostFound->update([
            'is_resolved' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Claim marked as completed and listing resolved'
        ]);
    }
}
