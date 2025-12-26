@extends('layouts.admin')

@section('title', 'Adopter Screening')

@section('content')
<div class="admin-screening-page">
    <div class="container-fluid px-4 py-4">
        <!-- Header -->
        <div class="page-header mb-4">
            <h1 class="page-title">Adopter Screening</h1>
            <p class="page-subtitle text-muted">Review adoption applications and screen potential adopters</p>
        </div>

        @if($adoptionRequests->count() > 0)
        <!-- Requests Table -->
        <div class="card table-card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Adopter</th>
                            <th>Pet</th>
                            <th>Contact</th>
                            <th>Housing</th>
                            <th>Status</th>
                            <th>Applied</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($adoptionRequests as $request)
                        <tr class="table-row-animated">
                            <td>
                                <div class="fw-bold">{{ $request->full_name }}</div>
                                <div class="text-muted small">{{ $request->adopter->email }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="pet-thumb-sm">
                                        @if($request->adoption->image_path)
                                            <img src="{{ asset('storage/' . $request->adoption->image_path) }}" alt="{{ $request->adoption->pet_name }}">
                                        @else
                                            <i class="fas fa-paw"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $request->adoption->pet_name }}</div>
                                        <div class="text-muted small">{{ ucfirst($request->adoption->species) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-muted small">
                                    <div><i class="fas fa-phone me-1"></i>{{ $request->phone }}</div>
                                    <div><i class="fas fa-map-marker-alt me-1"></i>{{ Str::limit($request->address, 30) }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="text-muted small">
                                    <div><i class="fas fa-home me-1"></i>{{ ucfirst($request->housing_type) }}</div>
                                    <div><i class="fas fa-tree me-1"></i>Yard: {{ $request->has_yard ? 'Yes' : 'No' }}</div>
                                </div>
                            </td>
                            <td>
                                @if($request->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($request->status === 'admin_screening')
                                    <span class="badge bg-info">Screening</span>
                                @elseif($request->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($request->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted small">{{ $request->created_at->diffForHumans() }}</span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal{{ $request->id }}" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($request->status === 'pending')
                                    <form action="{{ route('admin.adoption-requests.approve', $request) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.adoption-requests.reject', $request) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <a href="{{ route('messages.conversation', $request->adopter) }}" class="btn btn-sm btn-primary" title="Message Adopter">
                                        <i class="fas fa-comment"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <!-- View Modal -->
                        <div class="modal fade" id="viewModal{{ $request->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Application Details - {{ $request->full_name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <h6 class="fw-bold mb-3">Personal Information</h6>
                                                <div class="info-group">
                                                    <div class="info-item">
                                                        <span class="info-label">Name:</span>
                                                        <span>{{ $request->full_name }}</span>
                                                    </div>
                                                    <div class="info-item">
                                                        <span class="info-label">Email:</span>
                                                        <span>{{ $request->email }}</span>
                                                    </div>
                                                    <div class="info-item">
                                                        <span class="info-label">Phone:</span>
                                                        <span>{{ $request->phone }}</span>
                                                    </div>
                                                    <div class="info-item">
                                                        <span class="info-label">Address:</span>
                                                        <span>{{ $request->address }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="fw-bold mb-3">Housing Information</h6>
                                                <div class="info-group">
                                                    <div class="info-item">
                                                        <span class="info-label">Type:</span>
                                                        <span>{{ ucfirst($request->housing_type) }}</span>
                                                    </div>
                                                    <div class="info-item">
                                                        <span class="info-label">Has Yard:</span>
                                                        <span>{{ $request->has_yard ? 'Yes' : 'No' }}</span>
                                                    </div>
                                                    <div class="info-item">
                                                        <span class="info-label">Own/Rent:</span>
                                                        <span>{{ ucfirst($request->own_or_rent) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <h6 class="fw-bold mb-3">Adoption Reason</h6>
                                                <p>{{ $request->reason_for_adoption }}</p>
                                            </div>
                                            @if($request->current_pets)
                                            <div class="col-12">
                                                <h6 class="fw-bold mb-3">Current Pets</h6>
                                                <p>{{ $request->current_pets }}</p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <a href="{{ route('messages.conversation', $request->adopter) }}" class="btn btn-primary">
                                            <i class="fas fa-comment me-2"></i>Message Adopter
                                        </a>
                                        @if($request->status === 'pending')
                                        <form action="{{ route('admin.adoption-requests.approve', $request) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check me-2"></i>Approve
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($adoptionRequests->hasPages())
        <div class="mt-4">
            {{ $adoptionRequests->links() }}
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <h3 class="empty-state-title">No Pending Screenings</h3>
            <p class="empty-state-text">There are no adoption applications pending screening at this time.</p>
        </div>
        @endif
    </div>
</div>

<style>
:root {
    --primary: #2563eb;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --info: #3b82f6;
    --dark: #1e293b;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-600: #475569;
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
}

.admin-screening-page {
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

.table-card {
    border: none;
    box-shadow: var(--shadow-md);
    border-radius: 0.75rem;
    overflow: hidden;
    animation: fadeInUp 0.5s ease-out;
}

.table thead th {
    background: var(--gray-100);
    color: var(--dark);
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 1rem;
    border-bottom: 2px solid var(--gray-200);
}

.table tbody td {
    padding: 1rem;
    vertical-align: middle;
}

.pet-thumb-sm {
    width: 40px;
    height: 40px;
    border-radius: 0.375rem;
    overflow: hidden;
    background: var(--gray-100);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.pet-thumb-sm img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.pet-thumb-sm i {
    color: var(--gray-600);
}

.info-group {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.info-item {
    display: flex;
    gap: 0.5rem;
}

.info-label {
    font-weight: 600;
    color: var(--dark);
    min-width: 100px;
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
</style>
@endsection
