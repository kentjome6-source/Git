<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureVetIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and is a veterinarian
        if ($request->user() && $request->user()->role === 'vet') {
            // Check if veterinarian is verified
            if (!(bool) $request->user()->is_verified_vet) {
                // Redirect to login with error message
                return redirect()->route('login')->with('error', 'Your account is pending verification by an administrator.');
            }
        }

        return $next($request);
    }
}