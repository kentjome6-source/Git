@extends('layouts.admin')

@section('title', 'Pet Management')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-paw me-2"></i>Pet Management
                    </h4>
                    <a href="{{ route('admin.pets.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus-circle me-1"></i> Add New Pet
                    </a>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($pets->isEmpty())
                        <div class="alert alert-info text-center py-5 rounded-3 shadow-sm">
                            <h5 class="mb-0"><i class="fas fa-dog me-2"></i>No pets found</h5>
                            <small class="text-muted">Click "Add New Pet" to create your first pet listing.</small>
                        </div>
                    @else
                        <!-- Card-based layout for desktop -->
                        <div class="pets-grid">
                            @foreach($pets as $pet)
                                <div class="pet-card">
                                    <div class="pet-image">
                                        @if($pet->image_path)
                                            <img src="{{ asset('storage/' . $pet->image_path) }}" alt="{{ $pet->name }}">
                                        @else
                                            <div class="no-image">
                                                <i class="fas fa-paw"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="pet-content">
                                        <h3 class="pet-title">{{ $pet->name }}</h3>
                                        <div class="pet-meta">
                                            <div class="meta-item">
                                                <i class="fas fa-user"></i>
                                                {{ $pet->user->name }}
                                            </div>
                                            <div class="meta-item">
                                                <i class="fas fa-info-circle"></i>
                                                {{ Str::limit($pet->description, 50) }}
                                            </div>
                                        </div>
                                        <div class="pet-actions">
                                            <a href="{{ route('admin.pets.edit', $pet) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.pets.destroy', $pet) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this pet?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Swipeable table for mobile - consistent with other admin views -->
                        <div class="swipeable-table-container">
                            @foreach($pets as $pet)
                                <div class="swipeable-table-row">
                                    <div class="swipeable-table-item">
                                        <span class="swipeable-table-label">Name</span>
                                        <span class="pet-name-value">{{ $pet->name }}</span>
                                    </div>
                                    <div class="swipeable-table-item">
                                        <span class="swipeable-table-label">Owner</span>
                                        <span class="swipeable-table-value">{{ $pet->user->name }}</span>
                                    </div>
                                    <div class="swipeable-table-item">
                                        <span class="swipeable-table-label">Description</span>
                                        <span class="swipeable-table-value">{{ Str::limit($pet->description, 50) }}</span>
                                    </div>
                                    <div class="swipeable-table-item">
                                        <span class="swipeable-table-label">Image</span>
                                        <span class="swipeable-table-value">
                                            @if($pet->image_path)
                                                <img src="{{ asset('storage/' . $pet->image_path) }}" alt="{{ $pet->name }}" class="swipeable-pet-image">
                                            @else
                                                <i class="fas fa-paw"></i>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="swipeable-action-buttons">
                                        <a href="{{ route('admin.pets.edit', $pet) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.pets.destroy', $pet) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this pet?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Card-based layout for desktop */
    .pets-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }
    
    .pet-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #eee;
    }
    
    .pet-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    
    .pet-image {
        height: 200px;
        overflow: hidden;
        position: relative;
        background: #f8f9fa;
    }
    
    .pet-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .pet-image .no-image {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        background: linear-gradient(135deg, #e74c3c 0%, #ff6b6b 100%);
        color: white;
        font-size: 3rem;
    }
    
    .pet-content {
        padding: 20px;
    }
    
    .pet-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 12px;
    }
    
    .pet-meta {
        margin-bottom: 20px;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 8px;
    }
    
    .meta-item:last-child {
        margin-bottom: 0;
    }
    
    .pet-actions {
        display: flex;
        gap: 10px;
    }
    
    .pet-actions .btn {
        flex: 1;
        justify-content: center;
    }
    
    /* Swipeable table for mobile - consistent with other admin views */
    .swipeable-table-container {
        display: none;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding: 10px 0;
    }
    
    .swipeable-table-row {
        display: flex;
        flex-direction: column;
        border: 1px solid #eee;
        border-radius: 10px;
        margin-bottom: 15px;
        background: #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .swipeable-table-item {
        padding: 12px 15px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
    }
    
    .swipeable-table-item:last-child {
        border-bottom: none;
    }
    
    .swipeable-table-label {
        font-weight: 600;
        color: #e74c3c;
        min-width: 100px;
    }
    
    .pet-name-value {
        font-weight: 600;
        color: #333;
        text-align: right;
        flex: 1;
    }
    
    .swipeable-table-value {
        text-align: right;
        flex: 1;
    }
    
    .swipeable-pet-image {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 6px;
    }
    
    .swipeable-action-buttons {
        display: flex;
        gap: 8px;
        padding: 10px 15px;
    }
    
    .swipeable-action-buttons .btn {
        flex: 1;
        justify-content: center;
        padding: 8px;
        font-size: 0.8rem;
    }
    
    /* Responsive Improvements for All Devices */
    .table-responsive {
        border: none;
    }
    
    /* Desktop Improvements */
    @media (min-width: 769px) {
        .card-header {
            padding: 1rem 1.5rem;
        }
        
        .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .pets-grid {
            display: grid !important;
        }
        
        .swipeable-table-container {
            display: none !important;
        }
    }
    
    /* Tablet Improvements */
    @media (max-width: 991px) {
        .btn {
            padding: 0.3rem 0.6rem;
            font-size: 0.8rem;
        }
        
        .pets-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }
    }

    /* Mobile Responsive Improvements */
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem !important;
        }
        
        .card-header {
            padding: 0.75rem 1rem;
        }
        
        .card-title {
            font-size: 1.25rem;
        }
        
        .btn {
            padding: 0.375rem 0.5rem !important;
            font-size: 0.8rem;
            white-space: nowrap;
        }
        
        /* Hide card grid on mobile */
        .pets-grid {
            display: none;
        }
        
        /* Show swipeable table on mobile */
        .swipeable-table-container {
            display: block;
        }
        
        .swipeable-table-label {
            font-size: 0.9rem;
        }
        
        .swipeable-table-value {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 576px) {
        .card-body {
            padding: 0.75rem !important;
        }
        
        .card-header {
            padding: 0.6rem 0.8rem;
        }
        
        .card-title {
            font-size: 1.1rem;
        }
        
        .btn {
            padding: 0.25rem 0.4rem !important;
            font-size: 0.75rem;
        }
        
        .swipeable-table-item {
            padding: 10px 12px;
        }
        
        .swipeable-table-label {
            font-size: 0.85rem;
        }
        
        .swipeable-table-value {
            font-size: 0.85rem;
        }
        
        .swipeable-action-buttons {
            flex-direction: column;
        }
        
        .swipeable-action-buttons .btn {
            width: 100%;
        }
    }
    
    /* Ensure content fits within viewport */
    .container-fluid {
        max-width: 100%;
        overflow-x: hidden;
    }
    
    /* Prevent horizontal scrolling */
    body {
        overflow-x: hidden;
    }
</style>
@endsection