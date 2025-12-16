@extends('layouts.admin')

@section('title', 'User Details - User Management')

@section('styles')
<style>
    .user-details-header {
        background: #e74c3c; padding: 30px; border-radius: 15px; margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    .page-title { 
        font-size: 2.2rem; color: white; margin-bottom: 10px; font-weight: 700; 
    }
    .page-subtitle { font-size: 1.1rem; color: white; opacity: 0.9; }
    .breadcrumb {
        display: flex; align-items: center; gap: 8px; margin-bottom: 20px;
        font-size: 14px; color: #666;
    }
    .breadcrumb a { color: #3498db; text-decoration: none; }
    .breadcrumb a:hover { text-decoration: underline; }

    .user-profile {
        background: #fff; padding: 30px; border-radius: 15px; margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    .profile-header {
        display: flex; align-items: center; gap: 20px; margin-bottom: 20px;
    }
    
    /* Make vet and admin profile layout vertical (stacked) */
    .profile-header.vet-header,
    .profile-header.user-header,
    .profile-header.admin-header {
        flex-direction: column;
        text-align: center;
        align-items: center;
    }
    
    .profile-header.vet-header .profile-avatar,
    .profile-header.user-header .profile-avatar,
    .profile-header.admin-header .profile-avatar {
        margin-right: 0;
        margin-bottom: 15px;
    }
    
    .profile-header.vet-header .profile-info,
    .profile-header.user-header .profile-info,
    .profile-header.admin-header .profile-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
    }
    
    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }
        
        .profile-header.vet-header,
        .profile-header.user-header,
        .profile-header.admin-header {
            flex-direction: column;
            text-align: center;
            align-items: center;
        }
        
        .profile-header.vet-header .profile-info,
        .profile-header.user-header .profile-info,
        .profile-header.admin-header .profile-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }
        
        .profile-avatar {
            margin-right: 0;
        }
    }
    .profile-avatar {
        width: 80px !important; height: 80px !important; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; font-weight: bold; color: white;
        /* Updated to use admin red color for consistency */
        background: #e74c3c; margin-right: 20px;
        flex-shrink: 0; /* Prevent shrinking */
        transition: none; /* Remove any hover effects */
        transform: none !important; /* Prevent any transforms */
        transform-origin: center !important;
    }
    .profile-avatar img {
        width: 100%; height: 100%; border-radius: 50%; object-fit: cover;
        transition: none; /* Remove any hover effects */
    }
    .profile-info h2 { margin: 0; color: #2c3e50; }
    .profile-info p { margin: 5px 0; color: #666; }
    
    .profile-meta {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px; margin-bottom: 20px;
    }
    
    @media (max-width: 768px) {
        .profile-meta {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
    }
    
    @media (max-width: 576px) {
        .profile-meta {
            grid-template-columns: 1fr;
        }
    }
    .meta-item {
        background: #f8f9fa; padding: 15px; border-radius: 8px;
        border-left: 4px solid #3498db;
    }
    .meta-label { font-size: 12px; color: #666; text-transform: uppercase; font-weight: 600; }
    .meta-value { font-size: 16px; color: #2c3e50; font-weight: 600; margin-top: 5px; }
    
    .role-badge {
        padding: 6px 16px; border-radius: 20px; font-size: 14px;
        font-weight: 600; text-transform: uppercase;
    }
    .role-admin { background: #ffebee; color: #c62828; }
    .role-vet { background: #e8f5e8; color: #2e7d32; }
    .role-user { background: #fff3e0; color: #ef6c00; }
    
    .status-badge {
        padding: 6px 16px; border-radius: 20px; font-size: 14px;
        font-weight: 600;
    }
    .status-active { background: #e8f5e8; color: #2e7d32; }
    .status-inactive { background: #ffebee; color: #c62828; }
    .status-pending { background: #fff8e1; color: #ff8f00; }
    
    .verification-badge {
        padding: 6px 16px; border-radius: 20px; font-size: 14px;
        font-weight: 600;
    }
    .verified { background: #e8f5e8; color: #2e7d32; }
    .not-verified { background: #fff8e1; color: #ff8f00; }
    
    .vet-header {
        border-left-color: #27ae60;
    }
    
    .user-header {
        border-left-color: #3498db;
    }
    
    .vet-stats-card {
        border-left-color: #27ae60;
    }
    
    .user-stats-card {
        border-left-color: #3498db;
    }
    
    .certificate-section {
        background: #fff; padding: 20px; border-radius: 15px; margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border-left: 4px solid #27ae60;
    }
    
    .certificate-section.user-certificate {
        border-left-color: #3498db;
    }
    
    .certificate-section h3 {
        margin-top: 0; color: #2c3e50;
    }
    
    @media (max-width: 768px) {
        .certificate-section {
            padding: 15px;
        }
        
        .certificate-section h3 {
            font-size: 1.3rem;
        }
    }

    .stats-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px; margin-bottom: 30px;
    }
    
    @media (max-width: 768px) {
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
    }
    
    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
    .stat-card {
        background: #fff; padding: 25px; border-radius: 12px;
        text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border-left: 4px solid #3498db;
    }
    .stat-card.pets { border-left-color: #27ae60; }
    .stat-card.adoptions { border-left-color: #9b59b6; }
    .stat-card.appointments { border-left-color: #3498db; }

    .stat-card.lost-found { border-left-color: #f39c12; }
    
    .stat-number {
        font-size: 2.5rem; font-weight: 700; margin-bottom: 10px;
        color: #2c3e50;
    }
    .stat-label {
        font-size: 1rem; color: #666; text-transform: uppercase;
        font-weight: 600;
    }

    .activity-section {
        background: #fff; padding: 30px; border-radius: 15px; margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    .section-title {
        font-size: 1.5rem; color: #2c3e50; margin-bottom: 20px;
        font-weight: 600; display: flex; align-items: center; gap: 10px;
    }
    
    .activity-tabs {
        display: flex; gap: 0; margin-bottom: 20px; border-radius: 8px;
        overflow: hidden; background: #f8f9fa;
    }
    
    @media (max-width: 768px) {
        .activity-tabs {
            flex-wrap: wrap;
        }
        
        .tab-btn {
            flex: 1;
            min-width: auto;
            padding: 10px 15px;
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 576px) {
        .activity-tabs {
            flex-direction: column;
        }
    }
    .tab-btn {
        padding: 12px 20px; background: transparent; border: none;
        cursor: pointer; font-weight: 600; color: #666;
        transition: all 0.3s;
    }
    .tab-btn.active {
        background: #3498db; color: white;
    }
    
    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
    }
    
    .activity-list {
        max-height: 400px; overflow-y: auto;
    }
    .activity-item {
        padding: 15px; border: 1px solid #e1e8ed; border-radius: 8px;
        margin-bottom: 10px; background: #f8f9fa;
    }
    .activity-item h4 { margin: 0 0 8px 0; color: #2c3e50; font-size: 14px; }
    .activity-item p { margin: 0; color: #666; font-size: 12px; }
    .activity-date { font-size: 11px; color: #999; margin-top: 5px; }
    
    .no-activity {
        text-align: center; padding: 40px; color: #666;
    }
    .no-activity h3 { margin-bottom: 10px; }
    
    .action-buttons {
        display: flex; gap: 10px; justify-content: flex-end;
        margin-top: 20px;
    }
    
    /* Mobile adjustments for pet parent action buttons */
    @media (max-width: 768px) {
        .action-buttons {
            flex-wrap: wrap;
        }
        
        .btn {
            flex: 1;
            min-width: 120px;
        }
    }
    
    @media (max-width: 576px) {
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons.pet-parent-actions,
        .action-buttons.vet-actions,
        .action-buttons.admin-actions {
            flex-direction: row;
            justify-content: space-between;
        }
        
        .action-buttons.pet-parent-actions .btn-secondary,
        .action-buttons.vet-actions .btn-secondary,
        .action-buttons.admin-actions .btn-secondary {
            order: -1; /* Move back button to the left */
            width: auto;
            flex: 0 0 auto;
            min-width: 70px; /* Reduced width for mobile */
            max-width: fit-content;
        }
        
        .btn {
            width: 100%;
        }
        
        .action-buttons.pet-parent-actions .btn,
        .action-buttons.admin-actions .btn {
            width: auto;
            flex: 0 0 auto;
            min-width: 100px; /* Reduced width for mobile */
            max-width: fit-content;
        }
        
        .action-buttons.pet-parent-actions .btn-danger,
        .action-buttons.vet-actions .btn-danger,
        .action-buttons.admin-actions .btn-danger {
            flex: 0 0 auto;
            min-width: 80px;
            max-width: fit-content;
        }
    }
    .btn {
        padding: 12px 24px; border: none; border-radius: 8px;
        font-weight: 600; cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 8px;
        transition: background-color 0.3s;
    }
    .btn-primary { background: #3498db; color: white; }
    .btn-primary:hover { background: #2980b9; }
    .btn-secondary { background: #95a5a6; color: white; }
    .btn-secondary:hover { background: #7f8c8d; }
    .btn-warning { background: #f39c12; color: white; }
    .btn-warning:hover { background: #e67e22; }
    .btn-danger { background: #e74c3c; color: white; }
    .btn-danger:hover { background: #c0392b; }
    .btn-success { background: #27ae60; color: white; }
    .btn-success:hover { background: #229954; }
    
    .pet-card {
        background: #fff; border: 1px solid #e1e8ed; border-radius: 8px;
        padding: 15px; margin-bottom: 10px;
    }
    .pet-card h4 { margin: 0 0 8px 0; color: #2c3e50; }
    .pet-details { display: flex; gap: 15px; font-size: 12px; color: #666; }
    .pet-details span { display: flex; align-items: center; gap: 4px; }
    
    @media (max-width: 768px) {
        .pet-card {
            padding: 12px;
        }
        
        .pet-details {
            flex-direction: column;
            gap: 8px;
        }
        
        .pet-details span {
            font-size: 11px;
        }
    }
    
    @media (max-width: 576px) {
        .pet-card {
            padding: 10px;
        }
        
        .pet-card h4 {
            font-size: 1rem;
        }
    }
    
    .license-image {
        max-width: 100%;
        max-height: 300px;
        margin-top: 10px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    @media (max-width: 768px) {
        .license-image {
            max-height: 200px;
        }
    }
    
    @media (max-width: 576px) {
        .license-image {
            max-height: 150px;
        }
    }
    
    .certificate-section {
        background: #fff; padding: 20px; border-radius: 15px; margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border-left: 4px solid #27ae60;
    }
    .certificate-section h3 {
        margin-top: 0; color: #2c3e50;
    }
    
    @media (max-width: 768px) {
        .certificate-section {
            padding: 15px;
        }
        
        .certificate-section h3 {
            font-size: 1.3rem;
        }
    }
</style>
@endsection

@section('content')
<div class="user-details-header">
    <h1 class="page-title">👤 User Details</h1>
    <p class="page-subtitle">Complete user information and activity overview</p>
</div>

<!-- User Profile -->
<div class="user-profile">
    <div class="profile-header {{ $user->role === 'vet' ? 'vet-header' : ($user->role === 'user' ? 'user-header' : 'admin-header') }}">
        <div class="profile-avatar" style="background: {{ (isset($user) && isset($user->role) && strtolower($user->role) === 'admin') ? '#e74c3c' : ($user->role === 'vet' ? '#27ae60' : '#3498db') }};">
            @if($user->profile_picture_path)
                <img src="{{ $user->profile_picture_url }}" alt="{{ $user->name }}">
            @else
                @if(isset($user) && isset($user->role) && strtolower($user->role) === 'admin')
                    AD
                @elseif($user->role === 'vet')
                    VT
                @else
                    {{ isset($user) && isset($user->name) ? strtoupper(substr($user->name, 0, 2)) : '??' }}
                @endif
            @endif
        </div>
        <div class="profile-info">
            <h2>{{ $user->role === 'vet' ? 'Dr. ' : '' }}{{ $user->name }}</h2>
            <p>{{ $user->email }}</p>
            <div style="display: flex; gap: 10px; margin-top: 10px; flex-wrap: wrap; justify-content: center;">
                <span class="role-badge role-{{ $user->role }}">
                    {{ ucfirst($user->role) }}
                </span>
                <span class="status-badge status-{{ ($user->is_active ?? true) ? 'active' : 'inactive' }}">
                    {{ ($user->is_active ?? true) ? 'Active' : 'Inactive' }}
                </span>
                @if($user->role === 'vet')
                    <span class="verification-badge {{ $user->is_verified_vet ? 'verified' : 'not-verified' }}">
                        {{ $user->is_verified_vet ? 'Verified' : 'Pending Verification' }}
                    </span>
                @endif
            </div>
        </div>
    </div>
    
    <div class="profile-meta">
        <div class="meta-item">
            <div class="meta-label">Account Created</div>
            <div class="meta-value">{{ $user->created_at->format('M d, Y') }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Last Updated</div>
            <div class="meta-value">{{ $user->updated_at->format('M d, Y') }}</div>
        </div>
        @if($user->phone)
        <div class="meta-item">
            <div class="meta-label">Phone Number</div>
            <div class="meta-value">{{ $user->phone }}</div>
        </div>
        @endif
        @if($user->address)
        <div class="meta-item">
            <div class="meta-label">Address</div>
            <div class="meta-value">{{ $user->address }}</div>
        </div>
        @endif
    </div>
</div>

<!-- Veterinarian Certificate Section -->
@if($user->role === 'vet')
<div class="certificate-section">
    <h3>📋 Veterinarian Certificate</h3>
    @if($user->certificate_path)
        <p>License Certificate:</p>
        <img src="{{ $user->certificate_url }}" alt="Veterinarian License Certificate" class="license-image">
    @else
        <p>No certificate uploaded.</p>
    @endif
</div>
@endif

<!-- Detailed Statistics -->
<div class="stats-grid">
    @if($user->role !== 'admin')
        @if($user->role === 'vet')
            <div class="stat-card appointments vet-stats-card">
                <div class="stat-number">{{ number_format($stats['appointments_count'] ?? 0) }}</div>
                <div class="stat-label">Appointments</div>
            </div>

        @else
            <div class="stat-card adoptions {{ $user->role === 'user' ? 'user-stats-card' : '' }}">
                <div class="stat-number">{{ number_format($stats['adoption_listings_count'] ?? 0) }}</div>
                <div class="stat-label">Adoption Listings</div>
            </div>
            <div class="stat-card appointments {{ $user->role === 'user' ? 'user-stats-card' : '' }}">
                <div class="stat-number">{{ number_format($stats['appointments_count'] ?? 0) }}</div>
                <div class="stat-label">Appointments</div>
            </div>

            <div class="stat-card lost-found {{ $user->role === 'user' ? 'user-stats-card' : '' }}">
                <div class="stat-number">{{ number_format($stats['lost_found_count'] ?? 0) }}</div>
                <div class="stat-label">Lost & Found</div>
            </div>
        @endif
    @endif
</div>



<!-- Action Buttons -->
<div class="action-buttons {{ $user->role === 'user' ? 'pet-parent-actions' : ($user->role === 'vet' ? 'vet-actions' : 'admin-actions') }}">
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        ← Back to Users
    </a>
    
    @if($user->role === 'vet')
        @if(!$user->is_verified_vet)
            <form method="POST" action="{{ route('admin.users.verify-vet', $user) }}" 
                  style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-success">
                    ✅ Verify Veterinarian
                </button>
            </form>
            <form method="POST" action="{{ route('admin.users.reject-vet', $user) }}" 
                  style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-warning">
                    ❌ Reject Veterinarian
                </button>
            </form>
        @endif
    @endif
    
</div>


@endsection