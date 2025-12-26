@extends('layouts.vet')

@section('title', 'Appointment Records')

@php
use Illuminate\Support\Str;
@endphp

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="records-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="records-title">Appointment Records</h1>
                <p class="records-subtitle text-muted">View and manage completed appointments</p>
            </div>
            <div class="records-count">
                <span class="count-number">{{ $appointments->total() }}</span>
                <span class="count-label">Total Records</span>
            </div>
        </div>
    </div>

    <!-- Search Form -->
    <div class="search-card mb-4">
        <form method="GET" action="{{ route('vet.appointment.records') }}">
            <div class="row g-3">
                <div class="col-lg-9">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="form-control search-input" name="search" 
                               placeholder="Search by pet name, owner, type, or services..." 
                               value="{{ $search ?? '' }}">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-search flex-fill">
                            <i class="fas fa-search me-2"></i>Search
                        </button>
                        @if($search)
                            <a href="{{ route('vet.appointment.records') }}" class="btn btn-clear">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    @if($appointments->count() > 0)
        <!-- Desktop View -->
        <div class="records-grid desktop-view">
            @foreach($appointments as $appointment)
                @php
                    $statusDisplay = match($appointment->status) {
                        'pending' => 'Pending Review',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'cancelled' => 'Cancelled',
                        default => ucfirst($appointment->status)
                    };
                    
                    $statusClass = match($appointment->status) {
                        'pending' => 'status-pending',
                        'accepted' => 'status-success',
                        'rejected' => 'status-danger',
                        'cancelled' => 'status-secondary',
                        default => 'status-secondary'
                    };
                @endphp
                
                <div class="record-card">
                    <div class="record-header">
                        <div class="record-id">ID: #{{ $appointment->id }}</div>
                        <span class="status-badge {{ $statusClass }}">{{ $statusDisplay }}</span>
                    </div>
                    
                    <div class="record-body">
                        <div class="record-info-group">
                            <div class="info-label">Pet Owner</div>
                            <div class="info-value">{{ $appointment->owner_name }}</div>
                            <div class="info-subtext">{{ $appointment->owner_email }}</div>
                        </div>
                        
                        <div class="record-info-group">
                            <div class="info-label">Pet Details</div>
                            <div class="info-value">{{ $appointment->pet_name }}</div>
                            <div class="info-subtext">{{ $appointment->pet_type }}</div>
                        </div>
                        
                        <div class="record-info-group">
                            <div class="info-label">Services</div>
                            <div class="info-value">{{ Str::limit($appointment->pet_services_received, 40) }}</div>
                        </div>
                        
                        <div class="record-info-group">
                            <div class="info-label">Date</div>
                            <div class="info-value">{{ $appointment->created_at->format('M d, Y') }}</div>
                            <div class="info-subtext">{{ $appointment->created_at->format('g:i A') }}</div>
                        </div>
                        
                        @if($appointment->status === 'rejected' && $appointment->rejection_reason)
                            <div class="rejection-reason">
                                <i class="fas fa-info-circle me-1"></i>
                                {{ Str::limit($appointment->rejection_reason, 60) }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="record-footer">
                        <a href="{{ route('vet.appointment.records.show', $appointment) }}" class="btn btn-view">
                            <i class="fas fa-eye me-2"></i>View Details
                        </a>
                        <a href="{{ route('messages.index', ['user' => $appointment->user_id]) }}" class="btn btn-message">
                            <i class="fas fa-comment me-2"></i>Message
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Mobile View -->
        <div class="mobile-view">
            @foreach($appointments as $appointment)
                @php
                    $statusDisplay = match($appointment->status) {
                        'pending' => 'Pending Review',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'cancelled' => 'Cancelled',
                        default => ucfirst($appointment->status)
                    };
                    
                    $statusClass = match($appointment->status) {
                        'pending' => 'status-pending',
                        'accepted' => 'status-success',
                        'rejected' => 'status-danger',
                        'cancelled' => 'status-secondary',
                        default => 'status-secondary'
                    };
                @endphp
                
                <div class="record-card-mobile">
                    <div class="mobile-header">
                        <span class="mobile-id">ID: #{{ $appointment->id }}</span>
                        <span class="status-badge {{ $statusClass }}">{{ $statusDisplay }}</span>
                    </div>
                    
                    <div class="mobile-content">
                        <div class="mobile-row">
                            <span class="mobile-label">Owner</span>
                            <span class="mobile-value">{{ $appointment->owner_name }}</span>
                        </div>
                        <div class="mobile-row">
                            <span class="mobile-label">Pet</span>
                            <span class="mobile-value">{{ $appointment->pet_name }} ({{ $appointment->pet_type }})</span>
                        </div>
                        <div class="mobile-row">
                            <span class="mobile-label">Services</span>
                            <span class="mobile-value">{{ Str::limit($appointment->pet_services_received, 30) }}</span>
                        </div>
                        <div class="mobile-row">
                            <span class="mobile-label">Date</span>
                            <span class="mobile-value">{{ $appointment->created_at->format('M d, Y g:i A') }}</span>
                        </div>
                        @if($appointment->status === 'rejected' && $appointment->rejection_reason)
                            <div class="rejection-reason-mobile">
                                {{ Str::limit($appointment->rejection_reason, 50) }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="mobile-actions">
                        <a href="{{ route('vet.appointment.records.show', $appointment) }}" class="btn btn-view-mobile">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('messages.index', ['user' => $appointment->user_id]) }}" class="btn btn-message-mobile">
                            <i class="fas fa-comment"></i> Message
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper mt-4">
            {{ $appointments->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-file-medical empty-icon"></i>
            <h3 class="empty-title">No Appointment Records Found</h3>
            @if($search)
                <p class="empty-text">No records matched your search criteria. <a href="{{ route('vet.appointment.records') }}" class="empty-link">Clear search</a> to see all records.</p>
            @else
                <p class="empty-text">You don't have any appointment records yet.</p>
            @endif
        </div>
    @endif
</div>

<style>
.records-header {
    animation: fadeInDown 0.5s ease;
}

.records-title {
    font-size: 1.75rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
}

.records-subtitle {
    font-size: 0.95rem;
    margin: 0;
}

.records-count {
    text-align: center;
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #27ae60;
}

.count-number {
    display: block;
    font-size: 1.75rem;
    font-weight: 700;
    color: #27ae60;
    line-height: 1;
}

.count-label {
    display: block;
    font-size: 0.85rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

.search-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    animation: fadeIn 0.5s ease;
}

.search-input-wrapper {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

.search-input {
    padding-left: 2.75rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.search-input:focus {
    border-color: #27ae60;
    box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.1);
}

.btn-search {
    background: #27ae60;
    color: white;
    border: none;
    padding: 0.625rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-search:hover {
    background: #219653;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(39, 174, 96, 0.2);
}

.btn-clear {
    background: #6c757d;
    color: white;
    border: none;
    padding: 0.625rem 1rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-clear:hover {
    background: #5a6268;
}

.records-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
    animation: fadeIn 0.5s ease;
}

.record-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    overflow: hidden;
}

.record-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.12);
}

.record-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem;
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.record-id {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
}

.status-badge {
    padding: 0.35rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-success {
    background: #d4edda;
    color: #155724;
}

.status-danger {
    background: #f8d7da;
    color: #721c24;
}

.status-secondary {
    background: #e9ecef;
    color: #495057;
}

.record-body {
    padding: 1.25rem;
}

.record-info-group {
    margin-bottom: 1rem;
}

.record-info-group:last-child {
    margin-bottom: 0;
}

.info-label {
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.info-value {
    font-size: 0.95rem;
    color: #2c3e50;
    font-weight: 500;
}

.info-subtext {
    font-size: 0.85rem;
    color: #6c757d;
    margin-top: 0.15rem;
}

.rejection-reason {
    margin-top: 1rem;
    padding: 0.75rem;
    background: #fff3cd;
    border-left: 3px solid #ffc107;
    border-radius: 4px;
    font-size: 0.85rem;
    color: #856404;
}

.record-footer {
    padding: 1rem 1.25rem;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    display: flex;
    gap: 0.5rem;
}

.btn-view, .btn-message {
    flex: 1;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 500;
    text-align: center;
    transition: all 0.3s ease;
    border: none;
}

.btn-view {
    background: #27ae60;
    color: white;
}

.btn-view:hover {
    background: #219653;
    color: white;
}

.btn-message {
    background: white;
    color: #27ae60;
    border: 2px solid #27ae60;
}

.btn-message:hover {
    background: #27ae60;
    color: white;
}

.mobile-view {
    display: none;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.empty-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1.5rem;
}

.empty-title {
    font-size: 1.5rem;
    color: #495057;
    margin-bottom: 0.75rem;
}

.empty-text {
    color: #6c757d;
    margin: 0;
}

.empty-link {
    color: #27ae60;
    text-decoration: none;
    font-weight: 500;
}

.empty-link:hover {
    text-decoration: underline;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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

@media (max-width: 768px) {
    .desktop-view {
        display: none;
    }
    
    .mobile-view {
        display: block;
    }
    
    .records-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .records-count {
        width: 100%;
    }
    
    .records-title {
        font-size: 1.5rem;
    }
    
    .search-card {
        padding: 1rem;
    }
    
    .record-card-mobile {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 1rem;
        overflow: hidden;
    }
    
    .mobile-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.875rem 1rem;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }
    
    .mobile-id {
        font-weight: 600;
        color: #495057;
        font-size: 0.875rem;
    }
    
    .mobile-content {
        padding: 1rem;
    }
    
    .mobile-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .mobile-row:last-child {
        border-bottom: none;
    }
    
    .mobile-label {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 500;
    }
    
    .mobile-value {
        font-size: 0.85rem;
        color: #2c3e50;
        font-weight: 500;
        text-align: right;
        flex: 1;
        margin-left: 1rem;
    }
    
    .rejection-reason-mobile {
        margin-top: 0.75rem;
        padding: 0.625rem;
        background: #fff3cd;
        border-left: 3px solid #ffc107;
        border-radius: 4px;
        font-size: 0.8rem;
        color: #856404;
    }
    
    .mobile-actions {
        padding: 1rem;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-view-mobile, .btn-message-mobile {
        flex: 1;
        padding: 0.5rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        text-align: center;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-view-mobile {
        background: #27ae60;
        color: white;
    }
    
    .btn-message-mobile {
        background: white;
        color: #27ae60;
        border: 2px solid #27ae60;
    }
}

@media (max-width: 576px) {
    .records-title {
        font-size: 1.25rem;
    }
    
    .records-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection