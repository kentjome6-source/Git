@extends('layouts.app')

@section('title', 'Multi-Pet Dashboard')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12">
            {{-- Hero Header Section --}}
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary mb-3">
                    <i class="fas fa-paw me-2"></i>Pet Dashboard
                </h1>
                <p class="lead text-muted">Discover your perfect companion</p>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-4 border-0 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fs-4 me-3"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Dashboard Content --}}
            @if($pets->isEmpty())
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-dog fa-5x text-muted opacity-50"></i>
                    </div>
                    <h3 class="text-muted mb-3">No Pets Available Yet</h3>
                    <p class="text-muted">Check back soon for adorable companions looking for homes!</p>
                </div>
            @else
                {{-- Pet Cards Grid --}}
                <div class="row g-4">
                    @foreach($pets as $pet)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm hover-lift transition-all rounded-4 overflow-hidden">
                                {{-- Pet Image --}}
                                <div class="position-relative">
                                    @if($pet->image_path)
                                        <img src="{{ asset('storage/' . $pet->image_path) }}" 
                                             class="card-img-top" 
                                             alt="{{ $pet->name }}" 
                                             style="height: 280px; object-fit: cover;">
                                    @else
                                        <div class="bg-gradient d-flex align-items-center justify-content-center position-relative" 
                                             style="height: 280px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <i class="fas fa-paw fa-4x text-white opacity-75"></i>
                                        </div>
                                    @endif
                                    
                                    {{-- Overlay Badge --}}
                                </div>

                                {{-- Card Body --}}
                                <div class="card-body d-flex flex-column p-4">
                                    {{-- Pet Name --}}
                                    <h5 class="card-title fw-bold mb-3 text-dark">
                                        <i class="fas fa-paw text-primary me-2" style="font-size: 0.9rem;"></i>
                                        {{ $pet->name }}
                                    </h5>

                                    {{-- Pet Description --}}
                                    <p class="card-text text-muted mb-3 flex-grow-1" style="line-height: 1.6;">
                                        {{ Str::limit($pet->description ?? 'No description provided.', 100) }}
                                    </p>

                                    {{-- Divider --}}
                                    <hr class="my-3">

                                    {{-- Footer Info --}}
                                    <div class="d-flex justify-content-end align-items-center">
                                        <a href="{{ route('pet.multipet.show', $pet) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Custom Styles --}}
<style>
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
    }
    
    .transition-all {
        transition: all 0.3s ease;
    }
    
    .bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .card-img-top {
        transition: transform 0.3s ease;
    }
    
    .card:hover .card-img-top {
        transform: scale(1.05);
    }
    
    .card {
        overflow: hidden;
    }
    
    /* Match sidebar color theme */
    .display-4 {
        color: #5b4b9b !important;
    }
    
    .btn-primary {
        background: #5b4b9b !important;
        border-color: #5b4b9b !important;
    }
    
    .btn-primary:hover {
        background: #4a3d82 !important;
        border-color: #4a3d82 !important;
    }
    
    .card-title {
        color: #5b4b9b !important;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .display-4 {
            font-size: 2rem;
        }
        
        .lead {
            font-size: 1rem;
        }
        
        .card-img-top {
            height: 280px !important; /* Increased from 250px */
        }
    }
    
    @media (max-width: 576px) {
        .py-5 {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }
        
        .mb-5 {
            margin-bottom: 1rem !important;
        }
        
        .g-4 {
            --bs-gutter-x: 1rem;
        }
        
        .card-img-top {
            height: 250px !important; /* Increased from 220px */
        }
        
        .card-body {
            padding: 1rem !important;
        }
        
        .card-title {
            font-size: 1.1rem;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
    }
    
    @media (max-width: 400px) {
        .card {
            margin-bottom: 1rem;
        }
        
        .card-img-top {
            height: 230px !important; /* Increased from 200px */
        }
        
        .card-title {
            font-size: 1rem;
        }
        
        .card-text {
            font-size: 0.9rem;
        }
    }
</style>
@endsection