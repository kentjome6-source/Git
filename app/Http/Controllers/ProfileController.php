<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        
        // Return the appropriate profile view based on user role
        switch($user->role) {
            case 'admin':
                return view('admin.profile.edit', compact('user'));
            case 'vet':
                return view('vet.profile.edit', compact('user'));
            default: // user role
                return view('user.profile.edit', compact('user'));
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'pet_name' => 'nullable|string|max:255',
            'pet_type' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Add validation for profile picture
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture_path) {
                Storage::delete($user->profile_picture_path);
            }
            
            // Store the new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture_path = $path;
        }

        $user->update($request->except('profile_picture'));

        // Redirect to the appropriate profile view based on user role
        switch($user->role) {
            case 'admin':
                return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully.');
            case 'vet':
                return redirect()->route('vet.profile.edit')->with('success', 'Profile updated successfully.');
            default: // user role
                return redirect()->route('user.profile.edit')->with('success', 'Profile updated successfully.');
        }
    }
}