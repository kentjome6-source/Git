<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');

            } else if ($user->role === 'vet') {
                // Check if veterinarian is verified before allowing access
                if ((bool) $user->is_verified_vet) {
                    // Redirect verified veterinarians to their dashboard
                    return redirect()->route('vet.records');
                } else {
                    // Log out unverified veterinarians and show error
                    Auth::logout();
                    return back()->with('error', 'Your account is pending verification by an administrator.');
                }
            } else {
                // Redirect users to the Multi-Pet Dashboard
                return redirect()->route('pet.multipet.index');
            }
        }

        return back()->with('error', 'Invalid email or password.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // 🔹 Registration
    public function showRegister()
    {
        return view('auth.register');
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user', // default role = pet user
        ]);

        Auth::login($user);

        // Redirect new users to the Multi-Pet Dashboard
        return redirect()->route('pet.multipet.index');
    }

    // 🔹 Veterinarian Registration
    public function showVetRegister()
    {
        return view('auth.vet-register');
    }
    
    public function vetRegister(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users',
            'password'    => 'required|string|min:6|confirmed',
            'phone'       => 'required|string|max:20',
            'address'     => 'required|string|max:500',
            'certificate' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);

        // Store the certificate image
        $certificatePath = $request->file('certificate')->store('certificates', 'public');

        $user = User::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'password'         => Hash::make($request->password),
            'role'             => 'vet', // veterinarian role
            'phone'            => $request->phone,
            'address'          => $request->address,
            'certificate_path' => $certificatePath, // Store certificate path
            'is_verified_vet'  => false, // Not verified by default
        ]);

        Auth::login($user);

        // Log out the veterinarian immediately as they need to be verified first
        Auth::logout();

        // Redirect to login with success message
        return redirect()->route('login')->with('success', 'Registration successful! Your account is pending verification by an administrator. You will receive an email when your account is approved.');
    }
}