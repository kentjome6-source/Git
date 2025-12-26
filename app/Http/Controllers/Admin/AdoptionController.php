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
            'admin_screening_rejection' => 'required_if:action,reject|string|max:1000'
        ]);
        
        $adoptionRequest->admin_screened = true;
        $adoptionRequest->admin_screening_date = now();
        $adoptionRequest->admin_screened_by = auth()->id();
        $adoptionRequest->admin_screening_notes = $validated['admin_screening_notes'];
        
        if ($validated['action'] === 'approve') {
            $adoptionRequest->status = 'vet_orientation';
        } else {
            $adoptionRequest->status = 'admin_rejected';
            $adoptionRequest->admin_screening_rejection = $validated['admin_screening_rejection'];
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
}