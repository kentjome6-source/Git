@extends('layouts.admin')

@section('title', 'Map Management')

@section('styles')
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .admin-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px 0; color: white; }
    .admin-title { font-size: 2.5rem; font-weight: 700; margin-bottom: 10px; }
    .admin-subtitle { font-size: 1.1rem; opacity: 0.9; }

    /* Map Styles */
    .map-section { background: white; border-radius: 12px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .map-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px; }
    .map-container { 
        height: 400px; 
        border-radius: 8px; 
        overflow: hidden; 
        border: 2px solid #e5e7eb; 
        position: relative; 
    }
    
    .map-fullscreen { 
        position: fixed; 
        top: 0; 
        left: 0; 
        width: 100vw; 
        height: 100vh; 
        z-index: 10001; /* Increased z-index to ensure it's above all other elements */
        border-radius: 0; 
        border: none; 
        background: white; /* Explicit background color for fullscreen */
    }
    
    .map-controls { position: absolute; top: 10px; right: 10px; z-index: 1000; display: flex; gap: 5px; }
    .map-btn { background: white; border: 1px solid #ccc; border-radius: 4px; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.2s; }
    .map-btn:hover { background: #f5f5f5; box-shadow: 0 2px 6px rgba(0,0,0,0.15); }
    .map-btn i { font-size: 14px; color: #333; }
    
    .fullscreen-overlay { 
        position: fixed; 
        top: 0; 
        left: 0; 
        width: 100vw; 
        height: 100vh; 
        background: rgba(0,0,0,0.9); /* Darker background for better contrast */
        z-index: 10000; /* Increased z-index to ensure it's above all other elements */
        display: none; 
    }
    
    .map-actions { margin-left: auto; }
    
    .btn-primary { background: #667eea; color: white; padding: 8px 16px; border: none; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; text-decoration: none; display: inline-block; }
    .btn-primary:hover { background: #5a6fd8; }
    
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
        
        .map-section {
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .map-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .map-container {
            height: 300px;
            border-radius: 6px;
        }
        
        .map-actions {
            margin-left: 0;
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
        
        .map-section {
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
    
    /* Ensure fullscreen map covers entire viewport on mobile */
    @media (max-width: 768px) {
        .map-fullscreen {
            width: 100vw !important;
            height: 100vh !important;
            top: 0 !important;
            left: 0 !important;
            border-radius: 0 !important;
        }
        
        /* Ensure no other elements interfere with fullscreen map */
        .fullscreen-overlay {
            width: 100vw !important;
            height: 100vh !important;
            top: 0 !important;
            left: 0 !important;
        }
    }
</style>
@endsection

@section('content')
<div class="admin-header">
    <div class="container">
        <h1 class="admin-title">Map Management</h1>
        <p class="admin-subtitle">View locations on the map</p>
    </div>
</div>

<div class="container" style="padding: 30px 20px;">
    <!-- Map Section -->
    <div class="map-section">
        <div class="map-header">
            <h2 class="section-title" style="margin: 0;">
                <i class="fas fa-map-marked-alt"></i>
                Locations Map
            </h2>
            <div class="map-actions">
                <a href="{{ route('admin.map.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Shelter
                </a>
            </div>
        </div>
        <div id="shelterMap" class="map-container">
            <div class="map-controls">
                <div class="map-btn" id="fullscreen-btn" title="View Fullscreen">
                    <i class="fas fa-expand"></i>
                </div>
            </div>
        </div>
        <div class="fullscreen-overlay" id="fullscreen-overlay">
            <div id="fullscreen-map" class="map-container map-fullscreen">
                <div class="map-controls">
                    <div class="map-btn" id="exit-fullscreen-btn" title="Exit Fullscreen">
                        <i class="fas fa-compress"></i>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-top: 15px; font-size: 0.9rem; color: #6b7280;">
            <i class="fas fa-info-circle"></i> 
            Click on map markers to view location details.
        </div>
    </div>
</div>

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endsection

@section('scripts')
<script>
// Location data from backend
const locations = @json($shelters->items());
let allLocations = locations;
let sharedMap = null;

// Initialize map when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Wait a bit for the Vite assets to load
    setTimeout(function() {
        if (typeof SharedMap !== 'undefined') {
            // Initialize the shared map component
            sharedMap = new SharedMap('shelterMap', allLocations, {
                fullscreenEnabled: true,
                showViewDetails: true,
                viewDetailsRoute: '/admin/map/location/'
            });
            
            // Store reference to map for potential future use
            window.shelterMap = sharedMap;
        } else {
            // Fallback to basic map initialization
            initBasicMap();
        }
    }, 100);
});

// Fallback function for basic map initialization
function initBasicMap() {
    // Create map centered on San Francisco, Agusan del Sur
    const map = L.map('shelterMap', {
        zoomControl: false // Disable zoom controls
    }).setView([8.3450, 125.9800], 13);
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Add markers
    allLocations.forEach(location => {
        if (location.latitude && location.longitude) {
            const lat = parseFloat(location.latitude);
            const lng = parseFloat(location.longitude);
            
            // Use only veterinarian icon for all locations
            let iconClass = 'fas fa-user-md';
            let iconColor = '#10b981';
            
            // Create custom icon
            const customIcon = L.divIcon({
                html: `<div style="background: ${iconColor}; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"><i class="${iconClass}" style="font-size: 12px;"></i></div>`,
                iconSize: [36, 36],
                iconAnchor: [18, 18],
                popupAnchor: [0, -18],
                className: 'custom-marker'
            });
            
            // Create marker
            const marker = L.marker([lat, lng], { icon: customIcon });
            
            // Create popup content
            const popupContent = `
                <div style="min-width: 250px;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <div style="background: ${iconColor}; color: white; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                            <i class="${iconClass}"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0; font-size: 1.1rem; color: #1f2937;">${location.name}</h4>
                            <span style="background: #e5e7eb; color: #374151; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Veterinarian</span>
                        </div>
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-map-marker-alt" style="color: #667eea; margin-right: 6px;"></i>
                        ${location.address}<br>
                        ${location.city}, ${location.province}
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-phone" style="color: #667eea; margin-right: 6px;"></i>
                        ${location.phone || 'Not provided'}
                    </div>
                    ${location.email ? `
                        <div style="margin-bottom: 12px; color: #4b5563;">
                            <i class="fas fa-envelope" style="color: #667eea; margin-right: 6px;"></i>
                            ${location.email}
                        </div>
                    ` : ''}
                    <div style="display: flex; gap: 5px; margin-top: 12px;">
                        <a href="/admin/map/location/${location.id}" style="background: #667eea; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            marker.addTo(map);
        }
    });
    
    // Store reference to map
    window.shelterMap = map;
    
    // Initialize fullscreen functionality manually if SharedMap is not available
    initFullscreenFunctionality();
}

function initFullscreenFunctionality() {
    const fullscreenBtn = document.getElementById('fullscreen-btn');
    const exitFullscreenBtn = document.getElementById('exit-fullscreen-btn');
    const fullscreenOverlay = document.getElementById('fullscreen-overlay');
    
    if (fullscreenBtn) {
        // Add both click and touch events for better mobile support
        fullscreenBtn.addEventListener('click', handleFullscreenToggle);
        fullscreenBtn.addEventListener('touchstart', handleFullscreenToggle);
    }
    
    if (exitFullscreenBtn) {
        // Add both click and touch events for better mobile support
        exitFullscreenBtn.addEventListener('click', exitFullscreen);
        exitFullscreenBtn.addEventListener('touchstart', exitFullscreen);
    }
    
    if (fullscreenOverlay) {
        // Add both click and touch events for better mobile support
        fullscreenOverlay.addEventListener('click', handleOverlayClick);
        fullscreenOverlay.addEventListener('touchstart', handleOverlayClick);
    }
    
    // Exit fullscreen on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && fullscreenOverlay && fullscreenOverlay.style.display === 'block') {
            exitFullscreen();
        }
    });
}

function handleFullscreenToggle(e) {
    e.preventDefault();
    const fullscreenOverlay = document.getElementById('fullscreen-overlay');
    if (fullscreenOverlay) {
        fullscreenOverlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        if (!window.fullscreenMap) {
            initFullscreenMap();
        } else {
            setTimeout(() => {
                window.fullscreenMap.invalidateSize();
            }, 100);
        }
    }
}

function handleOverlayClick(e) {
    if (e.target.id === 'fullscreen-overlay') {
        exitFullscreen();
    }
}

function initFullscreenMap() {
    const mapLocations = @json($shelters->items());
    
    window.fullscreenMap = L.map('fullscreen-map', {
        zoomControl: false // Disable zoom controls
    }).setView([8.3450, 125.9800], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(window.fullscreenMap);
    
    // Add markers to fullscreen map
    mapLocations.forEach(location => {
        if (location.latitude && location.longitude) {
            const lat = parseFloat(location.latitude);
            const lng = parseFloat(location.longitude);
            
            // Use only veterinarian icon for all locations
            let iconClass = 'fas fa-user-md';
            let iconColor = '#10b981';
            
            // Create custom icon
            const customIcon = L.divIcon({
                html: `<div style="background: ${iconColor}; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"><i class="${iconClass}" style="font-size: 12px;"></i></div>`,
                iconSize: [36, 36],
                iconAnchor: [18, 18],
                popupAnchor: [0, -18],
                className: 'custom-marker'
            });
            
            // Create marker
            const marker = L.marker([lat, lng], { icon: customIcon });
            
            // Create popup content
            const popupContent = `
                <div style="min-width: 250px;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <div style="background: ${iconColor}; color: white; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                            <i class="${iconClass}"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0; font-size: 1.1rem; color: #1f2937;">${location.name}</h4>
                            <span style="background: #e5e7eb; color: #374151; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Veterinarian</span>
                        </div>
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-map-marker-alt" style="color: #667eea; margin-right: 6px;"></i>
                        ${location.address}<br>
                        ${location.city}, ${location.province}
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-phone" style="color: #667eea; margin-right: 6px;"></i>
                        ${location.phone || 'Not provided'}
                    </div>
                    ${location.email ? `
                        <div style="margin-bottom: 12px; color: #4b5563;">
                            <i class="fas fa-envelope" style="color: #667eea; margin-right: 6px;"></i>
                            ${location.email}
                        </div>
                    ` : ''}
                    <div style="display: flex; gap: 5px; margin-top: 12px;">
                        <a href="/admin/map/location/${location.id}" style="background: #667eea; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            marker.addTo(window.fullscreenMap);
        }
    });
    
    // Ensure map renders properly
    setTimeout(() => {
        window.fullscreenMap.invalidateSize();
    }, 100);
    
    // Ensure the map is properly rendered
    setTimeout(() => {
        window.fullscreenMap.invalidateSize();
    }, 1000);
}

function exitFullscreen() {
    const fullscreenOverlay = document.getElementById('fullscreen-overlay');
    if (fullscreenOverlay) {
        fullscreenOverlay.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

function getTypeName(type) {
    switch(type) {
        case 'pet_shop': return 'Pet Shop';
        case 'veterinarian': return 'Veterinarian';
        case 'grooming': return 'Grooming Service';
        default: return type;
    }
}
</script>
@endsection