@extends('layouts.admin')

@section('title', 'Edit Location - Map Management')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .page-header { 
        background: #e74c3c; padding: 30px; border-radius: 15px; margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    .page-title { font-size: 2rem; font-weight: 700; color: white; margin-bottom: 8px; }
    .page-subtitle { font-size: 1rem; color: white; opacity: 0.9; }

    /* Form Styles */
    .form-section { background: white; border-radius: 12px; padding: 25px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .section-title { font-size: 1.5rem; font-weight: 700; color: #1f2937; margin: 0; }
    
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; margin-bottom: 8px; font-weight: 600; color: #374151; }
    .form-control { width: 100%; padding: 12px 15px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem; transition: all 0.2s; }
    .form-control:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
    .form-text { font-size: 0.875rem; color: #6b7280; margin-top: 5px; }
    
    .coordinates-display { background: #f8fafc; border-radius: 8px; padding: 15px; margin-top: 15px; }
    .coordinates-display p { margin: 8px 0; font-size: 0.9rem; }
    
    .action-buttons { display: flex; gap: 10px; margin-top: 30px; justify-content: flex-end; }
    .btn { padding: 10px 20px; border-radius: 6px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 8px; font-size: 1rem; }
    .btn-primary { background: #667eea; color: white; }
    .btn-primary:hover { background: #5a67d8; }
    .btn-secondary { background: #6b7280; color: white; }
    .btn-secondary:hover { background: #4b5563; }
    
    /* Add button focus styles for accessibility */
    .btn:focus {
        outline: 2px solid #667eea;
        outline-offset: 2px;
    }
    
    .map-container { 
        height: 350px; 
        border-radius: 10px; 
        overflow: hidden; 
        border: 2px solid #e5e7eb; 
        position: relative; 
        width: 100%;
        max-width: 100vw;
        margin-top: 10px;
    }
    
    .map-controls { position: absolute; top: 10px; right: 10px; z-index: 1000; }
    .map-btn { background: white; border: 1px solid #ccc; border-radius: 4px; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.2s; }
    .map-btn:hover { background: #f5f5f5; box-shadow: 0 2px 6px rgba(0,0,0,0.15); }
    .map-btn i { font-size: 14px; color: #333; }
    
    .alert { padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; }
    .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    
    /* Responsive styles */
    @media (max-width: 768px) {
        .page-title { font-size: 1.75rem; }
        .page-subtitle { font-size: 0.9rem; }
        
        .form-section { padding: 20px; margin-bottom: 25px; }
        .section-header { flex-direction: column; align-items: flex-start; gap: 15px; }
        .section-title { font-size: 1.35rem; }
        
        .form-group { margin-bottom: 15px; }
        .form-control { padding: 10px 12px; font-size: 0.95rem; }
        
        .map-container { height: 300px; border-radius: 8px; max-width: 100vw; }
        
        .action-buttons { flex-direction: column; }
        .btn { justify-content: center; width: 100%; }
    }
    
    @media (max-width: 576px) {
        .page-title { font-size: 1.5rem; }
        
        .form-section { padding: 15px; margin-bottom: 20px; }
        .section-title { font-size: 1.25rem; }
        
        .form-control { padding: 8px 10px; font-size: 0.9rem; }
        .form-label { font-size: 0.9rem; }
        
        .map-container { height: 250px; border-radius: 6px; max-width: 100vw; }
    }
</style>
@endsection

@section('content')
<div class="content-section">
    <div class="page-header">
        <h1 class="page-title">Edit Location</h1>
        <p class="page-subtitle">Update veterinarian location information</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="form-section">
        <div class="section-header">
            <h2 class="section-title">Location Information</h2>
        </div>
        
        <form action="{{ route('admin.map.update', $map) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name" class="form-label">Location Name *</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $map->name) }}" required>
                    </div>
                </div>
                
                <!-- Removed Location Type field since all are veterinarians -->
                <input type="hidden" name="type" value="veterinarian">
            </div>
            
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $map->description) }}</textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $map->phone) }}">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $map->email) }}">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="address" class="form-label">Full Address *</label>
                <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $map->address) }}" required>
                <div class="form-text">Enter the complete address including street, barangay, and other details</div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="city" class="form-label">City *</label>
                        <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $map->city) }}" required readonly>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="province" class="form-label">Province *</label>
                        <input type="text" name="province" id="province" class="form-control" value="{{ old('province', $map->province) }}" required readonly>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Operating Hours</label>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="operating_hours_monday" class="form-label">Monday</label>
                            <input type="text" name="operating_hours[monday]" id="operating_hours_monday" class="form-control" value="{{ old('operating_hours.monday', isset($map->operating_hours['monday']) ? $map->operating_hours['monday'] : '') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="operating_hours_tuesday" class="form-label">Tuesday</label>
                            <input type="text" name="operating_hours[tuesday]" id="operating_hours_tuesday" class="form-control" value="{{ old('operating_hours.tuesday', isset($map->operating_hours['tuesday']) ? $map->operating_hours['tuesday'] : '') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="operating_hours_wednesday" class="form-label">Wednesday</label>
                            <input type="text" name="operating_hours[wednesday]" id="operating_hours_wednesday" class="form-control" value="{{ old('operating_hours.wednesday', isset($map->operating_hours['wednesday']) ? $map->operating_hours['wednesday'] : '') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="operating_hours_thursday" class="form-label">Thursday</label>
                            <input type="text" name="operating_hours[thursday]" id="operating_hours_thursday" class="form-control" value="{{ old('operating_hours.thursday', isset($map->operating_hours['thursday']) ? $map->operating_hours['thursday'] : '') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="operating_hours_friday" class="form-label">Friday</label>
                            <input type="text" name="operating_hours[friday]" id="operating_hours_friday" class="form-control" value="{{ old('operating_hours.friday', isset($map->operating_hours['friday']) ? $map->operating_hours['friday'] : '') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="operating_hours_saturday" class="form-label">Saturday</label>
                            <input type="text" name="operating_hours[saturday]" id="operating_hours_saturday" class="form-control" value="{{ old('operating_hours.saturday', isset($map->operating_hours['saturday']) ? $map->operating_hours['saturday'] : '') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="operating_hours_sunday" class="form-label">Sunday</label>
                            <input type="text" name="operating_hours[sunday]" id="operating_hours_sunday" class="form-control" value="{{ old('operating_hours.sunday', isset($map->operating_hours['sunday']) ? $map->operating_hours['sunday'] : '') }}">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="latitude" class="form-label">Latitude *</label>
                        <input type="text" name="latitude" id="latitude" class="form-control" value="{{ old('latitude', $map->latitude) }}" readonly required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="longitude" class="form-label">Longitude *</label>
                        <input type="text" name="longitude" id="longitude" class="form-control" value="{{ old('longitude', $map->longitude) }}" readonly required>
                    </div>
                </div>
            </div>
            
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
                    <p>Latitude: <span id="display-latitude">{{ $map->latitude }}</span></p>
                    <p>Longitude: <span id="display-longitude">{{ $map->longitude }}</span></p>
                    <p>Address: <span id="display-address">{{ $map->address }}</span></p>
                </div>
                <div class="form-text">Click on the map to select the shelter location. The coordinates will be automatically filled.</div>
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('admin.map.index') }}" class="btn btn-secondary" title="Back to Map">
                    <i class="fas fa-arrow-left"></i> Back to Map
                </a>
                <button type="submit" class="btn btn-primary" title="Update Location">
                    <i class="fas fa-save"></i> Update Location
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
// Initialize map when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Parse coordinates
    const lat = parseFloat({{ $map->latitude }});
    const lng = parseFloat({{ $map->longitude }});
    
    if (isNaN(lat) || isNaN(lng)) {
        console.error('Invalid coordinates');
        return;
    }
    
    // Create map
    const map = L.map('shelterMap', {
        center: [lat, lng],
        zoom: 16,
        zoomControl: false // Disable zoom controls
    });
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    // Create marker
    let marker = L.marker([lat, lng], {
        draggable: true
    }).addTo(map);
    
    // Update coordinate display
    document.getElementById('latitude').value = lat.toFixed(6);
    document.getElementById('longitude').value = lng.toFixed(6);
    document.getElementById('display-latitude').textContent = lat.toFixed(6);
    document.getElementById('display-longitude').textContent = lng.toFixed(6);
    
    // Handle marker drag end
    marker.on('dragend', function(e) {
        const position = marker.getLatLng();
        document.getElementById('latitude').value = position.lat.toFixed(6);
        document.getElementById('longitude').value = position.lng.toFixed(6);
        document.getElementById('display-latitude').textContent = position.lat.toFixed(6);
        document.getElementById('display-longitude').textContent = position.lng.toFixed(6);
        
        // Reverse geocode to get address
        reverseGeocode(position.lat, position.lng);
    });
    
    // Handle map click
    map.on('click', function(e) {
        const position = e.latlng;
        
        // Remove existing marker
        if (marker) {
            map.removeLayer(marker);
        }
        
        // Create new marker
        marker = L.marker([position.lat, position.lng], {
            draggable: true
        }).addTo(map);
        
        // Update coordinate display
        document.getElementById('latitude').value = position.lat.toFixed(6);
        document.getElementById('longitude').value = position.lng.toFixed(6);
        document.getElementById('display-latitude').textContent = position.lat.toFixed(6);
        document.getElementById('display-longitude').textContent = position.lng.toFixed(6);
        
        // Handle marker drag end for new marker
        marker.on('dragend', function(e) {
            const position = marker.getLatLng();
            document.getElementById('latitude').value = position.lat.toFixed(6);
            document.getElementById('longitude').value = position.lng.toFixed(6);
            document.getElementById('display-latitude').textContent = position.lat.toFixed(6);
            document.getElementById('display-longitude').textContent = position.lng.toFixed(6);
            
            // Reverse geocode to get address
            reverseGeocode(position.lat, position.lng);
        });
        
        // Reverse geocode to get address
        reverseGeocode(position.lat, position.lng);
    });
    
    // Locate me functionality
    document.getElementById('locate-btn').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                // Center map on user location
                map.setView([lat, lng], 16);
                
                // Remove existing marker
                if (marker) {
                    map.removeLayer(marker);
                }
                
                // Create new marker
                marker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(map);
                
                // Update coordinate display
                document.getElementById('latitude').value = lat.toFixed(6);
                document.getElementById('longitude').value = lng.toFixed(6);
                document.getElementById('display-latitude').textContent = lat.toFixed(6);
                document.getElementById('display-longitude').textContent = lng.toFixed(6);
                
                // Handle marker drag end for new marker
                marker.on('dragend', function(e) {
                    const position = marker.getLatLng();
                    document.getElementById('latitude').value = position.lat.toFixed(6);
                    document.getElementById('longitude').value = position.lng.toFixed(6);
                    document.getElementById('display-latitude').textContent = position.lat.toFixed(6);
                    document.getElementById('display-longitude').textContent = position.lng.toFixed(6);
                    
                    // Reverse geocode to get address
                    reverseGeocode(position.lat, position.lng);
                });
                
                // Reverse geocode to get address
                reverseGeocode(lat, lng);
            }, function(error) {
                alert('Unable to get your location: ' + error.message);
            });
        } else {
            alert('Geolocation is not supported by your browser.');
        }
    });
    
    // Reverse geocode function
    function reverseGeocode(lat, lng) {
        // In a real application, you would make an API call to a geocoding service
        // For now, we'll just update the display with a placeholder
        document.getElementById('display-address').textContent = 'Coordinates updated. Please enter address manually.';
    }
});
</script>
@endsection