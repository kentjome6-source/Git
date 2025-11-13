@extends('layouts.app')

@section('title', 'Pet Health Records')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden w-100">
                {{-- Header --}}
                <div class="card-header bg-gradient bg-primary text-white d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 py-3">
                    <h4 class="mb-0 fs-4 text-center text-md-start">
                        🐾 My Pet Health Records
                    </h4>
                    <a href="{{ route('pet.health.create') }}" class="btn btn-light px-4 py-2 fw-semibold">
                        <i class="fas fa-plus-circle me-1"></i> Add Record
                    </a>
                </div>

                <div class="card-body p-3 p-md-4">
                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Empty Records --}}
                    @if($records->isEmpty())
                        <div class="alert alert-info text-center py-5 rounded-3 shadow-sm">
                            <h5 class="mb-0 fs-5"><i class="fas fa-dog me-2"></i>No pet health records found 🐶🐱</h5>
                            <small class="text-muted">Click <strong>Add Record</strong> to get started.</small>
                        </div>
                    @else
                        {{-- Full Details Display --}}
                        @foreach($records as $record)
                            <div class="card mb-4 border-primary shadow-sm">
                                <div class="card-header bg-light d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 py-3">
                                    <h5 class="mb-0 fs-5">{{ $record->name }}</h5>
                                    <div class="btn-group btn-group-sm w-100 w-md-auto" role="group">
                                        <a href="{{ route('pet.health.show', $record) }}" 
                                           class="btn btn-outline-info btn-sm py-1 px-2 w-100 w-md-auto mb-1 mb-md-0">
                                            <i class="fas fa-eye me-1"></i> <span class="d-none d-sm-inline">Details</span>
                                        </a>
                                        <a href="{{ route('pet.health.edit', $record) }}" 
                                           class="btn btn-outline-warning btn-sm py-1 px-2 w-100 w-md-auto mb-1 mb-md-0">
                                            <i class="fas fa-edit me-1"></i> <span class="d-none d-sm-inline">Edit</span>
                                        </a>
                                        <form action="{{ route('pet.health.destroy', $record) }}" 
                                              method="POST" class="d-inline w-100 w-md-auto">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm py-1 px-2 w-100" 
                                                    onclick="return confirm('Are you sure you want to delete this record?')">
                                                <i class="fas fa-trash-alt me-1"></i> <span class="d-none d-sm-inline">Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body py-3">
                                    <div class="row g-3">
                                        {{-- Pet Information --}}
                                        <div class="col-12 col-md-6">
                                            <h6 class="text-primary mb-3">🐕 Pet Information</h6>
                                            <div class="row g-2">
                                                <div class="col-12 col-sm-6"><strong>Name:</strong> {{ $record->name }}</div>
                                                <div class="col-12 col-sm-6"><strong>Species:</strong> {{ $record->species ?? 'N/A' }}</div>
                                                <div class="col-12 col-sm-6"><strong>Breed:</strong> {{ $record->breed ?? 'N/A' }}</div>
                                                <div class="col-12 col-sm-6"><strong>Age:</strong> {{ $record->age ?? 'N/A' }}</div>
                                                <div class="col-12 col-sm-6"><strong>Weight:</strong> {{ $record->weight ?? 'N/A' }} kg</div>
                                            </div>
                                        </div>
                                        
                                        {{-- Health Information --}}
                                        <div class="col-12 col-md-6">
                                            <h6 class="text-success mb-3">🩺 Health Information</h6>
                                            <div class="row g-2">
                                                <div class="col-12 col-sm-6"><strong>Condition:</strong> {{ $record->condition ?? 'N/A' }}</div>
                                                <div class="col-12 col-sm-6"><strong>Medical Notes:</strong> {{ $record->medical_notes ?? 'N/A' }}</div>
                                                <div class="col-12 col-sm-6"><strong>Diagnosed At:</strong> {{ $record->diagnosed_at ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                        
                                        {{-- Vaccination --}}
                                        <div class="col-12">
                                            <h6 class="text-warning mb-3">💉 Vaccination</h6>
                                            <div class="row g-2">
                                                <div class="col-12 col-sm-6 col-md-3"><strong>Vaccine:</strong> {{ $record->vaccine_name ?? 'N/A' }}</div>
                                                <div class="col-12 col-sm-6 col-md-3"><strong>Date Given:</strong> {{ $record->date_given ?? 'N/A' }}</div>
                                                <div class="col-12 col-sm-6 col-md-3"><strong>Next Due:</strong> {{ $record->next_due ?? 'N/A' }}</div>
                                                <div class="col-12 col-sm-6 col-md-3"><strong>Status:</strong> {{ $record->vaccine_status ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .card-header {
        padding: 1rem;
    }
    
    .btn-group {
        width: 100%;
        margin-top: 0.5rem;
    }
    
    .btn-group .btn {
        margin-bottom: 0.25rem;
        width: 100%;
    }
    
    .fs-4 {
        font-size: 1.25rem !important;
    }
    
    .fs-5 {
        font-size: 1.1rem !important;
    }
    
    h6 {
        font-size: 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
}

@media (max-width: 576px) {
    .py-4 {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
    }
    
    .p-3 {
        padding: 1rem !important;
    }
    
    .g-3 {
        --bs-gutter-x: 1rem;
    }
    
    .g-2 {
        --bs-gutter-x: 0.5rem;
    }
}

/* Desktop button size adjustments */
@media (min-width: 769px) {
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
}
</style>
@endsection