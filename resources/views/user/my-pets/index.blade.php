@extends('layouts.app')

@section('title', 'My Pets')

@section('content')
<div class="my-pets-page">
    <div class="container-fluid px-4 py-5">
        <!-- Page Header -->
        <div class="page-header mb-5">
            <div class="header-content">
                <div class="header-text">
                    <span class="label">Pet Management</span>
                    <h1 class="page-title">My Pets</h1>
                    <p class="page-subtitle">Track and manage your registered pets</p>
                </div>
                <button type="button" class="btn-add-pet" data-bs-toggle="modal" data-bs-target="#addPetModal">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Add Pet</span>
                </button>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert-success-custom mb-4">
                <div class="alert-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <div class="alert-content">{{ session('success') }}</div>
                <button type="button" class="alert-close" onclick="this.parentElement.style.display='none'">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        @endif

        @if($pets->count() > 0)
            <!-- Pets Grid -->
            <div class="pets-grid">
                @foreach($pets as $pet)
                    <div class="pet-card">
                        <div class="pet-image-wrapper">
                            @if($pet->image_path)
                                <img src="{{ asset('storage/' . $pet->image_path) }}" class="pet-image" alt="{{ $pet->name }}">
                            @else
                                <div class="pet-image-placeholder">
                                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                                        <line x1="9" y1="9" x2="9.01" y2="9"></line>
                                        <line x1="15" y1="9" x2="15.01" y2="9"></line>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="pet-card-body">
                            <h3 class="pet-name">{{ $pet->name }}</h3>
                            @if($pet->breed)
                                <p class="pet-breed">{{ $pet->breed }}</p>
                            @endif

                            @if($pet->description)
                                <p class="pet-description">{{ Str::limit($pet->description, 80) }}</p>
                            @endif
                        </div>

                        <div class="pet-card-footer">
                            <a href="{{ route('my.pets.report-missing', $pet) }}" class="btn-report-missing">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                                Report Missing
                            </a>

                            <button type="button" class="btn-location" data-bs-toggle="modal" data-bs-target="#locationModal{{ $pet->id }}">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                Pet Location
                            </button>

                            <a href="{{ route('pet.multipet.edit', $pet) }}" class="btn-edit" data-modal data-modal-title="Edit Pet">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                Edit
                            </a>
                        </div>
                    </div>

                    <!-- Location Modal for this pet -->
                    <div class="modal fade" id="locationModal{{ $pet->id }}" tabindex="-1" aria-labelledby="locationModalLabel{{ $pet->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="locationModalLabel{{ $pet->id }}">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        {{ $pet->name }}'s Current Location
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-0">
                                    <div class="location-info-header">
                                        <div class="pet-info-mini">
                                            @if($pet->image_path)
                                                <img src="{{ asset('storage/' . $pet->image_path) }}" alt="{{ $pet->name }}" class="pet-mini-avatar">
                                            @else
                                                <div class="pet-mini-avatar-placeholder">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                                                        <line x1="9" y1="9" x2="9.01" y2="9"></line>
                                                        <line x1="15" y1="9" x2="15.01" y2="9"></line>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $pet->name }}</strong>
                                                @if($pet->breed)
                                                    <p class="mb-0 text-muted small">{{ $pet->breed }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="location-status-badge">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                            <span>Live Location</span>
                                        </div>
                                    </div>
                                    <div class="location-map" id="locationMap{{ $pet->id }}" style="height: 400px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                        <line x1="9" y1="9" x2="9.01" y2="9"></line>
                        <line x1="15" y1="9" x2="15.01" y2="9"></line>
                    </svg>
                </div>
                <h3 class="empty-title">No Pets Registered</h3>
                <p class="empty-text">Start by adding your first pet to track and manage them</p>
                <button type="button" class="btn-empty-action" data-bs-toggle="modal" data-bs-target="#addPetModal">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Add Your First Pet
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Add Pet Modal -->
<div class="modal fade" id="addPetModal" tabindex="-1" aria-labelledby="addPetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPetModalLabel">Add New Pet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('pet.multipet.store') }}" method="POST" enctype="multipart/form-data" id="addPetForm">
                    @csrf

                    <!-- Pet Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Pet Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Breed -->
                    <div class="mb-3">
                        <label for="breed" class="form-label">Breed</label>
                        <input type="text" class="form-control @error('breed') is-invalid @enderror" 
                               id="breed" name="breed" value="{{ old('breed') }}" 
                               placeholder="e.g., Golden Retriever, Persian Cat">
                        @error('breed')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Tell us about your pet...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Pet Image -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Pet Photo</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        <small class="form-text text-muted">Accepted formats: JPEG, PNG, JPG, GIF (Max: 2MB)</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Add Pet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --slate: #0f172a;
        --slate-light: #1e293b;
        --blue: #3b82f6;
        --purple: #8b5cf6;
        --green: #10b981;
        --orange: #f59e0b;
        --red: #ef4444;
        --gray: #64748b;
        --gray-light: #f1f5f9;
        --gray-lighter: #f8fafc;
    }

    .my-pets-page {
        font-family: 'Sora', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--gray-lighter);
        min-height: 100vh;
    }

    /* Page Header */
    .page-header {
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

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 24px;
    }

    .label {
        display: block;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--blue);
        margin-bottom: 12px;
        font-weight: 600;
    }

    .page-title {
        font-size: clamp(2rem, 4vw, 2.75rem);
        font-weight: 700;
        color: var(--slate);
        margin-bottom: 8px;
        letter-spacing: -0.02em;
    }

    .page-subtitle {
        font-size: 1.05rem;
        color: var(--gray);
    }

    .btn-add-pet {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: var(--purple);
        color: white;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-add-pet:hover {
        background: #7c3aed;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        color: white;
    }

    /* Success Alert */
    .alert-success-custom {
        background: #d1fae5;
        border: 1px solid #a7f3d0;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        animation: slideDown 0.4s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-icon {
        flex-shrink: 0;
        color: #059669;
    }

    .alert-content {
        flex: 1;
        color: #065f46;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .alert-close {
        flex-shrink: 0;
        background: none;
        border: none;
        color: #059669;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        transition: opacity 0.2s;
    }

    .alert-close:hover {
        opacity: 0.7;
    }

    /* Pets Grid */
    .pets-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
        animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .pet-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .pet-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        border-color: var(--blue);
        transform: translateY(-4px);
    }

    .pet-image-wrapper {
        position: relative;
        width: 100%;
        height: 250px;
        overflow: hidden;
        background: var(--gray-light);
    }

    .pet-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pet-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gray);
    }

    .pet-card-body {
        padding: 20px;
    }

    .pet-name {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--slate);
        margin-bottom: 6px;
    }

    .pet-breed {
        font-size: 0.9rem;
        color: var(--blue);
        font-weight: 500;
        margin-bottom: 12px;
    }

    .pet-description {
        font-size: 0.9rem;
        color: var(--gray);
        line-height: 1.6;
        margin-bottom: 12px;
    }

    .pet-card-footer {
        display: flex;
        gap: 8px;
        padding: 16px 20px;
        border-top: 1px solid #e2e8f0;
    }

    .btn-report-missing,
    .btn-edit {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.2s;
        flex: 1;
        justify-content: center;
    }

    .btn-track {
        background: var(--green);
        color: white;
    }

    .btn-track:hover {
        background: #059669;
        transform: translateY(-1px);
        color: white;
    }

    .btn-report-missing {
        background: var(--orange);
        color: white;
    }

    .btn-report-missing:hover {
        background: #d97706;
        transform: translateY(-1px);
        color: white;
    }

    .btn-edit {
        background: white;
        color: var(--purple);
        border: 1px solid var(--purple);
    }

    .btn-edit:hover {
        background: var(--purple);
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        max-width: 600px;
        margin: 0 auto;
    }

    .empty-icon {
        margin-bottom: 24px;
        color: var(--gray);
        opacity: 0.4;
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
        margin-bottom: 28px;
    }

    .btn-empty-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 14px 28px;
        background: var(--purple);
        color: white;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-empty-action:hover {
        background: #7c3aed;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        color: white;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        border-bottom: 2px solid var(--purple);
        padding: 20px 24px;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--slate);
    }

    .modal-body {
        padding: 24px;
    }

    .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 16px 0 0 0;
    }

    .btn-close {
        filter: none;
    }

    .btn-close:focus {
        box-shadow: none;
    }

    /* Location Modal Styles */
    .location-info-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: var(--gray-lighter);
    }

    .pet-info-mini {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .pet-mini-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--green);
    }

    .pet-mini-avatar-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--gray-light);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid var(--green);
        color: var(--gray);
    }

    .location-status-badge {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: rgba(16, 185, 129, 0.1);
        color: var(--green);
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .location-map {
        width: 100%;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-add-pet {
            width: 100%;
            justify-content: center;
        }

        .pets-grid {
            grid-template-columns: 1fr;
        }

        .pet-card-footer {
            flex-direction: column;
        }

        .btn-report-missing,
        .btn-location,
        .btn-edit {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 16px !important;
            padding-right: 16px !important;
        }

        .page-title {
            font-size: 1.75rem;
        }

        .pet-image-wrapper {
            height: 200px;
        }
    }
</style>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var addPetModal = new bootstrap.Modal(document.getElementById('addPetModal'));
        addPetModal.show();
    });
</script>
@endif

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Store initialized maps to prevent re-initialization
    const initializedMaps = {};
    const petMarkers = {}; // Store pet markers for animation
    const movementIntervals = {}; // Store interval IDs

    // Initialize map when modal is shown
    document.addEventListener('DOMContentLoaded', function() {
        // Get user's location (default to Philippines if geolocation fails)
        let userLat = 14.5995; // Default Manila
        let userLng = 120.9842;

        // Try to get user's actual location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                userLat = position.coords.latitude;
                userLng = position.coords.longitude;
            }, function(error) {
                console.log('Geolocation error:', error);
            });
        }

        // Function to generate random offset within 1-5 km radius
        function generatePetLocation(baseLat, baseLng) {
            // Random distance between 0.01 and 0.05 degrees (~1-5km)
            const maxOffset = 0.05;
            const minOffset = 0.01;
            const offset = Math.random() * (maxOffset - minOffset) + minOffset;
            
            // Random angle
            const angle = Math.random() * 2 * Math.PI;
            
            // Calculate new coordinates
            const latOffset = offset * Math.cos(angle);
            const lngOffset = offset * Math.sin(angle);
            
            return {
                lat: baseLat + latOffset,
                lng: baseLng + lngOffset
            };
        }

        // Function to generate small movement (5-20 meters)
        function generateSmallMovement(currentLat, currentLng) {
            // Very small offset for slow movement (0.00005 to 0.0002 degrees ~ 5-20 meters)
            const maxOffset = 0.0002;
            const minOffset = 0.00005;
            const offset = Math.random() * (maxOffset - minOffset) + minOffset;
            
            // Random direction
            const angle = Math.random() * 2 * Math.PI;
            
            const latOffset = offset * Math.cos(angle);
            const lngOffset = offset * Math.sin(angle);
            
            return {
                lat: currentLat + latOffset,
                lng: currentLng + lngOffset
            };
        }

        // Function to smoothly move marker
        function animateMarker(marker, currentPos, targetPos, duration, callback) {
            const startTime = Date.now();
            const startLat = currentPos.lat;
            const startLng = currentPos.lng;
            const deltaLat = targetPos.lat - startLat;
            const deltaLng = targetPos.lng - startLng;

            function update() {
                const elapsed = Date.now() - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                // Ease in-out function for smooth movement
                const easeProgress = progress < 0.5 
                    ? 2 * progress * progress 
                    : 1 - Math.pow(-2 * progress + 2, 2) / 2;

                const newLat = startLat + (deltaLat * easeProgress);
                const newLng = startLng + (deltaLng * easeProgress);

                marker.setLatLng([newLat, newLng]);

                if (progress < 1) {
                    requestAnimationFrame(update);
                } else if (callback) {
                    callback();
                }
            }

            requestAnimationFrame(update);
        }

        // Listen to all location modal show events
        @foreach($pets as $pet)
            const modal{{ $pet->id }} = document.getElementById('locationModal{{ $pet->id }}');
            
            modal{{ $pet->id }}.addEventListener('shown.bs.modal', function() {
                const mapId = 'locationMap{{ $pet->id }}';
                const petId = '{{ $pet->id }}';
                
                // Only initialize if not already initialized
                if (!initializedMaps[mapId]) {
                    // Generate fake pet location near user
                    let petLocation = generatePetLocation(userLat, userLng);
                    
                    // Initialize map
                    const map = L.map(mapId).setView([petLocation.lat, petLocation.lng], 15);
                    
                    // Add tile layer
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors',
                        maxZoom: 19
                    }).addTo(map);
                    
                    // Custom pet marker icon
                    const petIcon = L.divIcon({
                        className: 'custom-pet-marker',
                        html: `<div style="
                            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                            width: 50px;
                            height: 50px;
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            color: white;
                            font-size: 24px;
                            box-shadow: 0 4px 16px rgba(16, 185, 129, 0.4);
                            border: 4px solid white;
                            animation: pulse 2s infinite;
                        ">
                            🐾
                        </div>
                        <style>
                            @keyframes pulse {
                                0%, 100% { transform: scale(1); }
                                50% { transform: scale(1.1); }
                            }
                        </style>`,
                        iconSize: [50, 50],
                        iconAnchor: [25, 25]
                    });
                    
                    // Add marker
                    const marker = L.marker([petLocation.lat, petLocation.lng], { icon: petIcon }).addTo(map);
                    petMarkers[petId] = marker;
                    
                    // Add popup
                    const popup = L.popup()
                        .setContent(`
                            <div style="text-align: center; padding: 8px; min-width: 150px;">
                                <strong style="font-size: 1.1rem; color: #0f172a;">{{ $pet->name }}</strong>
                                <p style="margin: 8px 0 4px 0; color: #64748b; font-size: 0.85rem;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <span id="lastUpdate{{ $pet->id }}">Last updated: Just now</span>
                                </p>
                                <p style="margin: 0; color: #10b981; font-size: 0.8rem; font-weight: 600;">
                                    ● Live Location
                                </p>
                            </div>
                        `);
                    
                    marker.bindPopup(popup).openPopup();
                    
                    // Add circle to show approximate area
                    const circle = L.circle([petLocation.lat, petLocation.lng], {
                        color: '#10b981',
                        fillColor: '#10b981',
                        fillOpacity: 0.1,
                        radius: 100,
                        weight: 2
                    }).addTo(map);
                    
                    // Add user location marker
                    const userIcon = L.divIcon({
                        className: 'user-marker',
                        html: `<div style="
                            background: #3b82f6;
                            width: 20px;
                            height: 20px;
                            border-radius: 50%;
                            border: 3px solid white;
                            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.4);
                        "></div>`,
                        iconSize: [20, 20],
                        iconAnchor: [10, 10]
                    });
                    
                    const userMarker = L.marker([userLat, userLng], { icon: userIcon })
                        .addTo(map)
                        .bindPopup('<strong>Your Location</strong>');
                    
                    // Draw line between user and pet
                    let connectionLine = L.polyline([
                        [userLat, userLng],
                        [petLocation.lat, petLocation.lng]
                    ], {
                        color: '#8b5cf6',
                        weight: 2,
                        opacity: 0.6,
                        dashArray: '10, 10'
                    }).addTo(map);
                    
                    // Calculate distance
                    let distance = map.distance([userLat, userLng], [petLocation.lat, petLocation.lng]);
                    let distanceKm = (distance / 1000).toFixed(2);
                    
                    // Add distance info
                    const info = L.control({position: 'bottomleft'});
                    info.onAdd = function() {
                        const div = L.DomUtil.create('div', 'info-box');
                        div.style.cssText = `
                            background: white;
                            padding: 10px 15px;
                            border-radius: 8px;
                            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                            font-size: 0.9rem;
                        `;
                        div.innerHTML = `<strong>Distance:</strong> <span id="distance{{ $pet->id }}">${distanceKm} km</span> away`;
                        return div;
                    };
                    info.addTo(map);
                    
                    // Store the map instance
                    initializedMaps[mapId] = map;
                    
                    // Fix map rendering
                    setTimeout(() => map.invalidateSize(), 100);
                    
                    // Start pet movement animation (moves every 8-15 seconds)
                    function startPetMovement() {
                        const currentPos = marker.getLatLng();
                        const newPos = generateSmallMovement(currentPos.lat, currentPos.lng);
                        
                        // Animate marker to new position over 3 seconds
                        animateMarker(marker, currentPos, newPos, 3000, function() {
                            // Update circle position
                            circle.setLatLng([newPos.lat, newPos.lng]);
                            
                            // Update connection line
                            map.removeLayer(connectionLine);
                            connectionLine = L.polyline([
                                [userLat, userLng],
                                [newPos.lat, newPos.lng]
                            ], {
                                color: '#8b5cf6',
                                weight: 2,
                                opacity: 0.6,
                                dashArray: '10, 10'
                            }).addTo(map);
                            
                            // Update distance
                            distance = map.distance([userLat, userLng], [newPos.lat, newPos.lng]);
                            distanceKm = (distance / 1000).toFixed(2);
                            const distanceEl = document.getElementById('distance{{ $pet->id }}');
                            if (distanceEl) {
                                distanceEl.textContent = distanceKm + ' km';
                            }
                            
                            // Update last updated time
                            const lastUpdateEl = document.getElementById('lastUpdate{{ $pet->id }}');
                            if (lastUpdateEl) {
                                lastUpdateEl.textContent = 'Last updated: Just now';
                            }
                        });
                        
                        // Schedule next movement (random between 8-15 seconds)
                        const nextMove = Math.random() * 7000 + 8000;
                        movementIntervals[petId] = setTimeout(startPetMovement, nextMove);
                    }
                    
                    // Start first movement after 8 seconds
                    movementIntervals[petId] = setTimeout(startPetMovement, 8000);
                }
            });
            
            // Clean up interval when modal is hidden
            modal{{ $pet->id }}.addEventListener('hidden.bs.modal', function() {
                const petId = '{{ $pet->id }}';
                if (movementIntervals[petId]) {
                    clearTimeout(movementIntervals[petId]);
                    delete movementIntervals[petId];
                }
            });
        @endforeach
    });
</script>
@endsection
