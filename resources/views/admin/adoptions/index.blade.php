@extends('layouts.admin')

@section('title', 'Adoption History')

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center mx-0">
        <div class="col-12 col-lg-10 px-0">
            <!-- Header Section -->
            <div class="row mb-4 mx-2 mx-md-0">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h2 class="mb-1" id="page-heading">Adoption History</h2>
                            <p class="text-muted mb-0">View all adoption activities across the platform</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary" role="button" aria-label="Back to Dashboard">
                                <i class="fas fa-arrow-left me-2" aria-hidden="true"></i>Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Adoption Records Table -->
            <div class="row mx-2 mx-md-0">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <!-- Desktop Table View -->
                            <div class="table-responsive d-none d-md-block" role="region" aria-labelledby="adoption-table-desc" tabindex="0">
                                <p id="adoption-table-desc" class="sr-only">Adoption records table with sortable columns</p>
                                <table class="table table-hover" aria-describedby="adoption-table-desc">
                                    <thead>
                                        <tr>
                                            <th scope="col">Pet Name</th>
                                            <th scope="col">Listed By</th>
                                            <th scope="col">Adopted By</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Listed Date</th>
                                            <th scope="col">Adoption Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($adoptions as $adoption)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($adoption->image_path)
                                                    <img src="{{ asset('storage/' . $adoption->image_path) }}" 
                                                         class="me-2 rounded" 
                                                         alt="Photo of {{ $adoption->pet_name }}" 
                                                         width="40" height="40"
                                                         style="object-fit: cover;" loading="lazy">
                                                    @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center me-2 rounded" 
                                                         style="width: 40px; height: 40px;" role="img" aria-label="No image available">
                                                        <i class="fas fa-paw text-muted" aria-hidden="true"></i>
                                                    </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">{{ $adoption->pet_name }}</div>
                                                        @if($adoption->breed)
                                                        <small class="text-muted">{{ $adoption->breed }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($adoption->user)
                                                <div class="fw-bold">{{ $adoption->user->name }}</div>
                                                <small class="text-muted">{{ $adoption->user->email }}</small>
                                                @else
                                                <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($adoption->is_adopted && $adoption->adoptionHistory && $adoption->adoptionHistory->adopter)
                                                <div class="fw-bold">{{ $adoption->adoptionHistory->adopter->name }}</div>
                                                <small class="text-muted">{{ $adoption->adoptionHistory->adopter->email }}</small>
                                                @elseif($adoption->is_adopted)
                                                <span class="text-muted">Unknown User</span>
                                                @else
                                                <span class="text-muted">Not Adopted</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($adoption->is_adopted)
                                                <span class="badge bg-success" role="status" aria-label="Adopted">Adopted</span>
                                                @else
                                                <span class="badge bg-warning" role="status" aria-label="Available">Available</span>
                                                @endif
                                            </td>
                                            <td>
                                                <time datetime="{{ $adoption->created_at->toIso8601String() }}">
                                                    {{ $adoption->created_at->format('M d, Y') }}
                                                </time>
                                                <br>
                                                <small class="text-muted">{{ $adoption->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                @if($adoption->is_adopted && $adoption->updated_at)
                                                <time datetime="{{ $adoption->updated_at->toIso8601String() }}">
                                                    {{ $adoption->updated_at->format('M d, Y') }}
                                                </time>
                                                <br>
                                                <small class="text-muted">{{ $adoption->updated_at->format('h:i A') }}</small>
                                                @else
                                                <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <i class="fas fa-paw fa-3x text-muted mb-3" aria-hidden="true"></i>
                                                <h5 id="no-records-heading">No adoption records found</h5>
                                                <p class="text-muted">There are no adoption activities yet.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Mobile Card View -->
                            <div class="d-md-none">
                                @forelse($adoptions as $adoption)
                                <div class="card mb-3 border shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            @if($adoption->image_path)
                                            <img src="{{ asset('storage/' . $adoption->image_path) }}" 
                                                 class="me-3 rounded" 
                                                 alt="Photo of {{ $adoption->pet_name }}" 
                                                 width="60" height="60"
                                                 style="object-fit: cover;" loading="lazy">
                                            @else
                                            <div class="bg-light d-flex align-items-center justify-content-center me-3 rounded" 
                                                 style="width: 60px; height: 60px;" role="img" aria-label="No image available">
                                                <i class="fas fa-paw text-muted" aria-hidden="true"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <h5 class="mb-1">{{ $adoption->pet_name }}</h5>
                                                @if($adoption->breed)
                                                <small class="text-muted">{{ $adoption->breed }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Listed By:</span>
                                                    <span>
                                                        @if($adoption->user)
                                                        <div class="fw-bold">{{ $adoption->user->name }}</div>
                                                        <small class="text-muted">{{ $adoption->user->email }}</small>
                                                        @else
                                                        <span class="text-muted">N/A</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Adopted By:</span>
                                                    <span>
                                                        @if($adoption->is_adopted && $adoption->adoptionHistory && $adoption->adoptionHistory->adopter)
                                                        <div class="fw-bold">{{ $adoption->adoptionHistory->adopter->name }}</div>
                                                        <small class="text-muted">{{ $adoption->adoptionHistory->adopter->email }}</small>
                                                        @elseif($adoption->is_adopted)
                                                        <span class="text-muted">Unknown User</span>
                                                        @else
                                                        <span class="text-muted">Not Adopted</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Status:</span>
                                                    <span>
                                                        @if($adoption->is_adopted)
                                                        <span class="badge bg-success" role="status" aria-label="Adopted">Adopted</span>
                                                        @else
                                                        <span class="badge bg-warning" role="status" aria-label="Available">Available</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Listed Date:</span>
                                                    <span>
                                                        <time datetime="{{ $adoption->created_at->toIso8601String() }}">
                                                            {{ $adoption->created_at->format('M d, Y') }}
                                                        </time>
                                                        <br>
                                                        <small class="text-muted">{{ $adoption->created_at->format('h:i A') }}</small>
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            @if($adoption->is_adopted && $adoption->updated_at)
                                            <div class="col-12">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Adoption Date:</span>
                                                    <span>
                                                        <time datetime="{{ $adoption->updated_at->toIso8601String() }}">
                                                            {{ $adoption->updated_at->format('M d, Y') }}
                                                        </time>
                                                        <br>
                                                        <small class="text-muted">{{ $adoption->updated_at->format('h:i A') }}</small>
                                                    </span>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-5">
                                    <i class="fas fa-paw fa-3x text-muted mb-3" aria-hidden="true"></i>
                                    <h5 id="no-records-heading">No adoption records found</h5>
                                    <p class="text-muted">There are no adoption activities yet.</p>
                                </div>
                                @endforelse
                            </div>
                            
                            <!-- Pagination -->
                            @if($adoptions->hasPages())
                            <div class="d-flex justify-content-center mt-4" role="navigation" aria-label="Pagination Navigation">
                                {{ $adoptions->links() }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Mobile-specific styles */
@media (max-width: 767.98px) {
    .card-body {
        padding: 1rem;
    }
    
    .d-flex.align-items-center {
        flex-direction: row;
    }
    
    .d-flex.align-items-center .me-3 {
        margin-right: 1rem !important;
    }
    
    h2 {
        font-size: 1.5rem;
    }
    
    h5 {
        font-size: 1.1rem;
    }
    
    .btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .row.g-2 {
        --bs-gutter-x: 0.5rem;
        --bs-gutter-y: 0.5rem;
    }
}

@media (max-width: 575.98px) {
    .card-body {
        padding: 0.75rem;
    }
    
    h2 {
        font-size: 1.35rem;
    }
    
    .btn {
        padding: 0.375rem 0.625rem;
        font-size: 0.8125rem;
    }
}

/* Focus indicators for keyboard navigation */
.btn:focus, 
a:focus, 
table a:focus {
    outline: 2px solid #5b4b9b;
    outline-offset: 2px;
}

/* Screen reader only class */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* Improved color contrast */
.text-muted {
    color: #6c757d !important;
}

.badge.bg-warning {
    color: #212529 !important;
}
</style>
@endsection