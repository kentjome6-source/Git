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

                        <!-- Urgency Level -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="urgency_level" class="form-label fw-bold">Urgency Level</label>
                                <select name="urgency_level" id="urgency_level" class="form-select" required>
                                    <option value="">Select urgency level</option>
                                    <option value="low" {{ $appointment->urgency_level === 'low' ? 'selected' : '' }}>Low - Routine checkup or general questions</option>
                                    <option value="medium" {{ $appointment->urgency_level === 'medium' ? 'selected' : '' }}>Medium - Concerning symptoms but not urgent</option>
                                    <option value="high" {{ $appointment->urgency_level === 'high' ? 'selected' : '' }}>High - Serious symptoms requiring prompt attention</option>
                                    <option value="emergency" {{ $appointment->urgency_level === 'emergency' ? 'selected' : '' }}>Emergency - Life-threatening condition</option>
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
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="owner_name" class="form-label">Full Name *</label>
                                    <input type="text" name="owner_name" id="owner_name" class="form-control" 
                                           value="{{ old('owner_name', $appointment->owner_name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="owner_email" class="form-label">Email Address *</label>
                                    <input type="email" name="owner_email" id="owner_email" class="form-control" 
                                           value="{{ old('owner_email', $appointment->owner_email) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="owner_phone" class="form-label">Phone Number *</label>
                                    <input type="tel" name="owner_phone" id="owner_phone" class="form-control" 
                                           value="{{ old('owner_phone', $appointment->owner_phone) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="owner_address" class="form-label">Address</label>
                                    <textarea name="owner_address" id="owner_address" class="form-control" rows="3">{{ old('owner_address', $appointment->owner_address) }}</textarea>
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
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pet_name" class="form-label">Pet Name *</label>
                                    <input type="text" name="pet_name" id="pet_name" class="form-control" 
                                           value="{{ old('pet_name', $appointment->pet_name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pet_species" class="form-label">Species *</label>
                                    <select name="pet_species" id="pet_species" class="form-select" required>
                                        <option value="">Select species</option>
                                        <option value="Dog" {{ $appointment->pet_species === 'Dog' ? 'selected' : '' }}>Dog</option>
                                        <option value="Cat" {{ $appointment->pet_species === 'Cat' ? 'selected' : '' }}>Cat</option>
                                        <option value="Bird" {{ $appointment->pet_species === 'Bird' ? 'selected' : '' }}>Bird</option>
                                        <option value="Rabbit" {{ $appointment->pet_species === 'Rabbit' ? 'selected' : '' }}>Rabbit</option>
                                        <option value="Hamster" {{ $appointment->pet_species === 'Hamster' ? 'selected' : '' }}>Hamster</option>
                                        <option value="Guinea Pig" {{ $appointment->pet_species === 'Guinea Pig' ? 'selected' : '' }}>Guinea Pig</option>
                                        <option value="Fish" {{ $appointment->pet_species === 'Fish' ? 'selected' : '' }}>Fish</option>
                                        <option value="Reptile" {{ $appointment->pet_species === 'Reptile' ? 'selected' : '' }}>Reptile</option>
                                        <option value="Other" {{ $appointment->pet_species === 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="pet_breed" class="form-label">Breed</label>
                                    <input type="text" name="pet_breed" id="pet_breed" class="form-control" 
                                           value="{{ old('pet_breed', $appointment->pet_breed) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="pet_gender" class="form-label">Gender</label>
                                    <select name="pet_gender" id="pet_gender" class="form-select">
                                        <option value="">Select gender</option>
                                        <option value="male" {{ $appointment->pet_gender === 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ $appointment->pet_gender === 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="unknown" {{ $appointment->pet_gender === 'unknown' ? 'selected' : '' }}>Unknown</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="pet_age_years" class="form-label">Age (Years)</label>
                                    <input type="number" name="pet_age_years" id="pet_age_years" class="form-control" 
                                           min="0" max="30" value="{{ old('pet_age_years', $appointment->pet_age_years) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="pet_weight" class="form-label">Weight (kg)</label>
                                    <input type="number" name="pet_weight" id="pet_weight" class="form-control" 
                                           step="0.1" min="0" max="999.99" value="{{ old('pet_weight', $appointment->pet_weight) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Appointment Details Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-notes-medical me-2"></i>Appointment Details
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="consultation_reason" class="form-label">Reason for Appointment *</label>
                                    <select name="consultation_reason" id="consultation_reason" class="form-select" required>
                                        <option value="">Select reason</option>
                                        <option value="routine_checkup" {{ $appointment->consultation_reason === 'routine_checkup' ? 'selected' : '' }}>Routine Checkup</option>
                                        <option value="illness" {{ $appointment->consultation_reason === 'illness' ? 'selected' : '' }}>Illness/Sickness</option>
                                        <option value="injury" {{ $appointment->consultation_reason === 'injury' ? 'selected' : '' }}>Injury</option>
                                        <option value="vaccination" {{ $appointment->consultation_reason === 'vaccination' ? 'selected' : '' }}>Vaccination</option>
                                        <option value="behavioral" {{ $appointment->consultation_reason === 'behavioral' ? 'selected' : '' }}>Behavioral Issues</option>
                                        <option value="other" {{ $appointment->consultation_reason === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appointment_date" class="form-label">Preferred Appointment Date</label>
                                    <input type="date" name="appointment_date" id="appointment_date" 
                                           class="form-control" value="{{ old('appointment_date', $appointment->appointment_date ? $appointment->appointment_date->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appointment_time" class="form-label">Preferred Appointment Time</label>
                                    <input type="time" name="appointment_time" id="appointment_time" 
                                           class="form-control" value="{{ old('appointment_time', $appointment->appointment_time ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-6" id="scheduled_datetime_section" style="{{ $appointment->consultation_type === 'appointment' ? 'display: block;' : 'display: none;' }}">
                                <div class="mb-3">
                                    <label for="scheduled_datetime" class="form-label">Scheduled Date & Time</label>
                                    <input type="datetime-local" name="scheduled_datetime" id="scheduled_datetime" 
                                           class="form-control" value="{{ old('scheduled_datetime', $appointment->scheduled_datetime ? $appointment->scheduled_datetime->format('Y-m-d\TH:i') : '') }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="chief_complaint" class="form-label">Chief Complaint *</label>
                                    <textarea name="chief_complaint" id="chief_complaint" class="form-control" 
                                              rows="3" required placeholder="Briefly describe the main concern or reason for this consultation">{{ old('chief_complaint', $appointment->chief_complaint) }}</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="detailed_symptoms" class="form-label">Detailed Symptoms *</label>
                                    <textarea name="detailed_symptoms" id="detailed_symptoms" class="form-control" 
                                              rows="4" required placeholder="Provide detailed description of all symptoms observed">{{ old('detailed_symptoms', $appointment->detailed_symptoms) }}</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="additional_concerns" class="form-label">Additional Concerns</label>
                                    <textarea name="additional_concerns" id="additional_concerns" class="form-control" 
                                              rows="3" placeholder="Any other concerns or questions you have">{{ old('additional_concerns', $appointment->additional_concerns) }}</textarea>
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
                                           class="form-control" min="0" value="{{ old('symptom_duration_days', $appointment->symptom_duration_days) }}"
                                           placeholder="How many days have symptoms been present?">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="symptom_onset" class="form-label">Symptom Onset *</label>
                                    <select name="symptom_onset" id="symptom_onset" class="form-select" required>
                                        <option value="">Select onset type</option>
                                        <option value="sudden" {{ $appointment->symptom_onset === 'sudden' ? 'selected' : '' }}>Sudden - Symptoms appeared quickly</option>
                                        <option value="gradual" {{ $appointment->symptom_onset === 'gradual' ? 'selected' : '' }}>Gradual - Symptoms developed slowly over time</option>
                                        <option value="intermittent" {{ $appointment->symptom_onset === 'intermittent' ? 'selected' : '' }}>Intermittent - Symptoms come and go</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="symptom_progression" class="form-label">Symptom Progression</label>
                                    <textarea name="symptom_progression" id="symptom_progression" class="form-control" 
                                              rows="3" placeholder="How have the symptoms changed over time?">{{ old('symptom_progression', $appointment->symptom_progression) }}</textarea>
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
                                              rows="3" placeholder="List any medications your pet is currently taking">{{ old('current_medications', $appointment->current_medications) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="previous_treatments" class="form-label">Previous Treatments</label>
                                    <textarea name="previous_treatments" id="previous_treatments" class="form-control" 
                                              rows="3" placeholder="Describe any previous treatments for this condition">{{ old('previous_treatments', $appointment->previous_treatments) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="allergies" class="form-label">Known Allergies</label>
                                    <textarea name="allergies" id="allergies" class="form-control" 
                                              rows="3" placeholder="List any known allergies or adverse reactions">{{ old('allergies', $appointment->allergies) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vaccination_history" class="form-label">Vaccination History</label>
                                    <textarea name="vaccination_history" id="vaccination_history" class="form-control" 
                                              rows="3" placeholder="Recent vaccinations and dates">{{ old('vaccination_history', $appointment->vaccination_history) }}</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="previous_medical_history" class="form-label">Previous Medical History</label>
                                    <textarea name="previous_medical_history" id="previous_medical_history" class="form-control" 
                                              rows="3" placeholder="Any relevant past medical conditions or surgeries">{{ old('previous_medical_history', $appointment->previous_medical_history) }}</textarea>
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
    // Set minimum datetime to current time
    const datetimeInput = document.getElementById('scheduled_datetime');
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    datetimeInput.min = now.toISOString().slice(0, 16);
});
</script>
@endsection