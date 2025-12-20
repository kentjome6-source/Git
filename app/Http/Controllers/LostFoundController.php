<?php

namespace App\Http\Controllers;

use App\Models\LostFound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LostFoundController extends Controller
{
    public function index(Request $request)
    {
        $query = LostFound::with('user')
            ->where('is_resolved', false);

        // Apply filter if provided
        if ($request->has('filter') && $request->filter !== 'all') {
            $query->where('type', $request->filter);
        }

        // Apply sorting
        if ($request->has('sort') && $request->sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $lostFoundItems = $query->paginate(12);

        return view('user.lost-found.index', compact('lostFoundItems'));
    }

    public function map()
    {
        $lostFoundItems = LostFound::with('user')
            ->where('is_resolved', false)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.lost-found.map', compact('lostFoundItems'));
    }

    public function create()
    {
        return view('user.lost-found.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:lost,found',
            'pet_name' => 'required|string|max:255',
            'pet_type' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'size' => 'nullable|in:small,medium,large',
            'age' => 'nullable|integer|min:0|max:30',
            'gender' => 'required|in:male,female,unknown',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'date_lost_found' => 'required|date',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif'
        ]);

        // Explicitly specify the fields to include instead of using $request->all()
        $data = $request->only([
            'type',
            'pet_name',
            'pet_type',
            'breed',
            'color',
            'size',
            'age',
            'gender',
            'description',
            'location',
            'latitude',
            'longitude',
            'date_lost_found',
            'contact_name',
            'contact_phone',
            'contact_email',
        ]);
        
        $data['user_id'] = Auth::id();

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('lost-found-images', 'public');
            $data['image_path'] = $imagePath;
        }

        LostFound::create($data);

        // Support AJAX requests for modal
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your ' . $request->type . ' pet listing has been submitted successfully!'
            ]);
        }

        return redirect()->route('pet.lostfound')
            ->with('success', 'Your ' . $request->type . ' pet listing has been submitted successfully!');
    }

    public function show(LostFound $lostFound)
    {
        return view('user.lost-found.show', compact('lostFound'));
    }

    public function edit(LostFound $lostFound)
    {
        // Check if user owns this listing
        if ($lostFound->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('user.lost-found.edit', compact('lostFound'));
    }

    public function update(Request $request, LostFound $lostFound)
    {
        // Check if user owns this listing
        if ($lostFound->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'type' => 'required|in:lost,found',
            'pet_name' => 'required|string|max:255',
            'pet_type' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'size' => 'nullable|in:small,medium,large',
            'age' => 'nullable|integer|min:0|max:30',
            'gender' => 'required|in:male,female,unknown',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'date_lost_found' => 'required|date',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif'
        ]);

        // Explicitly specify the fields to include instead of using $request->all()
        $data = $request->only([
            'type',
            'pet_name',
            'pet_type',
            'breed',
            'color',
            'size',
            'age',
            'gender',
            'description',
            'location',
            'latitude',
            'longitude',
            'date_lost_found',
            'contact_name',
            'contact_phone',
            'contact_email',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($lostFound->image_path) {
                Storage::disk('public')->delete($lostFound->image_path);
            }
            $imagePath = $request->file('image')->store('lost-found-images', 'public');
            $data['image_path'] = $imagePath;
        }

        $lostFound->update($data);

        // Support AJAX requests for modal
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your ' . $request->type . ' pet listing has been updated successfully!'
            ]);
        }

        return redirect()->route('pet.lostfound')
            ->with('success', 'Your ' . $request->type . ' pet listing has been updated successfully!');
    }

    public function destroy(LostFound $lostFound)
    {
        // Check if user owns this listing
        if ($lostFound->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete image if exists
        if ($lostFound->image_path) {
            try {
                Storage::disk('public')->delete($lostFound->image_path);
            } catch (\Exception $e) {
                // Log the error but don't stop the process
                \Log::error('Image deletion failed: ' . $e->getMessage());
            }
        }

        $lostFound->delete();

        return redirect()->route('pet.lostfound')
            ->with('success', 'Your listing has been deleted successfully!');
    }

    public function markResolved(LostFound $lostFound)
    {
        // Check if user owns this listing
        if ($lostFound->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $lostFound->update(['is_resolved' => true]);

        return redirect()->route('pet.lostfound')
            ->with('success', 'Listing marked as resolved!');
    }

    public function myListings()
    {
        $myListings = LostFound::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.lost-found.my-listings', compact('myListings'));
    }
}