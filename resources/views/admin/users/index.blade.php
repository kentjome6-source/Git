@extends('layouts.admin')

@section('title', 'User Management')

@section('styles')
<style>
    .user-management-header {
        background: #e74c3c; padding: 30px; border-radius: 15px; margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    .page-title { font-size: 2.5rem; color: white; margin-bottom: 10px; font-weight: 700; }
    .page-subtitle { font-size: 1.1rem; color: white; opacity: 0.9; }
    
    .stats-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px; margin-bottom: 30px;
    }
    .stat-card {
        background: #fff; padding: 20px; border-radius: 12px;
        text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-left: 4px solid #3498db;
    }
    .stat-card.admin { border-left-color: #e74c3c; }
    .stat-card.vet { border-left-color: #27ae60; }
    .stat-card.user { border-left-color: #f39c12; }
    .stat-card.new { border-left-color: #9b59b6; }
    
    .stat-number {
        font-size: 2rem; font-weight: 700; margin-bottom: 8px;
        color: #2c3e50;
    }
    .stat-label {
        font-size: 0.9rem; color: #666; text-transform: uppercase;
        font-weight: 600;
    }

    .filters-section {
        background: #fff; padding: 20px; border-radius: 12px; margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .filters-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px; align-items: end;
    }
    .filter-group label {
        display: block; margin-bottom: 5px; font-weight: 600; color: #555;
    }
    .filter-input {
        width: 100%; padding: 8px 12px; border: 1px solid #ddd;
        border-radius: 6px; font-size: 14px;
    }
    .filter-btn {
        padding: 10px 20px; background: #3498db; color: white;
        border: none; border-radius: 6px; cursor: pointer;
        font-weight: 600;
    }
    .filter-btn:hover { background: #2980b9; }
    .clear-btn {
        background: #95a5a6; margin-left: 10px;
    }
    .clear-btn:hover { background: #7f8c8d; }

    .actions-bar {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 20px;
    }
    .bulk-actions {
        display: flex; gap: 10px; align-items: center;
    }
    .bulk-select {
        padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px;
    }
    .bulk-btn {
        padding: 8px 16px; background: #e74c3c; color: white;
        border: none; border-radius: 6px; cursor: pointer;
    }
    .bulk-btn:hover { background: #c0392b; }

    .users-table {
        background: #fff; border-radius: 12px; overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .users-table table {
        width: 100%; border-collapse: collapse;
    }
    .users-table th {
        background: #f8f9fa; padding: 15px; text-align: left;
        font-weight: 600; color: #2c3e50; border-bottom: 1px solid #dee2e6;
    }
    .users-table td {
        padding: 15px; border-bottom: 1px solid #dee2e6;
    }
    .users-table tr:hover {
        background: #f8f9fa;
    }

    .user-avatar {
        width: 40px !important; height: 40px !important; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 600; color: white; margin-right: 12px;
        background: #3498db;
        flex-shrink: 0; /* Prevent shrinking */
        transition: none; /* Remove any hover effects */
        transform: none !important; /* Prevent any transforms */
        transform-origin: center !important;
    }
    .user-avatar img {
        width: 100%; height: 100%; object-fit: cover;
        transition: none; /* Remove any hover effects */
    }
    .user-info {
        display: flex; align-items: center;
    }
    .user-details h4 {
        margin: 0; font-size: 14px; font-weight: 600;
    }
    .user-details p {
        margin: 2px 0 0 0; font-size: 12px; color: #666;
    }

    .role-badge {
        padding: 4px 12px; border-radius: 20px; font-size: 12px;
        font-weight: 600; text-transform: uppercase;
    }
    .role-admin { background: #ffebee; color: #c62828; }
    .role-vet { background: #e8f5e8; color: #2e7d32; }
    .role-user { background: #fff3e0; color: #ef6c00; }
    .role-pending { background: #fff8e1; color: #ff8f00; }

    .action-buttons {
        display: flex; gap: 8px;
    }
    .action-btn {
        padding: 6px 12px; border: none; border-radius: 4px;
        cursor: pointer; font-size: 12px; text-decoration: none;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .btn-view { background: #3498db; color: white; }
    .btn-delete { background: #e74c3c; color: white; }
    .btn-verify { background: #27ae60; color: white; }
    .btn-reject { background: #e74c3c; color: white; }

    .pagination-wrapper {
        margin-top: 20px; text-align: center;
    }

    .alert {
        padding: 12px 20px; border-radius: 6px; margin-bottom: 20px;
    }
    .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    
    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .user-management-header {
            padding: 20px 15px;
        }
        
        .page-title {
            font-size: 2rem;
            text-align: center;
        }
        
        .page-subtitle {
            font-size: 1rem;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .user-management-header {
                background: #e74c3c;
            }
            
            .page-title {
                color: white;
            }
            
            .page-subtitle {
                color: white;
            }
        }
        
        .stats-grid {
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .stat-card {
            padding: 15px;
        }
        
        .stat-number {
            font-size: 1.5rem;
        }
        
        .stat-label {
            font-size: 0.8rem;
        }
        
        .filters-section {
            padding: 15px;
        }
        
        .filters-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .actions-bar {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        /* Hide desktop table on mobile */
        .users-table {
            display: none;
        }
        
        /* Show swipeable card layout on mobile */
        .users-mobile-container {
            display: block;
        }
        
        .user-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #3498db;
        }
        
        .user-card.vet-card {
            border-left-color: #27ae60;
        }
        
        .user-card.admin-card {
            border-left-color: #e74c3c;
        }
        
        .user-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .user-card-header.vet-header {
            align-items: flex-start;
        }
        
        .user-card-avatar {
            width: 50px !important;
            height: 50px !important;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
            margin-right: 12px;
            background: #3498db;
            flex-shrink: 0; /* Prevent shrinking */
            transition: none; /* Remove any hover effects */
            transform: none !important; /* Prevent any transforms */
            transform-origin: center !important;
        }
        
        .user-card-info {
            flex: 1;
        }
        
        .user-card-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0 0 3px 0;
        }
        
        .user-card-email {
            font-size: 0.85rem;
            color: #666;
            margin: 0;
        }
        
        .user-card-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .user-card-detail {
            text-align: center;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .user-card-detail.vet-verification {
            background: #e8f5e8;
            border: 1px solid #c8e6c9;
        }
        
        .user-card-detail.vet-verification.pending {
            background: #fff8e1;
            border: 1px solid #ffecb3;
        }
        
        .user-card-detail-label {
            font-size: 0.75rem;
            color: #666;
            margin-bottom: 3px;
        }
        
        .user-card-detail-value {
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .user-card-role {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .user-card-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
        
        /* Vet-specific mobile improvements */
        .user-card-header.vet-header .user-card-info {
            display: flex;
            flex-direction: column;
        }
        
        .vet-status-badge {
            font-size: 0.7rem;
            padding: 3px 8px;
            align-self: flex-start;
        }
        
        .user-card-btn {
            flex: 1;
            padding: 8px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85rem;
            text-decoration: none;
            text-align: center;
            min-width: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .user-card-btn.vet-action {
            background: #27ae60;
            color: white;
        }
        
        .btn-view-mobile {
            background: #3498db;
            color: white;
        }
        
        .btn-view-mobile.vet-view {
            background: #27ae60;
        }
        
        .btn-delete-mobile {
            background: #e74c3c;
            color: white;
        }
        
        .vet-status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 5px;
        }
        
        .vet-status-badge.verified {
            background: #e8f5e8;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
        
        .vet-status-badge.pending {
            background: #fff8e1;
            color: #ff8f00;
            border: 1px solid #ffecb3;
        }
        
        .pagination-wrapper {
            margin-top: 15px;
        }
    }
    
    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .user-card-details {
            grid-template-columns: 1fr;
        }
        
        .user-card-actions {
            flex-direction: column;
        }
        
        .user-card-btn {
            width: 100%;
            margin-bottom: 5px;
        }
        
        .user-card-name {
            font-size: 1.1rem;
        }
        
        .user-card-email {
            font-size: 0.9rem;
        }
    }
    
    /* Desktop view - hide mobile cards */
    @media (min-width: 769px) {
        .users-mobile-container {
            display: none !important;
        }
        .users-table {
            display: block !important;
        }
    }
</style>
@endsection

@section('content')
<div class="user-management-header">
    <h1 class="page-title">👥 User Management</h1>
    <p class="page-subtitle">Manage legitimate registered users and admin accounts only</p>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number">{{ number_format($stats['total_users']) }}</div>
        <div class="stat-label">Total Users</div>
    </div>
    <div class="stat-card admin">
        <div class="stat-number">{{ number_format($stats['admin_count']) }}</div>
        <div class="stat-label">Administrators</div>
    </div>
    <div class="stat-card vet">
        <div class="stat-number">{{ number_format($stats['vet_count']) }}</div>
        <div class="stat-label">Veterinarians</div>
    </div>
    <div class="stat-card user">
        <div class="stat-number">{{ number_format($stats['user_count']) }}</div>
        <div class="stat-label">Pet Owners</div>
    </div>
    <div class="stat-card new">
        <div class="stat-number">{{ number_format($stats['new_this_month']) }}</div>
        <div class="stat-label">New This Month</div>
    </div>
</div>

<!-- Alerts -->
@if(session('success'))
    <div class="alert alert-success">
        <strong>Success!</strong> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error">
        <strong>Error!</strong> {{ session('error') }}
    </div>
@endif

<!-- Filters Section -->
<div class="filters-section">
    <form method="GET" action="{{ route('admin.users.index') }}">
        <div class="filters-grid">
            <div class="filter-group">
                <label for="search">Search Users</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                       placeholder="Name or email..." class="filter-input">
            </div>
            <div class="filter-group">
                <label for="role">Role</label>
                <select id="role" name="role" class="filter-input">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="vet" {{ request('role') == 'vet' ? 'selected' : '' }}>Veterinarian</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="date_from">From Date</label>
                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="filter-input">
            </div>
            <div class="filter-group">
                <label for="date_to">To Date</label>
                <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="filter-input">
            </div>
            <div class="filter-group">
                <button type="submit" class="filter-btn">🔍 Filter</button>
                <a href="{{ route('admin.users.index') }}" class="filter-btn clear-btn">Clear</a>
            </div>
        </div>
    </form>
</div>

<!-- Actions Bar -->
<div class="actions-bar">
    <div class="bulk-actions">
        <select id="bulk-action" class="bulk-select">
            <option value="">Bulk Actions</option>
            <option value="delete">Delete Selected</option>
        </select>
        <button type="button" id="apply-bulk" class="bulk-btn">Apply</button>
    </div>
    <!-- Removed Add New User button as requested -->
</div>

<!-- Users Table (Desktop) -->
<div class="users-table">
    <form id="bulk-form" method="POST" action="{{ route('admin.users.bulk-action') }}">
        @csrf
        <input type="hidden" name="action" id="bulk-action-input">
        
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>User</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Pets</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        @if($user->role !== 'vet')
                        <input type="checkbox" name="users[]" value="{{ $user->id }}" class="user-checkbox">
                        @endif
                    </td>
                    <td>
                        <div class="user-info">
                            <div class="user-avatar" style="background: {{ (isset($user) && isset($user->role) && strtolower($user->role) === 'admin') ? '#e74c3c' : '#3498db' }};">
                                @if($user->profile_picture_path)
                                    <img src="{{ asset('storage/' . $user->profile_picture_path) }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                @else
                                    @if(isset($user) && isset($user->role) && strtolower($user->role) === 'admin')
                                        AD
                                    @else
                                        {{ isset($user) && isset($user->name) ? strtoupper(substr($user->name, 0, 2)) : '??' }}
                                    @endif
                                @endif
                            </div>
                            <div class="user-details">
                                <h4>{{ $user->name }}</h4>
                                <p>{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="role-badge role-{{ $user->role }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>
                        @if($user->role === 'vet')
                            @if($user->is_verified_vet)
                                <span class="role-badge role-pending" style="background: #e8f5e8; color: #2e7d32;">
                                    Verified
                                </span>
                            @else
                                <span class="role-badge role-pending">
                                    Pending
                                </span>
                            @endif
                        @else
                            <span class="role-badge role-pending" style="background: #e3f2fd; color: #1565c0;">
                                N/A
                            </span>
                        @endif
                    </td>
                    <td>{{ $user->pets_count ?? 0 }} pets</td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.users.show', $user) }}" class="action-btn btn-view">
                                👁️ View
                            </a>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                                      style="display: inline;" 
                                      onsubmit="return confirm('Are you sure you want to delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete">
                                        🗑️ Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #666;">
                        <h3>No users found</h3>
                        <p>Try adjusting your search filters or add some users.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </form>
</div>

<!-- Users Mobile Cards (Mobile) -->
<div class="users-mobile-container">
    @forelse($users as $user)
    <div class="user-card {{ $user->role === 'vet' ? 'vet-card' : ($user->role === 'admin' ? 'admin-card' : '') }}">
        <div class="user-card-header {{ $user->role === 'vet' ? 'vet-header' : '' }}">
            <div class="user-card-avatar" style="background: {{ (isset($user) && isset($user->role) && strtolower($user->role) === 'admin') ? '#e74c3c' : ($user->role === 'vet' ? '#27ae60' : '#3498db') }};">
                @if($user->profile_picture_path)
                    <img src="{{ asset('storage/' . $user->profile_picture_path) }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
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
            <div class="user-card-info">
                <h4 class="user-card-name">{{ $user->role === 'vet' ? 'Dr. ' : '' }}{{ $user->name }}</h4>
                <p class="user-card-email">{{ $user->email }}</p>
                @if($user->role === 'vet')
                    @if(!$user->is_verified_vet)
                        <span class="vet-status-badge pending">
                            Pending Verification
                        </span>
                    @endif
                @endif
            </div>
        </div>
        
        <div class="user-card-details">
            <div class="user-card-detail">
                <div class="user-card-detail-label">Role</div>
                <div class="user-card-detail-value">
                    <span class="user-card-role role-{{ $user->role }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>
            
            @if($user->role === 'vet')
                <div class="user-card-detail">
                    <div class="user-card-detail-label">Status</div>
                    <div class="user-card-detail-value">
                        <span class="user-card-role" style="background: #e8f5e8; color: #2e7d32;">
                            {{ ($user->is_active ?? true) ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            @else
                <div class="user-card-detail">
                    <div class="user-card-detail-label">Status</div>
                    <div class="user-card-detail-value">
                        <span class="user-card-role" style="background: #e3f2fd; color: #1565c0;">
                            {{ ($user->is_active ?? true) ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            @endif
            
            <div class="user-card-detail">
                <div class="user-card-detail-label">Pets</div>
                <div class="user-card-detail-value">{{ $user->pets_count ?? 0 }} pets</div>
            </div>
            
            <div class="user-card-detail">
                <div class="user-card-detail-label">Joined</div>
                <div class="user-card-detail-value">{{ $user->created_at->format('M d, Y') }}</div>
            </div>
        </div>
        
        <div class="user-card-actions">
            <a href="{{ route('admin.users.show', $user) }}" class="user-card-btn btn-view-mobile {{ $user->role === 'vet' ? 'vet-view' : '' }}">
                👁️ View Details
            </a>
            @if($user->id !== auth()->id())
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                      style="display: inline;" 
                      onsubmit="return confirm('Are you sure you want to delete this user?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="user-card-btn btn-delete-mobile">
                        🗑️ Delete
                    </button>
                </form>
            @endif
            @if($user->role === 'vet' && !$user->is_verified_vet)
                <form method="POST" action="{{ route('admin.users.verify-vet', $user) }}" 
                      style="display: inline;" 
                      onsubmit="return confirm('Are you sure you want to verify this veterinarian?')">
                    @csrf
                    <button type="submit" class="user-card-btn vet-action">
                        ✅ Verify Vet
                    </button>
                </form>
            @endif
        </div>
    </div>
    @empty
    <div class="user-card">
        <div style="text-align: center; padding: 40px; color: #666;">
            <h3>No users found</h3>
            <p>Try adjusting your search filters or add some users.</p>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($users->hasPages())
<div class="pagination-wrapper">
    {{ $users->appends(request()->query())->links() }}
</div>
@endif

<!-- JavaScript for bulk actions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox functionality
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    // Bulk action handler
    document.getElementById('apply-bulk').addEventListener('click', function() {
        const action = document.getElementById('bulk-action').value;
        if (!action) {
            alert('Please select a bulk action.');
            return;
        }
        
        const selectedUsers = document.querySelectorAll('.user-checkbox:checked');
        if (selectedUsers.length === 0) {
            alert('Please select at least one user.');
            return;
        }
        
        const actionText = action === 'delete' ? 'delete' : action;
        if (confirm(`Are you sure you want to ${actionText} ${selectedUsers.length} selected user(s)?`)) {
            document.getElementById('bulk-action-input').value = action;
            document.getElementById('bulk-form').submit();
        }
    });
});
</script>
@endsection