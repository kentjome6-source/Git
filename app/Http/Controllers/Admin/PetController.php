<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    /**
     * Display a listing of pets.
     */
    public function index()
    {
        $pets = Pet::with(['user'])->latest()->get();
        return view('admin.pets.index', compact('pets'));
    }

    /**
     * Show the form for creating a new pet.
     */
    public function create()
    {
        return view('admin.pets.create');
    }

    /**
     * Store a newly created pet in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:1000',
            'appropriate_food' => 'nullable|string|max:1000',
            'other_care_details' => 'nullable|string|max:1000',
        ]);

        $pet = new Pet();
        // Use the authenticated admin user as the owner
        $pet->user_id = Auth::id();
        $pet->name = $request->name;
        $pet->breed = $request->breed;
        $pet->description = $request->description;
        $pet->appropriate_food = $request->appropriate_food;
        $pet->other_care_details = $request->other_care_details;

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('pet-images', 'public');
            $pet->image_path = $imagePath;
        }

        $pet->save();

        return redirect()->route('admin.pets.index')->with('success', 'Pet added successfully!');
    }

    /**
     * Display the specified pet.
     */
    public function show(Pet $pet)
    {
        $pet->load(['user']);
        return view('admin.pets.show', compact('pet'));
    }

    /**
     * Show the form for editing the specified pet.
     */
    public function edit(Pet $pet)
    {
        return view('admin.pets.edit', compact('pet'));
    }

    /**
     * Update the specified pet in storage.
     */
    public function update(Request $request, Pet $pet)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:1000',
            'appropriate_food' => 'nullable|string|max:1000',
            'other_care_details' => 'nullable|string|max:1000',
        ]);

        $pet->name = $request->name;
        $pet->breed = $request->breed;
        $pet->description = $request->description;
        $pet->appropriate_food = $request->appropriate_food;
        $pet->other_care_details = $request->other_care_details;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($pet->image_path) {
                Storage::disk('public')->delete($pet->image_path);
            }
            
            $imagePath = $request->file('image')->store('pet-images', 'public');
            $pet->image_path = $imagePath;
        }

        $pet->save();

        return redirect()->route('admin.pets.index')->with('success', 'Pet updated successfully!');
    }

    /**
     * Remove the specified pet from storage.
     */
    public function destroy(Pet $pet)
    {
        // Delete image if exists
        if ($pet->image_path) {
            Storage::disk('public')->delete($pet->image_path);
        }

        $pet->delete();

        return redirect()->route('admin.pets.index')->with('success', 'Pet deleted successfully!');
    }
}