@extends('layouts.app')

@section('title', 'Edit Adoption Listing')

@section('content')
<div class="create-adoption-page">
    <div class="container-fluid px-4 py-4">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('adoptions.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Adoption Center
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-card">
                    <!-- Form Header -->
                    <div class="form-header">
                        <h1 class="form-title">Edit Adoption Listing</h1>
                        <p class="form-subtitle">Update the information for {{ $adoption->pet_name }}</p>
                    </div>

                    <form action="{{ route('adoptions.update', $adoption) }}" method="POST" enctype="multipart/form-data" id="createAdoptionForm">
                        @csrf
                        @method('PUT')

                        <!-- Pet Basic Information -->
                        <div class="form-section">
                            <h5 class="form-section-title">Basic Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Pet Name <span class="required">*</span></label>
                                    <input type="text" class="form-control @error('pet_name') is-invalid @enderror" 
                                           name="pet_name" value="{{ old('pet_name', $adoption->pet_name) }}" required>
                                    @error('pet_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Species <span class="required">*</span></label>
                                    <select class="form-select @error('species') is-invalid @enderror" name="species" required>
                                        <option value="">Select Species</option>
                                        <option value="dog" {{ old('species', $adoption->species) == 'dog' ? 'selected' : '' }}>Dog</option>
                                        <option value="cat" {{ old('species', $adoption->species) == 'cat' ? 'selected' : '' }}>Cat</option>
                                        <option value="bird" {{ old('species', $adoption->species) == 'bird' ? 'selected' : '' }}>Bird</option>
                                        <option value="rabbit" {{ old('species', $adoption->species) == 'rabbit' ? 'selected' : '' }}>Rabbit</option>
                                        <option value="other" {{ old('species', $adoption->species) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('species')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Breed</label>
                                    <input type="text" class="form-control @error('breed') is-invalid @enderror" 
                                           name="breed" value="{{ old('breed', $adoption->breed) }}">
                                    @error('breed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Age (years) <span class="required">*</span></label>
                                    <input type="number" class="form-control @error('age') is-invalid @enderror" 
                                           name="age" min="0" value="{{ old('age', $adoption->age) }}" required>
                                    @error('age')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Gender <span class="required">*</span></label>
                                    <select class="form-select @error('gender') is-invalid @enderror" name="gender" required>
                                        <option value="">Select</option>
                                        <option value="male" {{ old('gender', $adoption->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $adoption->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Health Information -->
                        <div class="form-section">
                            <h5 class="form-section-title">Health Information</h5>
                            <div class="mb-3">
                                <label class="form-label">Health Status</label>
                                <textarea class="form-control @error('health_status') is-invalid @enderror" 
                                          name="health_status" rows="3" 
                                          placeholder="Describe the pet's current health condition...">{{ old('health_status', $adoption->health_status) }}</textarea>
                                @error('health_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Vaccination Records</label>
                                <textarea class="form-control @error('vaccination_records') is-invalid @enderror" 
                                          name="vaccination_records" rows="3"
                                          placeholder="List any vaccinations and dates...">{{ old('vaccination_records', $adoption->vaccination_records) }}</textarea>
                                @error('vaccination_records')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-section">
                            <h5 class="form-section-title">Description</h5>
                            <div class="mb-3">
                                <label class="form-label">About This Pet</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          name="description" rows="5"
                                          placeholder="Tell potential adopters about this pet's personality, habits, and special needs...">{{ old('description', $adoption->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Pet Image -->
                        <div class="form-section">
                            <h5 class="form-section-title">Pet Photo</h5>
                            <div class="mb-3">
                                <label class="form-label">Upload New Photo (Optional)</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                       name="image" accept="image/*" id="imageInput">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Maximum file size: 2MB. Supported formats: JPG, PNG, GIF</div>
                            </div>
                            @if($adoption->image_path)
                            <div class="mb-3">
                                <label class="form-label">Current Photo</label>
                                <div class="image-preview">
                                    <img src="{{ asset('storage/' . $adoption->image_path) }}" alt="{{ $adoption->pet_name }}">
                                </div>
                            </div>
                            @endif
                            <div id="imagePreview" class="image-preview" style="display: none;">
                                <img id="previewImg" src="" alt="Preview">
                                <button type="button" class="btn btn-sm btn-danger remove-preview" onclick="removePreview()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Update Listing
                            </button>
                            <a href="{{ route('adoptions.index') }}" class="btn btn-outline-secondary btn-lg">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary: #2563eb;
    --danger: #ef4444;
    --dark: #1e293b;
    --gray-50: #f8fafc;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-600: #475569;
    --gray-700: #334155;
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
}

.create-adoption-page {
    background: var(--gray-50);
    min-height: 100vh;
    padding-bottom: 2rem;
}

.form-card {
    background: white;
    border-radius: 0.75rem;
    box-shadow: var(--shadow-md);
    padding: 2.5rem;
    animation: fadeInUp 0.5s ease-out;
}

.form-header {
    text-align: center;
    padding-bottom: 2rem;
    margin-bottom: 2rem;
    border-bottom: 2px solid var(--gray-200);
}

.form-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.form-subtitle {
    color: var(--gray-600);
    font-size: 1rem;
    margin: 0;
}

.form-section {
    padding: 1.5rem 0;
    border-bottom: 1px solid var(--gray-200);
}

.form-section:last-of-type {
    border-bottom: none;
}

.form-section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 1.25rem;
}

.form-label {
    font-weight: 600;
    color: var(--gray-700);
    font-size: 0.9375rem;
    margin-bottom: 0.5rem;
}

.required {
    color: var(--danger);
}

.form-control, .form-select {
    border: 1px solid var(--gray-300);
    border-radius: 0.5rem;
    padding: 0.625rem 0.875rem;
    font-size: 0.9375rem;
    transition: all 0.2s;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-text {
    font-size: 0.875rem;
    color: var(--gray-600);
    margin-top: 0.5rem;
}

.image-preview {
    position: relative;
    width: 200px;
    height: 200px;
    border-radius: 0.5rem;
    overflow: hidden;
    border: 2px solid var(--gray-300);
    margin-top: 1rem;
}

.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.remove-preview {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-actions {
    display: flex;
    gap: 1rem;
    padding-top: 2rem;
    justify-content: center;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .form-card {
        padding: 1.5rem;
    }
    
    .form-title {
        font-size: 1.5rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions .btn {
        width: 100%;
    }
}
</style>

<script>
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});

function removePreview() {
    document.getElementById('imageInput').value = '';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('previewImg').src = '';
}
</script>
@endsection
