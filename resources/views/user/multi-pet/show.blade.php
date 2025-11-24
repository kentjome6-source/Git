@extends('layouts.app')

@section('title', 'Pet Details - ' . $pet->name)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <!-- Pet Image -->
                    <div class="mb-4 text-center">
                        @if($pet->image_path)
                            <img src="{{ asset('storage/' . $pet->image_path) }}" 
                                 class="img-fluid rounded-3 shadow" 
                                 alt="{{ $pet->name }}" 
                                 style="max-height: 300px; object-fit: cover;">
                        @else
                            <div class="bg-gradient d-flex align-items-center justify-content-center rounded-3 shadow" 
                                 style="height: 250px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="fas fa-paw fa-4x text-white opacity-75"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Pet Information in Bond Paper Style -->
                    <div class="bond-paper-content">
                        <!-- Animal Type -->
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-4 fw-bold text-primary">Animal Type:</div>
                                <div class="col-8">{{ ucfirst($pet->name ?? 'Not specified') }}</div>
                            </div>
                        </div>

                        <!-- Breed -->
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-4 fw-bold text-primary">Breed:</div>
                                <div class="col-8">{{ $pet->breed ?? 'Not specified' }}</div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-4 fw-bold text-primary">Description:</div>
                                <div class="col-8">{{ $pet->description ?? 'No description provided.' }}</div>
                            </div>
                        </div>

                        <!-- Appropriate Food -->
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-4 fw-bold text-primary">Appropriate Food:</div>
                                <div class="col-8">{{ $pet->appropriate_food ?? 'Not specified' }}</div>
                            </div>
                        </div>

                        <!-- Essential Care Details -->
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-4 fw-bold text-primary">Essential Care Details:</div>
                                <div class="col-8">{{ $pet->other_care_details ?? 'Not specified' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="text-center mt-4">
                        <a href="{{ route('pet.multipet.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Custom Styles --}}
<style>
    .bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .text-primary {
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
    
    .bond-paper-content {
        background-color: #fff;
        border: 1px solid #e9ecef;
        border-radius: 0.5rem;
        padding: 1.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .bond-paper-content .row {
        border-bottom: 1px solid #f0f0f0;
        padding: 0.75rem 0;
        margin: 0;
    }
    
    .bond-paper-content .row:last-child {
        border-bottom: none;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container {
            padding: 0.5rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .bond-paper-content {
            padding: 1rem;
        }
        
        .bond-paper-content .row {
            flex-direction: column;
            border-bottom: 1px solid #f0f0f0;
            padding: 0.5rem 0;
        }
        
        .bond-paper-content .col-4, 
        .bond-paper-content .col-8 {
            padding: 0;
            width: 100%;
        }
        
        .bond-paper-content .col-4 {
            font-weight: bold;
            margin-bottom: 0.25rem;
        }
    }
</style>
@endsection