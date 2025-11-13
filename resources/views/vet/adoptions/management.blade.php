@extends('layouts.vet')

@section('title', 'Vet Adoption Center')

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center mx-0">
        <div class="col-12 col-lg-10 px-0">
            <!-- Header Section -->
            <div class="row mb-4 mx-2 mx-md-0">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h2 class="mb-1" id="page-heading">Adoption Center</h2>
                            <p class="text-muted mb-0">View and manage pet adoption listings. Check Adoption History to see all adopted pets.</p>
                        </div>
                        <div class="d-flex flex-wrap gap-2 adoption-buttons">
                            <a href="{{ route('vet.adoptions.index') }}" class="btn btn-outline-primary adoption-history-btn" role="button">
                                <i class="fas fa-history me-2" aria-hidden="true"></i>All Adoption History
                            </a>
                            <a href="{{ route('vet.adoptions.management.create') }}" class="btn btn-primary list-pet-btn" role="button" aria-label="List a Pet for Adoption">
                                <i class="fas fa-plus me-2" aria-hidden="true"></i>Pet for Adoption
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Adoption Feed -->
            <div class="row g-4 mx-2 mx-md-0" id="adoption-feed" role="feed" aria-label="Adoption listings feed">
                @forelse($adoptionPets as $adoption)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 adoption-card" role="article" aria-labelledby="pet-name-{{ $adoption->id }}">
                        <!-- Pet Image -->
                        <div class="position-relative overflow-hidden" style="height: 280px;">
                            @if($adoption->image_path)
                            <img src="{{ asset('storage/' . $adoption->image_path) }}" 
                                 class="w-100 h-100 adoption-image rounded-top" 
                                 alt="Photo of {{ $adoption->pet_name }}" 
                                 style="object-fit: cover;" loading="lazy"
                                 onclick="openImageModal('{{ asset('storage/' . $adoption->image_path) }}')"
                                 role="button" tabindex="0"
                                 onkeydown="if(event.key==='Enter'||event.key===' ') openImageModal('{{ asset('storage/' . $adoption->image_path) }}')">
                            @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light rounded-top" role="img" aria-label="No image available for {{ $adoption->pet_name }}">
                                <i class="fas fa-paw fa-3x text-muted" aria-hidden="true"></i>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Card Body -->
                        <div class="card-body d-flex flex-column">
                            <div class="flex justify-between items-start">
                                <h2 class="text-xl font-bold text-gray-800">{{ $adoption->pet_name }}</h2>
                                @if($adoption->uploader_type === 'vet')
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                        Vet Upload
                                    </span>
                                @endif
                            </div>
                            
                            <div class="mb-2">
                                @if($adoption->breed)
                                <span class="badge bg-secondary me-1">{{ $adoption->breed }}</span>
                                @endif
                                @if($adoption->age)
                                <span class="badge bg-info">{{ $adoption->age }} yrs</span>
                                @endif
                                @if($adoption->gender)
                                <span class="badge bg-primary">{{ ucfirst($adoption->gender) }}</span>
                                @endif
                            </div>
                            
                            @if($adoption->description)
                            <div class="mb-3">
                                <h6 class="text-muted description-label mb-1">Description</h6>
                                <p class="card-text">{{ Str::limit($adoption->description, 100) }}</p>
                            </div>
                            @endif
                            
                            <!-- Display adopter information if user is the owner and there's a pending request -->
                            @if($adoption->user_id == auth()->id() && $adoption->hasPendingRequest())
                                @php
                                    $pendingRequest = $adoption->pendingRequest();
                                @endphp
                                @if($pendingRequest && $pendingRequest->adopter)
                                <div class="alert alert-info small p-2 mb-3" role="alert">
                                    <i class="fas fa-user me-1"></i>
                                    <strong>{{ $pendingRequest->adopter->name }}</strong> requested to adopt this pet
                                </div>
                                @endif
                            @endif
                            
                            <!-- Display approval status for owner -->
                            @if($adoption->user_id == auth()->id() && !$adoption->hasPendingRequest() && $adoption->hasApprovedRequest())
                                <div class="alert alert-success small p-2 mb-3" role="alert">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Adoption approved - check your history
                                </div>
                            @endif
                            
                            <!-- Display approval status for adopter -->
                            @if($adoption->user_id != auth()->id() && $adoption->hasApprovedRequest() && $adoption->adoptionRequests()->where('status', 'approved')->where('adopter_id', auth()->id())->exists())
                                <div class="alert alert-success small p-2 mb-3" role="alert">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Your adoption request was approved!
                                </div>
                            @endif
                            
                            <!-- Action Buttons -->
                            <div class="d-flex flex-column flex-md-row gap-2 mt-auto">
                                <a href="{{ route('vet.adoptions.management.show', $adoption) }}" class="btn btn-success flex-fill" role="button" aria-label="View details for {{ $adoption->pet_name }}">
                                    <i class="fas fa-info-circle me-1" aria-hidden="true"></i>View Details
                                </a>
                                @if($adoption->user_id == auth()->id())
                                    <!-- Owner-specific buttons -->
                                    @if($adoption->hasPendingRequest())
                                        <a href="{{ route('vet.adoptions.management.show', $adoption) }}" class="btn btn-info flex-fill" role="button">
                                            <i class="fas fa-user-clock me-1" aria-hidden="true"></i>Review Request
                                        </a>
                                    @elseif($adoption->hasApprovedRequest())
                                        <button class="btn btn-success flex-fill" disabled>
                                            <i class="fas fa-check me-1" aria-hidden="true"></i>Adoption Approved
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                        
                        <!-- Card Footer -->
                        <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-3">
                            <small class="text-muted">
                                <i class="fas fa-user me-1" aria-hidden="true"></i>
                                <span>Listed by {{ $adoption->user->name }} • 
                                <time datetime="{{ $adoption->created_at->toIso8601String() }}">{{ $adoption->created_at->format('M d, Y') }}</time></span>
                            </small>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-paw fa-3x text-muted mb-3" aria-hidden="true"></i>
                        <h5 id="no-records-heading">No pets available for adoption</h5>
                        <p class="text-muted">Be the first to list a pet for adoption!</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Pet Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <img id="modalImage" src="" class="w-100" alt="Full size pet image" style="max-height: 80vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

<script>
function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
    myModal.show();
}

// Add keyboard support for image modal
document.addEventListener('DOMContentLoaded', function() {
    const clickableImages = document.querySelectorAll('img[onclick]');
    clickableImages.forEach(img => {
        img.setAttribute('tabindex', '0');
        img.setAttribute('role', 'button');
        img.setAttribute('aria-label', 'View larger image');
        
        img.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                const onclickAttr = this.getAttribute('onclick');
                if (onclickAttr) {
                    // Extract the function call and execute it
                    const funcMatch = onclickAttr.match(/openImageModal\('([^']+)'\)/);
                    if (funcMatch && funcMatch[1]) {
                        openImageModal(funcMatch[1]);
                    }
                }
            }
        });
    });
});
</script>

<style>

@media (min-width: 768px) {
    /* Desktop button size reduction */
    .adoption-history-btn,
    .list-pet-btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        min-height: 36px;
    }
    
    /* View Details button size reduction */
    .btn-success.flex-fill {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        min-height: 36px;
    }
    
    /* Review Request button size reduction */
    .btn-info.flex-fill {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        min-height: 36px;
    }
}

@media (max-width: 767.98px) {
    /* Mobile button size reduction */
    .adoption-history-btn,
    .list-pet-btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8125rem;
        min-height: 36px;
    }
    
    /* View Details button size reduction */
    .btn-success.flex-fill {
        padding: 0.25rem 0.5rem;
        font-size: 0.8125rem;
        min-height: 36px;
    }
    
    /* Review Request button size reduction */
    .btn-info.flex-fill {
        padding: 0.25rem 0.5rem;
        font-size: 0.8125rem;
        min-height: 36px;
    }
    
    .gap-2 {
        gap: 0.5rem !important;
    }
}

@media (max-width: 768px) {
    .row.g-4 {
        gap: 1rem;
    }
    
    .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .card {
        margin-bottom: 1rem;
    }
    
    h2 {
        font-size: 1.5rem;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
        margin-bottom: 0.5rem;
    }
    
    .d-flex.flex-column.flex-md-row {
        flex-direction: column !important;
    }
    
    .adoption-buttons {
        width: 100%;
        justify-content: flex-end !important;
    }
    
    .adoption-history-btn,
    .list-pet-btn {
        width: auto;
        min-width: 44px;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
    
    .card-title {
        font-size: 1.25rem;
    }
    
    .position-relative {
        height: 220px !important;
    }
}

@media (max-width: 576px) {
    .card-body {
        padding: 1rem;
    }
    
    h2 {
        font-size: 1.25rem;
    }
    
    .card-title {
        font-size: 1.1rem;
    }
    
    .badge {
        font-size: 0.75rem;
    }
    
    .position-relative {
        height: 200px !important;
    }
    
    .adoption-buttons {
        flex-direction: column !important;
        align-items: flex-end !important;
    }
    
    .adoption-history-btn,
    .list-pet-btn {
        width: 100%;
    }
    
    .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
}

@media (max-width: 400px) {
    .card {
        margin: 0 0.25rem 1rem 0.25rem;
    }
    
    .card-body {
        padding: 0.75rem;
    }
    
    .card-title {
        font-size: 1rem;
    }
    
    .position-relative {
        height: 180px !important;
    }
    
    .btn {
        padding: 0.2rem 0.4rem;
        font-size: 0.75rem;
    }
    
    .badge {
        padding: 0.25em 0.5em;
        font-size: 0.7rem;
    }
}

/* Focus indicators for keyboard navigation */
.btn:focus, 
a:focus, 
img[role="button"]:focus {
    outline: 2px solid #27ae60;
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

/* Card hover effect for better interaction */
.adoption-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.adoption-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

/* Image modal improvements */
.modal-content {
    border: none;
    border-radius: 0.5rem;
}

.modal-header {
    border-bottom: 1px solid #e9ecef;
}

.modal-title {
    font-weight: 600;
}
</style>

@endsection