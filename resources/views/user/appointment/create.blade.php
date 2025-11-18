@extends('layouts.app')

@section('title', 'Request Appointment')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white border-bottom">
                    <h4 class="mb-0 text-dark">
                        <i class="fas fa-stethoscope me-2 text-muted"></i>
                        Request Appointment
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('appointments.store') }}" method="POST" id="consultationForm">
                        @csrf
                        
                        <!-- Hidden appointment type field -->
                        <input type="hidden" name="appointment_type" value="appointment">

                        <!-- Veterinarian Selection -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <h6 class="text-dark border-bottom pb-1 mb-2">
                                    <i class="fas fa-user-md me-2 text-info"></i>Select Veterinarian
                                </h6>
                            </div>
                            <div class="col-12">
                                <div class="mb-2">
                                    <label for="vet_id" class="form-label">Veterinarian *</label>
                                    <select name="vet_id" id="vet_id" class="form-select form-select-sm" required>
                                        <option value="">Select a veterinarian</option>
                                        @foreach($vets as $vet)
                                            @if($vet->is_verified_vet)
                                                <option value="{{ $vet->id }}">
                                                    Dr. {{ $vet->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <div class="form-text">Only verified veterinarians are listed here. Your appointment request will be sent to the selected veterinarian only.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Owner Information Section -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <h6 class="text-dark border-bottom pb-1 mb-2">
                                    <i class="fas fa-user me-2 text-warning"></i>Owner Information
                                </h6>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="owner_name" class="form-label">Full Name *</label>
                                    <input type="text" name="owner_name" id="owner_name" class="form-control form-control-sm" 
                                           value="{{ old('owner_name', auth()->user()->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="owner_phone" class="form-label">Phone *</label>
                                    <input type="tel" name="owner_phone" id="owner_phone" class="form-control form-control-sm" 
                                           value="{{ old('owner_phone') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" name="email" id="email" class="form-control form-control-sm" 
                                           value="{{ old('email', auth()->user()->email) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="owner_address" class="form-label">Address</label>
                                    <input type="text" name="owner_address" id="owner_address" class="form-control form-control-sm" 
                                           value="{{ old('owner_address') }}" placeholder="Enter your full address">
                                </div>
                            </div>
                        </div>

                        <!-- Pet Information Section -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <h6 class="text-dark border-bottom pb-1 mb-2">
                                    <i class="fas fa-paw me-2 text-danger"></i>Pet Information
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="pet_name" class="form-label">Pet Name *</label>
                                    <input type="text" name="pet_name" id="pet_name" class="form-control form-control-sm" 
                                           value="{{ old('pet_name') }}" placeholder="Enter your pet's name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="pet_type" class="form-label">Pet Type *</label>
                                    <select name="pet_type" id="pet_type" class="form-select form-select-sm" required>
                                        <option value="">Select pet type</option>
                                        <option value="Dog" {{ old('pet_type') == 'Dog' ? 'selected' : '' }}>Dog</option>
                                        <option value="Cat" {{ old('pet_type') == 'Cat' ? 'selected' : '' }}>Cat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-2">
                                    <label for="pet_services_received" class="form-label">Pet Services Received</label>
                                    <textarea name="pet_services_received" id="pet_services_received" class="form-control form-control-sm" 
                                              rows="2" placeholder="Enter services your pet has received (e.g., Deworming, Vaccination, Tick and Flea Prevention)">{{ old('pet_services_received') }}</textarea>
                                    <div class="form-text">List any services your pet has recently received, such as deworming, vaccination, or tick and flea prevention.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Scheduling Section -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <h6 class="text-dark border-bottom pb-1 mb-2">
                                    <i class="fas fa-calendar me-2 text-primary"></i>Scheduling
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="preferred_date" class="form-label">Preferred Date</label>
                                    <input type="date" name="preferred_date" id="preferred_date" 
                                           class="form-control form-control-sm" value="{{ old('preferred_date') }}" placeholder="Select a date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="preferred_time" class="form-label">Preferred Time</label>
                                    <input type="time" name="preferred_time" id="preferred_time" 
                                           class="form-control form-control-sm" value="{{ old('preferred_time') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Submit Section -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between flex-column flex-md-row gap-2">
                                    <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Back to Appointments
                                    </a>
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i>Submit Appointment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-control:focus, .form-select:focus {
    border-color: #6c757d;
    box-shadow: 0 0 0 0.1rem rgba(108,117,125,.15);
}

/* Green button custom style */
.btn-success {
    background-color: #28a745;
    border-color: #28a745;
    padding: 10px 24px;
    font-weight: 500;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
}

/* Fixed form sizing */
.card {
    max-width: 1200px;
    margin: 0 auto;
}

.form-control, .form-select {
    resize: none;
    min-height: 38px;
}

.container-fluid {
    max-width: 1400px;
    padding: 20px;
}

.row {
    margin: 0;
}

.col-md-4, .col-md-6, .col-md-12 {
    padding-left: 8px;
    padding-right: 8px;
}

/* Responsive styles */
@media (max-width: 768px) {
    .container-fluid {
        padding: 10px;
    }
    
    .card {
        margin: 0 0.5rem;
    }
    
    .card-header h4 {
        font-size: 1.25rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .col-md-4, .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 1rem;
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
    
    .btn-lg {
        padding: 0.75rem 1.25rem;
        font-size: 1rem;
    }
    
    h5, h6 {
        font-size: 1.1rem;
    }
    
    .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .d-flex .btn {
        width: 100%;
    }
    
    .border-bottom {
        margin-bottom: 1rem !important;
        padding-bottom: 0.5rem !important;
    }
    
    /* Adjust form elements for better mobile experience */
    .form-control-sm, .form-select-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    textarea.form-control {
        font-size: 0.875rem;
    }
    
    .mb-2, .mb-3, .mb-4 {
        margin-bottom: 1rem !important;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding: 0.5rem;
    }
    
    .card {
        margin: 0;
    }
    
    .card-header {
        padding: 0.75rem;
    }
    
    .card-body {
        padding: 0.75rem;
    }
    
    .mb-2, .mb-3, .mb-4 {
        margin-bottom: 0.75rem !important;
    }
    
    .form-text {
        font-size: 0.8rem;
    }
    
    textarea.form-control {
        font-size: 0.9rem;
    }
    
    .btn {
        padding: 0.5rem;
        font-size: 0.875rem;
    }
    
    .btn-lg {
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
    }
    
    h5 {
        font-size: 1rem;
    }
    
    h6 {
        font-size: 0.95rem;
    }
}

/* Extra small devices */
@media (max-width: 400px) {
    .container-fluid {
        padding: 0.25rem;
    }
    
    .card-body {
        padding: 0.5rem;
    }
    
    .form-label {
        font-size: 0.85rem;
    }
    
    .form-control, .form-select {
        font-size: 0.9rem;
        padding: 0.375rem 0.5rem;
    }
    
    .btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.85rem;
    }
    
    .btn-lg {
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date to current date
    const dateInput = document.getElementById('preferred_date');
    const timeInput = document.getElementById('preferred_time');
    
    if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.min = today;
    }
    
    // Set minimum time based on current date
    if (dateInput && timeInput) {
        dateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            selectedDate.setHours(0, 0, 0, 0);
            
            // If selected date is today, set minimum time to current time
            if (selectedDate.getTime() === today.getTime()) {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = Math.ceil(now.getMinutes() / 30) * 30; // Round up to nearest 30 minutes
                const roundedMinutes = minutes >= 60 ? '00' : String(minutes).padStart(2, '0');
                const minTime = minutes >= 60 ? String(parseInt(hours) + 1).padStart(2, '0') + ':' + roundedMinutes : hours + ':' + roundedMinutes;
                timeInput.min = minTime;
            } else {
                timeInput.min = '00:00';
            }
        });
    }
});
</script>
@endsection