@extends('layouts.app')

@section('title', 'Pet Health Details')

@section('content')
<div class="container-fluid py-4 py-md-5"> {{-- container-fluid para mas wide --}}
    <div class="row justify-content-center">
        <div class="col-xl-10 col-xxl-9"> {{-- mas malapad kaysa col-md-8 --}}
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                
                {{-- Header --}}
                <div class="card-header bg-gradient bg-info text-white text-center py-3 py-md-4">
                    <h3 class="fw-bold mb-0 fs-4 fs-md-3">🐾 Pet Health Record</h3>
                </div>

                {{-- Body --}}
                <div class="card-body p-3 p-md-5 bg-light">
                    
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
                                        <th class="small">Vet</th>
                                        @auth
                                            @if(Auth::user()->role === 'vet')
                                                <th class="small">Actions</th>
                                            @endif
                                        @endauth
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($record->treatments as $t)
                                        <tr>
                                            <td class="small">{{ $t->treatment_date }}</td>
                                            <td class="small">{{ $t->title }}</td>
                                            <td class="small">{{ $t->medication ?? '—' }}</td>
                                            <td class="small">{{ $t->dosage ?? '—' }}</td>
                                            <td class="small">{{ $t->frequency ?? '—' }}</td>
                                            <td class="small">{{ $t->notes ?? '—' }}</td>
                                            <td class="small">{{ $t->vet->name ?? 'Unknown Vet' }}</td>
                                            @auth
                                                @if(Auth::user()->role === 'vet')
                                                    <td class="small">
                                                        @if($t->vet_id === Auth::id())
                                                            <a href="{{ route('vet.records.treatments.edit', $t->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                                        @else
                                                            <span class="text-muted">Not editable</span>
                                                        @endif
                                                    </td>
                                                @endif
                                            @endauth
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{-- Mobile Card View --}}
                        <div class="d-md-none">
                            @foreach($record->treatments as $t)
                                <div class="card mb-3 shadow-sm border">
                                    <div class="card-header bg-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0 fs-6">{{ $t->title }}</h5>
                                            <span class="badge bg-primary">{{ $t->treatment_date }}</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <small class="text-muted">Medication:</small>
                                                <div class="fw-bold">{{ $t->medication ?? '—' }}</div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Dosage:</small>
                                                <div>{{ $t->dosage ?? '—' }}</div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Frequency:</small>
                                                <div>{{ $t->frequency ?? '—' }}</div>
                                            </div>
                                            <div class="col-12">
                                                <small class="text-muted">Notes:</small>
                                                <div>{{ $t->notes ?? '—' }}</div>
                                            </div>
                                            <div class="col-12">
                                                <small class="text-muted">Vet:</small>
                                                <div>{{ $t->vet->name ?? 'Unknown Vet' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    @auth
                                        @if(Auth::user()->role === 'vet')
                                            <div class="card-footer bg-white">
                                                @if($t->vet_id === Auth::id())
                                                    <a href="{{ route('vet.records.treatments.edit', $t->id) }}" class="btn btn-sm btn-outline-primary w-100">
                                                        <i class="fas fa-edit me-1"></i> Edit
                                                    </a>
                                                @else
                                                    <span class="text-muted">Not editable</span>
                                                @endif
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mt-4 mt-md-5">
                        <a href="{{ route('pet.health.edit', $record->id) }}" class="btn btn-warning btn-sm px-3 py-2"> Edit</a>
                        <form action="{{ route('pet.health.destroy', $record->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm px-3 py-2" onclick="return confirm('Delete this record?')"> Delete</button>
                        </form>
                        <a href="{{ route('pet.health') }}" class="btn btn-secondary btn-sm px-3 py-2">⬅ Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Custom Styling --}}
<style>
    .card-header h3 {
        font-size: 1.6rem;
    }
    .card-body h4 {
        font-weight: 600;
        border-left: 5px solid #ccc;
        padding-left: 10px;
        font-size: 1.25rem;
    }
    .table thead th {
        font-weight: 600;
        font-size: 1rem;
    }
    .table tbody td {
        font-size: 0.95rem;
    }
    
    @media (max-width: 768px) {
        .card-header {
            padding: 1rem;
        }
        
        .card-header h3 {
            font-size: 1.5rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        
        .d-flex.gap-2 {
            display: flex !important;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .d-flex.gap-2 .btn {
            width: 100%;
            margin-right: 0 !important;
        }
        
        h4 {
            font-size: 1.25rem;
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
        
        .mb-4 {
            margin-bottom: 1rem !important;
        }
    }
    
    /* Desktop button size adjustments */
    @media (min-width: 769px) {
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .card-header {
            padding: 0.75rem 1.25rem;
        }
        
        .card-body {
            padding: 1.25rem;
        }
    }
</style>
@endsection