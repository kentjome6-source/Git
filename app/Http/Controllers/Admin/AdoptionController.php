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
}