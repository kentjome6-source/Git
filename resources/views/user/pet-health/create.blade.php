@extends('layouts.app')

@section('title', 'Add Pet Health Record')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        {{-- From col-md-8 → col-md-10 para mas malapad --}}
        <div class="col-md-10">
            <div class="card shadow-sm border-0 rounded-4" style="max-width: 100%;">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">🐾 Add New Pet Health Record</h3>
                    <small>Please fill out all fields to ensure complete health tracking</small>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('pet.health.store') }}" method="POST">
                        @csrf

                        {{-- Basic Pet Information --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pet Name *</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" required placeholder="Enter pet's name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Species *</label>
                                    <select name="species" class="form-control @error('species') is-invalid @enderror" required>
                                        <option value="">Select Species</option>
                                        <option value="Dog" {{ old('species') == 'Dog' ? 'selected' : '' }}>Dog</option>
                                        <option value="Cat" {{ old('species') == 'Cat' ? 'selected' : '' }}>Cat</option>
                                        <option value="Bird" {{ old('species') == 'Bird' ? 'selected' : '' }}>Bird</option>
                                        <option value="Rabbit" {{ old('species') == 'Rabbit' ? 'selected' : '' }}>Rabbit</option>
                                        <option value="Other" {{ old('species') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('species')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Breed *</label>
                                    <input type="text" name="breed" class="form-control @error('breed') is-invalid @enderror" 
                                           value="{{ old('breed') }}" required placeholder="Enter breed">
                                    @error('breed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Age (years) *</label>
                                    <input type="number" name="age" class="form-control @error('age') is-invalid @enderror" 
                                           value="{{ old('age') }}" required min="0" max="30" placeholder="Enter age">
                                    @error('age')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Weight (kg) *</label>
                            <input type="number" step="0.1" name="weight" class="form-control @error('weight') is-invalid @enderror" 
                                   value="{{ old('weight') }}" required min="0.1" max="200" placeholder="Enter weight in kg">
                            @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Health Information --}}
                        <hr class="my-4">
                        <h5 class="text-primary mb-3">Health Information</h5>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Current Health Condition</label>
                            <input type="text" name="condition" class="form-control @error('condition') is-invalid @enderror" 
                                   value="{{ old('condition') }}" placeholder="e.g., Healthy, Under treatment, etc.">
                            @error('condition')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Medical Notes</label>
                            <textarea name="medical_notes" class="form-control @error('medical_notes') is-invalid @enderror" 
                                      rows="3" placeholder="Any medical history, allergies, or concerns">{{ old('medical_notes') }}</textarea>
                            @error('medical_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Last Checkup Date</label>
                            <input type="date" name="diagnosed_at" class="form-control @error('diagnosed_at') is-invalid @enderror" 
                                   value="{{ old('diagnosed_at') }}">
                            @error('diagnosed_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Vaccination Information --}}
                        <hr class="my-4">
                        <h5 class="text-primary mb-3">Vaccination Information</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Latest Vaccine Name</label>
                                    <input type="text" name="vaccine_name" class="form-control @error('vaccine_name') is-invalid @enderror" 
                                           value="{{ old('vaccine_name') }}" placeholder="e.g., Rabies, DHPP, etc.">
                                    @error('vaccine_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Date Given</label>
                                    <input type="date" name="date_given" class="form-control @error('date_given') is-invalid @enderror" 
                                           value="{{ old('date_given') }}">
                                    @error('date_given')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Next Due Date</label>
                                    <input type="date" name="next_due" class="form-control @error('next_due') is-invalid @enderror" 
                                           value="{{ old('next_due') }}">
                                    @error('next_due')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Vaccination Status</label>
                                    <select name="vaccine_status" class="form-control @error('vaccine_status') is-invalid @enderror">
                                        <option value="">Select Status</option>
                                        <option value="Up to date" {{ old('vaccine_status') == 'Up to date' ? 'selected' : '' }}>Up to date</option>
                                        <option value="Due soon" {{ old('vaccine_status') == 'Due soon' ? 'selected' : '' }}>Due soon</option>
                                        <option value="Overdue" {{ old('vaccine_status') == 'Overdue' ? 'selected' : '' }}>Overdue</option>
                                        <option value="Not vaccinated" {{ old('vaccine_status') == 'Not vaccinated' ? 'selected' : '' }}>Not vaccinated</option>
                                    </select>
                                    @error('vaccine_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('pet.health') }}" class="btn btn-outline-secondary btn-sm me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-success btn-sm px-3">
                                <i class="fas fa-save"></i> Save Pet Health Record
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
    
    .form-control {
        font-size: 1rem;
        padding: 0.5rem 0.75rem;
    }
    
    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    
    .d-grid.gap-2.d-md-flex {
        display: flex !important;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .d-grid.gap-2.d-md-flex .btn {
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