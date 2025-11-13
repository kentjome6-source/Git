@extends(auth()->user()->role === 'vet' ? 'layouts.vet' : 'layouts.app')

@section('title', 'Appointment')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-column flex-md-row gap-3">
                <h2><i class="fas fa-stethoscope me-2"></i>Appointment</h2>
                <div class="d-flex gap-2 flex-wrap">
                    @if($appointment->status === 'pending' && $appointment->user_id === auth()->id())
                        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit Request
                        </a>
                    @endif
                    <a href="{{ auth()->user()->role === 'vet' ? route('vet.appointments') : route('appointments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>

            <!-- Main Card -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Appointment Details</h5>
                </div>
                <div class="card-body">
                    <!-- Status Info -->
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <strong>Status:</strong>
                            @php
                                // Map statuses for pet parents view
                                $statusDisplay = match($appointment->status) {
                                    'pending' => 'Pending Review',
                                    'accepted' => 'Accepted',
                                    'rejected' => 'Rejected',
                                    'cancelled' => 'Cancelled',
                                    default => ucfirst($appointment->status)
                                };
                                
                                // Map background classes for pet parents view
                                $statusClass = match($appointment->status) {
                                    'pending' => 'warning',
                                    'accepted' => 'success',
                                    'rejected' => 'dark',
                                    'cancelled' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">
                                {{ $statusDisplay }}
                            </span>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <strong>Priority:</strong> {{ ucfirst($appointment->urgency_level) }}
                        </div>
                        <div class="col-md-4">
                            <strong>Date:</strong> {{ $appointment->created_at->format('M d, Y') }}
                        </div>
                    </div>

                    <!-- Owner & Pet Information -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <h6><i class="fas fa-user me-2"></i>Owner Information</h6>
                            <p><strong>Name:</strong> {{ $appointment->owner_name }}</p>
                            <p><strong>Email:</strong> {{ $appointment->owner_email }}</p>
                            <p><strong>Phone:</strong> {{ $appointment->owner_phone }}</p>
                            @if($appointment->owner_address)
                                <p><strong>Address:</strong> {{ $appointment->owner_address }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-paw me-2"></i>Pet Information</h6>
                            <p><strong>Name:</strong> {{ $appointment->pet_name }}</p>
                            <p><strong>Species:</strong> {{ $appointment->pet_species }}</p>
                            @if($appointment->pet_breed)
                                <p><strong>Breed:</strong> {{ $appointment->pet_breed }}</p>
                            @endif
                            @if($appointment->pet_weight)
                                <p><strong>Weight:</strong> {{ $appointment->pet_weight }} kg</p>
                            @endif
                            @if($appointment->pet_gender)
                                <p><strong>Gender:</strong> {{ ucfirst($appointment->pet_gender) }}</p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <!-- Appointment Details -->
                    <h6><i class="fas fa-notes-medical me-2"></i>Appointment Details</h6>
                    <p><strong>Chief Complaint:</strong> {{ $appointment->chief_complaint }}</p>
                    <p><strong>Symptoms:</strong> {{ $appointment->detailed_symptoms }}</p>
                    
                    @if($appointment->appointment_date)
                        <p><strong>Preferred Appointment Date:</strong> {{ $appointment->appointment_date->format('M d, Y') }}</p>
                    @endif
                    
                    @if($appointment->appointment_time)
                        <p><strong>Preferred Appointment Time:</strong> {{ date('g:i A', strtotime($appointment->appointment_time)) }}</p>
                    @endif
                    
                    @if($appointment->scheduled_datetime)
                        <p><strong>Scheduled Date & Time:</strong> {{ $appointment->scheduled_datetime->format('M d, Y g:i A') }}</p>
                    @endif
                    
                    @if($appointment->additional_concerns)
                        <p><strong>Additional Concerns:</strong> {{ $appointment->additional_concerns }}</p>
                    @endif

                    @if($appointment->current_medications)
                        <p><strong>Current Medications:</strong> {{ $appointment->current_medications }}</p>
                    @endif
                    
                    @if($appointment->allergies)
                        <p><strong>Allergies:</strong> <span class="text-danger">{{ $appointment->allergies }}</span></p>
                    @endif

                    <!-- Action Buttons -->
                    @if(auth()->user()->role !== 'vet')
                        <div class="mt-4">
                            @if($appointment->status === 'pending' && $appointment->user_id === auth()->id())
                                <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this appointment request?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash me-2"></i>Cancel Request
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Veterinary Information -->
            @if(auth()->user()->role !== 'vet' && ($appointment->vet || $appointment->vet_notes || $appointment->diagnosis || $appointment->treatment_plan))
                <div class="card mt-4">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fas fa-user-md me-2"></i>Veterinary Information</h6>
                    </div>
                    <div class="card-body">
                        @if($appointment->vet)
                            <p><strong>Veterinarian:</strong> Dr. {{ $appointment->vet->name }}</p>
                            <p><strong>Email:</strong> {{ $appointment->vet->email }}</p>
                            
                            @if($appointment->vet_notes)
                                <p><strong>Vet Notes:</strong> {{ $appointment->vet_notes }}</p>
                            @endif
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-hourglass-half me-2"></i>
                                Your appointment request is pending review. A veterinarian will be assigned soon.
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            
            <!-- Show veterinarian information for the assigned vet -->
            @if(auth()->user()->role === 'vet' && auth()->id() === $appointment->vet_id)
                <div class="card mt-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-user me-2"></i>Pet Owner Information</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Owner Name:</strong> {{ $appointment->owner_name }}</p>
                        <p><strong>Email:</strong> {{ $appointment->owner_email }}</p>
                        <p><strong>Phone:</strong> {{ $appointment->owner_phone }}</p>
                        @if($appointment->owner_address)
                            <p><strong>Address:</strong> {{ $appointment->owner_address }}</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Responsive styles */
@media (max-width: 768px) {
    .container-fluid {
        padding: 10px;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .card-header h5 {
        font-size: 1.1rem;
    }
    
    h6 {
        font-size: 1rem;
        margin-bottom: 0.75rem;
    }
    
    p {
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    
    .d-flex {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    
    .d-flex .btn {
        width: 100%;
        justify-content: center;
    }
    
    .badge {
        font-size: 0.8rem;
        padding: 0.4em 0.6em;
    }
    
    hr {
        margin: 1rem 0;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding: 0.5rem;
    }
    
    .card {
        margin-bottom: 1rem;
    }
    
    .card-header {
        padding: 0.75rem 1rem;
    }
    
    .card-body {
        padding: 0.75rem;
    }
    
    h2 {
        font-size: 1.25rem;
        text-align: center;
    }
    
    h6 {
        font-size: 0.95rem;
    }
    
    p {
        font-size: 0.85rem;
    }
    
    .btn {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    
    .alert {
        padding: 0.75rem;
        font-size: 0.85rem;
    }
}

/* Extra small devices */
@media (max-width: 400px) {
    .card-body {
        padding: 0.5rem;
    }
    
    h2 {
        font-size: 1.1rem;
    }
    
    h6 {
        font-size: 0.9rem;
    }
    
    p {
        font-size: 0.8rem;
    }
    
    .btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.8rem;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.3em 0.5em;
    }
}
</style>
@endsection