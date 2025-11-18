@extends('layouts.admin')

@section('title', 'Create Shelter')

@section('styles')
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .admin-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px 0; color: white; }
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
    
    /* Responsive styles for mobile */
    @media (max-width: 768px) {
        .admin-header {
            padding: 20px 0;
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
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons a, .action-buttons button {
            width: 100%;
            text-align: center;
        }
    }
    
    @media (max-width: 576px) {
        .admin-header {
            padding: 15px 0;
        }
        
        .admin-title {
            font-size: 1.75rem;
        }
        
        .form-section {
            padding: 12px;
        }
        
        .map-container {
            height: 250px;
        }
        
        .map-btn {
            width: 25px;
            height: 25px;
        }
        
        .map-btn i {
            font-size: 12px;
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
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="operating_hours" class="form-label">Operating Hours</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="operating_hours[monday]" class="form-label">Monday</label>
                                    <input type="text" name="operating_hours[monday]" id="operating_hours[monday]" class="form-control" placeholder="e.g., 8 AM - 5 PM" value="8 AM - 5 PM">
                                </div>
                                <div class="form-group">
                                    <label for="operating_hours[tuesday]" class="form-label">Tuesday</label>
                                    <input type="text" name="operating_hours[tuesday]" id="operating_hours[tuesday]" class="form-control" placeholder="e.g., 8 AM - 5 PM" value="8 AM - 5 PM">
                                </div>
                                <div class="form-group">
                                    <label for="operating_hours[wednesday]" class="form-label">Wednesday</label>
                                    <input type="text" name="operating_hours[wednesday]" id="operating_hours[wednesday]" class="form-control" placeholder="e.g., 8 AM - 5 PM" value="8 AM - 5 PM">
                                </div>
                                <div class="form-group">
                                    <label for="operating_hours[thursday]" class="form-label">Thursday</label>
                                    <input type="text" name="operating_hours[thursday]" id="operating_hours[thursday]" class="form-control" placeholder="e.g., 8 AM - 5 PM" value="8 AM - 5 PM">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="operating_hours[friday]" class="form-label">Friday</label>
                                    <input type="text" name="operating_hours[friday]" id="operating_hours[friday]" class="form-control" placeholder="e.g., 8 AM - 5 PM" value="8 AM - 5 PM">
                                </div>
                                <div class="form-group">
                                    <label for="operating_hours[saturday]" class="form-label">Saturday</label>
                                    <input type="text" name="operating_hours[saturday]" id="operating_hours[saturday]" class="form-control" placeholder="e.g., 8 AM - 5 PM" value="8 AM - 5 PM">
                                </div>
                                <div class="form-group">
                                    <label for="operating_hours[sunday]" class="form-label">Sunday</label>
                                    <input type="text" name="operating_hours[sunday]" id="operating_hours[sunday]" class="form-control" placeholder="e.g., 9 AM - 4 PM" value="9 AM - 4 PM">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="address" class="form-label">Address *</label>
                        <input type="text" name="address" id="address" class="form-control" required>
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
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('admin.map.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Map
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Save Shelter
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
    const map = L.map('shelterMap').setView([8.3333, 125.9833], 13);
    
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
});
</script>
@endsection