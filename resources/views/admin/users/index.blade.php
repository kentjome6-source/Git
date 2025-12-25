@extends('layouts.admin')

@section('title', 'User Management')

@section('styles')
<style>
    :root {
        --primary: #0f172a;
        --primary-light: #1e293b;
        --accent: #3b82f6;
        --accent-light: #60a5fa;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --info: #06b6d4;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --bg-primary: #ffffff;
        --bg-secondary: #f8fafc;
        --border-color: #e2e8f0;
        --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        --radius: 12px;
        --radius-lg: 16px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        background-color: var(--bg-secondary);
        color: var(--text-primary);
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .container-fluid {
        padding: 2rem 1.5rem;
    }

    /* Page Header with Add Vet Button */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        animation: slideDown 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .page-header-content {
        flex: 1;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        letter-spacing: -0.025em;
    }

    .page-subtitle {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .add-vet-btn {
        padding: 0.75rem 1.5rem;
        background: var(--success);
        color: white;
        border: none;
        border-radius: var(--radius);
        cursor: pointer;
        font-weight: 600;
        font-size: 0.875rem;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: var(--shadow);
        margin-left: 1rem;
        white-space: nowrap;
    }

    .add-vet-btn:hover {
        background: #0da271;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        color: white;
        text-decoration: none;
    }

    /* Stats Grid - Removed Admin Card */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        transition: var(--transition);
        animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) backwards;
        position: relative;
        overflow: hidden;
    }

    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.15s; }
    .stat-card:nth-child(3) { animation-delay: 0.2s; }
    .stat-card:nth-child(4) { animation-delay: 0.25s; }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--card-accent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-card.default { --card-accent: var(--accent); }
    .stat-card.vet { --card-accent: var(--success); }
    .stat-card.user { --card-accent: var(--warning); }
    .stat-card.new { --card-accent: var(--info); }

    .stat-number {
        font-size: 2.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Alert */
    .alert {
        padding: 1rem 1.25rem;
        border-radius: var(--radius);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
        animation: slideDown 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        color: #065f46;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .alert-error {
        background: rgba(239, 68, 68, 0.1);
        color: #991b1b;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    /* Filters */
    .filters-section {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.4s backwards;
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        align-items: end;
    }

    .filter-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--text-primary);
    }

    .filter-input {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        font-size: 0.875rem;
        transition: var(--transition);
        background: var(--bg-primary);
        color: var(--text-primary);
    }

    .filter-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .filter-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .filter-btn {
        padding: 0.625rem 1.25rem;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: var(--radius);
        cursor: pointer;
        font-weight: 600;
        font-size: 0.875rem;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .filter-btn:hover {
        background: var(--accent);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        color: white;
    }

    .clear-btn {
        background: var(--text-muted);
    }

    .clear-btn:hover {
        background: var(--text-secondary);
        color: white;
    }

    /* Users Table */
    .users-table {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        overflow: hidden;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.5s backwards;
    }

    .users-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .users-table th {
        background: var(--bg-secondary);
        padding: 1rem 1.25rem;
        text-align: left;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        border-bottom: 1px solid var(--border-color);
    }

    .users-table td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-primary);
    }

    .users-table tbody tr {
        transition: var(--transition);
    }

    .users-table tbody tr:hover {
        background: var(--bg-secondary);
    }

    .users-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* User Info */
    .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .user-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
        color: white;
        flex-shrink: 0;
        overflow: hidden;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-details h4 {
        margin: 0;
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .user-details p {
        margin: 0.25rem 0 0 0;
        font-size: 0.8125rem;
        color: var(--text-secondary);
    }

    /* Badges - Removed admin role */
    .role-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
        letter-spacing: 0.025em;
    }

    .role-vet {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .role-user {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    .role-pending {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .action-btn {
        padding: 0.5rem 0.875rem;
        border: none;
        border-radius: var(--radius);
        cursor: pointer;
        font-size: 0.8125rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        transition: var(--transition);
    }

    .btn-view {
        background: rgba(59, 130, 246, 0.1);
        color: var(--accent);
    }

    .btn-view:hover {
        background: var(--accent);
        color: white;
        transform: translateY(-2px);
    }

    /* Mobile Cards */
    .users-mobile-container {
        display: none;
    }

    .user-card {
        background: var(--bg-primary);
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        margin-bottom: 1rem;
        box-shadow: var(--shadow);
        overflow: hidden;
        animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) backwards;
    }

    .user-card:nth-child(1) { animation-delay: 0.1s; }
    .user-card:nth-child(2) { animation-delay: 0.15s; }
    .user-card:nth-child(3) { animation-delay: 0.2s; }
    .user-card:nth-child(4) { animation-delay: 0.25s; }
    .user-card:nth-child(5) { animation-delay: 0.3s; }

    .user-card-header {
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .user-card-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.125rem;
        color: white;
        flex-shrink: 0;
        overflow: hidden;
    }

    .user-card-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-card-info {
        flex: 1;
    }

    .user-card-name {
        font-size: 1.0625rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 0.25rem 0;
    }

    .user-card-email {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .vet-status-badge {
        display: inline-block;
        padding: 0.25rem 0.625rem;
        border-radius: 50px;
        font-size: 0.6875rem;
        font-weight: 600;
        margin-top: 0.375rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .vet-status-badge.verified {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .vet-status-badge.pending {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    .user-card-body {
        padding: 1.25rem;
    }

    .user-card-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .user-card-detail {
        text-align: center;
        padding: 0.75rem;
        background: var(--bg-secondary);
        border-radius: var(--radius);
    }

    .user-card-detail-label {
        font-size: 0.6875rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.375rem;
    }

    .user-card-detail-value {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .user-card-actions {
        display: flex;
        gap: 0.5rem;
    }

    .user-card-btn {
        flex: 1;
        padding: 0.75rem 1rem;
        border: none;
        border-radius: var(--radius);
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        text-align: center;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.375rem;
    }

    .btn-view-mobile {
        background: rgba(59, 130, 246, 0.1);
        color: var(--accent);
    }

    .btn-view-mobile:hover {
        background: var(--accent);
        color: white;
    }

    .vet-action {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .vet-action:hover {
        background: var(--success);
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-muted);
    }

    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
    }

    .empty-state p {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0;
    }

    /* Animations */
    @keyframes slideDown {
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
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 1024px) {
        .container-fluid {
            padding: 1.5rem 1.25rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding: 1.25rem 1rem;
        }

        .page-header {
            flex-direction: column;
            align-items: stretch;
            padding: 1.5rem;
            gap: 1rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .page-subtitle {
            font-size: 0.9375rem;
        }

        .add-vet-btn {
            margin-left: 0;
            width: 100%;
            justify-content: center;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .stat-card {
            padding: 1.5rem 1.25rem;
        }

        .stat-number {
            font-size: 2rem;
        }

        .filters-section {
            padding: 1.5rem;
        }

        .filters-grid {
            grid-template-columns: 1fr;
            gap: 0.875rem;
        }

        .filter-buttons {
            flex-direction: row;
        }

        /* Hide desktop table on mobile */
        .users-table {
            display: none;
        }

        /* Show mobile cards */
        .users-mobile-container {
            display: block;
        }

        .user-card-details {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding: 1rem 0.875rem;
        }

        .page-header {
            padding: 1.25rem;
        }

        .page-title {
            font-size: 1.375rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-card {
            padding: 1.25rem 1rem;
        }

        .stat-number {
            font-size: 1.75rem;
        }

        .filters-section {
            padding: 1.25rem;
        }

        .user-card-header {
            padding: 1rem;
        }

        .user-card-avatar {
            width: 48px;
            height: 48px;
            font-size: 1rem;
        }

        .user-card-body {
            padding: 1rem;
        }

        .user-card-actions {
            flex-direction: column;
        }
    }

    /* Desktop - ensure table shows */
    @media (min-width: 769px) {
        .users-mobile-container {
            display: none !important;
        }
        .users-table {
            display: block !important;
        }
    }

    /* Smooth Scrolling */
    html {
        scroll-behavior: smooth;
    }

    /* Table empty state colspan fix */
    .users-table .empty-state {
        padding: 2rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header with Add Vet Button -->
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">User Management</h1>
            <p class="page-subtitle">Manage pet owners and veterinarians</p>
        </div>
        <a href="{{ route('admin.users.create-vet') }}" class="add-vet-btn">
            <i class="fas fa-plus"></i> Add New Vet
        </a>
    </div>

    <!-- Stats Cards - Removed Admin Count -->
    <div class="stats-grid">
        <div class="stat-card default">
            <div class="stat-number">{{ number_format($stats['total_users']) }}</div>
            <div class="stat-label">Total Users</div>
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
            <i class="fas fa-check-circle"></i>
            <strong>Success!</strong> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
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
                        <option value="vet" {{ request('role') == 'vet' ? 'selected' : '' }}>Veterinarian</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Pet Owner</option>
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
                    <div class="filter-buttons">
                        <button type="submit" class="filter-btn">Filter</button>
                        <a href="{{ route('admin.users.index') }}" class="filter-btn clear-btn">Clear</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Users Table (Desktop) -->
    <div class="users-table">
        <table>
            <thead>
                <tr>
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
                    @if($user->role !== 'admin') {{-- Skip admin users --}}
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar" style="background: {{ ($user->role === 'vet' ? '#10b981' : '#3b82f6') }};">
                                    @if($user->profile_picture_path)
                                        <img src="{{ $user->profile_picture_url }}" alt="{{ $user->name }}">
                                    @else
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    @endif
                                </div>
                                <div class="user-details">
                                    <h4>{{ $user->role === 'vet' ? 'Dr. ' : '' }}{{ $user->name }}</h4>
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
                                    <span class="role-badge" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                        Verified
                                    </span>
                                @else
                                    <span class="role-badge role-pending">
                                        Pending
                                    </span>
                                @endif
                            @else
                                <span class="role-badge" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                                    Active
                                </span>
                            @endif
                        </td>
                        <td>{{ $user->pets_count ?? 0 }} pets</td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.users.show', $user) }}" class="action-btn btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endif
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <h3>No users found</h3>
                            <p>Try adjusting your search filters</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Users Mobile Cards (Mobile) -->
    <div class="users-mobile-container">
        @forelse($users as $user)
            @if($user->role !== 'admin') {{-- Skip admin users --}}
            <div class="user-card">
                <div class="user-card-header">
                    <div class="user-card-avatar" style="background: {{ $user->role === 'vet' ? '#10b981' : '#3b82f6' }};">
                        @if($user->profile_picture_path)
                            <img src="{{ $user->profile_picture_url }}" alt="{{ $user->name }}">
                        @else
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        @endif
                    </div>
                    <div class="user-card-info">
                        <h4 class="user-card-name">{{ $user->role === 'vet' ? 'Dr. ' : '' }}{{ $user->name }}</h4>
                        <p class="user-card-email">{{ $user->email }}</p>
                        @if($user->role === 'vet' && !$user->is_verified_vet)
                            <span class="vet-status-badge pending">
                                Pending Verification
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="user-card-body">
                    <div class="user-card-details">
                        <div class="user-card-detail">
                            <div class="user-card-detail-label">Role</div>
                            <div class="user-card-detail-value">
                                <span class="role-badge role-{{ $user->role }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="user-card-detail">
                            <div class="user-card-detail-label">Status</div>
                            <div class="user-card-detail-value">
                                @if($user->role === 'vet')
                                    @if($user->is_verified_vet)
                                        <span class="vet-status-badge verified">Verified</span>
                                    @else
                                        <span class="vet-status-badge pending">Pending</span>
                                    @endif
                                @else
                                    <span class="role-badge" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">Active</span>
                                @endif
                            </div>
                        </div>
                        
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
                        <a href="{{ route('admin.users.show', $user) }}" class="user-card-btn btn-view-mobile">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        @if($user->role === 'vet' && !$user->is_verified_vet)
                            <form method="POST" action="{{ route('admin.users.verify-vet', $user) }}" 
                                  style="flex: 1; margin: 0;" 
                                  onsubmit="return confirm('Are you sure you want to verify this veterinarian?')">
                                @csrf
                                <button type="submit" class="user-card-btn vet-action" style="width: 100%;">
                                    <i class="fas fa-check"></i> Verify Vet
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        @empty
        <div class="user-card">
            <div class="empty-state">
                <h3>No users found</h3>
                <p>Try adjusting your search filters</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add any necessary JavaScript here
    document.addEventListener('DOMContentLoaded', function() {
        // Date validation for filters
        const dateFrom = document.getElementById('date_from');
        const dateTo = document.getElementById('date_to');
        
        if (dateFrom && dateTo) {
            dateFrom.addEventListener('change', function() {
                dateTo.min = this.value;
            });
            
            dateTo.addEventListener('change', function() {
                dateFrom.max = this.value;
            });
        }
        
        // Add confirmation for vet verification on mobile
        const verifyForms = document.querySelectorAll('form[action*="verify-vet"]');
        verifyForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to verify this veterinarian?')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush