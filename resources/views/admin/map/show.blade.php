@extends('layouts.admin')

@section('title', 'Location Details - Map')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    :root {
        --primary: #0f172a;
        --primary-light: #1e293b;
        --accent: #3b82f6;
        --accent-light: #60a5fa;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --info: #06b6d4;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --bg-primary: #ffffff;
        --bg-secondary: #f8fafc;
        --border-color: #e2e8f0;
        --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        --radius: 12px;
        --radius-lg: 16px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        background-color: var(--bg-secondary);
        color: var(--text-primary);
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .content-section {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1.5rem;
    }

    /* Back Button */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 0.9375rem;
        font-weight: 500;
        margin-bottom: 1.5rem;
        transition: var(--transition);
        animation: slideDown 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .back-link:hover {
        color: var(--accent);
        transform: translateX(-4px);
    }

    .back-link i {
        font-size: 0.875rem;
    }

    /* Alert */
    .alert {
        padding: 1rem 1.25rem;
        border-radius: var(--radius);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
        animation: slideDown 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        color: #065f46;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .alert-error {
        background: rgba(239, 68, 68, 0.1);
        color: #991b1b;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    /* Details Card */
    .details-card {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 1.5rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) backwards;
    }

    .details-card:nth-child(2) { animation-delay: 0.1s; }
    .details-card:nth-child(3) { animation-delay: 0.2s; }

    /* Location Header */
    .location-header {
        display: flex;
        align-items: flex-start;
        gap: 1.5rem;
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--border-color);
    }

    .location-icon {
        width: 80px;
        height: 80px;
        border-radius: var(--radius-lg);
        background: linear-gradient(135deg, var(--success), #34d399);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        flex-shrink: 0;
        box-shadow: var(--shadow-lg);
        transition: var(--transition);
    }

    .details-card:hover .location-icon {
        transform: scale(1.05);
    }

    .location-info {
        flex: 1;
    }

    .location-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        letter-spacing: -0.025em;
    }

    .location-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .badge {
        padding: 0.375rem 0.875rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        letter-spacing: 0.025em;
    }

    .badge-success {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .badge-active {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .badge-inactive {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .location-description {
        color: var(--text-secondary);
        font-size: 1rem;
        line-height: 1.7;
        margin: 0;
    }

    /* Section */
    .section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        letter-spacing: -0.025em;
    }

    .section-title i {
        color: var(--accent);
        font-size: 1.125rem;
    }

    /* Contact Info */
    .contact-list {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .contact-icon {
        width: 42px;
        height: 42px;
        border-radius: var(--radius);
        background: var(--bg-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: var(--transition);
    }

    .contact-item:hover .contact-icon {
        background: var(--accent);
        color: white;
    }

    .contact-icon i {
        color: var(--accent);
        font-size: 1rem;
        transition: var(--transition);
    }

    .contact-item:hover .contact-icon i {
        color: white;
    }

    .contact-details {
        flex: 1;
    }

    .contact-label {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }

    .contact-value {
        font-size: 0.9375rem;
        color: var(--text-primary);
        font-weight: 500;
    }

    .contact-value a {
        color: var(--accent);
        text-decoration: none;
        transition: var(--transition);
    }

    .contact-value a:hover {
        color: var(--accent-light);
        text-decoration: underline;
    }

    /* Hours List */
    .hours-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .hours-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .hours-item:last-child {
        border-bottom: none;
    }

    .hours-day {
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--text-primary);
    }

    .hours-time {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    /* Grid Layout */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    /* Map */
    .map-container {
        height: 450px;
        border-radius: var(--radius);
        overflow: hidden;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        position: relative;
    }

    .map-controls {
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 1000;
    }

    .map-btn {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: var(--shadow);
        transition: var(--transition);
    }

    .map-btn:hover {
        background: var(--bg-secondary);
        box-shadow: var(--shadow-md);
    }

    .map-btn i {
        font-size: 0.875rem;
        color: var(--text-primary);
    }

    .no-map {
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        background: var(--bg-secondary);
        border-radius: var(--radius);
        color: var(--text-muted);
    }

    .no-map i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Fullscreen Overlay */
    .fullscreen-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.9);
        z-index: 9999;
        display: none;
    }

    .map-fullscreen {
        height: 100%;
        border-radius: 0;
        border: none;
    }

    /* Animations */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (min-width: 768px) {
        .info-grid {
            grid-template-columns: 400px 1fr;
        }
    }

    @media (max-width: 1024px) {
        .content-section {
            padding: 1.5rem 1.25rem;
        }

        .location-title {
            font-size: 1.625rem;
        }

        .map-container {
            height: 400px;
        }
    }

    @media (max-width: 768px) {
        .content-section {
            padding: 1.25rem 1rem;
        }

        .details-card {
            padding: 1.5rem;
        }

        .location-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .location-icon {
            width: 64px;
            height: 64px;
            font-size: 1.75rem;
        }

        .location-title {
            font-size: 1.5rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .map-container {
            height: 350px;
        }

        .map-btn {
            width: 32px;
            height: 32px;
        }

        .map-btn i {
            font-size: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        .content-section {
            padding: 1rem 0.875rem;
        }

        .details-card {
            padding: 1.25rem;
        }

        .location-icon {
            width: 56px;
            height: 56px;
            font-size: 1.5rem;
        }

        .location-title {
            font-size: 1.375rem;
        }

        .location-description {
            font-size: 0.9375rem;
        }

        .section-title {
            font-size: 1.125rem;
        }

        .contact-icon {
            width: 38px;
            height: 38px;
        }

        .contact-icon i {
            font-size: 0.9375rem;
        }

        .map-container {
            height: 300px;
        }

        .map-btn {
            width: 28px;
            height: 28px;
        }

        .no-map {
            height: 300px;
        }

        .no-map i {
            font-size: 2.5rem;
        }
    }

    /* Smooth Scrolling */
    html {
        scroll-behavior: smooth;
    }

    /* Leaflet Popup Customization */
    .leaflet-popup-content-wrapper {
        border-radius: var(--radius);
        box-shadow: var(--shadow-lg);
    }

    .leaflet-popup-content {
        font-family: inherit;
    }
</style>
@endsection

@section('content')
<div class="content-section">
    <a href="{{ route('admin.map.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Map</span>
    </a>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Location Overview -->
    <div class="details-card">
        <div class="location-header">
            <div class="location-icon">
                <i class="fas fa-user-md"></i>
            </div>
            <div class="location-info">
                <h1 class="location-title">{{ $map->name }}</h1>
                <div class="location-badges">
                    <span class="badge badge-success">Veterinarian</span>
                    <span class="badge badge-{{ $map->is_active ? 'active' : 'inactive' }}">
                        {{ $map->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                @if($map->description)
                    <p class="location-description">{{ $map->description }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="info-grid">
        <!-- Contact & Location Info -->
        <div class="details-card">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Contact & Location
                </h2>
            </div>
            
            <div class="contact-list">
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="contact-details">
                        <div class="contact-label">Address</div>
                        <div class="contact-value">{{ $map->address }}</div>
                    </div>
                </div>
                
                @if($map->phone)
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <div class="contact-label">Phone</div>
                            <div class="contact-value">
                                <a href="tel:{{ $map->phone }}">{{ $map->phone }}</a>
                            </div>
                        </div>
                    </div>
                @endif

                @if($map->email)
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <div class="contact-label">Email</div>
                            <div class="contact-value">
                                <a href="mailto:{{ $map->email }}">{{ $map->email }}</a>
                            </div>
                        </div>
                    </div>
                @endif

                @if($map->website)
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <div class="contact-details">
                            <div class="contact-label">Website</div>
                            <div class="contact-value">
                                <a href="{{ $map->website }}" target="_blank">{{ $map->website }}</a>
                            </div>
                        </div>
                    </div>
                @endif

                @if($map->operating_hours)
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-details">
                            <div class="contact-label">Operating Hours</div>
                            @php
                                $hours = is_string($map->operating_hours) ? json_decode($map->operating_hours, true) : $map->operating_hours;
                                $defaultHours = [
                                    'monday' => '8 AM - 5 PM',
                                    'tuesday' => '8 AM - 5 PM',
                                    'wednesday' => '8 AM - 5 PM',
                                    'thursday' => '8 AM - 5 PM',
                                    'friday' => '8 AM - 5 PM',
                                    'saturday' => '8 AM - 5 PM',
                                    'sunday' => '9 AM - 4 PM'
                                ];
                                
                                if (is_array($hours)) {
                                    foreach ($hours as $day => $time) {
                                        if (is_string($time) && 
                                            (stripos($time, 'purok') !== false || 
                                             stripos($time, 'poblacion') !== false || 
                                             stripos($time, 'san francisco') !== false || 
                                             stripos($time, 'agusan') !== false || 
                                             stripos($time, 'caraga') !== false || 
                                             preg_match('/\d{4}/', $time))) {
                                            unset($hours[$day]);
                                        }
                                    }
                                    
                                    $hourOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                    $orderedHours = [];
                                    
                                    foreach ($hourOrder as $day) {
                                        if (isset($hours[$day]) && !empty($hours[$day])) {
                                            $orderedHours[$day] = $hours[$day];
                                        } else {
                                            $orderedHours[$day] = $defaultHours[$day];
                                        }
                                    }
                                    
                                    $hours = $orderedHours;
                                } else {
                                    $hours = $defaultHours;
                                }
                            @endphp
                            <ul class="hours-list">
                                @foreach($hours as $day => $time)
                                    <li class="hours-item">
                                        <span class="hours-day">{{ ucfirst($day) }}</span>
                                        <span class="hours-time">{{ $time }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Map -->
        <div class="details-card">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-map"></i>
                    Location Map
                </h2>
            </div>
            
            @if($map->latitude && $map->longitude)
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
<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

@if($map->latitude && $map->longitude)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            if (typeof SharedMap !== 'undefined') {
                const shelterData = [{
                    id: {{ $map->id }},
                    name: "{{ $map->name }}",
                    address: "{{ $map->address }}",
                    city: "{{ $map->city }}",
                    province: "{{ $map->province }}",
                    phone: "{{ $map->phone }}",
                    email: "{{ $map->email }}",
                    latitude: "{{ $map->latitude }}",
                    longitude: "{{ $map->longitude }}",
                    type: "veterinarian"
                }];
                
                const sharedMap = new SharedMap('shelter-map', shelterData, {
                    fullscreenEnabled: true,
                    showViewDetails: false,
                    zoom: 16
                });
                
                window.shelterMap = sharedMap;
            } else {
                console.error('SharedMap is not available');
                initBasicMap();
            }
        }, 100);
    });
    
    function initBasicMap() {
        const lat = parseFloat({{ $map->latitude }});
        const lng = parseFloat({{ $map->longitude }});
        
        if (isNaN(lat) || isNaN(lng)) {
            console.error('Invalid coordinates');
            return;
        }
        
        const map = L.map('shelter-map', {
            center: [lat, lng],
            zoom: 16,
            zoomControl: false,
            attributionControl: true
        });
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 18
        }).addTo(map);
        
        const customIcon = L.divIcon({
            html: `<div style="background: #10b981; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"><i class="fas fa-user-md" style="font-size: 12px;"></i></div>`,
            iconSize: [36, 36],
            iconAnchor: [18, 18],
            popupAnchor: [0, -18],
            className: 'custom-marker'
        });
        
        const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);
        
        marker.bindPopup(`
            <div style="min-width: 200px;">
                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                    <div style="background: #10b981; color: white; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 1.1rem; color: #1f2937;">{{ $map->name }}</h4>
                        <span style="background: #e5e7eb; color: #374151; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Veterinarian</span>
                    </div>
                </div>
                <div style="margin-bottom: 8px; color: #4b5563;">
                    <i class="fas fa-map-marker-alt" style="color: #3b82f6; margin-right: 6px;"></i>
                    {{ $map->address }}<br>
                    {{ $map->city }}, {{ $map->province }}
                </div>
                @if($map->phone)
                <div style="margin-bottom: 8px; color: #4b5563;">
                    <i class="fas fa-phone" style="color: #3b82f6; margin-right: 6px;"></i>
                    {{ $map->phone }}
                </div>
                @endif
                @if($map->email)
                <div style="margin-bottom: 12px; color: #4b5563;">
                    <i class="fas fa-envelope" style="color: #3b82f6; margin-right: 6px;"></i>
                    {{ $map->email }}
                </div>
                @endif
            </div>
        `);
        
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
        
        window.shelterMap = map;
        initFullscreenFunctionality();
    }
    
    function initFullscreenFunctionality() {
        const fullscreenBtn = document.getElementById('fullscreen-btn');
        const exitFullscreenBtn = document.getElementById('exit-fullscreen-btn');
        const fullscreenOverlay = document.getElementById('fullscreen-overlay');
        
        if (fullscreenBtn) {
            fullscreenBtn.addEventListener('click', handleFullscreenToggle);
            fullscreenBtn.addEventListener('touchstart', handleFullscreenToggle);
        }
        
        if (exitFullscreenBtn) {
            exitFullscreenBtn.addEventListener('click', exitFullscreen);
            exitFullscreenBtn.addEventListener('touchstart', exitFullscreen);
        }
        
        if (fullscreenOverlay) {
            fullscreenOverlay.addEventListener('click', handleOverlayClick);
            fullscreenOverlay.addEventListener('touchstart', handleOverlayClick);
        }
        
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
        const lat = parseFloat({{ $map->latitude }});
        const lng = parseFloat({{ $map->longitude }});
        
        if (isNaN(lat) || isNaN(lng)) {
            console.error('Invalid coordinates');
            return;
        }
        
        window.fullscreenMap = L.map('fullscreen-map', {
            center: [lat, lng],
            zoom: 16,
            zoomControl: false,
            attributionControl: true
        });
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 18
        }).addTo(window.fullscreenMap);
        
        const customIcon = L.divIcon({
            html: `<div style="background: #10b981; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"><i class="fas fa-user-md" style="font-size: 12px;"></i></div>`,
            iconSize: [36, 36],
            iconAnchor: [18, 18],
            popupAnchor: [0, -18],
            className: 'custom-marker'
        });
        
        const marker = L.marker([lat, lng], { icon: customIcon }).addTo(window.fullscreenMap);
        
        marker.bindPopup(`
            <div style="min-width: 200px;">
                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                    <div style="background: #10b981; color: white; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 1.1rem; color: #1f2937;">{{ $map->name }}</h4>
                        <span style="background: #e5e7eb; color: #374151; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Veterinarian</span>
                    </div>
                </div>
                <div style="margin-bottom: 8px; color: #4b5563;">
                    <i class="fas fa-map-marker-alt" style="color: #3b82f6; margin-right: 6px;"></i>
                    {{ $map->address }}<br>
                    {{ $map->city }}, {{ $map->province }}
                </div>
                @if($map->phone)
                <div style="margin-bottom: 8px; color: #4b5563;">
                    <i class="fas fa-phone" style="color: #3b82f6; margin-right: 6px;"></i>
                    {{ $map->phone }}
                </div>
                @endif
                @if($map->email)
                <div style="margin-bottom: 12px; color: #4b5563;">
                    <i class="fas fa-envelope" style="color: #3b82f6; margin-right: 6px;"></i>
                    {{ $map->email }}
                </div>
                @endif
            </div>
        `);
        
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
@endif
@endsection