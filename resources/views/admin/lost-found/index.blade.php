@extends('layouts.admin')

@section('title', 'Lost & Found Records')

@section('styles')
<style>
    :root {
        --primary: #0f172a;
        --primary-light: #1e293b;
        --accent: #3b82f6;
        --accent-light: #60a5fa;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --bg-primary: #ffffff;
        --bg-secondary: #f8fafc;
        --border-color: #e2e8f0;
        --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        --radius: 12px;
        --radius-lg: 16px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        background-color: var(--bg-secondary);
        color: var(--text-primary);
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .container-fluid {
        padding: 2rem 1.5rem;
    }

    /* Page Header */
    .page-header {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        animation: slideDown 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        letter-spacing: -0.025em;
    }

    .page-subtitle {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0;
    }

    /* Alert */
    .alert {
        padding: 1rem 1.25rem;
        border-radius: var(--radius);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
        animation: slideDown 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        color: #065f46;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        transition: var(--transition);
        animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) backwards;
        position: relative;
        overflow: hidden;
    }

    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--card-accent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-card.lost {
        --card-accent: var(--danger);
    }

    .stat-card.found {
        --card-accent: var(--success);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--card-accent);
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Section Header */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.3s backwards;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        letter-spacing: -0.025em;
    }

    .section-title i {
        color: var(--accent);
        font-size: 1.125rem;
    }

    .section-count {
        background: var(--primary);
        color: white;
        padding: 0.375rem 0.875rem;
        border-radius: 50px;
        font-size: 0.8125rem;
        font-weight: 600;
    }

    /* Listings Grid */
    .listings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .listing-card {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        overflow: hidden;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        transition: var(--transition);
        animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) backwards;
    }

    .listing-card:nth-child(1) { animation-delay: 0.4s; }
    .listing-card:nth-child(2) { animation-delay: 0.45s; }
    .listing-card:nth-child(3) { animation-delay: 0.5s; }
    .listing-card:nth-child(4) { animation-delay: 0.55s; }
    .listing-card:nth-child(5) { animation-delay: 0.6s; }
    .listing-card:nth-child(6) { animation-delay: 0.65s; }

    .listing-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-lg);
        border-color: var(--accent);
    }

    .listing-image {
        height: 220px;
        overflow: hidden;
        position: relative;
        background: var(--bg-secondary);
    }

    .listing-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .listing-card:hover .listing-image img {
        transform: scale(1.05);
    }

    .listing-image .no-image {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: var(--text-muted);
        font-size: 3rem;
    }

    .type-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        backdrop-filter: blur(10px);
        box-shadow: var(--shadow-md);
    }

    .type-badge.lost {
        background: rgba(239, 68, 68, 0.95);
        color: white;
    }

    .type-badge.found {
        background: rgba(16, 185, 129, 0.95);
        color: white;
    }

    .listing-content {
        padding: 1.5rem;
    }

    .listing-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        letter-spacing: -0.025em;
    }

    .listing-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        font-size: 0.8125rem;
        color: var(--text-secondary);
    }

    .meta-item i {
        color: var(--accent);
        font-size: 0.75rem;
    }

    .listing-description {
        color: var(--text-secondary);
        font-size: 0.9375rem;
        line-height: 1.6;
        margin-bottom: 1.25rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .listing-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    .listing-date {
        font-size: 0.8125rem;
        color: var(--text-muted);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: var(--radius);
        text-decoration: none;
        transition: var(--transition);
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--accent);
        transform: translateX(2px);
    }

    .btn i {
        font-size: 0.75rem;
    }

    /* Mobile Table */
    .mobile-table {
        display: none;
    }

    .mobile-card {
        background: var(--bg-primary);
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        margin-bottom: 1rem;
        overflow: hidden;
        box-shadow: var(--shadow);
        animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) backwards;
    }

    .mobile-card:nth-child(1) { animation-delay: 0.1s; }
    .mobile-card:nth-child(2) { animation-delay: 0.15s; }
    .mobile-card:nth-child(3) { animation-delay: 0.2s; }
    .mobile-card:nth-child(4) { animation-delay: 0.25s; }
    .mobile-card:nth-child(5) { animation-delay: 0.3s; }

    .mobile-item {
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .mobile-item:last-of-type {
        border-bottom: none;
    }

    .mobile-label {
        font-weight: 600;
        font-size: 0.8125rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .mobile-value {
        text-align: right;
        font-size: 0.9375rem;
        color: var(--text-primary);
        font-weight: 500;
    }

    .mobile-badge {
        display: inline-block;
        padding: 0.375rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .mobile-badge.lost {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .mobile-badge.found {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .mobile-badge.resolved {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .mobile-badge.unresolved {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .mobile-actions {
        padding: 1rem;
        display: flex;
        gap: 0.5rem;
    }

    .mobile-actions .btn {
        flex: 1;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--text-muted);
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
    }

    .empty-state p {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0;
    }

    /* Animations */
    @keyframes slideDown {
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
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .container-fluid {
            padding: 1.5rem 1.25rem;
        }

        .listings-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding: 1.25rem 1rem;
        }

        .page-header {
            padding: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .page-subtitle {
            font-size: 0.9375rem;
        }

        .stats-grid {
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .stat-card {
            padding: 1.5rem 1.25rem;
        }

        .stat-number {
            font-size: 2rem;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        /* Hide desktop grid on mobile */
        .listings-grid {
            display: none;
        }

        /* Show mobile table */
        .mobile-table {
            display: block;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding: 1rem 0.875rem;
        }

        .page-header {
            padding: 1.25rem;
        }

        .page-title {
            font-size: 1.375rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .section-title {
            font-size: 1.125rem;
        }

        .mobile-item {
            padding: 0.875rem;
        }

        .empty-state {
            padding: 3rem 1.5rem;
        }

        .empty-state i {
            font-size: 3rem;
        }

        .empty-state h3 {
            font-size: 1.25rem;
        }
    }

    /* Desktop - ensure grid shows */
    @media (min-width: 769px) {
        .mobile-table {
            display: none !important;
        }
        .listings-grid {
            display: grid !important;
        }
    }

    /* Smooth Scrolling */
    html {
        scroll-behavior: smooth;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h1 class="page-title">Lost & Found Records</h1>
        <p class="page-subtitle">View lost and found pet listings</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="stats-grid">
        <div class="stat-card lost">
            <div class="stat-number">{{ $lostFoundItems->where('type', 'lost')->count() }}</div>
            <div class="stat-label">Lost Pets</div>
        </div>
        <div class="stat-card found">
            <div class="stat-number">{{ $lostFoundItems->where('type', 'found')->count() }}</div>
            <div class="stat-label">Found Pets</div>
        </div>
    </div>

    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-paw"></i>
            All Listings
        </h2>
        <span class="section-count">{{ $lostFoundItems->count() }}</span>
    </div>

    @if($lostFoundItems->count() > 0)
        <!-- Desktop Card Grid -->
        <div class="listings-grid">
            @foreach($lostFoundItems as $listing)
                <div class="listing-card">
                    <div class="listing-image">
                        @if($listing->image_path)
                            <img src="{{ asset('storage/' . $listing->image_path) }}" alt="{{ $listing->pet_name }}">
                        @else
                            <div class="no-image">
                                <i class="fas fa-paw"></i>
                            </div>
                        @endif
                        <div class="type-badge {{ $listing->type }}">{{ $listing->type }}</div>
                    </div>
                    <div class="listing-content">
                        <h3 class="listing-title">{{ $listing->pet_name }}</h3>
                        <div class="listing-meta">
                            <div class="meta-item">
                                <i class="fas fa-paw"></i>
                                <span>{{ ucfirst($listing->pet_type) }}@if($listing->breed) - {{ $listing->breed }}@endif</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $listing->location }}</span>
                            </div>
                        </div>
                        <p class="listing-description">{{ Str::limit($listing->description, 100) }}</p>
                        <div class="listing-footer">
                            <div class="listing-date">
                                {{ $listing->created_at->diffForHumans() }}
                            </div>
                            <a href="{{ route('admin.lost-found.show', $listing) }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i>
                                <span>View</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Mobile Table -->
        <div class="mobile-table">
            @foreach($lostFoundItems as $listing)
                <div class="mobile-card">
                    <div class="mobile-item">
                        <span class="mobile-label">Pet</span>
                        <span class="mobile-value">{{ $listing->pet_name }}</span>
                    </div>
                    <div class="mobile-item">
                        <span class="mobile-label">Type</span>
                        <span class="mobile-value">
                            <span class="mobile-badge {{ $listing->type }}">{{ $listing->type }}</span>
                        </span>
                    </div>
                    <div class="mobile-item">
                        <span class="mobile-label">Location</span>
                        <span class="mobile-value">{{ $listing->location }}</span>
                    </div>
                    <div class="mobile-item">
                        <span class="mobile-label">Status</span>
                        <span class="mobile-value">
                            @if($listing->is_resolved)
                                <span class="mobile-badge resolved">Resolved</span>
                            @else
                                <span class="mobile-badge unresolved">Unresolved</span>
                            @endif
                        </span>
                    </div>
                    <div class="mobile-item">
                        <span class="mobile-label">Submitted</span>
                        <span class="mobile-value">{{ $listing->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="mobile-actions">
                        <a href="{{ route('admin.lost-found.show', $listing) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i>
                            <span>View</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-paw"></i>
            <h3>No listings yet</h3>
            <p>There are no lost or found pet listings at this time.</p>
        </div>
    @endif
</div>
@endsection