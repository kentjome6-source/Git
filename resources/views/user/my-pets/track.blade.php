@extends('layouts.app')

@section('title', 'Track ' . $pet->name)

@section('content')
<div class="track-pet-page">
    <div class="container-fluid px-4 py-5">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <a href="{{ route('my.pets') }}" class="btn-back">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to My Pets
            </a>
            <h1 class="page-title">Tracking {{ $pet->name }}</h1>
            @if($pet->last_known_location)
                <p class="location-text">Last known location: {{ $pet->last_known_location }}</p>
            @endif
        </div>

        <!-- Map Container -->
        <div class="map-container" id="map"></div>

        <!-- Pet Info Card -->
        <div class="pet-info-card">
            <div class="pet-info-header">
                @if($pet->image_path)
                    <img src="{{ asset('storage/' . $pet->image_path) }}" class="pet-avatar" alt="{{ $pet->name }}">
                @else
                    <div class="pet-avatar-placeholder">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                            <line x1="9" y1="9" x2="9.01" y2="9"></line>
                            <line x1="15" y1="9" x2="15.01" y2="9"></line>
                        </svg>
                    </div>
                @endif
                <div class="pet-info-details">
                    <h3>{{ $pet->name }}</h3>
                    @if($pet->breed)
                        <p>{{ $pet->breed }}</p>
                    @endif
                </div>
            </div>
            <div class="location-status">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                Location Active
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    :root {
        --slate: #0f172a;
        --slate-light: #1e293b;
        --blue: #3b82f6;
        --purple: #8b5cf6;
        --green: #10b981;
        --gray: #64748b;
        --gray-light: #f1f5f9;
    }

    .track-pet-page {
        font-family: 'Sora', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--gray-light);
        min-height: 100vh;
    }

    .page-header {
        margin-bottom: 24px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        background: white;
        color: var(--gray);
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.2s;
        margin-bottom: 16px;
    }

    .btn-back:hover {
        background: var(--gray-light);
        color: var(--slate);
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--slate);
        margin-bottom: 8px;
    }

    .location-text {
        font-size: 1rem;
        color: var(--gray);
    }

    .map-container {
        width: 100%;
        height: 600px;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;
    }

    .pet-info-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .pet-info-header {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .pet-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--purple);
    }

    .pet-avatar-placeholder {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--gray-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gray);
        border: 3px solid var(--purple);
    }

    .pet-info-details h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--slate);
        margin-bottom: 4px;
    }

    .pet-info-details p {
        font-size: 0.9rem;
        color: var(--gray);
        margin: 0;
    }

    .location-status {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: rgba(16, 185, 129, 0.1);
        color: var(--green);
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .map-container {
            height: 400px;
        }

        .pet-info-card {
            flex-direction: column;
            align-items: flex-start;
        }

        .location-status {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map
        const lat = {{ $pet->latitude ?? 0 }};
        const lng = {{ $pet->longitude ?? 0 }};
        
        const map = L.map('map').setView([lat, lng], 15);
        
        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Custom pet marker icon
        const petIcon = L.divIcon({
            className: 'custom-pet-marker',
            html: `<div style="
                background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 20px;
                box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
                border: 3px solid white;
            ">
                🐾
            </div>`,
            iconSize: [40, 40],
            iconAnchor: [20, 20]
        });
        
        // Add marker
        const marker = L.marker([lat, lng], { icon: petIcon }).addTo(map);
        
        // Add popup
        marker.bindPopup(`
            <div style="text-align: center; padding: 8px;">
                <strong style="font-size: 1.1rem; color: #0f172a;">{{ $pet->name }}</strong>
                @if($pet->last_known_location)
                    <p style="margin: 6px 0 0 0; color: #64748b; font-size: 0.9rem;">{{ $pet->last_known_location }}</p>
                @endif
            </div>
        `).openPopup();
        
        // Add circle to show area
        L.circle([lat, lng], {
            color: '#8b5cf6',
            fillColor: '#8b5cf6',
            fillOpacity: 0.1,
            radius: 200
        }).addTo(map);
    });
</script>
@endsection
