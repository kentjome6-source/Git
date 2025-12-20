<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    /**
     * Display user's own pets
     */
    public function myPets()
    {
        $pets = Pet::where('user_id', Auth::id())
                   ->orderBy('created_at', 'desc')
                   ->get();
        
        return view('user.my-pets.index', compact('pets'));
    }

    /**
     * Track a specific pet on the map
     */
    public function trackPet(Pet $pet)
    {
        // Ensure the pet belongs to the authenticated user
        if ($pet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to pet.');
        }

        return view('user.my-pets.track', compact('pet'));
    }

    /**
     * Show report missing form for a pet
     */
    public function reportMissing(Pet $pet)
    {
        // Ensure the pet belongs to the authenticated user
        if ($pet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to pet.');
        }

        return view('user.my-pets.report-missing', compact('pet'));
    }

    /**
     * Display a listing of the user's pets.
     */
    public function index()
    {
        // Show all pets with their users
        $pets = Pet::with(['user'])
                  ->orderBy('name')
                  ->get();
        
        return view('user.multi-pet.index', compact('pets'));
    }

    /**
     * Show the form for creating a new pet.
     */
    public function create()
    {
        return view('user.multi-pet.create');
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
        ]);

        $pet = new Pet();
        $pet->user_id = Auth::id();
        $pet->name = $request->input('name');
        $pet->breed = $request->input('breed');
        $pet->description = $request->input('description');

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('pet-images', 'public');
            $pet->image_path = $imagePath;
        }

        $pet->save();

        return redirect()->route('my.pets')->with('success', 'Pet added successfully!');
    }

    /**
     * Display the specified pet.
     */
    public function show(Pet $pet)
    {
        // Load the pet with its users
        $pet->load(['user']);
        
        return view('user.multi-pet.show', compact('pet'));
    }

    /**
     * Show the form for editing the specified pet.
     */
    public function edit(Pet $pet)
    {
        // Ensure the pet belongs to the authenticated user
        if ($pet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to pet.');
        }

        return view('user.multi-pet.edit', compact('pet'));
    }

    /**
     * Update the specified pet in storage.
     */
    public function update(Request $request, Pet $pet)
    {
        // Ensure the pet belongs to the authenticated user
        if ($pet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to pet.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:1000',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($pet->image_path) {
                Storage::disk('public')->delete($pet->image_path);
            }
            
            $imagePath = $request->file('image')->store('pet-images', 'public');
            $pet->image_path = $imagePath;
        }
        
        // Update pet details
        $pet->name = $request->input('name');
        $pet->breed = $request->input('breed');
        $pet->description = $request->input('description');

        $pet->save();

        // Support AJAX requests for modal
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pet updated successfully!',
                'data' => $pet
            ]);
        }

        return redirect()->route('my.pets')->with('success', 'Pet updated successfully!');
    }

    /**
     * Remove the specified pet from storage.
     */
    public function destroy(Pet $pet)
    {
        // Ensure the pet belongs to the authenticated user
        if ($pet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to pet.');
        }

        // Delete image if exists
        if ($pet->image_path) {
            Storage::disk('public')->delete($pet->image_path);
        }

        $pet->delete();

        return redirect()->route('my.pets')->with('success', 'Pet deleted successfully!');
    }
}