<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adoption;
use App\Models\Pet;
use App\Models\AdoptionRequest;
use App\Models\AdoptionHistory;
use App\Models\AdoptionAgreement;
use App\Models\AdoptionFollowup;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdoptionController extends Controller
{
    /**
     * Display public homepage with adoptions and lost & found.
     */
    public function publicHome()
    {
        $adoptionPets = Adoption::with(['user', 'pet'])
                              ->where('listing_status', 'published')
                              ->where('is_adopted', false)
                              ->whereDoesntHave('adoptionRequests', function($query) {
                                  $query->where('status', 'approved');
                              })
                              ->latest()
                              ->take(6)
                              ->get();
        
        $lostFoundPets = \App\Models\LostFound::with(['user'])
                              ->where('is_resolved', false)
                              ->where('status', 'approved')
                              ->latest()
                              ->take(6)
                              ->get();
        
        return view('welcome', compact('adoptionPets', 'lostFoundPets'));
    }

    /**
     * Display a listing of pets available for adoption.
     */
    public function index()
    {
        $adoptionPets = Adoption::with(['user'])
                              ->where('listing_status', 'published')
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
        $adoption->uploader_type = 'user';
        $adoption->pet_name = $request->pet_name;
        $adoption->breed = $request->breed;
        $adoption->age = $request->age;
        $adoption->gender = $request->gender;
        $adoption->description = $request->description;
        $adoption->image_path = $imagePath;
        $adoption->is_adopted = false;
        $adoption->listing_status = 'vet_review'; // Start with vet review
        $adoption->save();



        // Support AJAX requests for modal
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pet listed for adoption successfully!',
                'data' => $adoption
            ]);
        }

        return redirect()->route('adoptions.index')->with('success', 'Pet listed for adoption! Your listing will be reviewed by a veterinarian first.');
    }

    /**
     * Display the specified adoption listing.
     */
    public function show(Adoption $adoption)
    {
        $userId = Auth::id();
        $isOwner = $adoption->user_id == $userId;
        $isAdopter = $adoption->adoptionRequests()->where('adopter_id', $userId)->exists();
        $isAdmin = Auth::user() && Auth::user()->role === 'admin';
        
        // Allow pet owners to view their own adoptions
        if ($isOwner) {
            $adoption->load(['user', 'pet', 'adoptionRequests.adopter', 'adoptionRequests.vetOrientation', 'adoptionRequests.adminScreening', 'adoptionRequests.agreement']);
            return view('user.adoptions.show', compact('adoption', 'isOwner', 'isAdopter', 'isAdmin'));
        }
        
        // Allow admin to view any adoption
        if ($isAdmin) {
            $adoption->load(['user', 'pet', 'adoptionRequests.adopter', 'adoptionRequests.vetOrientation', 'adoptionRequests.adminScreening', 'adoptionRequests.agreement']);
            return view('user.adoptions.show', compact('adoption', 'isOwner', 'isAdopter', 'isAdmin'));
        }
        
        // Allow adopters to view adoptions they applied for
        if ($isAdopter) {
            $adoption->load(['user', 'pet', 'adoptionRequests.adopter', 'adoptionRequests.vetOrientation', 'adoptionRequests.adminScreening', 'adoptionRequests.agreement']);
            return view('user.adoptions.show', compact('adoption', 'isOwner', 'isAdopter', 'isAdmin'));
        }
        
        // Allow any authenticated user to view published adoptions that are available
        if ($adoption->listing_status === 'published' && !$adoption->is_adopted) {
            $adoption->load(['user', 'pet', 'adoptionRequests.adopter', 'adoptionRequests.vetOrientation', 'adoptionRequests.adminScreening', 'adoptionRequests.agreement']);
            return view('user.adoptions.show', compact('adoption', 'isOwner', 'isAdopter', 'isAdmin'));
        }
        
        abort(403, 'This action is unauthorized.');
    }

    /**
     * Process adoption request.
     */
    public function adopt(Request $request, Adoption $adoption)
    {
        // Prevent pet owners from adopting their own pets
        if ($adoption->user_id == Auth::id()) {
            return redirect()->back()->with('error', 'You cannot adopt your own pet.');
        }
        
        // Check if the pet is already adopted
        if ($adoption->is_adopted) {
            return redirect()->back()->with('error', 'This pet has already been adopted.');
        }
        
        // Check if there's already a pending adoption request from this user
        $existingRequest = AdoptionRequest::where('adoption_id', $adoption->id)
            ->where('adopter_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->first();
            
        if ($existingRequest) {
            return redirect()->back()->with('error', 'You already have a ' . $existingRequest->status . ' request for this pet.');
        }
        
        // Check if there's already an approved adoption request from another user
        if ($adoption->hasApprovedRequest()) {
            return redirect()->back()->with('error', 'This pet has already been approved for adoption.');
        }

        // Validate adoption application form
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'housing_type' => 'required|in:house,apartment,condo,other',
            'has_yard' => 'required|boolean',
            'own_or_rent' => 'required|in:own,rent',
            'current_pets' => 'nullable|string|max:1000',
            'veterinarian_info' => 'nullable|string|max:500',
            'experience_with_pets' => 'nullable|string|max:1000',
            'reason_for_adoption' => 'required|string|max:1000',
            'hours_alone' => 'nullable|integer|min:0|max:24',
            'agree_to_home_visit' => 'required|boolean',
            'additional_info' => 'nullable|string|max:1000',
        ]);

        // Create adoption request with complete application details
        $adoptionRequest = new AdoptionRequest();
        $adoptionRequest->adoption_id = $adoption->id;
        $adoptionRequest->adopter_id = Auth::id();
        $adoptionRequest->status = 'pending';
        $adoptionRequest->requested_at = now();
        $adoptionRequest->fill($validated);
        $adoptionRequest->save();

        return redirect()->route('adoptions.my-applications')->with('success', 'Your adoption request is in process. The admin will screen your application and a veterinarian will conduct orientation.');
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
        
        // Get the adoption request awaiting owner review
        $adoptionRequest = $adoption->adoptionRequests()
            ->where('status', 'owner_review')
            ->first();
        
        // Check if there's an adoption request awaiting review
        if (!$adoptionRequest) {
            return redirect()->back()->with('error', 'There is no adoption request awaiting your review.');
        }
        
        // Validate application completeness
        if (!$adoptionRequest->isComplete()) {
            return redirect()->back()->with('error', 'The adoption application is incomplete.');
        }

        // Update adoption request to owner_approved status (awaiting admin final approval)
        $adoptionRequest->status = 'owner_approved';
        $adoptionRequest->owner_approved = true;
        $adoptionRequest->owner_approval_date = now();
        $adoptionRequest->responded_at = now();
        $adoptionRequest->save();
        
        // Create adoption agreement
        $agreement = new AdoptionAgreement();
        $agreement->adoption_request_id = $adoptionRequest->id;
        $agreement->adoption_id = $adoption->id;
        $agreement->owner_id = $adoption->user_id;
        $agreement->adopter_id = $adoptionRequest->adopter_id;
        $agreement->terms_and_conditions = $this->getDefaultTermsAndConditions();
        $agreement->adoption_fee = 0; // Can be set by owner
        $agreement->save();

        return redirect()->route('adoptions.show', $adoption)->with('success', 'Adoption request approved! Awaiting admin final approval and agreement processing.');
    }
    
    /**
     * Reject adoption request.
     */
    public function rejectAdoption(Request $request, Adoption $adoption)
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
        
        // Validate rejection reason
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        // Update adoption request to rejected status
        $adoptionRequest->status = 'rejected';
        $adoptionRequest->responded_at = now();
        $adoptionRequest->rejection_reason = $request->rejection_reason;
        $adoptionRequest->save();

        return redirect()->route('adoptions.show', $adoption)->with('success', 'Adoption request has been rejected.');
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
        
        // Check if agreement is signed by both parties
        $agreement = $adoptionRequest->agreement;
        if (!$agreement || !$agreement->isFullySigned()) {
            return redirect()->back()->with('error', 'The adoption agreement must be signed by both parties before completion.');
        }
        
        // Check if payment is completed (if there's a fee)
        if ($agreement->adoption_fee > 0 && !$agreement->payment_completed) {
            return redirect()->back()->with('error', 'The adoption fee must be paid before completion.');
        }
        
        // Check if admin certificate is issued
        if (!$agreement->admin_certificate_issued) {
            return redirect()->back()->with('error', 'Admin must issue the adoption certificate before completion.');
        }
        
        // Check if vet final clearance is provided
        if (!$agreement->vet_final_clearance) {
            return redirect()->back()->with('error', 'Veterinarian must provide final medical clearance before completion.');
        }
        
        // Complete the adoption
        $adoption->is_adopted = true;
        $adoption->listing_status = 'adopted';
        $adoption->save();
        
        // Create adoption history record
        $adoptionHistory = new AdoptionHistory();
        $adoptionHistory->adoption_id = $adoption->id;
        $adoptionHistory->uploader_id = $adoption->user_id;
        $adoptionHistory->adopter_id = $adoptionRequest->adopter_id;
        $adoptionHistory->adopted_at = now();
        $adoptionHistory->save();
        
        // Schedule follow-up checks
        $this->scheduleFollowups($adoptionHistory);

        return redirect()->route('adoptions.history')->with('success', 'Congratulations! Adoption completed successfully. Follow-up checks have been scheduled.');
    }
    
    /**
     * Schedule follow-up checks after adoption.
     */
    private function scheduleFollowups(AdoptionHistory $adoptionHistory)
    {
        $followupSchedule = [
            ['type' => '1_week', 'days' => 7],
            ['type' => '1_month', 'days' => 30],
            ['type' => '3_months', 'days' => 90],
            ['type' => '6_months', 'days' => 180],
            ['type' => '1_year', 'days' => 365],
        ];
        
        foreach ($followupSchedule as $schedule) {
            AdoptionFollowup::create([
                'adoption_history_id' => $adoptionHistory->id,
                'followup_type' => $schedule['type'],
                'scheduled_date' => now()->addDays($schedule['days']),
                'completed' => false
            ]);
        }
    }
    
    /**
     * Get default terms and conditions for adoption agreement.
     */
    private function getDefaultTermsAndConditions()
    {
        return "ADOPTION AGREEMENT TERMS AND CONDITIONS\n\n" .
               "1. The adopter agrees to provide proper care, nutrition, and veterinary care for the pet.\n" .
               "2. The adopter agrees to keep the pet in a safe and healthy environment.\n" .
               "3. The adopter agrees to not abuse, neglect, or abandon the pet.\n" .
               "4. The adopter agrees to comply with all local animal control laws and regulations.\n" .
               "5. The adopter agrees to allow follow-up visits to ensure the pet's welfare.\n" .
               "6. The adopter understands that this is a lifetime commitment to the pet.\n" .
               "7. If the adopter can no longer care for the pet, they agree to contact the original owner first.\n" .
               "8. The adopter agrees to have the pet spayed/neutered if not already done.\n" .
               "9. The adopter agrees to keep the pet's vaccinations and health records up to date.\n" .
               "10. Both parties agree to act in the best interest of the pet's welfare.";
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

        // Support AJAX requests for modal
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Adoption post updated successfully!',
                'data' => $adoption
            ]);
        }

        return redirect()->route('adoptions.show', $adoption)->with('success', 'Adoption post updated successfully!');
    }

    /**
     * Display adoption history for the authenticated user.
     */
    public function history()
    {
        // Get all adoptions owned by the user (pets they listed)
        $myListings = Adoption::with(['adoptionRequests.adopter'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        // Get adoption history where the user adopted a pet
        $adoptedPets = AdoptionHistory::with(['adoption', 'uploader'])
            ->where('adopter_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('user.adoptions.history', compact('myListings', 'adoptedPets'));
    }

    /**
     * Display the user's adoption applications.
     */
    public function myApplications()
    {
        $applications = AdoptionRequest::with(['adoption.user', 'adoption.vet', 'adopter'])
            ->where('adopter_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('user.adoptions.my-applications', compact('applications'));
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
    
    /**
     * Sign adoption agreement.
     */
    public function signAgreement(Request $request, AdoptionAgreement $agreement)
    {
        $request->validate([
            'signature' => 'required|string|max:255'
        ]);
        
        $userId = Auth::id();
        
        // Check if user is owner or adopter
        if ($agreement->owner_id == $userId && !$agreement->owner_signed) {
            $agreement->owner_signed = true;
            $agreement->owner_signed_at = now();
            $agreement->owner_signature = $request->signature;
            $agreement->save();
            
            return redirect()->back()->with('success', 'You have successfully signed the adoption agreement.');
        } elseif ($agreement->adopter_id == $userId && !$agreement->adopter_signed) {
            $agreement->adopter_signed = true;
            $agreement->adopter_signed_at = now();
            $agreement->adopter_signature = $request->signature;
            $agreement->save();
            
            return redirect()->back()->with('success', 'You have successfully signed the adoption agreement.');
        }
        
        return redirect()->back()->with('error', 'You are not authorized to sign this agreement or it has already been signed.');
    }
    
    /**
     * View adoption agreement.
     */
    public function viewAgreement(AdoptionAgreement $agreement)
    {
        $userId = Auth::id();
        
        // Only owner and adopter can view
        if ($agreement->owner_id != $userId && $agreement->adopter_id != $userId) {
            return redirect()->back()->with('error', 'You are not authorized to view this agreement.');
        }
        
        return view('user.adoptions.agreement', compact('agreement'));
    }
    
    /**
     * Update adoption fee.
     */
    public function updateAdoptionFee(Request $request, AdoptionAgreement $agreement)
    {
        // Only owner can update fee
        if ($agreement->owner_id != Auth::id()) {
            return redirect()->back()->with('error', 'Only the pet owner can set the adoption fee.');
        }
        
        // Cannot update if already signed
        if ($agreement->owner_signed || $agreement->adopter_signed) {
            return redirect()->back()->with('error', 'Cannot update fee after agreement has been signed.');
        }
        
        $request->validate([
            'adoption_fee' => 'required|numeric|min:0|max:99999.99'
        ]);
        
        $agreement->adoption_fee = $request->adoption_fee;
        $agreement->save();
        
        return redirect()->back()->with('success', 'Adoption fee updated successfully.');
    }
    
    /**
     * Mark payment as completed.
     */
    public function markPaymentCompleted(AdoptionAgreement $agreement)
    {
        // Only owner can mark payment as completed
        if ($agreement->owner_id != Auth::id()) {
            return redirect()->back()->with('error', 'Only the pet owner can confirm payment.');
        }
        
        $agreement->payment_completed = true;
        $agreement->save();
        
        return redirect()->back()->with('success', 'Payment confirmed successfully.');
    }
    
    /**
     * View adoption application details.
     */
    public function viewApplication(AdoptionRequest $request)
    {
        $userId = Auth::id();
        $adoption = $request->adoption;
        
        // Only owner and applicant can view
        if ($adoption->user_id != $userId && $request->adopter_id != $userId) {
            return redirect()->back()->with('error', 'You are not authorized to view this application.');
        }
        
        return view('user.adoptions.application', compact('request'));
    }
    
    /**
     * Complete a follow-up check.
     */
    public function completeFollowup(Request $request, AdoptionFollowup $followup)
    {
        $adoptionHistory = $followup->adoptionHistory;
        
        // Only original owner can complete followup
        if ($adoptionHistory->uploader_id != Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to complete this follow-up.');
        }
        
        $request->validate([
            'pet_status' => 'required|in:excellent,good,fair,poor,returned',
            'health_status' => 'nullable|string|max:1000',
            'behavioral_status' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'requires_attention' => 'boolean'
        ]);
        
        $followup->completed = true;
        $followup->completed_at = now();
        $followup->pet_status = $request->pet_status;
        $followup->health_status = $request->health_status;
        $followup->behavioral_status = $request->behavioral_status;
        $followup->notes = $request->notes;
        $followup->requires_attention = $request->requires_attention ?? false;
        $followup->save();
        
        return redirect()->route('adoptions.history')->with('success', 'Follow-up check completed successfully.');
    }
    
    /**
     * View all pending followups for user's adopted pets.
     */
    public function viewFollowups()
    {
        $userId = Auth::id();
        
        // Get all adoption histories where user is the original owner
        $adoptionHistories = AdoptionHistory::where('uploader_id', $userId)
            ->with(['followups' => function($query) {
                $query->orderBy('scheduled_date', 'asc');
            }, 'adoption', 'adopter'])
            ->get();
        
        return view('user.adoptions.followups', compact('adoptionHistories'));
    }
    
    /**
     * Owner approves adoption request.
     */
    public function ownerApprove(AdoptionRequest $adoptionRequest)
    {
        // Check if the current user is the pet owner
        if ($adoptionRequest->adoption->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to approve this request.'
            ], 403);
        }
        
        // Check if request is in owner_review status
        if ($adoptionRequest->status !== 'owner_review') {
            return response()->json([
                'success' => false,
                'message' => 'This request is not ready for owner approval.'
            ], 400);
        }
        
        // Update status to owner_approved
        $adoptionRequest->status = 'owner_approved';
        $adoptionRequest->owner_approval_date = now();
        $adoptionRequest->save();
        
        return response()->json([
            'success' => true,
            'message' => 'You have approved this adoption application. It will now proceed to final admin approval.'
        ]);
    }
    
    /**
     * Owner rejects adoption request.
     */
    public function ownerReject(Request $request, AdoptionRequest $adoptionRequest)
    {
        // Check if the current user is the pet owner
        if ($adoptionRequest->adoption->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to reject this request.'
            ], 403);
        }
        
        // Update status to rejected
        $adoptionRequest->status = 'rejected';
        $adoptionRequest->rejection_reason = $request->input('rejection_reason', 'Rejected by pet owner');
        $adoptionRequest->save();
        
        return response()->json([
            'success' => true,
            'message' => 'You have rejected this adoption application.'
        ]);
    }
}