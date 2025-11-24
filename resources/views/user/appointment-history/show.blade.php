@extends('layouts.app')

@section('title', 'Appointment Details')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-column flex-md-row gap-3">
                <h2><i class="fas fa-stethoscope me-2"></i>Appointment Details</h2>
                <!-- Back button moved to bottom -->
            </div>

            <!-- Main Card -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Appointment Information</h5>
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
                                    'rejected' => 'danger',
                                    'cancelled' => 'secondary',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">
                                {{ $statusDisplay }}
                            </span>
                            @if($appointment->status === 'rejected' && $appointment->rejection_reason)
                                <div class="mt-2">
                                    <strong>Rejection Reason:</strong>
                                    <div class="alert alert-dark mt-1 mb-0">{{ $appointment->rejection_reason }}</div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <strong>Date:</strong> {{ $appointment->created_at->format('M d, Y') }}
                        </div>
                    </div>

                    <!-- Pet Information -->
                    <h6><i class="fas fa-paw me-2"></i>Pet Information</h6>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <p><strong>Pet Name:</strong> {{ $appointment->pet_name }}</p>
                            <p><strong>Pet Type:</strong> {{ $appointment->pet_type }}</p>
                            <p><strong>Pet Services Received:</strong> {{ $appointment->pet_services_received }}</p>
                        </div>
                    </div>

                    <!-- Scheduling -->
                    <h6><i class="fas fa-calendar-alt me-2"></i>Scheduling</h6>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-4 mb-md-0">
                            @if($appointment->appointment_date)
                                <p><strong>Preferred Appointment Date:</strong> {{ $appointment->appointment_date->format('M d, Y') }}</p>
                            @endif
                            
                            @if($appointment->appointment_time)
                                <p><strong>Preferred Appointment Time:</strong> {{ date('g:i A', strtotime($appointment->appointment_time)) }}</p>
                            @endif
                            
                            @if($appointment->scheduled_datetime)
                                <p><strong>Scheduled Date & Time:</strong> {{ $appointment->scheduled_datetime->format('M d, Y g:i A') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Veterinary Information -->
            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-user-md me-2"></i>Veterinary Information</h6>
                </div>
                <div class="card-body">
                    @if($appointment->vet)
                        <p><strong>Veterinarian:</strong> Dr. {{ $appointment->vet->name }}</p>
                        <p><strong>Email:</strong> {{ $appointment->vet->email }}</p>
                        
                        @if($appointment->vet_notes)
                            <p><strong>Vet Notes:</strong> {{ $appointment->vet_notes }}</p>
                        @endif
                        
                        @if($appointment->diagnosis)
                            <p><strong>Diagnosis:</strong> {{ $appointment->diagnosis }}</p>
                        @endif
                        
                        @if($appointment->treatment_plan)
                            <p><strong>Treatment Plan:</strong> {{ $appointment->treatment_plan }}</p>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-hourglass-half me-2"></i>
                            Your appointment request is pending review. A veterinarian will be assigned soon.
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Back Button at Bottom Right -->
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('appointments.history') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to History
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Pet parent purple theme for appointment headers */
.card-header.bg-primary {
    background-color: #5b4b9b !important;
}

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
    
    /* Keep button on the right side on mobile */
    .d-flex.justify-content-end {
        justify-content: flex-end !important;
    }
    
    .d-flex .btn {
        width: auto;
        justify-content: flex-start;
    }
    
    .badge {
        font-size: 0.8rem;
        padding: 0.4em 0.6em;
    }
    
    hr {
        margin: 1rem 0;
    }
}
</style>
@endsection