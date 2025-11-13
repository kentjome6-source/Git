<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PetUserController extends Controller
{
    // Show all pet users
    public function index()
    {
        $users = User::where('role', 'pet')->get();
        return view('admin.pet-users.index', compact('users'));
    }

    // Show form to create pet user
    public function create()
    {
        return view('admin.pet-users.create');
    }

    // Store new pet user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pet',
        ]);

        return redirect()->route('pet-users.index')->with('success', 'Pet user created successfully.');
    }

    // Show edit form
    public function edit(User $petUser)
    {
        return view('admin.pet-users.edit', compact('petUser'));
    }

    // Update pet user
    public function update(Request $request, User $petUser)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$petUser->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $petUser->name = $request->name;
        $petUser->email = $request->email;
        if($request->password){
            $petUser->password = Hash::make($request->password);
        }
        $petUser->role = 'pet'; // ensure role stays pet
        $petUser->save();

        return redirect()->route('pet-users.index')->with('success', 'Pet user updated successfully.');
    }

    // Delete pet user
    public function destroy(User $petUser)
    {
        $petUser->delete();
        return redirect()->route('pet-users.index')->with('success', 'Pet user deleted successfully.');
    }
}
