<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adoption;
use App\Models\Pet;
use App\Models\AdoptionRequest;
use App\Models\AdoptionHistory;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdoptionController extends Controller
{
    /**
     * Display a listing of pets available for adoption.
     */
    public function index()
    {
        $adoptionPets = Adoption::with(['user'])
                              ->where('is_adopted', false)
                              ->whereDoesntHave('adoptionRequests', function($query) {
                                  $query->where('status', 'approved');
                              })
                              ->latest()
                              ->get();
        
        return view('user.adoptions.index', compact('adoptionPets'));
    }

    /**
     * Show the form for creating a new adoption listing.
     */
    public function create()
    {
        return view('user.adoptions.create');
    }

    /**
     * Store a new adoption listing in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pet_name' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0',
            'gender' => 'nullable|string|in:male,female',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('adoption-images', 'public');
        }

        $adoption = new Adoption();
        $adoption->user_id = Auth::id();
        $adoption->uploader_type = 'user'; // Pet parent is uploading
        $adoption->pet_name = $request->pet_name;
        $adoption->breed = $request->breed;
        $adoption->age = $request->age;
        $adoption->gender = $request->gender;
        $adoption->description = $request->description;
        $adoption->image_path = $imagePath;
        $adoption->is_adopted = false;
        $adoption->save();



        return redirect()->route('adoptions.index')->with('success', 'Pet listed for adoption successfully!');
    }

    /**
     * Display the specified adoption listing.
     */
    public function show(Adoption $adoption)
    {
        $adoption->load(['user', 'pet', 'adoptionRequests.adopter']);
        
        return view('user.adoptions.show', compact('adoption'));
    }

    /**
     * Process adoption request.
     */
    public function adopt(Adoption $adoption)
    {
        // Prevent pet owners from adopting their own pets
        if ($adoption->user_id == Auth::id()) {
            return redirect()->back()->with('error', 'You cannot adopt your own pet.');
        }
        
        // Check if the pet is already adopted
        if ($adoption->is_adopted) {
            return redirect()->back()->with('error', 'This pet has already been adopted.');
        }
        
        // Check if there's already a pending adoption request
        if ($adoption->hasPendingRequest()) {
            return redirect()->back()->with('error', 'There is already an adoption request for this pet.');
        }
        
        // Check if there's already an approved adoption request
        if ($adoption->hasApprovedRequest()) {
            return redirect()->back()->with('error', 'This pet has already been approved for adoption.');
        }

        // Create adoption request
        $adoptionRequest = new AdoptionRequest();
        $adoptionRequest->adoption_id = $adoption->id;
        $adoptionRequest->adopter_id = Auth::id();
        $adoptionRequest->status = 'pending';
        $adoptionRequest->requested_at = now();
        $adoptionRequest->save();

        return redirect()->route('adoptions.index')->with('success', 'Adoption request submitted successfully! Awaiting approval from the pet owner.');
    }
    
    /**
     * Approve adoption request.
     */
    public function approveAdoption(Adoption $adoption)
    {
        // Check if the current user is the pet owner
        if ($adoption->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to approve this adoption request.');
        }
        
        // Get the pending adoption request
        $adoptionRequest = $adoption->pendingRequest();
        
        // Check if there's a pending adoption request
        if (!$adoptionRequest) {
            return redirect()->back()->with('error', 'There is no pending adoption request for this pet.');
        }

        // Update adoption request to approved status
        $adoptionRequest->status = 'approved';
        $adoptionRequest->responded_at = now();
        $adoptionRequest->save();
        
        // Create adoption history record when approved
        $adoptionHistory = new AdoptionHistory();
        $adoptionHistory->adoption_id = $adoption->id;
        $adoptionHistory->uploader_id = $adoption->user_id;
        $adoptionHistory->adopter_id = $adoptionRequest->adopter_id;
        $adoptionHistory->adopted_at = now();
        $adoptionHistory->save();

        return redirect()->route('adoptions.index')->with('success', 'Adoption request approved successfully!');
    }
    
    /**
     * Reject adoption request.
     */
    public function rejectAdoption(Adoption $adoption)
    {
        // Check if the current user is the pet owner
        if ($adoption->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to reject this adoption request.');
        }
        
        // Get the pending adoption request
        $adoptionRequest = $adoption->pendingRequest();
        
        // Check if there's a pending adoption request
        if (!$adoptionRequest) {
            return redirect()->back()->with('error', 'There is no pending adoption request for this pet.');
        }

        // Update adoption request to rejected status
        $adoptionRequest->status = 'rejected';
        $adoptionRequest->responded_at = now();
        $adoptionRequest->save();

        return redirect()->route('adoptions.index')->with('success', 'Adoption request rejected successfully!');
    }
    
    /**
     * Complete adoption process.
     */
    public function completeAdoption(Adoption $adoption)
    {
        // Get the approved adoption request
        $adoptionRequest = $adoption->adoptionRequests()->where('status', 'approved')->first();
        
        // Check if the current user is the adopter
        if (!$adoptionRequest || $adoptionRequest->adopter_id != Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to complete this adoption.');
        }
        
        // Complete the adoption
        $adoption->is_adopted = true;
        $adoption->save();

        return redirect()->route('adoptions.index')->with('success', 'Adoption completed successfully!');
    }

    /**
     * Show the form for editing the specified adoption listing.
     */
    public function edit(Adoption $adoption)
    {
        // Check if the current user is the owner of the adoption post
        if ($adoption->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to edit this adoption post.');
        }
        
        return view('user.adoptions.edit', compact('adoption'));
    }

    /**
     * Update the specified adoption listing in storage.
     */
    public function update(Request $request, Adoption $adoption)
    {
        // Check if the current user is the owner of the adoption post
        if ($adoption->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to update this adoption post.');
        }
        
        $request->validate([
            'pet_name' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0',
            'gender' => 'nullable|string|in:male,female',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($adoption->image_path) {
                Storage::disk('public')->delete($adoption->image_path);
            }
            
            $imagePath = $request->file('image')->store('adoption-images', 'public');
            $adoption->image_path = $imagePath;
        }

        $adoption->pet_name = $request->pet_name;
        $adoption->breed = $request->breed;
        $adoption->age = $request->age;
        $adoption->gender = $request->gender;
        $adoption->description = $request->description;
        $adoption->save();

        return redirect()->route('adoptions.show', $adoption)->with('success', 'Adoption post updated successfully!');
    }

    /**
     * Display adoption history for the authenticated user.
     */
    public function history()
    {
        // Get adoption history where the user is either the uploader or adopter
        $adoptedPetsAsUploader = AdoptionHistory::with(['adoption', 'adopter'])
            ->where('uploader_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        $adoptedPetsAsAdopter = AdoptionHistory::with(['adoption', 'uploader'])
            ->where('adopter_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Get adoption listings that the user has uploaded but not yet adopted
        $uploadedPets = Adoption::with(['adoptionRequests'])
            ->where('user_id', Auth::id())
            ->whereDoesntHave('adoptionHistory')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('user.adoptions.history', compact('adoptedPetsAsUploader', 'adoptedPetsAsAdopter', 'uploadedPets'));
    }

    /**
     * Remove the specified adoption post from storage.
     */
    public function destroy(Adoption $adoption)
    {
        // Check if the current user is the owner of the adoption post
        if ($adoption->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to delete this adoption post.');
        }
        
        // Check if there are any pending or approved adoption requests
        if ($adoption->adoptionRequests()->whereIn('status', ['pending', 'approved'])->exists()) {
            return redirect()->back()->with('error', 'Cannot delete adoption post with pending or approved requests.');
        }
        
        // Delete the adoption post
        $adoption->delete();
        
        return redirect()->route('adoptions.index')->with('success', 'Adoption post deleted successfully!');
    }
}