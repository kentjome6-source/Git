<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        Auth::logout();                          // log the user out
        $request->session()->invalidate();       // invalidate session
        $request->session()->regenerateToken();  // regenerate CSRF token

        return redirect('/login');               // redirect to login page
    }
}
