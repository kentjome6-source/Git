@extends('layouts.vet')

@section('title', 'Lost & Found Verifications')

@section('content')
<div class="verification-page">
    <div class="container-fluid px-4 py-4">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="h3 mb-1 fw-semibold text-dark">Lost & Found Verifications</h1>
                    <p class="text-muted mb-0">Verify pet ownership claims</p>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs mb-4">
            <div class="btn-group" role="group">
                <a href="{{ route('vet.lost-found.verifications', ['filter' => 'under_review']) }}" 
                   class="btn btn-sm {{ request('filter', 'under_review') == 'under_review' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    Pending Review
                </a>
                <a href="{{ route('vet.lost-found.verifications', ['filter' => 'verified']) }}" 
                   class="btn btn-sm {{ request('filter') == 'verified' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    Verified
                </a>
                <a href="{{ route('vet.lost-found.verifications', ['filter' => 'all']) }}" 
                   class="btn btn-sm {{ request('filter') == 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    All
                </a>
            </div>
        </div>

        <!-- Claims List -->
        @if($claims->count() > 0)
            <div class="row g-4">
                @foreach($claims as $claim)
                    <div class="col-12">
                        <div class="card border-0 shadow-sm hover-lift">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-2 text-center mb-3 mb-md-0">
                                        @if($claim->lostFound->image_path)
                                            <img src="{{ asset('storage/' . $claim->lostFound->image_path) }}" 
                                                 alt="Pet" 
                                                 class="rounded" 
                                                 style="width: 100px; height: 100px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 100px; height: 100px;">
                                                <i class="fas fa-paw fa-2x text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-7 mb-3 mb-md-0">
                                        <div class="d-flex align-items-start gap-2 mb-2">
                                            <span class="badge bg-{{ $claim->lostFound->type == 'lost' ? 'danger' : 'success' }}">
                                                {{ ucfirst($claim->lostFound->type) }}
                                            </span>
                                            <span class="badge bg-{{ 
                                                $claim->status == 'under_review' ? 'warning' : 
                                                ($claim->status == 'approved' ? 'success' : 
                                                ($claim->status == 'completed' ? 'info' : 'secondary'))
                                            }}">
                                                {{ ucfirst(str_replace('_', ' ', $claim->status)) }}
                                            </span>
                                        </div>
                                        <h5 class="mb-2">{{ $claim->lostFound->pet_name }}</h5>
                                        <p class="text-muted small mb-2">
                                            <strong>Claimer:</strong> {{ $claim->claimer->name }}<br>
                                            <strong>Pet Type:</strong> {{ $claim->lostFound->pet_type }}<br>
                                            <strong>Location:</strong> {{ $claim->lostFound->location }}
                                        </p>
                                        <p class="text-muted small mb-0">
                                            <i class="far fa-clock me-1"></i>
                                            Submitted {{ $claim->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="col-md-3 text-md-end">
                                        <a href="{{ route('vet.lost-found.verifications.show', $claim) }}" 
                                           class="btn btn-primary btn-sm w-100 w-md-auto">
                                            <i class="fas fa-eye me-1"></i> View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $claims->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-clipboard-list fa-4x text-muted"></i>
                </div>
                <h5 class="text-muted">No claims to verify</h5>
                <p class="text-muted">Claims pending verification will appear here</p>
            </div>
        @endif
    </div>
</div>

<style>
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

@media (max-width: 768px) {
    .filter-tabs .btn-group {
        width: 100%;
    }
    
    .filter-tabs .btn {
        flex: 1;
    }
}
</style>
@endsection
