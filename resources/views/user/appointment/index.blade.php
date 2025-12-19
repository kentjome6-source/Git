@extends('layouts.app')

@section('title', 'Appointments')

@section('content')
<div class="appointments-page">
    <div class="container-fluid px-4 py-5">
        <!-- Page Header -->
        <div class="page-header mb-5">
            <div class="header-content">
                <div class="header-text">
                    <span class="label">Healthcare</span>
                    <h1 class="page-title">My Appointments</h1>
                    <p class="page-subtitle">Manage your pet's healthcare appointments</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('appointments.history') }}" class="btn-history">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span>History</span>
                    </a>
                    <a href="{{ route('appointments.create') }}" class="btn-create-appointment">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Request Appointment</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert-success-custom mb-4">
                <div class="alert-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <div class="alert-content">{{ session('success') }}</div>
                <button type="button" class="alert-close" data-bs-dismiss="alert">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        @endif

        @if($appointments->count() > 0)
            <!-- Stats Cards -->
            <div class="stats-grid mb-5">
                <div class="stat-card">
                    <div class="stat-icon stat-icon-pending">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <div class="stat-number">{{ $appointments->count() }}</div>
                    <div class="stat-label">Pending Review</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon stat-icon-total">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                        </svg>
                    </div>
                    <div class="stat-number">{{ $appointments->count() }}</div>
                    <div class="stat-label">Total Appointments</div>
                </div>
            </div>

            <!-- Appointments List -->
            <div class="appointments-container">
                <div class="section-header">
                    <h2 class="section-title">All Appointments</h2>
                    <span class="section-badge">{{ $appointments->count() }}</span>
                </div>
                
                <div class="appointments-list">
                    @foreach($appointments as $appointment)
                        <div class="appointment-card">
                            <div class="appointment-header">
                                <div class="pet-info">
                                    <h3 class="pet-name">{{ $appointment->pet_name }}</h3>
                                    <div class="appointment-meta">
                                        <span class="meta-item">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                            {{ $appointment->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="appointment-status">
                                    @php
                                        $statusDisplay = match($appointment->status) {
                                            'pending' => 'Pending',
                                            'accepted' => 'Accepted',
                                            'rejected' => 'Rejected',
                                            'cancelled' => 'Cancelled',
                                            default => ucfirst($appointment->status)
                                        };
                                        
                                        $statusClass = match($appointment->status) {
                                            'pending' => 'status-pending',
                                            'accepted' => 'status-accepted',
                                            'rejected' => 'status-rejected',
                                            'cancelled' => 'status-cancelled',
                                            default => 'status-pending'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">{{ $statusDisplay }}</span>
                                </div>
                            </div>
                            
                            <div class="appointment-body">
                                <div class="info-item">
                                    <div class="info-label">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        Veterinarian
                                    </div>
                                    <div class="info-value">
                                        @if($appointment->vet)
                                            Dr. {{ $appointment->vet->name }}
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="appointment-footer">
                                <a href="{{ route('appointments.show', $appointment) }}" class="btn-view">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    View Details
                                </a>
                                
                                @if($appointment->status === 'pending')
                                    <a href="{{ route('appointments.edit', $appointment) }}" class="btn-edit">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($appointments->hasPages())
                    <div class="pagination-wrapper">
                        {{ $appointments->links() }}
                    </div>
                @endif
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                    </svg>
                </div>
                <h3 class="empty-title">No appointments yet</h3>
                <p class="empty-text">Start your pet's healthcare journey by requesting your first appointment</p>
                {{-- <a href="{{ route('appointments.create') }}" class="btn-empty-action">
                    Request Appointment
                </a> --}}
            </div>
        @endif
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

    .appointments-page {
        font-family: 'Sora', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--gray-lighter);
        min-height: 100vh;
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

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 24px;
    }

    .header-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
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
        font-size: clamp(2rem, 4vw, 2.75rem);
        font-weight: 700;
        color: var(--slate);
        margin-bottom: 8px;
        letter-spacing: -0.02em;
    }

    .page-subtitle {
        font-size: 1.05rem;
        color: var(--gray);
    }

    .btn-history {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: white;
        color: var(--purple);
        border: 2px solid var(--purple);
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-history:hover {
        background: var(--purple);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .btn-create-appointment {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: var(--purple);
        color: white;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
    }

    .btn-create-appointment:hover {
        background: #7c3aed;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        color: white;
    }

    /* Success Alert */
    .alert-success-custom {
        background: #d1fae5;
        border: 1px solid #a7f3d0;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        animation: slideDown 0.4s ease-out;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-icon {
        flex-shrink: 0;
        color: #059669;
    }

    .alert-content {
        flex: 1;
        color: #065f46;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .alert-close {
        flex-shrink: 0;
        background: none;
        border: none;
        color: #059669;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        transition: opacity 0.2s;
    }

    .alert-close:hover {
        opacity: 0.7;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 24px;
        animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 28px;
        border: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }

    .stat-icon-pending {
        background: rgba(245, 158, 11, 0.1);
        color: var(--orange);
    }

    .stat-icon-total {
        background: rgba(139, 92, 246, 0.1);
        color: var(--purple);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--slate);
        margin-bottom: 8px;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--gray);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Appointments Container */
    .appointments-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e2e8f0;
    }

    .section-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--slate);
        letter-spacing: -0.01em;
    }

    .section-badge {
        background: var(--purple);
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    /* Appointments List */
    .appointments-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .appointment-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .appointment-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        border-color: var(--blue);
    }

    .appointment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px;
        border-bottom: 1px solid #e2e8f0;
    }

    .pet-name {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--slate);
        margin-bottom: 8px;
    }

    .appointment-meta {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85rem;
        color: var(--gray);
    }

    .meta-item svg {
        flex-shrink: 0;
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .status-accepted {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .status-rejected {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    .status-cancelled {
        background: rgba(100, 116, 139, 0.1);
        color: var(--gray);
    }

    .appointment-body {
        padding: 24px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
    }

    .info-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        color: var(--gray);
        font-weight: 500;
    }

    .info-value {
        font-size: 0.95rem;
        color: var(--slate);
        font-weight: 500;
        text-align: right;
    }

    .text-muted {
        color: var(--gray);
        font-weight: 400;
    }

    .appointment-footer {
        display: flex;
        gap: 12px;
        padding: 20px 24px;
        border-top: 1px solid #e2e8f0;
    }

    .btn-view,
    .btn-edit {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
    }

    .btn-view {
        background: var(--purple);
        color: white;
    }

    .btn-view:hover {
        background: #7c3aed;
        transform: translateY(-1px);
        color: white;
    }

    .btn-edit {
        background: white;
        color: var(--orange);
        border: 1px solid var(--orange);
    }

    .btn-edit:hover {
        background: var(--orange);
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        max-width: 600px;
        margin: 0 auto;
    }

    .empty-icon {
        margin-bottom: 24px;
        color: var(--gray);
        opacity: 0.4;
    }

    .empty-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: var(--slate);
        margin-bottom: 12px;
    }

    .empty-text {
        font-size: 1.05rem;
        color: var(--gray);
        margin-bottom: 28px;
    }

    .btn-empty-action {
        display: inline-flex;
        padding: 14px 28px;
        background: var(--purple);
        color: white;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-empty-action:hover {
        background: #7c3aed;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        color: white;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 40px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
        }

        .btn-history,
        .btn-create-appointment {
            flex: 1;
            justify-content: center;
        }

        .page-title {
            font-size: 1.75rem;
        }

        .page-subtitle {
            font-size: 1rem;
        }

        .stats-grid {
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .stat-card {
            padding: 20px;
        }

        .stat-number {
            font-size: 2rem;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .appointment-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }

        .appointment-footer {
            flex-direction: column;
        }

        .btn-view,
        .btn-edit {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 16px !important;
            padding-right: 16px !important;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .appointment-card {
            margin-bottom: 16px;
        }

        .appointment-header,
        .appointment-body,
        .appointment-footer {
            padding: 16px;
        }

        .pet-name {
            font-size: 1.1rem;
        }

        .section-title {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 400px) {
        .stat-card {
            padding: 16px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
        }

        .stat-number {
            font-size: 1.75rem;
        }
    }
</style>
@endsection