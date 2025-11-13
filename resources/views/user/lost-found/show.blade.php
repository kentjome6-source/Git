@extends('layouts.app')

@section('title', 'View ' . $lostFound->pet_name)

@section('styles')
<style>
        
        .page-header {
            text-align: center; margin-bottom: 40px;
        }
        .page-title { font-size: 2.5rem; color: #5b4b9b; margin-bottom: 10px; }
        .page-subtitle { font-size: 1.1rem; color: #666; }

        .back-link {
            display: inline-flex; align-items: center; gap: 8px;
            color: #5b4b9b; text-decoration: none; font-weight: 500;
            margin-bottom: 20px; transition: 0.2s;
        }
        .back-link:hover { color: #4a3d7a; }

        .listing-detail {
            background: #fff; border-radius: 15px; overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08); margin-bottom: 30px;
        }

        /* Full image adjustments */
        .listing-header {
        position: relative;
        max-height: 600px; /* taller for clearer image */
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #f8f9fa;
        }
        .listing-header img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        display: block;
        }
        .listing-header .no-image {
            display: flex; align-items: center; justify-content: center;
            height: 400px; background: #f8f9fa; color: #999; font-size: 4rem;
        }
        .type-badge {
            position: absolute; top: 20px; right: 20px;
            padding: 8px 16px; border-radius: 25px; font-size: 0.9rem;
            font-weight: 600; text-transform: uppercase;
        }
        .type-badge.lost { background: #e74c3c; color: #fff; }
        .type-badge.found { background: #27ae60; color: #fff; }

        .listing-content {
            padding: 40px;
        }

        .pet-name {
            font-size: 2.5rem; font-weight: 700; color: #333;
            margin-bottom: 15px;
        }

        .pet-details {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px; margin-bottom: 30px;
        }
        .detail-item {
            display: flex; align-items: center; gap: 10px;
            padding: 15px; background: #f8f9fa; border-radius: 10px;
        }
        .detail-icon {
            width: 40px; height: 40px; background: #5b4b9b; color: #fff;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
        }
        .detail-content h4 {
            font-size: 0.9rem; color: #666; margin-bottom: 5px; text-transform: uppercase;
        }
        .detail-content p {
            font-size: 1.1rem; font-weight: 600; color: #333;
        }

        .description-section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 1.3rem; font-weight: 600; color: #333;
            margin-bottom: 15px; display: flex; align-items: center; gap: 10px;
        }
        .description-text {
            font-size: 1.1rem; line-height: 1.6; color: #555;
            background: #f8f9fa; padding: 20px; border-radius: 10px;
        }

        .contact-section {
            background: #f8f9fa; padding: 25px; border-radius: 10px;
            margin-bottom: 30px;
        }
        .contact-info {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .contact-item {
            display: flex; align-items: center; gap: 12px;
        }
        .contact-icon {
            width: 35px; height: 35px; background: #5b4b9b; color: #fff;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
        }

        .action-buttons {
            display: flex; gap: 15px; justify-content: center; margin-top: 30px;
        }

        .btn {
            padding: 12px 24px; border: none; border-radius: 8px;
            text-decoration: none; font-weight: 600; cursor: pointer;
            transition: 0.2s; display: inline-flex; align-items: center; gap: 8px;
            font-size: 1rem;
        }
        .btn-primary { background: #5b4b9b; color: #fff; }
        .btn-primary:hover { background: #4a3d7a; }
        .btn-secondary { background: #6c757d; color: #fff; }
        .btn-secondary:hover { background: #5a6268; }
        .btn-success { background: #27ae60; color: #fff; }
        .btn-success:hover { background: #229954; }

        /* Map Styles */
        .map-section {
            margin-bottom: 30px;
        }
        .map-container {
            height: 400px; border-radius: 10px; overflow: hidden;
            border: 2px solid #e5e7eb;
        }

        .alert {
            padding: 15px 20px; border-radius: 8px; margin-bottom: 20px;
            font-weight: 500;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }


        /* Responsive styles for mobile */
        @media (max-width: 768px) {
            .listing-content {
                padding: 25px;
            }
            
            .pet-name {
                font-size: 2rem;
            }
            
            .pet-details {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .detail-item {
                padding: 12px;
            }
            
            .detail-content h4 {
                font-size: 0.85rem;
            }
            
            .detail-content p {
                font-size: 1rem;
            }
            
            .description-text {
                padding: 15px;
                font-size: 1rem;
            }
            
            .contact-section {
                padding: 20px;
            }
            
            .contact-info {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .map-container {
                height: 300px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
        
        @media (max-width: 576px) {
            .listing-content {
                padding: 20px 15px;
            }
            
            .pet-name {
                font-size: 1.75rem;
            }
            
            .detail-item {
                padding: 10px;
            }
            
            .detail-icon {
                width: 35px; height: 35px;
            }
            
            .description-text {
                padding: 12px;
                font-size: 0.95rem;
            }
            
            .contact-section {
                padding: 15px;
            }
            
            .map-container {
                height: 250px;
            }
            
            /* Ensure content fits within mobile screen without horizontal scrolling */
            .container, .container-fluid {
                padding-left: 10px;
                padding-right: 10px;
            }
            
            .listing-detail {
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
    <h1 class="page-title">View Listing</h1>
    <p class="page-subtitle">View details of this lost/found pet listing</p>
</div>

@if(session('success'))
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div class="listing-detail">
    <div class="listing-header">
        @if($lostFound->image_path)
            <img src="{{ asset('storage/' . $lostFound->image_path) }}" alt="{{ $lostFound->pet_name }}">
        @else
            <div class="no-image">
                <i class="fas fa-paw"></i>
            </div>
        @endif
        <div class="type-badge {{ $lostFound->type }}">{{ $lostFound->type }}</div>
    </div>

    <div class="listing-content">
        <h1 class="pet-name">{{ $lostFound->pet_name }}</h1>

        <div class="pet-details">
            <div class="detail-item">
                <div class="detail-icon"><i class="fas fa-paw"></i></div>
                <div class="detail-content">
                    <h4>Pet Type</h4>
                    <p>{{ ucfirst($lostFound->pet_type) }}</p>
                </div>
            </div>

            @if($lostFound->breed)
            <div class="detail-item">
                <div class="detail-icon"><i class="fas fa-tag"></i></div>
                <div class="detail-content">
                    <h4>Breed</h4>
                    <p>{{ $lostFound->breed }}</p>
                </div>
            </div>
            @endif

            @if($lostFound->color)
            <div class="detail-item">
                <div class="detail-icon"><i class="fas fa-palette"></i></div>
                <div class="detail-content">
                    <h4>Color</h4>
                    <p>{{ $lostFound->color }}</p>
                </div>
            </div>
            @endif

            @if($lostFound->size)
            <div class="detail-item">
                <div class="detail-icon"><i class="fas fa-ruler"></i></div>
                <div class="detail-content">
                    <h4>Size</h4>
                    <p>{{ ucfirst($lostFound->size) }}</p>
                </div>
            </div>
            @endif

            @if($lostFound->age)
            <div class="detail-item">
                <div class="detail-icon"><i class="fas fa-calendar"></i></div>
                <div class="detail-content">
                    <h4>Age</h4>
                    <p>{{ $lostFound->age }} years old</p>
                </div>
            </div>
            @endif

            <div class="detail-item">
                <div class="detail-icon"><i class="fas fa-venus-mars"></i></div>
                <div class="detail-content">
                    <h4>Gender</h4>
                    <p>{{ ucfirst($lostFound->gender) }}</p>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div class="detail-content">
                    <h4>Location</h4>
                    <p>{{ $lostFound->location }}</p>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-icon"><i class="fas fa-calendar-day"></i></div>
                <div class="detail-content">
                    <h4>Date {{ $lostFound->type == 'lost' ? 'Lost' : 'Found' }}</h4>
                    <p>{{ $lostFound->date_lost_found->format('M d, Y') }}</p>
                </div>
            </div>
            
            <div class="detail-item">
                <div class="detail-icon"><i class="fas fa-user"></i></div>
                <div class="detail-content">
                    <h4>Submitted by</h4>
                    <p>{{ $lostFound->user->name }}</p>
                </div>
            </div>
            
            <div class="detail-item">
                <div class="detail-icon"><i class="fas fa-clock"></i></div>
                <div class="detail-content">
                    <h4>Submitted on</h4>
                    <p>{{ $lostFound->created_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        @if($lostFound->latitude && $lostFound->longitude)
        <div class="map-section">
            <h2 class="section-title"><i class="fas fa-map-marked-alt"></i> Location on Map</h2>
            <div id="map" class="map-container"></div>
        </div>
        @endif

        <div class="description-section">
            <h2 class="section-title"><i class="fas fa-align-left"></i> Description</h2>
            <div class="description-text">{{ $lostFound->description }}</div>
        </div>

        <div class="contact-section">
            <h2 class="section-title"><i class="fas fa-phone"></i> Contact Information</h2>
            <div class="contact-info">
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-user"></i></div>
                    <div><strong>{{ $lostFound->contact_name }}</strong></div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-phone"></i></div>
                    <div><strong>{{ $lostFound->contact_phone }}</strong></div>
                </div>
                @if($lostFound->contact_email)
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                    <div><strong>{{ $lostFound->contact_email }}</strong></div>
                </div>
                @endif
            </div>
        </div>

        @if(Auth::id() === $lostFound->user_id)
        <div class="action-buttons">
            <a href="{{ route('lost-found.edit', $lostFound) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Listing
            </a>
            
            <form action="{{ route('lost-found.resolve', $lostFound) }}" method="POST" style="display: inline;">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to mark this listing as resolved?')">
                    <i class="fas fa-check-circle"></i> Mark as Resolved
                </button>
            </form>
        </div>
        @endif
    </div>
</div>

<!-- Leaflet CSS and JS -->
@if($lostFound->latitude && $lostFound->longitude)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Create map centered on the pet's location
        const map = L.map('map').setView([{{ $lostFound->latitude }}, {{ $lostFound->longitude }}], 15);
        
        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Create custom icon with pet image if available
        let customIcon;
        if ("{{ $lostFound->image_path }}") {
            // Use the pet image as the marker without any icon overlay
            customIcon = L.divIcon({
                html: `<div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <img src="/storage/{{ $lostFound->image_path }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>`,
                iconSize: [56, 56],
                iconAnchor: [28, 28],
                popupAnchor: [0, -28],
                className: 'custom-marker'
            });
        } else {
            // Use the default icon if no image is available
            const color = "{{ $lostFound->type }}" === 'lost' ? '#e74c3c' : '#27ae60';
            const iconClass = "{{ $lostFound->type }}" === 'lost' ? 'fas fa-heart-broken' : 'fas fa-heart';
            customIcon = L.divIcon({
                html: `<div style="background: ${color}; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <i class="${iconClass}" style="font-size: 16px;"></i>
                </div>`,
                iconSize: [46, 46],
                iconAnchor: [23, 23],
                popupAnchor: [0, -28],
                className: 'custom-marker'
            });
        }
        
        // Add marker for the pet's location with custom icon
        const marker = L.marker([{{ $lostFound->latitude }}, {{ $lostFound->longitude }}], { icon: customIcon }).addTo(map);
        
        // Create popup content
        const popupContent = `
            <div style="min-width: 200px;">
                <h4 style="margin: 0 0 10px 0; color: #1f2937;">{{ $lostFound->pet_name }}</h4>
                <p style="margin: 5px 0; color: #4b5563;">
                    <i class="fas fa-${{ $lostFound->type == 'lost' ? 'exclamation-triangle' : 'heart' }}" style="color: ${{ $lostFound->type == 'lost' ? 'e74c3c' : '27ae60' }}; margin-right: 6px;"></i>
                    {{ ucfirst($lostFound->type) }} Pet
                </p>
                <p style="margin: 5px 0; color: #4b5563;">
                    <i class="fas fa-map-marker-alt" style="color: #667eea; margin-right: 6px;"></i>
                    {{ $lostFound->location }}
                </p>
            </div>
        `;
        
        marker.bindPopup(popupContent);
    });
</script>
@endif
@endsection