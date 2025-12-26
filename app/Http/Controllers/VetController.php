<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VetController extends Controller
{
    /**
     * Show appointments for veterinarians
     */
    public function appointments()
    {
        // Only show pending appointments assigned to this specific veterinarian
        // Accepted or rejected appointments should not appear here
        $appointments = Appointment::where('vet_id', Auth::id())
            ->where('status', 'pending')
            ->with(['user', 'pet'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('vet.appointment-management.index', compact('appointments'));
    }

    /**
     * Show appointment records for veterinarians (accepted/rejected appointments)
     */
    public function appointmentRecords(Request $request)
    {
        // Get search query
        $search = $request->input('search');

        // Only show accepted or rejected appointments assigned to this specific veterinarian
        $query = Appointment::where('vet_id', Auth::id())
            ->whereIn('status', ['accepted', 'rejected'])
            ->with(['user', 'pet'])
            ->orderBy('created_at', 'desc');

        // Apply search filter if provided
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('pet_name', 'LIKE', "%{$search}%")
                  ->orWhere('owner_name', 'LIKE', "%{$search}%")
                  ->orWhere('pet_type', 'LIKE', "%{$search}%")
                  ->orWhere('pet_services_received', 'LIKE', "%{$search}%");
            });
        }

        $appointments = $query->paginate(15)->appends(['search' => $search]);

        return view('vet.appointment-records.index', compact('appointments', 'search'));
    }
    
    /**
     * Show a specific appointment record
     */
    public function show(Appointment $appointment)
    {
        // Ensure the appointment belongs to this vet
        if ($appointment->vet_id !== Auth::id()) {
            abort(403, 'Unauthorized access to appointment.');
        }
        
        $appointment->load(['user', 'pet']);
        return view('vet.appointment-records.show', compact('appointment'));
    }
    
    /**
     * View a specific appointment record in detail
     */
    public function viewRecord($id)
    {
        $appointment = Appointment::findOrFail($id);
        
        // Ensure the appointment belongs to this vet
        if ($appointment->vet_id !== Auth::id()) {
            abort(403, 'Unauthorized access to appointment.');
        }
        
        $appointment->load(['user', 'pet']);
        return view('vet.appointment-records.show', compact('appointment'));
    }
    
    /**
     * Show all records (alias for appointmentRecords)
     */
    public function records(Request $request)
    {
        return $this->appointmentRecords($request);
    }
    
    /**
     * Show pending adoption listings that need vet certification
     */
    public function pendingCertifications()
    {
        $adoptions = \App\Models\Adoption::where('listing_status', 'vet_review')
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('vet.adoptions.certifications', compact('adoptions'));
    }
    
    /**
     * Certify a pet for adoption
     */
    public function certifyPet(Request $request, \App\Models\Adoption $adoption)
    {
        if ($adoption->listing_status !== 'vet_review') {
            return redirect()->back()->with('error', 'This listing is not pending vet review.');
        }
        
        $request->validate([
            'vet_health_notes' => 'required|string|max:2000'
        ]);
        
        $adoption->vet_id = Auth::id();
        $adoption->vet_certified = true;
        $adoption->vet_certification_date = now();
        $adoption->vet_health_notes = $request->vet_health_notes;
        $adoption->listing_status = 'admin_review';
        $adoption->save();
        
        return redirect()->route('vet.adoptions.pending')->with('success', 'Pet certified successfully! Listing sent to admin for approval.');
    }
    
    /**
     * Reject a pet listing
     */
    public function rejectPetListing(Request $request, \App\Models\Adoption $adoption)
    {
        if ($adoption->listing_status !== 'vet_review') {
            return redirect()->back()->with('error', 'This listing is not pending vet review.');
        }
        
        $request->validate([
            'vet_rejection_reason' => 'required|string|max:1000'
        ]);
        
        $adoption->vet_id = Auth::id();
        $adoption->vet_certified = false;
        $adoption->vet_rejection_reason = $request->vet_rejection_reason;
        $adoption->listing_status = 'vet_rejected';
        $adoption->save();
        
        return redirect()->route('vet.adoptions.pending')->with('success', 'Listing rejected.');
    }
    
    /**
     * Show pending adopter orientations
     */
    public function pendingOrientations()
    {
        $requests = \App\Models\AdoptionRequest::where('status', 'vet_orientation')
            ->with(['adoption.user', 'adopter'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('vet.adoptions.orientations', compact('requests'));
    }
    
    /**
     * Conduct adopter orientation
     */
    public function conductOrientation(Request $request, \App\Models\AdoptionRequest $adoptionRequest)
    {
        if ($adoptionRequest->status !== 'vet_orientation') {
            return redirect()->back()->with('error', 'This application is not pending vet orientation.');
        }
        
        $request->validate([
            'vet_orientation_notes' => 'required|string|max:2000'
        ]);
        
        $adoptionRequest->vet_orientation_completed = true;
        $adoptionRequest->vet_orientation_date = now();
        $adoptionRequest->vet_orientation_by = Auth::id();
        $adoptionRequest->vet_orientation_notes = $request->vet_orientation_notes;
        $adoptionRequest->status = 'owner_review';
        $adoptionRequest->save();
        
        return redirect()->route('vet.adoptions.orientations')->with('success', 'Orientation completed! Application sent to pet owner for review.');
    }
    
    /**
     * Provide final medical clearance for adoption
     */
    public function provideFinalClearance(Request $request, \App\Models\AdoptionAgreement $agreement)
    {
        if (!$agreement->admin_certificate_issued) {
            return redirect()->back()->with('error', 'Admin certificate must be issued first.');
        }
        
        if ($agreement->vet_final_clearance) {
            return redirect()->back()->with('error', 'Final clearance already provided.');
        }
        
        $request->validate([
            'vet_final_clearance_notes' => 'required|string|max:2000'
        ]);
        
        $agreement->vet_final_clearance = true;
        $agreement->vet_final_clearance_date = now();
        $agreement->vet_final_clearance_by = Auth::id();
        $agreement->vet_final_clearance_notes = $request->vet_final_clearance_notes;
        $agreement->save();
        
        return redirect()->back()->with('success', 'Final medical clearance provided successfully!');
    }
}