<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vetshop;

class MapController extends Controller
{
    // Define common animal types
    private $animalTypes = [
        'dog', 'cat', 'bird', 'fish', 'rabbit', 
        'hamster', 'guinea_pig', 'reptile', 'small_pet', 'exotic'
    ];

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
        
        // Animal type filter
        if ($request->filled('animal_type')) {
            $query->whereJsonContains('animal_types', $request->animal_type);
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $shelters = $query->orderBy('created_at', 'desc')
                          ->paginate(15);
        
        $animalTypes = $this->animalTypes;
        
        return view('admin.map.index', compact('shelters', 'animalTypes'));
    }

    public function create()
    {
        $animalTypes = $this->animalTypes;
        return view('admin.map.create', compact('animalTypes'));
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
            
            // Animal types validation
            'animal_types' => 'nullable|array',
            'animal_types.*' => 'in:' . implode(',', $this->animalTypes),
            
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

        // Process animal types (remove empty values)
        if (isset($validated['animal_types']) && is_array($validated['animal_types'])) {
            $validated['animal_types'] = array_filter($validated['animal_types']);
            if (empty($validated['animal_types'])) {
                $validated['animal_types'] = null;
            }
        } else {
            $validated['animal_types'] = null;
        }

        // Ensure operating hours are properly formatted
        if (isset($validated['operating_hours']) && is_array($validated['operating_hours'])) {
            $validated['operating_hours'] = $this->processOperatingHours($validated['operating_hours']);
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
        
        $animalTypes = $this->animalTypes;
        return view('admin.map.edit', compact('map', 'animalTypes'));
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
            
            // Animal types validation
            'animal_types' => 'nullable|array',
            'animal_types.*' => 'in:' . implode(',', $this->animalTypes),
            
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        // Set default values for San Francisco, Agusan Del Sur
        $validated['city'] = 'San Francisco';
        $validated['province'] = 'Agusan Del Sur';

        // Process animal types
        if (isset($validated['animal_types']) && is_array($validated['animal_types'])) {
            $validated['animal_types'] = array_filter($validated['animal_types']);
            if (empty($validated['animal_types'])) {
                $validated['animal_types'] = null;
            }
        } else {
            $validated['animal_types'] = null;
        }

        // Process operating hours
        if (isset($validated['operating_hours']) && is_array($validated['operating_hours'])) {
            $validated['operating_hours'] = $this->processOperatingHours($validated['operating_hours']);
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

    /**
     * Process and clean operating hours data
     */
    private function processOperatingHours($operatingHours)
    {
        if (empty($operatingHours) || !is_array($operatingHours)) {
            return null;
        }
        
        // Filter out any empty values
        $operatingHours = array_filter($operatingHours);
        
        // Filter out address data
        foreach ($operatingHours as $day => $hours) {
            if (is_string($hours) && 
                $this->isAddressData($hours)) {
                unset($operatingHours[$day]);
            }
        }
        
        // Reorder the operating hours to match the standard sequence
        $orderedHours = [];
        $hourOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        
        foreach ($hourOrder as $day) {
            if (isset($operatingHours[$day])) {
                $orderedHours[$day] = $operatingHours[$day];
            }
        }
        
        // Add any additional days that might not be in the standard order
        foreach ($operatingHours as $day => $hours) {
            if (!isset($orderedHours[$day])) {
                $orderedHours[$day] = $hours;
            }
        }
        
        return empty($orderedHours) ? null : $orderedHours;
    }

    /**
     * Check if string looks like address data
     */
    private function isAddressData($string)
    {
        $addressKeywords = [
            'purok', 'poblacion', 'san francisco', 'agusan', 'caraga',
            'street', 'st.', 'avenue', 'ave.', 'road', 'rd.', 'brgy', 'barangay'
        ];
        
        foreach ($addressKeywords as $keyword) {
            if (stripos($string, $keyword) !== false) {
                return true;
            }
        }
        
        // Check for ZIP code pattern
        if (preg_match('/\d{4}/', $string)) {
            return true;
        }
        
        return false;
    }
}