@extends('layouts.app')

@section('title', 'List Pet for Adoption')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0" id="form-heading">List a Pet for Adoption</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" aria-live="polite">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" aria-live="polite">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('adoptions.store') }}" method="POST" enctype="multipart/form-data" aria-labelledby="form-heading">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="pet_name" class="form-label">Pet Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('pet_name') is-invalid @enderror" 
                                   id="pet_name" name="pet_name" value="{{ old('pet_name') }}" required
                                   aria-describedby="pet-name-help">
                            <div id="pet-name-help" class="form-text">Enter the name of your pet</div>
                            @error('pet_name')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="breed" class="form-label">Breed</label>
                                    <input type="text" class="form-control @error('breed') is-invalid @enderror" 
                                           id="breed" name="breed" value="{{ old('breed') }}"
                                           aria-describedby="breed-help">
                                    <div id="breed-help" class="form-text">Enter your pet's breed (optional)</div>
                                    @error('breed')
                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="age" class="form-label">Age</label>
                                    <input type="number" class="form-control @error('age') is-invalid @enderror" 
                                           id="age" name="age" value="{{ old('age') }}" min="0"
                                           aria-describedby="age-help">
                                    <div id="age-help" class="form-text">Enter your pet's age in years</div>
                                    @error('age')
                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender"
                                    aria-describedby="gender-help">
                                <option value="">Select gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            <div id="gender-help" class="form-text">Select your pet's gender</div>
                            @error('gender')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4"
                                      aria-describedby="description-help">{{ old('description') }}</textarea>
                            <div id="description-help" class="form-text">Provide a detailed description of your pet's personality, behavior, and any special needs</div>
                            @error('description')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Pet Image (optional)</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*"
                                   aria-describedby="image-help">
                            <div id="image-help" class="form-text">Upload an image of your pet (optional). Supported formats: JPG, PNG, GIF.</div>
                            @error('image')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Veterinary Appointment Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-stethoscope me-2"></i>
                                    Veterinary Appointment (Optional)
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Schedule a veterinary appointment for your pet's health check-up before adoption.</p>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="schedule_appointment" name="schedule_appointment" 
                                           {{ old('schedule_appointment') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="schedule_appointment">
                                        Schedule a veterinary appointment
                                    </label>
                                </div>
                                
                                <div id="appointment-fields" style="display: none;">
                                    <div class="mb-3">
                                        <label for="vet_id" class="form-label">Select Veterinarian *</label>
                                        <select class="form-select @error('vet_id') is-invalid @enderror" 
                                                id="vet_id" name="vet_id" aria-describedby="vet-help">
                                            <option value="">Select a veterinarian</option>
                                            @foreach($vets as $vet)
                                                @if($vet->is_verified_vet)
                                                    <option value="{{ $vet->id }}" 
                                                            {{ old('vet_id') == $vet->id ? 'selected' : '' }}>
                                                        Dr. {{ $vet->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div id="vet-help" class="form-text">Only verified veterinarians are listed here.</div>
                                        @error('vet_id')
                                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="preferred_date" class="form-label">Preferred Date</label>
                                                <input type="date" class="form-control @error('preferred_date') is-invalid @enderror" 
                                                       id="preferred_date" name="preferred_date" 
                                                       value="{{ old('preferred_date') }}" 
                                                       aria-describedby="date-help">
                                                <div id="date-help" class="form-text">Select your preferred appointment date.</div>
                                                @error('preferred_date')
                                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="preferred_time" class="form-label">Preferred Time</label>
                                                <input type="time" class="form-control @error('preferred_time') is-invalid @enderror" 
                                                       id="preferred_time" name="preferred_time" 
                                                       value="{{ old('preferred_time') }}" 
                                                       aria-describedby="time-help">
                                                <div id="time-help" class="form-text">Select your preferred appointment time.</div>
                                                @error('preferred_time')
                                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between flex-wrap gap-2 adoption-form-buttons">
                            <a href="{{ route('adoptions.index') }}" class="btn btn-secondary cancel-btn" role="button" aria-label="Cancel and return to adoptions">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary submit-btn" aria-label="List pet for adoption">
                                List for Adoption
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>

@media (min-width: 768px) {
    /* Desktop button size reduction */
    .cancel-btn,
    .submit-btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        min-height: 36px;
    }
}

@media (max-width: 767.98px) {
    /* Mobile button size reduction */
    .cancel-btn,
    .submit-btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8125rem;
        min-height: 36px;
    }
    
    .adoption-form-buttons {
        gap: 0.5rem !important;
    }
}

@media (max-width: 768px) {
    .container {
        padding: 1rem;
    }
    
    .card {
        margin: 0;
    }
    
    h4 {
        font-size: 1.25rem;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
    
    .d-flex {
        flex-direction: column !important;
    }
    
    .row {
        margin-bottom: 1rem;
    }
    
    .adoption-form-buttons {
        width: 100%;
        justify-content: flex-end !important;
    }
    
    .cancel-btn,
    .submit-btn {
        width: auto;
        min-width: 44px;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
    
    .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 1rem;
    }
}

@media (max-width: 576px) {
    .card-body {
        padding: 1rem;
    }
    
    .form-label {
        font-weight: 600;
    }
    
    .form-text {
        font-size: 0.8rem;
    }
    
    .mt-5 {
        margin-top: 1rem !important;
    }
    
    .adoption-form-buttons {
        flex-direction: column !important;
        align-items: flex-end !important;
    }
    
    .cancel-btn,
    .submit-btn {
        width: 100%;
    }
}

/* Focus indicators for keyboard navigation */
.btn:focus, 
a:focus, 
.form-control:focus, 
.form-select:focus {
    outline: 2px solid #5b4b9b;
    outline-offset: 2px;
}

/* Screen reader only class */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* Improved color contrast */
.text-danger {
    color: #dc3545 !important;
}

.text-muted {
    color: #6c757d !important;
}

/* Form validation improvements */
.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle appointment fields based on checkbox
    const scheduleCheckbox = document.getElementById('schedule_appointment');
    const appointmentFields = document.getElementById('appointment-fields');
    
    if (scheduleCheckbox && appointmentFields) {
        // Show/hide appointment fields based on checkbox state
        scheduleCheckbox.addEventListener('change', function() {
            appointmentFields.style.display = this.checked ? 'block' : 'none';
        });
        
        // If checkbox is already checked (e.g., after validation error), show fields
        if (scheduleCheckbox.checked) {
            appointmentFields.style.display = 'block';
        }
    }
    
    // Set minimum date to current date for appointment scheduling
    const dateInput = document.getElementById('preferred_date');
    if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.min = today;
    }
    
    // Set minimum time based on current date
    const timeInput = document.getElementById('preferred_time');
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