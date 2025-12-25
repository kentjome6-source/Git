@extends('layouts.admin')

@section('title', 'Pet Management')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h2 class="page-title mb-1">Pet Management</h2>
                        <p class="page-subtitle text-muted mb-0">Manage and organize your pet listings</p>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-start">
                                <svg class="icon-sm me-2 flex-shrink-0 mt-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                                <div class="flex-grow-1">{{ session('success') }}</div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($pets->isEmpty())
                        <div class="empty-state">
                            <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <h4 class="empty-state-title">No pets found</h4>
                            <p class="empty-state-text">Get started by adding your first pet listing</p>
                            <a href="{{ route('admin.pets.create') }}" class="btn btn-primary mt-3">
                                Add Your First Pet
                            </a>
                        </div>
                    @else
                        <!-- Desktop Grid View -->
                        <div class="pets-grid">
                            @foreach($pets as $pet)
                                <div class="pet-card">
                                    <div class="pet-card-image">
                                        @if($pet->image_path)
                                            <img src="{{ asset('storage/' . $pet->image_path) }}" alt="{{ $pet->name }}">
                                        @else
                                            <div class="pet-card-placeholder">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                    <polyline points="21 15 16 10 5 21"></polyline>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="pet-card-body">
                                        <h3 class="pet-card-title">{{ $pet->name }}</h3>
                                        <div class="pet-card-meta">
                                            <div class="meta-row">
                                                <span class="meta-label">Owner</span>
                                                <span class="meta-value">{{ $pet->user->name }}</span>
                                            </div>
                                            <div class="meta-row">
                                                <span class="meta-label">Description</span>
                                                <span class="meta-value">{{ Str::limit($pet->description, 60) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Mobile List View -->
                        <div class="mobile-list">
                            @foreach($pets as $pet)
                                <div class="mobile-card">
                                    <div class="mobile-card-header">
                                        <div class="mobile-card-image">
                                            @if($pet->image_path)
                                                <img src="{{ asset('storage/' . $pet->image_path) }}" alt="{{ $pet->name }}">
                                            @else
                                                <div class="mobile-card-placeholder">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                        <polyline points="21 15 16 10 5 21"></polyline>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="mobile-card-title-section">
                                            <h4 class="mobile-card-title">{{ $pet->name }}</h4>
                                            <p class="mobile-card-subtitle">{{ $pet->user->name }}</p>
                                        </div>
                                    </div>
                                    <div class="mobile-card-body">
                                        <div class="mobile-info-item">
                                            <span class="mobile-info-label">Description</span>
                                            <span class="mobile-info-value">{{ Str::limit($pet->description, 80) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- <!-- Pagination -->
                        @if($pets->hasPages())
                            <div class="pagination-wrapper mt-4">
                                {{ $pets->links() }}
                            </div>
                        @endif --}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* CSS Variables for Theming */
    :root {
        --primary-color: #2563eb;
        --primary-hover: #1d4ed8;
        --primary-light: #3b82f6;
        --danger-color: #dc2626;
        --danger-hover: #b91c1c;
        --success-color: #16a34a;
        --success-bg: #f0fdf4;
        --success-border: #bbf7d0;
        --text-primary: #111827;
        --text-secondary: #6b7280;
        --text-tertiary: #9ca3af;
        --border-color: #e5e7eb;
        --bg-surface: #ffffff;
        --bg-base: #f9fafb;
        --border-radius: 12px;
        --border-radius-sm: 8px;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        --transition: all 0.2s ease-in-out;
    }

    /* Icon Utilities */
    .icon-xs {
        width: 14px;
        height: 14px;
        vertical-align: middle;
    }

    .icon-sm {
        width: 18px;
        height: 18px;
        vertical-align: middle;
    }

    .icon-md {
        width: 24px;
        height: 24px;
    }

    /* Page Header */
    .page-header {
        margin-bottom: 1.5rem;
    }

    .page-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        line-height: 1.2;
    }

    .page-subtitle {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
    }

    /* Card Styles */
    .card {
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        background: var(--bg-surface);
        overflow: hidden;
    }

    .card-body {
        padding: 2rem;
    }

    /* Button Styles */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.625rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 500;
        line-height: 1.25rem;
        border-radius: var(--border-radius-sm);
        transition: var(--transition);
        border: 1px solid transparent;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: #ffffff;
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
        background-color: transparent;
    }

    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        color: #ffffff;
        border-color: var(--primary-color);
    }

    .btn-outline-danger {
        color: var(--danger-color);
        border-color: var(--danger-color);
        background-color: transparent;
    }

    .btn-outline-danger:hover {
        background-color: var(--danger-color);
        color: #ffffff;
        border-color: var(--danger-color);
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.8125rem;
    }

    /* Alert Styles */
    .alert {
        border-radius: var(--border-radius-sm);
        border: 1px solid transparent;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background-color: var(--success-bg);
        border-color: var(--success-border);
        color: #15803d;
    }

    .alert .btn-close {
        padding: 0.75rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        color: var(--text-tertiary);
        stroke-width: 1.5;
    }

    .empty-state-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .empty-state-text {
        color: var(--text-secondary);
        font-size: 0.9375rem;
        margin-bottom: 0;
    }

    /* Pet Cards Grid - Desktop */
    .pets-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    .pet-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        overflow: hidden;
        transition: var(--transition);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .pet-card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-4px);
        border-color: var(--primary-light);
    }

    .pet-card-image {
        width: 100%;
        height: 220px;
        overflow: hidden;
        background-color: var(--bg-base);
        position: relative;
    }

    .pet-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .pet-card:hover .pet-card-image img {
        transform: scale(1.05);
    }

    .pet-card-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    }

    .pet-card-placeholder svg {
        width: 64px;
        height: 64px;
        color: var(--text-tertiary);
    }

    .pet-card-body {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .pet-card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1rem;
        line-height: 1.4;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .pet-card-meta {
        margin-bottom: 1.25rem;
        flex-grow: 1;
    }

    .meta-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
        gap: 1rem;
    }

    .meta-row:last-child {
        margin-bottom: 0;
    }

    .meta-label {
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--text-secondary);
        flex-shrink: 0;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .meta-value {
        font-size: 0.875rem;
        color: var(--text-primary);
        text-align: right;
        line-height: 1.5;
    }

    .pet-card-actions {
        display: flex;
        gap: 0.75rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    .pet-card-actions .btn {
        flex: 1;
    }

    .pet-card-actions form {
        flex: 1;
    }

    /* Mobile List View */
    .mobile-list {
        display: none;
    }

    .mobile-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        margin-bottom: 1rem;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .mobile-card-header {
        display: flex;
        align-items: center;
        padding: 1rem;
        gap: 1rem;
        border-bottom: 1px solid var(--border-color);
        background-color: var(--bg-base);
    }

    .mobile-card-image {
        width: 64px;
        height: 64px;
        border-radius: var(--border-radius-sm);
        overflow: hidden;
        background-color: var(--bg-base);
        flex-shrink: 0;
        border: 1px solid var(--border-color);
    }

    .mobile-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .mobile-card-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    }

    .mobile-card-placeholder svg {
        width: 32px;
        height: 32px;
        color: var(--text-tertiary);
    }

    .mobile-card-title-section {
        flex: 1;
        min-width: 0;
    }

    .mobile-card-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .mobile-card-subtitle {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .mobile-card-body {
        padding: 1rem;
    }

    .mobile-info-item {
        display: flex;
        flex-direction: column;
        gap: 0.375rem;
    }

    .mobile-info-label {
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .mobile-info-value {
        font-size: 0.875rem;
        color: var(--text-primary);
        line-height: 1.5;
    }

    .mobile-card-actions {
        display: flex;
        gap: 0.75rem;
        padding: 1rem;
        border-top: 1px solid var(--border-color);
        background-color: var(--bg-base);
    }

    .mobile-card-actions .btn {
        flex: 1;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-color);
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .pets-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        }
    }

    @media (max-width: 992px) {
        .page-title {
            font-size: 1.5rem;
        }

        .pets-grid {
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.25rem;
        }

        .pet-card-body {
            padding: 1.25rem;
        }
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem;
        }

        .page-title {
            font-size: 1.375rem;
        }

        .page-subtitle {
            font-size: 0.8125rem;
        }

        /* Hide grid, show mobile list */
        .pets-grid {
            display: none;
        }

        .mobile-list {
            display: block;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
        }

        .page-header .btn {
            padding: 0.625rem 1.125rem;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .card-body {
            padding: 1rem;
        }

        .page-title {
            font-size: 1.25rem;
        }

        .page-header {
            margin-bottom: 1rem;
        }

        .page-header .d-flex {
            flex-direction: column;
            align-items: stretch !important;
        }

        .page-header .btn {
            width: 100%;
        }

        .empty-state {
            padding: 3rem 1rem;
        }

        .empty-state-icon {
            width: 64px;
            height: 64px;
        }

        .mobile-card {
            margin-bottom: 0.875rem;
        }

        .mobile-card-header {
            padding: 0.875rem;
        }

        .mobile-card-body {
            padding: 0.875rem;
        }

        .mobile-card-actions {
            flex-direction: column;
            padding: 0.875rem;
        }

        .mobile-card-actions .btn {
            width: 100%;
        }

        .alert {
            padding: 0.875rem 1rem;
        }
    }

    /* Prevent horizontal scroll */
    body {
        overflow-x: hidden;
    }

    .container-fluid {
        max-width: 100%;
        overflow-x: hidden;
    }

    /* Focus states for accessibility */
    .btn:focus,
    .btn:focus-visible {
        outline: 2px solid var(--primary-color);
        outline-offset: 2px;
    }

    /* Print styles */
    @media print {
        .page-header .btn,
        .pet-card-actions,
        .mobile-card-actions {
            display: none;
        }
    }
</style>
@endsection