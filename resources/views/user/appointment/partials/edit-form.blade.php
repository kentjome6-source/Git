<form action="{{ route('appointments.update', $appointment) }}" method="POST" id="editAppointmentForm">
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
                <select name="vet_id" id="vet_id" class="form-select" required>
                    <option value="">Select a veterinarian</option>
                    @foreach($vets as $vet)
                        @if($vet->is_verified_vet)
                            <option value="{{ $vet->id }}" {{ $appointment->vet_id == $vet->id ? 'selected' : '' }}>
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
        <div class="col-md-6">
            <div class="mb-2">
                <label for="owner_name" class="form-label">Full Name *</label>
                <input type="text" name="owner_name" id="owner_name" class="form-control" 
                       value="{{ old('owner_name', $appointment->owner_name) }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label for="owner_phone" class="form-label">Phone *</label>
                <input type="tel" name="owner_phone" id="owner_phone" class="form-control" 
                       value="{{ old('owner_phone', $appointment->owner_phone) }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label for="email" class="form-label">Email *</label>
                <input type="email" name="email" id="email" class="form-control" 
                       value="{{ old('email', $appointment->email) }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label for="owner_address" class="form-label">Address</label>
                <input type="text" name="owner_address" id="owner_address" class="form-control" 
                       value="{{ old('owner_address', $appointment->owner_address) }}" placeholder="Enter your full address">
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
                <input type="text" name="pet_name" id="pet_name" class="form-control" 
                       value="{{ old('pet_name', $appointment->pet_name) }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label for="pet_type" class="form-label">Pet Type *</label>
                <select name="pet_type" id="pet_type" class="form-select" required>
                    <option value="">Select pet type</option>
                    <option value="Dog" {{ old('pet_type', $appointment->pet_type) == 'Dog' ? 'selected' : '' }}>Dog</option>
                    <option value="Cat" {{ old('pet_type', $appointment->pet_type) == 'Cat' ? 'selected' : '' }}>Cat</option>
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-2">
                <label for="pet_services_received" class="form-label">Pet Services Received</label>
                <textarea name="pet_services_received" id="pet_services_received" class="form-control" 
                          rows="2" placeholder="Enter services your pet has received (e.g., Deworming, Vaccination, Tick and Flea Prevention)">{{ old('pet_services_received', $appointment->pet_services_received) }}</textarea>
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
                       class="form-control" value="{{ old('preferred_date', $preferred_date) }}" placeholder="Select a date">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label for="preferred_time" class="form-label">Preferred Time</label>
                <input type="time" name="preferred_time" id="preferred_time" 
                       class="form-control" value="{{ old('preferred_time', $preferred_time) }}">
            </div>
        </div>
    </div>
</form>