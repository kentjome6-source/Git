@extends('layouts.app')

@section('title', 'Report ' . $pet->name . ' Missing')

@section('content')
<div class="report-missing-page">
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
            <h1 class="page-title">Report {{ $pet->name }} Missing</h1>
            <p class="page-subtitle">Click on the map to mark the last seen location</p>
        </div>

        <div class="row g-4">
            <!-- Form Column -->
            <div class="col-lg-6">
                <div class="form-card">
                    <form action="{{ route('lost-found.store') }}" method="POST" enctype="multipart/form-data" id="reportForm">
                        @csrf
                        <input type="hidden" name="type" value="lost">
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">

                        <div class="form-section">
                            <h3 class="section-title">Pet Information</h3>
                            
                            <!-- Pet Name -->
                            <div class="form-group">
                                <label for="pet_name" class="form-label">Pet Name <span class="required">*</span></label>
                                <input type="text" class="form-control @error('pet_name') is-invalid @enderror" 
                                       id="pet_name" name="pet_name" value="{{ old('pet_name', $pet->name) }}" required readonly>
                                @error('pet_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Pet Type -->
                            <div class="form-group">
                                <label for="pet_type" class="form-label">Pet Type <span class="required">*</span></label>
                                <select class="form-control @error('pet_type') is-invalid @enderror" 
                                        id="pet_type" name="pet_type" required>
                                    <option value="">Select type</option>
                                    <option value="Dog" {{ old('pet_type') == 'Dog' ? 'selected' : '' }}>Dog</option>
                                    <option value="Cat" {{ old('pet_type') == 'Cat' ? 'selected' : '' }}>Cat</option>
                                    <option value="Bird" {{ old('pet_type') == 'Bird' ? 'selected' : '' }}>Bird</option>
                                    <option value="Other" {{ old('pet_type') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('pet_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Breed -->
                            <div class="form-group">
                                <label for="breed" class="form-label">Breed</label>
                                <input type="text" class="form-control @error('breed') is-invalid @enderror" 
                                       id="breed" name="breed" value="{{ old('breed', $pet->breed) }}">
                                @error('breed')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="color" class="form-label">Color</label>
                                        <input type="text" class="form-control @error('color') is-invalid @enderror" 
                                               id="color" name="color" value="{{ old('color') }}">
                                        @error('color')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="size" class="form-label">Size</label>
                                        <select class="form-control @error('size') is-invalid @enderror" 
                                                id="size" name="size">
                                            <option value="">Select size</option>
                                            <option value="small" {{ old('size') == 'small' ? 'selected' : '' }}>Small</option>
                                            <option value="medium" {{ old('size') == 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="large" {{ old('size') == 'large' ? 'selected' : '' }}>Large</option>
                                        </select>
                                        @error('size')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="age" class="form-label">Age (years)</label>
                                        <input type="number" class="form-control @error('age') is-invalid @enderror" 
                                               id="age" name="age" value="{{ old('age') }}" min="0" max="30">
                                        @error('age')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender" class="form-label">Gender <span class="required">*</span></label>
                                        <select class="form-control @error('gender') is-invalid @enderror" 
                                                id="gender" name="gender" required>
                                            <option value="">Select gender</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="unknown" {{ old('gender') == 'unknown' ? 'selected' : '' }}>Unknown</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                                <label for="description" class="form-label">Description <span class="required">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3" 
                                          placeholder="Provide any distinctive features or markings..." required>{{ old('description', $pet->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Pet Image -->
                            <div class="form-group">
                                <label for="image" class="form-label">Pet Photo</label>
                                @if($pet->image_path)
                                    <div class="current-image mb-2">
                                        <img src="{{ asset('storage/' . $pet->image_path) }}" alt="{{ $pet->name }}">
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*">
                                <small class="form-text">Upload a clear photo to help identify your pet</small>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-section">
                            <h3 class="section-title">Last Seen Details</h3>

                            <!-- Location -->
                            <div class="form-group">
                                <label for="location" class="form-label">Last Seen Location <span class="required">*</span></label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                       id="location" name="location" value="{{ old('location') }}" 
                                       placeholder="e.g., Near SM Mall, Manila" required>
                                <small class="form-text">Click on the map to set coordinates automatically</small>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Coordinates Display -->
                            <div class="coordinates-display" id="coordinatesDisplay" style="display: none;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                <span id="coordsText">No location selected</span>
                            </div>

                            <!-- Date Lost -->
                            <div class="form-group">
                                <label for="date_lost_found" class="form-label">Date Last Seen <span class="required">*</span></label>
                                <input type="date" class="form-control @error('date_lost_found') is-invalid @enderror" 
                                       id="date_lost_found" name="date_lost_found" 
                                       value="{{ old('date_lost_found', date('Y-m-d')) }}" 
                                       max="{{ date('Y-m-d') }}" required>
                                @error('date_lost_found')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-section">
                            <h3 class="section-title">Contact Information</h3>

                            <!-- Contact Name -->
                            <div class="form-group">
                                <label for="contact_name" class="form-label">Your Name <span class="required">*</span></label>
                                <input type="text" class="form-control @error('contact_name') is-invalid @enderror" 
                                       id="contact_name" name="contact_name" 
                                       value="{{ old('contact_name', Auth::user()->name) }}" required>
                                @error('contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact_phone" class="form-label">Phone Number <span class="required">*</span></label>
                                        <input type="tel" class="form-control @error('contact_phone') is-invalid @enderror" 
                                               id="contact_phone" name="contact_phone" 
                                               value="{{ old('contact_phone') }}" required>
                                        @error('contact_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact_email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                               id="contact_email" name="contact_email" 
                                               value="{{ old('contact_email', Auth::user()->email) }}">
                                        @error('contact_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <a href="{{ route('my.pets') }}" class="btn-cancel">Cancel</a>
                            <button type="submit" class="btn-submit">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                                Submit Missing Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Map Column -->
            <div class="col-lg-6">
                <div class="map-card sticky-top">
                    <div class="map-header">
                        <h3>Click Map to Mark Last Seen Location</h3>
                        <p>Tap anywhere on the map to set the coordinates</p>
                    </div>
                    <div class="map-container" id="map"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    :root {
        --slate: #0f172a;
        --blue: #3b82f6;
        --purple: #8b5cf6;
        --orange: #f59e0b;
        --gray: #64748b;
        --gray-light: #f1f5f9;
        --gray-lighter: #f8fafc;
    }

    .report-missing-page {
        font-family: 'Sora', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--gray-lighter);
        min-height: 100vh;
    }

    .page-header {
        margin-bottom: 32px;
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

    .page-subtitle {
        font-size: 1rem;
        color: var(--orange);
        font-weight: 500;
    }

    .form-card, .map-card {
        background: white;
        border-radius: 16px;
        padding: 32px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .map-card {
        position: sticky;
        top: 20px;
    }

    .map-header {
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid var(--orange);
    }

    .map-header h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--slate);
        margin-bottom: 4px;
    }

    .map-header p {
        font-size: 0.85rem;
        color: var(--gray);
        margin: 0;
    }

    .map-container {
        width: 100%;
        height: 500px;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid #e2e8f0;
    }

    .current-image {
        width: 150px;
        height: 150px;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid #e2e8f0;
    }

    .current-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .coordinates-display {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 16px;
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid var(--orange);
        border-radius: 8px;
        color: var(--orange);
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 20px;
    }

    .form-section {
        margin-bottom: 32px;
    }

    .section-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--slate);
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--purple);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--slate);
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .required {
        color: #ef4444;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--purple);
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .form-text {
        display: block;
        color: var(--gray);
        font-size: 0.8rem;
        margin-top: 6px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        padding-top: 24px;
        border-top: 1px solid #e2e8f0;
    }

    .btn-cancel,
    .btn-submit {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        cursor: pointer;
    }

    .btn-cancel {
        background: white;
        color: var(--gray);
        border: 1px solid #e2e8f0;
    }

    .btn-cancel:hover {
        background: var(--gray-light);
        color: var(--slate);
    }

    .btn-submit {
        background: var(--orange);
        color: white;
    }

    .btn-submit:hover {
        background: #d97706;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    @media (max-width: 991px) {
        .map-card {
            position: relative;
            top: 0;
            margin-top: 24px;
        }

        .map-container {
            height: 400px;
        }
    }

    @media (max-width: 768px) {
        .form-card, .map-card {
            padding: 20px;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn-cancel,
        .btn-submit {
            width: 100%;
            justify-content: center;
        }

        .page-title {
            font-size: 1.5rem;
        }
    }
</style>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map centered on Philippines
        const map = L.map('map').setView([14.5995, 120.9842], 13);
        
        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);
        
        let marker = null;
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        const coordsDisplay = document.getElementById('coordinatesDisplay');
        const coordsText = document.getElementById('coordsText');
        
        // Handle map click
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            
            // Update form inputs
            latInput.value = lat.toFixed(8);
            lngInput.value = lng.toFixed(8);
            
            // Show coordinates
            coordsDisplay.style.display = 'flex';
            coordsText.textContent = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
            
            // Remove existing marker
            if (marker) {
                map.removeLayer(marker);
            }
            
            // Add new marker
            const customIcon = L.divIcon({
                className: 'custom-marker',
                html: `<div style="
                    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-size: 20px;
                    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
                    border: 3px solid white;
                ">
                    📍
                </div>`,
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            });
            
            marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);
            marker.bindPopup('Last seen location').openPopup();
        });
        
        // Form validation
        document.getElementById('reportForm').addEventListener('submit', function(e) {
            if (!latInput.value || !lngInput.value) {
                e.preventDefault();
                alert('Please click on the map to mark the last seen location!');
                return false;
            }
        });
    });
</script>
@endsection
