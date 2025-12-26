<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Adoption;
use App\Models\User;

class AdoptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:isAdmin');
    }
    
    /**
     * Display adoption history for all users.
     */
    public function index()
    {
        // Get all adoption records with related data
        $adoptions = Adoption::with(['user', 'pet', 'adoptionHistory.adopter'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.adoptions.index', compact('adoptions'));
    }
    
    /**
     * Display the specified adoption.
     */
    public function show(Adoption $adoption)
    {
        $adoption->load(['user', 'pet', 'adoptionHistory.adopter']);
        return view('admin.adoptions.show', compact('adoption'));
    }
    
    /**
     * Show the form for creating a new adoption.
     */
    public function create()
    {
        return view('admin.adoptions.create');
    }
    
    /**
     * Store a newly created adoption in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pet_name' => 'required|string|max:255',
            'pet_type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $adoption = new Adoption();
        $adoption->user_id = auth()->id();
        $adoption->pet_name = $request->pet_name;
        $adoption->pet_type = $request->pet_type;
        $adoption->description = $request->description;
        $adoption->uploader_type = 'admin';
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('adoption-images', 'public');
            $adoption->image_path = $imagePath;
        }
        
        $adoption->save();
        
        return redirect()->route('admin.adoptions.index')
                        ->with('success', 'Adoption listing created successfully!');
    }
    
    /**
     * Show the form for editing the specified adoption.
     */
    public function edit(Adoption $adoption)
    {
        return view('admin.adoptions.edit', compact('adoption'));
    }
    
    /**
     * Update the specified adoption in storage.
     */
    public function update(Request $request, Adoption $adoption)
    {
        $request->validate([
            'pet_name' => 'required|string|max:255',
            'pet_type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $adoption->pet_name = $request->pet_name;
        $adoption->pet_type = $request->pet_type;
        $adoption->description = $request->description;
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($adoption->image_path) {
                \Storage::disk('public')->delete($adoption->image_path);
            }
            
            $imagePath = $request->file('image')->store('adoption-images', 'public');
            $adoption->image_path = $imagePath;
        }
        
        $adoption->save();
        
        return redirect()->route('admin.adoptions.index')
                        ->with('success', 'Adoption listing updated successfully!');
    }
    
    /**
     * Remove the specified adoption from storage.
     */
    public function destroy(Adoption $adoption)
    {
        // Delete image if exists
        if ($adoption->image_path) {
            \Storage::disk('public')->delete($adoption->image_path);
        }
        
        $adoption->delete();
        
        return redirect()->route('admin.adoptions.index')
                        ->with('success', 'Adoption listing deleted successfully!');
    }
    
    /**
     * Show pending listing approvals
     */
    public function pendingApprovals()
    {
        $adoptions = Adoption::where('listing_status', 'admin_review')
            ->with(['user', 'vet'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.adoptions.pending-approvals', compact('adoptions'));
    }
    
    /**
     * Approve an adoption listing
     */
    public function approveListing(Adoption $adoption)
    {
        if ($adoption->listing_status !== 'admin_review') {
            return redirect()->back()->with('error', 'This listing is not pending admin approval.');
        }
        
        if (!$adoption->vet_certified) {
            return redirect()->back()->with('error', 'Listing must be vet-certified first.');
        }
        
        $adoption->admin_approved = true;
        $adoption->admin_approval_date = now();
        $adoption->admin_approved_by = auth()->id();
        $adoption->listing_status = 'published';
        $adoption->save();
        
        return redirect()->route('admin.adoptions.pending')->with('success', 'Listing approved and published successfully!');
    }
    
    /**
     * Reject an adoption listing
     */
    public function rejectListing(Request $request, Adoption $adoption)
    {
        if ($adoption->listing_status !== 'admin_review') {
            return redirect()->back()->with('error', 'This listing is not pending admin approval.');
        }
        
        $request->validate([
            'admin_rejection_reason' => 'required|string|max:1000'
        ]);
        
        $adoption->admin_approved = false;
        $adoption->admin_rejection_reason = $request->admin_rejection_reason;
        $adoption->admin_approved_by = auth()->id();
        $adoption->listing_status = 'admin_rejected';
        $adoption->save();
        
        return redirect()->route('admin.adoptions.pending')->with('success', 'Listing rejected.');
    }
    
    /**
     * Show pending adopter screenings
     */
    public function pendingScreenings()
    {
        $adoptionRequests = \App\Models\AdoptionRequest::where('status', 'pending')
            ->with(['adoption', 'adopter'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.adoption-requests.screenings', compact('adoptionRequests'));
    }
    
    /**
     * Show adoption requests awaiting final admin approval
     */
    public function pendingFinalApproval()
    {
        $adoptionRequests = \App\Models\AdoptionRequest::where('status', 'owner_approved')
            ->with(['adoption.user', 'adopter'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.adoption-requests.final-approvals', compact('adoptionRequests'));
    }
    
    /**
     * Screen an adopter
     */
    public function screenAdopter(Request $request, \App\Models\AdoptionRequest $adoptionRequest)
    {
        if ($adoptionRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'This application is not pending screening.');
        }
        
        $validated = $request->validate([
            'admin_screening_notes' => 'required|string|max:2000',
            'action' => 'required|in:approve,reject',
            'admin_screening_rejection' => 'required_if:action,reject|nullable|string|max:1000'
        ]);
        
        $adoptionRequest->admin_screened = true;
        $adoptionRequest->admin_screening_date = now();
        $adoptionRequest->admin_screened_by = auth()->id();
        $adoptionRequest->admin_screening_notes = $validated['admin_screening_notes'];
        
        if ($validated['action'] === 'approve') {
            $adoptionRequest->status = 'vet_orientation';
        } else {
            $adoptionRequest->status = 'admin_rejected';
            $adoptionRequest->admin_screening_rejection = $validated['admin_screening_rejection'] ?? null;
        }
        
        $adoptionRequest->save();
        
        $message = $validated['action'] === 'approve' 
            ? 'Adopter approved for vet orientation!' 
            : 'Adopter application rejected.';
        
        return redirect()->route('admin.adoption-requests.screening')->with('success', $message);
    }
    
    /**
     * Schedule an interview
     */
    public function scheduleInterview(Request $request, \App\Models\AdoptionRequest $adoptionRequest)
    {
        $validated = $request->validate([
            'interview_type' => 'required|in:phone,video,in_person,home_visit',
            'scheduled_date' => 'required|date|after:now'
        ]);
        
        $interview = new \App\Models\AdoptionInterview();
        $interview->adoption_request_id = $adoptionRequest->id;
        $interview->interview_type = $validated['interview_type'];
        $interview->scheduled_date = $validated['scheduled_date'];
        $interview->conducted_by = auth()->id();
        $interview->save();
        
        return redirect()->back()->with('success', 'Interview scheduled successfully!');
    }
    
    /**
     * Conduct an interview
     */
    public function conductInterview(Request $request, \App\Models\AdoptionInterview $interview)
    {
        if ($interview->isCompleted()) {
            return redirect()->back()->with('error', 'This interview has already been completed.');
        }
        
        $validated = $request->validate([
            'interview_notes' => 'required|string|max:2000',
            'passed' => 'required|boolean'
        ]);
        
        $interview->interview_notes = $validated['interview_notes'];
        $interview->passed = $validated['passed'];
        $interview->completed_at = now();
        $interview->save();
        
        return redirect()->back()->with('success', 'Interview completed successfully!');
    }
    
    /**
     * Issue adoption certificate
     */
    public function issueCertificate(Request $request, \App\Models\AdoptionAgreement $agreement)
    {
        if (!$agreement->isFullySigned()) {
            return redirect()->back()->with('error', 'Agreement must be signed by both parties first.');
        }
        
        if ($agreement->admin_certificate_issued) {
            return redirect()->back()->with('error', 'Certificate already issued.');
        }
        
        // Generate unique certificate number
        $certificateNumber = 'ADOPT-' . date('Y') . '-' . str_pad($agreement->id, 6, '0', STR_PAD_LEFT);
        
        $agreement->admin_certificate_issued = true;
        $agreement->admin_certificate_number = $certificateNumber;
        $agreement->admin_certificate_issued_at = now();
        $agreement->admin_issued_by = auth()->id();
        $agreement->save();
        
        return redirect()->back()->with('success', 'Adoption certificate issued successfully! Certificate Number: ' . $certificateNumber);
    }
    
    /**
     * Get adoption request details
     */
    public function getRequestDetails(\App\Models\AdoptionRequest $adoptionRequest)
    {
        return response()->json([
            'full_name' => $adoptionRequest->full_name,
            'email' => $adoptionRequest->email,
            'phone' => $adoptionRequest->phone,
            'address' => $adoptionRequest->address,
            'housing_type' => $adoptionRequest->housing_type,
            'has_yard' => $adoptionRequest->has_yard,
            'own_or_rent' => $adoptionRequest->own_or_rent,
            'current_pets' => $adoptionRequest->current_pets,
            'experience_with_pets' => $adoptionRequest->experience_with_pets,
            'reason_for_adoption' => $adoptionRequest->reason_for_adoption,
            'admin_screening_notes' => $adoptionRequest->admin_screening_notes,
            'vet_orientation_notes' => $adoptionRequest->vet_orientation_notes,
        ]);
    }
    
    /**
     * Approve an adoption request
     */
    public function approveRequest(Request $request, \App\Models\AdoptionRequest $adoptionRequest)
    {
        if ($adoptionRequest->status !== 'owner_approved') {
            return response()->json([
                'success' => false,
                'message' => 'This application is not ready for final approval.'
            ], 400);
        }
        
        $validated = $request->validate([
            'admin_approval_notes' => 'nullable|string|max:2000'
        ]);
        
        $adoptionRequest->admin_final_approved = true;
        $adoptionRequest->admin_final_approval_date = now();
        $adoptionRequest->admin_final_approved_by = auth()->id();
        $adoptionRequest->admin_approval_notes = $validated['admin_approval_notes'] ?? null;
        $adoptionRequest->status = 'approved';
        $adoptionRequest->save();
        
        // Create adoption agreement if it doesn't exist
        if (!$adoptionRequest->agreement) {
            $agreement = new \App\Models\AdoptionAgreement();
            $agreement->adoption_request_id = $adoptionRequest->id;
            $agreement->adoption_id = $adoptionRequest->adoption_id;
            $agreement->owner_id = $adoptionRequest->adoption->user_id;
            $agreement->adopter_id = $adoptionRequest->adopter_id;
            $agreement->terms_and_conditions = $this->getDefaultTermsAndConditions();
            $agreement->adoption_fee = 0;
            $agreement->save();
        }
        
        // Update pet status
        $adoptionRequest->adoption->update([
            'status' => 'adopted',
            'adopted_by' => $adoptionRequest->adopter_id
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Adoption request approved successfully! Adoption agreement created.'
        ]);
    }
    
    /**
     * Reject an adoption request
     */
    public function rejectRequest(Request $request, \App\Models\AdoptionRequest $adoptionRequest)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);
        
        $adoptionRequest->status = 'rejected';
        $adoptionRequest->rejection_reason = $validated['rejection_reason'];
        $adoptionRequest->rejected_by = auth()->id();
        $adoptionRequest->rejected_at = now();
        $adoptionRequest->save();
        
        return redirect()->back()->with('success', 'Adoption request rejected.');
    }
    
    /**
     * Get default terms and conditions for adoption agreement
     */
    private function getDefaultTermsAndConditions()
    {
        return "ADOPTION AGREEMENT TERMS AND CONDITIONS\n\n" .
               "1. The adopter agrees to provide proper care, food, shelter, and medical attention to the pet.\n" .
               "2. The adopter agrees to keep the pet's vaccinations current.\n" .
               "3. The adopter will not abuse, neglect, or abandon the pet.\n" .
               "4. The adopter agrees to comply with all local animal control laws and regulations.\n" .
               "5. The adopter agrees to allow follow-up visits to ensure the pet's welfare.\n" .
               "6. If the adopter can no longer care for the pet, they agree to return the pet to the shelter.\n" .
               "7. The adopter understands this is a lifelong commitment to care for the pet.";
    }
}