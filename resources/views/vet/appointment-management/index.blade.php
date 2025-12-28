@extends('layouts.vet')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-stethoscope me-2"></i>Pending Appointments</h2>
                <div class="d-flex gap-2">
                    <span class="badge bg-success">{{ $appointments->total() }} Pending Appointments</span>
                </div>
            </div>

            @if($appointments->count() > 0)
                <div class="card shadow">
                    <div class="card-body p-0">
                        <!-- Desktop Table View -->
                        <div class="table-responsive desktop-table">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr class="table-success">
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
                                                        'rejected' => 'dark',
                                                        'cancelled' => 'danger',
                                                        default => 'secondary'
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }}">
                                                    {{ $statusDisplay }}
                                                </span>
                                            </td>
                                            <td>
                                                <small>{{ $appointment->created_at->format('M d, Y') }}</small>
                                                <br>
                                                <small class="text-muted">{{ $appointment->created_at->format('g:i A') }}</small>
                                            </td>
                                           <td>
    <div class="btn-group-vertical btn-group-sm" role="group">
        <a href="{{ route('vet.appointments.show', $appointment) }}" class="btn btn-info btn-sm me-1" title="View Details">
            <i class="fas fa-eye"></i> View
        </a>
        
        <a href="{{ route('vet.messages.index', ['user_id' => $appointment->user_id]) }}" class="btn btn-primary btn-sm me-1" title="Message Owner">
            <i class="fas fa-comments"></i> Message
        </a>
        
        {{-- Show Accept/Reject buttons for pending appointments --}}
        @if($appointment->status === 'pending')
            <form action="{{ route('vet.appointments.accept', $appointment) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success btn-sm" 
                        onclick="return confirm('Are you sure you want to accept this appointment?')">
                    <i class="fas fa-check me-1"></i>Accept
                </button>
            </form>
            
            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $appointment->id }}">
                <i class="fas fa-times me-1"></i>Reject
            </button>
            
            <!-- Rejection Modal -->
            <div class="modal fade" id="rejectModal{{ $appointment->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $appointment->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('vet.appointments.reject', $appointment) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="rejectModalLabel{{ $appointment->id }}">Reject Appointment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="rejection_reason{{ $appointment->id }}" class="form-label">Reason for Rejection</label>
                                    <textarea class="form-control" id="rejection_reason{{ $appointment->id }}" name="rejection_reason" rows="4" required></textarea>
                                    <div class="form-text">Please provide a reason for rejecting this appointment (maximum 500 characters).</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">Reject Appointment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
        
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
                                                    'rejected' => 'dark',
                                                    'cancelled' => 'danger',
                                                    default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}">
                                                {{ $statusDisplay }}
                                            </span>
                                        </span>
                                    </div>
                                    
                                    <div class="swipeable-item">
                                        <span class="label">Requested:</span>
                                        <span class="value">
                                            {{ $appointment->created_at->format('M d, Y g:i A') }}
                                        </span>
                                    </div>
                                    
                                    <div class="swipeable-actions">
                                        <a href="{{ route('vet.appointments.show', $appointment) }}" class="btn btn-info btn-sm" title="View Details">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        
                                        <a href="{{ route('vet.messages.index', ['user_id' => $appointment->user_id]) }}" class="btn btn-primary btn-sm" title="Message Owner">
                                            <i class="fas fa-comments"></i> Message
                                        </a>
                                        
                                        {{-- Show Accept/Reject buttons for pending appointments --}}
                                        @if($appointment->status === 'pending')
                                            <form action="{{ route('vet.appointments.accept', $appointment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" 
                                                        onclick="return confirm('Are you sure you want to accept this appointment?')">
                                                    <i class="fas fa-check me-1"></i>Accept
                                                </button>
                                            </form>
                                            
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModalMobile{{ $appointment->id }}">
                                                <i class="fas fa-times me-1"></i>Reject
                                            </button>
                                            
                                            <!-- Rejection Modal for Mobile -->
                                            <div class="modal fade" id="rejectModalMobile{{ $appointment->id }}" tabindex="-1" aria-labelledby="rejectModalMobileLabel{{ $appointment->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('vet.appointments.reject', $appointment) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="rejectModalMobileLabel{{ $appointment->id }}">Reject Appointment</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="rejection_reason_mobile{{ $appointment->id }}" class="form-label">Reason for Rejection</label>
                                                                    <textarea class="form-control" id="rejection_reason_mobile{{ $appointment->id }}" name="rejection_reason" rows="4" required></textarea>
                                                                    <div class="form-text">Please provide a reason for rejecting this appointment (maximum 500 characters).</div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger">Reject Appointment</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
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

                <!-- No modals needed as per user request -->
            @else
                <div class="text-center py-5">
                    <i class="fas fa-stethoscope fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No Pending Appointments</h4>
                    <p class="text-muted">You don't have any pending appointment requests assigned to you.</p>
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

/* Vet green theme for table header */
.table-success {
    --bs-table-bg: #27ae60;
    --bs-table-color: #fff;
    --bs-table-border-color: #219653;
}

.table-success th {
    color: #fff !important;
    background-color: #27ae60 !important;
    border-color: #219653 !important;
}

.btn-group-vertical .btn {
    margin-bottom: 2px;
}

/* Desktop view - default display */
.desktop-table {
    display: block;
}

.mobile-swipeable-view {
    display: none;
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* Mobile swipeable view styles */
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
    align-items: flex-start;
    gap: 10px;
}

.swipeable-item:last-of-type {
    border-bottom: none;
}

.swipeable-item .label {
    font-weight: 600;
    color: #495057;
    min-width: 100px;
    flex-shrink: 0;
}

.swipeable-item .value {
    text-align: right;
    flex: 1;
    word-break: break-word;
}

.swipeable-actions {
    padding: 15px;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    border-top: 2px solid #dee2e6;
}

.swipeable-actions .btn {
    flex: 1 1 calc(50% - 4px);
    min-width: 120px;
    white-space: nowrap;
}

.swipeable-actions form {
    flex: 1 1 calc(50% - 4px);
    min-width: 120px;
}

.swipeable-actions form .btn {
    width: 100%;
}

/* Tablet and below - show mobile view */
@media (max-width: 991px) {
    .desktop-table {
        display: none !important;
    }
    
    .mobile-swipeable-view {
        display: block;
        padding: 15px;
    }
    
    h2 {
        font-size: 1.5rem;
    }
    
    .d-flex.justify-content-between.align-items-center {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 10px;
    }
    
    .d-flex.justify-content-between.align-items-center h2 {
        margin-bottom: 0;
    }
}

/* Small tablets and large phones */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .mobile-swipeable-view {
        padding: 10px;
    }
    
    .swipeable-item {
        padding: 10px 12px;
        flex-direction: column;
        align-items: flex-start;
    }
    
    .swipeable-item .label {
        min-width: auto;
        margin-bottom: 4px;
    }
    
    .swipeable-item .value {
        text-align: left;
        width: 100%;
    }
    
    .swipeable-actions {
        padding: 12px;
        gap: 8px;
    }
    
    .swipeable-actions .btn {
        flex: 1 1 100%;
        min-width: 100%;
    }
    
    .swipeable-actions form {
        flex: 1 1 100%;
        min-width: 100%;
    }
    
    h2 {
        font-size: 1.3rem;
    }
    
    h2 i {
        font-size: 1.1rem;
    }
    
    .badge {
        font-size: 0.85rem;
    }
}

/* Mobile phones */
@media (max-width: 576px) {
    .container-fluid {
        padding-left: 8px;
        padding-right: 8px;
    }
    
    .mobile-swipeable-view {
        padding: 8px;
    }
    
    .swipeable-appointment-card {
        margin-bottom: 12px;
    }
    
    .swipeable-item {
        padding: 8px 10px;
    }
    
    .swipeable-item .label {
        font-size: 0.9rem;
    }
    
    .swipeable-item .value {
        font-size: 0.9rem;
    }
    
    .swipeable-actions {
        padding: 10px;
        gap: 6px;
    }
    
    .swipeable-actions .btn {
        padding: 8px 12px;
        font-size: 0.875rem;
    }
    
    h2 {
        font-size: 1.2rem;
    }
    
    .badge {
        font-size: 0.8rem;
        padding: 4px 8px;
    }
    
    .modal-dialog {
        margin: 10px;
    }
    
    .modal-body {
        padding: 15px;
    }
}

/* Very small screens */
@media (max-width: 400px) {
    .container-fluid {
        padding-left: 5px;
        padding-right: 5px;
    }
    
    .mobile-swipeable-view {
        padding: 5px;
    }
    
    .swipeable-appointment-card {
        margin-bottom: 10px;
        border-radius: 6px;
    }
    
    .swipeable-item {
        padding: 6px 8px;
    }
    
    .swipeable-item .label {
        font-size: 0.85rem;
    }
    
    .swipeable-item .value {
        font-size: 0.85rem;
    }
    
    .swipeable-actions {
        padding: 8px;
        gap: 5px;
    }
    
    .swipeable-actions .btn {
        padding: 6px 10px;
        font-size: 0.8rem;
    }
    
    h2 {
        font-size: 1.1rem;
    }
    
    h2 i {
        font-size: 1rem;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 3px 6px;
    }
}

/* Large desktop - ensure table remains visible */
@media (min-width: 992px) {
    .mobile-swipeable-view {
        display: none !important;
    }
    
    .desktop-table {
        display: block !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum datetime to current time for scheduling
    const datetimeInputs = document.querySelectorAll('input[type="datetime-local"]');
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    const minDateTime = now.toISOString().slice(0, 16);
    
    datetimeInputs.forEach(input => {
        input.min = minDateTime;
    });
});
</script>
@endsection