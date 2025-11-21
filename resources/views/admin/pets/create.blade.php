@extends('layouts.admin')

@section('title', 'Add New Pet')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-reddish-orange text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Add New Pet
                    </h4>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('admin.pets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Animal Type</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Enter pet description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Pet Image</label>
                            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload an image of the pet (JPG, PNG, GIF - max 2MB)</div>
                        </div>

                        <div class="d-flex justify-content-between flex-column flex-md-row gap-2">
                            <a href="{{ route('admin.pets.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Pets
                            </a>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-plus-circle me-1"></i> Add Pet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Custom reddish-orange background */
    .bg-reddish-orange {
        background: #e74c3c !important;
    }
    
    /* Mobile Responsive Improvements */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }
        
        .card-header h4 {
            font-size: 1.25rem;
            text-align: center;
        }
        
        .form-label {
            font-size: 0.95rem;
        }
        
        .form-control {
            font-size: 1rem;
            padding: 0.75rem;
        }
        
        .btn {
            padding: 0.75rem 1.25rem;
            font-size: 0.95rem;
        }
        
        .d-flex {
            gap: 1rem !important;
        }
    }

    @media (max-width: 576px) {
        .card-body {
            padding: 1rem !important;
        }
        
        .form-control {
            font-size: 1rem;
            padding: 0.625rem;
        }
        
        .btn {
            padding: 0.625rem 1rem;
            font-size: 0.9rem;
        }
        
        .form-text {
            font-size: 0.8rem;
        }
    }
</style>
@endsection