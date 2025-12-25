@extends('layouts.admin')

@section('title', 'View ' . $lostFound->pet_name)

@section('styles')
<style>
    :root {
        --primary: #0f172a;
        --primary-light: #1e293b;
        --accent: #3b82f6;
        --accent-light: #60a5fa;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
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

    .container-fluid {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1.5rem;
    }

    /* Back Link */
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

    /* Page Header */
    .page-header {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        animation: slideDown 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        letter-spacing: -0.025em;
    }

    .page-subtitle {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0;
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

    /* Listing Detail Container */
    .listing-detail {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        overflow: hidden;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-md);
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Image Header */
    .listing-header {
        position: relative;
        max-height: 500px;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        background: var(--bg-secondary);
    }

    .listing-header img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .listing-header .no-image {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 400px;
        color: var(--text-muted);
        font-size: 4rem;
    }

    .type-badge {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        padding: 0.625rem 1.25rem;
        border-radius: 50px;
        font-size: 0.8125rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        backdrop-filter: blur(10px);
        box-shadow: var(--shadow-lg);
        animation: scaleIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.3s backwards;
    }

    .type-badge.lost {
        background: rgba(239, 68, 68, 0.95);
        color: white;
    }

    .type-badge.found {
        background: rgba(16, 185, 129, 0.95);
        color: white;
    }

    /* Content Area */
    .listing-content {
        padding: 2.5rem;
    }

    .pet-name {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 2rem;
        letter-spacing: -0.025em;
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.2s backwards;
    }

    /* Details Grid */
    .pet-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1rem;
        margin-bottom: 2.5rem;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem;
        background: var(--bg-secondary);
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        transition: var(--transition);
        animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) backwards;
    }

    .detail-item:nth-child(1) { animation-delay: 0.1s; }
    .detail-item:nth-child(2) { animation-delay: 0.15s; }
    .detail-item:nth-child(3) { animation-delay: 0.2s; }
    .detail-item:nth-child(4) { animation-delay: 0.25s; }
    .detail-item:nth-child(5) { animation-delay: 0.3s; }
    .detail-item:nth-child(6) { animation-delay: 0.35s; }
    .detail-item:nth-child(7) { animation-delay: 0.4s; }
    .detail-item:nth-child(8) { animation-delay: 0.45s; }
    .detail-item:nth-child(9) { animation-delay: 0.5s; }
    .detail-item:nth-child(10) { animation-delay: 0.55s; }
    .detail-item:nth-child(11) { animation-delay: 0.6s; }

    .detail-item:hover {
        background: var(--bg-primary);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .detail-icon {
        width: 42px;
        height: 42px;
        background: var(--primary);
        color: white;
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: var(--transition);
    }

    .detail-item:hover .detail-icon {
        background: var(--accent);
        transform: scale(1.1);
    }

    .detail-icon i {
        font-size: 1rem;
    }

    .detail-content {
        flex: 1;
        min-width: 0;
    }

    .detail-content h4 {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.375rem;
    }

    .detail-content p {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Section */
    .section {
        margin-bottom: 2.5rem;
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.4s backwards;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        letter-spacing: -0.025em;
    }

    .section-title i {
        color: var(--accent);
        font-size: 1.125rem;
    }

    /* Description */
    .description-text {
        font-size: 1rem;
        line-height: 1.7;
        color: var(--text-secondary);
        background: var(--bg-secondary);
        padding: 1.5rem;
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
    }

    /* Contact Section */
    .contact-section {
        background: var(--bg-secondary);
        padding: 1.75rem;
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
    }

    .contact-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.25rem;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .contact-icon {
        width: 42px;
        height: 42px;
        background: var(--primary);
        color: white;
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .contact-icon i {
        font-size: 1rem;
    }

    .contact-item strong {
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.9375rem;
    }

    /* Map */
    .map-container {
        height: 450px;
        border-radius: var(--radius);
        overflow: hidden;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
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

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .container-fluid {
            padding: 1.5rem 1.25rem;
        }

        .listing-content {
            padding: 2rem;
        }
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding: 1.25rem 1rem;
        }

        .page-header {
            padding: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .page-subtitle {
            font-size: 0.9375rem;
        }

        .listing-header {
            max-height: 400px;
        }

        .listing-header .no-image {
            height: 300px;
            font-size: 3rem;
        }

        .type-badge {
            top: 1rem;
            right: 1rem;
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
        }

        .listing-content {
            padding: 1.75rem 1.5rem;
        }

        .pet-name {
            font-size: 2rem;
            text-align: center;
        }

        .pet-details {
            grid-template-columns: 1fr;
            gap: 0.875rem;
        }

        .detail-item {
            padding: 1rem;
        }

        .detail-icon {
            width: 38px;
            height: 38px;
        }

        .detail-icon i {
            font-size: 0.9375rem;
        }

        .section-title {
            font-size: 1.125rem;
        }

        .description-text {
            padding: 1.25rem;
            font-size: 0.9375rem;
        }

        .contact-section {
            padding: 1.5rem;
        }

        .contact-info {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .map-container {
            height: 350px;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding: 1rem 0.875rem;
        }

        .page-header {
            padding: 1.25rem;
        }

        .page-title {
            font-size: 1.375rem;
        }

        .listing-header {
            max-height: 350px;
        }

        .listing-header .no-image {
            height: 250px;
            font-size: 2.5rem;
        }

        .type-badge {
            top: 0.875rem;
            right: 0.875rem;
            padding: 0.375rem 0.875rem;
            font-size: 0.6875rem;
        }

        .listing-content {
            padding: 1.5rem 1.25rem;
        }

        .pet-name {
            font-size: 1.75rem;
        }

        .detail-item {
            padding: 0.875rem;
        }

        .detail-icon {
            width: 36px;
            height: 36px;
        }

        .detail-icon i {
            font-size: 0.875rem;
        }

        .detail-content h4 {
            font-size: 0.6875rem;
        }

        .detail-content p {
            font-size: 0.9375rem;
        }

        .section-title {
            font-size: 1.0625rem;
        }

        .description-text {
            padding: 1rem;
            font-size: 0.875rem;
        }

        .contact-section {
            padding: 1.25rem;
        }

        .contact-icon {
            width: 38px;
            height: 38px;
        }

        .contact-icon i {
            font-size: 0.9375rem;
        }

        .contact-item strong {
            font-size: 0.875rem;
        }

        .map-container {
            height: 300px;
        }
    }

    /* Smooth Scrolling */
    html {
        scroll-behavior: smooth;
    }

    /* Leaflet Map Adjustments */
    .leaflet-container {
        font-family: inherit;
    }

    .leaflet-popup-content-wrapper {
        border-radius: var(--radius);
        box-shadow: var(--shadow-lg);
    }

    .leaflet-popup-content h4 {
        font-family: inherit;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <a href="{{ route('admin.lost-found.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Lost & Found Records</span>
    </a>

    <div class="page-header">
        <h1 class="page-title">View Listing</h1>
        <p class="page-subtitle">View details of this lost/found pet listing</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
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
                    <div class="detail-icon">
                        <i class="fas fa-paw"></i>
                    </div>
                    <div class="detail-content">
                        <h4>Pet Type</h4>
                        <p>{{ ucfirst($lostFound->pet_type) }}</p>
                    </div>
                </div>

                @if($lostFound->breed)
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-tag"></i>
                    </div>
                    <div class="detail-content">
                        <h4>Breed</h4>
                        <p>{{ $lostFound->breed }}</p>
                    </div>
                </div>
                @endif

                @if($lostFound->color)
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <div class="detail-content">
                        <h4>Color</h4>
                        <p>{{ $lostFound->color }}</p>
                    </div>
                </div>
                @endif

                @if($lostFound->size)
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-ruler"></i>
                    </div>
                    <div class="detail-content">
                        <h4>Size</h4>
                        <p>{{ ucfirst($lostFound->size) }}</p>
                    </div>
                </div>
                @endif

                @if($lostFound->age)
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="detail-content">
                        <h4>Age</h4>
                        <p>{{ $lostFound->age }} years old</p>
                    </div>
                </div>
                @endif

                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-venus-mars"></i>
                    </div>
                    <div class="detail-content">
                        <h4>Gender</h4>
                        <p>{{ ucfirst($lostFound->gender) }}</p>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="detail-content">
                        <h4>Location</h4>
                        <p>{{ $lostFound->location }}</p>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="detail-content">
                        <h4>Date {{ $lostFound->type == 'lost' ? 'Lost' : 'Found' }}</h4>
                        <p>{{ $lostFound->date_lost_found->format('M d, Y') }}</p>
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="detail-content">
                        <h4>Submitted by</h4>
                        <p>{{ $lostFound->user->name }}</p>
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="detail-content">
                        <h4>Submitted on</h4>
                        <p>{{ $lostFound->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="detail-content">
                        <h4>Status</h4>
                        <p>{{ $lostFound->is_resolved ? 'Resolved' : 'Unresolved' }}</p>
                    </div>
                </div>
            </div>

            @if($lostFound->latitude && $lostFound->longitude)
            <div class="section map-section">
                <h2 class="section-title">
                    <i class="fas fa-map-marked-alt"></i>
                    Location on Map
                </h2>
                <div id="map" class="map-container"></div>
            </div>
            @endif

            <div class="section description-section">
                <h2 class="section-title">
                    <i class="fas fa-align-left"></i>
                    Description
                </h2>
                <div class="description-text">{{ $lostFound->description }}</div>
            </div>

            <div class="section">
                <h2 class="section-title">
                    <i class="fas fa-phone"></i>
                    Contact Information
                </h2>
                <div class="contact-section">
                    <div class="contact-info">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <strong>{{ $lostFound->contact_name }}</strong>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <strong>{{ $lostFound->contact_phone }}</strong>
                        </div>
                        @if($lostFound->contact_email)
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <strong>{{ $lostFound->contact_email }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($lostFound->latitude && $lostFound->longitude)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const map = L.map('map').setView([{{ $lostFound->latitude }}, {{ $lostFound->longitude }}], 15);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        let customIcon;
        if ("{{ $lostFound->image_path }}") {
            customIcon = L.divIcon({
                html: `<div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                    <img src="/storage/{{ $lostFound->image_path }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>`,
                iconSize: [56, 56],
                iconAnchor: [28, 28],
                popupAnchor: [0, -28],
                className: 'custom-marker'
            });
        } else {
            const color = "{{ $lostFound->type }}" === 'lost' ? '#ef4444' : '#10b981';
            const iconClass = "{{ $lostFound->type }}" === 'lost' ? 'fas fa-heart-broken' : 'fas fa-heart';
            customIcon = L.divIcon({
                html: `<div style="background: ${color}; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                    <i class="${iconClass}" style="font-size: 16px;"></i>
                </div>`,
                iconSize: [46, 46],
                iconAnchor: [23, 23],
                popupAnchor: [0, -28],
                className: 'custom-marker'
            });
        }
        
        const marker = L.marker([{{ $lostFound->latitude }}, {{ $lostFound->longitude }}], { icon: customIcon }).addTo(map);
        
        const popupContent = `
            <div style="min-width: 200px;">
                <h4 style="margin: 0 0 10px 0; color: #1f2937; font-size: 1.1rem;">{{ $lostFound->pet_name }}</h4>
                <p style="margin: 5px 0; color: #4b5563; font-size: 0.9rem;">
                    <i class="fas fa-{{ $lostFound->type == 'lost' ? 'exclamation-triangle' : 'heart' }}" style="color: {{ $lostFound->type == 'lost' ? '#ef4444' : '#10b981' }}; margin-right: 6px;"></i>
                    {{ ucfirst($lostFound->type) }} Pet
                </p>
                <p style="margin: 5px 0; color: #4b5563; font-size: 0.9rem;">
                    <i class="fas fa-map-marker-alt" style="color: #3b82f6; margin-right: 6px;"></i>
                    {{ $lostFound->location }}
                </p>
            </div>
        `;
        
        marker.bindPopup(popupContent);
    });
</script>
@endif
@endsection