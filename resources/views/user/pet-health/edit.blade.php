@extends('layouts.app')

@section('title', 'Edit Pet Health Record')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-xxl-9"> {{-- Mas lapad pero hindi full screen --}}
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-warning text-dark py-3 rounded-top">
                    <h3 class="mb-1 fw-bold">✏️ Edit Pet Health Record</h3>
                    <small class="text-muted">Update your pet's health information</small>
                </div>
                <div class="card-body p-4">
                    
                    {{-- Error Handling --}}
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Edit Form --}}
                    <form action="{{ route('pet.health.update', $record) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Basic Pet Information --}}
                        <h5 class="fw-bold text-primary mb-3">🐾 Basic Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Pet Name *</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $record->name) }}" required placeholder="Enter pet's name">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Species *</label>
                                <select name="species" class="form-select @error('species') is-invalid @enderror" required>
                                    <option value="">Select Species</option>
                                    <option value="Dog" {{ old('species', $record->species) == 'Dog' ? 'selected' : '' }}>Dog</option>
                                    <option value="Cat" {{ old('species', $record->species) == 'Cat' ? 'selected' : '' }}>Cat</option>
                                    <option value="Bird" {{ old('species', $record->species) == 'Bird' ? 'selected' : '' }}>Bird</option>
                                    <option value="Rabbit" {{ old('species', $record->species) == 'Rabbit' ? 'selected' : '' }}>Rabbit</option>
                                    <option value="Other" {{ old('species', $record->species) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('species')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Breed *</label>
                                <input type="text" name="breed" class="form-control @error('breed') is-invalid @enderror" 
                                       value="{{ old('breed', $record->breed) }}" required placeholder="Enter breed">
                                @error('breed')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Age (years) *</label>
                                <input type="number" name="age" class="form-control @error('age') is-invalid @enderror" 
                                       value="{{ old('age', $record->age) }}" required min="0" max="30" placeholder="Enter age">
                                @error('age')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold">Weight (kg) *</label>
                            <input type="number" step="0.1" name="weight" class="form-control @error('weight') is-invalid @enderror" 
                                   value="{{ old('weight', $record->weight) }}" required min="0.1" max="200" placeholder="Enter weight in kg">
                            @error('weight')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Health Information --}}
                        <hr class="my-4">
                        <h5 class="fw-bold text-primary mb-3">🩺 Health Information</h5>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Current Health Condition</label>
                            <input type="text" name="condition" class="form-control @error('condition') is-invalid @enderror" 
                                   value="{{ old('condition', $record->condition) }}" placeholder="e.g., Healthy, Under treatment">
                            @error('condition')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Medical Notes</label>
                            <textarea name="medical_notes" class="form-control @error('medical_notes') is-invalid @enderror" 
                                      rows="3" placeholder="Any medical history, allergies, or concerns">{{ old('medical_notes', $record->medical_notes) }}</textarea>
                            @error('medical_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Last Checkup Date</label>
                            <input type="date" name="diagnosed_at" class="form-control @error('diagnosed_at') is-invalid @enderror" 
                                   value="{{ old('diagnosed_at', $record->diagnosed_at) }}">
                            @error('diagnosed_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Vaccination Information --}}
                        <hr class="my-4">
                        <h5 class="fw-bold text-primary mb-3">💉 Vaccination Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Latest Vaccine Name</label>
                                <input type="text" name="vaccine_name" class="form-control @error('vaccine_name') is-invalid @enderror" 
                                       value="{{ old('vaccine_name', $record->vaccine_name) }}" placeholder="e.g., Rabies, DHPP, etc.">
                                @error('vaccine_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Date Given</label>
                                <input type="date" name="date_given" class="form-control @error('date_given') is-invalid @enderror" 
                                       value="{{ old('date_given', $record->date_given) }}">
                                @error('date_given')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Next Due Date</label>
                                <input type="date" name="next_due" class="form-control @error('next_due') is-invalid @enderror" 
                                       value="{{ old('next_due', $record->next_due) }}">
                                @error('next_due')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Vaccination Status</label>
                                <select name="vaccine_status" class="form-select @error('vaccine_status') is-invalid @enderror">
                                    <option value="">Select Status</option>
                                    <option value="Up to date" {{ old('vaccine_status', $record->vaccine_status) == 'Up to date' ? 'selected' : '' }}>Up to date</option>
                                    <option value="Due soon" {{ old('vaccine_status', $record->vaccine_status) == 'Due soon' ? 'selected' : '' }}>Due soon</option>
                                    <option value="Overdue" {{ old('vaccine_status', $record->vaccine_status) == 'Overdue' ? 'selected' : '' }}>Overdue</option>
                                    <option value="Not vaccinated" {{ old('vaccine_status', $record->vaccine_status) == 'Not vaccinated' ? 'selected' : '' }}>Not vaccinated</option>
                                </select>
                                @error('vaccine_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-end mt-4 gap-2">
                            <a href="{{ route('pet.health') }}" class="btn btn-outline-secondary btn-sm px-3">
                                <i class="fas fa-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-warning btn-sm px-3">
                                <i class="fas fa-save"></i> Update Record
                            </button>
                        </div>
                    </form>
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
    
    .card-header h3 {
        font-size: 1.5rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .form-label {
        font-size: 0.9rem;
    }
    
    .form-control, .form-select {
        font-size: 1rem;
        padding: 0.5rem 0.75rem;
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
    
    hr.my-4 {
        margin: 1.5rem 0;
    }
    
    h5 {
        font-size: 1.25rem;
    }
}

@media (max-width: 576px) {
    .py-5 {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
    }
    
    .col-md-6 {
        margin-bottom: 1rem;
    }
    
    .card {
        margin: 0 0.5rem;
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