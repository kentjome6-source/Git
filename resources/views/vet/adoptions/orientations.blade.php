@extends('layouts.vet')

@section('title', 'Adoption Orientations')

@section('content')
<div class="vet-orientations-page">
    <div class="container-fluid px-4 py-4">
        <!-- Header -->
        <div class="page-header mb-4">
            <h1 class="page-title">Adoption Orientations</h1>
            <p class="page-subtitle text-muted">Conduct orientations for potential adopters</p>
        </div>

        @if($requests->count() > 0)
        <!-- Requests Grid -->
        <div class="row g-4">
            @foreach($requests as $request)
            <div class="col-lg-6">
                <div class="orientation-card">
                    <div class="orientation-header">
                        <div class="d-flex align-items-center gap-3">
                            <div class="pet-thumb">
                                @if($request->adoption->image_path)
                                    <img src="{{ asset('storage/' . $request->adoption->image_path) }}" alt="{{ $request->adoption->pet_name }}">
                                @else
                                    <div class="pet-thumb-placeholder">
                                        <i class="fas fa-paw"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="orientation-title mb-1">{{ $request->adoption->pet_name }}</h5>
                                <p class="text-muted small mb-0">{{ ucfirst($request->adoption->species) }} • {{ $request->adoption->age }} years</p>
                            </div>
                            <span class="badge bg-info">Orientation</span>
                        </div>
                    </div>

                    <div class="orientation-body">
                        <div class="adopter-info mb-3">
                            <h6 class="fw-bold mb-2">Adopter Information</h6>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-icon"><i class="fas fa-user"></i></span>
                                    <div>
                                        <div class="fw-semibold">{{ $request->full_name }}</div>
                                        <div class="text-muted small">{{ $request->email }}</div>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <span class="info-icon"><i class="fas fa-phone"></i></span>
                                    <div>
                                        <div class="text-muted small">Phone</div>
                                        <div class="fw-semibold">{{ $request->phone }}</div>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <span class="info-icon"><i class="fas fa-home"></i></span>
                                    <div>
                                        <div class="text-muted small">Housing</div>
                                        <div class="fw-semibold">{{ ucfirst($request->housing_type) }}</div>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <span class="info-icon"><i class="fas fa-map-marker-alt"></i></span>
                                    <div>
                                        <div class="text-muted small">Location</div>
                                        <div class="fw-semibold">{{ Str::limit($request->address, 30) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($request->current_pets || $request->experience_with_pets)
                        <div class="pet-experience mb-3">
                            <h6 class="fw-bold mb-2">Pet Experience</h6>
                            @if($request->current_pets)
                            <div class="mb-2">
                                <span class="text-muted small">Current Pets:</span>
                                <p class="mb-0 small">{{ Str::limit($request->current_pets, 100) }}</p>
                            </div>
                            @endif
                            @if($request->experience_with_pets)
                            <div>
                                <span class="text-muted small">Previous Experience:</span>
                                <p class="mb-0 small">{{ Str::limit($request->experience_with_pets, 100) }}</p>
                            </div>
                            @endif
                        </div>
                        @endif

                        <div class="orientation-actions">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#orientationModal{{ $request->id }}">
                                <i class="fas fa-eye me-2"></i>View Full Details
                            </button>
                            <form action="{{ route('vet.adoptions.complete-orientation', $request) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-check me-2"></i>Complete Orientation
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Orientation Modal -->
                <div class="modal fade" id="orientationModal{{ $request->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Orientation Details - {{ $request->full_name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <h6 class="fw-bold">Pet Details</h6>
                                        <div class="info-list">
                                            <div class="info-item">
                                                <span class="fw-semibold">Name:</span>
                                                <span>{{ $request->adoption->pet_name }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="fw-semibold">Species:</span>
                                                <span>{{ ucfirst($request->adoption->species) }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="fw-semibold">Breed:</span>
                                                <span>{{ $request->adoption->breed ?? 'N/A' }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="fw-semibold">Age:</span>
                                                <span>{{ $request->adoption->age }} years</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="fw-bold">Adopter Contact</h6>
                                        <div class="info-list">
                                            <div class="info-item">
                                                <span class="fw-semibold">Name:</span>
                                                <span>{{ $request->full_name }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="fw-semibold">Email:</span>
                                                <span>{{ $request->email }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="fw-semibold">Phone:</span>
                                                <span>{{ $request->phone }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="fw-semibold">Address:</span>
                                                <span>{{ $request->address }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <h6 class="fw-bold">Adoption Reason</h6>
                                        <p>{{ $request->reason_for_adoption }}</p>
                                    </div>
                                    @if($request->current_pets)
                                    <div class="col-12">
                                        <h6 class="fw-bold">Current Pets</h6>
                                        <p>{{ $request->current_pets }}</p>
                                    </div>
                                    @endif
                                    @if($request->experience_with_pets)
                                    <div class="col-12">
                                        <h6 class="fw-bold">Pet Experience</h6>
                                        <p>{{ $request->experience_with_pets }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <form action="{{ route('vet.adoptions.complete-orientation', $request) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check me-2"></i>Complete Orientation
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($requests->hasPages())
        <div class="mt-4">
            {{ $requests->links() }}
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <h3 class="empty-state-title">No Pending Orientations</h3>
            <p class="empty-state-text">There are no adoption orientations scheduled at this time.</p>
        </div>
        @endif
    </div>
</div>

<style>
:root {
    --primary: #2563eb;
    --success: #10b981;
    --info: #3b82f6;
    --dark: #1e293b;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-600: #475569;
    --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
}

.vet-orientations-page {
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

.orientation-card {
    background: white;
    border-radius: 0.75rem;
    box-shadow: var(--shadow);
    transition: all 0.3s;
    animation: fadeInUp 0.5s ease-out backwards;
}

.orientation-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.col-lg-6:nth-child(1) .orientation-card { animation-delay: 0.05s; }
.col-lg-6:nth-child(2) .orientation-card { animation-delay: 0.1s; }
.col-lg-6:nth-child(3) .orientation-card { animation-delay: 0.15s; }
.col-lg-6:nth-child(4) .orientation-card { animation-delay: 0.2s; }

.orientation-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--gray-200);
}

.pet-thumb {
    width: 60px;
    height: 60px;
    border-radius: 0.5rem;
    overflow: hidden;
    flex-shrink: 0;
}

.pet-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.pet-thumb-placeholder {
    width: 100%;
    height: 100%;
    background: var(--gray-100);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-600);
    font-size: 1.5rem;
}

.orientation-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
}

.orientation-body {
    padding: 1.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.info-item {
    display: flex;
    gap: 0.75rem;
}

.info-icon {
    width: 36px;
    height: 36px;
    border-radius: 0.5rem;
    background: var(--gray-100);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    flex-shrink: 0;
}

.orientation-actions {
    display: flex;
    gap: 0.75rem;
    padding-top: 1rem;
    border-top: 1px solid var(--gray-200);
}

.info-list {
    display: flex;
    flex-direction: column;
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
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .orientation-actions {
        flex-direction: column;
    }
}
</style>
@endsection
