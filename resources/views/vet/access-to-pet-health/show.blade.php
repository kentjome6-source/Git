@extends('layouts.vet')

@section('title', 'Pet Health Record Details')

@section('content')
<div class="container py-3 py-md-4">
    <h2 class="mb-3 fs-4 fs-md-2">🐾 Pet Health Record Details</h2>
    <p class="text-muted small">View detailed health information.</p>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-header bg-dark text-white py-2 py-md-3">
            <h5 class="mb-0 fs-6 fs-md-5">📋 Record Details for {{ $record->name }}</h5>
        </div>
        <div class="card-body p-3 p-md-4">
            {{-- Pet Information --}}
            <h4 class="text-primary mb-3 fs-5">🐕 Pet Information</h4>
            <div class="row mb-4">
                <div class="col-md-6"><p class="mb-2"><strong>Name:</strong> {{ $record->name }}</p></div>
                <div class="col-md-6"><p class="mb-2"><strong>Species:</strong> {{ $record->species ?? 'N/A' }}</p></div>
                <div class="col-md-6"><p class="mb-2"><strong>Breed:</strong> {{ $record->breed ?? 'N/A' }}</p></div>
                <div class="col-md-6"><p class="mb-2"><strong>Age:</strong> {{ $record->age ?? 'N/A' }}</p></div>
                <div class="col-md-6"><p class="mb-2"><strong>Weight:</strong> {{ $record->weight ?? 'N/A' }} kg</p></div>
            </div>

            <hr>

            {{-- Health Information --}}
            <h4 class="text-success mb-3 fs-5">🩺 Health Information</h4>
            <div class="row mb-4">
                <div class="col-md-6"><p class="mb-2"><strong>Condition:</strong> {{ $record->condition ?? 'N/A' }}</p></div>
                <div class="col-md-6"><p class="mb-2"><strong>Medical Notes:</strong> {{ $record->medical_notes ?? 'N/A' }}</p></div>
                <div class="col-md-6"><p class="mb-2"><strong>Diagnosed At:</strong> {{ $record->diagnosed_at ?? 'N/A' }}</p></div>
            </div>

            <hr>

            {{-- Vaccination --}}
            <h4 class="text-warning mb-3 fs-5">💉 Vaccination</h4>
            <div class="row mb-4">
                <div class="col-md-6"><p class="mb-2"><strong>Vaccine:</strong> {{ $record->vaccine_name ?? 'N/A' }}</p></div>
                <div class="col-md-6"><p class="mb-2"><strong>Date Given:</strong> {{ $record->date_given ?? 'N/A' }}</p></div>
                <div class="col-md-6"><p class="mb-2"><strong>Next Due:</strong> {{ $record->next_due ?? 'N/A' }}</p></div>
                <div class="col-md-6"><p class="mb-2"><strong>Status:</strong> {{ $record->vaccine_status ?? 'N/A' }}</p></div>
            </div>

            <hr>

            {{-- Treatments --}}
            <h4 class="text-dark mb-3 fs-5">💊 Treatments / Prescriptions</h4>
            @if($record->treatments->isEmpty())
                <div class="alert alert-light shadow-sm py-2 py-md-3">No treatments recorded yet.</div>
            @else
                {{-- Desktop Table View --}}
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-striped table-bordered align-middle shadow-sm mb-0">
                        <thead class="table-info">
                            <tr>
                                <th class="small">Date</th>
                                <th class="small">Title</th>
                                <th class="small">Medication</th>
                                <th class="small">Dosage</th>
                                <th class="small">Frequency</th>
                                <th class="small">Notes</th>
                                <th class="small">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($record->treatments as $treatment)
                                <tr>
                                    <td class="small">{{ $treatment->treatment_date }}</td>
                                    <td class="small">{{ $treatment->title }}</td>
                                    <td class="small">{{ $treatment->medication ?? '—' }}</td>
                                    <td class="small">{{ $treatment->dosage ?? '—' }}</td>
                                    <td class="small">{{ $treatment->frequency ?? '—' }}</td>
                                    <td class="small">{{ $treatment->notes ?? '—' }}</td>
                                    <td class="small">
                                        @if($treatment->vet_id === Auth::id())
                                            <a href="{{ route('vet.records.treatments.edit', $treatment->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        @else
                                            <span class="text-muted">Not editable</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{-- Mobile Card View --}}
                <div class="d-md-none">
                    @foreach($record->treatments as $treatment)
                        <div class="card mb-3 shadow-sm border">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 fs-6">{{ $treatment->title }}</h5>
                                    <span class="badge bg-primary">{{ $treatment->treatment_date }}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <small class="text-muted">Medication:</small>
                                        <div class="fw-bold">{{ $treatment->medication ?? '—' }}</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Dosage:</small>
                                        <div>{{ $treatment->dosage ?? '—' }}</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Frequency:</small>
                                        <div>{{ $treatment->frequency ?? '—' }}</div>
                                    </div>
                                    <div class="col-12">
                                        <small class="text-muted">Notes:</small>
                                        <div>{{ $treatment->notes ?? '—' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white">
                                @if($treatment->vet_id === Auth::id())
                                    <a href="{{ route('vet.records.treatments.edit', $treatment->id) }}" class="btn btn-sm btn-outline-primary w-100">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                @else
                                    <span class="text-muted">Not editable</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('vet.records') }}" class="btn btn-secondary px-3 px-md-4 py-2 fw-semibold">
            <i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline">Back to Records</span>
            <span class="d-inline d-sm-none">Back</span>
        </a>
    </div>
</div>
@endsection

@section('styles')
<style>
.card {
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-2px);
}
.card-header h5 {
    font-size: 1.25rem;
}
.card-body h4 {
    font-weight: 600;
    border-left: 5px solid #ccc;
    padding-left: 10px;
    font-size: 1.25rem;
}
.table thead th {
    font-weight: 600;
    font-size: 0.9rem;
}
.table tbody td {
    font-size: 0.85rem;
}

@media (max-width: 768px) {
    .btn {
        width: 100%;
        margin-bottom: 0.25rem;
        padding: 0.5rem;
        font-size: 0.9rem;
    }
    
    .card-header h5 {
        font-size: 1.1rem;
    }
    
    .card-body h4 {
        font-size: 1.1rem;
    }
    
    .table thead th {
        font-size: 0.8rem;
    }
    
    .table tbody td {
        font-size: 0.75rem;
        padding: 0.25rem;
    }
    
    .table-responsive {
        font-size: 0.85rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .mb-4 {
        margin-bottom: 1rem !important;
    }
}

@media (max-width: 576px) {
    .py-3 {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }
    
    .p-3 {
        padding: 1rem !important;
    }
    
    .fs-5 {
        font-size: 1rem !important;
    }
    
    .fs-4 {
        font-size: 1.1rem !important;
    }
    
    .btn {
        padding: 0.5rem;
        font-size: 0.85rem;
    }
    
    .table thead th {
        font-size: 0.7rem;
    }
    
    .table tbody td {
        font-size: 0.7rem;
    }
}
</style>
@endsection