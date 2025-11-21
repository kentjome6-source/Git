<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vetshop;

class MapController extends Controller
{
    public function index(Request $request)
    {
        $query = Vetshop::query();
        
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
        
        return view('admin.map.index', compact('shelters'));
    }

    public function create()
    {
        return view('admin.map.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'operating_hours' => 'nullable|array',
            'operating_hours.*' => 'nullable|string|max:100',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        // Set default values for San Francisco, Agusan Del Sur
        $validated['city'] = 'San Francisco';
        $validated['province'] = 'Agusan Del Sur';
        $validated['is_active'] = true;
        $validated['type'] = 'veterinarian';

        // Ensure operating hours are properly formatted and in the correct order
        if (isset($validated['operating_hours']) && is_array($validated['operating_hours'])) {
            // Filter out any empty values
            $validated['operating_hours'] = array_filter($validated['operating_hours']);
            
            // Ensure operating hours contain only time data, not address data
            foreach ($validated['operating_hours'] as $day => $hours) {
                // If the hours look like address data (contains common address words), skip it
                if (is_string($hours) && 
                    (stripos($hours, 'purok') !== false || 
                     stripos($hours, 'poblacion') !== false || 
                     stripos($hours, 'san francisco') !== false || 
                     stripos($hours, 'agusan') !== false || 
                     stripos($hours, 'caraga') !== false || 
                     preg_match('/\d{4}/', $hours))) {
                    // This looks like address data, not time data - remove it
                    unset($validated['operating_hours'][$day]);
                }
            }
            
            // Reorder the operating hours to match the standard sequence
            $orderedHours = [];
            $hourOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            
            foreach ($hourOrder as $day) {
                if (isset($validated['operating_hours'][$day])) {
                    $orderedHours[$day] = $validated['operating_hours'][$day];
                }
            }
            
            // Add any additional days that might not be in the standard order
            foreach ($validated['operating_hours'] as $day => $hours) {
                if (!isset($orderedHours[$day])) {
                    $orderedHours[$day] = $hours;
                }
            }
            
            $validated['operating_hours'] = $orderedHours;
        }

        $shelter = Vetshop::create($validated);

        return redirect()->route('admin.map.index')
                    ->with('success', 'Location created successfully!');
    }

    public function show(Vetshop $map)
    {
        // Ensure shelter is in San Francisco, Agusan Del Sur
        if ($map->city !== 'San Francisco' || $map->province !== 'Agusan Del Sur') {
            abort(404, 'Shelter not found.');
        }
        
        return view('admin.map.show', compact('map'));
    }

    public function edit(Vetshop $map)
    {
        // Ensure shelter is in San Francisco, Agusan Del Sur
        if ($map->city !== 'San Francisco' || $map->province !== 'Agusan Del Sur') {
            abort(404, 'Shelter not found.');
        }
        
        return view('admin.map.edit', compact('map'));
    }

    public function update(Request $request, Vetshop $map)
    {
        // Ensure shelter is in San Francisco, Agusan Del Sur
        if ($map->city !== 'San Francisco' || $map->province !== 'Agusan Del Sur') {
            abort(404, 'Shelter not found.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'operating_hours' => 'nullable|array',
            'operating_hours.*' => 'nullable|string|max:100',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        // Set default values for San Francisco, Agusan Del Sur
        $validated['city'] = 'San Francisco';
        $validated['province'] = 'Agusan Del Sur';

        // Ensure operating hours are properly formatted and in the correct order
        if (isset($validated['operating_hours']) && is_array($validated['operating_hours'])) {
            // Filter out any empty values
            $validated['operating_hours'] = array_filter($validated['operating_hours']);
            
            // Ensure operating hours contain only time data, not address data
            foreach ($validated['operating_hours'] as $day => $hours) {
                // If the hours look like address data (contains common address words), skip it
                if (is_string($hours) && 
                    (stripos($hours, 'purok') !== false || 
                     stripos($hours, 'poblacion') !== false || 
                     stripos($hours, 'san francisco') !== false || 
                     stripos($hours, 'agusan') !== false || 
                     stripos($hours, 'caraga') !== false || 
                     preg_match('/\d{4}/', $hours))) {
                    // This looks like address data, not time data - remove it
                    unset($validated['operating_hours'][$day]);
                }
            }
            
            // Reorder the operating hours to match the standard sequence
            $orderedHours = [];
            $hourOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            
            foreach ($hourOrder as $day) {
                if (isset($validated['operating_hours'][$day])) {
                    $orderedHours[$day] = $validated['operating_hours'][$day];
                }
            }
            
            // Add any additional days that might not be in the standard order
            foreach ($validated['operating_hours'] as $day => $hours) {
                if (!isset($orderedHours[$day])) {
                    $orderedHours[$day] = $hours;
                }
            }
            
            $validated['operating_hours'] = $orderedHours;
        }

        $map->update($validated);

        return redirect()->route('admin.map.index')
                    ->with('success', 'Location updated successfully!');
    }

    public function destroy(Vetshop $map)
    {
        // Ensure shelter is in San Francisco, Agusan Del Sur
        if ($map->city !== 'San Francisco' || $map->province !== 'Agusan Del Sur') {
            abort(404, 'Shelter not found.');
        }
        
        $map->delete();
        
        return redirect()->route('admin.map.index')
                        ->with('success', 'Location deleted successfully!');
    }
}