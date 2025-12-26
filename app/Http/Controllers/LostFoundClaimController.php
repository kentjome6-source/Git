<?php

namespace App\Http\Controllers;

use App\Models\LostFound;
use App\Models\LostFoundClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LostFoundClaimController extends Controller
{
    public function create(LostFound $lostFound)
    {
        // Check if listing is available for claiming
        if ($lostFound->is_resolved || $lostFound->status !== 'approved') {
            abort(404);
        }

        // Check if user already has a pending claim
        $existingClaim = LostFoundClaim::where('lost_found_id', $lostFound->id)
            ->where('claimer_id', Auth::id())
            ->whereIn('status', ['pending', 'under_review'])
            ->first();

        if ($existingClaim) {
            return redirect()->back()->with('error', 'You already have a pending claim for this pet.');
        }

        return view('user.lost-found.claim', compact('lostFound'));
    }

    public function store(Request $request, LostFound $lostFound)
    {
        $request->validate([
            'proof_description' => 'required|string|min:50',
            'proof_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'identification_info' => 'nullable|string',
        ]);

        // Check if user already has a pending claim
        $existingClaim = LostFoundClaim::where('lost_found_id', $lostFound->id)
            ->where('claimer_id', Auth::id())
            ->whereIn('status', ['pending', 'under_review'])
            ->first();

        if ($existingClaim) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a pending claim for this pet.'
            ], 422);
        }

        $proofImages = [];
        if ($request->hasFile('proof_images')) {
            foreach ($request->file('proof_images') as $image) {
                $path = $image->store('claim-proofs', 'public');
                $proofImages[] = $path;
            }
        }

        LostFoundClaim::create([
            'lost_found_id' => $lostFound->id,
            'claimer_id' => Auth::id(),
            'proof_description' => $request->proof_description,
            'proof_images' => $proofImages,
            'identification_info' => $request->identification_info,
            'status' => 'pending',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your claim has been submitted successfully. It will be reviewed by the admin.'
            ]);
        }

        return redirect()->route('pet.lostfound')->with('success', 'Your claim has been submitted successfully.');
    }

    public function myClaims()
    {
        $claims = LostFoundClaim::with(['lostFound.user', 'adminReviewer', 'vetVerifier'])
            ->where('claimer_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.lost-found.my-claims', compact('claims'));
    }

    public function showClaim(LostFoundClaim $claim)
    {
        // Check if user owns this claim or the listing
        if ($claim->claimer_id !== Auth::id() && $claim->lostFound->user_id !== Auth::id()) {
            abort(403);
        }

        $claim->load(['lostFound.user', 'claimer', 'adminReviewer', 'vetVerifier']);
        return view('user.lost-found.claim-details', compact('claim'));
    }
}
