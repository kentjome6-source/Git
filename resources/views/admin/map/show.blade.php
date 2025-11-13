@extends('layouts.admin')

@section('title', 'Location Details - View Map')

@section('styles')
<style>
    .shelter-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px 0; color: white; text-align: center; }
    .shelter-title { font-size: 2rem; font-weight: 700; margin-bottom: 10px; }
    .shelter-subtitle { font-size: 1rem; opacity: 0.9; }

    .content-section { padding: 30px 15px; max-width: 1200px; margin: 0 auto; }
    
    .details-card { background: white; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .card-header { border-bottom: 2px solid #e5e7eb; margin-bottom: 20px; padding-bottom: 12px; }
    .section-title { font-size: 1.3rem; font-weight: 700; color: #1f2937; margin: 0; display: flex; align-items: center; gap: 8px; }
    
    .shelter-overview { display: flex; flex-direction: column; gap: 20px; }
    .shelter-info { }
    .shelter-header-info { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
    .shelter-icon { width: 60px; height: 60px; border-radius: 12px; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; flex-shrink: 0; }
    .icon-grooming { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .icon-veterinarian { background: linear-gradient(135deg, #10b981, #059669); }
    .icon-pet-shop { background: linear-gradient(135deg, #667eea, #764ba2); }
    .shelter-details h2 { font-size: 1.5rem; font-weight: 700; color: #1f2937; margin: 0 0 8px 0; }
    .badge { padding: 5px 12px; border-radius: 15px; font-size: 0.8rem; font-weight: 600; margin-right: 8px; }
    .badge-primary { background: #ddd6fe; color: #5b21b6; }
    .badge-success { background: #dcfce7; color: #166534; }
    .badge-info { background: #dbeafe; color: #1e40af; }
    .badge-warning { background: #fef3c7; color: #92400e; }
    .badge-active { background: #d1fae5; color: #065f46; }
    .badge-inactive { background: #fee2e2; color: #991b1b; }
    
    .shelter-description { color: #6b7280; margin: 12px 0; font-size: 1rem; line-height: 1.6; }
    
    .action-buttons { display: flex; flex-direction: column; gap: 8px; }
    .btn { padding: 10px 16px; border-radius: 6px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-size: 0.9rem; }
    .btn-primary { background: #667eea; color: white; }
    .btn-primary:hover { background: #5a67d8; }
    .btn-secondary { background: #6b7280; color: white; }
    .btn-secondary:hover { background: #4b5563; }
    
    .info-grid { display: flex; flex-direction: column; gap: 20px; }
    .contact-info { }
    .contact-item { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 15px; }
    .contact-icon { width: 20px; text-align: center; color: #667eea; font-size: 0.9rem; margin-top: 3px; }
    .contact-details strong { color: #1f2937; display: block; margin-bottom: 3px; font-size: 0.95rem; }
    .contact-details span { color: #6b7280; font-size: 0.9rem; }
    .contact-details a { color: #667eea; text-decoration: none; font-size: 0.9rem; }
    .contact-details a:hover { text-decoration: underline; }
    
    .hours-list { list-style: none; padding: 0; }
    .hours-list li { padding: 6px 0; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; }
    .hours-list li:last-child { border-bottom: none; }
    .day-name { font-weight: 600; color: #1f2937; font-size: 0.9rem; }
    .day-time { color: #6b7280; font-size: 0.9rem; }
    
    .map-container { 
        height: 300px; 
        border-radius: 10px; 
        overflow: hidden; 
        border: 2px solid #e5e7eb; 
        position: relative; 
        width: 100%;
        /* Ensure the map is visible on all devices */
        display: block !important;
        min-height: 250px;
        /* Add z-index to ensure map is visible */
        z-index: 10;
    }
    
    .map-fullscreen { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 10000; border-radius: 0; border: none; }
    .map-controls { position: absolute; top: 8px; right: 8px; z-index: 1000; display: flex; gap: 4px; }
    .map-btn { background: white; border: 1px solid #ccc; border-radius: 4px; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.2s; }
    .map-btn:hover { background: #f5f5f5; box-shadow: 0 2px 6px rgba(0,0,0,0.15); }
    .map-btn i { font-size: 12px; color: #333; }
    .fullscreen-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.8); z-index: 9999; display: none; }
    .no-map { height: 200px; display: flex; align-items: center; justify-content: center; flex-direction: column; background: #f8fafc; border-radius: 10px; color: #6b7280; }
    .no-map i { font-size: 2rem; margin-bottom: 10px; }
    
    .alert { padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; }
    .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    
    /* Mobile-specific styles */
    @media (min-width: 768px) {
        .shelter-header { padding: 40px 0; }
        .shelter-title { font-size: 2.5rem; margin-bottom: 15px; }
        .shelter-subtitle { font-size: 1.2rem; }
        
        .content-section { padding: 40px 20px; }
        .details-card { padding: 30px; margin-bottom: 25px; }
        .card-header { margin-bottom: 25px; padding-bottom: 15px; }
        .section-title { font-size: 1.5rem; gap: 10px; }
        
        .shelter-overview { flex-direction: row; gap: 30px; }
        .shelter-header-info { gap: 20px; margin-bottom: 20px; }
        .shelter-icon { width: 80px; height: 80px; border-radius: 15px; font-size: 2rem; }
        .shelter-details h2 { font-size: 2rem; margin: 0 0 10px 0; }
        .badge { padding: 6px 16px; border-radius: 20px; font-size: 0.9rem; margin-right: 10px; }
        
        .shelter-description { margin: 15px 0; font-size: 1.1rem; }
        
        .action-buttons { flex-direction: column; gap: 10px; }
        .btn { padding: 12px 20px; border-radius: 8px; gap: 8px; font-size: 1rem; }
        
        .info-grid { flex-direction: row; gap: 25px; }
        .contact-item { gap: 12px; margin-bottom: 20px; }
        .contact-icon { width: 24px; font-size: 1rem; margin-top: 3px; }
        .contact-details strong { margin-bottom: 4px; font-size: 1rem; }
        .contact-details span { font-size: 1rem; }
        .contact-details a { font-size: 1rem; }
        
        .hours-list li { padding: 8px 0; }
        .day-name { font-size: 1rem; }
        .day-time { font-size: 1rem; }
        
        .map-container { height: 400px; }
        .no-map { height: 300px; }
        .no-map i { font-size: 3rem; margin-bottom: 15px; }
    }
    
    @media (min-width: 992px) {
        .shelter-overview { display: grid; grid-template-columns: 1fr auto; gap: 30px; align-items: start; }
    }

    /* Desktop-specific adjustments for wider map and contact sections */
    @media (min-width: 1200px) {
        .info-grid { 
            flex-direction: row; 
            gap: 30px; 
        }
        
        .details-card { 
            flex: 1; 
            min-width: 0; 
        }
        
        /* Make the map section wider on desktop */
        .info-grid > .details-card:last-child { 
            flex: 1.5; 
        }
        
        .map-container { 
            height: 450px; 
        }
    }
    
    /* Mobile-specific fix for map visibility */
    @media (max-width: 767.98px) {
        .map-container {
            height: 300px;
            min-height: 250px;
            /* Additional mobile-specific properties */
            max-width: 100vw;
            box-sizing: border-box;
            /* Ensure map is visible on top of other elements */
            z-index: 100;
        }
        
        .content-section {
            padding: 20px 10px;
        }
        
        .details-card {
            padding: 15px;
        }
        
        .map-controls {
            top: 6px;
            right: 6px;
        }
        
        .map-btn {
            width: 26px;
            height: 26px;
        }
        
        .map-btn i {
            font-size: 10px;
        }
        
        /* Ensure map is visible on mobile */
        #shelter-map {
            visibility: visible !important;
            display: block !important;
            /* Add specific mobile properties */
            width: 100% !important;
            height: 300px !important;
            position: relative !important;
            z-index: 100 !important;
        }
        
        /* Fix for mobile Safari and other mobile browsers */
        #shelter-map .leaflet-container {
            width: 100% !important;
            height: 300px !important;
        }
        
        .shelter-header {
            padding: 25px 0;
        }
        
        .shelter-title {
            font-size: 1.8rem;
        }
        
        .shelter-subtitle {
            font-size: 0.95rem;
        }
    }
    
    @media (max-width: 576px) {
        .map-container {
            height: 250px;
        }
        
        .shelter-header {
            padding: 20px 0;
        }
        
        .shelter-title {
            font-size: 1.75rem;
        }
        
        .shelter-subtitle {
            font-size: 0.9rem;
        }
        
        /* Additional fix for very small screens */
        .map-container {
            height: 230px;
            min-height: 200px;
        }
        
        #shelter-map {
            height: 230px !important;
        }
        
        #shelter-map .leaflet-container {
            height: 230px !important;
        }
    }
</style>
@endsection

@section('content')
<div class="shelter-header">
    <div class="container">
        <h1 class="shelter-title">Location Details</h1>
        <p class="shelter-subtitle">Complete information about {{ $shelter->name }}</p>
    </div>
</div>

<div class="content-section">
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

    <!-- Location Overview -->
    <div class="details-card">
        <div class="shelter-overview">
            <div class="shelter-info">
                <div class="shelter-header-info">
                    <div class="shelter-icon icon-{{ $shelter->type }}">
                        @if($shelter->type === 'pet_shop')
                            <i class="fas fa-store"></i>
                        @elseif($shelter->type === 'veterinarian')
                            <i class="fas fa-user-md"></i>
                        @elseif($shelter->type === 'grooming')
                            <i class="fas fa-cut"></i>
                        @else
                            <i class="fas fa-home"></i>
                        @endif
                    </div>
                    <div class="shelter-details">
                        <h2>{{ $shelter->name }}</h2>
                        <div>
                            <span class="badge badge-{{ $shelter->type === 'pet_shop' ? 'primary' : ($shelter->type === 'veterinarian' ? 'success' : ($shelter->type === 'grooming' ? 'warning' : 'info')) }}">
                                {{ $shelter->type_name }}
                            </span>
                            <span class="badge badge-{{ $shelter->is_active ? 'active' : 'inactive' }}">
                                {{ $shelter->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                @if($shelter->description)
                    <p class="shelter-description">{{ $shelter->description }}</p>
                @endif
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('admin.map.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Map
                </a>
            </div>
        </div>
    </div>

    <div class="info-grid">
        <!-- Contact & Location Info -->
        <div class="details-card">
            <div class="card-header">
                <h3 class="section-title"><i class="fas fa-info-circle"></i> Contact & Location</h3>
            </div>
            
            <div class="contact-info">
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="contact-details">
                        <strong>Address:</strong>
                        <span>{{ $shelter->address }}</span>
                    </div>
                </div>
                
                @if($shelter->phone)
                    <div class="contact-item">
                        <div class="contact-icon"><i class="fas fa-phone"></i></div>
                        <div class="contact-details">
                            <strong>Phone:</strong>
                            <a href="tel:{{ $shelter->phone }}">{{ $shelter->phone }}</a>
                        </div>
                    </div>
                @endif

                @if($shelter->email)
                    <div class="contact-item">
                        <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                        <div class="contact-details">
                            <strong>Email:</strong>
                            <a href="mailto:{{ $shelter->email }}">{{ $shelter->email }}</a>
                        </div>
                    </div>
                @endif

                @if($shelter->website)
                    <div class="contact-item">
                        <div class="contact-icon"><i class="fas fa-globe"></i></div>
                        <div class="contact-details">
                            <strong>Website:</strong>
                            <a href="{{ $shelter->website }}" target="_blank">{{ $shelter->website }}</a>
                        </div>
                    </div>
                @endif

                @if($shelter->operating_hours)
                    <div class="contact-item">
                        <div class="contact-icon"><i class="fas fa-clock"></i></div>
                        <div class="contact-details">
                            <strong>Operating Hours:</strong>
                            @php
                                $hours = is_string($shelter->operating_hours) ? json_decode($shelter->operating_hours, true) : $shelter->operating_hours;
                            @endphp
                            @if($hours)
                                <ul class="hours-list">
                                    @foreach($hours as $day => $time)
                                        <li>
                                            <span class="day-name">{{ ucfirst($day) }}:</span>
                                            <span class="day-time">{{ $time }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span>Not specified</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Map -->
        <div class="details-card">
            <div class="card-header">
                <h3 class="section-title"><i class="fas fa-map"></i> Location Map</h3>
            </div>
            
            @if($shelter->latitude && $shelter->longitude)
                <div id="shelter-map" class="map-container">
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
            @else
                <div class="no-map">
                    <i class="fas fa-map-marker-alt"></i>
                    <p>No location coordinates available</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('scripts')
@if($shelter->latitude && $shelter->longitude)
<script>
    // Simple and robust map initialization
    let mapInitialized = false;
    
    function initMap() {
        // Prevent multiple initializations
        if (mapInitialized) return;
        
        const mapContainer = document.getElementById('shelter-map');
        if (!mapContainer) return;
        
        try {
            // Ensure container is visible and properly sized
            mapContainer.style.display = 'block';
            mapContainer.style.width = '100%';
            mapContainer.style.height = window.innerWidth <= 576 ? '250px' : '400px';
            
            // Clear any existing content
            mapContainer.innerHTML = '';
            
            // Load Leaflet dynamically if not available
            if (typeof L === 'undefined') {
                // Load CSS
                const css = document.createElement('link');
                css.rel = 'stylesheet';
                css.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                document.head.appendChild(css);
                
                // Load JS
                const script = document.createElement('script');
                script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                script.onload = function() {
                    createMap();
                };
                document.head.appendChild(script);
            } else {
                createMap();
            }
        } catch (error) {
            console.error('Map initialization error:', error);
            showError(mapContainer, 'Failed to initialize map: ' + error.message);
        }
    }
    
    function createMap() {
        try {
            const mapContainer = document.getElementById('shelter-map');
            if (!mapContainer) return;
            
            // Parse coordinates
            const lat = parseFloat({{ $shelter->latitude }});
            const lng = parseFloat({{ $shelter->longitude }});
            
            if (isNaN(lat) || isNaN(lng)) {
                throw new Error('Invalid coordinates');
            }
            
            // Remove any existing map
            if (mapContainer._leaflet_id) {
                mapContainer._leaflet_id = null;
            }
            
            // Create map with explicit options
            const map = L.map('shelter-map', {
                center: [lat, lng],
                zoom: 15,
                zoomControl: false,
                attributionControl: true
            });
            
            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 18
            }).addTo(map);
            
            // Add marker
            const marker = L.marker([lat, lng]).addTo(map);
            
            // Add popup
            marker.bindPopup(`
                <b>{{ $shelter->name }}</b><br>
                {{ $shelter->address }}
                @if($shelter->phone)
                <br>Phone: {{ $shelter->phone }}
                @endif
            `);
            
            // Force resize
            setTimeout(() => {
                map.invalidateSize();
            }, 100);
            
            mapInitialized = true;
            console.log('Map initialized successfully');
            
        } catch (error) {
            console.error('Map creation error:', error);
            const mapContainer = document.getElementById('shelter-map');
            if (mapContainer) {
                showError(mapContainer, 'Failed to create map: ' + error.message);
            }
        }
    }
    
    function showError(container, message) {
        container.innerHTML = `
            <div style="display: flex; align-items: center; justify-content: center; height: 100%; flex-direction: column; padding: 20px; text-align: center; color: #666;">
                <i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 15px; color: #e74c3c;"></i>
                <p style="margin: 0; font-size: 1.1rem;">${message}</p>
                <button onclick="initMap()" style="margin-top: 15px; padding: 8px 16px; background: #667eea; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    <i class="fas fa-redo"></i> Retry
                </button>
            </div>
        `;
    }
    
    // Initialize map when page is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMap);
    } else {
        initMap();
    }
    
    // Also initialize on window load for extra reliability
    window.addEventListener('load', function() {
        setTimeout(initMap, 500);
    });
</script>
@endif
@endsection