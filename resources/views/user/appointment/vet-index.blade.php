@extends('layouts.vet')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-stethoscope me-2"></i>Pending Appointments</h2>
                <div class="d-flex gap-2">
                    <span class="badge badge-info">{{ $appointments->total() }} Pending Appointments</span>
                </div>
            </div>

            @if($appointments->count() > 0)
                <div class="card shadow">
                    <div class="card-body p-0">
                        <div class="table-responsive">
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
                                                        'rejected' => 'dark',
                                                        'cancelled' => 'danger',
                                                        default => 'secondary'
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }}">
                                                    {{ $statusDisplay }}
                                                </span>
                                                @if($appointment->status === 'rejected' && $appointment->rejection_reason)
                                                    <div class="mt-1">
                                                        <small class="text-muted">Reason: {{ \Illuminate\Support\Str::limit($appointment->rejection_reason, 50) }}</small>
                                                    </div>
                                                @endif
                                                @if($appointment->vet_id === auth()->id())
                                                    <br><small class="text-success">Assigned to you</small>
                                                @elseif($appointment->vet)
                                                    <br><small class="text-muted">Dr. {{ $appointment->vet->name }}</small>
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

        
        {{-- Show Accept/Reject buttons for pending appointments --}}
        @if($appointment->status === 'pending')
            <form action="{{ route('appointments.accept', $appointment) }}" method="POST" class="d-inline">
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
                        <form action="{{ route('appointments.reject', $appointment) }}" method="POST">
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
        

        
        {{-- No actions after acceptance as per user request --}}
    </div>
</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                    <p class="text-muted">There are currently no pending appointment requests to review.</p>
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

/* Mobile responsiveness improvements */
@media (max-width: 768px) {
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        max-height: none;
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