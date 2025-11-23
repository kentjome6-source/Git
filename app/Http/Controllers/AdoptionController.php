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
        // Get verified veterinarians for appointment scheduling
        $vets = \App\Models\User::where('role', 'vet')
            ->where('is_verified_vet', true)
            ->get();
        
        return view('user.adoptions.create', compact('vets'));
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
            // Appointment validation rules (only required if scheduling appointment)
            'schedule_appointment' => 'nullable|boolean',
            'vet_id' => 'nullable|required_if:schedule_appointment,1|exists:users,id',
            'preferred_date' => 'nullable|required_if:schedule_appointment,1|date',
            'preferred_time' => 'nullable|required_if:schedule_appointment,1|date_format:H:i',
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

        // Handle appointment scheduling if requested
        if ($request->schedule_appointment) {
            // Determine pet type based on adoption data
            $petType = 'Pet';
            if ($request->breed) {
                $petType = $request->breed;
            } elseif ($request->gender) {
                $petType = ucfirst($request->gender) . ' Pet';
            }
            
            $appointmentData = [
                'user_id' => Auth::id(),
                'vet_id' => $request->vet_id,
                'status' => 'pending',
                // Owner Information
                'owner_name' => Auth::user()->name,
                'owner_phone' => Auth::user()->phone ?? '',
                'email' => Auth::user()->email,
                'owner_address' => Auth::user()->address ?? '',
                // Pet Information
                'pet_name' => $request->pet_name,
                'pet_type' => $petType,
                'pet_services_received' => 'Pre-adoption health check for ' . $request->pet_name,
                // Scheduling
                'scheduled_datetime' => isset($request->preferred_date) && isset($request->preferred_time) ? 
                    $request->preferred_date . ' ' . $request->preferred_time . ':00' : null,
            ];

            $appointment = Appointment::create($appointmentData);
            
            // Add success message about appointment
            return redirect()->route('adoptions.index')->with('success', 'Pet listed for adoption successfully! A veterinary appointment has also been scheduled.');
        }

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

        return redirect()->back()->with('success', 'Adoption request approved successfully!');
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

        return redirect()->back()->with('success', 'Adoption request rejected successfully!');
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
        
        return view('user.adoptions.history', compact('adoptedPetsAsUploader', 'adoptedPetsAsAdopter'));
    }

    /**
     * Remove the adoption listing from storage.
     */
    public function destroy(Adoption $adoption)
    {
        // Ensure the adoption listing belongs to the authenticated user
        if ($adoption->user_id !== Auth::id()) {
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

        return redirect()->route('adoptions.index')->with('success', 'Adoption listing removed successfully!');
    }
}