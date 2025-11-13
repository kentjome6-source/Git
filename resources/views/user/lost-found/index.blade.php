@extends('layouts.app')

@section('title', 'Lost & Found Pets')

@section('styles')
<style>
        
        .page-header {
            text-align: center; margin-bottom: 40px;
        }
        .page-title { font-size: 2.5rem; color: #5b4b9b; margin-bottom: 10px; }
        .page-subtitle { font-size: 1.1rem; color: #666; }

        .section-header {
            display: flex; justify-content: between; align-items: center;
            margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #eee;
        }
        .section-title {
            font-size: 1.5rem; font-weight: 600; color: #333;
            display: flex; align-items: center; gap: 10px;
        }

        .filter-form {
            background: #fff; padding: 25px; border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08); margin-bottom: 30px;
        }
        .form-row {
            display: grid; grid-template-columns: 1fr 1fr auto; gap: 20px;
            align-items: end;
        }
        .form-group {
            margin-bottom: 0;
        }
        .form-label {
            display: block; margin-bottom: 8px; font-weight: 600; color: #333;
        }
        .form-select {
            width: 100%; padding: 12px 15px; border: 2px solid #ddd;
            border-radius: 8px; font-size: 1rem;
        }
        .btn {
            padding: 12px 24px; border: none; border-radius: 8px;
            text-decoration: none; font-weight: 600; cursor: pointer;
            transition: 0.2s; display: inline-flex; align-items: center; gap: 8px;
            font-size: 1rem;
        }
        .btn-primary { background: #5b4b9b; color: #fff; }
        .btn-primary:hover { background: #4a3d7a; }
        .btn-secondary { background: #6c757d; color: #fff; }
        .btn-secondary:hover { background: #5a6268; }

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
        }
        .btn-view:hover { background: #4a3d7a; }

        .pagination {
            display: flex; justify-content: center; gap: 10px;
        }
        .pagination a, .pagination span {
            padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px;
            text-decoration: none; color: #666; transition: 0.2s;
        }
        .pagination a:hover { background: #5b4b9b; color: #fff; border-color: #5b4b9b; }
        .pagination .current { background: #5b4b9b; color: #fff; border-color: #5b4b9b; }

        .alert {
            padding: 15px 20px; border-radius: 8px; margin-bottom: 20px;
            font-weight: 500;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }

        /* Responsive styles */
        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }
            
            .page-subtitle {
                font-size: 1rem;
            }
            
            .filter-form {
                padding: 20px 15px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
                padding: 10px 15px;
                font-size: 0.9rem;
                margin-bottom: 10px; /* Add spacing between buttons */
            }
            
            /* Ensure buttons stack properly on mobile */
            .page-header .btn + .btn {
                margin-left: 0;
                margin-top: 10px;
            }
            
            .listings-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .listing-image {
                height: 180px;
            }
            
            .listing-content {
                padding: 20px;
            }
            
            .listing-title {
                font-size: 1.2rem;
            }
            
            .listing-meta {
                gap: 10px;
            }
            
            .meta-item {
                font-size: 0.85rem;
            }
            
            .btn-view {
                padding: 6px 12px;
                font-size: 0.85rem;
            }
        }
        
        @media (max-width: 576px) {
            .page-header {
                margin-bottom: 30px;
            }
            
            .page-title {
                font-size: 1.75rem;
            }
            
            .listing-image {
                height: 160px;
            }
            
            .type-badge {
                top: 10px;
                right: 10px;
                padding: 4px 8px;
                font-size: 0.7rem;
            }
            
            .listing-content {
                padding: 15px;
            }
            
            .listing-title {
                font-size: 1.1rem;
            }
            
            .listing-description {
                font-size: 0.9rem;
            }
            
            .listing-footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .listing-date {
                font-size: 0.8rem;
            }
            
            .btn-view {
                width: 100%;
                text-align: center;
                justify-content: center;
            }
            
            /* Ensure content fits within mobile screen without horizontal scrolling */
            .container, .container-fluid {
                padding-left: 10px;
                padding-right: 10px;
            }
            
            .filter-form {
                padding: 15px 10px;
            }
            
            .listing-card {
                margin-left: 0;
                margin-right: 0;
            }
        }
        
        /* Additional fix to prevent horizontal scrolling */
        body {
            overflow-x: hidden;
        }
        
        .main-content {
            overflow-x: hidden;
        }
</style>
@endsection

@section('content')
        <div class="page-header">
            <h1 class="page-title">Lost & Found Pets</h1>
            <p class="page-subtitle">Help reunite pets with their families</p>
            <div class="button-group" style="margin-top: 20px; display: flex; justify-content: center; flex-wrap: wrap; gap: 10px;">
                <a href="{{ route('lost-found.create') }}" class="btn btn-primary">
                    <i class="fas fa-paw"></i> Report Pet
                </a>
                <a href="{{ route('view.map') }}" class="btn btn-secondary">
                    <i class="fas fa-map-marked-alt"></i> View Map
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="filter-form">
            <form action="{{ route('pet.lostfound') }}" method="GET">
                <div class="form-row">
                    <div class="form-group">
                        <label for="filter" class="form-label">Filter by Type</label>
                        <select id="filter" name="filter" class="form-select">
                            <option value="all" {{ request('filter', 'all') == 'all' ? 'selected' : '' }}>All Listings</option>
                            <option value="lost" {{ request('filter') == 'lost' ? 'selected' : '' }}>Lost Pets</option>
                            <option value="found" {{ request('filter') == 'found' ? 'selected' : '' }}>Found Pets</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sort" class="form-label">Sort by</label>
                        <select id="sort" name="sort" class="form-select">
                            <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if($lostFoundItems->count() > 0)
            <div class="listings-grid">
                @foreach($lostFoundItems as $item)
                    <div class="listing-card">
                        <div class="listing-image">
                            @if($item->image_path)
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->pet_name }}">
                            @else
                                <div class="no-image">
                                    <i class="fas fa-paw"></i>
                                </div>
                            @endif
                            <div class="type-badge {{ $item->type }}">{{ $item->type }}</div>
                        </div>
                        <div class="listing-content">
                            <h3 class="listing-title">{{ $item->pet_name }}</h3>
                            <div class="listing-meta">
                                <div class="meta-item">
                                    <i class="fas fa-paw"></i>
                                    {{ ucfirst($item->pet_type) }}
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $item->location }}
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-calendar"></i>
                                    {{ $item->date_lost_found->format('M d, Y') }}
                                </div>
                            </div>
                            <p class="listing-description">{{ $item->description }}</p>
                            <div class="listing-footer">
                                <div class="listing-date">
                                    Posted {{ $item->created_at->diffForHumans() }}
                                </div>
                                <a href="{{ route('lost-found.show', $item) }}" class="btn-view">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination">
                {{ $lostFoundItems->appends(request()->query())->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px; background: #fff; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
                <i class="fas fa-paw" style="font-size: 4rem; color: #5b4b9b; margin-bottom: 20px;"></i>
                <h3 style="font-size: 1.8rem; color: #333; margin-bottom: 15px;">No listings found</h3>
                <p style="font-size: 1.1rem; color: #666; margin-bottom: 30px;">
                    @if(request('filter') == 'lost')
                        There are no lost pet listings at this time.
                    @elseif(request('filter') == 'found')
                        There are no found pet listings at this time.
                    @else
                        There are no lost or found pet listings at this time.
                    @endif
                </p>
            </div>
        @endif
@endsection