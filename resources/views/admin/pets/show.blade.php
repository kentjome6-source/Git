@extends('layouts.admin')

@section('title', 'View Pet')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-reddish-orange text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-eye me-2"></i>View Pet Details
                    </h4>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="pet-image-container">
                                @if($pet->image_path)
                                    <img src="{{ asset('storage/' . $pet->image_path) }}" alt="{{ $pet->name }}" class="pet-image">
                                @else
                                    <div class="no-image-placeholder">
                                        <div class="text-center">
                                            <svg class="no-image-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                <polyline points="21 15 16 10 5 21"></polyline>
                                            </svg>
                                            <p class="mt-3 mb-0 text-muted">No Image Available</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-label">Animal Type</div>
                                <p class="detail-value">{{ $pet->name }}</p>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">Breed</div>
                                <p class="detail-value">{{ $pet->breed ?? '<span class="detail-value-empty">N/A</span>' }}</p>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">Owner</div>
                                <p class="detail-value">{{ $pet->user->name ?? '<span class="detail-value-empty">Unknown</span>' }}</p>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">Description</div>
                                <p class="detail-value">{{ $pet->description ?? '<span class="detail-value-empty">N/A</span>' }}</p>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">Appropriate Food</div>
                                <p class="detail-value">{{ $pet->appropriate_food ?? '<span class="detail-value-empty">N/A</span>' }}</p>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">Other Care Details</div>
                                <p class="detail-value">{{ $pet->other_care_details ?? '<span class="detail-value-empty">N/A</span>' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between flex-column flex-md-row gap-2 mt-4">
                        <a href="{{ route('admin.pets.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Pets
                        </a>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.pets.edit', $pet) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i> Edit Pet
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* CSS Variables for Theming */
    :root {
        --primary-color: #2563eb;
        --primary-hover: #1d4ed8;
        --primary-light: #3b82f6;
        --danger-color: #dc2626;
        --danger-hover: #b91c1c;
        --success-color: #16a34a;
        --success-bg: #f0fdf4;
        --success-border: #bbf7d0;
        --text-primary: #111827;
        --text-secondary: #6b7280;
        --text-tertiary: #9ca3af;
        --border-color: #e5e7eb;
        --bg-surface: #ffffff;
        --bg-base: #f9fafb;
        --border-radius: 12px;
        --border-radius-sm: 8px;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        --transition: all 0.2s ease-in-out;
    }
    
    /* Card Styles */
    .card {
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        background: var(--bg-surface);
        overflow: hidden;
    }
    
    .card-header {
        border-radius: 0 !important;
        border: none;
        padding: 1.5rem;
    }
    
    .bg-reddish-orange {
        background: linear-gradient(135deg, #e74c3c, #c0392b) !important;
        color: white;
    }
    
    .card-body {
        padding: 2rem !important;
    }
    
    /* Image Styles */
    .pet-image-container {
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow);
        background-color: var(--bg-base);
    }
    
    .pet-image {
        width: 100%;
        height: 300px;
        object-fit: cover;
        display: block;
    }
    
    .no-image-placeholder {
        width: 100%;
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border-radius: var(--border-radius);
    }
    
    .no-image-icon {
        width: 80px;
        height: 80px;
        color: var(--text-tertiary);
    }
    
    /* Detail Item Styles */
    .detail-item {
        margin-bottom: 1.25rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid var(--border-color);
    }
    
    .detail-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .detail-label {
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.025em;
        margin-bottom: 0.375rem;
    }
    
    .detail-value {
        font-size: 1rem;
        color: var(--text-primary);
        line-height: 1.5;
        margin: 0;
    }
    
    .detail-value-empty {
        color: var(--text-tertiary);
        font-style: italic;
    }
    
    /* Button Styles */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.625rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 500;
        line-height: 1.25rem;
        border-radius: var(--border-radius-sm);
        transition: var(--transition);
        border: 1px solid transparent;
        cursor: pointer;
        white-space: nowrap;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        color: #ffffff;
        border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }
    
    .btn-secondary {
        background-color: var(--text-secondary);
        color: #ffffff;
        border-color: var(--text-secondary);
    }
    
    .btn-secondary:hover {
        background-color: #585d65;
        border-color: #585d65;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }
    
    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.8125rem;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }
        
        .card-header {
            padding: 1.25rem !important;
        }
        
        .card-header h4 {
            font-size: 1.25rem;
            text-align: center;
        }
        
        .pet-image {
            height: 200px;
        }
        
        .no-image-placeholder {
            height: 200px;
        }
        
        .detail-label {
            font-size: 0.75rem;
        }
        
        .detail-value {
            font-size: 0.9375rem;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
        }
        
        .d-flex {
            gap: 1rem !important;
        }
    }
    
    @media (max-width: 576px) {
        .d-flex {
            flex-direction: column !important;
        }
        
        .btn {
            width: 100%;
        }
    }
</style>
@endsection