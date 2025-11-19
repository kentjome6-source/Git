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
}