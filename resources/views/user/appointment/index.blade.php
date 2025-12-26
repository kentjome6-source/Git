@extends('layouts.app')

@section('title', 'Appointments')

@section('content')
<div class="appointments-page">
    <div class="container-fluid px-4 py-5">
        <!-- Page Header -->
        <div class="page-header mb-5">
            <div class="header-content">
                <div class="header-text">
                    <span class="label">Healthcare</span>
                    <h1 class="page-title">My Appointments</h1>
                    <p class="page-subtitle">Manage your pet's healthcare appointments</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('appointments.history') }}" class="btn-history">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span>History</span>
                    </a>
                    <button type="button" class="btn-create-appointment" data-bs-toggle="modal" data-bs-target="#requestAppointmentModal">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Request Appointment</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert-success-custom mb-4">
                <div class="alert-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <div class="alert-content">{{ session('success') }}</div>
                <button type="button" class="alert-close" data-bs-dismiss="alert">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        @endif

        @if($appointments->count() > 0)
            <!-- Stats Cards -->
            <div class="stats-grid mb-5">
                <div class="stat-card">
                    <div class="stat-icon stat-icon-pending">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <div class="stat-number">{{ $appointments->count() }}</div>
                    <div class="stat-label">Pending Review</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon stat-icon-total">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                        </svg>
                    </div>
                    <div class="stat-number">{{ $appointments->count() }}</div>
                    <div class="stat-label">Total Appointments</div>
                </div>
            </div>

            <!-- Appointments List -->
            <div class="appointments-container">
                <div class="section-header">
                    <h2 class="section-title">All Appointments</h2>
                    <span class="section-badge">{{ $appointments->count() }}</span>
                </div>
                
                <div class="appointments-list">
                    @foreach($appointments as $appointment)
                        <div class="appointment-card">
                            <div class="appointment-header">
                                <div class="pet-info">
                                    <h3 class="pet-name">{{ $appointment->pet_name }}</h3>
                                    <div class="appointment-meta">
                                        <span class="meta-item">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                            {{ $appointment->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="appointment-status">
                                    @php
                                        $statusDisplay = match($appointment->status) {
                                            'pending' => 'Pending',
                                            'accepted' => 'Accepted',
                                            'rejected' => 'Rejected',
                                            'cancelled' => 'Cancelled',
                                            default => ucfirst($appointment->status)
                                        };
                                        
                                        $statusClass = match($appointment->status) {
                                            'pending' => 'status-pending',
                                            'accepted' => 'status-accepted',
                                            'rejected' => 'status-rejected',
                                            'cancelled' => 'status-cancelled',
                                            default => 'status-pending'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">{{ $statusDisplay }}</span>
                                </div>
                            </div>
                            
                            <div class="appointment-body">
                                <div class="info-item">
                                    <div class="info-label">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        Veterinarian
                                    </div>
                                    <div class="info-value">
                                        @if($appointment->vet)
                                            Dr. {{ $appointment->vet->name }}
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="appointment-footer">
                                <a href="{{ route('appointments.show', $appointment) }}" class="btn-view">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    View Details
                                </a>
                                
                                @if($appointment->vet)
                                    <a href="{{ route('messages.index', ['user_id' => $appointment->vet_id]) }}" class="btn-message">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                        </svg>
                                        Message Vet
                                    </a>
                                @endif
                                
                                @if($appointment->status === 'pending')
                                    <button type="button" class="btn-edit" 
                                            onclick="openEditModal({{ $appointment->id }})">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        Edit
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($appointments->hasPages())
                    <div class="pagination-wrapper">
                        {{ $appointments->links() }}
                    </div>
                @endif
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                    </svg>
                </div>
                <h3 class="empty-title">No appointments yet</h3>
                <p class="empty-text">Start your pet's healthcare journey by requesting your first appointment</p>
                {{-- <button type="button" class="btn-empty-action" data-bs-toggle="modal" data-bs-target="#requestAppointmentModal">
                    Request Appointment
                </button> --}}
            </div>
        @endif
    </div>
</div>

<!-- Request Appointment Modal -->
<div class="modal fade" id="requestAppointmentModal" tabindex="-1" aria-labelledby="requestAppointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="requestAppointmentModalLabel">
                    <i class="fas fa-stethoscope me-2"></i>Request Appointment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('appointments.store') }}" method="POST" id="consultationForm">
                @csrf
                <div class="modal-body">
                    <!-- Hidden appointment type field -->
                    <input type="hidden" name="appointment_type" value="appointment">

                    <!-- Veterinarian Selection -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <h6 class="text-dark border-bottom pb-1 mb-2">
                                <i class="fas fa-user-md me-2 text-info"></i>Select Veterinarian
                            </h6>
                        </div>
                        <div class="col-12">
                            <div class="mb-2">
                                <label for="vet_id" class="form-label">Veterinarian *</label>
                                <select name="vet_id" id="vet_id" class="form-select" required>
                                    <option value="">Select a veterinarian</option>
                                    @foreach($vets as $vet)
                                        @if($vet->is_verified_vet)
                                            <option value="{{ $vet->id }}">
                                                Dr. {{ $vet->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="form-text">Only verified veterinarians are listed here. Your appointment request will be sent to the selected veterinarian only.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Owner Information Section -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <h6 class="text-dark border-bottom pb-1 mb-2">
                                <i class="fas fa-user me-2 text-warning"></i>Owner Information
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="owner_name" class="form-label">Full Name *</label>
                                <input type="text" name="owner_name" id="owner_name" class="form-control" 
                                       value="{{ old('owner_name', auth()->user()->name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="owner_phone" class="form-label">Phone *</label>
                                <input type="tel" name="owner_phone" id="owner_phone" class="form-control" 
                                       value="{{ old('owner_phone') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" name="email" id="email" class="form-control" 
                                       value="{{ old('email', auth()->user()->email) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="owner_address" class="form-label">Address</label>
                                <input type="text" name="owner_address" id="owner_address" class="form-control" 
                                       value="{{ old('owner_address') }}" placeholder="Enter your full address">
                            </div>
                        </div>
                    </div>

                    <!-- Pet Information Section -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <h6 class="text-dark border-bottom pb-1 mb-2">
                                <i class="fas fa-paw me-2 text-danger"></i>Pet Information
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="pet_name" class="form-label">Pet Name *</label>
                                <input type="text" name="pet_name" id="pet_name" class="form-control" 
                                       value="{{ old('pet_name') }}" placeholder="Enter your pet's name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="pet_type" class="form-label">Pet Type *</label>
                                <select name="pet_type" id="pet_type" class="form-select" required>
                                    <option value="">Select pet type</option>
                                    <option value="Dog" {{ old('pet_type') == 'Dog' ? 'selected' : '' }}>Dog</option>
                                    <option value="Cat" {{ old('pet_type') == 'Cat' ? 'selected' : '' }}>Cat</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-2">
                                <label for="pet_services_received" class="form-label">Pet Services Received</label>
                                <textarea name="pet_services_received" id="pet_services_received" class="form-control" 
                                          rows="2" placeholder="Enter services your pet has received (e.g., Deworming, Vaccination, Tick and Flea Prevention)">{{ old('pet_services_received') }}</textarea>
                                <div class="form-text">List any services your pet has recently received, such as deworming, vaccination, or tick and flea prevention.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Scheduling Section -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <h6 class="text-dark border-bottom pb-1 mb-2">
                                <i class="fas fa-calendar me-2 text-primary"></i>Scheduling
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="preferred_date" class="form-label">Preferred Date</label>
                                <input type="date" name="preferred_date" id="preferred_date" 
                                       class="form-control" value="{{ old('preferred_date') }}" placeholder="Select a date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="preferred_time" class="form-label">Preferred Time</label>
                                <input type="time" name="preferred_time" id="preferred_time" 
                                       class="form-control" value="{{ old('preferred_time') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane me-2"></i>Submit Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Appointment Modal -->
<div class="modal fade" id="editAppointmentModal" tabindex="-1" aria-labelledby="editAppointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editAppointmentModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Appointment
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editAppointmentForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editModalBody">
                    <!-- Content will be loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Update Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    :root {
        --slate: #0f172a;
        --slate-light: #1e293b;
        --blue: #3b82f6;
        --purple: #8b5cf6;
        --green: #10b981;
        --orange: #f59e0b;
        --red: #ef4444;
        --gray: #64748b;
        --gray-light: #f1f5f9;
        --gray-lighter: #f8fafc;
    }

    .appointments-page {
        font-family: 'Sora', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--gray-lighter);
        min-height: 100vh;
    }

    /* Page Header */
    .page-header {
        animation: fadeInDown 0.6s ease-out;
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

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 24px;
    }

    .header-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .label {
        display: block;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--blue);
        margin-bottom: 12px;
        font-weight: 600;
    }

    .page-title {
        font-size: clamp(2rem, 4vw, 2.75rem);
        font-weight: 700;
        color: var(--slate);
        margin-bottom: 8px;
        letter-spacing: -0.02em;
    }

    .page-subtitle {
        font-size: 1.05rem;
        color: var(--gray);
    }

    .btn-history {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: white;
        color: var(--purple);
        border: 2px solid var(--purple);
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-history:hover {
        background: var(--purple);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .btn-create-appointment {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: var(--purple);
        color: white;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-create-appointment:hover {
        background: #7c3aed;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        color: white;
    }

    /* Success Alert */
    .alert-success-custom {
        background: #d1fae5;
        border: 1px solid #a7f3d0;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        animation: slideDown 0.4s ease-out;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-icon {
        flex-shrink: 0;
        color: #059669;
    }

    .alert-content {
        flex: 1;
        color: #065f46;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .alert-close {
        flex-shrink: 0;
        background: none;
        border: none;
        color: #059669;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        transition: opacity 0.2s;
    }

    .alert-close:hover {
        opacity: 0.7;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 24px;
        animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 28px;
        border: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }

    .stat-icon-pending {
        background: rgba(245, 158, 11, 0.1);
        color: var(--orange);
    }

    .stat-icon-total {
        background: rgba(139, 92, 246, 0.1);
        color: var(--purple);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--slate);
        margin-bottom: 8px;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--gray);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Appointments Container */
    .appointments-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e2e8f0;
    }

    .section-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--slate);
        letter-spacing: -0.01em;
    }

    .section-badge {
        background: var(--purple);
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    /* Appointments List */
    .appointments-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .appointment-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .appointment-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        border-color: var(--blue);
    }

    .appointment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px;
        border-bottom: 1px solid #e2e8f0;
    }

    .pet-name {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--slate);
        margin-bottom: 8px;
    }

    .appointment-meta {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85rem;
        color: var(--gray);
    }

    .meta-item svg {
        flex-shrink: 0;
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .status-accepted {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .status-rejected {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    .status-cancelled {
        background: rgba(100, 116, 139, 0.1);
        color: var(--gray);
    }

    .appointment-body {
        padding: 24px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
    }

    .info-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        color: var(--gray);
        font-weight: 500;
    }

    .info-value {
        font-size: 0.95rem;
        color: var(--slate);
        font-weight: 500;
        text-align: right;
    }

    .text-muted {
        color: var(--gray);
        font-weight: 400;
    }

    .appointment-footer {
        display: flex;
        gap: 12px;
        padding: 20px 24px;
        border-top: 1px solid #e2e8f0;
    }

    .btn-view,
    .btn-message,
    .btn-edit {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-view {
        background: var(--purple);
        color: white;
        text-decoration: none;
    }

    .btn-view:hover {
        background: #7c3aed;
        transform: translateY(-1px);
        color: white;
    }

    .btn-message {
        background: white;
        color: var(--blue);
        border: 1px solid var(--blue);
        text-decoration: none;
    }

    .btn-message:hover {
        background: var(--blue);
        color: white;
        transform: translateY(-1px);
    }

    .btn-edit {
        background: white;
        color: var(--orange);
        border: 1px solid var(--orange);
    }

    .btn-edit:hover {
        background: var(--orange);
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        max-width: 600px;
        margin: 0 auto;
    }

    .empty-icon {
        margin-bottom: 24px;
        color: var(--gray);
        opacity: 0.4;
    }

    .empty-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: var(--slate);
        margin-bottom: 12px;
    }

    .empty-text {
        font-size: 1.05rem;
        color: var(--gray);
        margin-bottom: 28px;
    }

    .btn-empty-action {
        display: inline-flex;
        padding: 14px 28px;
        background: var(--purple);
        color: white;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-empty-action:hover {
        background: #7c3aed;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        color: white;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 40px;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .modal-header {
        border-radius: 12px 12px 0 0;
        padding: 1.25rem 1.5rem;
    }

    .modal-body {
        padding: 1.5rem;
        max-height: 70vh;
        overflow-y: auto;
    }

    .modal-footer {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid #dee2e6;
    }

    .btn-close-white {
        filter: brightness(0) invert(1);
    }

    /* Form Styles in Modal */
    .form-control:focus, .form-select:focus {
        border-color: #5b4b9b;
        box-shadow: 0 0 0 0.2rem rgba(91, 75, 155, 0.25);
    }

    .form-text {
        font-size: 0.875rem;
        color: #6c757d;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
        }

        .btn-history,
        .btn-create-appointment {
            flex: 1;
            justify-content: center;
        }

        .page-title {
            font-size: 1.75rem;
        }

        .page-subtitle {
            font-size: 1rem;
        }

        .stats-grid {
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .stat-card {
            padding: 20px;
        }

        .stat-number {
            font-size: 2rem;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .appointment-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }

        .appointment-footer {
            flex-direction: column;
        }

        .btn-view,
        .btn-edit {
            width: 100%;
            justify-content: center;
        }

        .modal-dialog {
            margin: 0.5rem;
        }

        .modal-body {
            padding: 1rem;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 16px !important;
            padding-right: 16px !important;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .appointment-card {
            margin-bottom: 16px;
        }

        .appointment-header,
        .appointment-body,
        .appointment-footer {
            padding: 16px;
        }

        .pet-name {
            font-size: 1.1rem;
        }

        .section-title {
            font-size: 1.5rem;
        }

        .modal-dialog {
            margin: 0.25rem;
        }
    }

    @media (max-width: 400px) {
        .stat-card {
            padding: 16px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
        }

        .stat-number {
            font-size: 1.75rem;
        }

        .modal-body {
            padding: 0.75rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date to current date for new appointment modal
    const dateInput = document.getElementById('preferred_date');
    const timeInput = document.getElementById('preferred_time');
    
    if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.min = today;
    }
    
    // Set minimum time based on current date
    if (dateInput && timeInput) {
        dateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            selectedDate.setHours(0, 0, 0, 0);
            
            // If selected date is today, set minimum time to current time
            if (selectedDate.getTime() === today.getTime()) {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = Math.ceil(now.getMinutes() / 30) * 30; // Round up to nearest 30 minutes
                const roundedMinutes = minutes >= 60 ? '00' : String(minutes).padStart(2, '0');
                const minTime = minutes >= 60 ? String(parseInt(hours) + 1).padStart(2, '0') + ':' + roundedMinutes : hours + ':' + roundedMinutes;
                timeInput.min = minTime;
            } else {
                timeInput.min = '00:00';
            }
        });
    }

    // Handle form submission for new appointment
    const consultationForm = document.getElementById('consultationForm');
    if (consultationForm) {
        consultationForm.addEventListener('submit', function(e) {
            // Validate form
            const vetSelect = document.getElementById('vet_id');
            const petName = document.getElementById('pet_name');
            const petType = document.getElementById('pet_type');
            
            if (!vetSelect.value) {
                e.preventDefault();
                showError('Please select a veterinarian.');
                vetSelect.focus();
                return;
            }
            
            if (!petName.value.trim()) {
                e.preventDefault();
                showError('Please enter your pet\'s name.');
                petName.focus();
                return;
            }
            
            if (!petType.value) {
                e.preventDefault();
                showError('Please select your pet type.');
                petType.focus();
                return;
            }
            
            // Show loading indicator
            const submitBtn = consultationForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
            
            // Form is valid, allow submission
        });
    }

    // Handle edit form submission
    const editAppointmentForm = document.getElementById('editAppointmentForm');
    if (editAppointmentForm) {
        editAppointmentForm.addEventListener('submit', function(e) {
            // Show loading indicator
            const submitBtn = editAppointmentForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
        });
    }

    // Reset form when modal is closed
    const requestModal = document.getElementById('requestAppointmentModal');
    if (requestModal) {
        requestModal.addEventListener('hidden.bs.modal', function () {
            const form = document.getElementById('consultationForm');
            if (form) {
                form.reset();
                
                // Reset owner name and email to current user
                const ownerName = document.getElementById('owner_name');
                const email = document.getElementById('email');
                if (ownerName) ownerName.value = '{{ auth()->user()->name }}';
                if (email) email.value = '{{ auth()->user()->email }}';
                
                // Reset date min value
                const dateInput = document.getElementById('preferred_date');
                if (dateInput) {
                    const today = new Date().toISOString().split('T')[0];
                    dateInput.min = today;
                    dateInput.value = '';
                }
                
                // Reset time
                const timeInput = document.getElementById('preferred_time');
                if (timeInput) {
                    timeInput.value = '';
                    timeInput.min = '00:00';
                }
            }
        });
    }

    // Reset edit form when modal is closed
    const editModal = document.getElementById('editAppointmentModal');
    if (editModal) {
        editModal.addEventListener('hidden.bs.modal', function () {
            const editForm = document.getElementById('editAppointmentForm');
            if (editForm) {
                editForm.reset();
                document.getElementById('editModalBody').innerHTML = '';
            }
        });
    }
});

function openEditModal(appointmentId) {
    // Show loading in modal body
    const modalBody = document.getElementById('editModalBody');
    modalBody.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-warning" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading appointment details...</p>
        </div>
    `;
    
    // Fetch appointment data
    fetch(`/appointments/${appointmentId}/edit`)
        .then(response => response.text())
        .then(html => {
            // Extract just the form from the response
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const formContent = doc.querySelector('.card-body form') || doc.querySelector('form');
            
            if (formContent) {
                // Update modal body with form content
                modalBody.innerHTML = formContent.innerHTML;
                
                // Update form action
                const editForm = document.getElementById('editAppointmentForm');
                editForm.action = `/appointments/${appointmentId}`;
                
                // Set up date validation for edit form
                setupEditFormValidation();
            } else {
                modalBody.innerHTML = `
                    <div class="alert alert-danger">
                        Failed to load appointment details. Please try again.
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    Error loading appointment details. Please try again.
                </div>
            `;
        });
    
    // Show the modal
    const editModal = new bootstrap.Modal(document.getElementById('editAppointmentModal'));
    editModal.show();
}

function setupEditFormValidation() {
    const editDateInput = document.getElementById('preferred_date');
    const editTimeInput = document.getElementById('preferred_time');
    
    if (editDateInput) {
        const today = new Date().toISOString().split('T')[0];
        editDateInput.min = today;
    }
    
    if (editDateInput && editTimeInput) {
        editDateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            selectedDate.setHours(0, 0, 0, 0);
            
            // If selected date is today, set minimum time to current time
            if (selectedDate.getTime() === today.getTime()) {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = Math.ceil(now.getMinutes() / 30) * 30; // Round up to nearest 30 minutes
                const roundedMinutes = minutes >= 60 ? '00' : String(minutes).padStart(2, '0');
                const minTime = minutes >= 60 ? String(parseInt(hours) + 1).padStart(2, '0') + ':' + roundedMinutes : hours + ':' + roundedMinutes;
                editTimeInput.min = minTime;
            } else {
                editTimeInput.min = '00:00';
            }
        });
    }
}

function showError(message) {
    // Create error alert
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed';
    alertDiv.style.top = '20px';
    alertDiv.style.right = '20px';
    alertDiv.style.zIndex = '9999';
    alertDiv.innerHTML = `
        <i class="fas fa-exclamation-circle me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

function showSuccess(message) {
    // Create success alert
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
    alertDiv.style.top = '20px';
    alertDiv.style.right = '20px';
    alertDiv.style.zIndex = '9999';
    alertDiv.innerHTML = `
        <i class="fas fa-check-circle me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>
@endsection