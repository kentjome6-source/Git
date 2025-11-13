@extends('layouts.vet')

@section('title', 'Pet Health Record Details')

@section('content')
<div class="container">
    <h2 class="mb-4">🐾 Pet Health Record Details</h2>

    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">📋 Record Details for {{ $record->name }}</h5>
        </div>
        <div class="card-body">
            {{-- Pet Information --}}
            <h4 class="text-primary mb-3">🐕 Pet Information</h4>
            <div class="row mb-4">
                <div class="col-md-6"><p><strong>Name:</strong> {{ $record->name }}</p></div>
                <div class="col-md-6"><p><strong>Species:</strong> {{ $record->species ?? 'N/A' }}</p></div>
                <div class="col-md-6"><p><strong>Breed:</strong> {{ $record->breed ?? 'N/A' }}</p></div>
                <div class="col-md-6"><p><strong>Age:</strong> {{ $record->age ?? 'N/A' }}</p></div>
                <div class="col-md-6"><p><strong>Weight:</strong> {{ $record->weight ?? 'N/A' }} kg</p></div>
            </div>

            <hr>

            {{-- Health Information --}}
            <h4 class="text-success mb-3">🩺 Health Information</h4>
            <div class="row mb-4">
                <div class="col-md-6"><p><strong>Condition:</strong> {{ $record->condition ?? 'N/A' }}</p></div>
                <div class="col-md-6"><p><strong>Medical Notes:</strong> {{ $record->medical_notes ?? 'N/A' }}</p></div>
                <div class="col-md-6"><p><strong>Diagnosed At:</strong> {{ $record->diagnosed_at ?? 'N/A' }}</p></div>
            </div>

            <hr>

            {{-- Vaccination --}}
            <h4 class="text-warning mb-3">💉 Vaccination</h4>
            <div class="row mb-4">
                <div class="col-md-6"><p><strong>Vaccine:</strong> {{ $record->vaccine_name ?? 'N/A' }}</p></div>
                <div class="col-md-6"><p><strong>Date Given:</strong> {{ $record->date_given ?? 'N/A' }}</p></div>
                <div class="col-md-6"><p><strong>Next Due:</strong> {{ $record->next_due ?? 'N/A' }}</p></div>
                <div class="col-md-6"><p><strong>Status:</strong> {{ $record->vaccine_status ?? 'N/A' }}</p></div>
            </div>

            <hr>

            {{-- Treatments --}}
            <h4 class="text-dark mb-3">💊 Treatments / Prescriptions</h4>
            @if($record->treatments->isEmpty())
                <div class="alert alert-light shadow-sm">No treatments recorded yet.</div>
            @else
                {{-- Desktop Table View --}}
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-striped table-bordered align-middle shadow-sm">
                        <thead class="table-info">
                            <tr>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Medication</th>
                                <th>Dosage</th>
                                <th>Frequency</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($record->treatments as $treatment)
                                <tr>
                                    <td>{{ $treatment->treatment_date }}</td>
                                    <td>{{ $treatment->title }}</td>
                                    <td>{{ $treatment->medication ?? '—' }}</td>
                                    <td>{{ $treatment->dosage ?? '—' }}</td>
                                    <td>{{ $treatment->frequency ?? '—' }}</td>
                                    <td>{{ $treatment->notes ?? '—' }}</td>
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
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('vet.records') }}" class="btn btn-secondary px-4 fw-semibold">
            <i class="fas fa-arrow-left"></i> Back to Records
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
        margin-bottom: 0.5rem;
        padding: 0.75rem;
        font-size: 1rem;
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
    
    .card-body {
        padding: 1rem;
    }
    
    .mb-4 {
        margin-bottom: 1rem !important;
    }
    
    .mb-3 {
        margin-bottom: 0.75rem !important;
    }
}

@media (max-width: 576px) {
    .container {
        padding: 0 0.5rem;
    }
    
    .card-body {
        padding: 0.75rem;
    }
    
    .btn {
        padding: 0.5rem;
        font-size: 0.9rem;
    }
    
    .table thead th {
        font-size: 0.7rem;
    }
    
    .table tbody td {
        font-size: 0.65rem;
    }
    
    h2 {
        font-size: 1.25rem;
    }
    
    .card-header h5 {
        font-size: 1rem;
    }
}
</style>
@endsection