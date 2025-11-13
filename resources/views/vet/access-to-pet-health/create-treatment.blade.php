@extends('layouts.vet')

@section('title', 'Add Treatment')

@section('content')
<div class="container">
    <h2 class="mb-4">💊 Add Treatment for {{ $record->name }}</h2>
    <p class="text-muted">Fill in the treatment details for this pet.</p>

    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">📋 Treatment Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('vet.records.treatments.add', $record->id) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="treatment_date" class="form-label">Date</label>
                        <input type="date" name="treatment_date" id="treatment_date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" required name="title" id="title" class="form-control" placeholder="e.g. Antibiotic Treatment">
                    </div>
                    <div class="col-md-4">
                        <label for="medication" class="form-label">Medication</label>
                        <input type="text" name="medication" id="medication" class="form-control" placeholder="e.g. Amoxicillin">
                    </div>
                    <div class="col-md-4">
                        <label for="dosage" class="form-label">Dosage</label>
                        <input type="text" name="dosage" id="dosage" class="form-control" placeholder="e.g. 250mg">
                    </div>
                    <div class="col-md-4">
                        <label for="frequency" class="form-label">Frequency</label>
                        <input type="text" name="frequency" id="frequency" class="form-control" placeholder="e.g. 2x daily">
                    </div>
                    <div class="col-12">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Additional instructions..."></textarea>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save"></i> Save Treatment
                        </button>
                        <a href="{{ route('vet.records') }}" class="btn btn-secondary px-4">
                            <i class="fas fa-arrow-left"></i> Back to Records
                        </a>
                    </div>
                </div>
            </form>
        </div>
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
        padding: 0.75rem 1rem;
        font-size: 1rem;
    }
    
    .col-12.mt-3 {
        display: flex;
        flex-direction: column;
    }
    
    .col-12.mt-3 .btn:first-child {
        margin-bottom: 0.5rem;
    }
}

@media (max-width: 576px) {
    .card-body {
        padding: 1rem;
    }
    
    .form-label {
        font-size: 0.9rem;
    }
    
    .form-control {
        font-size: 1rem;
        padding: 0.5rem 0.75rem;
    }
    
    .btn {
        padding: 0.75rem;
        font-size: 0.95rem;
    }
}
</style>
@endsection