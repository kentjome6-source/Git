@extends('layouts.app')

@section('title', 'Appointment History')

@php
use Illuminate\Support\Str;
@endphp

@section('content')
<div class="history-page">
    <div class="container-fluid px-4 py-5">
        <!-- Back Button -->
        <div class="back-nav mb-4">
            <a href="{{ route('appointments.index') }}" class="back-button">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5"></path>
                    <path d="M12 19l-7-7 7-7"></path>
                </svg>
                <span>Back to Appointments</span>
            </a>
        </div>

        <!-- Page Header -->
        <div class="page-header mb-5">
            <div class="header-content">
                <div class="header-text">
                    <span class="label">Healthcare Records</span>
                    <h1 class="page-title">Appointment History</h1>
                    <p class="page-subtitle">View your past appointment records</p>
                </div>
            </div>
        </div>

        @if($appointments->count() > 0)
            <!-- Appointments History List -->
            <div class="history-container">
                <div class="history-list">
                    @foreach($appointments as $appointment)
                        <div class="history-card">
                            <!-- Card Header -->
                            <div class="history-header">
                                <div class="pet-info">
                                    <div class="id-badge">#{{ $appointment->id }}</div>
                                    <div class="pet-details">
                                        <h3 class="pet-name">{{ $appointment->pet_name }}</h3>
                                        <div class="pet-meta">
                                            <span class="meta-item">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                                                    <line x1="9" y1="9" x2="9.01" y2="9"></line>
                                                    <line x1="15" y1="9" x2="15.01" y2="9"></line>
                                                </svg>
                                                {{ $appointment->pet_type }}
                                            </span>
                                            <span class="meta-item">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <polyline points="12 6 12 12 16 14"></polyline>
                                                </svg>
                                                {{ $appointment->created_at->format('M d, Y g:i A') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="status-section">
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
                            
                            <!-- Card Body -->
                            <div class="history-body">
                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                                <polyline points="10 9 9 9 8 9"></polyline>
                                            </svg>
                                            Services Received
                                        </div>
                                        <div class="info-value">{{ $appointment->pet_services_received }}</div>
                                    </div>
                                    
                                    @if($appointment->status === 'rejected' && $appointment->rejection_reason)
                                        <div class="info-item full-width">
                                            <div class="info-label">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="12" y1="16" x2="12" y2="12"></line>
                                                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                                </svg>
                                                Rejection Reason
                                            </div>
                                            <div class="info-value reason-text">{{ $appointment->rejection_reason }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Card Footer -->
                            <div class="history-footer">
                                <a href="{{ route('appointments.history.show', $appointment) }}" class="btn-view-history">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
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
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <h3 class="empty-title">No History Found</h3>
                <p class="empty-text">You don't have any appointment records yet</p>
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

    .history-page {
        font-family: 'Sora', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--gray-lighter);
        min-height: 100vh;
    }

    /* Back Button */
    .back-nav {
        animation: fadeIn 0.5s ease-out;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--gray);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.95rem;
        padding: 8px 12px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .back-button:hover {
        color: var(--blue);
        background: rgba(59, 130, 246, 0.05);
    }

    .back-button svg {
        transition: transform 0.2s ease;
    }

    .back-button:hover svg {
        transform: translateX(-2px);
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

    /* History Container */
    .history-container {
        max-width: 1200px;
        margin: 0 auto;
        animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* History List */
    .history-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .history-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .history-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        border-color: var(--blue);
    }

    /* History Header */
    .history-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px;
        border-bottom: 1px solid #e2e8f0;
    }

    .pet-info {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .id-badge {
        background: var(--gray-light);
        color: var(--gray);
        padding: 8px 14px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        font-family: 'JetBrains Mono', monospace;
    }

    .pet-name {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--slate);
        margin-bottom: 6px;
    }

    .pet-meta {
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

    /* History Body */
    .history-body {
        padding: 24px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .info-item.full-width {
        grid-column: 1 / -1;
    }

    .info-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        color: var(--gray);
        font-weight: 500;
    }

    .info-value {
        font-size: 0.95rem;
        color: var(--slate);
        font-weight: 500;
    }

    .reason-text {
        background: var(--gray-light);
        padding: 12px 16px;
        border-radius: 8px;
        line-height: 1.6;
    }

    /* History Footer */
    .history-footer {
        padding: 20px 24px;
        border-top: 1px solid #e2e8f0;
    }

    .btn-view-history {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: var(--purple);
        color: white;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
    }

    .btn-view-history:hover {
        background: #7c3aed;
        transform: translateY(-1px);
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
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 40px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-title {
            font-size: 1.75rem;
        }

        .page-subtitle {
            font-size: 1rem;
        }

        .history-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }

        .pet-info {
            width: 100%;
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .info-grid {
            gap: 16px;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 16px !important;
            padding-right: 16px !important;
        }

        .history-card {
            margin-bottom: 16px;
        }

        .history-header,
        .history-body,
        .history-footer {
            padding: 16px;
        }

        .pet-name {
            font-size: 1.1rem;
        }

        .pet-meta {
            flex-direction: column;
            gap: 8px;
        }

        .meta-item {
            font-size: 0.8rem;
        }

        .btn-view-history {
            width: 100%;
            justify-content: center;
        }

        .back-button {
            font-size: 0.9rem;
            padding: 6px 10px;
        }

        .back-button span {
            display: none;
        }

        .back-button::after {
            content: 'Back';
            font-size: 0.9rem;
        }
    }

    @media (max-width: 400px) {
        .history-header,
        .history-body,
        .history-footer {
            padding: 12px;
        }

        .pet-name {
            font-size: 1rem;
        }

        .id-badge {
            padding: 6px 12px;
            font-size: 0.85rem;
        }
    }
</style>
@endsection