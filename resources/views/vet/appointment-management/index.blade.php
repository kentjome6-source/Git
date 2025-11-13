@extends('layouts.vet')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-stethoscope me-2"></i>Your Appointments</h2>
                <div class="d-flex gap-2">
                    <span class="badge badge-info">{{ $appointments->total() }} Appointments</span>
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
                                        <th>Priority</th>
                                        <th>Pet Owner</th>
                                        <th>Pet Name</th>
                                        <th>Chief Complaint</th>
                                        <th>Status</th>
                                        <th>Requested</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $appointment)
                                        <tr class="{{ $appointment->urgency_level === 'emergency' ? 'table-danger' : ($appointment->urgency_level === 'high' ? 'table-warning' : '') }}">
                                            <td>
                                                <span class="badge {{ $appointment->getUrgencyBadgeClass() }}">
                                                    {{ ucfirst($appointment->urgency_level) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $appointment->owner_name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $appointment->owner_email }}</small>
                                                    <br>
                                                    <small class="text-muted">{{ $appointment->owner_phone }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $appointment->pet_name }}</strong>
                                                    {{-- Removed pet species and breed information as per user request --}}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;" title="{{ $appointment->chief_complaint }}">
                                                    {{ Str::limit($appointment->chief_complaint, 80) }}
                                                </div>
                                                <small class="text-muted">
                                                    Reason: {{ ucfirst(str_replace('_', ' ', $appointment->consultation_reason)) }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge {{ $appointment->getStatusBadgeClass() }}">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small>{{ $appointment->created_at->format('M d, Y') }}</small>
                                                <br>
                                                <small class="text-muted">{{ $appointment->created_at->format('g:i A') }}</small>
                                                @if($appointment->scheduled_datetime)
                                                    <br>
                                                    <small class="text-info">
                                                        Scheduled: {{ $appointment->scheduled_datetime->format('M d, g:i A') }}
                                                    </small>
                                                @endif
                                            </td>
                                           <td>
    <div class="btn-group-vertical btn-group-sm" role="group">
        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-info btn-sm me-1" title="View Details">
            <i class="fas fa-eye"></i>
        </a>
        
        {{-- Only show Accept/Reject buttons for appointment-type consultations that are pending --}}
        @if($appointment->status === 'pending' && (!$appointment->vet_id || $appointment->vet_id === auth()->id()) && $appointment->consultation_type === 'appointment')
            <form action="{{ route('appointments.accept', $appointment) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success btn-sm" 
                        onclick="return confirm('Are you sure you want to accept this appointment?')">
                    <i class="fas fa-check me-1"></i>Accept
                </button>
            </form>
            
            <form action="{{ route('appointments.reject', $appointment) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm" 
                        onclick="return confirm('Are you sure you want to reject this appointment?')">
                    <i class="fas fa-times me-1"></i>Reject
                </button>
            </form>
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
                                        <span class="label">Priority:</span>
                                        <span class="value">
                                            <span class="badge {{ $appointment->getUrgencyBadgeClass() }}">
                                                {{ ucfirst($appointment->urgency_level) }}
                                            </span>
                                        </span>
                                    </div>
                                    
                                    <div class="swipeable-item">
                                        <span class="label">Pet Owner:</span>
                                        <span class="value">
                                            <strong>{{ $appointment->owner_name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $appointment->owner_email }}</small>
                                            <br>
                                            <small class="text-muted">{{ $appointment->owner_phone }}</small>
                                        </span>
                                    </div>
                                    
                                    <div class="swipeable-item">
                                        <span class="label">Pet Name:</span>
                                        <span class="value">
                                            <strong>{{ $appointment->pet_name }}</strong>
                                            {{-- Removed pet species and breed information as per user request --}}
                                        </span>
                                    </div>
                                    
                                    <div class="swipeable-item">
                                        <span class="label">Complaint:</span>
                                        <span class="value">
                                            {{ Str::limit($appointment->chief_complaint, 80) }}
                                            <br>
                                            <small class="text-muted">
                                                Reason: {{ ucfirst(str_replace('_', ' ', $appointment->consultation_reason)) }}
                                            </small>
                                        </span>
                                    </div>
                                    
                                    <div class="swipeable-item">
                                        <span class="label">Status:</span>
                                        <span class="value">
                                            <span class="badge {{ $appointment->getStatusBadgeClass() }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </span>
                                    </div>
                                    
                                    <div class="swipeable-item">
                                        <span class="label">Requested:</span>
                                        <span class="value">
                                            {{ $appointment->created_at->format('M d, Y g:i A') }}
                                            @if($appointment->scheduled_datetime)
                                                <br>
                                                <small class="text-info">
                                                    Scheduled: {{ $appointment->scheduled_datetime->format('M d, g:i A') }}
                                                </small>
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <div class="swipeable-actions">
                                        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-info btn-sm" title="View Details">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        
                                        {{-- Only show Accept/Reject buttons for appointment-type consultations that are pending --}}
                                        @if($appointment->status === 'pending' && (!$appointment->vet_id || $appointment->vet_id === auth()->id()) && $appointment->consultation_type === 'appointment')
                                            <form action="{{ route('appointments.accept', $appointment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" 
                                                        onclick="return confirm('Are you sure you want to accept this appointment?')">
                                                    <i class="fas fa-check me-1"></i>Accept
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('appointments.reject', $appointment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        onclick="return confirm('Are you sure you want to reject this appointment?')">
                                                    <i class="fas fa-times me-1"></i>Reject
                                                </button>
                                            </form>
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
                    <h4 class="text-muted">No Appointment Requests</h4>
                    <p class="text-muted">You don't have any appointment requests assigned to you yet.</p>
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
        min-width: 80px;
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
        min-width: 70px;
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
        min-width: 60px;
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