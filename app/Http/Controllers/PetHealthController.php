<?php

namespace App\Http\Controllers;

use App\Models\PetHealthRecord;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetHealthController extends Controller
{
    public function index()
    {
        $records = PetHealthRecord::where('user_id', Auth::id())->get();
        return view('user.pet-health.index', compact('records'));
    }

    public function create()
    {
        return view('user.pet-health.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:30',
            'weight' => 'required|numeric|min:0.1|max:200',
            'condition' => 'nullable|string|max:255',
            'medical_notes' => 'nullable|string',
            'diagnosed_at' => 'nullable|date',
            'vaccine_name' => 'nullable|string|max:255',
            'date_given' => 'nullable|date',
            'next_due' => 'nullable|date|after_or_equal:date_given',
            'vaccine_status' => 'nullable|string|max:255',
        ]);

        // Clean and prepare data
        $data = [
            'user_id' => Auth::id(),
            'name' => trim($request->name),
            'species' => trim($request->species),
            'breed' => trim($request->breed),
            'age' => $request->age,
            'weight' => $request->weight,
            'condition' => $request->condition ? trim($request->condition) : null,
            'medical_notes' => $request->medical_notes ? trim($request->medical_notes) : null,
            'diagnosed_at' => $request->diagnosed_at,
            'vaccine_name' => $request->vaccine_name ? trim($request->vaccine_name) : null,
            'date_given' => $request->date_given,
            'next_due' => $request->next_due,
            'vaccine_status' => $request->vaccine_status,
        ];

        PetHealthRecord::create($data);

        return redirect()->route('pet.health')->with('success', 'Pet health record saved successfully! All information is now available to veterinarians.');
    }

    public function show($id)
    {
        $record = $this->getRecordWithTreatments($id);
        return view('user.pet-health.show', compact('record'));
    }

    /**
     * Helper method to get record with treatments (removes duplication)
     */
    private function getRecordWithTreatments($id)
    {
        return PetHealthRecord::with(['treatments' => function ($q) {
            $q->latest();
        }])->findOrFail($id);
    }

    public function edit($id)
    {
        $record = PetHealthRecord::findOrFail($id);
        return view('user.pet-health.edit', compact('record'));
    }

    public function update(Request $request, $id)
    {
        $record = PetHealthRecord::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'species' => 'nullable|string',
            'breed' => 'nullable|string',
            'age' => 'nullable|integer',
            'weight' => 'nullable|numeric',
            'condition' => 'nullable|string',
            'medical_notes' => 'nullable|string',
            'diagnosed_at' => 'nullable|date',
            'vaccine_name' => 'nullable|string',
            'date_given' => 'nullable|date',
            'next_due' => 'nullable|date',
            'vaccine_status' => 'nullable|string',
        ]);

        $data = $request->only([
            'name', 'species', 'breed', 'age', 'weight',
            'condition', 'medical_notes', 'diagnosed_at',
            'vaccine_name', 'date_given', 'next_due', 'vaccine_status'
        ]);

        $record->update($data);

        return redirect()->route('pet.health')->with('success', 'Record updated successfully!');
    }

    public function destroy($id)
    {
        $record = PetHealthRecord::findOrFail($id);
        $record->delete();

        return redirect()->route('pet.health')->with('success', 'Record deleted successfully!');
    }
}