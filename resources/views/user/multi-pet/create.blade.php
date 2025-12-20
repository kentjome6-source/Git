@extends('layouts.app')

@section('title', 'Add New Pet')

@section('content')
<div class="add-pet-page">
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
            <h1 class="page-title">Add New Pet</h1>
            <p class="page-subtitle">Register your pet to start tracking</p>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <form action="{{ route('pet.multipet.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-section">
                    <h3 class="section-title">Basic Information</h3>
                    
                    <!-- Pet Name -->
                    <div class="form-group">
                        <label for="name" class="form-label">Pet Name <span class="required">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Breed -->
                    <div class="form-group">
                        <label for="breed" class="form-label">Breed</label>
                        <input type="text" class="form-control @error('breed') is-invalid @enderror" 
                               id="breed" name="breed" value="{{ old('breed') }}" 
                               placeholder="e.g., Golden Retriever, Persian Cat">
                        @error('breed')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" 
                                  placeholder="Tell us about your pet...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Pet Image -->
                    <div class="form-group">
                        <label for="image" class="form-label">Pet Photo</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        <small class="form-text">Accepted formats: JPEG, PNG, JPG, GIF (Max: 2MB)</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('my.pets') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-submit">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Add Pet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    :root {
        --slate: #0f172a;
        --blue: #3b82f6;
        --purple: #8b5cf6;
        --gray: #64748b;
        --gray-light: #f1f5f9;
        --gray-lighter: #f8fafc;
    }

    .add-pet-page {
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
        color: var(--gray);
    }

    .form-card {
        background: white;
        border-radius: 16px;
        padding: 40px;
        border: 1px solid #e2e8f0;
        max-width: 800px;
        margin: 0 auto;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .form-section {
        margin-bottom: 40px;
    }

    .form-section:last-of-type {
        margin-bottom: 32px;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--slate);
        margin-bottom: 8px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--purple);
    }

    .section-description {
        font-size: 0.9rem;
        color: var(--gray);
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--slate);
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .required {
        color: #ef4444;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.2s;
        background: white;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--purple);
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .form-control.is-invalid {
        border-color: #ef4444;
    }

    .invalid-feedback {
        display: block;
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 6px;
    }

    .form-text {
        display: block;
        color: var(--gray);
        font-size: 0.85rem;
        margin-top: 6px;
    }

    textarea.form-control {
        resize: vertical;
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
        background: var(--purple);
        color: white;
    }

    .btn-submit:hover {
        background: #7c3aed;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    @media (max-width: 768px) {
        .form-card {
            padding: 24px;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn-cancel,
        .btn-submit {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 16px !important;
            padding-right: 16px !important;
        }

        .form-card {
            padding: 20px;
        }

        .page-title {
            font-size: 1.75rem;
        }
    }
</style>
@endsection
