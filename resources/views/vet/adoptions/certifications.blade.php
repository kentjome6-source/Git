@extends('layouts.vet')

@section('title', 'Pet Certifications')

@section('content')
<div class="vet-certifications-page">
    <div class="container-fluid px-4 py-4">
        <!-- Header -->
        <div class="page-header mb-4">
            <h1 class="page-title">Pet Certifications</h1>
            <p class="page-subtitle text-muted">Review and certify pets for adoption</p>
        </div>

        @if($adoptions->count() > 0)
        <!-- Adoptions Grid -->
        <div class="row g-4">
            @foreach($adoptions as $adoption)
            <div class="col-lg-6">
                <div class="cert-card">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <div class="cert-image">
                                @if($adoption->image_path)
                                    <img src="{{ asset('storage/' . $adoption->image_path) }}" alt="{{ $adoption->pet_name }}">
                                @else
                                    <div class="cert-image-placeholder">
                                        <i class="fas fa-paw"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="cert-content">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="cert-pet-name mb-0">{{ $adoption->pet_name }}</h5>
                                    <span class="badge bg-warning">Pending</span>
                                </div>
                                
                                <div class="cert-meta mb-3">
                                    <span class="meta-item">
                                        <i class="fas fa-paw"></i>
                                        {{ ucfirst($adoption->species) }}
                                    </span>
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
                                </div>

                                @if($adoption->health_status)
                                <div class="mb-3">
                                    <h6 class="fw-bold small mb-1">Health Status</h6>
                                    <p class="text-muted small mb-0">{{ Str::limit($adoption->health_status, 100) }}</p>
                                </div>
                                @endif

                                @if($adoption->vaccination_records)
                                <div class="mb-3">
                                    <h6 class="fw-bold small mb-1">Vaccination Records</h6>
                                    <p class="text-muted small mb-0">{{ Str::limit($adoption->vaccination_records, 100) }}</p>
                                </div>
                                @endif

                                <div class="cert-actions mt-3">
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#certifyModal{{ $adoption->id }}">
                                        <i class="fas fa-certificate me-2"></i>Certify
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $adoption->id }}">
                                        <i class="fas fa-eye me-2"></i>Details
                                    </button>
                                    <a href="{{ route('messages.conversation', $adoption->user) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-comment me-2"></i>Message
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certify Modal -->
                <div class="modal fade" id="certifyModal{{ $adoption->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Certify {{ $adoption->pet_name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('vet.adoptions.certify', $adoption) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="vet_health_notes{{ $adoption->id }}" class="form-label fw-semibold">Health Notes <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="vet_health_notes{{ $adoption->id }}" name="vet_health_notes" rows="4" required placeholder="Enter your health assessment and notes..."></textarea>
                                        <small class="text-muted">Provide detailed health assessment and any recommendations</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="is_fit_for_adoption{{ $adoption->id }}" class="form-label fw-semibold">Adoption Fitness <span class="text-danger">*</span></label>
                                        <select class="form-select" id="is_fit_for_adoption{{ $adoption->id }}" name="is_fit_for_adoption" required>
                                            <option value="">Select status...</option>
                                            <option value="1">Fit for Adoption</option>
                                            <option value="0">Not Fit for Adoption</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-certificate me-2"></i>Submit Certification
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Details Modal -->
                <div class="modal fade" id="detailsModal{{ $adoption->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ $adoption->pet_name }} - Full Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <h6 class="fw-bold">Basic Information</h6>
                                        <div class="info-list">
                                            <div class="info-item">
                                                <span class="fw-semibold">Species:</span>
                                                <span>{{ ucfirst($adoption->species) }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="fw-semibold">Breed:</span>
                                                <span>{{ $adoption->breed ?? 'N/A' }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="fw-semibold">Age:</span>
                                                <span>{{ $adoption->age }} years</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="fw-semibold">Gender:</span>
                                                <span>{{ ucfirst($adoption->gender) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="fw-bold">Owner Information</h6>
                                        <div class="info-list">
                                            <div class="info-item">
                                                <span class="fw-semibold">Name:</span>
                                                <span>{{ $adoption->user->name }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="fw-semibold">Email:</span>
                                                <span>{{ $adoption->user->email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <h6 class="fw-bold">Health Status</h6>
                                        <p>{{ $adoption->health_status ?? 'No health status provided' }}</p>
                                    </div>
                                    <div class="col-12">
                                        <h6 class="fw-bold">Vaccination Records</h6>
                                        <p>{{ $adoption->vaccination_records ?? 'No vaccination records provided' }}</p>
                                    </div>
                                    @if($adoption->description)
                                    <div class="col-12">
                                        <h6 class="fw-bold">Description</h6>
                                        <p>{{ $adoption->description }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <a href="{{ route('messages.conversation', $adoption->user) }}" class="btn btn-primary">
                                    <i class="fas fa-comment me-2"></i>Message Owner
                                </a>
                                <button type="button" class="btn btn-success" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#certifyModal{{ $adoption->id }}">
                                    <i class="fas fa-certificate me-2"></i>Certify Pet
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($adoptions->hasPages())
        <div class="mt-4">
            {{ $adoptions->links() }}
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-certificate"></i>
            </div>
            <h3 class="empty-state-title">No Pets Pending Certification</h3>
            <p class="empty-state-text">There are no pets awaiting veterinary certification at this time.</p>
        </div>
        @endif
    </div>
</div>

<style>
:root {
    --primary: #2563eb;
    --success: #10b981;
    --warning: #f59e0b;
    --dark: #1e293b;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-600: #475569;
    --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
}

.vet-certifications-page {
    background: var(--gray-50);
    min-height: 100vh;
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

.cert-card {
    background: white;
    border-radius: 0.75rem;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: all 0.3s;
    animation: fadeInUp 0.5s ease-out backwards;
    height: 100%;
}

.cert-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.col-lg-6:nth-child(1) .cert-card { animation-delay: 0.05s; }
.col-lg-6:nth-child(2) .cert-card { animation-delay: 0.1s; }
.col-lg-6:nth-child(3) .cert-card { animation-delay: 0.15s; }
.col-lg-6:nth-child(4) .cert-card { animation-delay: 0.2s; }

.cert-image {
    height: 100%;
    min-height: 250px;
    background: var(--gray-100);
}

.cert-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cert-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-600);
    font-size: 3rem;
}

.cert-content {
    padding: 1.5rem;
}

.cert-pet-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
}

.cert-meta {
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

.info-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item {
    display: flex;
    gap: 0.5rem;
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
    .cert-image {
        min-height: 200px;
    }
    
    .cert-content {
        padding: 1rem;
    }
    
    .cert-actions {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .cert-actions .btn {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .page-title {
        font-size: 1.5rem;
    }
    
    .cert-image {
        min-height: 150px;
    }
    
    .cert-pet-name {
        font-size: 1.1rem;
    }
    
    .cert-meta {
        flex-direction: column;
        gap: 0.25rem;
    }
}
</style>
@endsection
