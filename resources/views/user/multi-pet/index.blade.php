@extends('layouts.app')

@section('title', 'Pet Dashboard')

@section('content')
<div class="pet-dashboard">
    <div class="container-fluid px-4 py-5">
        {{-- Hero Header Section --}}
        <div class="dashboard-header mb-5">
            <div class="header-content">
                <span class="label">Pet Dashboard</span>
            </div>
        </div>

        {{-- Success Message --}}
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

        {{-- Dashboard Content --}}
        @if($pets->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                        <line x1="9" y1="9" x2="9.01" y2="9"></line>
                        <line x1="15" y1="9" x2="15.01" y2="9"></line>
                    </svg>
                </div>
                <h3 class="empty-title">No Pets Available Yet</h3>
                <p class="empty-text">Check back soon for adorable companions looking for homes!</p>
            </div>
        @else
            {{-- Pet Cards Grid --}}
            <div class="pets-grid">
                @foreach($pets as $pet)
                    <div class="pet-card">
                        <a href="{{ route('pet.multipet.show', $pet) }}" class="pet-card-link">
                            {{-- Pet Image --}}
                            <div class="pet-image-wrapper">
                                @if($pet->image_path)
                                    <img src="{{ asset('storage/' . $pet->image_path) }}" 
                                         class="pet-image" 
                                         alt="{{ $pet->name }}">
                                @else
                                    <div class="pet-image-placeholder">
                                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                                            <line x1="9" y1="9" x2="9.01" y2="9"></line>
                                            <line x1="15" y1="9" x2="15.01" y2="9"></line>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Card Body --}}
                            <div class="pet-card-body">
                                <h3 class="pet-name">{{ $pet->name }}</h3>
                                <p class="pet-description">
                                    {{ Str::limit($pet->description ?? 'No description provided.', 100) }}
                                </p>
                                
                                <div class="pet-card-footer">
                                    <span class="view-details-text">
                                        View Details
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Custom Styles --}}
<style>
    :root {
        --slate: #0f172a;
        --slate-light: #1e293b;
        --blue: #3b82f6;
        --purple: #8b5cf6;
        --gray: #64748b;
        --gray-light: #f1f5f9;
        --gray-lighter: #f8fafc;
    }

    .pet-dashboard {
        font-family: 'Sora', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--gray-lighter);
        min-height: 100vh;
    }

    /* Header */
    .dashboard-header {
        text-align: center;
        padding: 40px 20px;
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

    .label {
        display: inline-block;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--blue);
        margin-bottom: 16px;
        font-weight: 600;
    }

    .dashboard-title {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 700;
        color: var(--slate);
        margin-bottom: 12px;
        letter-spacing: -0.02em;
    }

    .dashboard-subtitle {
        font-size: 1.1rem;
        color: var(--gray);
        max-width: 600px;
        margin: 0 auto;
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

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
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

    /* Pet Grid */
    .pets-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 28px;
        animation: fadeIn 0.8s ease-out;
    }

    /* Pet Card */
    .pet-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e2e8f0;
    }

    .pet-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        border-color: var(--blue);
    }

    .pet-card-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    /* Pet Image */
    .pet-image-wrapper {
        position: relative;
        width: 100%;
        height: 280px;
        overflow: hidden;
        background: var(--gray-light);
    }

    .pet-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .pet-card:hover .pet-image {
        transform: scale(1.08);
    }

    .pet-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--blue) 0%, var(--purple) 100%);
        color: white;
    }

    /* Card Body */
    .pet-card-body {
        padding: 24px;
    }

    .pet-name {
        font-size: 1.35rem;
        font-weight: 600;
        color: var(--slate);
        margin-bottom: 12px;
        letter-spacing: -0.01em;
    }

    .pet-description {
        font-size: 0.95rem;
        color: var(--gray);
        line-height: 1.6;
        margin-bottom: 20px;
        min-height: 3em;
    }

    .pet-card-footer {
        padding-top: 16px;
        border-top: 1px solid #e2e8f0;
    }

    .view-details-text {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--blue);
        transition: all 0.2s;
    }

    .pet-card:hover .view-details-text {
        gap: 12px;
    }

    .view-details-text svg {
        transition: transform 0.2s;
    }

    .pet-card:hover .view-details-text svg {
        transform: translateX(4px);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .pets-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
        }
    }

    @media (max-width: 768px) {
        .dashboard-header {
            padding: 30px 15px;
        }

        .dashboard-title {
            font-size: 2rem;
        }

        .dashboard-subtitle {
            font-size: 1rem;
        }

        .pets-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .pet-image-wrapper {
            height: 240px;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 16px !important;
            padding-right: 16px !important;
            padding-top: 24px !important;
            padding-bottom: 24px !important;
        }

        .dashboard-header {
            padding: 20px 0;
            margin-bottom: 32px !important;
        }

        .pets-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .pet-card-body {
            padding: 20px;
        }

        .pet-image-wrapper {
            height: 220px;
        }

        .pet-name {
            font-size: 1.2rem;
        }

        .pet-description {
            font-size: 0.9rem;
            min-height: auto;
        }

        .alert-success-custom {
            padding: 14px 16px;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 400px) {
        .pet-image-wrapper {
            height: 200px;
        }

        .pet-card-body {
            padding: 16px;
        }

        .pet-name {
            font-size: 1.1rem;
        }
    }
</style>
@endsection