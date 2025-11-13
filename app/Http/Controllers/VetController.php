<?php

namespace App\Http\Controllers;

use App\Models\PetHealthRecord;
use App\Models\Treatment;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VetController extends Controller
{
    // Access all pet health records
    public function records()
    {
        $records = PetHealthRecord::with('user')->orderBy('name')->get();
        return view('vet.access-to-pet-health.index', compact('records'));
    }

    // Show single record with advice form
    public function show($id)
    {
        $record = $this->getRecordWithTreatments($id);
        return view('vet.access-to-pet-health.show', compact('record'));
    }

    // View full record details
    public function viewRecord($id)
    {
        $record = $this->getRecordWithTreatments($id);
        return view('vet.access-to-pet-health.view-record', compact('record'));
    }

    // Show treatment creation form
    public function createTreatment($id)
    {
        $record = PetHealthRecord::with('user')->findOrFail($id);
        return view('vet.access-to-pet-health.create-treatment', compact('record'));
    }

    // Send advice to pet owner
    public function sendAdvice(Request $request, $id)
    {
        $record = PetHealthRecord::findOrFail($id);

        $request->validate([
            'vet_advice' => 'required|string',
        ]);

        $record->vet_advice = $request->vet_advice;
        $record->save();

        // Broadcast realtime advice to the owner of the record
        AdviceSent::dispatch($record);

        return redirect()->route('vet.records.show', $id)
            ->with('success', 'Advice sent to the pet owner successfully!');
    }

    /**
     * Get record with treatments
     */
    private function getRecordWithTreatments($id)
    {
        return PetHealthRecord::with(['treatments' => function ($query) {
            $query->orderBy('treatment_date', 'desc');
        }, 'user'])->findOrFail($id);
    }

    public function addTreatment(Request $request, $id)
    {
        $record = PetHealthRecord::findOrFail($id);

        $validated = $request->validate([
            'treatment_date' => 'nullable|date',
            'title' => 'required|string|max:255',
            'medication' => 'nullable|string|max:255',
            'dosage' => 'nullable|string|max:255',
            'frequency' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Treatment::create([
            'pet_health_record_id' => $record->id,
            'vet_id' => Auth::id(),
            'treatment_date' => $validated['treatment_date'] ?? now()->toDateString(),
            'title' => $validated['title'],
            'medication' => $validated['medication'] ?? null,
            'dosage' => $validated['dosage'] ?? null,
            'frequency' => $validated['frequency'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('vet.records')
            ->with('success', 'Treatment added successfully!');
    }

    /**
     * Show the form for editing a treatment
     */
    public function editTreatment(Treatment $treatment)
    {
        // Ensure the vet can only edit their own treatments
        if ($treatment->vet_id !== Auth::id()) {
            return redirect()->route('vet.records')
                ->with('error', 'You can only edit treatments you created.');
        }

        $record = $treatment->record;
        return view('vet.access-to-pet-health.edit-treatment', compact('treatment', 'record'));
    }

    /**
     * Update a treatment
     */
    public function updateTreatment(Request $request, Treatment $treatment)
    {
        // Ensure the vet can only update their own treatments
        if ($treatment->vet_id !== Auth::id()) {
            return redirect()->route('vet.records')
                ->with('error', 'You can only edit treatments you created.');
        }

        $validated = $request->validate([
            'treatment_date' => 'nullable|date',
            'title' => 'required|string|max:255',
            'medication' => 'nullable|string|max:255',
            'dosage' => 'nullable|string|max:255',
            'frequency' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $treatment->update($validated);

        return redirect()->route('vet.records')
            ->with('success', 'Treatment updated successfully!');
    }

    /**
     * Show appointments for veterinarians
     */
    public function appointments()
    {
        // Only show appointments assigned to this specific veterinarian
        $appointments = Appointment::where('vet_id', Auth::id())
            ->with(['user', 'pet'])
            ->orderBy('urgency_level', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('vet.appointment-management.index', compact('appointments'));
    }
}