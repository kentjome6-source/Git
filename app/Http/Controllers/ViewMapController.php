<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vetshop;
use App\Models\LostFound;
use Illuminate\Support\Facades\Auth;

class ViewMapController extends Controller
{
    /**
     * Display map page with available shelters and lost/found pets
     */
    public function index(Request $request)
    {
        // Show all active shelters in San Francisco, Agusan Del Sur
        $shelters = Vetshop::active()
            ->where('city', 'San Francisco')
            ->where('province', 'Agusan Del Sur')
            ->get();
            
        // Show all unresolved lost/found pets with coordinates
        $lostFoundItems = LostFound::with('user')
            ->where('is_resolved', false)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();
        
        // Check if we need to focus on a specific shelter
        $focusShelterId = $request->query('shelter');
        $focusShelter = null;
        
        if ($focusShelterId) {
            $focusShelter = $shelters->firstWhere('id', $focusShelterId);
        }
        
        return view('user.view-map.index', compact('shelters', 'lostFoundItems', 'focusShelter'));
    }
    
    /**
     * Show shelter details for users
     */
    public function show($id)
    {
        // Find shelter by ID
        $shelter = Vetshop::find($id);
        
        // Ensure shelter exists
        if (!$shelter) {
            abort(404, 'Shelter not found.');
        }
        
        // Ensure shelter is active
        if (!$shelter->is_active) {
            abort(404, 'Shelter not found or inactive.');
        }
        
        // Load grooming services for grooming type shelters
        if ($shelter->type === 'grooming') {
            $shelter->load('groomingServices');
        }
        
        return view('user.view-map.show-shelter', compact('shelter'));
    }
}