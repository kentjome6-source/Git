<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;


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
                if ((bool) $user->is_verified_vet) {
                    return redirect()->route('vet.appointments');
                } else {
                    Auth::logout();
                    return back()->with('error', 'Your account is pending verification by an administrator.');
                }
            } else {
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
            'role'     => 'user',
        ]);

        Auth::login($user);
        return redirect()->route('pet.multipet.index');
    }

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
            'certificate' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $certificatePath = $request->file('certificate')->store('certificates', 'public');

        $user = User::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'password'         => Hash::make($request->password),
            'role'             => 'vet',
            'phone'            => $request->phone,
            'address'          => $request->address,
            'certificate_path' => $certificatePath,
            'is_verified_vet'  => false,
        ]);

        Auth::login($user);

        Auth::logout();

        return redirect()->route('login')->with('success', 'Registration successful! Your account is pending verification by an administrator. You will receive an email when your account is approved.');
    }

    public function redirectToGoogle()
    {
        // Store a fresh state in the session
        $state = Str::random(40);
        session()->put('state', $state);
        
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())->first();
            
            if (!$user) {
                $user = User::where('email', $googleUser->getEmail())->first();
                
                if ($user) {
                    $user->update(['google_id' => $googleUser->getId()]);
                } else {
                    // For new users, we'll save the Google avatar URL directly
                    // The User model will handle displaying it correctly
                    $avatarUrl = $googleUser->getAvatar();
                    
                    // Google sometimes provides avatar URLs with size parameters
                    // We can modify it to get a better size for our application
                    if ($avatarUrl) {
                        // Remove any existing size parameters and set to 400px
                        $avatarUrl = preg_replace('/\?sz=\d+$/', '', $avatarUrl);
                        $avatarUrl .= '?sz=400'; // Set size to 400px
                    }
                    
                    $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'role' => 'user',
                        'profile_picture_path' => $avatarUrl, // Store the full URL
                    ]);
                }
            }
            
            Auth::login($user);

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else if ($user->role === 'vet') {
                if ((bool) $user->is_verified_vet) {
                    return redirect()->route('vet.appointments');
                } else {
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'Your account is pending verification by an administrator.');
                }
            } else {
                return redirect()->route('pet.multipet.index');
            }
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            // Handle state mismatch specifically - redirect back to Google login
            return redirect()->route('auth.google');
        } catch (\Exception $e) {
            // Log the actual error for debugging
            \Log::error('Google authentication error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Google authentication failed. Please try again.');
        }
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
