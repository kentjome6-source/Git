@extends('layouts.app')

@section('title', 'Adoption History')

@section('content')
<div class="history-page">
    <div class="container-fluid px-4 py-4">
        <!-- Header -->
        <div class="page-header mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="page-title mb-1">Adoption History</h1>
                    <p class="page-subtitle text-muted mb-0">View your adoption activity</p>
                </div>
                <a href="{{ route('adoptions.index') }}" class="btn btn-primary">
                    <i class="fas fa-heart me-2"></i>Browse Pets
                </a>
            </div>
        </div>

        @if($adoptions->count() > 0)
        <!-- History Grid -->
        <div class="row g-4">
            @foreach($adoptions as $adoption)
            <div class="col-lg-4 col-md-6">
                <div class="history-card">
                    <!-- Pet Image -->
                    <div class="history-image">
                        @if($adoption->image_path)
                            <img src="{{ asset('storage/' . $adoption->image_path) }}" alt="{{ $adoption->pet_name }}">
                        @else
                            <img src="{{ asset('images/pawpatrol.jpg') }}" alt="{{ $adoption->pet_name }}">
                        @endif
                        <div class="history-status">
                            @if($adoption->status === 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($adoption->status === 'published')
                                <span class="badge bg-primary">Active</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($adoption->status) }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Pet Info -->
                    <div class="history-content">
                        <h5 class="history-pet-name">{{ $adoption->pet_name }}</h5>
                        
                        <div class="history-meta mb-3">
                            @if($adoption->breed)
                            <span class="meta-item">
                                <i class="fas fa-tag"></i>
                                {{ $adoption->breed }}
                            </span>
                            @endif
                            <span class="meta-item">
                                <i class="fas fa-birthday-cake"></i>
                                {{ $adoption->age }} years
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-calendar"></i>
                                {{ $adoption->created_at->format('M d, Y') }}
                            </span>
                        </div>

                        @if($adoption->description)
                        <p class="history-description">
                            {{ Str::limit($adoption->description, 80) }}
                        </p>
                        @endif

                        <!-- Actions -->
                        <div class="history-actions mt-3">
                            <a href="{{ route('adoptions.show', $adoption) }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-eye me-2"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($adoptions->hasPages())
        <div class="pagination-wrapper mt-5">
            {{ $adoptions->links() }}
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-history"></i>
            </div>
            <h3 class="empty-state-title">No Adoption History</h3>
            <p class="empty-state-text">
                You don't have any adoption history yet.<br>
                Start by listing a pet for adoption or applying to adopt.
            </p>
            <a href="{{ route('adoptions.index') }}" class="btn btn-primary mt-3">
                <i class="fas fa-heart me-2"></i>Browse Available Pets
            </a>
        </div>
        @endif
    </div>
</div>

<style>
:root {
    --primary: #2563eb;
    --success: #10b981;
    --dark: #1e293b;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-600: #475569;
    --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
}

.history-page {
    background: var(--gray-50);
    min-height: 100vh;
    padding-bottom: 2rem;
}

.page-header {
    animation: fadeInDown 0.5s ease-out;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--dark);
}

.page-subtitle {
    color: var(--gray-600);
}

.history-card {
    background: white;
    border-radius: 0.75rem;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: all 0.3s;
    animation: fadeInUp 0.5s ease-out backwards;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.history-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.col-lg-4:nth-child(1) .history-card { animation-delay: 0.05s; }
.col-lg-4:nth-child(2) .history-card { animation-delay: 0.1s; }
.col-lg-4:nth-child(3) .history-card { animation-delay: 0.15s; }
.col-lg-4:nth-child(4) .history-card { animation-delay: 0.2s; }
.col-lg-4:nth-child(5) .history-card { animation-delay: 0.25s; }
.col-lg-4:nth-child(6) .history-card { animation-delay: 0.3s; }

.history-image {
    position: relative;
    width: 100%;
    padding-top: 75%;
    overflow: hidden;
    background: var(--gray-100);
}

.history-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.history-card:hover .history-image img {
    transform: scale(1.05);
}

.history-status {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
}

.history-status .badge {
    padding: 0.375rem 0.75rem;
    font-weight: 600;
    font-size: 0.75rem;
    border-radius: 0.375rem;
    text-transform: uppercase;
}

.history-content {
    padding: 1.25rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.history-pet-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 0.75rem;
}

.history-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.meta-item {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.875rem;
    color: var(--gray-600);
}

.meta-item i {
    color: var(--primary);
}

.history-description {
    font-size: 0.9375rem;
    color: var(--gray-600);
    line-height: 1.6;
    flex: 1;
}

.history-actions {
    margin-top: auto;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    animation: fadeIn 0.6s ease-out;
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--gray-100);
    border-radius: 50%;
}

.empty-state-icon i {
    font-size: 2.5rem;
    color: var(--gray-600);
}

.empty-state-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
}

.empty-state-text {
    color: var(--gray-600);
    max-width: 500px;
    margin: 0 auto;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    animation: fadeIn 0.8s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
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

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .page-title {
        font-size: 1.5rem;
    }
}
</style>
@endsection
