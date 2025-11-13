<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shelter;
use Illuminate\Validation\Rule;

class MapController extends Controller
{
    public function index(Request $request)
    {
        $query = Shelter::query();
        
        // Filter by San Francisco, Agusan Del Sur by default
        $query->where('city', 'San Francisco')
              ->where('province', 'Agusan Del Sur');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%")
                  ->orWhere('city', 'LIKE', "%{$search}%");
            });
        }
        
        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $shelters = $query->orderBy('created_at', 'desc')
                          ->paginate(15);
        
        // Get stats
        $stats = [
            'total_locations' => Shelter::where('city', 'San Francisco')
                                       ->where('province', 'Agusan Del Sur')
                                       ->count(),
            'pet_shops' => Shelter::where('type', 'pet_shop')
                                 ->where('city', 'San Francisco')
                                 ->where('province', 'Agusan Del Sur')
                                 ->count(),
            'veterinarians' => Shelter::where('type', 'veterinarian')
                                     ->where('city', 'San Francisco')
                                     ->where('province', 'Agusan Del Sur')
                                     ->count(),
            'grooming_services' => Shelter::where('type', 'grooming')
                                         ->where('city', 'San Francisco')
                                         ->where('province', 'Agusan Del Sur')
                                         ->count(),
            'active_locations' => Shelter::where('is_active', true)
                                        ->where('city', 'San Francisco')
                                        ->where('province', 'Agusan Del Sur')
                                        ->count(),
        ];
        
        return view('admin.map.index', compact('shelters', 'stats'));
    }

    public function show(Shelter $shelter)
    {
        // Ensure shelter is in San Francisco, Agusan Del Sur
        if ($shelter->city !== 'San Francisco' || $shelter->province !== 'Agusan Del Sur') {
            abort(404, 'Shelter not found.');
        }
        
        return view('admin.map.show', compact('shelter'));
    }

    public function destroy(Shelter $shelter)
    {
        // Ensure shelter is in San Francisco, Agusan Del Sur
        if ($shelter->city !== 'San Francisco' || $shelter->province !== 'Agusan Del Sur') {
            abort(404, 'Shelter not found.');
        }
        
        $shelter->delete();
        
        return redirect()->route('admin.map.index')
                        ->with('success', 'Location deleted successfully!');
    }
    
    public function toggleStatus(Shelter $shelter)
    {
        // Ensure shelter is in San Francisco, Agusan Del Sur
        if ($shelter->city !== 'San Francisco' || $shelter->province !== 'Agusan Del Sur') {
            abort(404, 'Shelter not found.');
        }
        
        $shelter->update([
            'is_active' => !$shelter->is_active
        ]);
        
        $status = $shelter->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('admin.map.index')
                        ->with('success', "Location {$status} successfully!");
    }
}