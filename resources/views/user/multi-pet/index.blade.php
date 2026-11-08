@extends('layouts.app')

@section('title', 'Pet Dashboard')

@section('content')
<div class="pet-dashboard">
    <div class="container-fluid px-4 py-5">
        <header class="dashboard-header">
            <div class="header-copy">
                <span class="dashboard-eyebrow">YOUR PET COMMUNITY</span>
                <h1 class="dashboard-title">Pet dashboard</h1>
                <p class="dashboard-subtitle">A closer look at the pets in your community.</p>
            </div>
        </header>

        <div class="dashboard-toolbar">
            <span class="pet-count">
                <i class="fa-solid fa-paw" aria-hidden="true"></i>
                {{ $pets->count() }} {{ Str::plural('pet', $pets->count()) }}
            </span>
            <a href="{{ route('pet.multipet.create') }}" class="add-pet-button">
                <i class="fa-solid fa-plus" aria-hidden="true"></i>
                Add a pet
            </a>
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
                    <i class="fa-solid fa-paw" aria-hidden="true"></i>
                </div>
                <h2 class="empty-title">Your pet community starts here</h2>
                <p class="empty-text">There are no pets to show just yet. Add a pet to get started.</p>
            </div>
        @else
            {{-- Pet Cards Grid --}}
            <div class="pets-grid">
                @foreach($pets as $pet)
                    <div class="pet-card">
                        <a href="{{ route('pet.multipet.show', $pet) }}" class="pet-card-link">
                            <div class="pet-image-wrapper">
                                @if($pet->image_path)
                                    <img src="{{ asset('storage/' . $pet->image_path) }}"
                                         class="pet-image"
                                         alt="{{ $pet->name }}"
                                         loading="lazy">
                                @else
                                    <div class="pet-image-placeholder">
                                        <i class="fa-solid fa-paw" aria-hidden="true"></i>
                                    </div>
                                @endif
                                <span class="pet-image-overlay" aria-hidden="true"></span>
                            </div>

                            <div class="pet-card-body">
                                <div class="pet-heading">
                                    <h2 class="pet-name">{{ $pet->name }}</h2>
                                    @if($pet->breed)
                                        <span class="pet-breed">{{ $pet->breed }}</span>
                                    @endif
                                </div>
                                <p class="pet-description">{{ Str::limit($pet->description ?? 'No description provided.', 100) }}</p>
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
    .pet-dashboard {
        --pet-ink: #20243a;
        --pet-muted: #72778b;
        --pet-accent: #6951a2;
        --pet-accent-dark: #523c88;
        --pet-line: #e9e7f0;
        --pet-surface: #fff;
        --pet-canvas: #f7f6fa;
        min-height: calc(100vh - 24px);
        background: var(--pet-canvas);
        color: var(--pet-ink);
    }

    .dashboard-header {
        margin: 0 auto 20px;
        max-width: 1440px;
        padding: clamp(28px, 5vw, 52px);
        border: 1px solid rgba(255, 255, 255, 0.55);
        border-radius: 22px;
        background:
            radial-gradient(ellipse at 82% 18%, rgba(255, 255, 255, 0.25), transparent 34%),
            linear-gradient(120deg, #42346e 0%, #6951a2 56%, #927bbd 100%);
        box-shadow: 0 16px 34px rgba(67, 50, 108, 0.18);
    }

    .dashboard-eyebrow {
        display: block;
        margin-bottom: 8px;
        color: #e8defc;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        font-weight: 700;
    }

    .dashboard-title {
        margin: 0 0 6px;
        color: #fff;
        font-size: clamp(1.9rem, 3vw, 2.45rem);
        font-weight: 750;
        letter-spacing: -0.045em;
    }

    .dashboard-subtitle {
        margin: 0;
        color: rgba(255, 255, 255, 0.82);
        font-size: 1rem;
    }

    .dashboard-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        max-width: 1440px;
        margin: 0 auto 32px;
    }

    .pet-count {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--pet-muted);
        font-size: 0.9rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .pet-count i {
        color: var(--pet-accent);
    }

    .add-pet-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 44px;
        padding: 0 18px;
        border: 1px solid var(--pet-accent);
        border-radius: 10px;
        background: var(--pet-accent);
        color: #fff;
        font-size: 0.9rem;
        font-weight: 650;
        text-decoration: none;
        box-shadow: 0 4px 10px rgba(105, 81, 162, 0.16);
        transition: background-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
    }

    .add-pet-button:hover {
        background: var(--pet-accent-dark);
        border-color: var(--pet-accent-dark);
        color: #fff;
        box-shadow: 0 7px 16px rgba(105, 81, 162, 0.22);
        transform: translateY(-1px);
    }

    .pet-dashboard a:focus-visible,
    .pet-dashboard button:focus-visible {
        outline: 3px solid rgba(105, 81, 162, 0.35);
        outline-offset: 3px;
    }

    .alert-success-custom {
        max-width: 1440px;
        margin-right: auto;
        margin-left: auto;
        border: 1px solid #b8e4d1;
        border-radius: 12px;
        background: #effaf4;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-icon {
        flex-shrink: 0;
        color: #21865b;
    }

    .alert-content {
        flex: 1;
        color: #236747;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .alert-close {
        flex-shrink: 0;
        background: none;
        border: none;
        color: #21865b;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        transition: opacity 0.18s ease;
    }

    .alert-close:hover { opacity: 0.7; }

    .empty-state {
        max-width: 1440px;
        margin: 0 auto;
        border: 1px dashed #d8d4e4;
        border-radius: 18px;
        background: var(--pet-surface);
        text-align: center;
        padding: 64px 24px;
    }

    .empty-icon {
        display: grid;
        width: 68px;
        height: 68px;
        margin: 0 auto 20px;
        place-items: center;
        border-radius: 20px;
        background: #f0ecf8;
        color: var(--pet-accent);
        font-size: 1.65rem;
    }

    .empty-title {
        margin: 0 0 8px;
        color: var(--pet-ink);
        font-size: 1.35rem;
        font-weight: 700;
    }

    .empty-text {
        margin: 0 auto 22px;
        color: var(--pet-muted);
        font-size: 0.98rem;
    }

    .empty-action {
        display: inline-flex;
    }

    .pets-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(min(100%, 290px), 1fr));
        gap: 22px;
        max-width: 1440px;
        margin: 0 auto;
    }

    .pet-card {
        min-width: 0;
        border: 1px solid var(--pet-line);
        border-radius: 16px;
        background: var(--pet-surface);
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(32, 36, 58, 0.035);
        transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
    }

    .pet-card:hover {
        transform: translateY(-4px);
        border-color: #d2c8e5;
        box-shadow: 0 14px 30px rgba(42, 34, 68, 0.1);
    }

    .pet-card-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .pet-image-wrapper {
        position: relative;
        width: 100%;
        height: 230px;
        overflow: hidden;
        background: #eeedf3;
    }

    .pet-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.35s ease;
    }

    .pet-card:hover .pet-image { transform: scale(1.045); }

    .pet-image-overlay {
        position: absolute;
        inset: 45% 0 0;
        background: linear-gradient(180deg, transparent, rgba(24, 21, 37, 0.18));
        pointer-events: none;
    }

    .pet-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at 25% 20%, #f7f3fc, transparent 42%), linear-gradient(135deg, #e8e1f3, #ded8eb);
        color: var(--pet-accent);
        font-size: 2.5rem;
    }

    .pet-card-body {
        padding: 20px 21px 18px;
    }

    .pet-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }

    .pet-name {
        min-width: 0;
        margin: 0;
        color: var(--pet-ink);
        font-size: 1.16rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        overflow-wrap: anywhere;
    }

    .pet-breed {
        flex: 0 1 auto;
        max-width: 46%;
        border-radius: 999px;
        background: #f2eff8;
        color: #5e4a8f;
        padding: 5px 9px;
        font-size: 0.72rem;
        font-weight: 650;
        line-height: 1.2;
        text-align: center;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .pet-description {
        min-height: 3.2em;
        margin: 12px 0 16px;
        color: var(--pet-muted);
        font-size: 0.9rem;
        line-height: 1.65;
    }

    .pet-card-footer {
        border-top: 1px solid #efedf3;
        padding-top: 14px;
    }

    .view-details-text {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: var(--pet-accent);
        font-size: 0.88rem;
        font-weight: 700;
        transition: gap 0.18s ease;
    }

    .pet-card:hover .view-details-text { gap: 11px; }

    .view-details-text svg { transition: transform 0.18s ease; }

    .pet-card:hover .view-details-text svg { transform: translateX(3px); }

    @media (max-width: 767.98px) {
        .pet-dashboard > .container-fluid { padding-top: 24px !important; }

        .dashboard-header {
            margin-bottom: 18px;
            padding: 28px 24px;
        }

        .dashboard-toolbar {
            width: 100%;
            margin-bottom: 24px;
        }

        .pets-grid { gap: 16px; }
        .pet-image-wrapper { height: 220px; }
    }

    @media (max-width: 420px) {
        .dashboard-toolbar { align-items: flex-start; }
        .add-pet-button { padding: 0 13px; font-size: 0.84rem; }
        .pet-heading { flex-direction: column; gap: 8px; }
        .pet-breed { max-width: 100%; }
        .pet-image-wrapper { height: 200px; }
    }

    @media (prefers-reduced-motion: reduce) {
        .pet-dashboard *,
        .pet-dashboard *::before,
        .pet-dashboard *::after {
            scroll-behavior: auto !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>
@endsection