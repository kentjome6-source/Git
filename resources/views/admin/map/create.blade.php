@extends('layouts.admin')

@section('title', 'Create Shelter')

@section('styles')
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .admin-header { background: #e74c3c; padding: 30px 0; color: white; }
    .admin-title { font-size: 2.5rem; font-weight: 700; margin-bottom: 10px; }
    .admin-subtitle { font-size: 1.1rem; opacity: 0.9; }

    /* Form Styles */
    .form-section { background: white; border-radius: 12px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; margin-bottom: 8px; font-weight: 600; color: #374151; }
    .form-control { width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem; transition: border-color 0.2s; }
    .form-control:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
    .form-text { font-size: 0.875rem; color: #6b7280; margin-top: 5px; }
    
    /* Map Styles */
    .map-container { 
        height: 400px; 
        border-radius: 8px; 
        overflow: hidden; 
        border: 2px solid #e5e7eb; 
        position: relative; 
        margin-bottom: 20px;
        max-width: 100vw;
    }
    
    .map-controls { position: absolute; top: 10px; right: 10px; z-index: 1000; display: flex; gap: 5px; }
    .map-btn { background: white; border: 1px solid #ccc; border-radius: 4px; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.2s; }
    .map-btn:hover { background: #f5f5f5; box-shadow: 0 2px 6px rgba(0,0,0,0.15); }
    .map-btn i { font-size: 14px; color: #333; }
    
    .coordinates-display { 
        background: #f3f4f6; 
        padding: 15px; 
        border-radius: 6px; 
        margin-bottom: 20px;
        font-family: monospace;
        font-size: 0.9rem;
    }
    
    .coordinates-display p { margin: 5px 0; }
    
    .btn-primary { background: #667eea; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
    .btn-primary:hover { background: #5a6fd8; }
    .btn-secondary { background: #6b7280; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; text-decoration: none; display: inline-block; }
    .btn-secondary:hover { background: #5b626e; }
    
    .action-buttons { display: flex; gap: 10px; margin-top: 20px; }
    
    /* Animal Types Checkboxes */
    .animal-types-container {
        background: #f9fafb;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #e5e7eb;
    }
    
    .animal-checkbox-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 10px;
        margin-top: 10px;
    }
    
    .animal-checkbox-item {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        background: white;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        transition: all 0.2s;
    }
    
    .animal-checkbox-item:hover {
        border-color: #667eea;
        background: #f8fafc;
    }
    
    .animal-checkbox-item input[type="checkbox"] {
        margin-right: 10px;
    }
    
    .animal-checkbox-item label {
        cursor: pointer;
        font-weight: 500;
        flex: 1;
    }
    
    /* Responsive styles for mobile */
    @media (max-width: 768px) {
        .admin-header {
            padding: 20px 0;
            background: #e74c3c;
        }
        
        .admin-title {
            font-size: 2rem;
        }
        
        .admin-subtitle {
            font-size: 1rem;
        }
        
        .form-section {
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .map-container {
            height: 300px;
            border-radius: 6px;
            max-width: 100vw;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons a, .action-buttons button {
            width: 100%;
            text-align: center;
        }
        
        .animal-checkbox-grid {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 8px;
        }
    }
    
    @media (max-width: 576px) {
        .admin-header {
            padding: 15px 0;
            background: #e74c3c;
        }
        
        .admin-title {
            font-size: 1.75rem;
        }
        
        .form-section {
            padding: 12px;
        }
        
        .map-container {
            height: 250px;
            max-width: 100vw;
        }
        
        .map-btn {
            width: 25px;
            height: 25px;
        }
        
        .map-btn i {
            font-size: 12px;
        }
        
        /* Add action button styles */
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 30px;
            justify-content: flex-end;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 1rem;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a67d8;
        }
        
        .btn-secondary {
            background: #6b7280;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #4b5563;
        }
        
        /* Add button focus styles for accessibility */
        .btn:focus {
            outline: 2px solid #667eea;
            outline-offset: 2px;
        }
        
        /* Ensure form elements fit on small screens */
        .form-control {
            font-size: 0.9rem;
            padding: 10px;
        }
        
        .form-label {
            font-size: 0.9rem;
        }
        
        .animal-checkbox-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endsection

@section('content')
<div class="admin-header">
    <div class="container">
        <h1 class="admin-title">Create Shelter</h1>
        <p class="admin-subtitle">Add a new shelter location to the map</p>
    </div>
</div>

<div class="container" style="padding: 30px 20px;">
    <div class="form-section">
        <form action="{{ route('admin.map.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name" class="form-label">Shelter Name *</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    
                    <!-- Add hidden type field for veterinarian -->
                    <input type="hidden" name="type" value="veterinarian">
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <!-- Animal Types Section -->
                    <div class="form-group">
                        <label class="form-label">Animal Types Catered</label>
                        <small class="form-text d-block mb-2">Select all animal types that this shelter caters to</small>
                        
                        <div class="animal-types-container">
                            <div class="animal-checkbox-grid">
                                @php
                                    // Define animal types - should match the ones in your controller
                                    $animalTypes = [
                                        'dog' => 'Dog',
                                        'cat' => 'Cat',
                                        'bird' => 'Bird',
                                        'fish' => 'Fish',
                                        'rabbit' => 'Rabbit',
                                        'hamster' => 'Hamster',
                                        'guinea_pig' => 'Guinea Pig',
                                        'reptile' => 'Reptile',
                                        'small_pet' => 'Small Pet',
                                        'exotic' => 'Exotic'
                                    ];
                                @endphp
                                
                                @foreach($animalTypes as $value => $label)
                                    <div class="animal-checkbox-item">
                                        <input 
                                            type="checkbox" 
                                            id="animal_type_{{ $value }}" 
                                            name="animal_types[]" 
                                            value="{{ $value }}"
                                        >
                                        <label for="animal_type_{{ $value }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" name="phone" id="phone" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Operating Hours</label>
                        <small class="form-text d-block mb-2">Set operating hours for each day</small>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="operating_hours_monday" class="form-label">Monday</label>
                                    <input type="text" name="operating_hours[monday]" id="operating_hours_monday" class="form-control" placeholder="e.g., 8 AM - 5 PM" value="8 AM - 5 PM">
                                </div>
                                <div class="form-group">
                                    <label for="operating_hours_tuesday" class="form-label">Tuesday</label>
                                    <input type="text" name="operating_hours[tuesday]" id="operating_hours_tuesday" class="form-control" placeholder="e.g., 8 AM - 5 PM" value="8 AM - 5 PM">
                                </div>
                                <div class="form-group">
                                    <label for="operating_hours_wednesday" class="form-label">Wednesday</label>
                                    <input type="text" name="operating_hours[wednesday]" id="operating_hours_wednesday" class="form-control" placeholder="e.g., 8 AM - 5 PM" value="8 AM - 5 PM">
                                </div>
                                <div class="form-group">
                                    <label for="operating_hours_thursday" class="form-label">Thursday</label>
                                    <input type="text" name="operating_hours[thursday]" id="operating_hours_thursday" class="form-control" placeholder="e.g., 8 AM - 5 PM" value="8 AM - 5 PM">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="operating_hours_friday" class="form-label">Friday</label>
                                    <input type="text" name="operating_hours[friday]" id="operating_hours_friday" class="form-control" placeholder="e.g., 8 AM - 5 PM" value="8 AM - 5 PM">
                                </div>
                                <div class="form-group">
                                    <label for="operating_hours_saturday" class="form-label">Saturday</label>
                                    <input type="text" name="operating_hours[saturday]" id="operating_hours_saturday" class="form-control" placeholder="e.g., 8 AM - 5 PM" value="8 AM - 5 PM">
                                </div>
                                <div class="form-group">
                                    <label for="operating_hours_sunday" class="form-label">Sunday</label>
                                    <input type="text" name="operating_hours[sunday]" id="operating_hours_sunday" class="form-control" placeholder="e.g., 9 AM - 4 PM" value="9 AM - 4 PM">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="address" class="form-label">Address *</label>
                        <input type="text" name="address" id="address" class="form-control" required>
                        <small class="form-text">This will be auto-filled when you click on the map, but you can edit it manually</small>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city" class="form-label">City *</label>
                                <input type="text" name="city" id="city" class="form-control" value="San Francisco" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="province" class="form-label">Province *</label>
                                <input type="text" name="province" id="province" class="form-control" value="Agusan Del Sur" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="latitude" class="form-label">Latitude *</label>
                                <input type="text" name="latitude" id="latitude" class="form-control" readonly required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="longitude" class="form-label">Longitude *</label>
                                <input type="text" name="longitude" id="longitude" class="form-control" readonly required>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Location Map *</label>
                        <div id="shelterMap" class="map-container">
                            <div class="map-controls">
                                <div class="map-btn" id="locate-btn" title="Locate Me">
                                    <i class="fas fa-location-arrow"></i>
                                </div>
                            </div>
                        </div>
                        <div class="coordinates-display">
                            <p><strong>Selected Coordinates:</strong></p>
                            <p>Latitude: <span id="display-latitude">Not selected</span></p>
                            <p>Longitude: <span id="display-longitude">Not selected</span></p>
                            <p>Address: <span id="display-address">Click on the map to select a location</span></p>
                        </div>
                        <div class="form-text">Click on the map to select the shelter location. The coordinates will be automatically filled.</div>
                    </div>
                    
                    <!-- Status field (hidden by default) -->
                    <div class="form-group">
                        <label for="is_active" class="form-label">Status</label>
                        <select name="is_active" id="is_active" class="form-control">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <small class="form-text">Set the shelter's active status</small>
                    </div>
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('admin.map.index') }}" class="btn btn-secondary" title="Back to Map">
                    <i class="fas fa-arrow-left"></i> Back to Map
                </a>
                <button type="submit" class="btn btn-primary" title="Save Location">
                    <i class="fas fa-save"></i> Save Location
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map centered on San Francisco, Agusan del Sur
    const map = L.map('shelterMap').setView([8.504588, 125.975800], 15);
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Add marker variable
    let marker = null;
    
    // Add click event to map
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        // Remove existing marker if any
        if (marker) {
            map.removeLayer(marker);
        }
        
        // Add new marker
        marker = L.marker([lat, lng]).addTo(map);
        
        // Update form fields
        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);
        document.getElementById('display-latitude').textContent = lat.toFixed(6);
        document.getElementById('display-longitude').textContent = lng.toFixed(6);
        
        // Reverse geocode to get address
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    document.getElementById('display-address').textContent = data.display_name;
                    // Update the address field with the reverse geocoded address
                    document.getElementById('address').value = data.display_name;
                } else {
                    document.getElementById('display-address').textContent = 'Address not found';
                }
            })
            .catch(error => {
                console.error('Error fetching address:', error);
                document.getElementById('display-address').textContent = 'Error fetching address';
            });
    });
    
    // Locate me button
    document.getElementById('locate-btn').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                // Center map on user location
                map.setView([lat, lng], 15);
                
                // Trigger click event to place marker
                map.fire('click', {
                    latlng: L.latLng(lat, lng)
                });
            }, function(error) {
                alert('Unable to get your location: ' + error.message);
            });
        } else {
            alert('Geolocation is not supported by your browser.');
        }
    });
    
    // Set a default location on load (center of San Francisco, Agusan Del Sur)
    map.fire('click', {
        latlng: L.latLng(8.504588, 125.975800)
    });
});
</script>
@endsection