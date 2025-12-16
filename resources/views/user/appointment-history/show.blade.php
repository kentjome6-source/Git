@extends('layouts.app')

@section('title', 'Appointment Details')

@section('content')
<div class="details-page">
    <div class="container-fluid px-4 py-5">
        <div class="details-container">
            <!-- Page Header -->
            <div class="page-header mb-5">
                <a href="{{ route('appointments.history') }}" class="back-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Back to History
                </a>
                <div class="header-content">
                    <span class="label">Appointment</span>
                    <h1 class="page-title">Appointment Details</h1>
                </div>
            </div>

            <!-- Status Banner -->
            <div class="status-banner">
                @php
                    $statusDisplay = match($appointment->status) {
                        'pending' => 'Pending Review',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'cancelled' => 'Cancelled',
                        default => ucfirst($appointment->status)
                    };
                    
                    $statusClass = match($appointment->status) {
                        'pending' => 'banner-pending',
                        'accepted' => 'banner-accepted',
                        'rejected' => 'banner-rejected',
                        'cancelled' => 'banner-cancelled',
                        default => 'banner-pending'
                    };
                    
                    $statusIcon = match($appointment->status) {
                        'pending' => '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>',
                        'accepted' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>',
                        'rejected' => '<circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line>',
                        'cancelled' => '<circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line>',
                        default => '<circle cx="12" cy="12" r="10"></circle>'
                    };
                @endphp
                
                <div class="status-banner-inner {{ $statusClass }}">
                    <div class="status-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            {!! $statusIcon !!}
                        </svg>
                    </div>
                    <div class="status-content">
                        <div class="status-label">Status</div>
                        <div class="status-value">{{ $statusDisplay }}</div>
                    </div>
                </div>
                
                <div class="date-info">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    {{ $appointment->created_at->format('M d, Y') }}
                </div>
            </div>

            <!-- Rejection Reason Alert -->
            @if($appointment->status === 'rejected' && $appointment->rejection_reason)
                <div class="rejection-alert">
                    <div class="alert-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                    </div>
                    <div class="alert-content">
                        <div class="alert-title">Rejection Reason</div>
                        <div class="alert-text">{{ $appointment->rejection_reason }}</div>
                    </div>
                </div>
            @endif

            <!-- Main Content Grid -->
            <div class="content-grid">
                <!-- Pet Information Card -->
                <div class="info-card">
                    <div class="card-header">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                            <line x1="9" y1="9" x2="9.01" y2="9"></line>
                            <line x1="15" y1="9" x2="15.01" y2="9"></line>
                        </svg>
                        <h2>Pet Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-label">Pet Name</div>
                            <div class="info-value">{{ $appointment->pet_name }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Pet Type</div>
                            <div class="info-value">{{ $appointment->pet_type }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Services Received</div>
                            <div class="info-value">{{ $appointment->pet_services_received }}</div>
                        </div>
                    </div>
                </div>

                <!-- Scheduling Card -->
                <div class="info-card">
                    <div class="card-header">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <h2>Scheduling</h2>
                    </div>
                    <div class="card-body">
                        @if($appointment->appointment_date)
                            <div class="info-row">
                                <div class="info-label">Preferred Date</div>
                                <div class="info-value">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                            </div>
                        @endif
                        
                        @if($appointment->appointment_time)
                            <div class="info-row">
                                <div class="info-label">Preferred Time</div>
                                <div class="info-value">{{ date('g:i A', strtotime($appointment->appointment_time)) }}</div>
                            </div>
                        @endif
                        
                        @if($appointment->scheduled_datetime)
                            <div class="info-row">
                                <div class="info-label">Scheduled Date & Time</div>
                                <div class="info-value">{{ $appointment->scheduled_datetime->format('M d, Y g:i A') }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Veterinary Information Card -->
                <div class="info-card full-width">
                    <div class="card-header">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                        </svg>
                        <h2>Veterinary Information</h2>
                    </div>
                    <div class="card-body">
                        @if($appointment->vet)
                            <div class="vet-info-grid">
                                <div class="info-row">
                                    <div class="info-label">Veterinarian</div>
                                    <div class="info-value">Dr. {{ $appointment->vet->name }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Email</div>
                                    <div class="info-value">{{ $appointment->vet->email }}</div>
                                </div>
                            </div>
                            
                            @if($appointment->vet_notes)
                                <div class="notes-section">
                                    <div class="notes-label">Vet Notes</div>
                                    <div class="notes-content">{{ $appointment->vet_notes }}</div>
                                </div>
                            @endif
                            
                            @if($appointment->diagnosis)
                                <div class="notes-section">
                                    <div class="notes-label">Diagnosis</div>
                                    <div class="notes-content">{{ $appointment->diagnosis }}</div>
                                </div>
                            @endif
                            
                            @if($appointment->treatment_plan)
                                <div class="notes-section">
                                    <div class="notes-label">Treatment Plan</div>
                                    <div class="notes-content">{{ $appointment->treatment_plan }}</div>
                                </div>
                            @endif
                        @else
                            <div class="pending-notice">
                                <div class="pending-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                </div>
                                <div class="pending-text">
                                    <strong>Pending Assignment</strong>
                                    <p>Your appointment request is under review. A veterinarian will be assigned soon.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --slate: #0f172a;
        --slate-light: #1e293b;
        --blue: #3b82f6;
        --purple: #8b5cf6;
        --green: #10b981;
        --orange: #f59e0b;
        --red: #ef4444;
        --gray: #64748b;
        --gray-light: #f1f5f9;
        --gray-lighter: #f8fafc;
    }

    .details-page {
        font-family: 'Sora', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--gray-lighter);
        min-height: 100vh;
    }

    .details-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Page Header */
    .page-header {
        animation: fadeInDown 0.6s ease-out;
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

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--gray);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 20px;
        transition: all 0.2s;
    }

    .back-link:hover {
        color: var(--purple);
        transform: translateX(-4px);
    }

    .label {
        display: block;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--blue);
        margin-bottom: 12px;
        font-weight: 600;
    }

    .page-title {
        font-size: clamp(1.75rem, 3vw, 2.25rem);
        font-weight: 700;
        color: var(--slate);
        letter-spacing: -0.02em;
    }

    /* Status Banner */
    .status-banner {
        background: white;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        border: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .status-banner-inner {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .status-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .banner-pending .status-icon {
        background: rgba(245, 158, 11, 0.1);
        color: var(--orange);
    }

    .banner-accepted .status-icon {
        background: rgba(16, 185, 129, 0.1);
        color: var(--green);
    }

    .banner-rejected .status-icon {
        background: rgba(239, 68, 68, 0.1);
        color: var(--red);
    }

    .banner-cancelled .status-icon {
        background: rgba(100, 116, 139, 0.1);
        color: var(--gray);
    }

    .status-label {
        font-size: 0.85rem;
        color: var(--gray);
        font-weight: 500;
        margin-bottom: 4px;
    }

    .status-value {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--slate);
    }

    .date-info {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--gray);
        font-size: 0.9rem;
        font-weight: 500;
    }

    /* Rejection Alert */
    .rejection-alert {
        background: rgba(239, 68, 68, 0.05);
        border: 1px solid rgba(239, 68, 68, 0.2);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
        display: flex;
        gap: 16px;
    }

    .rejection-alert .alert-icon {
        flex-shrink: 0;
        color: var(--red);
    }

    .alert-title {
        font-weight: 600;
        color: var(--red);
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .alert-text {
        color: var(--slate);
        line-height: 1.6;
    }

    /* Content Grid */
    .content-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 24px;
    }

    .info-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .info-card.full-width {
        grid-column: 1 / -1;
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: var(--gray-lighter);
    }

    .card-header svg {
        color: var(--purple);
    }

    .card-header h2 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--slate);
        margin: 0;
    }

    .card-body {
        padding: 24px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: start;
        padding: 16px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .info-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-row:first-child {
        padding-top: 0;
    }

    .info-label {
        font-size: 0.9rem;
        color: var(--gray);
        font-weight: 500;
    }

    .info-value {
        font-size: 0.95rem;
        color: var(--slate);
        font-weight: 500;
        text-align: right;
        max-width: 60%;
        word-wrap: break-word;
    }

    .vet-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 0;
    }

    .notes-section {
        margin-top: 24px;
        padding-top: 24px;
        border-top: 1px solid #e2e8f0;
    }

    .notes-label {
        font-size: 0.9rem;
        color: var(--gray);
        font-weight: 600;
        margin-bottom: 12px;
    }

    .notes-content {
        background: var(--gray-lighter);
        padding: 16px;
        border-radius: 10px;
        line-height: 1.6;
        color: var(--slate);
    }

    .pending-notice {
        display: flex;
        gap: 16px;
        padding: 24px;
        background: rgba(245, 158, 11, 0.05);
        border-radius: 12px;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .pending-icon {
        flex-shrink: 0;
        color: var(--orange);
    }

    .pending-text strong {
        display: block;
        color: var(--orange);
        font-weight: 600;
        margin-bottom: 8px;
    }

    .pending-text p {
        color: var(--slate);
        margin: 0;
        line-height: 1.6;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .status-banner {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }

        .date-info {
            width: 100%;
        }

        .content-grid {
            grid-template-columns: 1fr;
        }

        .info-row {
            flex-direction: column;
            gap: 8px;
        }

        .info-value {
            text-align: left;
            max-width: 100%;
        }

        .vet-info-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 16px !important;
            padding-right: 16px !important;
        }

        .status-banner {
            padding: 20px;
        }

        .status-icon {
            width: 40px;
            height: 40px;
        }

        .status-value {
            font-size: 1.1rem;
        }

        .card-header,
        .card-body {
            padding: 16px;
        }

        .rejection-alert {
            padding: 16px;
        }

        .pending-notice {
            flex-direction: column;
            padding: 16px;
        }
    }

    @media (max-width: 400px) {
        .status-banner {
            padding: 16px;
        }

        .card-header h2 {
            font-size: 1rem;
        }
    }
</style>
@endsection