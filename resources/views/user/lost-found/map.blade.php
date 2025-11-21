@extends('layouts.app')

@section('title', 'Lost & Found Map')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .page-header {
        text-align: center; margin-bottom: 40px;
    }
    .page-title { font-size: 2.5rem; color: #5b4b9b; margin-bottom: 10px; }
    .page-subtitle { font-size: 1.1rem; color: #666; }

    /* Map Styles */
    .map-section { background: white; border-radius: 12px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .map-header { display: flex; justify-content: between; align-items: center; margin-bottom: 20px; }
    .map-container { 
        height: 250px; 
        border-radius: 8px; 
        overflow: hidden; 
        border: 2px solid #e5e7eb; 
        position: relative; 
    }
    .map-fullscreen { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 10000; border-radius: 0; border: none; }
    .map-controls { position: absolute; top: 10px; right: 10px; z-index: 1000; display: flex; gap: 5px; }
    .map-btn { background: white; border: 1px solid #ccc; border-radius: 4px; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.2s; }
    .map-btn:hover { background: #f5f5f5; box-shadow: 0 2px 6px rgba(0,0,0,0.15); }
    .map-btn i { font-size: 14px; color: #333; }
    .fullscreen-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.8); z-index: 9999; display: none; }
    
    .info-panel {
        position: absolute;
        top: 10px;
        left: 10px;
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
        z-index: 1000;
        max-width: 300px;
        display: none;
    }
    
    .back-link {
        display: inline-flex; align-items: center; gap: 8px;
        color: #5b4b9b; text-decoration: none; font-weight: 500;
        margin-bottom: 20px; transition: 0.2s;
    }
    .back-link:hover { color: #4a3d7a; }
    
    /* Responsive styles */
    @media (max-width: 768px) {
        .page-title {
            font-size: 2rem;
        }
        
        .page-subtitle {
            font-size: 1rem;
        }
        
        .map-section {
            padding: 15px;
        }
        
        .map-container {
            height: 300px;
        }
        
        .map-btn {
            width: 25px;
            height: 25px;
        }
        
        .map-btn i {
            font-size: 12px;
        }
    }
    
    @media (max-width: 576px) {
        .page-header {
            margin-bottom: 30px;
        }
        
        .page-title {
            font-size: 1.75rem;
        }
        
        .map-section {
            padding: 10px;
        }
        
        .map-container {
            height: 250px;
        }
        
        /* Ensure content fits within mobile screen without horizontal scrolling */
        .container, .container-fluid {
            padding-left: 10px;
            padding-right: 10px;
        }
        
        .map-section {
            margin-left: 0;
            margin-right: 0;
        }
    }
    
    /* Additional fix to prevent horizontal scrolling */
    body {
        overflow-x: hidden;
    }
    
    .main-content {
        overflow-x: hidden;
    }

</style>
@endsection

@section('content')
<a href="{{ route('pet.lostfound') }}" class="back-link">
    <i class="fas fa-arrow-left"></i> Back to Lost & Found
</a>

<div class="page-header">
    <h1 class="page-title">Lost & Found Map</h1>
    <p class="page-subtitle">View lost and found pet locations</p>
</div>

<div class="map-section">
    <div class="map-header">
        <h2 class="section-title" style="margin: 0;">
            <i class="fas fa-map-marked-alt"></i>
            Pet Locations Map
        </h2>
    </div>
    <div id="lostFoundMap" class="map-container">
        <div class="map-controls">
            <div class="map-btn" id="fullscreen-btn" title="View Fullscreen">
                <i class="fas fa-expand"></i>
            </div>
        </div>
        <div class="info-panel" id="info-panel">
            <h4 id="info-title"></h4>
            <p id="info-content"></p>
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
        Click on map markers to view lost/found pet details.
    </div>
</div>

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endsection

@section('scripts')
<script>
// Location data from backend
const locations = @json($lostFoundItems);

// Initialize map when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Create map centered on San Francisco, Agusan del Sur
    const map = L.map('lostFoundMap', {
        zoomControl: false // Disable zoom controls
    }).setView([8.504588, 125.975800], 13);
    
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Add markers
    locations.forEach(location => {
        if (location.latitude && location.longitude) {
            const lat = parseFloat(location.latitude);
            const lng = parseFloat(location.longitude);
            
            // Choose color based on type
            const color = location.type === 'lost' ? '#e74c3c' : '#27ae60';
            const iconClass = location.type === 'lost' ? 'fas fa-heart-broken' : 'fas fa-heart';
            
            // Create custom icon with pet image if available
            let iconHtml = '';
            if (location.image_path) {
                // Use the pet image as the marker without the heart icon
                iconHtml = `<div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <img src="/storage/${location.image_path}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>`;
            } else {
                // Use the default icon if no image is available
                iconHtml = `<div style="background: ${color}; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <i class="${iconClass}" style="font-size: 16px;"></i>
                </div>`;
            }
            
            const customIcon = L.divIcon({
                html: iconHtml,
                iconSize: location.image_path ? [56, 56] : [46, 46],
                iconAnchor: location.image_path ? [28, 28] : [23, 23],
                popupAnchor: [0, -28],
                className: 'custom-marker'
            });
            
            // Create marker
            const marker = L.marker([lat, lng], { icon: customIcon });
            
            // Create popup content
            const popupContent = `
                <div style="min-width: 250px;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        ${location.image_path ? 
                            `<div style="width: 60px; height: 60px; border-radius: 8px; overflow: hidden; margin-right: 12px;">
                                <img src="/storage/${location.image_path}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>` : 
                            `<div style="background: ${color}; color: white; width: 60px; height: 60px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                <i class="${iconClass}" style="font-size: 24px;"></i>
                            </div>`
                        }
                        <div>
                            <h4 style="margin: 0; font-size: 1.1rem; color: #1f2937;">${location.pet_name}</h4>
                            <span style="background: #e5e7eb; color: #374151; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">${location.type.charAt(0).toUpperCase() + location.type.slice(1)} Pet</span>
                        </div>
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-paw" style="color: #667eea; margin-right: 6px;"></i>
                        ${location.pet_type} ${location.breed ? `(${location.breed})` : ''}
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-map-marker-alt" style="color: #667eea; margin-right: 6px;"></i>
                        ${location.location}
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-calendar" style="color: #667eea; margin-right: 6px;"></i>
                        ${new Date(location.date_lost_found).toLocaleDateString()}
                    </div>
                    <div style="margin-bottom: 12px; color: #4b5563;">
                        <i class="fas fa-user" style="color: #667eea; margin-right: 6px;"></i>
                        Reported by ${location.user.name}
                    </div>
                    <div style="display: flex; gap: 5px; margin-top: 12px;">
                        <a href="/lost-found/${location.id}" style="background: #667eea; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
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
    window.lostFoundMap = map;
    
    // Initialize fullscreen functionality
    initFullscreenFunctionality();
});

function initFullscreenFunctionality() {
    const fullscreenBtn = document.getElementById('fullscreen-btn');
    const exitFullscreenBtn = document.getElementById('exit-fullscreen-btn');
    const fullscreenOverlay = document.getElementById('fullscreen-overlay');
    
    if (fullscreenBtn) {
        fullscreenBtn.addEventListener('click', () => {
            fullscreenOverlay.style.display = 'block';
            document.body.style.overflow = 'hidden';
            
            // Initialize fullscreen map
            setTimeout(() => {
                if (!window.fullscreenLostFoundMap) {
                    initFullscreenMap();
                } else {
                    setTimeout(() => {
                        window.fullscreenLostFoundMap.invalidateSize();
                    }, 100);
                }
            }, 100);
        });
    }
    
    if (exitFullscreenBtn) {
        exitFullscreenBtn.addEventListener('click', () => {
            exitFullscreen();
        });
    }
    
    if (fullscreenOverlay) {
        fullscreenOverlay.addEventListener('click', (e) => {
            if (e.target === fullscreenOverlay) {
                exitFullscreen();
            }
        });
    }
    
    // Exit fullscreen on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && fullscreenOverlay && fullscreenOverlay.style.display === 'block') {
            exitFullscreen();
        }
    });
}

function initFullscreenMap() {
    window.fullscreenLostFoundMap = L.map('fullscreen-map', {
        zoomControl: false // Disable zoom controls
    }).setView([8.504588, 125.975800], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(window.fullscreenLostFoundMap);
    
    // Add markers to fullscreen map
    locations.forEach(location => {
        if (location.latitude && location.longitude) {
            const lat = parseFloat(location.latitude);
            const lng = parseFloat(location.longitude);
            
            // Choose color based on type
            const color = location.type === 'lost' ? '#e74c3c' : '#27ae60';
            const iconClass = location.type === 'lost' ? 'fas fa-heart-broken' : 'fas fa-heart';
            
            // Create custom icon with pet image if available
            let iconHtml = '';
            if (location.image_path) {
                // Use the pet image as the marker without the heart icon
                iconHtml = `<div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <img src="/storage/${location.image_path}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>`;
            } else {
                // Use the default icon if no image is available
                iconHtml = `<div style="background: ${color}; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <i class="${iconClass}" style="font-size: 16px;"></i>
                </div>`;
            }
            
            const customIcon = L.divIcon({
                html: iconHtml,
                iconSize: location.image_path ? [56, 56] : [46, 46],
                iconAnchor: location.image_path ? [28, 28] : [23, 23],
                popupAnchor: [0, -28],
                className: 'custom-marker'
            });
            
            // Create marker
            const marker = L.marker([lat, lng], { icon: customIcon });
            
            // Create popup content
            const popupContent = `
                <div style="min-width: 250px;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        ${location.image_path ? 
                            `<div style="width: 60px; height: 60px; border-radius: 8px; overflow: hidden; margin-right: 12px;">
                                <img src="/storage/${location.image_path}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>` : 
                            `<div style="background: ${color}; color: white; width: 60px; height: 60px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                <i class="${iconClass}" style="font-size: 24px;"></i>
                            </div>`
                        }
                        <div>
                            <h4 style="margin: 0; font-size: 1.1rem; color: #1f2937;">${location.pet_name}</h4>
                            <span style="background: #e5e7eb; color: #374151; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">${location.type.charAt(0).toUpperCase() + location.type.slice(1)} Pet</span>
                        </div>
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-paw" style="color: #667eea; margin-right: 6px;"></i>
                        ${location.pet_type} ${location.breed ? `(${location.breed})` : ''}
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-map-marker-alt" style="color: #667eea; margin-right: 6px;"></i>
                        ${location.location}
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-calendar" style="color: #667eea; margin-right: 6px;"></i>
                        ${new Date(location.date_lost_found).toLocaleDateString()}
                    </div>
                    <div style="margin-bottom: 12px; color: #4b5563;">
                        <i class="fas fa-user" style="color: #667eea; margin-right: 6px;"></i>
                        Reported by ${location.user.name}
                    </div>
                    <div style="display: flex; gap: 5px; margin-top: 12px;">
                        <a href="/lost-found/${location.id}" style="background: #667eea; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            marker.addTo(window.fullscreenLostFoundMap);
        }
    });
    
    // Ensure map renders properly
    setTimeout(() => {
        window.fullscreenLostFoundMap.invalidateSize();
    }, 100);
}

function exitFullscreen() {
    const fullscreenOverlay = document.getElementById('fullscreen-overlay');
    if (fullscreenOverlay) {
        fullscreenOverlay.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}
</script>
@endsection