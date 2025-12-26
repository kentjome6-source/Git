@extends('layouts.admin')

@section('title', 'Pending Adoption Approvals')

@section('content')
<div class="admin-adoptions-page">
    <div class="container-fluid px-4 py-4">
        <!-- Header -->
        <div class="page-header mb-4">
            <h1 class="page-title">Listing Approvals</h1>
            <p class="page-subtitle text-muted">Review and approve pet listings for adoption</p>
        </div>

        @if($adoptions->count() > 0)
        <!-- Adoptions Table -->
        <div class="card table-card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Pet</th>
                            <th>Details</th>
                            <th>Owner</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($adoptions as $adoption)
                        <tr class="table-row-animated">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="pet-thumb">
                                        @if($adoption->image_path)
                                            <img src="{{ asset('storage/' . $adoption->image_path) }}" alt="{{ $adoption->pet_name }}">
                                        @else
                                            <div class="pet-thumb-placeholder">
                                                <i class="fas fa-paw"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $adoption->pet_name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-muted small">
                                    <div><i class="fas fa-tag me-1"></i>{{ ucfirst($adoption->species) }}</div>
                                    @if($adoption->breed)
                                    <div><i class="fas fa-paw me-1"></i>{{ $adoption->breed }}</div>
                                    @endif
                                    <div><i class="fas fa-birthday-cake me-1"></i>{{ $adoption->age }} years</div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $adoption->user->name }}</div>
                                <div class="text-muted small">{{ $adoption->user->email }}</div>
                            </td>
                            <td>
                                @if($adoption->listing_status === 'vet_review')
                                    <span class="badge bg-info">Vet Review</span>
                                @elseif($adoption->listing_status === 'admin_review')
                                    <span class="badge bg-warning">Pending Approval</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($adoption->listing_status) }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted small">{{ $adoption->created_at->diffForHumans() }}</span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    @if($adoption->listing_status === 'admin_review')
                                    <form action="{{ route('admin.adoptions.approve', $adoption) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.adoptions.reject', $adoption) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @else
                                    <button class="btn btn-sm btn-secondary" disabled>
                                        <i class="fas fa-clock"></i>
                                    </button>
                                    @endif
                                    <a href="{{ route('messages.conversation', $adoption->user) }}" class="btn btn-sm btn-primary" title="Message Owner">
                                        <i class="fas fa-comment"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($adoptions->hasPages())
        <div class="mt-4">
            {{ $adoptions->links() }}
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <h3 class="empty-state-title">No Pending Approvals</h3>
            <p class="empty-state-text">There are no adoption listings pending your approval at this time.</p>
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
    --gray-600: #475569;
    --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
}

.admin-adoptions-page {
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
    font-size: 1rem;
    color: var(--gray-600);
}

.table-card {
    border: none;
    box-shadow: var(--shadow-md);
    border-radius: 0.75rem;
    overflow: hidden;
    animation: fadeInUp 0.5s ease-out;
}

.table {
    margin-bottom: 0;
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

.table-row-animated {
    animation: fadeIn 0.3s ease-out;
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
    .page-title {
        font-size: 1.5rem;
    }
}
</style>
@endsection
