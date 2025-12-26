<?php

namespace App\Http\Controllers\Vet;

use App\Http\Controllers\Controller;
use App\Models\LostFoundClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LostFoundVerificationController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'under_review');
        
        $query = LostFoundClaim::with(['lostFound.user', 'claimer', 'adminReviewer']);
        
        if ($filter === 'pending') {
            $query->whereIn('status', ['under_review']);
        } elseif ($filter === 'verified') {
            $query->where('status', 'approved')
                  ->whereNotNull('vet_verifier_id');
        } elseif ($filter === 'all') {
            $query->whereIn('status', ['under_review', 'approved', 'completed']);
        }
        
        $claims = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('vet.lost-found.verifications', compact('claims', 'filter'));
    }

    public function show(LostFoundClaim $claim)
    {
        if (!in_array($claim->status, ['under_review', 'approved', 'completed'])) {
            abort(404);
        }

        $claim->load(['lostFound.user', 'claimer', 'adminReviewer', 'vetVerifier']);
        return view('vet.lost-found.verification-details', compact('claim'));
    }

    public function verify(Request $request, LostFoundClaim $claim)
    {
        if ($claim->status !== 'under_review') {
            return response()->json([
                'success' => false,
                'message' => 'This claim cannot be verified at this stage.'
            ], 422);
        }

        $request->validate([
            'vet_notes' => 'required|string',
            'verification_status' => 'required|in:approved,rejected',
        ]);

        if ($request->verification_status === 'approved') {
            $claim->update([
                'status' => 'approved',
                'vet_verifier_id' => Auth::id(),
                'vet_verified_at' => now(),
                'vet_notes' => $request->vet_notes,
            ]);

            $message = 'Pet claim verified successfully. Awaiting final completion.';
        } else {
            $claim->update([
                'status' => 'rejected',
                'vet_verifier_id' => Auth::id(),
                'vet_verified_at' => now(),
                'vet_notes' => $request->vet_notes,
                'rejection_reason' => $request->vet_notes,
            ]);

            $message = 'Pet claim rejected.';
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function completeClaim(Request $request, LostFoundClaim $claim)
    {
        if ($claim->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'This claim is not ready for completion.'
            ], 422);
        }

        $request->validate([
            'final_notes' => 'nullable|string',
        ]);

        $claim->update([
            'status' => 'completed',
            'completed_at' => now(),
            'vet_notes' => $claim->vet_notes . "\n\nFinal Notes: " . ($request->final_notes ?? 'Pet successfully reunited with owner.'),
        ]);

        // Mark the lost/found listing as resolved
        $claim->lostFound->update([
            'is_resolved' => true,
            'status' => 'resolved',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Claim completed successfully. Pet reunited with owner!'
        ]);
    }
}
