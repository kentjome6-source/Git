@extends('layouts.vet')

@section('title', 'Appointment Records')

@php
use Illuminate\Support\Str;
@endphp

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-file-medical me-2"></i>Appointment Records</h2>
                <div class="d-flex gap-2">
                    <span class="badge badge-info">{{ $appointments->total() }} Records (Accepted/Rejected)</span>
                </div>
            </div>

            <!-- Search Form -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('vet.appointment.records') }}">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" name="search" placeholder="Search by Pet Name, Owner Name, Pet Type, or Services..." value="{{ $search ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-grid d-md-block">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i> Search</button>
                                    @if($search)
                                        <a href="{{ route('vet.appointment.records') }}" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Clear</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if($appointments->count() > 0)
                <div class="card shadow">
                    <div class="card-body p-0">
                        <!-- Desktop Table View -->
                        <div class="table-responsive desktop-table">
                            <table class="table table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Pet Owner</th>
                                        <th>Pet Name</th>
                                        <th>Pet Type</th>
                                        <th>Services Received</th>
                                        <th>Status</th>
                                        <th>Requested</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $appointment)
                                        <tr>
                                            <td>{{ $appointment->id }}</td>
                                            <td>
                                                <div>
                                                    <strong>{{ $appointment->owner_name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $appointment->owner_email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $appointment->pet_name }}</strong>
                                                </div>
                                            </td>
                                            <td>{{ $appointment->pet_type }}</td>
                                            <td>{{ $appointment->pet_services_received }}</td>
                                            <td>
                                                @php
                                                    // Map statuses for veterinarian view to match pet parents view
                                                    $statusDisplay = match($appointment->status) {
                                                        'pending' => 'Pending Review',
                                                        'accepted' => 'Accepted',
                                                        'rejected' => 'Rejected',
                                                        'cancelled' => 'Cancelled',
                                                        default => ucfirst($appointment->status)
                                                    };
                                                    
                                                    // Map background classes for veterinarian view to match pet parents view
                                                    $statusClass = match($appointment->status) {
                                                        'pending' => 'warning',
                                                        'accepted' => 'success',
                                                        'rejected' => 'danger',
                                                        'cancelled' => 'secondary',
                                                        default => 'secondary'
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }}">
                                                    {{ $statusDisplay }}
                                                </span>
                                                @if($appointment->status === 'rejected' && $appointment->rejection_reason)
                                                    <div class="mt-1">
                                                        <small class="text-muted">Reason: {{ Str::limit($appointment->rejection_reason, 50) }}</small>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $appointment->created_at->format('M d, Y') }}</small>
                                                <br>
                                                <small class="text-muted">{{ $appointment->created_at->format('g:i A') }}</small>
                                            </td>
                                           <td>
                                                <div class="btn-group-vertical btn-group-sm" role="group">
                                                    <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-info btn-sm me-1" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Mobile Swipeable View -->
                        <div class="mobile-swipeable-view">
                            @foreach($appointments as $appointment)
                                <div class="swipeable-appointment-card">
                                    <div class="swipeable-item">
                                        <span class="label">ID:</span>
                                        <span class="value">{{ $appointment->id }}</span>
                                    </div>
                                    
                                    <div class="swipeable-item">
                                        <span class="label">Pet Owner:</span>
                                        <span class="value">
                                            <strong>{{ $appointment->owner_name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $appointment->owner_email }}</small>
                                        </span>
                                    </div>
                                    
                                    <div class="swipeable-item">
                                        <span class="label">Pet Name:</span>
                                        <span class="value">
                                            <strong>{{ $appointment->pet_name }}</strong>
                                        </span>
                                    </div>
                                    
                                    <div class="swipeable-item">
                                        <span class="label">Pet Type:</span>
                                        <span class="value">
                                            {{ $appointment->pet_type }}
                                        </span>
                                    </div>
                                    
                                    <div class="swipeable-item">
                                        <span class="label">Services Received:</span>
                                        <span class="value">
                                            {{ $appointment->pet_services_received }}
                                        </span>
                                    </div>
                                    
                                    <div class="swipeable-item">
                                        <span class="label">Status:</span>
                                        <span class="value">
                                            @php
                                                // Map statuses for veterinarian view to match pet parents view
                                                $statusDisplay = match($appointment->status) {
                                                    'pending' => 'Pending Review',
                                                    'accepted' => 'Accepted',
                                                    'rejected' => 'Rejected',
                                                    'cancelled' => 'Cancelled',
                                                    default => ucfirst($appointment->status)
                                                };
                                                
                                                // Map background classes for veterinarian view to match pet parents view
                                                $statusClass = match($appointment->status) {
                                                    'pending' => 'warning',
                                                    'accepted' => 'success',
                                                    'rejected' => 'danger',
                                                    'cancelled' => 'secondary',
                                                    default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}">
                                                {{ $statusDisplay }}
                                            </span>
                                            @if($appointment->status === 'rejected' && $appointment->rejection_reason)
                                                <div class="mt-1">
                                                    <small class="text-muted">Reason: {{ Str::limit($appointment->rejection_reason, 50) }}</small>
                                                </div>
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <div class="swipeable-item">
                                        <span class="label">Requested:</span>
                                        <span class="value">
                                            {{ $appointment->created_at->format('M d, Y g:i A') }}
                                        </span>
                                    </div>
                                    
                                    <div class="swipeable-actions">
                                        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-info btn-sm" title="View Details">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $appointments->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-medical fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No Appointment Records Found</h4>
                    @if($search)
                        <p class="text-muted">No records matched your search criteria. <a href="{{ route('vet.appointment.records') }}">Clear search</a> to see all records.</p>
                    @else
                        <p class="text-muted">You don't have any accepted or rejected appointment records yet.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.badge-success { background-color: #28a745; }
.badge-warning { background-color: #ffc107; color: #212529; }
.badge-danger { background-color: #dc3545; }
.badge-dark { background-color: #343a40; }
.badge-info { background-color: #17a2b8; }
.badge-secondary { background-color: #6c757d; }

.table-responsive {
    max-height: 70vh;
}

.btn-group-vertical .btn {
    margin-bottom: 2px;
}

/* Mobile swipeable view styles */
.mobile-swipeable-view {
    display: none;
    padding: 15px;
}

.swipeable-appointment-card {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 15px;
    background: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.swipeable-item {
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
}

.swipeable-item:last-child {
    border-bottom: none;
}

.swipeable-item .label {
    font-weight: 600;
    color: #495057;
    min-width: 100px;
}

.swipeable-item .value {
    text-align: right;
    flex: 1;
}

.swipeable-actions {
    padding: 15px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.swipeable-actions .btn {
    flex: 1;
    min-width: 100px;
}

/* Mobile responsiveness improvements */
@media (max-width: 768px) {
    .table-responsive {
        display: none;
    }
    
    .mobile-swipeable-view {
        display: block;
    }
    
    .table {
        min-width: 800px; /* Ensure table has minimum width for scrolling */
        font-size: 0.9rem;
    }
    
    .table th,
    .table td {
        padding: 8px 6px;
        white-space: nowrap;
    }
    
    .btn-group-vertical .btn {
        padding: 6px 10px;
        font-size: 0.85rem;
        margin-bottom: 1px;
    }
    
    h2 {
        font-size: 1.5rem;
    }
    
    .swipeable-item {
        padding: 10px 12px;
    }
    
    .swipeable-item .label {
        min-width: 90px;
        font-size: 0.9rem;
    }
    
    .swipeable-actions {
        padding: 12px;
    }
    
    .swipeable-actions .btn {
        padding: 6px 10px;
        font-size: 0.85rem;
        min-width: 100px;
    }
}

@media (max-width: 576px) {
    .table {
        min-width: 700px;
        font-size: 0.85rem;
    }
    
    .table th,
    .table td {
        padding: 6px 4px;
        font-size: 0.8rem;
    }
    
    .btn-group-vertical .btn {
        padding: 5px 8px;
        font-size: 0.8rem;
    }
    
    .text-truncate {
        max-width: 150px;
    }
    
    .swipeable-item {
        padding: 8px 10px;
    }
    
    .swipeable-item .label {
        font-size: 0.85rem;
        min-width: 80px;
    }
    
    .swipeable-actions {
        padding: 10px;
        gap: 5px;
    }
    
    .swipeable-actions .btn {
        padding: 5px 8px;
        font-size: 0.8rem;
        min-width: 80px;
    }
}

@media (max-width: 400px) {
    .table {
        min-width: 600px;
        font-size: 0.8rem;
    }
    
    .table th,
    .table td {
        padding: 5px 3px;
        font-size: 0.75rem;
    }
    
    .btn-group-vertical .btn {
        padding: 4px 6px;
        font-size: 0.75rem;
    }
    
    .text-truncate {
        max-width: 120px;
    }
    
    .swipeable-item {
        padding: 6px 8px;
    }
    
    .swipeable-item .label {
        font-size: 0.8rem;
        min-width: 70px;
    }
    
    .swipeable-actions {
        padding: 8px;
        gap: 4px;
    }
    
    .swipeable-actions .btn {
        padding: 4px 6px;
        font-size: 0.75rem;
        min-width: 70px;
    }
}

/* Desktop view */
@media (min-width: 769px) {
    .mobile-swipeable-view {
        display: none !important;
    }
    .desktop-table {
        display: block !important;
    }
}
</style>
@endsection