@extends('layouts.admin')

@section('title', 'Lost & Found Management')

@section('styles')
<style>
    .admin-header {
        background: #e74c3c; padding: 30px; border-radius: 15px; margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    .admin-title { font-size: 2.5rem; color: white; margin-bottom: 10px; }
    .admin-subtitle { font-size: 1.1rem; color: white; opacity: 0.9; }

    .stats-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px; margin-bottom: 40px;
    }
    .stat-card {
        background: #fff; padding: 25px; border-radius: 12px;
        text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-left: 4px solid #5b4b9b;
    }
    .stat-card.lost { border-left-color: #e74c3c; }
    .stat-card.found { border-left-color: #27ae60; }
    .stat-number {
        font-size: 2.5rem; font-weight: 700; margin-bottom: 10px;
    }
    .stat-card.lost .stat-number { color: #e74c3c; }
    .stat-card.found .stat-number { color: #27ae60; }
    .stat-label {
        font-size: 1rem; color: #666; text-transform: uppercase;
        font-weight: 600;
    }

    .section-header {
        display: flex; justify-content: between; align-items: center;
        margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #eee;
    }
    .section-title {
        font-size: 1.5rem; font-weight: 600; color: #333;
        display: flex; align-items: center; gap: 10px;
    }
    .section-count {
        background: #5b4b9b; color: #fff; padding: 4px 12px;
        border-radius: 20px; font-size: 0.9rem; font-weight: 600;
    }

    /* Card-based layout for listings */
    .listings-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px; margin-bottom: 40px;
    }
    .listing-card {
        background: #fff; border-radius: 15px; overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08); transition: 0.2s;
    }
    .listing-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
    .listing-image {
        height: 200px; overflow: hidden; position: relative;
    }
    .listing-image img {
        width: 100%; height: 100%; object-fit: cover;
    }
    .listing-image .no-image {
        display: flex; align-items: center; justify-content: center;
        height: 100%; background: #f8f9fa; color: #999; font-size: 3rem;
    }
    .type-badge {
        position: absolute; top: 15px; right: 15px;
        padding: 6px 12px; border-radius: 20px; font-size: 0.8rem;
        font-weight: 600; text-transform: uppercase;
    }
    .type-badge.lost { background: #e74c3c; color: #fff; }
    .type-badge.found { background: #27ae60; color: #fff; }
    .listing-content {
        padding: 25px;
    }
    .listing-title {
        font-size: 1.3rem; font-weight: 700; color: #333;
        margin-bottom: 10px;
    }
    .listing-meta {
        display: flex; flex-wrap: wrap; gap: 15px;
        margin-bottom: 15px;
    }
    .meta-item {
        display: flex; align-items: center; gap: 5px;
        font-size: 0.9rem; color: #666;
    }
    .listing-description {
        color: #555; margin-bottom: 20px;
        display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .listing-footer {
        display: flex; justify-content: between; align-items: center;
    }
    .listing-date {
        font-size: 0.85rem; color: #999;
    }
    .btn-view {
        padding: 8px 16px; background: #5b4b9b; color: #fff;
        border-radius: 6px; text-decoration: none; font-weight: 600;
        font-size: 0.9rem; transition: 0.2s;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .btn-view:hover { background: #4a3d7a; }

    .status-badge {
        padding: 6px 12px; border-radius: 20px; font-size: 0.8rem;
        font-weight: 600; text-transform: uppercase;
    }
    .status-badge.lost { background: #e74c3c; color: #fff; }
    .status-badge.found { background: #27ae60; color: #fff; }
    .status-badge.resolved { background: #27ae60; color: #fff; }
    .status-badge.unresolved { background: #e74c3c; color: #fff; }

    .action-buttons {
        display: flex; gap: 8px;
    }
    .btn {
        padding: 8px 16px; border: none; border-radius: 6px;
        text-decoration: none; font-weight: 600; cursor: pointer;
        transition: 0.2s; display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.85rem;
    }
    .btn-primary { background: #5b4b9b; color: #fff; }
    .btn-primary:hover { background: #4a3d7a; }
    .btn-success { background: #27ae60; color: #fff; }
    .btn-success:hover { background: #229954; }
    .btn-danger { background: #e74c3c; color: #fff; }
    .btn-danger:hover { background: #c0392b; }
    .btn-secondary { background: #6c757d; color: #fff; }
    .btn-secondary:hover { background: #5a6268; }

    .alert {
        padding: 15px 20px; border-radius: 8px; margin-bottom: 20px;
        font-weight: 500;
    }
    .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }

    .pagination {
        display: flex; justify-content: center; gap: 10px; margin-top: 20px;
    }
    .pagination a, .pagination span {
        padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px;
        text-decoration: none; color: #666; transition: 0.2s;
    }
    .pagination a:hover { background: #5b4b9b; color: #fff; border-color: #5b4b9b; }
    .pagination .current { background: #5b4b9b; color: #fff; border-color: #5b4b9b; }
    
    /* Swipeable table for mobile - consistent with other admin views */
    .swipeable-table-container {
        display: none;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding: 10px 0;
    }
    
    .swipeable-table-row {
        display: flex;
        flex-direction: column;
        border: 1px solid #eee;
        border-radius: 10px;
        margin-bottom: 15px;
        background: #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .swipeable-table-item {
        padding: 12px 15px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
    }
    
    .swipeable-table-item:last-child {
        border-bottom: none;
    }
    
    .swipeable-table-label {
        font-weight: 600;
        color: #5b4b9b;
        min-width: 100px;
    }
    
    .swipeable-table-value {
        text-align: right;
        flex: 1;
    }
    
    .swipeable-status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .swipeable-action-buttons {
        display: flex;
        gap: 8px;
        padding: 10px 15px;
    }
    
    .swipeable-action-buttons .btn {
        flex: 1;
        justify-content: center;
        padding: 8px;
        font-size: 0.8rem;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        .admin-header {
            padding: 20px 15px;
        }
        
        .admin-title {
            font-size: 2rem;
        }
        
        @media (max-width: 768px) {
            .admin-header {
                background: #e74c3c;
            }
            
            .admin-title {
                color: white;
            }
            
            .admin-subtitle {
                color: white;
            }
        }
        
        .stats-grid {
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .stat-card {
            padding: 20px 15px;
        }
        
        .stat-number {
            font-size: 2rem;
        }
        
        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        /* Hide card grid on mobile */
        .listings-grid {
            display: none;
        }
        
        /* Show swipeable table on mobile */
        .swipeable-table-container {
            display: block;
        }
        
        .table th,
        .table td {
            padding: 10px 8px;
            font-size: 0.9rem;
        }
        
        .pet-info {
            gap: 10px;
        }
        
        .pet-details h4 {
            font-size: 0.95rem;
        }
        
        .pet-details p {
            font-size: 0.8rem;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 5px;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
            padding: 6px 12px;
            font-size: 0.8rem;
        }
        
        .status-badge {
            font-size: 0.7rem;
            padding: 4px 8px;
        }
    }
    
    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .section-title {
            font-size: 1.25rem;
        }
        
        .admin-title {
            font-size: 1.75rem;
        }
        
        .admin-subtitle {
            font-size: 1rem;
        }
        
        .table th,
        .table td {
            padding: 8px 6px;
            font-size: 0.85rem;
        }
        
        .pet-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
    }
    
    /* Desktop view - hide swipeable table */
    @media (min-width: 769px) {
        .swipeable-table-container {
            display: none !important;
        }
        .listings-grid {
            display: grid !important;
        }
    }
</style>
@endsection

@section('content')
    <div class="admin-header">
        <h1 class="admin-title">Lost & Found Management</h1>
        <p class="admin-subtitle">Manage lost and found pet listings</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
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

    <!-- All Listings -->
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-paw"></i>
            All Listings
            <span class="section-count">{{ $lostFoundItems->count() }}</span>
        </h2>
    </div>
    
    @if($lostFoundItems->count() > 0)
        <!-- Card-based layout for desktop -->
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
                                {{ ucfirst($listing->pet_type) }} @if($listing->breed) - {{ $listing->breed }} @endif
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $listing->location }}
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-calendar"></i>
                                {{ $listing->created_at->format('M d, Y') }}
                            </div>
                        </div>
                        <p class="listing-description">{{ Str::limit($listing->description, 100) }}</p>
                        <div class="listing-footer">
                            <div class="listing-date">
                                Submitted {{ $listing->created_at->diffForHumans() }}
                            </div>
                            <div class="action-buttons">
                                <a href="{{ route('admin.lost-found.show', $listing) }}" class="btn btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Swipeable table for mobile - consistent with other admin views -->
        <div class="swipeable-table-container">
            @foreach($lostFoundItems as $listing)
                <div class="swipeable-table-row">
                    <div class="swipeable-table-item">
                        <span class="swipeable-table-label">Pet</span>
                        <span class="swipeable-table-value">{{ $listing->pet_name }}</span>
                    </div>
                    <div class="swipeable-table-item">
                        <span class="swipeable-table-label">Type</span>
                        <span class="swipeable-table-value">
                            <span class="swipeable-status-badge {{ $listing->type }}">{{ $listing->type }}</span>
                        </span>
                    </div>
                    <div class="swipeable-table-item">
                        <span class="swipeable-table-label">Location</span>
                        <span class="swipeable-table-value">{{ $listing->location }}</span>
                    </div>
                    <div class="swipeable-table-item">
                        <span class="swipeable-table-label">Status</span>
                        <span class="swipeable-table-value">
                            @if($listing->is_resolved)
                                <span class="swipeable-status-badge resolved">Resolved</span>
                            @else
                                <span class="swipeable-status-badge unresolved">Unresolved</span>
                            @endif
                        </span>
                    </div>
                    <div class="swipeable-table-item">
                        <span class="swipeable-table-label">Submitted</span>
                        <span class="swipeable-table-value">{{ $listing->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="swipeable-action-buttons">
                        <a href="{{ route('admin.lost-found.show', $listing) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="pagination">
            {{ $lostFoundItems->links() }}
        </div>
    @else
        <div style="padding: 40px; text-align: center; color: #666; background: #fff; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
            <i class="fas fa-paw" style="font-size: 3rem; color: #5b4b9b; margin-bottom: 15px;"></i>
            <h3>No listings yet</h3>
            <p>There are no lost or found pet listings at this time.</p>
        </div>
    @endif
@endsection