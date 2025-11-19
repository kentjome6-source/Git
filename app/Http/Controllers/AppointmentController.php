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
     * Only shows pending and accepted appointments
     */
    public function index()
    {
        $appointments = Appointment::where('user_id', Auth::id())
            ->where('status', 'pending')
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
            'vet_id' => 'required|exists:users,id',

            // Owner Information (simplified)
            'owner_name' => 'required|string|max:255',
            'owner_phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'owner_address' => 'nullable|string',

            // Pet Information (simplified)
            'pet_name' => 'required|string|max:255',
            'pet_type' => 'required|in:Dog,Cat',
            'pet_services_received' => 'nullable|string',
            
            // Scheduling (optional)
            'preferred_date' => 'nullable|date',
            'preferred_time' => 'nullable|date_format:H:i',
        ]);

        $appointmentData = [
            'user_id' => Auth::id(),
            'vet_id' => $validated['vet_id'],
            'status' => 'pending',

            // Owner
            'owner_name' => $validated['owner_name'],
            'owner_phone' => $validated['owner_phone'],
            'email' => $validated['email'],
            'owner_address' => $validated['owner_address'] ?? null,

            // Pet
            'pet_name' => $validated['pet_name'],
            'pet_type' => $validated['pet_type'],
            'pet_services_received' => $validated['pet_services_received'] ?? null,

            // Scheduling
            'scheduled_datetime' => isset($validated['preferred_date']) && isset($validated['preferred_time']) ? 
                $validated['preferred_date'] . ' ' . $validated['preferred_time'] . ':00' : null,
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
            'vet_id' => 'required|exists:users,id',

            // Owner Information (simplified)
            'owner_name' => 'required|string|max:255',
            'owner_phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'owner_address' => 'nullable|string',

            // Pet Information (simplified)
            'pet_name' => 'required|string|max:255',
            'pet_type' => 'required|in:Dog,Cat',
            'pet_services_received' => 'nullable|string',
            
            // Scheduling (optional)
            'preferred_date' => 'nullable|date',
            'preferred_time' => 'nullable|date_format:H:i',
        ]);

        // Prepare appointment data (simplified)
        $appointmentData = [
            'vet_id' => $validated['vet_id'],

            // Owner Information
            'owner_name' => $validated['owner_name'],
            'owner_phone' => $validated['owner_phone'],
            'email' => $validated['email'],
            'owner_address' => $validated['owner_address'] ?? null,

            // Pet Information
            'pet_name' => $validated['pet_name'],
            'pet_type' => $validated['pet_type'],
            'pet_services_received' => $validated['pet_services_received'] ?? null,

            // Scheduling
            'scheduled_datetime' => isset($validated['preferred_date']) && isset($validated['preferred_time']) ? 
                $validated['preferred_date'] . ' ' . $validated['preferred_time'] . ':00' : null,
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

        // Only show pending appointments to vets
        $appointments = Appointment::where('status', 'pending')
            ->with(['user', 'pet'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user.appointment.vet-index', compact('appointments'));
    }

    /**
     * Display appointment history for the authenticated user
     * Shows accepted and rejected appointments
     */
    public function history(Request $request)
    {
        // Only show accepted or rejected appointments for this specific user
        $appointments = Appointment::where('user_id', Auth::id())
            ->whereIn('status', ['accepted', 'rejected'])
            ->with(['vet'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user.appointment-history.index', compact('appointments'));
    }

    /**
     * Display the specified appointment in history context
     */
    public function showHistory(Appointment $appointment)
    {
        // Ensure user can only view their own appointments
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to appointment.');
        }

        $appointment->load(['pet', 'vet', 'user']);
        return view('user.appointment-history.show', compact('appointment'));
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

        // Validate the request to include rejection reason
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        // Check if appointment is already accepted or assigned to another vet
        if ($appointment->status !== 'pending' || ($appointment->vet_id && $appointment->vet_id !== Auth::id())) {
            return redirect()->back()->with('error', 'This appointment cannot be rejected.');
        }

        // Reject the appointment with reason
        $appointment->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => Auth::id(),
            'rejection_reason' => $validated['rejection_reason'],
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