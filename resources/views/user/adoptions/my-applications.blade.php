@extends('layouts.app')

@section('title', 'My Adoption Applications')

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center mx-0">
        <div class="col-12 col-lg-10 px-0">
            <!-- Header Section -->
            <div class="row mb-4 mx-0 mx-md-2">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h2 class="mb-1">My Adoption Applications</h2>
                            <p class="text-muted mb-0">Track the status of your adoption applications</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('adoptions.index') }}" class="btn btn-primary">
                                <i class="fas fa-heart me-2"></i>Browse Pets
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-2 mx-md-2" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mx-2 mx-md-2" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Applications List -->
            <div class="row mx-0 mx-md-2">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            @if($applications->count() > 0)
                                @foreach($applications as $application)
                                    <div class="card mb-3 border shadow-sm">
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Pet Image -->
                                                <div class="col-md-3">
                                                    @if($application->adoption->image_path)
                                                        <img src="{{ asset('storage/' . $application->adoption->image_path) }}" 
                                                             class="img-fluid rounded" 
                                                             alt="{{ $application->adoption->pet_name }}"
                                                             style="width: 100%; height: 200px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                                             style="width: 100%; height: 200px;">
                                                            <i class="fas fa-paw fa-3x text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Application Details -->
                                                <div class="col-md-9">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <div>
                                                            <h4 class="mb-1">{{ $application->adoption->pet_name }}</h4>
                                                            <p class="text-muted mb-0">
                                                                {{ $application->adoption->breed ?? 'Mixed Breed' }} • 
                                                                {{ $application->adoption->age ?? 'Age unknown' }} years old
                                                            </p>
                                                        </div>
                                                        <div>
                                                            @php
                                                                $statusClass = 'secondary';
                                                                $statusIcon = 'fas fa-clock';
                                                                $statusText = 'Pending';
                                                                
                                                                switch($application->status) {
                                                                    case 'pending':
                                                                        $statusClass = 'warning';
                                                                        $statusIcon = 'fas fa-clock';
                                                                        $statusText = 'Admin Screening';
                                                                        break;
                                                                    case 'admin_screening':
                                                                        $statusClass = 'info';
                                                                        $statusIcon = 'fas fa-user-check';
                                                                        $statusText = 'Under Admin Review';
                                                                        break;
                                                                    case 'vet_orientation':
                                                                        $statusClass = 'primary';
                                                                        $statusIcon = 'fas fa-chalkboard-teacher';
                                                                        $statusText = 'Vet Orientation';
                                                                        break;
                                                                    case 'owner_review':
                                                                        $statusClass = 'info';
                                                                        $statusIcon = 'fas fa-user-clock';
                                                                        $statusText = 'Owner Review';
                                                                        break;
                                                                    case 'approved':
                                                                        $statusClass = 'success';
                                                                        $statusIcon = 'fas fa-check-circle';
                                                                        $statusText = 'Approved';
                                                                        break;
                                                                    case 'rejected':
                                                                        $statusClass = 'danger';
                                                                        $statusIcon = 'fas fa-times-circle';
                                                                        $statusText = 'Rejected';
                                                                        break;
                                                                    case 'completed':
                                                                        $statusClass = 'success';
                                                                        $statusIcon = 'fas fa-heart';
                                                                        $statusText = 'Adopted';
                                                                        break;
                                                                }
                                                            @endphp
                                                            <span class="badge bg-{{ $statusClass }} fs-6">
                                                                <i class="{{ $statusIcon }} me-1"></i>{{ $statusText }}
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <!-- Application Timeline -->
                                                    <div class="timeline-container mb-3">
                                                        <h6 class="fw-bold mb-2">Application Progress:</h6>
                                                        <div class="progress mb-2" style="height: 8px;">
                                                            @php
                                                                $progress = 20;
                                                                switch($application->status) {
                                                                    case 'pending': $progress = 25; break;
                                                                    case 'admin_screening': $progress = 40; break;
                                                                    case 'vet_orientation': $progress = 60; break;
                                                                    case 'owner_review': $progress = 75; break;
                                                                    case 'approved': $progress = 90; break;
                                                                    case 'completed': $progress = 100; break;
                                                                    case 'rejected': $progress = 100; break;
                                                                }
                                                            @endphp
                                                            <div class="progress-bar bg-{{ $statusClass }}" 
                                                                 role="progressbar" 
                                                                 style="width: {{ $progress }}%" 
                                                                 aria-valuenow="{{ $progress }}" 
                                                                 aria-valuemin="0" 
                                                                 aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="small text-muted">
                                                            @if($application->status == 'pending')
                                                                <i class="fas fa-info-circle text-warning me-1"></i>
                                                                Your adoption request is in process. The admin will screen your application.
                                                            @elseif($application->status == 'admin_screening')
                                                                <i class="fas fa-spinner fa-spin text-info me-1"></i>
                                                                Admin is reviewing your application.
                                                            @elseif($application->status == 'vet_orientation')
                                                                <i class="fas fa-stethoscope text-primary me-1"></i>
                                                                Waiting for veterinarian orientation. You will be contacted soon.
                                                            @elseif($application->status == 'owner_review')
                                                                <i class="fas fa-user-clock text-info me-1"></i>
                                                                Pet owner is reviewing your application.
                                                            @elseif($application->status == 'approved')
                                                                <i class="fas fa-check-circle text-success me-1"></i>
                                                                Congratulations! Your application has been approved. Final steps in progress.
                                                            @elseif($application->status == 'completed')
                                                                <i class="fas fa-heart text-success me-1"></i>
                                                                Adoption completed! Enjoy your new companion.
                                                            @elseif($application->status == 'rejected')
                                                                <i class="fas fa-times-circle text-danger me-1"></i>
                                                                Application was not approved.
                                                                @if($application->admin_screening_rejection)
                                                                    <br><strong>Reason:</strong> {{ $application->admin_screening_rejection }}
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Application Info -->
                                                    <div class="row g-2">
                                                        <div class="col-md-6">
                                                            <small class="text-muted d-block">Applied On</small>
                                                            <strong>{{ $application->created_at->format('M d, Y') }}</strong>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <small class="text-muted d-block">Pet Owner</small>
                                                            <strong>{{ $application->adoption->user->name }}</strong>
                                                        </div>
                                                    </div>

                                                    @if($application->admin_screening_notes)
                                                        <div class="alert alert-info mt-3 mb-0">
                                                            <strong>Admin Notes:</strong> {{ $application->admin_screening_notes }}
                                                        </div>
                                                    @endif

                                                    @if($application->vet_orientation_notes)
                                                        <div class="alert alert-success mt-3 mb-0">
                                                            <strong>Vet Notes:</strong> {{ $application->vet_orientation_notes }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No Applications Yet</h5>
                                    <p class="text-muted">You haven't applied to adopt any pets yet.</p>
                                    <a href="{{ route('adoptions.index') }}" class="btn btn-primary">
                                        <i class="fas fa-heart me-2"></i>Browse Available Pets
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
