@extends('layouts.app')

@section('title', 'Map')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .map-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px 0; color: white; text-align: center; }
    .map-title { font-size: 2rem; font-weight: 700; margin-bottom: 10px; }
    .map-subtitle { font-size: 1rem; opacity: 0.9; }

    .content-section { padding: 30px 15px; }
    .section-title { font-size: 1.75rem; font-weight: 700; color: #1f2937; margin-bottom: 20px; text-align: center; }

    .shelters-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px; }
    

    .shelter-card { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: all 0.3s; border: 2px solid transparent; }
    .shelter-card:hover { transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0,0,0,0.15); border-color: #667eea; }

    .shelter-header { display: flex; align-items: center; margin-bottom: 15px; }
    .shelter-icon { width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.25rem; margin-right: 12px; }
    .shelter-icon { width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.25rem; margin-right: 12px; }
    .icon-pet-shop { background: linear-gradient(135deg, #667eea, #764ba2); }
    .icon-veterinarian { background: linear-gradient(135deg, #10b981, #059669); }
    .icon-grooming { background: linear-gradient(135deg, #f59e0b, #d97706); }

    .shelter-info h3 { margin: 0 0 3px 0; font-size: 1.15rem; font-weight: 600; color: #1f2937; }
    .shelter-info p { margin: 0; color: #6b7280; font-size: 0.85rem; }

    .shelter-details { margin-bottom: 15px; }
    .detail-item { display: flex; align-items: flex-start; margin-bottom: 8px; color: #4b5563; }
    .detail-item i { width: 18px; margin-right: 8px; color: #667eea; margin-top: 3px; }

    .type-badge { padding: 5px 10px; border-radius: 16px; font-size: 0.75rem; font-weight: 600; display: inline-block; margin-bottom: 12px; }
    .type-pet-shop { background: #ddd6fe; color: #5b21b6; }
    .type-veterinarian { background: #dcfce7; color: #166534; }
    .type-grooming { background: #fef3c7; color: #92400e; }

    .btn { padding: 10px 20px; border-radius: 6px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; text-align: center; display: inline-block; font-size: 0.9rem; }
    .btn-primary { background: #667eea; color: white; }
    .btn-primary:hover { background: #5a67d8; color: white; text-decoration: none; }
    .btn-secondary { background: #9ca3af; color: white; }
    .btn-secondary:hover { background: #6b7280; color: white; text-decoration: none; }

    .empty-state { text-align: center; padding: 40px 15px; color: #6b7280; }
    .empty-state i { font-size: 3rem; margin-bottom: 15px; opacity: 0.3; }
    .empty-state h3 { margin-bottom: 8px; color: #374151; font-size: 1.5rem; }

    /* Map Styles */
    .map-section { background: white; border-radius: 12px; padding: 20px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .map-container { 
        height: 450px; 
        border-radius: 10px; 
        overflow: hidden; 
        border: 2px solid #e5e7eb; 
        position: relative; 
        width: 100%;
    }
    
    /* Map Controls */
    .map-controls { position: absolute; top: 8px; right: 8px; z-index: 1000; display: flex; gap: 4px; }
    .map-btn { background: white; border: 1px solid #ccc; border-radius: 4px; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.2s; }
    .map-btn:hover { background: #f5f5f5; box-shadow: 0 2px 6px rgba(0,0,0,0.15); }
    .map-btn i { font-size: 12px; color: #333; }
    .fullscreen-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.8); z-index: 9999; display: none; }
    
    /* Responsive styles for mobile */
    @media (max-width: 768px) {
        .map-header { padding: 25px 0; }
        .map-title { font-size: 1.75rem; }
        .map-subtitle { font-size: 0.9rem; }
        
        .content-section { padding: 25px 15px; }
        .section-title { font-size: 1.5rem; margin-bottom: 15px; }
        
        .shelters-grid { 
            grid-template-columns: 1fr; 
            gap: 15px; 
            margin-bottom: 25px; 
        }
        
        .shelter-card { 
            padding: 15px; 
            border-radius: 10px; 
        }
        
        .shelter-header { margin-bottom: 12px; }
        .shelter-icon { 
            width: 45px; 
            height: 45px; 
            border-radius: 8px; 
            font-size: 1rem; 
            margin-right: 10px; 
        }
        
        .shelter-info h3 { font-size: 1.1rem; }
        .shelter-info p { font-size: 0.8rem; }
        
        .detail-item { margin-bottom: 6px; }
        .detail-item i { width: 16px; margin-right: 6px; }
        
        .type-badge { 
            padding: 4px 8px; 
            border-radius: 14px; 
            font-size: 0.7rem; 
            margin-bottom: 10px; 
        }
        
        .btn { 
            padding: 8px 16px; 
            border-radius: 5px; 
            font-size: 0.85rem; 
        }
        
        .map-section { 
            padding: 15px; 
            border-radius: 10px; 
            margin-bottom: 25px; 
        }
        
        .map-container { 
            height: 350px; 
            border-radius: 8px; 
        }
        
        .map-controls { 
            top: 6px; 
            right: 6px; 
            gap: 3px; 
        }
        
        .map-btn { 
            width: 24px; 
            height: 24px; 
        }
        
        .map-btn i { font-size: 10px; }
        
        .empty-state { padding: 30px 15px; }
        .empty-state i { font-size: 2.5rem; margin-bottom: 12px; }
        .empty-state h3 { font-size: 1.25rem; }
    }
    
    @media (max-width: 576px) {
        .map-header { padding: 20px 0; }
        .map-title { font-size: 1.5rem; }
        .map-subtitle { font-size: 0.85rem; }
        
        .content-section { padding: 20px 10px; }
        .section-title { font-size: 1.35rem; margin-bottom: 12px; }
        
        .shelters-grid { gap: 12px; margin-bottom: 20px; }
        
        .shelter-card { padding: 12px; }
        
        .shelter-header { margin-bottom: 10px; }
        .shelter-icon { 
            width: 40px; 
            height: 40px; 
            border-radius: 6px; 
            font-size: 0.9rem; 
            margin-right: 8px; 
        }
        
        .shelter-info h3 { font-size: 1rem; }
        .shelter-info p { font-size: 0.75rem; }
        
        .detail-item { margin-bottom: 5px; }
        .detail-item i { width: 14px; margin-right: 5px; }
        
        .type-badge { 
            padding: 3px 6px; 
            border-radius: 12px; 
            font-size: 0.65rem; 
            margin-bottom: 8px; 
        }
        
        .btn { 
            padding: 6px 12px; 
            border-radius: 4px; 
            font-size: 0.8rem; 
        }
        
        .map-section { 
            padding: 12px; 
            border-radius: 8px; 
            margin-bottom: 20px; 
        }
        
        .map-container { 
            height: 280px; 
            border-radius: 6px; 
        }
        
        .map-controls { top: 5px; right: 5px; gap: 2px; }
        
        .map-btn { 
            width: 22px; 
            height: 22px; 
        }
        
        .map-btn i { font-size: 9px; }
        
        .empty-state { padding: 25px 10px; }
        .empty-state i { font-size: 2rem; margin-bottom: 10px; }
        .empty-state h3 { font-size: 1.1rem; }
    }
</style>
@endsection

@section('content')
<div class="map-header">
    <h1 class="map-title">Find Vet Shops & Services</h1>
    <p class="map-subtitle">Find shelters, services, and lost/found pets near you</p>
</div>

<div class="content-section">
    <div class="map-section">
        <h2 class="section-title">Location Map</h2>
        <div class="map-container">
            <div id="shelterMap" style="height: 100%; width: 100%;"></div>
            <div class="map-controls">
                <button id="fullscreen-btn" class="map-btn" title="Fullscreen">
                    <i class="fas fa-expand"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="content-section">
        <h2 class="section-title">Available Shelters & Services</h2>
        
        @if($shelters->count() > 0)
            <div class="shelters-grid">
                @foreach($shelters as $shelter)
                    <div class="shelter-card">
                        <div class="shelter-header">
                            <div class="shelter-icon icon-veterinarian">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div class="shelter-info">
                                <h3>{{ $shelter->name }}</h3>
                                <p>{{ $shelter->city }}, {{ $shelter->province }}</p>
                            </div>
                        </div>

                        <span class="type-badge type-veterinarian">
                            Veterinarian
                        </span>

                        @if($shelter->description)
                            <p style="margin-bottom: 20px; color: #4b5563;">{{ $shelter->description }}</p>
                        @endif

                        <div class="shelter-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span style="word-break: break-word;">{{ $shelter->address }}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-phone"></i>
                                <span style="word-break: break-word;">{{ $shelter->phone }}</span>
                            </div>
                            @if($shelter->email)
                                <div class="detail-item">
                                    <i class="fas fa-envelope"></i>
                                    <span style="word-break: break-word;">{{ $shelter->email }}</span>
                                </div>
                            @endif
                            @if($shelter->operating_hours && isset($shelter->operating_hours['weekdays']))
                                <div class="detail-item">
                                    <i class="fas fa-clock"></i>
                                    <span style="word-break: break-word;">{{ $shelter->operating_hours['weekdays'] ?? 'Contact for hours' }}</span>
                                </div>
                            @endif
                        </div>

                        <div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 8px; margin-top: 15px;">
                            <p style="margin: 0; color: #6b7280; font-size: 0.9rem;">
                                <i class="fas fa-info-circle"></i> View details on map
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-paw"></i>
                <h3>No shelters available</h3>
                <p>Check back later for shelter locations.</p>
            </div>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="notification" style="position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 15px 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 1000;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    <script>
        setTimeout(function() {
            document.querySelector('.notification').style.display = 'none';
        }, 5000);
    </script>

@endif

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
// Function to get human-readable name for shelter type
function getShelterTypeName(type) {
    switch(type) {
        case 'pet_shop':
            return 'Vet Shop';
        case 'veterinarian':
            return 'Veterinarian';
        case 'grooming':
            return 'Grooming Service';
        default:
            return 'Veterinarian';
    }
}

// Combine shelters and lost/found items for the map
const mapShelters = @json($shelters);
const mapLostFoundItems = @json($lostFoundItems);
const mapData = [...mapShelters, ...mapLostFoundItems];

// Initialize map when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Wait a bit for the assets to load
    setTimeout(function() {
        if (typeof SharedMap !== 'undefined') {
            // Initialize the shared map component
            const sharedMap = new SharedMap('shelterMap', mapData, {
                fullscreenEnabled: true,
                showViewDetails: true,
                viewDetailsRoute: '/view-map/'
            });
            
            
            // Check if we need to focus on a specific shelter
            const urlParams = new URLSearchParams(window.location.search);
            const focusShelterId = urlParams.get('shelter');
            
            if (focusShelterId) {
                // Find the shelter in our data
                const focusShelter = mapData.find(item => item.id == focusShelterId && item.latitude && item.longitude);
                
                if (focusShelter) {
                    // Wait a bit for the map to initialize, then focus on the shelter
                    setTimeout(function() {
                        // Center the map on the specific shelter with zoom level 15
                        sharedMap.map.setView([parseFloat(focusShelter.latitude), parseFloat(focusShelter.longitude)], 15);
                        
                        // Find the marker for this shelter and open its popup
                        sharedMap.markersLayer.eachLayer(function(layer) {
                            if (layer.options && layer.options.icon && layer.options.icon.options.html) {
                                // Check if this is the marker for our focus shelter
                                if (layer._latlng.lat === parseFloat(focusShelter.latitude) && 
                                    layer._latlng.lng === parseFloat(focusShelter.longitude)) {
                                    // Open the popup for this marker
                                    layer.openPopup();
                                }
                            }
                        });
                    }, 500);
                }
            }
            
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
    }).setView([8.504588, 125.975800], 15);
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Add markers for shelters
    mapShelters.forEach(shelter => {
        if (shelter.latitude && shelter.longitude) {
            const lat = parseFloat(shelter.latitude);
            const lng = parseFloat(shelter.longitude);
            
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
                            <h4 style="margin: 0; font-size: 1.1rem; color: #1f2937;">${shelter.name}</h4>
                            <span style="background: #e5e7eb; color: #374151; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Veterinarian</span>
                        </div>
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563; word-break: break-word;">
                        <i class="fas fa-map-marker-alt" style="color: #667eea; margin-right: 6px;"></i>
                        ${shelter.address}<br>
                        ${shelter.city}, ${shelter.province}
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-phone" style="color: #667eea; margin-right: 6px;"></i>
                        ${shelter.phone || 'Not provided'}
                    </div>
                    ${shelter.email ? `
                        <div style="margin-bottom: 12px; color: #4b5563; word-break: break-word;">
                            <i class="fas fa-envelope" style="color: #667eea; margin-right: 6px;"></i>
                            ${shelter.email}
                        </div>
                    ` : ''}
                    <div style="display: flex; gap: 5px; margin-top: 12px;">
                        <a href="/view-map/${shelter.id}" style="background: #667eea; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            marker.addTo(map);
        }
    });
    
    // Add markers for lost/found items
    mapLostFoundItems.forEach(item => {
        if (item.latitude && item.longitude) {
            const lat = parseFloat(item.latitude);
            const lng = parseFloat(item.longitude);
            
            // Create custom icon with pet image if available
            let iconHtml = '';
            if (item.image_path) {
                // Use the pet image as the marker without any icon overlay
                iconHtml = `<div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <img src="/storage/${item.image_path}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>`;
            } else {
                // Use the default icon if no image is available
                const color = item.type === 'lost' ? '#e74c3c' : '#27ae60';
                const iconClass = item.type === 'lost' ? 'fas fa-heart-broken' : 'fas fa-heart';
                iconHtml = `<div style="background: ${color}; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <i class="${iconClass}" style="font-size: 16px;"></i>
                </div>`;
            }
            
            const customIcon = L.divIcon({
                html: iconHtml,
                iconSize: item.image_path ? [56, 56] : [46, 46],
                iconAnchor: item.image_path ? [28, 28] : [23, 23],
                popupAnchor: [0, -28],
                className: 'custom-marker'
            });
            
            // Create marker
            const marker = L.marker([lat, lng], { icon: customIcon });
            
            // Create popup content
            const popupContent = `
                <div style="min-width: 250px;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        ${item.image_path ? 
                            `<div style="width: 60px; height: 60px; border-radius: 8px; overflow: hidden; margin-right: 12px;">
                                <img src="/storage/${item.image_path}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>` : 
                            `<div style="background: ${item.type === 'lost' ? '#e74c3c' : '#27ae60'}; color: white; width: 60px; height: 60px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                <i class="fas ${item.type === 'lost' ? 'fa-heart-broken' : 'fa-heart'}" style="font-size: 24px;"></i>
                            </div>`
                        }
                        <div>
                            <h4 style="margin: 0; font-size: 1.1rem; color: #1f2937;">${item.pet_name}</h4>
                            <span style="background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Lost/Found</span>
                        </div>
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-tag" style="color: #667eea; margin-right: 6px;"></i>
                        ${item.pet_type}
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-calendar" style="color: #667eea; margin-right: 6px;"></i>
                        Reported: ${new Date(item.created_at).toLocaleDateString()}
                    </div>
                    <div style="margin-bottom: 12px; color: #4b5563;">
                        <i class="fas fa-user" style="color: #667eea; margin-right: 6px;"></i>
                        ${item.user ? item.user.name : 'Anonymous'}
                    </div>
                    <div style="display: flex; gap: 5px; margin-top: 12px;">
                        <a href="/lost-found/${item.id}" style="background: #667eea; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
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
    const mapShelters = @json($shelters);
    const mapLostFoundItems = @json($lostFoundItems);
    const fullscreenMapData = [...mapShelters, ...mapLostFoundItems];
    
    window.fullscreenMap = L.map('fullscreen-map', {
        zoomControl: false // Disable zoom controls
    }).setView([8.504588, 125.975800], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(window.fullscreenMap);
    
    // Add markers to fullscreen map for shelters
    mapShelters.forEach(shelter => {
        if (shelter.latitude && shelter.longitude) {
            const lat = parseFloat(shelter.latitude);
            const lng = parseFloat(shelter.longitude);
            
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
                            <h4 style="margin: 0; font-size: 1.1rem; color: #1f2937;">${shelter.name}</h4>
                            <span style="background: #e5e7eb; color: #374151; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Veterinarian</span>
                        </div>
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563; word-break: break-word;">
                        <i class="fas fa-map-marker-alt" style="color: #667eea; margin-right: 6px;"></i>
                        ${shelter.address}<br>
                        ${shelter.city}, ${shelter.province}
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-phone" style="color: #667eea; margin-right: 6px;"></i>
                        ${shelter.phone || 'Not provided'}
                    </div>
                    ${shelter.email ? `
                        <div style="margin-bottom: 12px; color: #4b5563; word-break: break-word;">
                            <i class="fas fa-envelope" style="color: #667eea; margin-right: 6px;"></i>
                            ${shelter.email}
                        </div>
                    ` : ''}
                    <div style="display: flex; gap: 5px; margin-top: 12px;">
                        <a href="/view-map/${shelter.id}" style="background: #667eea; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            marker.addTo(window.fullscreenMap);
        }
    });
    
    // Add markers to fullscreen map for lost/found items
    mapLostFoundItems.forEach(item => {
        if (item.latitude && item.longitude) {
            const lat = parseFloat(item.latitude);
            const lng = parseFloat(item.longitude);
            
            // Create custom icon with pet image if available
            let iconHtml = '';
            if (item.image_path) {
                // Use the pet image as the marker without any icon overlay
                iconHtml = `<div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <img src="/storage/${item.image_path}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>`;
            } else {
                // Use the default icon if no image is available
                const color = item.type === 'lost' ? '#e74c3c' : '#27ae60';
                const iconClass = item.type === 'lost' ? 'fas fa-heart-broken' : 'fas fa-heart';
                iconHtml = `<div style="background: ${color}; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <i class="${iconClass}" style="font-size: 16px;"></i>
                </div>`;
            }
            
            const customIcon = L.divIcon({
                html: iconHtml,
                iconSize: item.image_path ? [56, 56] : [46, 46],
                iconAnchor: item.image_path ? [28, 28] : [23, 23],
                popupAnchor: [0, -28],
                className: 'custom-marker'
            });
            
            // Create marker
            const marker = L.marker([lat, lng], { icon: customIcon });
            
            // Create popup content
            const popupContent = `
                <div style="min-width: 250px;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        ${item.image_path ? 
                            `<div style="width: 60px; height: 60px; border-radius: 8px; overflow: hidden; margin-right: 12px;">
                                <img src="/storage/${item.image_path}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>` : 
                            `<div style="background: ${item.type === 'lost' ? '#e74c3c' : '#27ae60'}; color: white; width: 60px; height: 60px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                <i class="fas ${item.type === 'lost' ? 'fa-heart-broken' : 'fa-heart'}" style="font-size: 24px;"></i>
                            </div>`
                        }
                        <div>
                            <h4 style="margin: 0; font-size: 1.1rem; color: #1f2937;">${item.pet_name}</h4>
                            <span style="background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Lost/Found</span>
                        </div>
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-tag" style="color: #667eea; margin-right: 6px;"></i>
                        ${item.pet_type}
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-calendar" style="color: #667eea; margin-right: 6px;"></i>
                        Reported: ${new Date(item.created_at).toLocaleDateString()}
                    </div>
                    <div style="margin-bottom: 12px; color: #4b5563;">
                        <i class="fas fa-user" style="color: #667eea; margin-right: 6px;"></i>
                        ${item.user ? item.user.name : 'Anonymous'}
                    </div>
                    <div style="display: flex; gap: 5px; margin-top: 12px;">
                        <a href="/lost-found/${item.id}" style="background: #667eea; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            marker.addTo(window.fullscreenMap);
        }
    });
    
    // Add click handler to exit fullscreen when clicking on the map
    window.fullscreenMap.on('click', function() {
        exitFullscreen();
    });
}

function exitFullscreen() {
    const fullscreenOverlay = document.getElementById('fullscreen-overlay');
    if (fullscreenOverlay) {
        fullscreenOverlay.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}
</script>

<!-- Fullscreen Overlay -->
<div id="fullscreen-overlay" class="fullscreen-overlay">
    <div id="fullscreen-map" style="height: 100%; width: 100%;"></div>
    <div class="map-controls">
        <button id="exit-fullscreen-btn" class="map-btn" title="Exit Fullscreen">
            <i class="fas fa-compress"></i>
        </button>
    </div>
</div>
@endsection