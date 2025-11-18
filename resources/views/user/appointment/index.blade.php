@extends('layouts.app')

@section('title', 'Appointments')

@section('styles')
<style>
    .admin-header {
        background: #fff; padding: 30px; border-radius: 15px; margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        text-align: center; /* Center content in header */
    }
    .admin-title { font-size: 2.5rem; color: #5b4b9b; margin-bottom: 10px; }
    .admin-subtitle { font-size: 1.1rem; color: #666; }

    .request-btn-container {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }

    .stats-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px; margin-bottom: 40px;
    }
    .stat-card {
        background: #fff; padding: 25px; border-radius: 12px;
        text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-left: 4px solid #5b4b9b;
    }
    .stat-card.pending { border-left-color: #f39c12; }
    .stat-card.confirmed { border-left-color: #27ae60; }
    .stat-card.completed { border-left-color: #e74c3c; }
    .stat-number {
        font-size: 2.5rem; font-weight: 700; margin-bottom: 10px;
    }
    .stat-card.pending .stat-number { color: #f39c12; }
    .stat-card.confirmed .stat-number { color: #27ae60; }
    .stat-card.completed .stat-number { color: #e74c3c; }
    .stat-label {
        font-size: 1rem; color: #666; text-transform: uppercase;
        font-weight: 600;
    }

    .section-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #eee;
    }
    .section-title {
        font-size: 1.5rem; font-weight: 600; color: #333;
        display: flex; align-items: center; gap: 10px;
    }
    .section-count {
        background: #5b4b9b; color: #fff; padding: 4px 12px;
        border-radius: 20px; font-size: 0.9rem; font-weight: 600;
    }

    .listings-table {
        background: #fff; border-radius: 15px; overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08); margin-bottom: 40px;
    }
    .table {
        width: 100%; border-collapse: collapse;
    }
    .table th {
        background: #f8f9fa; padding: 15px; text-align: left;
        font-weight: 600; color: #333; border-bottom: 1px solid #eee;
    }
    .table td {
        padding: 15px; border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }
    .table tr:hover { background: #f9f9f9; }

    .pet-info {
        display: flex; align-items: center; gap: 15px;
    }
    .pet-details h4 {
        font-size: 1rem; font-weight: 600; color: #333; margin-bottom: 5px;
    }
    .pet-details p {
        font-size: 0.85rem; color: #666; margin-bottom: 2px;
    }

    .status-badge {
        padding: 6px 12px; border-radius: 20px; font-size: 0.8rem;
        font-weight: 600; text-transform: uppercase;
    }
    .status-badge.pending { background: #f39c12; color: #fff; }
    .status-badge.accepted { background: #27ae60; color: #fff; }
    .status-badge.rejected { background: #e74c3c; color: #fff; }
    .status-badge.cancelled { background: #6c757d; color: #fff; }

    .action-buttons {
        display: flex; gap: 8px;
    }
    .btn {
        padding: 8px 16px; border: none; border-radius: 6px;
        text-decoration: none; font-weight: 600; cursor: pointer;
        transition: 0.2s; display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.85rem;
    }
    .btn-primary { background: #5b4b9b; color: #fff; }
    .btn-primary:hover { background: #4a3d7a; }
    .btn-success { background: #27ae60; color: #fff; }
    .btn-success:hover { background: #229954; }
    .btn-warning { background: #f39c12; color: #fff; }
    .btn-warning:hover { background: #e67e22; }
    .btn-secondary { background: #6c757d; color: #fff; }
    .btn-secondary:hover { background: #5a6268; }

    .alert {
        padding: 15px 20px; border-radius: 8px; margin-bottom: 20px;
        font-weight: 500;
    }
    .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }

    .pagination {
        display: flex; justify-content: center; gap: 10px; margin-top: 20px;
    }
    .pagination a, .pagination span {
        padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px;
        text-decoration: none; color: #666; transition: 0.2s;
    }
    .pagination a:hover { background: #5b4b9b; color: #fff; border-color: #5b4b9b; }
    .pagination .current { background: #5b4b9b; color: #fff; border-color: #5b4b9b; }
    
    /* Swipeable table for mobile */
    .swipeable-table-container {
        display: none;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding: 10px 0;
    }
    
    .swipeable-table-row {
        display: flex;
        flex-direction: column;
        border: 1px solid #eee;
        border-radius: 10px;
        margin-bottom: 15px;
        background: #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .swipeable-table-item {
        padding: 12px 15px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
    }
    
    .swipeable-table-item:last-child {
        border-bottom: none;
    }
    
    .swipeable-table-label {
        font-weight: 600;
        color: #5b4b9b;
        min-width: 100px;
    }
    
    .swipeable-table-value {
        text-align: right;
        flex: 1;
    }
    
    .swipeable-status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .swipeable-action-buttons {
        display: flex;
        gap: 8px;
        padding: 10px 15px;
    }
    
    .swipeable-action-buttons .btn {
        flex: 1;
        justify-content: center;
        padding: 8px;
        font-size: 0.8rem;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        .admin-header {
            padding: 20px 15px;
        }
        
        .admin-title {
            font-size: 2rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .stat-card {
            padding: 20px 15px;
        }
        
        .stat-number {
            font-size: 2rem;
        }
        
        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .table-container {
            display: none;
        }
        
        .swipeable-table-container {
            display: block;
        }
        
        .table {
            display: block;
            overflow-x: auto;
        }
        
        .table th,
        .table td {
            padding: 10px 8px;
            font-size: 0.9rem;
        }
        
        .pet-info {
            gap: 10px;
        }
        
        .pet-details h4 {
            font-size: 0.95rem;
        }
        
        .pet-details p {
            font-size: 0.8rem;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 5px;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
            padding: 6px 12px;
            font-size: 0.8rem;
        }
        
        .status-badge {
            font-size: 0.7rem;
            padding: 4px 8px;
        }
    }
    
    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .section-title {
            font-size: 1.25rem;
        }
        
        .admin-title {
            font-size: 1.75rem;
        }
        
        .admin-subtitle {
            font-size: 1rem;
        }
        
        .request-btn-container .btn {
            width: 100%;
            padding: 10px;
        }
        
        .table th,
        .table td {
            padding: 8px 6px;
            font-size: 0.85rem;
        }
        
        .pet-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
    }
    
    /* Desktop view - hide swipeable table */
    @media (min-width: 769px) {
        .swipeable-table-container {
            display: none !important;
        }
        .table-container {
            display: block !important;
        }
    }
</style>
@endsection

@section('content')
    <div class="admin-header">
        <h1 class="admin-title">My Appointments</h1>
        <p class="admin-subtitle">Manage your pet appointments</p>
        <div class="request-btn-container">
            <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Request Appointment
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($appointments->count() > 0)
        <div class="stats-grid">
            <div class="stat-card pending">
                <div class="stat-number">{{ $appointments->where('status', 'pending')->count() }}</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card confirmed">
                <div class="stat-number">{{ $appointments->where('status', 'accepted')->count() }}</div>
                <div class="stat-label">Accepted</div>
            </div>
            <div class="stat-card completed">
                <div class="stat-number">{{ $appointments->where('status', 'rejected')->count() }}</div>
                <div class="stat-label">Rejected</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $appointments->count() }}</div>
                <div class="stat-label">Total</div>
            </div>
        </div>

        <!-- All Appointments -->
        <div class="listings-table">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-stethoscope"></i>
                    All Appointments
                    <span class="section-count">{{ $appointments->count() }}</span>
                </h2>
            </div>
            
            <!-- Desktop Table View -->
                    <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Pet Name</th>
                            <th>Status</th>
                            <th>Veterinarian</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                            <tr>
                                <td>
                                    <div class="pet-info">
                                        <div class="pet-details">
                                            <h4>{{ $appointment->pet_name }}</h4>
                                            {{-- Pet complaint removed per simplified requirements --}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        // Map statuses for pet parents view
                                        $statusDisplay = match($appointment->status) {
                                            'pending' => 'Pending Review',
                                            'accepted' => 'Accepted',
                                            'rejected' => 'Rejected',
                                            'cancelled' => 'Cancelled',
                                            default => ucfirst($appointment->status)
                                        };
                                        
                                        // Map CSS classes for pet parents view
                                        $statusClass = match($appointment->status) {
                                            'pending' => 'pending',
                                            'accepted' => 'accepted',
                                            'rejected' => 'rejected',
                                            'cancelled' => 'cancelled',
                                            default => 'pending'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">{{ $statusDisplay }}</span>
                                </td>
                                <td>
                                    @if($appointment->vet)
                                        <span class="badge bg-success">Dr. {{ $appointment->vet->name }}</span>
                                    @else
                                        <span class="badge bg-warning">Not assigned</span>
                                    @endif
                                </td>
                                <td>{{ $appointment->created_at->format('M d, Y') }}</td>
                                
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        @if($appointment->status === 'pending')
                                            <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-warning">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                @if($appointments->hasPages())
                    <div class="pagination">
                        {{ $appointments->links() }}
                    </div>
                @endif
            </div>
            
            <!-- Mobile Swipeable View -->
            <div class="swipeable-table-container">
                        @foreach($appointments as $appointment)
                    <div class="swipeable-table-row">
                        <div class="swipeable-table-item">
                            <span class="swipeable-table-label">Pet Name:</span>
                            <span class="swipeable-table-value">{{ $appointment->pet_name }}</span>
                        </div>
                        
                        {{-- Removed pet species and breed information as per user request --}}
                        
                        <div class="swipeable-table-item">
                            <span class="swipeable-table-label">Status:</span>
                            <span class="swipeable-table-value">
                                @php
                                    // Map statuses for pet parents view
                                    $statusDisplay = match($appointment->status) {
                                        'pending' => 'Pending Review',
                                        'accepted' => 'Accepted',
                                        'rejected' => 'Rejected',
                                        'cancelled' => 'Cancelled',
                                        default => ucfirst($appointment->status)
                                    };
                                    
                                    // Map CSS classes for pet parents view
                                    $statusClass = match($appointment->status) {
                                        'pending' => 'pending',
                                        'accepted' => 'accepted',
                                        'rejected' => 'rejected',
                                        'cancelled' => 'cancelled',
                                        default => 'pending'
                                    };
                                @endphp
                                <span class="swipeable-status-badge {{ $statusClass }}">{{ $statusDisplay }}</span>
                            </span>
                        </div>
                        
                        <div class="swipeable-table-item">
                            <span class="swipeable-table-label">Veterinarian:</span>
                            <span class="swipeable-table-value">
                                @if($appointment->vet)
                                    Dr. {{ $appointment->vet->name }}
                                @else
                                    Not assigned
                                @endif
                            </span>
                        </div>
                        
                        <div class="swipeable-table-item">
                            <span class="swipeable-table-label">Created:</span>
                            <span class="swipeable-table-value">{{ $appointment->created_at->format('M d, Y') }}</span>
                        </div>
                        
                        
                        
                        <div class="swipeable-action-buttons">
                            <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i> View
                            </a>
                            @if($appointment->status === 'pending')
                                <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
                
                @if($appointments->hasPages())
                    <div class="pagination">
                        {{ $appointments->links() }}
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="listings-table">
            <div style="padding: 40px; text-align: center; color: #666;">
                <i class="fas fa-stethoscope" style="font-size: 3rem; color: #5b4b9b; margin-bottom: 15px;"></i>
                <h3>No appointments yet</h3>
                <p>Start your pet's healthcare journey by requesting your first appointment.</p>
                <!-- Button is already in header, so no need to duplicate here -->
            </div>
        </div>
    @endif
@endsection