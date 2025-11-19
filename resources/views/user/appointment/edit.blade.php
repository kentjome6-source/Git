@extends('layouts.app')

@section('title', 'Edit Appointment Request')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Appointment
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('appointments.update', $appointment) }}" method="POST" id="appointmentForm">
                        @csrf
                        @method('PUT')
                        
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
                                                <option value="{{ $vet->id }}" {{ (old('vet_id', $appointment->vet_id) == $vet->id) ? 'selected' : '' }}>
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
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-user me-2"></i>Owner Information
                                </h5>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="owner_name" class="form-label">Full Name *</label>
                                    <input type="text" name="owner_name" id="owner_name" class="form-control" 
                                           value="{{ old('owner_name', $appointment->owner_name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="owner_phone" class="form-label">Phone *</label>
                                    <input type="tel" name="owner_phone" id="owner_phone" class="form-control" 
                                           value="{{ old('owner_phone', $appointment->owner_phone) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="owner_email" class="form-label">Email *</label>
                                    <input type="email" name="owner_email" id="owner_email" class="form-control" 
                                           value="{{ old('owner_email', $appointment->owner_email) }}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="owner_address" class="form-label">Address</label>
                                    <textarea name="owner_address" id="owner_address" class="form-control" rows="2">{{ old('owner_address', $appointment->owner_address) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Pet Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-paw me-2"></i>Pet Information
                                </h5>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="pet_name" class="form-label">Pet Name *</label>
                                    <input type="text" name="pet_name" id="pet_name" class="form-control" 
                                           value="{{ old('pet_name', $appointment->pet_name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="pet_type" class="form-label">Pet Type *</label>
                                    <select name="pet_type" id="pet_type" class="form-select" required>
                                        <option value="">Select pet type</option>
                                        <option value="Dog" {{ $appointment->pet_type === 'Dog' ? 'selected' : '' }}>Dog</option>
                                        <option value="Cat" {{ $appointment->pet_type === 'Cat' ? 'selected' : '' }}>Cat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="pet_services_received" class="form-label">Pet Services Received</label>
                                    <textarea name="pet_services_received" id="pet_services_received" class="form-control" 
                                              rows="2" placeholder="List any services your pet has recently received, such as deworming, vaccination, or tick and flea prevention.">{{ old('pet_services_received', $appointment->pet_services_received) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Scheduling Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-calendar-alt me-2"></i>Scheduling
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="preferred_date" class="form-label">Preferred Date</label>
                                    <input type="date" name="preferred_date" id="preferred_date" 
                                           class="form-control" value="{{ old('preferred_date', $appointment->appointment_date ? $appointment->appointment_date->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="preferred_time" class="form-label">Preferred Time</label>
                                    <input type="time" name="preferred_time" id="preferred_time" 
                                           class="form-control" value="{{ old('preferred_time', $appointment->appointment_time ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Submit Section -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Back to Appointments
                                    </a>
                                    <button type="submit" class="btn btn-warning btn-lg">
                                        <i class="fas fa-save me-2"></i>Update Appointment
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
.consultation-type-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
}

.consultation-type-card:hover {
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.consultation-type-card.selected {
    border-color: #007bff;
    background-color: #f8f9fa;
}

.consultation-type-card input[type="radio"] {
    transform: scale(1.5);
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