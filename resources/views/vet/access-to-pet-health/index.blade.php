@extends('layouts.vet')

@section('title', 'Pet Health Records')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-4">
        <h2 class="mb-0 fs-3 fs-md-2">🐾 Access to Pet Health & Records</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white py-2 py-md-3">
            <h5 class="mb-0 fs-5 fs-md-4">📋 Pet Health Records</h5>
        </div>
        <div class="card-body p-3 p-md-4">
            @if($records->isEmpty())
                <div class="alert alert-info text-center py-3 py-md-4">
                    <i class="fas fa-info-circle"></i> No pet health records found.
                </div>
            @else
                {{-- Full Details Display --}}
                @foreach($records as $record)
                    <div class="card mb-4 border-primary shadow-sm">
                        <div class="card-header bg-light d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                            <div>
                                <h5 class="mb-1 fs-5">{{ $record->name }}</h5>
                                <small class="text-muted">Owner: {{ $record->user->name ?? 'Unknown Owner' }}</small>
                            </div>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('vet.records.show', $record->id) }}" class="btn btn-primary btn-sm py-1 px-2" title="View Details">
                                    <i class="fas fa-eye"></i> <span class="d-none d-sm-inline">Details</span>
                                </a>
                                <a href="{{ route('vet.records.treatment.create', $record->id) }}" class="btn btn-success btn-sm py-1 px-2" title="Add Treatment">
                                    <i class="fas fa-plus"></i> <span class="d-none d-sm-inline">Add Treatment</span>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                {{-- Pet Information --}}
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-primary mb-3">🐕 Pet Information</h6>
                                    <div class="row">
                                        <div class="col-sm-6 mb-2"><strong>Name:</strong> {{ $record->name }}</div>
                                        <div class="col-sm-6 mb-2"><strong>Species:</strong> {{ $record->species ?? 'N/A' }}</div>
                                        <div class="col-sm-6 mb-2"><strong>Breed:</strong> {{ $record->breed ?? 'N/A' }}</div>
                                        <div class="col-sm-6 mb-2"><strong>Age:</strong> {{ $record->age ?? 'N/A' }}</div>
                                        <div class="col-sm-6 mb-2"><strong>Weight:</strong> {{ $record->weight ?? 'N/A' }} kg</div>
                                    </div>
                                </div>
                                
                                {{-- Health Information --}}
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-success mb-3">🩺 Health Information</h6>
                                    <div class="row">
                                        <div class="col-sm-6 mb-2"><strong>Condition:</strong> {{ $record->condition ?? 'N/A' }}</div>
                                        <div class="col-sm-6 mb-2"><strong>Medical Notes:</strong> {{ $record->medical_notes ?? 'N/A' }}</div>
                                        <div class="col-sm-6 mb-2"><strong>Diagnosed At:</strong> {{ $record->diagnosed_at ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                
                                {{-- Vaccination --}}
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-warning mb-3">💉 Vaccination</h6>
                                    <div class="row">
                                        <div class="col-sm-6 mb-2"><strong>Vaccine:</strong> {{ $record->vaccine_name ?? 'N/A' }}</div>
                                        <div class="col-sm-6 mb-2"><strong>Date Given:</strong> {{ $record->date_given ?? 'N/A' }}</div>
                                        <div class="col-sm-6 mb-2"><strong>Next Due:</strong> {{ $record->next_due ?? 'N/A' }}</div>
                                        <div class="col-sm-6 mb-2"><strong>Status:</strong> {{ $record->vaccine_status ?? 'N/A' }}</div>
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
@endsection

@section('styles')
<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
.card-header {
    border-radius: 0.375rem 0.375rem 0 0 !important;
}
.btn-group .btn {
    margin-right: 0.25rem;
    margin-bottom: 0.25rem;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
}
.btn-group .btn:last-child {
    margin-right: 0;
}

@media (max-width: 768px) {
    .btn-group .btn {
        width: 100%;
        margin-bottom: 0.25rem;
        padding: 0.5rem;
        font-size: 0.9rem;
    }
    
    .card-header {
        padding: 1rem;
    }
    
    .fs-5 {
        font-size: 1.1rem !important;
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
    
    .btn-group {
        width: 100%;
        margin-top: 0.5rem;
    }
}
</style>
@endsection