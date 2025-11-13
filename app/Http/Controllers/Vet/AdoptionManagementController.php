<?php

namespace App\Http\Controllers\Vet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Adoption;
use App\Models\AdoptionRequest;
use App\Models\AdoptionHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdoptionManagementController extends Controller
{
    /**
     * Display adoption listings that can be viewed by vets.
     * This includes both vet uploads and pet parent uploads.
     */
    public function index()
    {
        // Get all adoption listings that are not yet adopted
        $adoptionPets = Adoption::with(['user'])
                              ->where('is_adopted', false)
                              ->whereDoesntHave('adoptionRequests', function($query) {
                                  $query->where('status', 'approved');
                              })
                              ->latest()
                              ->get();
        
        return view('vet.adoptions.management', compact('adoptionPets'));
    }

    /**
     * Show the form for creating a new adoption listing by a vet.
     */
    public function create()
    {
        return view('vet.adoptions.create');
    }

    /**
     * Store a new adoption listing uploaded by a vet.
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
        $adoption->uploader_type = 'vet'; // Vet is uploading
        $adoption->pet_name = $request->pet_name;
        $adoption->breed = $request->breed;
        $adoption->age = $request->age;
        $adoption->gender = $request->gender;
        $adoption->description = $request->description;
        $adoption->image_path = $imagePath;
        $adoption->is_adopted = false;
        $adoption->save();

        return redirect()->route('vet.adoptions.management.index')->with('success', 'Pet listed for adoption successfully!');
    }

    /**
     * Display the specified adoption listing.
     */
    public function show(Adoption $adoption)
    {
        $adoption->load(['user', 'adoptionRequests.adopter']);
        
        return view('vet.adoptions.show', compact('adoption'));
    }

    /**
     * Process adoption request by a vet.
     */
    public function adopt(Adoption $adoption)
    {
        // Vets should not be able to adopt pets, only list them for adoption
        return redirect()->back()->with('error', 'Vets can only list pets for adoption, not adopt pets.');
    }
    
    /**
     * Approve adoption request by a vet.
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

        return redirect()->back()->with('success', 'Adoption request approved successfully!');
    }
    
    /**
     * Reject adoption request by a vet.
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

        return redirect()->back()->with('success', 'Adoption request rejected successfully!');
    }
    
    /**
     * Complete adoption process by a vet.
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

        return redirect()->route('vet.adoptions.management.index')->with('success', 'Adoption completed successfully!');
    }

    /**
     * Remove the adoption listing from storage (only for vet's own listings).
     */
    public function destroy(Adoption $adoption)
    {
        // Ensure the adoption listing was uploaded by this vet
        if ($adoption->user_id !== Auth::id() || $adoption->uploader_type !== 'vet') {
            abort(403, 'Unauthorized access to adoption listing.');
        }
        
        // Check if the pet has already been adopted
        if ($adoption->is_adopted) {
            return redirect()->back()->with('error', 'Cannot remove adoption listing for an already adopted pet.');
        }

        // Delete image if it exists
        if ($adoption->image_path) {
            Storage::disk('public')->delete($adoption->image_path);
        }

        $adoption->delete();

        return redirect()->route('vet.adoptions.management.index')->with('success', 'Adoption listing removed successfully!');
    }
}