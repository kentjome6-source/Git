@extends('layouts.admin')

@section('title', 'Lost & Found Management')

@section('content')
<div class="lost-found-admin-page">
    <div class="container-fluid px-4 py-5">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="header-content">
                <div>
                    <span class="label">Pet Recovery System</span>
                    <h1 class="page-title">Lost & Found Management</h1>
                    <p class="page-subtitle">Review and manage lost and found pet listings</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid mb-4">
            <div class="stat-card">
                <div class="stat-icon pending">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $pendingCount }}</div>
                    <div class="stat-label">Pending Review</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon active">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $activeCount }}</div>
                    <div class="stat-label">Active Listings</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon resolved">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 6 9 17l-5-5"/></svg>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $resolvedCount }}</div>
                    <div class="stat-label">Resolved Cases</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon claims">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $claimsCount }}</div>
                    <div class="stat-label">Pending Claims</div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs-container mb-4">
            <div class="tabs">
                <button class="tab-btn active" data-tab="pending">
                    Pending Review ({{ $pendingCount }})
                </button>
                <button class="tab-btn" data-tab="approved">
                    Approved Listings ({{ $activeCount }})
                </button>
                <button class="tab-btn" data-tab="resolved">
                    Resolved Cases ({{ $resolvedCount }})
                </button>
            </div>
        </div>

        <!-- Tab Contents -->
        <div class="tab-content active" id="pending-tab">
            @if($pendingListings->count() > 0)
                <div class="listings-grid">
                    @foreach($pendingListings as $listing)
                        <div class="listing-card">
                            <div class="listing-image">
                                @if($listing->image_path)
                                    <img src="{{ asset('storage/' . $listing->image_path) }}" alt="{{ $listing->pet_name }}">
                                @else
                                    <div class="no-image">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <circle cx="12" cy="12" r="10"></circle>
                                        </svg>
                                    </div>
                                @endif
                                <div class="type-badge type-{{ $listing->type }}">{{ $listing->type }}</div>
                            </div>
                            
                            <div class="listing-content">
                                <h3 class="listing-title">{{ $listing->pet_name }}</h3>
                                
                                <div class="listing-meta">
                                    <span class="meta-item">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                        </svg>
                                        {{ ucfirst($listing->pet_type) }}
                                    </span>
                                    <span class="meta-item">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        </svg>
                                        {{ $listing->location }}
                                    </span>
                                    <span class="meta-item">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        </svg>
                                        {{ $listing->date_lost_found->format('M d, Y') }}
                                    </span>
                                </div>
                                
                                <p class="listing-description">{{ Str::limit($listing->description, 80) }}</p>
                                
                                <div class="listing-submitter">
                                    <strong>Submitted by:</strong> {{ $listing->user->name }}<br>
                                    <strong>Date:</strong> {{ $listing->created_at->diffForHumans() }}
                                </div>
                                
                                <div class="listing-actions">
                                    <button class="btn-view" onclick="viewListing({{ $listing->id }})">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                        View Details
                                    </button>
                                    <button class="btn-approve" onclick="approveListing({{ $listing->id }})">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        Approve
                                    </button>
                                    <button class="btn-reject" onclick="rejectListing({{ $listing->id }})">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                        Reject
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="pagination-wrapper mt-4">
                    {{ $pendingListings->links() }}
                </div>
            @else
                <div class="empty-state">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <h3>No Pending Reviews</h3>
                    <p>All listings have been reviewed</p>
                </div>
            @endif
        </div>

        <div class="tab-content" id="approved-tab">
            @if($approvedListings->count() > 0)
                <div class="listings-grid">
                    @foreach($approvedListings as $listing)
                        <div class="listing-card">
                            <div class="listing-image">
                                @if($listing->image_path)
                                    <img src="{{ asset('storage/' . $listing->image_path) }}" alt="{{ $listing->pet_name }}">
                                @else
                                    <div class="no-image">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <circle cx="12" cy="12" r="10"></circle>
                                        </svg>
                                    </div>
                                @endif
                                <div class="type-badge type-{{ $listing->type }}">{{ $listing->type }}</div>
                                @if($listing->is_featured)
                                    <div class="featured-badge">Featured</div>
                                @endif
                            </div>
                            
                            <div class="listing-content">
                                <h3 class="listing-title">{{ $listing->pet_name }}</h3>
                                
                                <div class="listing-meta">
                                    <span class="meta-item">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                        </svg>
                                        {{ ucfirst($listing->pet_type) }}
                                    </span>
                                    <span class="meta-item">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        </svg>
                                        {{ $listing->location }}
                                    </span>
                                </div>
                                
                                <p class="listing-description">{{ Str::limit($listing->description, 80) }}</p>
                                
                                <div class="listing-actions">
                                    <button class="btn-view" onclick="viewListing({{ $listing->id }})">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        </svg>
                                        View
                                    </button>
                                    @if($listing->type == 'found' && $listing->claims_count > 0)
                                        <button class="btn-claims" onclick="viewClaims({{ $listing->id }})">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="9" cy="7" r="4"></circle>
                                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                            </svg>
                                            Claims ({{ $listing->claims_count }})
                                        </button>
                                    @endif
                                    <button class="btn-feature" onclick="toggleFeature({{ $listing->id }}, {{ $listing->is_featured ? 'true' : 'false' }})">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                        </svg>
                                        {{ $listing->is_featured ? 'Unfeature' : 'Feature' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="pagination-wrapper mt-4">
                    {{ $approvedListings->links() }}
                </div>
            @else
                <div class="empty-state">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <h3>No Active Listings</h3>
                    <p>No approved listings available</p>
                </div>
            @endif
        </div>

        <div class="tab-content" id="resolved-tab">
            @if($resolvedListings->count() > 0)
                <div class="listings-grid">
                    @foreach($resolvedListings as $listing)
                        <div class="listing-card resolved">
                            <div class="listing-image">
                                @if($listing->image_path)
                                    <img src="{{ asset('storage/' . $listing->image_path) }}" alt="{{ $listing->pet_name }}">
                                @else
                                    <div class="no-image">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <circle cx="12" cy="12" r="10"></circle>
                                        </svg>
                                    </div>
                                @endif
                                <div class="resolved-badge">Resolved</div>
                            </div>
                            
                            <div class="listing-content">
                                <h3 class="listing-title">{{ $listing->pet_name }}</h3>
                                
                                <div class="listing-meta">
                                    <span class="meta-item">{{ ucfirst($listing->pet_type) }}</span>
                                    <span class="meta-item">{{ $listing->location }}</span>
                                </div>
                                
                                <p class="listing-description">{{ Str::limit($listing->description, 80) }}</p>
                                
                                <div class="listing-actions">
                                    <button class="btn-view" onclick="viewListing({{ $listing->id }})">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        </svg>
                                        View Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="pagination-wrapper mt-4">
                    {{ $resolvedListings->links() }}
                </div>
            @else
                <div class="empty-state">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <h3>No Resolved Cases</h3>
                    <p>No cases have been resolved yet</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    :root {
        --primary: #2563eb;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --gray: #64748b;
        --gray-light: #f1f5f9;
    }

    .lost-found-admin-page {
        background: var(--gray-light);
        min-height: 100vh;
    }

    .page-header .label {
        display: block;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--primary);
        margin-bottom: 8px;
        font-weight: 600;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .page-subtitle {
        color: var(--gray);
        font-size: 1rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
    }

    .stat-card {
        background: white;
        padding: 24px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon.pending { background: #fef3c7; color: #f59e0b; }
    .stat-icon.active { background: #dbeafe; color: #2563eb; }
    .stat-icon.resolved { background: #d1fae5; color: #10b981; }
    .stat-icon.claims { background: #f3e8ff; color: #8b5cf6; }

    .stat-number {
        font-size: 1.875rem;
        font-weight: 700;
        color: #0f172a;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--gray);
    }

    .tabs-container {
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 8px;
    }

    .tabs {
        display: flex;
        gap: 8px;
    }

    .tab-btn {
        flex: 1;
        padding: 12px 20px;
        border: none;
        background: transparent;
        border-radius: 8px;
        font-weight: 600;
        color: var(--gray);
        cursor: pointer;
        transition: all 0.2s;
    }

    .tab-btn.active {
        background: var(--primary);
        color: white;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .listings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
    }

    .listing-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        transition: all 0.3s;
    }

    .listing-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
    }

    .listing-card.resolved {
        opacity: 0.7;
    }

    .listing-image {
        position: relative;
        height: 220px;
        overflow: hidden;
        background: #f8fafc;
    }

    .listing-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .no-image {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #cbd5e1;
    }

    .type-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .type-badge.type-lost {
        background: #ef4444;
        color: white;
    }

    .type-badge.type-found {
        background: #10b981;
        color: white;
    }

    .featured-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        background: #8b5cf6;
        color: white;
    }

    .resolved-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        background: #64748b;
        color: white;
    }

    .listing-content {
        padding: 20px;
    }

    .listing-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 12px;
    }

    .listing-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 12px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 0.875rem;
        color: var(--gray);
    }

    .listing-description {
        color: var(--gray);
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 16px;
    }

    .listing-submitter {
        font-size: 0.85rem;
        color: var(--gray);
        padding: 12px;
        background: #f8fafc;
        border-radius: 8px;
        margin-bottom: 16px;
    }

    .listing-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .listing-actions button {
        flex: 1;
        min-width: fit-content;
        padding: 10px 16px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-view {
        background: #f1f5f9;
        color: #475569;
    }

    .btn-view:hover {
        background: #e2e8f0;
    }

    .btn-approve {
        background: #10b981;
        color: white;
    }

    .btn-approve:hover {
        background: #059669;
    }

    .btn-reject {
        background: #ef4444;
        color: white;
    }

    .btn-reject:hover {
        background: #dc2626;
    }

    .btn-feature {
        background: #8b5cf6;
        color: white;
    }

    .btn-feature:hover {
        background: #7c3aed;
    }

    .btn-claims {
        background: #8b5cf6;
        color: white;
    }

    .btn-claims:hover {
        background: #7c3aed;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .empty-state svg {
        color: #cbd5e1;
        margin-bottom: 16px;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: var(--gray);
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .tabs {
            flex-direction: column;
        }

        .listings-grid {
            grid-template-columns: 1fr;
        }

        .listing-actions {
            flex-direction: column;
        }

        .listing-actions button {
            width: 100%;
        }
    }
</style>

<script>
    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            this.classList.add('active');
            document.getElementById(this.dataset.tab + '-tab').classList.add('active');
        });
    });

    function viewListing(id) {
        window.location.href = `/admin/lost-found/${id}`;
    }

    function approveListing(id) {
        if (confirm('Approve this listing?')) {
            fetch(`/admin/lost-found/${id}/approve`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    }

    function rejectListing(id) {
        const reason = prompt('Enter rejection reason:');
        if (reason) {
            fetch(`/admin/lost-found/${id}/reject`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    }

    function toggleFeature(id, currentlyFeatured) {
        fetch(`/admin/lost-found/${id}/toggle-feature`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }

    function viewClaims(id) {
        window.location.href = `/admin/lost-found/${id}/claims`;
    }
</script>
@endsection
