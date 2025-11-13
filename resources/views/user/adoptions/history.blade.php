@extends('layouts.app')

@section('title', 'Adoption History')

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center mx-0">
        <div class="col-12 col-lg-10 px-0">
            <!-- Header Section -->
            <div class="row mb-4 mx-0 mx-md-2">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h2 class="mb-1" id="page-heading">Adoption History</h2>
                            <p class="text-muted mb-0">View your adoption activities within the Adoption Center</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('adoptions.index') }}" class="btn btn-primary" role="button" aria-label="Back to Adoption Center">
                                <i class="fas fa-heart me-2" aria-hidden="true"></i>Back to Adoption Center
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs for different history views -->
            <div class="row mx-0 mx-md-2 mb-4">
                <div class="col-12">
                    <ul class="nav nav-tabs" id="adoptionHistoryTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="adopter-tab" data-bs-toggle="tab" data-bs-target="#adopter" type="button" role="tab" aria-controls="adopter" aria-selected="true">
                                Pets I've Adopted
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="uploader-tab" data-bs-toggle="tab" data-bs-target="#uploader" type="button" role="tab" aria-controls="uploader" aria-selected="false">
                                Pets I've Uploaded
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="tab-content" id="adoptionHistoryTabsContent">
                <!-- Adopter History Tab -->
                <div class="tab-pane fade show active" id="adopter" role="tabpanel" aria-labelledby="adopter-tab">
                    <div class="row mx-0 mx-md-2">
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
                                                    <th scope="col">Adoption Date</th>
                                                    <th scope="col">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($adoptedPetsAsAdopter as $history)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @if($history->adoption->image_path)
                                                            <img src="{{ asset('storage/' . $history->adoption->image_path) }}" 
                                                                 class="me-2 rounded" 
                                                                 alt="Photo of {{ $history->adoption->pet_name }}" 
                                                                 width="40" height="40"
                                                                 style="object-fit: cover;" loading="lazy">
                                                            @else
                                                            <div class="bg-light d-flex align-items-center justify-content-center me-2 rounded" 
                                                                 style="width: 40px; height: 40px;" role="img" aria-label="No image available">
                                                                <i class="fas fa-paw text-muted" aria-hidden="true"></i>
                                                            </div>
                                                            @endif
                                                            <div>
                                                                <div class="fw-bold">{{ $history->adoption->pet_name }}</div>
                                                                @if($history->adoption->breed)
                                                                <small class="text-muted">{{ $history->adoption->breed }}</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($history->uploader)
                                                        <div class="fw-bold">{{ $history->uploader->name }}</div>
                                                        <small class="text-muted">{{ $history->uploader->email }}</small>
                                                        @else
                                                        <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <time datetime="{{ $history->adopted_at->toIso8601String() }}">
                                                            {{ $history->adopted_at->format('M d, Y') }}
                                                        </time>
                                                        <br>
                                                        <small class="text-muted">{{ $history->adopted_at->format('h:i A') }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success" role="status" aria-label="Adopted">Adopted</span>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-5">
                                                        <i class="fas fa-heart fa-3x text-muted mb-3" aria-hidden="true"></i>
                                                        <h5 id="no-records-heading">No adopted pets</h5>
                                                        <p class="text-muted">You haven't adopted any pets yet.</p>
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <!-- Mobile Card View -->
                                    <div class="d-md-none">
                                        @forelse($adoptedPetsAsAdopter as $history)
                                        <div class="card mb-3 border shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    @if($history->adoption->image_path)
                                                    <img src="{{ asset('storage/' . $history->adoption->image_path) }}" 
                                                         class="me-3 rounded" 
                                                         alt="Photo of {{ $history->adoption->pet_name }}" 
                                                         width="60" height="60"
                                                         style="object-fit: cover;" loading="lazy">
                                                    @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center me-3 rounded" 
                                                         style="width: 60px; height: 60px;" role="img" aria-label="No image available">
                                                        <i class="fas fa-paw text-muted" aria-hidden="true"></i>
                                                    </div>
                                                    @endif
                                                    <div>
                                                        <h5 class="mb-1">{{ $history->adoption->pet_name }}</h5>
                                                        @if($history->adoption->breed)
                                                        <small class="text-muted">{{ $history->adoption->breed }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="row g-2">
                                                    <div class="col-12">
                                                        <div class="d-flex justify-content-between">
                                                            <span class="text-muted">Listed By:</span>
                                                            <span>
                                                                @if($history->uploader)
                                                                <div class="fw-bold">{{ $history->uploader->name }}</div>
                                                                <small class="text-muted">{{ $history->uploader->email }}</small>
                                                                @else
                                                                <span class="text-muted">N/A</span>
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-12">
                                                        <div class="d-flex justify-content-between">
                                                            <span class="text-muted">Adoption Date:</span>
                                                            <span>
                                                                <time datetime="{{ $history->adopted_at->toIso8601String() }}">
                                                                    {{ $history->adopted_at->format('M d, Y') }}
                                                                </time>
                                                                <br>
                                                                <small class="text-muted">{{ $history->adopted_at->format('h:i A') }}</small>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-12">
                                                        <div class="d-flex justify-content-between">
                                                            <span class="text-muted">Status:</span>
                                                            <span>
                                                                <span class="badge bg-success" role="status" aria-label="Adopted">Adopted</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                        <div class="text-center py-5">
                                            <i class="fas fa-heart fa-3x text-muted mb-3" aria-hidden="true"></i>
                                            <h5 id="no-records-heading">No adopted pets</h5>
                                            <p class="text-muted">You haven't adopted any pets yet.</p>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Uploader History Tab -->
                <div class="tab-pane fade" id="uploader" role="tabpanel" aria-labelledby="uploader-tab">
                    <div class="row mx-0 mx-md-2">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <!-- Desktop Table View -->
                                    <div class="table-responsive d-none d-md-block" role="region" aria-labelledby="adoption-uploader-table-desc" tabindex="0">
                                        <p id="adoption-uploader-table-desc" class="sr-only">Uploaded adoption records table with sortable columns</p>
                                        <table class="table table-hover" aria-describedby="adoption-uploader-table-desc">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Pet Name</th>
                                                    <th scope="col">Adopted By</th>
                                                    <th scope="col">Adoption Date</th>
                                                    <th scope="col">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($adoptedPetsAsUploader as $history)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @if($history->adoption->image_path)
                                                            <img src="{{ asset('storage/' . $history->adoption->image_path) }}" 
                                                                 class="me-2 rounded" 
                                                                 alt="Photo of {{ $history->adoption->pet_name }}" 
                                                                 width="40" height="40"
                                                                 style="object-fit: cover;" loading="lazy">
                                                            @else
                                                            <div class="bg-light d-flex align-items-center justify-content-center me-2 rounded" 
                                                                 style="width: 40px; height: 40px;" role="img" aria-label="No image available">
                                                                <i class="fas fa-paw text-muted" aria-hidden="true"></i>
                                                            </div>
                                                            @endif
                                                            <div>
                                                                <div class="fw-bold">{{ $history->adoption->pet_name }}</div>
                                                                @if($history->adoption->breed)
                                                                <small class="text-muted">{{ $history->adoption->breed }}</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($history->adopter)
                                                        <div class="fw-bold">{{ $history->adopter->name }}</div>
                                                        <small class="text-muted">{{ $history->adopter->email }}</small>
                                                        @else
                                                        <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <time datetime="{{ $history->adopted_at->toIso8601String() }}">
                                                            {{ $history->adopted_at->format('M d, Y') }}
                                                        </time>
                                                        <br>
                                                        <small class="text-muted">{{ $history->adopted_at->format('h:i A') }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success" role="status" aria-label="Adopted">Adopted</span>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-5">
                                                        <i class="fas fa-heart fa-3x text-muted mb-3" aria-hidden="true"></i>
                                                        <h5 id="no-records-heading">No adopted pets</h5>
                                                        <p class="text-muted">None of your pets have been adopted yet.</p>
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <!-- Mobile Card View -->
                                    <div class="d-md-none">
                                        @forelse($adoptedPetsAsUploader as $history)
                                        <div class="card mb-3 border shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    @if($history->adoption->image_path)
                                                    <img src="{{ asset('storage/' . $history->adoption->image_path) }}" 
                                                         class="me-3 rounded" 
                                                         alt="Photo of {{ $history->adoption->pet_name }}" 
                                                         width="60" height="60"
                                                         style="object-fit: cover;" loading="lazy">
                                                    @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center me-3 rounded" 
                                                         style="width: 60px; height: 60px;" role="img" aria-label="No image available">
                                                        <i class="fas fa-paw text-muted" aria-hidden="true"></i>
                                                    </div>
                                                    @endif
                                                    <div>
                                                        <h5 class="mb-1">{{ $history->adoption->pet_name }}</h5>
                                                        @if($history->adoption->breed)
                                                        <small class="text-muted">{{ $history->adoption->breed }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="row g-2">
                                                    <div class="col-12">
                                                        <div class="d-flex justify-content-between">
                                                            <span class="text-muted">Adopted By:</span>
                                                            <span>
                                                                @if($history->adopter)
                                                                <div class="fw-bold">{{ $history->adopter->name }}</div>
                                                                <small class="text-muted">{{ $history->adopter->email }}</small>
                                                                @else
                                                                <span class="text-muted">N/A</span>
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-12">
                                                        <div class="d-flex justify-content-between">
                                                            <span class="text-muted">Adoption Date:</span>
                                                            <span>
                                                                <time datetime="{{ $history->adopted_at->toIso8601String() }}">
                                                                    {{ $history->adopted_at->format('M d, Y') }}
                                                                </time>
                                                                <br>
                                                                <small class="text-muted">{{ $history->adopted_at->format('h:i A') }}</small>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-12">
                                                        <div class="d-flex justify-content-between">
                                                            <span class="text-muted">Status:</span>
                                                            <span>
                                                                <span class="badge bg-success" role="status" aria-label="Adopted">Adopted</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                        <div class="text-center py-5">
                                            <i class="fas fa-heart fa-3x text-muted mb-3" aria-hidden="true"></i>
                                            <h5 id="no-records-heading">No adopted pets</h5>
                                            <p class="text-muted">None of your pets have been adopted yet.</p>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
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

.badge.bg-success {
    color: #fff !important;
}

/* Tab styles */
.nav-tabs .nav-link {
    border: 1px solid transparent;
    border-top-left-radius: 0.375rem;
    border-top-right-radius: 0.375rem;
}

.nav-tabs .nav-link.active {
    color: #495057;
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
}

.nav-tabs .nav-link:hover {
    border-color: #e9ecef #e9ecef #dee2e6;
}
</style>
@endsection