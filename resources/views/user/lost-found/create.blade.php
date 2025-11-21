@extends('layouts.app')

@section('title', 'Report Pet')

@section('styles')
<style>
        
        .page-header {
            text-align: center; margin-bottom: 40px;
        }
        .page-title { font-size: 2.5rem; color: #5b4b9b; margin-bottom: 10px; }
        .page-subtitle { font-size: 1.1rem; color: #666; }

        .form-container {
            background: #fff; border-radius: 15px; padding: 40px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .form-group {
            margin-bottom: 25px;
        }
        .form-label {
            display: block; margin-bottom: 8px; font-weight: 600; color: #333;
        }
        .form-input, .form-select, .form-textarea {
            width: 100%; padding: 12px 15px; border: 2px solid #ddd;
            border-radius: 8px; font-size: 1rem; transition: 0.2s;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none; border-color: #5b4b9b; box-shadow: 0 0 0 3px rgba(91, 75, 155, 0.1);
        }
        .form-textarea { min-height: 120px; resize: vertical; }
        .form-help { font-size: 0.85rem; color: #666; margin-top: 5px; }

        .radio-group {
            display: flex; gap: 20px; margin-top: 10px;
        }
        .radio-item {
            display: flex; align-items: center; gap: 8px;
        }
        .radio-item input[type="radio"] { margin: 0; }

        .form-row {
            display: grid; grid-template-columns: 1fr 1fr; gap: 20px;
        }

        .file-upload {
            position: relative; display: inline-block; width: 100%;
        }
        .file-upload input[type="file"] {
            position: absolute; opacity: 0; width: 100%; height: 100%;
            cursor: pointer;
        }
        .file-upload-label {
            display: flex; align-items: center; justify-content: center;
            gap: 10px; padding: 20px; border: 2px dashed #ddd;
            border-radius: 8px; background: #f9f9f9; cursor: pointer;
            transition: 0.2s; text-align: center;
        }
        .file-upload-label:hover { border-color: #5b4b9b; background: #f0f0ff; }
        .file-preview {
            margin-top: 15px; text-align: center;
        }
        .file-preview img {
            max-width: 200px; max-height: 200px; border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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

        .form-actions {
            display: flex; gap: 15px; justify-content: center; margin-top: 30px;
        }

        .alert {
            padding: 15px 20px; border-radius: 8px; margin-bottom: 20px;
            font-weight: 500;
        }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        .required { color: #e74c3c; }

        /* Map Styles */
        .map-container {
            height: 500px; border-radius: 8px; overflow: hidden; 
            border: 2px solid #ddd; margin-top: 10px;
        }
        .map-instructions {
            background: #e3f2fd; padding: 15px; border-radius: 8px; 
            margin-top: 10px; border-left: 4px solid #2196f3;
        }
        
        /* Responsive styles */
        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }
            
            .page-subtitle {
                font-size: 1rem;
            }
            
            .form-container {
                padding: 20px 15px;
                margin: 0 0.5rem;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .radio-group {
                flex-direction: column;
                gap: 10px;
            }
            
            .form-label {
                font-size: 0.9rem;
            }
            
            .form-input, .form-select, .form-textarea {
                font-size: 1rem;
                padding: 10px 12px;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
                padding: 10px 15px;
                font-size: 0.9rem;
            }
            
            .form-actions {
                flex-direction: column;
                gap: 10px;
            }
            
            .map-container {
                height: 300px;
            }
            
            .file-upload-label {
                padding: 15px 10px;
                font-size: 0.9rem;
            }
            
            .file-preview img {
                max-width: 150px;
                max-height: 150px;
            }
        }
        
        @media (max-width: 576px) {
            .page-header {
                margin-bottom: 30px;
            }
            
            .page-title {
                font-size: 1.75rem;
            }
            
            .form-container {
                padding: 15px;
                margin: 0;
            }
            
            .form-group {
                margin-bottom: 20px;
            }
            
            .form-textarea {
                min-height: 100px;
            }
            
            .map-container {
                height: 250px;
            }
            
            .map-instructions {
                padding: 10px;
                font-size: 0.9rem;
            }
        }
</style>
@endsection

@section('content')
        <div class="page-header">
            <h1 class="page-title">Report Lost/Found Pet</h1>
            <p class="page-subtitle">Help reunite pets with their families</p>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-top: 20px; border: 1px solid #c3e6cb;">
                <i class="fas fa-info-circle"></i>
                <strong>Note:</strong> Your listing will be reviewed by our admin team before being published. This helps ensure quality and accuracy of all listings.
            </div>
        </div>

        <div class="form-container">
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Please correct the following errors:
                    <ul style="margin-top: 10px; margin-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('lost-found.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Type <span class="required">*</span></label>
                    <div class="radio-group">
                        <div class="radio-item">
                            <input type="radio" name="type" value="lost" id="type_lost" 
                                   {{ request('type') == 'lost' || old('type') == 'lost' ? 'checked' : '' }}>
                            <label for="type_lost">
                                <i class="fas fa-exclamation-triangle" style="color: #e74c3c;"></i>
                                Lost Pet
                            </label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" name="type" value="found" id="type_found"
                                   {{ request('type') == 'found' || old('type') == 'found' ? 'checked' : '' }}>
                            <label for="type_found">
                                <i class="fas fa-heart" style="color: #27ae60;"></i>
                                Found Pet
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="pet_name" class="form-label">Pet Name <span class="required">*</span></label>
                    <input type="text" id="pet_name" name="pet_name" class="form-input" 
                           value="{{ old('pet_name') }}" placeholder="Enter pet's name" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="pet_type" class="form-label">Pet Type <span class="required">*</span></label>
                        <select id="pet_type" name="pet_type" class="form-select" required>
                            <option value="">Select pet type</option>
                            <option value="dog" {{ old('pet_type') == 'dog' ? 'selected' : '' }}>Dog</option>
                            <option value="cat" {{ old('pet_type') == 'cat' ? 'selected' : '' }}>Cat</option>
                            <option value="bird" {{ old('pet_type') == 'bird' ? 'selected' : '' }}>Bird</option>
                            <option value="rabbit" {{ old('pet_type') == 'rabbit' ? 'selected' : '' }}>Rabbit</option>
                            <option value="hamster" {{ old('pet_type') == 'hamster' ? 'selected' : '' }}>Hamster</option>
                            <option value="fish" {{ old('pet_type') == 'fish' ? 'selected' : '' }}>Fish</option>
                            <option value="other" {{ old('pet_type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="breed" class="form-label">Breed</label>
                        <input type="text" id="breed" name="breed" class="form-input" 
                               value="{{ old('breed') }}" placeholder="e.g., Golden Retriever">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="color" class="form-label">Color</label>
                        <input type="text" id="color" name="color" class="form-input" 
                               value="{{ old('color') }}" placeholder="e.g., Brown, White, Black">
                    </div>
                    <div class="form-group">
                        <label for="size" class="form-label">Size</label>
                        <select id="size" name="size" class="form-select">
                            <option value="">Select size</option>
                            <option value="small" {{ old('size') == 'small' ? 'selected' : '' }}>Small</option>
                            <option value="medium" {{ old('size') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="large" {{ old('size') == 'large' ? 'selected' : '' }}>Large</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="age" class="form-label">Age (years)</label>
                        <input type="number" id="age" name="age" class="form-input" 
                               value="{{ old('age') }}" min="0" max="30" placeholder="Age in years">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Gender <span class="required">*</span></label>
                        <div class="radio-group">
                            <div class="radio-item">
                                <input type="radio" name="gender" value="male" id="gender_male" 
                                       {{ old('gender') == 'male' ? 'checked' : '' }}>
                                <label for="gender_male">Male</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" name="gender" value="female" id="gender_female"
                                       {{ old('gender') == 'female' ? 'checked' : '' }}>
                                <label for="gender_female">Female</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" name="gender" value="unknown" id="gender_unknown"
                                       {{ old('gender') == 'unknown' || !old('gender') ? 'checked' : '' }}>
                                <label for="gender_unknown">Unknown</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description <span class="required">*</span></label>
                    <textarea id="description" name="description" class="form-textarea" 
                              placeholder="Describe the pet's appearance, behavior, and any distinctive features..." required>{{ old('description') }}</textarea>
                    <div class="form-help">Include any special markings, collar details, or other identifying features.</div>
                </div>

                <div class="form-group">
                    <label for="location" class="form-label">Location <span class="required">*</span></label>
                    <input type="text" id="location" name="location" class="form-input" 
                           value="{{ old('location') }}" placeholder="Where was the pet lost/found?" required>
                    <div class="form-help">Include street name, neighborhood, or landmark.</div>
                </div>

                <!-- Map Section -->
                <div class="form-group">
                    <label class="form-label">Pin Location on Map</label>
                    <div class="map-instructions">
                        <i class="fas fa-info-circle" style="color: #2196f3; margin-right: 8px;"></i>
                        Click on the map to pin the exact location where the pet was lost/found. This will help others find the pet more easily.
                    </div>
                    <div id="map" class="map-container"></div>
                    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                </div>

                <div class="form-group">
                    <label for="date_lost_found" class="form-label">Date Lost/Found <span class="required">*</span></label>
                    <input type="date" id="date_lost_found" name="date_lost_found" class="form-input" 
                           value="{{ old('date_lost_found') }}" required>
                </div>

                <div class="form-group">
                    <label for="contact_name" class="form-label">Your Name <span class="required">*</span></label>
                    <input type="text" id="contact_name" name="contact_name" class="form-input" 
                           value="{{ old('contact_name', Auth::user()->name) }}" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="contact_phone" class="form-label">Phone Number <span class="required">*</span></label>
                        <input type="tel" id="contact_phone" name="contact_phone" class="form-input" 
                               value="{{ old('contact_phone') }}" placeholder="Your phone number" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_email" class="form-label">Email</label>
                        <input type="email" id="contact_email" name="contact_email" class="form-input" 
                               value="{{ old('contact_email', Auth::user()->email) }}" placeholder="Your email address">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Pet Photo</label>
                    <div class="file-upload">
                        <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                        <label for="image" class="file-upload-label">
                            <i class="fas fa-camera"></i>
                            <span>Click to upload a photo of the pet</span>
                        </label>
                    </div>
                    <div class="form-help">Upload a clear photo to help with identification (optional but recommended).</div>
                    <div id="image-preview" class="file-preview" style="display: none;">
                        <img id="preview-img" src="" alt="Preview">
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('pet.lostfound') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Submit Report
                    </button>
                </div>
            </form>
        </div>

    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Function to perform reverse geocoding
        function reverseGeocode(lat, lng) {
            // Using Nominatim for reverse geocoding (OpenStreetMap)
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data.display_name) {
                        document.getElementById('location').value = data.display_name;
                    }
                })
                .catch(error => {
                    console.error('Reverse geocoding error:', error);
                });
        }

        // Initialize map when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Create map centered on San Francisco, Agusan del Sur with zoom level 15
            // Using the correct coordinates: [8.504588, 125.975800]
            const map = L.map('map').setView([8.504588, 125.975800], 15);
            
            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            // Ensure the map is properly rendered
            setTimeout(function() {
                map.invalidateSize();
                // Double-check that the map is centered correctly
                map.setView([8.504588, 125.975800], 15);
            }, 100);
            
            // Add click event to set marker
            let marker = null;
            
            map.on('click', function(e) {
                // Remove existing marker if any
                if (marker) {
                    map.removeLayer(marker);
                }
                
                // Create new marker
                marker = L.marker(e.latlng).addTo(map);
                
                // Update hidden input fields
                document.getElementById('latitude').value = e.latlng.lat;
                document.getElementById('longitude').value = e.latlng.lng;
                
                // Perform reverse geocoding to update location field
                reverseGeocode(e.latlng.lat, e.latlng.lng);
            });
            
            // If we have existing coordinates from old input, set marker
            const lat = document.getElementById('latitude').value;
            const lng = document.getElementById('longitude').value;
            
            if (lat && lng) {
                const latLng = L.latLng(lat, lng);
                marker = L.marker(latLng).addTo(map);
                map.setView(latLng, 15);
            }
        });
    </script>
@endsection