@extends('layouts.app')

@section('title', $adoption->pet_name . ' - Adoption Details')

@section('content')
<div class="pet-details-page">
    <div class="container-fluid px-4 py-4">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('adoptions.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Adoption Center
            </a>
        </div>

        <div class="row g-4">
            <!-- Left Column - Pet Image and Info -->
            <div class="col-lg-5">
                <!-- Pet Image -->
                <div class="pet-image-card">
                    @if($adoption->image_path)
                        <img src="{{ asset('storage/' . $adoption->image_path) }}" 
                             alt="{{ $adoption->pet_name }}" 
                             class="pet-main-image">
                    @else
                        <img src="{{ asset('images/pawpatrol.jpg') }}" 
                             alt="{{ $adoption->pet_name }}" 
                             class="pet-main-image">
                    @endif
                    
                    <div class="pet-status-overlay">
                        @if($adoption->listing_status === 'published')
                            <span class="badge bg-success">Available for Adoption</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $adoption->listing_status)) }}</span>
                        @endif
                    </div>
                </div>

                <!-- Pet Quick Info -->
                <div class="info-card mt-4">
                    <h5 class="info-card-title">Pet Information</h5>
                    <div class="info-list">
                        @if($adoption->species)
                        <div class="info-item">
                            <span class="info-label">
                                <i class="fas fa-paw"></i>Species
                            </span>
                            <span class="info-value">{{ ucfirst($adoption->species) }}</span>
                        </div>
                        @endif
                        @if($adoption->breed)
                        <div class="info-item">
                            <span class="info-label">
                                <i class="fas fa-tag"></i>Breed
                            </span>
                            <span class="info-value">{{ $adoption->breed }}</span>
                        </div>
                        @endif
                        @if($adoption->age)
                        <div class="info-item">
                            <span class="info-label">
                                <i class="fas fa-birthday-cake"></i>Age
                            </span>
                            <span class="info-value">{{ $adoption->age }} years old</span>
                        </div>
                        @endif
                        @if($adoption->gender)
                        <div class="info-item">
                            <span class="info-label">
                                <i class="fas fa-{{ $adoption->gender === 'male' ? 'mars' : 'venus' }}"></i>Gender
                            </span>
                            <span class="info-value">{{ ucfirst($adoption->gender) }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Owner Info -->
                <div class="info-card mt-4">
                    <h5 class="info-card-title">Pet Owner</h5>
                    <div class="owner-info">
                        <div class="owner-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <p class="owner-name mb-1">{{ $adoption->user->name }}</p>
                            <p class="owner-email mb-0">{{ $adoption->user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Application Form or Details -->
            <div class="col-lg-7">
                <div class="content-card">
                    <div class="pet-header mb-4">
                        <h1 class="pet-title">{{ $adoption->pet_name }}</h1>
                        <p class="text-muted mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Listed {{ $adoption->created_at->diffForHumans() }}
                        </p>
                    </div>

                    @if($adoption->description)
                    <div class="pet-description-section mb-4">
                        <h5 class="section-title">About {{ $adoption->pet_name }}</h5>
                        <p class="pet-description">{{ $adoption->description }}</p>
                    </div>
                    @endif

                    @if($adoption->user_id != auth()->id() && $adoption->listing_status === 'published')
                        @if(!$adoption->adoptionRequests()->where('adopter_id', auth()->id())->exists())
                            <!-- Application Form -->
                            <div class="application-form-section">
                                <div class="form-header mb-4">
                                    <h4 class="form-title">Adoption Application</h4>
                                    <p class="form-subtitle">Please fill out this form to apply for {{ $adoption->pet_name }}</p>
                                </div>

                                <form action="{{ route('adoptions.adopt', $adoption) }}" method="POST" id="adoptionForm">
                                    @csrf
                                    
                                    <!-- Personal Information -->
                                    <div class="form-section">
                                        <h6 class="form-section-title">Personal Information</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Full Name <span class="required">*</span></label>
                                                <input type="text" class="form-control" name="full_name" required value="{{ old('full_name', auth()->user()->name) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Email <span class="required">*</span></label>
                                                <input type="email" class="form-control" name="email" required value="{{ old('email', auth()->user()->email) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Phone Number <span class="required">*</span></label>
                                                <input type="tel" class="form-control" name="phone" required value="{{ old('phone') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Address <span class="required">*</span></label>
                                                <input type="text" class="form-control" name="address" required value="{{ old('address') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Housing Information -->
                                    <div class="form-section">
                                        <h6 class="form-section-title">Housing Information</h6>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Housing Type <span class="required">*</span></label>
                                                <select class="form-select" name="housing_type" required>
                                                    <option value="">Select...</option>
                                                    <option value="house">House</option>
                                                    <option value="apartment">Apartment</option>
                                                    <option value="condo">Condo</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Have a Yard? <span class="required">*</span></label>
                                                <select class="form-select" name="has_yard" required>
                                                    <option value="">Select...</option>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Own or Rent? <span class="required">*</span></label>
                                                <select class="form-select" name="own_or_rent" id="ownOrRent" required>
                                                    <option value="">Select...</option>
                                                    <option value="own">Own</option>
                                                    <option value="rent">Rent</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pet Experience -->
                                    <div class="form-section">
                                        <h6 class="form-section-title">Pet Experience</h6>
                                        <div class="mb-3">
                                            <label class="form-label">Current Pets</label>
                                            <textarea class="form-control" name="current_pets" rows="2" placeholder="Do you currently have any pets? Please describe...">{{ old('current_pets') }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Veterinarian Information</label>
                                            <textarea class="form-control" name="veterinarian_info" rows="2" placeholder="Current vet name and contact (if applicable)">{{ old('veterinarian_info') }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Previous Pet Experience</label>
                                            <textarea class="form-control" name="experience_with_pets" rows="3" placeholder="Tell us about your experience with pets...">{{ old('experience_with_pets') }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Adoption Details -->
                                    <div class="form-section">
                                        <h6 class="form-section-title">Adoption Details</h6>
                                        <div class="mb-3">
                                            <label class="form-label">Why do you want to adopt {{ $adoption->pet_name }}? <span class="required">*</span></label>
                                            <textarea class="form-control" name="reason_for_adoption" rows="3" required placeholder="Tell us why you'd like to adopt this pet...">{{ old('reason_for_adoption') }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Hours Alone Per Day</label>
                                            <input type="number" class="form-control" name="hours_alone" min="0" max="24" value="{{ old('hours_alone', 0) }}">
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="agree_to_home_visit" value="1" id="homeVisit" required>
                                                <label class="form-check-label" for="homeVisit">
                                                    I agree to a home visit if required <span class="required">*</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Additional Information</label>
                                            <textarea class="form-control" name="additional_info" rows="3" placeholder="Any additional information you'd like to share...">{{ old('additional_info') }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Submit Buttons -->
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-paper-plane me-2"></i>Submit Application
                                        </button>
                                        <a href="{{ route('adoptions.index') }}" class="btn btn-outline-secondary btn-lg">
                                            Cancel
                                        </a>
                                    </div>
                                </form>
                            </div>
                        @else
                            <!-- Already Applied -->
                            <div class="alert-card alert-info">
                                <div class="alert-icon">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div>
                                    <h5 class="alert-title">Application Submitted</h5>
                                    <p class="mb-0">You have already submitted an application for {{ $adoption->pet_name }}. Check your applications page to track the status.</p>
                                    <a href="{{ route('adoptions.my-applications') }}" class="btn btn-primary btn-sm mt-3">
                                        <i class="fas fa-clipboard-list me-2"></i>View My Applications
                                    </a>
                                </div>
                            </div>
                        @endif
                    @else
                        @if($adoption->user_id == auth()->id())
                            <!-- Owner View - Show Adoption Progress -->
                            <div class="owner-dashboard-section">
                                <div class="section-header mb-4">
                                    <h4 class="section-title">Your Pet Listing</h4>
                                    <p class="text-muted mb-0">Manage your pet's adoption process</p>
                                </div>

                                <!-- Listing Status -->
                                <div class="status-card mb-4">
                                    <h6 class="status-label">Listing Status</h6>
                                    <div class="status-value">
                                        @if($adoption->listing_status === 'vet_review')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-stethoscope me-1"></i>Awaiting Vet Certification
                                            </span>
                                        @elseif($adoption->listing_status === 'admin_review')
                                            <span class="badge bg-info">
                                                <i class="fas fa-user-shield me-1"></i>Awaiting Admin Approval
                                            </span>
                                        @elseif($adoption->listing_status === 'published')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Published & Available
                                            </span>
                                        @elseif($adoption->is_adopted)
                                            <span class="badge bg-dark">
                                                <i class="fas fa-heart me-1"></i>Adopted
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                {{ ucfirst(str_replace('_', ' ', $adoption->listing_status)) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Adoption Requests -->
                                @php
                                    $activeRequests = $adoption->adoptionRequests()
                                        ->with('adopter')
                                        ->whereIn('status', ['pending', 'screened', 'oriented', 'owner_review', 'owner_approved', 'approved'])
                                        ->latest()
                                        ->get();
                                @endphp

                                @if($activeRequests->count() > 0)
                                    <div class="requests-section">
                                        <h5 class="section-title mb-3">
                                            <i class="fas fa-clipboard-list me-2"></i>Adoption Applications
                                        </h5>

                                        @foreach($activeRequests as $request)
                                            <div class="request-card mb-3">
                                                <div class="request-header">
                                                    <div class="applicant-info">
                                                        <div class="applicant-avatar">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="applicant-name mb-1">{{ $request->adopter->name }}</h6>
                                                            <p class="applicant-email mb-0">{{ $request->adopter->email }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="request-status-badge">
                                                        @if($request->status === 'pending')
                                                            <span class="badge bg-warning">Pending Screening</span>
                                                        @elseif($request->status === 'screened')
                                                            <span class="badge bg-info">Screened</span>
                                                        @elseif($request->status === 'oriented')
                                                            <span class="badge bg-primary">Orientation Complete</span>
                                                        @elseif($request->status === 'owner_review')
                                                            <span class="badge bg-warning">Awaiting Your Review</span>
                                                        @elseif($request->status === 'owner_approved')
                                                            <span class="badge bg-success">You Approved</span>
                                                        @elseif($request->status === 'approved')
                                                            <span class="badge bg-success">Approved - Ready</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="request-progress mt-3">
                                                    <div class="progress-steps">
                                                        <div class="progress-step {{ in_array($request->status, ['pending', 'screened', 'oriented', 'owner_review', 'owner_approved', 'approved']) ? 'completed' : '' }}">
                                                            <div class="step-icon"><i class="fas fa-file-alt"></i></div>
                                                            <div class="step-label">Applied</div>
                                                        </div>
                                                        <div class="progress-line {{ in_array($request->status, ['screened', 'oriented', 'owner_review', 'owner_approved', 'approved']) ? 'completed' : '' }}"></div>
                                                        <div class="progress-step {{ in_array($request->status, ['screened', 'oriented', 'owner_review', 'owner_approved', 'approved']) ? 'completed' : '' }}">
                                                            <div class="step-icon"><i class="fas fa-user-check"></i></div>
                                                            <div class="step-label">Screened</div>
                                                        </div>
                                                        <div class="progress-line {{ in_array($request->status, ['oriented', 'owner_review', 'owner_approved', 'approved']) ? 'completed' : '' }}"></div>
                                                        <div class="progress-step {{ in_array($request->status, ['oriented', 'owner_review', 'owner_approved', 'approved']) ? 'completed' : '' }}">
                                                            <div class="step-icon"><i class="fas fa-graduation-cap"></i></div>
                                                            <div class="step-label">Oriented</div>
                                                        </div>
                                                        <div class="progress-line {{ in_array($request->status, ['owner_approved', 'approved']) ? 'completed' : '' }}"></div>
                                                        <div class="progress-step {{ in_array($request->status, ['owner_approved', 'approved']) ? 'completed' : '' }}">
                                                            <div class="step-icon"><i class="fas fa-check-circle"></i></div>
                                                            <div class="step-label">Owner Approved</div>
                                                        </div>
                                                        <div class="progress-line {{ $request->status === 'approved' ? 'completed' : '' }}"></div>
                                                        <div class="progress-step {{ $request->status === 'approved' ? 'completed' : '' }}">
                                                            <div class="step-icon"><i class="fas fa-heart"></i></div>
                                                            <div class="step-label">Final Approval</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if($request->status === 'owner_review')
                                                    <div class="request-actions mt-3">
                                                        <button onclick="approveOwner({{ $request->id }})" class="btn btn-success">
                                                            <i class="fas fa-check me-2"></i>Approve Applicant
                                                        </button>
                                                        <button onclick="rejectOwner({{ $request->id }})" class="btn btn-outline-danger">
                                                            <i class="fas fa-times me-2"></i>Reject
                                                        </button>
                                                        <a href="{{ route('messages.index', ['user' => $request->adopter_id]) }}" class="btn btn-outline-primary">
                                                            <i class="fas fa-envelope me-2"></i>Message
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="request-actions mt-3">
                                                        <a href="{{ route('messages.index', ['user' => $request->adopter_id]) }}" class="btn btn-outline-primary btn-sm">
                                                            <i class="fas fa-envelope me-2"></i>Message Applicant
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert-card alert-info">
                                        <div class="alert-icon">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                        <div>
                                            <h5 class="alert-title">No Applications Yet</h5>
                                            <p class="mb-0">No one has applied to adopt {{ $adoption->pet_name }} yet. Applications will appear here when users submit them.</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Edit/Delete Actions -->
                                @if($adoption->listing_status !== 'published' && !$adoption->is_adopted)
                                    <div class="owner-actions mt-4">
                                        <a href="{{ route('adoptions.edit', $adoption) }}" class="btn btn-primary">
                                            <i class="fas fa-edit me-2"></i>Edit Listing
                                        </a>
                                        <form action="{{ route('adoptions.destroy', $adoption) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this listing?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="fas fa-trash me-2"></i>Delete Listing
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- Not Available -->
                            <div class="alert-card alert-warning">
                                <div class="alert-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <h5 class="alert-title">Not Available</h5>
                                    <p class="mb-0">This pet is currently not available for adoption.</p>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary: #2563eb;
    --success: #10b981;
    --warning: #f59e0b;
    --info: #3b82f6;
    --dark: #1e293b;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-600: #475569;
    --gray-700: #334155;
    --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
}

.pet-details-page {
    background: var(--gray-50);
    min-height: 100vh;
    padding-bottom: 2rem;
}

/* Pet Image Card */
.pet-image-card {
    position: relative;
    background: white;
    border-radius: 0.75rem;
    overflow: hidden;
    box-shadow: var(--shadow-md);
    animation: fadeIn 0.5s ease-out;
}

.pet-main-image {
    width: 100%;
    height: 500px;
    object-fit: cover;
}

.pet-status-overlay {
    position: absolute;
    top: 1rem;
    right: 1rem;
}

.pet-status-overlay .badge {
    padding: 0.5rem 1rem;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

/* Info Card */
.info-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: var(--shadow);
    animation: fadeInUp 0.6s ease-out;
}

.info-card-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--gray-200);
}

.info-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
}

.info-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: var(--gray-700);
    font-size: 0.9375rem;
}

.info-label i {
    color: var(--primary);
    width: 20px;
}

.info-value {
    color: var(--gray-600);
    font-size: 0.9375rem;
}

/* Owner Info */
.owner-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.owner-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.owner-name {
    font-weight: 600;
    color: var(--dark);
    font-size: 1rem;
}

.owner-email {
    color: var(--gray-600);
    font-size: 0.875rem;
}

/* Content Card */
.content-card {
    background: white;
    border-radius: 0.75rem;
    padding: 2rem;
    box-shadow: var(--shadow-md);
    animation: fadeInUp 0.5s ease-out;
}

.pet-header {
    padding-bottom: 1.5rem;
    border-bottom: 2px solid var(--gray-200);
}

.pet-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.pet-description-section {
    padding: 1.5rem 0;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 1rem;
}

.pet-description {
    font-size: 1rem;
    color: var(--gray-600);
    line-height: 1.7;
}

/* Form Sections */
.application-form-section {
    padding-top: 1.5rem;
}

.form-header {
    padding-bottom: 1.5rem;
    border-bottom: 2px solid var(--gray-200);
}

.form-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
}

.form-subtitle {
    color: var(--gray-600);
    margin: 0;
}

.form-section {
    padding: 1.5rem 0;
    border-bottom: 1px solid var(--gray-200);
}

.form-section:last-of-type {
    border-bottom: none;
}

.form-section-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 1rem;
}

.form-label {
    font-weight: 600;
    color: var(--gray-700);
    font-size: 0.9375rem;
    margin-bottom: 0.5rem;
}

.required {
    color: var(--danger);
}

.form-control, .form-select {
    border: 1px solid var(--gray-300);
    border-radius: 0.5rem;
    padding: 0.625rem 0.875rem;
    font-size: 0.9375rem;
    transition: all 0.2s;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-actions {
    display: flex;
    gap: 1rem;
    padding-top: 2rem;
}

/* Alert Cards */
.alert-card {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
    border-radius: 0.75rem;
    border: 1px solid;
}

.alert-card.alert-info {
    background: #eff6ff;
    border-color: #bfdbfe;
}

.alert-card.alert-warning {
    background: #fef3c7;
    border-color: #fde68a;
}

.alert-icon {
    font-size: 2rem;
}

.alert-card.alert-info .alert-icon {
    color: var(--info);
}

.alert-card.alert-warning .alert-icon {
    color: var(--warning);
}

.alert-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 0.5rem;
}

/* Owner Dashboard Styles */
.owner-dashboard-section {
    animation: fadeInUp 0.6s ease-out;
}

.status-card {
    background: var(--gray-50);
    border-radius: 0.5rem;
    padding: 1rem;
    border-left: 4px solid var(--primary);
}

.status-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-600);
    text-transform: uppercase;
    margin-bottom: 0.5rem;
}

.status-value {
    font-size: 1rem;
}

.requests-section {
    margin-top: 2rem;
}

.request-card {
    background: var(--gray-50);
    border-radius: 0.75rem;
    padding: 1.5rem;
    border: 1px solid var(--gray-200);
    transition: all 0.3s;
}

.request-card:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--primary);
}

.request-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.applicant-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.applicant-avatar {
    width: 50px;
    height: 50px;
    background: var(--primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.applicant-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--dark);
}

.applicant-email {
    font-size: 0.875rem;
    color: var(--gray-600);
}

.request-status-badge .badge {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

.request-progress {
    margin-top: 1.5rem;
}

.progress-steps {
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
}

.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    position: relative;
    z-index: 2;
}

.step-icon {
    width: 40px;
    height: 40px;
    background: var(--gray-200);
    color: var(--gray-600);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    transition: all 0.3s;
}

.progress-step.completed .step-icon {
    background: var(--primary);
    color: white;
}

.step-label {
    font-size: 0.75rem;
    color: var(--gray-600);
    text-align: center;
    max-width: 80px;
}

.progress-step.completed .step-label {
    color: var(--primary);
    font-weight: 600;
}

.progress-line {
    flex: 1;
    height: 2px;
    background: var(--gray-200);
    margin: 0 0.5rem;
    position: relative;
    top: -20px;
}

.progress-line.completed {
    background: var(--primary);
}

.request-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.owner-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 991px) {
    .pet-main-image {
        height: 400px;
    }
    
    .pet-title {
        font-size: 2rem;
    }
    
    .content-card {
        padding: 1.5rem;
    }
}

@media (max-width: 768px) {
    .pet-main-image {
        height: 300px;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions .btn {
        width: 100%;
    }
    
    .progress-steps {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .progress-line {
        display: none;
    }
    
    .progress-step {
        flex-direction: row;
        width: 100%;
    }
    
    .step-label {
        text-align: left;
        max-width: none;
    }
    
    .request-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .request-actions {
        width: 100%;
    }
    
    .request-actions .btn {
        flex: 1;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ownOrRent = document.getElementById('ownOrRent');
    const landlordField = document.getElementById('landlordField');
    const landlordApproval = document.getElementById('landlordApproval');
    
    if (ownOrRent) {
        ownOrRent.addEventListener('change', function() {
            if (this.value === 'rent') {
                landlordField.style.display = 'block';
                landlordApproval.required = true;
            } else {
                landlordField.style.display = 'none';
                landlordApproval.required = false;
                landlordApproval.value = '';
            }
        });
    }
});

function approveOwner(requestId) {
    Swal.fire({
        title: 'Approve Application?',
        text: 'Are you sure you want to approve this adoption application?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Approve',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/adoption-requests/${requestId}/owner-approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Approved!',
                        text: data.message,
                        icon: 'success'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'An error occurred while processing your request.', 'error');
            });
        }
    });
}

function rejectOwner(requestId) {
    Swal.fire({
        title: 'Reject Application?',
        text: 'Are you sure you want to reject this adoption application?',
        icon: 'warning',
        input: 'textarea',
        inputPlaceholder: 'Reason for rejection (optional)',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Reject',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/adoption-requests/${requestId}/owner-reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    rejection_reason: result.value || 'No reason provided'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Rejected',
                        text: data.message,
                        icon: 'success'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'An error occurred while processing your request.', 'error');
            });
        }
    });
}
</script>
@endsection
