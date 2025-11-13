<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of appointments for the authenticated user
     */
    public function index()
    {
        $appointments = Appointment::where('user_id', Auth::id())
            ->with(['pet', 'vet'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.appointment.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment request
     */
    public function create()
    {
        $pets = Pet::where('user_id', Auth::id())->get();
        $vets = User::where('role', 'vet')->where('is_verified_vet', true)->get();
        
        return view('user.appointment.create', compact('pets', 'vets'));
    }

    /**
     * Store a newly created appointment request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_type' => 'required|in:appointment',
            'urgency_level' => 'required|in:low,medium,high,emergency',
            'vet_id' => 'required|exists:users,id,role,vet,is_verified_vet,1',
            
            // Owner Information
            'owner_name' => 'required|string|max:255',
            'owner_phone' => 'required|string|max:20',
            'owner_email' => 'required|email|max:255',
            'owner_address' => 'nullable|string',
            
            // Pet Information
            'pet_name' => 'required|string|max:255',
            'pet_species' => 'required|string|max:255',
            'pet_breed' => 'nullable|string|max:255',
            'pet_age_years' => 'nullable|numeric|min:0|max:30', // Changed from pet_age to pet_age_years
            'pet_weight' => 'nullable|numeric|min:0|max:999.99',
            'pet_gender' => 'nullable|in:male,female,unknown',
            
            // Appointment Details
            'chief_complaint' => 'required|string',
            'detailed_symptoms' => 'required|string',
            'consultation_reason' => 'required|in:routine_checkup,illness,injury,vaccination,behavioral,other',
            'additional_concerns' => 'nullable|string',
            
            // Duration of Symptoms
            'symptom_duration_days' => 'nullable|integer|min:0',
            'symptom_onset' => 'required|in:sudden,gradual,intermittent',
            'symptom_progression' => 'nullable|string',
            
            // Previous Medications / Treatments
            'current_medications' => 'nullable|string',
            'previous_treatments' => 'nullable|string',
            'allergies' => 'nullable|string',
            'vaccination_history' => 'nullable|string',
            'previous_medical_history' => 'nullable|string',
            
            // Scheduling
            'scheduled_datetime' => 'nullable|date|after:now',
            'appointment_date' => 'nullable|date',
            'appointment_time' => 'nullable|date_format:H:i',
        ]);

        // Create appointment without creating a new pet record
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        // Prepare appointment data
        $appointmentData = [
            'user_id' => $validated['user_id'],
            'pet_id' => null, // No pet record created, only store info in appointment
            'vet_id' => $validated['vet_id'],
            'urgency_level' => $validated['urgency_level'],
            'status' => $validated['status'],
            
            // Owner Information
            'owner_name' => $validated['owner_name'],
            'owner_phone' => $validated['owner_phone'],
            'owner_email' => $validated['owner_email'],
            'owner_address' => $validated['owner_address'],
            
            // Pet Information
            'pet_name' => $validated['pet_name'],
            'pet_species' => $validated['pet_species'],
            'pet_breed' => $validated['pet_breed'],
            'pet_age_years' => $validated['pet_age_years'], // Changed from $validated['pet_age'] to $validated['pet_age_years']
            // Removed pet_age_months as it doesn't exist in the database
            'pet_weight' => $validated['pet_weight'],
            'pet_gender' => $validated['pet_gender'] ?? 'unknown',
            
            // Appointment Details
            'chief_complaint' => $validated['chief_complaint'],
            'detailed_symptoms' => $validated['detailed_symptoms'],
            'consultation_reason' => $validated['consultation_reason'],
            'additional_concerns' => $validated['additional_concerns'],
            
            // Duration of Symptoms
            'symptom_duration_days' => $validated['symptom_duration_days'],
            'symptom_onset' => $validated['symptom_onset'],
            'symptom_progression' => $validated['symptom_progression'],
            
            // Previous Medications / Treatments
            'current_medications' => $validated['current_medications'],
            'previous_treatments' => $validated['previous_treatments'],
            'allergies' => $validated['allergies'],
            'vaccination_history' => $validated['vaccination_history'],
            'previous_medical_history' => $validated['previous_medical_history'],
            
            // Scheduling
            'scheduled_datetime' => $validated['scheduled_datetime'],
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
        ];

        $appointment = Appointment::create($appointmentData);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment request submitted successfully!');
    }

    /**
     * Display the specified appointment
     */
    public function show(Appointment $appointment)
    {
        // Ensure user can only view their own appointments or if they're a vet
        if ($appointment->user_id !== Auth::id() && Auth::user()->role !== 'vet') {
            abort(403, 'Unauthorized access to appointment.');
        }

        $appointment->load(['pet', 'vet', 'user']);
        
        return view('user.appointment.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment
     */
    public function edit(Appointment $appointment)
    {
        // Only allow editing if appointment is pending and user owns it
        if ($appointment->user_id !== Auth::id() || $appointment->status !== 'pending') {
            abort(403, 'Cannot edit this appointment.');
        }

        $pets = Pet::where('user_id', Auth::id())->get();
        $vets = User::where('role', 'vet')->where('is_verified_vet', true)->get();
        
        return view('user.appointment.edit', compact('appointment', 'pets', 'vets'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        Gate::authorize('update-appointment', $appointment);

        $validated = $request->validate([
            'appointment_type' => 'required|in:appointment',
            'urgency_level' => 'required|in:low,medium,high,emergency',
            'vet_id' => 'required|exists:users,id,role,vet,is_verified_vet,1',
            
            // Owner Information
            'owner_name' => 'required|string|max:255',
            'owner_phone' => 'required|string|max:20',
            'owner_email' => 'required|email|max:255',
            'owner_address' => 'nullable|string',
            
            // Pet Information
            'pet_name' => 'required|string|max:255',
            'pet_species' => 'required|string|max:255',
            'pet_breed' => 'nullable|string|max:255',
            'pet_age_years' => 'nullable|numeric|min:0|max:30', // Changed from pet_age to pet_age_years
            'pet_weight' => 'nullable|numeric|min:0|max:999.99',
            'pet_gender' => 'nullable|in:male,female,unknown',
            
            // Appointment Details
            'chief_complaint' => 'required|string',
            'detailed_symptoms' => 'required|string',
            'consultation_reason' => 'required|in:routine_checkup,illness,injury,vaccination,behavioral,other',
            'additional_concerns' => 'nullable|string',
            
            // Duration of Symptoms
            'symptom_duration_days' => 'nullable|integer|min:0',
            'symptom_onset' => 'required|in:sudden,gradual,intermittent',
            'symptom_progression' => 'nullable|string',
            
            // Previous Medications / Treatments
            'current_medications' => 'nullable|string',
            'previous_treatments' => 'nullable|string',
            'allergies' => 'nullable|string',
            'vaccination_history' => 'nullable|string',
            'previous_medical_history' => 'nullable|string',
            
            // Scheduling
            'scheduled_datetime' => 'nullable|date|after:now',
            'appointment_date' => 'nullable|date',
            'appointment_time' => 'nullable|date_format:H:i',
        ]);

        // Prepare appointment data
        $appointmentData = [
            'urgency_level' => $validated['urgency_level'],
            'vet_id' => $validated['vet_id'],
            
            // Owner Information
            'owner_name' => $validated['owner_name'],
            'owner_phone' => $validated['owner_phone'],
            'owner_email' => $validated['owner_email'],
            'owner_address' => $validated['owner_address'],
            
            // Pet Information
            'pet_name' => $validated['pet_name'],
            'pet_species' => $validated['pet_species'],
            'pet_breed' => $validated['pet_breed'],
            'pet_age_years' => $validated['pet_age_years'], // Changed from $validated['pet_age'] to $validated['pet_age_years']
            // Removed pet_age_months as it doesn't exist in the database
            'pet_weight' => $validated['pet_weight'],
            'pet_gender' => $validated['pet_gender'] ?? 'unknown',
            
            // Appointment Details
            'chief_complaint' => $validated['chief_complaint'],
            'detailed_symptoms' => $validated['detailed_symptoms'],
            'consultation_reason' => $validated['consultation_reason'],
            'additional_concerns' => $validated['additional_concerns'],
            
            // Duration of Symptoms
            'symptom_duration_days' => $validated['symptom_duration_days'],
            'symptom_onset' => $validated['symptom_onset'],
            'symptom_progression' => $validated['symptom_progression'],
            
            // Previous Medications / Treatments
            'current_medications' => $validated['current_medications'],
            'previous_treatments' => $validated['previous_treatments'],
            'allergies' => $validated['allergies'],
            'vaccination_history' => $validated['vaccination_history'],
            'previous_medical_history' => $validated['previous_medical_history'],
            
            // Scheduling
            'scheduled_datetime' => $validated['scheduled_datetime'],
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
        ];

        $appointment->update($appointmentData);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully!');
    }

    /**
     * Remove the specified appointment
     */
    public function destroy(Appointment $appointment)
    {
        // Only allow deletion if appointment is pending and user owns it
        if ($appointment->user_id !== Auth::id() || $appointment->status !== 'pending') {
            abort(403, 'Cannot delete this appointment.');
        }

        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment request deleted successfully.');
    }

    /**
     * Vet dashboard to view and manage appointment requests
     */
    public function vetIndex()
    {
        // Only vets can access this
        if (Auth::user()->role !== 'vet') {
            abort(403, 'Access denied. Veterinarian role required.');
        }

        $appointments = Appointment::with(['user', 'pet'])
            ->orderBy('urgency_level', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user.appointment.vet-index', compact('appointments'));
    }

    /**
     * Accept an appointment request (for vets)
     */
    public function accept(Request $request, Appointment $appointment)
    {
        if (Auth::user()->role !== 'vet') {
            abort(403, 'Access denied.');
        }

        // Check if appointment is already accepted or assigned to another vet
        if ($appointment->status !== 'pending' || ($appointment->vet_id && $appointment->vet_id !== Auth::id())) {
            return redirect()->back()->with('error', 'This appointment cannot be accepted.');
        }

        // Accept the appointment without requiring scheduling details upfront
        $appointment->update([
            'status' => 'accepted',
            'vet_id' => Auth::id(),
            'accepted_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Appointment accepted successfully!');
    }

    /**
     * Reject an appointment request (for vets)
     */
    public function reject(Request $request, Appointment $appointment)
    {
        if (Auth::user()->role !== 'vet') {
            abort(403, 'Access denied.');
        }

        // Check if appointment is already accepted or assigned to another vet
        if ($appointment->status !== 'pending' || ($appointment->vet_id && $appointment->vet_id !== Auth::id())) {
            return redirect()->back()->with('error', 'This appointment cannot be rejected.');
        }

        // Reject the appointment
        $appointment->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Appointment rejected successfully!');
    }

    /**
     * Update appointment status
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        if (Auth::user()->role !== 'vet' || $appointment->vet_id !== Auth::id()) {
            abort(403, 'Access denied.');
        }

        $validated = $request->validate([
            'status' => 'required|in:accepted,in_progress,completed,cancelled',
        ]);

        $appointment->update($validated);

        return redirect()->back()->with('success', 'Appointment updated successfully!');
    }
}