@extends('layouts.app')

@section('title', 'Map')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    :root {
        --slate: #0f172a;
        --slate-light: #1e293b;
        --blue: #3b82f6;
        --purple: #8b5cf6;
        --green: #10b981;
        --orange: #f59e0b;
        --gray: #64748b;
        --gray-light: #f1f5f9;
        --gray-lighter: #f8fafc;
    }

    .map-page {
        font-family: 'Sora', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--gray-lighter);
        min-height: 100vh;
    }

    /* Page Header */
    .page-header {
        padding: 40px 20px;
        text-align: center;
        animation: fadeInDown 0.6s ease-out;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .label {
        display: inline-block;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--blue);
        margin-bottom: 12px;
        font-weight: 600;
    }

    .page-title {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 700;
        color: var(--slate);
        margin-bottom: 12px;
        letter-spacing: -0.02em;
    }

    .page-subtitle {
        font-size: 1.1rem;
        color: var(--gray);
    }

    /* Map Section */
    .map-section {
        padding: 0 20px 40px;
        animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .map-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
    }

    .map-container {
        position: relative;
        height: 500px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    #shelterMap {
        height: 100%;
        width: 100%;
    }

    .map-controls {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 1000;
        display: flex;
        gap: 8px;
    }

    .map-btn {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.2s;
    }

    .map-btn:hover {
        background: var(--gray-light);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .map-btn svg {
        width: 16px;
        height: 16px;
        stroke: var(--slate);
    }

    /* Shelters Section */
    .shelters-section {
        padding: 40px 20px;
    }

    .shelters-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .section-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .section-title {
        font-size: clamp(1.75rem, 3vw, 2.25rem);
        font-weight: 700;
        color: var(--slate);
        letter-spacing: -0.02em;
    }

    /* Shelters Grid */
    .shelters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
    }

    .shelter-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .shelter-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        border-color: var(--green);
    }

    .shelter-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 16px;
    }

    .shelter-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--green) 0%, #059669 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .shelter-icon svg {
        width: 28px;
        height: 28px;
        stroke: white;
    }

    .shelter-info h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--slate);
        margin-bottom: 4px;
        letter-spacing: -0.01em;
    }

    .shelter-info p {
        font-size: 0.9rem;
        color: var(--gray);
        margin: 0;
    }

    .vet-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: rgba(16, 185, 129, 0.1);
        color: var(--green);
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 16px;
    }

    .vet-badge svg {
        width: 14px;
        height: 14px;
        stroke: currentColor;
    }

    .shelter-description {
        color: var(--gray);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .shelter-details {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 20px;
    }

    .detail-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        color: var(--gray);
        font-size: 0.9rem;
    }

    .detail-item svg {
        width: 16px;
        height: 16px;
        stroke: var(--blue);
        flex-shrink: 0;
        margin-top: 2px;
    }

    .detail-item span {
        word-break: break-word;
    }

    .map-link {
        background: var(--gray-light);
        padding: 16px;
        border-radius: 10px;
        text-align: center;
        color: var(--gray);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .map-link svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-icon {
        margin-bottom: 24px;
        color: var(--gray);
        opacity: 0.4;
    }

    .empty-icon svg {
        width: 80px;
        height: 80px;
        stroke: currentColor;
    }

    .empty-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: var(--slate);
        margin-bottom: 12px;
    }

    .empty-text {
        font-size: 1.05rem;
        color: var(--gray);
    }

    /* Success Notification */
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--green);
        color: white;
        padding: 16px 20px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideInRight 0.3s ease-out;
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .notification svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
    }

    /* Fullscreen Overlay */
    .fullscreen-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.95);
        z-index: 9999;
        display: none;
    }

    #fullscreen-map {
        height: 100%;
        width: 100%;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .shelters-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 30px 15px;
        }

        .page-title {
            font-size: 1.75rem;
        }

        .page-subtitle {
            font-size: 1rem;
        }

        .map-section {
            padding: 0 15px 30px;
        }

        .map-wrapper {
            padding: 20px;
        }

        .map-container {
            height: 400px;
        }

        .shelters-section {
            padding: 30px 15px;
        }

        .shelters-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }

    @media (max-width: 576px) {
        .page-header {
            padding: 25px 10px;
        }

        .map-section {
            padding: 0 10px 25px;
        }

        .map-wrapper {
            padding: 16px;
        }

        .map-container {
            height: 320px;
        }

        .map-controls {
            top: 8px;
            right: 8px;
            gap: 6px;
        }

        .map-btn {
            width: 32px;
            height: 32px;
        }

        .map-btn svg {
            width: 14px;
            height: 14px;
        }

        .shelters-section {
            padding: 25px 10px;
        }

        .shelter-card {
            padding: 20px;
        }

        .shelter-header {
            gap: 12px;
        }

        .shelter-icon {
            width: 48px;
            height: 48px;
        }

        .shelter-icon svg {
            width: 24px;
            height: 24px;
        }

        .notification {
            top: 10px;
            right: 10px;
            left: 10px;
            padding: 14px 16px;
        }
    }

    @media (max-width: 400px) {
        .map-container {
            height: 280px;
        }

        .shelter-card {
            padding: 16px;
        }
    }
</style>
@endsection

@section('content')
<div class="map-page">
    <!-- Page Header -->
    <div class="page-header">
        <span class="label">Location Services</span>
        <h1 class="page-title">Find Vet Shops & Services</h1>
        <p class="page-subtitle">Discover veterinarians and pet services near you</p>
    </div>

    <!-- Map Section -->
    <div class="map-section">
        <div class="map-wrapper">
            <div class="map-container">
                <div id="shelterMap"></div>
                <div class="map-controls">
                    <button id="fullscreen-btn" class="map-btn" title="Fullscreen">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                            <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Shelters Section -->
    <div class="shelters-section">
        <div class="shelters-container">
            <div class="section-header">
                <h2 class="section-title">Available Veterinarians</h2>
            </div>
            
            @if($shelters->count() > 0)
                <div class="shelters-grid">
                    @foreach($shelters as $shelter)
                        <div class="shelter-card">
                            <div class="shelter-header">
                                <div class="shelter-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                                    </svg>
                                </div>
                                <div class="shelter-info">
                                    <h3>{{ $shelter->name }}</h3>
                                    <p>{{ $shelter->city }}, {{ $shelter->province }}</p>
                                </div>
                            </div>

                            <span class="vet-badge">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                                </svg>
                                Veterinarian
                            </span>

                            @if($shelter->description)
                                <p class="shelter-description">{{ $shelter->description }}</p>
                            @endif

                            <div class="shelter-details">
                                <div class="detail-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <span>{{ $shelter->address }}</span>
                                </div>
                                <div class="detail-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                    </svg>
                                    <span>{{ $shelter->phone }}</span>
                                </div>
                                @if($shelter->email)
                                    <div class="detail-item">
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                            <polyline points="22,6 12,13 2,6"/>
                                        </svg>
                                        <span>{{ $shelter->email }}</span>
                                    </div>
                                @endif
                                @if($shelter->operating_hours && isset($shelter->operating_hours['weekdays']))
                                    <div class="detail-item">
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"/>
                                            <polyline points="12 6 12 12 16 14"/>
                                        </svg>
                                        <span>{{ $shelter->operating_hours['weekdays'] ?? 'Contact for hours' }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="map-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="16" x2="12" y2="12"/>
                                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                                </svg>
                                <span>View location on map</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                            <line x1="9" y1="9" x2="9.01" y2="9"/>
                            <line x1="15" y1="9" x2="15.01" y2="9"/>
                        </svg>
                    </div>
                    <h3 class="empty-title">No veterinarians available</h3>
                    <p class="empty-text">Check back later for service locations</p>
                </div>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
    <div class="notification">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    <script>
        setTimeout(function() {
            const notification = document.querySelector('.notification');
            if (notification) {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100px)';
                setTimeout(() => notification.remove(), 300);
            }
        }, 5000);
    </script>
@endif

<!-- Fullscreen Overlay -->
<div id="fullscreen-overlay" class="fullscreen-overlay">
    <div id="fullscreen-map"></div>
    <div class="map-controls">
        <button id="exit-fullscreen-btn" class="map-btn" title="Exit Fullscreen">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                <path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3"/>
            </svg>
        </button>
    </div>
</div>

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
// Map data
const mapShelters = @json($shelters);
const mapLostFoundItems = @json($lostFoundItems);
const mapData = [...mapShelters, ...mapLostFoundItems];

// Initialize map
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        if (typeof SharedMap !== 'undefined') {
            const sharedMap = new SharedMap('shelterMap', mapData, {
                fullscreenEnabled: true,
                showViewDetails: true,
                viewDetailsRoute: '/view-map/'
            });
            
            const urlParams = new URLSearchParams(window.location.search);
            const focusShelterId = urlParams.get('shelter');
            
            if (focusShelterId) {
                const focusShelter = mapData.find(item => item.id == focusShelterId && item.latitude && item.longitude);
                
                if (focusShelter) {
                    setTimeout(function() {
                        sharedMap.map.setView([parseFloat(focusShelter.latitude), parseFloat(focusShelter.longitude)], 15);
                        
                        sharedMap.markersLayer.eachLayer(function(layer) {
                            if (layer._latlng.lat === parseFloat(focusShelter.latitude) && 
                                layer._latlng.lng === parseFloat(focusShelter.longitude)) {
                                layer.openPopup();
                            }
                        });
                    }, 500);
                }
            }
            
            window.shelterMap = sharedMap;
        } else {
            initBasicMap();
        }
    }, 100);
});

function initBasicMap() {
    const map = L.map('shelterMap', {
        zoomControl: false
    }).setView([8.504588, 125.975800], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    mapShelters.forEach(shelter => {
        if (shelter.latitude && shelter.longitude) {
            const lat = parseFloat(shelter.latitude);
            const lng = parseFloat(shelter.longitude);
            
            const customIcon = L.divIcon({
                html: `<div style="background: #10b981; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"><i class="fas fa-user-md" style="font-size: 12px;"></i></div>`,
                iconSize: [36, 36],
                iconAnchor: [18, 18],
                popupAnchor: [0, -18],
                className: 'custom-marker'
            });
            
            const marker = L.marker([lat, lng], { icon: customIcon });
            
            const popupContent = `
                <div style="min-width: 250px;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <div style="background: #10b981; color: white; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0; font-size: 1.1rem; color: #1f2937;">${shelter.name}</h4>
                            <span style="background: #e5e7eb; color: #374151; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Veterinarian</span>
                        </div>
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563; word-break: break-word;">
                        <i class="fas fa-map-marker-alt" style="color: #3b82f6; margin-right: 6px;"></i>
                        ${shelter.address}<br>
                        ${shelter.city}, ${shelter.province}
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-phone" style="color: #3b82f6; margin-right: 6px;"></i>
                        ${shelter.phone || 'Not provided'}
                    </div>
                    ${shelter.email ? `
                        <div style="margin-bottom: 12px; color: #4b5563; word-break: break-word;">
                            <i class="fas fa-envelope" style="color: #3b82f6; margin-right: 6px;"></i>
                            ${shelter.email}
                        </div>
                    ` : ''}
                    <div style="display: flex; justify-content: flex-end; margin-top: 12px;">
                        <a href="/view-map/${shelter.id}" style="background: #8b5cf6; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            marker.addTo(map);
        }
    });
    
    mapLostFoundItems.forEach(item => {
        if (item.latitude && item.longitude) {
            const lat = parseFloat(item.latitude);
            const lng = parseFloat(item.longitude);
            
            let iconHtml = '';
            if (item.image_path) {
                iconHtml = `<div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <img src="/storage/${item.image_path}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>`;
            } else {
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
            
            const marker = L.marker([lat, lng], { icon: customIcon });
            
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
                        <i class="fas fa-tag" style="color: #3b82f6; margin-right: 6px;"></i>
                        ${item.pet_type}
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-calendar" style="color: #3b82f6; margin-right: 6px;"></i>
                        Reported: ${new Date(item.created_at).toLocaleDateString()}
                    </div>
                    <div style="margin-bottom: 12px; color: #4b5563;">
                        <i class="fas fa-user" style="color: #3b82f6; margin-right: 6px;"></i>
                        ${item.user ? item.user.name : 'Anonymous'}
                    </div>
                    <div style="display: flex; justify-content: flex-end; margin-top: 12px;">
                        <a href="/lost-found/${item.id}" style="background: #8b5cf6; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            marker.addTo(map);
        }
    });
    
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
    window.fullscreenMap = L.map('fullscreen-map', {
        zoomControl: false
    }).setView([8.504588, 125.975800], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(window.fullscreenMap);
    
    // Add all markers to fullscreen map (same as main map)
    mapShelters.forEach(shelter => {
        if (shelter.latitude && shelter.longitude) {
            const lat = parseFloat(shelter.latitude);
            const lng = parseFloat(shelter.longitude);
            
            const customIcon = L.divIcon({
                html: `<div style="background: #10b981; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"><i class="fas fa-user-md" style="font-size: 12px;"></i></div>`,
                iconSize: [36, 36],
                iconAnchor: [18, 18],
                popupAnchor: [0, -18],
                className: 'custom-marker'
            });
            
            const marker = L.marker([lat, lng], { icon: customIcon });
            
            const popupContent = `
                <div style="min-width: 250px;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <div style="background: #10b981; color: white; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0; font-size: 1.1rem; color: #1f2937;">${shelter.name}</h4>
                            <span style="background: #e5e7eb; color: #374151; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Veterinarian</span>
                        </div>
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563; word-break: break-word;">
                        <i class="fas fa-map-marker-alt" style="color: #3b82f6; margin-right: 6px;"></i>
                        ${shelter.address}<br>
                        ${shelter.city}, ${shelter.province}
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-phone" style="color: #3b82f6; margin-right: 6px;"></i>
                        ${shelter.phone || 'Not provided'}
                    </div>
                    ${shelter.email ? `
                        <div style="margin-bottom: 12px; color: #4b5563; word-break: break-word;">
                            <i class="fas fa-envelope" style="color: #3b82f6; margin-right: 6px;"></i>
                            ${shelter.email}
                        </div>
                    ` : ''}
                    <div style="display: flex; justify-content: flex-end; margin-top: 12px;">
                        <a href="/view-map/${shelter.id}" style="background: #8b5cf6; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            marker.addTo(window.fullscreenMap);
        }
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
@endsection