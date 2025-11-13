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

                        <!-- Urgency Level -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="urgency_level" class="form-label fw-bold">Urgency Level</label>
                                <select name="urgency_level" id="urgency_level" class="form-select" required>
                                    <option value="">Select urgency level</option>
                                    <option value="low">Low - Routine checkup or general questions</option>
                                    <option value="medium">Medium - Concerning symptoms but not urgent</option>
                                    <option value="high">High - Serious symptoms requiring prompt attention</option>
                                    <option value="emergency">Emergency - Life-threatening condition</option>
                                </select>
                            </div>
                        </div>

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
                                    <label for="owner_email" class="form-label">Email *</label>
                                    <input type="email" name="owner_email" id="owner_email" class="form-control form-control-sm" 
                                           value="{{ old('owner_email', auth()->user()->email) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="owner_phone" class="form-label">Phone *</label>
                                    <input type="tel" name="owner_phone" id="owner_phone" class="form-control form-control-sm" 
                                           value="{{ old('owner_phone') }}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-2">
                                    <label for="owner_address" class="form-label">Address</label>
                                    <input type="text" name="owner_address" id="owner_address" class="form-control form-control-sm" 
                                           value="{{ old('owner_address') }}" placeholder="Enter your full address">
                                </div>
                            </div>
                        </div>

                        <!-- Pet Selection and Information Section -->
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
                                    <label for="pet_species" class="form-label">Species *</label>
                                    <input type="text" name="pet_species" id="pet_species" class="form-control form-control-sm" 
                                           value="{{ old('pet_species') }}" placeholder="e.g., Dog, Cat, Bird" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="pet_breed" class="form-label">Breed</label>
                                    <input type="text" name="pet_breed" id="pet_breed" class="form-control form-control-sm" 
                                           value="{{ old('pet_breed') }}" placeholder="e.g., Golden Retriever">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="pet_age_years" class="form-label">Age (years)</label>
                                    <input type="number" name="pet_age_years" id="pet_age_years" class="form-control form-control-sm" 
                                           value="{{ old('pet_age_years') }}" min="0" step="0.1" placeholder="e.g., 2.5">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="pet_weight" class="form-label">Weight (kg)</label>
                                    <input type="number" name="pet_weight" id="pet_weight" class="form-control form-control-sm" 
                                           value="{{ old('pet_weight') }}" min="0" step="0.1" placeholder="e.g., 15.5">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="pet_gender" class="form-label">Gender</label>
                                    <select name="pet_gender" id="pet_gender" class="form-select form-select-sm">
                                        <option value="">Select gender</option>
                                        <option value="male" {{ old('pet_gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('pet_gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="unknown" {{ old('pet_gender') == 'unknown' || old('pet_gender') == '' ? 'selected' : '' }}>Unknown</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Appointment Details Section -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <h6 class="text-dark border-bottom pb-1 mb-2">
                                    <i class="fas fa-stethoscope me-2 text-primary"></i>Appointment Details
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="consultation_reason" class="form-label">Reason *</label>
                                    <select name="consultation_reason" id="consultation_reason" class="form-select form-select-sm" required>
                                        <option value="">Select reason</option>
                                        <option value="routine_checkup">Routine Checkup</option>
                                        <option value="illness">Illness/Sickness</option>
                                        <option value="injury">Injury</option>
                                        <option value="vaccination">Vaccination</option>
                                        <option value="behavioral">Behavioral Issues</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="appointment_date" class="form-label">Preferred Appointment Date</label>
                                    <input type="date" name="appointment_date" id="appointment_date" 
                                           class="form-control form-control-sm" value="{{ old('appointment_date') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="appointment_time" class="form-label">Preferred Appointment Time</label>
                                    <input type="time" name="appointment_time" id="appointment_time" 
                                           class="form-control form-control-sm" value="{{ old('appointment_time') }}">
                                </div>
                            </div>
                            <div class="col-md-6" id="scheduled_datetime_section" style="display: none;">
                                <div class="mb-2">
                                    <label for="scheduled_datetime" class="form-label">Scheduled Date & Time</label>
                                    <input type="datetime-local" name="scheduled_datetime" id="scheduled_datetime" 
                                           class="form-control form-control-sm" value="{{ old('scheduled_datetime') }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-2">
                                    <label for="chief_complaint" class="form-label">Chief Complaint *</label>
                                    <textarea name="chief_complaint" id="chief_complaint" class="form-control form-control-sm" rows="2" 
                                              placeholder="Briefly describe the main concern" required>{{ old('chief_complaint') }}</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-2">
                                    <label for="detailed_symptoms" class="form-label">Detailed Symptoms</label>
                                    <textarea name="detailed_symptoms" id="detailed_symptoms" class="form-control form-control-sm" rows="2" 
                                              placeholder="Detailed description of symptoms">{{ old('detailed_symptoms') }}</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="additional_concerns" class="form-label">Additional Concerns</label>
                                    <textarea name="additional_concerns" id="additional_concerns" class="form-control" 
                                              rows="3" placeholder="Any other concerns or questions you have">{{ old('additional_concerns') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Duration of Symptoms Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-clock me-2"></i>Duration of Symptoms
                                </h5>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="symptom_duration_days" class="form-label">Duration (Days)</label>
                                    <input type="number" name="symptom_duration_days" id="symptom_duration_days" 
                                           class="form-control" min="0" value="{{ old('symptom_duration_days') }}"
                                           placeholder="How many days have symptoms been present?">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="symptom_onset" class="form-label">Symptom Onset *</label>
                                    <select name="symptom_onset" id="symptom_onset" class="form-select" required>
                                        <option value="">Select onset type</option>
                                        <option value="sudden">Sudden - Symptoms appeared quickly</option>
                                        <option value="gradual">Gradual - Symptoms developed slowly over time</option>
                                        <option value="intermittent">Intermittent - Symptoms come and go</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="symptom_progression" class="form-label">Symptom Progression</label>
                                    <textarea name="symptom_progression" id="symptom_progression" class="form-control" 
                                              rows="3" placeholder="How have the symptoms changed over time?">{{ old('symptom_progression') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Previous Medications / Treatments Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-pills me-2"></i>Previous Medications / Treatments
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="current_medications" class="form-label">Current Medications</label>
                                    <textarea name="current_medications" id="current_medications" class="form-control" 
                                              rows="3" placeholder="List any medications your pet is currently taking">{{ old('current_medications') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="previous_treatments" class="form-label">Previous Treatments</label>
                                    <textarea name="previous_treatments" id="previous_treatments" class="form-control" 
                                              rows="3" placeholder="Describe any previous treatments for this condition">{{ old('previous_treatments') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="allergies" class="form-label">Known Allergies</label>
                                    <textarea name="allergies" id="allergies" class="form-control" 
                                              rows="3" placeholder="List any known allergies or adverse reactions">{{ old('allergies') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vaccination_history" class="form-label">Vaccination History</label>
                                    <textarea name="vaccination_history" id="vaccination_history" class="form-control" 
                                              rows="3" placeholder="Recent vaccinations and dates">{{ old('vaccination_history') }}</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="previous_medical_history" class="form-label">Previous Medical History</label>
                                    <textarea name="previous_medical_history" id="previous_medical_history" class="form-control" 
                                              rows="3" placeholder="Any relevant past medical conditions or surgeries">{{ old('previous_medical_history') }}</textarea>
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
.consultation-type-card {
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid #dee2e6;
    min-height: 180px;
    max-width: 100%;
}

.consultation-type-card:hover {
    border-color: #6c757d;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.consultation-type-card.selected {
    border-color: #495057;
    background-color: #f8f9fa;
}

.consultation-type-card input[type="radio"] {
    transform: scale(1.2);
}

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
    // Set minimum datetime to current time
    const datetimeInput = document.getElementById('scheduled_datetime');
    if (datetimeInput) {
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        datetimeInput.min = now.toISOString().slice(0, 16);
    }
});
</script>
@endsection