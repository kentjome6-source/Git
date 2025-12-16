@extends('layouts.app')

@section('title', 'Lost & Found Pets')

@section('content')
<div class="lostfound-page">
    <div class="container-fluid px-4 py-5">
        <!-- Page Header -->
        <div class="page-header mb-5">
            <div class="header-content">
                <div class="header-text">
                    <span class="label">Community Help</span>
                    <h1 class="page-title">Lost & Found Pets</h1>
                    <p class="page-subtitle">Help reunite pets with their families</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('lost-found.create') }}" class="btn-report">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Report Pet</span>
                    </a>
                    <a href="{{ route('view.map') }}" class="btn-map">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span>View Map</span>
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

        <!-- Filter Form -->
        <div class="filter-section mb-5">
            <form action="{{ route('pet.lostfound') }}" method="GET" class="filter-form">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label for="filter" class="filter-label">Type</label>
                        <select id="filter" name="filter" class="filter-select">
                            <option value="all" {{ request('filter', 'all') == 'all' ? 'selected' : '' }}>All Listings</option>
                            <option value="lost" {{ request('filter') == 'lost' ? 'selected' : '' }}>Lost Pets</option>
                            <option value="found" {{ request('filter') == 'found' ? 'selected' : '' }}>Found Pets</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="sort" class="filter-label">Sort by</label>
                        <select id="sort" name="sort" class="filter-select">
                            <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        </select>
                    </div>
                    <div class="filter-action">
                        <button type="submit" class="btn-filter">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>
                            Apply
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Listings Grid -->
        @if($lostFoundItems->count() > 0)
            <div class="listings-grid">
                @foreach($lostFoundItems as $item)
                    <div class="listing-card">
                        <a href="{{ route('lost-found.show', $item) }}" class="card-link">
                            <div class="listing-image">
                                @if($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->pet_name }}">
                                @else
                                    <div class="no-image">
                                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                                            <line x1="9" y1="9" x2="9.01" y2="9"></line>
                                            <line x1="15" y1="9" x2="15.01" y2="9"></line>
                                        </svg>
                                    </div>
                                @endif
                                <div class="type-badge type-{{ $item->type }}">{{ $item->type }}</div>
                            </div>
                            
                            <div class="listing-content">
                                <h3 class="listing-title">{{ $item->pet_name }}</h3>
                                
                                <div class="listing-meta">
                                    <span class="meta-item">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                                            <line x1="9" y1="9" x2="9.01" y2="9"></line>
                                            <line x1="15" y1="9" x2="15.01" y2="9"></line>
                                        </svg>
                                        {{ ucfirst($item->pet_type) }}
                                    </span>
                                    <span class="meta-item">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        {{ $item->location }}
                                    </span>
                                    <span class="meta-item">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                        {{ $item->date_lost_found->format('M d, Y') }}
                                    </span>
                                </div>
                                
                                <p class="listing-description">{{ Str::limit($item->description, 100) }}</p>
                                
                                <div class="listing-footer">
                                    <span class="posted-time">{{ $item->created_at->diffForHumans() }}</span>
                                    <span class="view-details">
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

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $lostFoundItems->appends(request()->query())->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                        <line x1="9" y1="9" x2="9.01" y2="9"></line>
                        <line x1="15" y1="9" x2="15.01" y2="9"></line>
                    </svg>
                </div>
                <h3 class="empty-title">No listings found</h3>
                <p class="empty-text">
                    @if(request('filter') == 'lost')
                        There are no lost pet listings at this time
                    @elseif(request('filter') == 'found')
                        There are no found pet listings at this time
                    @else
                        There are no lost or found pet listings at this time
                    @endif
                </p>
                {{-- <a href="{{ route('lost-found.create') }}" class="btn-empty-action">
                    Report a Pet
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
        --red: #ef4444;
        --gray: #64748b;
        --gray-light: #f1f5f9;
        --gray-lighter: #f8fafc;
    }

    .lostfound-page {
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

    .header-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-report,
    .btn-map {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
    }

    .btn-report {
        background: var(--purple);
        color: white;
    }

    .btn-report:hover {
        background: #7c3aed;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        color: white;
    }

    .btn-map {
        background: white;
        color: var(--slate);
        border: 2px solid #e2e8f0;
    }

    .btn-map:hover {
        background: var(--gray-light);
        border-color: #cbd5e1;
        color: var(--slate);
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
        max-width: 1400px;
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

    /* Filter Section */
    .filter-section {
        max-width: 1400px;
        margin-left: auto;
        margin-right: auto;
        animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .filter-form {
        background: white;
        padding: 24px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 20px;
        align-items: end;
    }

    .filter-label {
        display: block;
        font-weight: 500;
        font-size: 0.9rem;
        color: var(--slate);
        margin-bottom: 8px;
    }

    .filter-select {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.95rem;
        font-family: 'Sora', sans-serif;
        transition: all 0.2s;
        background: white;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn-filter {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: var(--blue);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-filter:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }

    /* Listings Grid */
    .listings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
        max-width: 1400px;
        margin: 0 auto 40px;
    }

    .listing-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .listing-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        border-color: var(--blue);
    }

    .card-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .listing-image {
        position: relative;
        height: 250px;
        overflow: hidden;
        background: var(--gray-light);
    }

    .listing-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .listing-card:hover .listing-image img {
        transform: scale(1.08);
    }

    .no-image {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--blue) 0%, var(--purple) 100%);
        color: white;
    }

    .type-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        backdrop-filter: blur(8px);
    }

    .type-lost {
        background: rgba(239, 68, 68, 0.95);
        color: white;
    }

    .type-found {
        background: rgba(16, 185, 129, 0.95);
        color: white;
    }

    .listing-content {
        padding: 24px;
    }

    .listing-title {
        font-size: 1.35rem;
        font-weight: 600;
        color: var(--slate);
        margin-bottom: 12px;
        letter-spacing: -0.01em;
    }

    .listing-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 16px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85rem;
        color: var(--gray);
    }

    .listing-description {
        color: var(--gray);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .listing-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 16px;
        border-top: 1px solid #e2e8f0;
    }

    .posted-time {
        font-size: 0.85rem;
        color: var(--gray);
    }

    .view-details {
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--blue);
        transition: gap 0.2s;
    }

    .listing-card:hover .view-details {
        gap: 10px;
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
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .listings-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
        }

        .btn-report,
        .btn-map {
            flex: 1;
            justify-content: center;
        }

        .page-title {
            font-size: 1.75rem;
        }

        .page-subtitle {
            font-size: 1rem;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .btn-filter {
            width: 100%;
            justify-content: center;
        }

        .listings-grid {
            grid-template-columns: 1fr;
        }

        .listing-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 16px !important;
            padding-right: 16px !important;
        }

        .filter-form {
            padding: 20px;
        }

        .listing-image {
            height: 220px;
        }

        .listing-content {
            padding: 20px;
        }

        .listing-title {
            font-size: 1.2rem;
        }

        .type-badge {
            top: 10px;
            right: 10px;
            padding: 4px 10px;
            font-size: 0.7rem;
        }
    }

    @media (max-width: 400px) {
        .listing-image {
            height: 200px;
        }

        .listing-content {
            padding: 16px;
        }
    }
</style>
@endsection