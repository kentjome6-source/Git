@extends('layouts.app')

@section('title', 'My Adoption Applications')

@section('content')
<div class="applications-page">
    <div class="container-fluid px-4 py-4">
        <!-- Header -->
        <div class="page-header mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="page-title mb-1">My Adoption Applications</h1>
                    <p class="page-subtitle text-muted mb-0">Track the status of your applications</p>
                </div>
                <a href="{{ route('adoptions.index') }}" class="btn btn-primary">
                    <i class="fas fa-heart me-2"></i>Browse Pets
                </a>
            </div>
        </div>

        @if($applications->count() > 0)
        <!-- Applications Grid -->
        <div class="row g-4">
            @foreach($applications as $application)
            <div class="col-lg-6 col-md-12">
                <div class="application-card">
                    <div class="row g-0">
                        <!-- Pet Image -->
                        <div class="col-md-4">
                            <div class="application-image">
                                @if($application->adoption->image_path)
                                    <img src="{{ asset('storage/' . $application->adoption->image_path) }}" 
                                         alt="{{ $application->adoption->pet_name }}"
                                         loading="lazy">
                                @else
                                    <img src="{{ asset('images/pawpatrol.jpg') }}" 
                                         alt="{{ $application->adoption->pet_name }}">
                                @endif
                            </div>
                        </div>

                        <!-- Application Details -->
                        <div class="col-md-8">
                            <div class="application-content">
                                <!-- Header -->
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="application-pet-name mb-1">{{ $application->adoption->pet_name }}</h5>
                                        <p class="text-muted mb-0 small">Applied {{ $application->created_at->diffForHumans() }}</p>
                                    </div>
                                    @php
                                        $statusConfig = [
                                            'pending' => ['badge' => 'warning', 'icon' => 'clock', 'text' => 'Pending Review'],
                                            'admin_screening' => ['badge' => 'info', 'icon' => 'user-shield', 'text' => 'Admin Screening'],
                                            'vet_orientation' => ['badge' => 'info', 'icon' => 'stethoscope', 'text' => 'Vet Orientation'],
                                            'owner_review' => ['badge' => 'info', 'icon' => 'user-check', 'text' => 'Owner Review'],
                                            'approved' => ['badge' => 'success', 'icon' => 'check-circle', 'text' => 'Approved'],
                                            'rejected' => ['badge' => 'danger', 'icon' => 'times-circle', 'text' => 'Rejected'],
                                            'completed' => ['badge' => 'success', 'icon' => 'check-double', 'text' => 'Completed'],
                                        ];
                                        $config = $statusConfig[$application->status] ?? ['badge' => 'secondary', 'icon' => 'question', 'text' => ucfirst($application->status)];
                                    @endphp
                                    <span class="badge bg-{{ $config['badge'] }} status-badge">
                                        <i class="fas fa-{{ $config['icon'] }} me-1"></i>
                                        {{ $config['text'] }}
                                    </span>
                                </div>

                                <!-- Pet Info -->
                                <div class="application-meta mb-3">
                                    @if($application->adoption->breed)
                                    <span class="meta-item">
                                        <i class="fas fa-tag"></i>
                                        {{ $application->adoption->breed }}
                                    </span>
                                    @endif
                                    @if($application->adoption->age)
                                    <span class="meta-item">
                                        <i class="fas fa-birthday-cake"></i>
                                        {{ $application->adoption->age }} years
                                    </span>
                                    @endif
                                </div>

                                <!-- Progress Bar -->
                                <div class="progress-section mb-3">
                                    <div class="progress-steps">
                                        <div class="step {{ in_array($application->status, ['pending', 'admin_screening', 'vet_orientation', 'owner_review', 'approved', 'completed']) ? 'active' : '' }}">
                                            <div class="step-icon">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
                                        </div>
                                        <div class="step-line {{ in_array($application->status, ['admin_screening', 'vet_orientation', 'owner_review', 'approved', 'completed']) ? 'active' : '' }}"></div>
                                        <div class="step {{ in_array($application->status, ['admin_screening', 'vet_orientation', 'owner_review', 'approved', 'completed']) ? 'active' : '' }}">
                                            <div class="step-icon">
                                                <i class="fas fa-user-shield"></i>
                                            </div>
                                        </div>
                                        <div class="step-line {{ in_array($application->status, ['vet_orientation', 'owner_review', 'approved', 'completed']) ? 'active' : '' }}"></div>
                                        <div class="step {{ in_array($application->status, ['vet_orientation', 'owner_review', 'approved', 'completed']) ? 'active' : '' }}">
                                            <div class="step-icon">
                                                <i class="fas fa-stethoscope"></i>
                                            </div>
                                        </div>
                                        <div class="step-line {{ in_array($application->status, ['owner_review', 'approved', 'completed']) ? 'active' : '' }}"></div>
                                        <div class="step {{ in_array($application->status, ['owner_review', 'approved', 'completed']) ? 'active' : '' }}">
                                            <div class="step-icon">
                                                <i class="fas fa-user-check"></i>
                                            </div>
                                        </div>
                                        <div class="step-line {{ in_array($application->status, ['approved', 'completed']) ? 'active' : '' }}"></div>
                                        <div class="step {{ in_array($application->status, ['approved', 'completed']) ? 'active' : '' }}">
                                            <div class="step-icon">
                                                <i class="fas fa-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Message -->
                                <div class="status-message">
                                    @if($application->status === 'pending')
                                        <p class="mb-0 small text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Your application is being reviewed by the admin.
                                        </p>
                                    @elseif($application->status === 'admin_screening')
                                        <p class="mb-0 small text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Admin is screening your application.
                                        </p>
                                    @elseif($application->status === 'vet_orientation')
                                        <p class="mb-0 small text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Vet orientation is in progress.
                                        </p>
                                    @elseif($application->status === 'owner_review')
                                        <p class="mb-0 small text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Pet owner is reviewing your application.
                                        </p>
                                    @elseif($application->status === 'approved')
                                        <p class="mb-0 small text-success">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Congratulations! Your application has been approved.
                                        </p>
                                    @elseif($application->status === 'rejected')
                                        <p class="mb-0 small text-danger">
                                            <i class="fas fa-times-circle me-1"></i>
                                            Unfortunately, your application was not approved.
                                        </p>
                                    @elseif($application->status === 'completed')
                                        <p class="mb-0 small text-success">
                                            <i class="fas fa-check-double me-1"></i>
                                            Adoption completed! Enjoy your time with {{ $application->adoption->pet_name }}.
                                        </p>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                @if($application->status === 'approved')
                                <div class="application-actions mt-3">
                                    <button class="btn btn-success btn-sm w-100">
                                        <i class="fas fa-calendar-check me-2"></i>Schedule Finalization
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h3 class="empty-state-title">No Applications Yet</h3>
            <p class="empty-state-text">
                You haven't submitted any adoption applications yet.<br>
                Browse available pets and start your adoption journey!
            </p>
            <a href="{{ route('adoptions.index') }}" class="btn btn-primary mt-3">
                <i class="fas fa-heart me-2"></i>Browse Available Pets
            </a>
        </div>
        @endif
    </div>
</div>

<style>
:root {
    --primary: #2563eb;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
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

.applications-page {
    background: var(--gray-50);
    min-height: 100vh;
    padding-bottom: 2rem;
}

.page-header {
    animation: fadeInDown 0.5s ease-out;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--dark);
}

.page-subtitle {
    font-size: 1rem;
    color: var(--gray-600);
}

/* Application Cards */
.application-card {
    background: white;
    border-radius: 0.75rem;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    animation: fadeInUp 0.5s ease-out backwards;
    height: 100%;
}

.application-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.col-lg-6:nth-child(1) .application-card { animation-delay: 0.05s; }
.col-lg-6:nth-child(2) .application-card { animation-delay: 0.1s; }
.col-lg-6:nth-child(3) .application-card { animation-delay: 0.15s; }
.col-lg-6:nth-child(4) .application-card { animation-delay: 0.2s; }
.col-lg-6:nth-child(5) .application-card { animation-delay: 0.25s; }
.col-lg-6:nth-child(6) .application-card { animation-delay: 0.3s; }

.application-image {
    position: relative;
    width: 100%;
    height: 100%;
    min-height: 250px;
    overflow: hidden;
    background: var(--gray-100);
}

.application-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.application-card:hover .application-image img {
    transform: scale(1.05);
}

.application-content {
    padding: 1.5rem;
}

.application-pet-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
}

.status-badge {
    padding: 0.375rem 0.75rem;
    font-weight: 600;
    font-size: 0.75rem;
    border-radius: 0.375rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.application-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.meta-item {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.875rem;
    color: var(--gray-600);
}

.meta-item i {
    color: var(--primary);
}

/* Progress Steps */
.progress-section {
    padding: 0.5rem 0;
}

.progress-steps {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.step {
    position: relative;
    z-index: 1;
}

.step-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--gray-200);
    border: 2px solid var(--gray-300);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-600);
    font-size: 0.75rem;
    transition: all 0.3s ease;
}

.step.active .step-icon {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
    transform: scale(1.1);
}

.step-line {
    flex: 1;
    height: 2px;
    background: var(--gray-300);
    transition: all 0.3s ease;
}

.step-line.active {
    background: var(--primary);
}

.status-message {
    padding: 0.75rem;
    background: var(--gray-50);
    border-radius: 0.5rem;
    border-left: 3px solid var(--gray-300);
}

.application-actions {
    padding-top: 1rem;
    border-top: 1px solid var(--gray-200);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    animation: fadeIn 0.6s ease-out;
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--gray-100);
    border-radius: 50%;
}

.empty-state-icon i {
    font-size: 2.5rem;
    color: var(--gray-600);
}

.empty-state-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.empty-state-text {
    font-size: 1rem;
    color: var(--gray-600);
    max-width: 500px;
    margin: 0 auto;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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
@media (max-width: 768px) {
    .page-title {
        font-size: 1.5rem;
    }
    
    .application-image {
        min-height: 200px;
    }
    
    .progress-steps {
        padding: 0 0.5rem;
    }
    
    .step-icon {
        width: 28px;
        height: 28px;
        font-size: 0.7rem;
    }
}
</style>
@endsection
