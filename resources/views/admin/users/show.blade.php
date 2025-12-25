@extends('layouts.admin')

@section('title', 'User Details - User Management')

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
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1.5rem;
    }

    /* Back Link */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 0.9375rem;
        font-weight: 500;
        margin-bottom: 1.5rem;
        transition: var(--transition);
        animation: slideDown 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .back-link:hover {
        color: var(--accent);
        transform: translateX(-4px);
    }

    .back-link i {
        font-size: 0.875rem;
    }

    /* Page Header */
    .page-header {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        animation: slideDown 0.5s cubic-bezier(0.16, 1, 0.3, 1);
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

    /* User Profile Card */
    .user-profile {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        padding: 2.5rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.1s backwards;
    }

    .profile-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--border-color);
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 700;
        color: white;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-lg);
        overflow: hidden;
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-info h2 {
        font-size: 1.875rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.75rem 0;
        letter-spacing: -0.025em;
    }

    .profile-info p {
        font-size: 1rem;
        color: var(--text-secondary);
        margin: 0 0 1rem 0;
    }

    .profile-badges {
        display: flex;
        gap: 0.625rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.8125rem;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
        letter-spacing: 0.025em;
    }

    .role-admin {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .role-vet {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .role-user {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    .status-active {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .status-inactive {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .verified {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .not-verified {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    /* Profile Meta */
    .profile-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1rem;
    }

    .meta-item {
        background: var(--bg-secondary);
        padding: 1.25rem;
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        transition: var(--transition);
    }

    .meta-item:hover {
        background: var(--bg-primary);
        box-shadow: var(--shadow);
    }

    .meta-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }

    .meta-value {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    /* Certificate Section */
    .certificate-section {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.2s backwards;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 1.5rem 0;
        letter-spacing: -0.025em;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title i {
        color: var(--accent);
        font-size: 1.125rem;
    }

    .license-image {
        width: 100%;
        max-width: 600px;
        height: auto;
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-md);
        margin-top: 1rem;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        text-align: center;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        transition: var(--transition);
        animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) backwards;
        position: relative;
        overflow: hidden;
    }

    .stat-card:nth-child(1) { animation-delay: 0.3s; }
    .stat-card:nth-child(2) { animation-delay: 0.35s; }
    .stat-card:nth-child(3) { animation-delay: 0.4s; }

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

    .stat-card.appointments { --card-accent: var(--accent); }
    .stat-card.adoptions { --card-accent: var(--success); }
    .stat-card.lost-found { --card-accent: var(--warning); }

    .stat-number {
        font-size: 2.5rem;
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

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        flex-wrap: wrap;
        animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.5s backwards;
        margin-top: 1rem;
    }

    .action-buttons form {
        margin: 0;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 0.9375rem;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: var(--transition);
    }

    .btn i {
        font-size: 0.875rem;
    }

    .btn-primary {
        background: var(--accent);
        color: white;
    }

    .btn-primary:hover {
        background: var(--accent-light);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-secondary {
        background: var(--text-muted);
        color: white;
    }

    .btn-secondary:hover {
        background: var(--text-secondary);
        transform: translateY(-2px);
    }

    .btn-success {
        background: var(--success);
        color: white;
    }

    .btn-success:hover {
        background: #0d9668;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-warning {
        background: var(--warning);
        color: white;
    }

    .btn-warning:hover {
        background: #d97706;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-danger {
        background: var(--danger);
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
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
    @media (max-width: 1024px) {
        .container-fluid {
            padding: 1.5rem 1.25rem;
        }

        .profile-avatar {
            width: 90px;
            height: 90px;
            font-size: 1.75rem;
        }

        .profile-info h2 {
            font-size: 1.625rem;
        }
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding: 1.25rem 1rem;
        }

        .page-header {
            padding: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .page-subtitle {
            font-size: 0.9375rem;
        }

        .user-profile {
            padding: 2rem 1.5rem;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            font-size: 1.5rem;
        }

        .profile-info h2 {
            font-size: 1.5rem;
        }

        .profile-meta {
            grid-template-columns: repeat(2, 1fr);
        }

        .certificate-section {
            padding: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .action-buttons {
            justify-content: stretch;
        }

        .action-buttons .btn {
            flex: 1;
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

        .user-profile {
            padding: 1.75rem 1.25rem;
        }

        .profile-avatar {
            width: 72px;
            height: 72px;
            font-size: 1.25rem;
        }

        .profile-info h2 {
            font-size: 1.375rem;
        }

        .profile-badges {
            flex-direction: column;
            align-items: center;
        }

        .badge {
            width: 100%;
            max-width: 200px;
            text-align: center;
        }

        .profile-meta {
            grid-template-columns: 1fr;
        }

        .certificate-section {
            padding: 1.25rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-number {
            font-size: 2rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .action-buttons .btn {
            width: 100%;
        }
    }

    /* Smooth Scrolling */
    html {
        scroll-behavior: smooth;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <a href="{{ route('admin.users.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Users</span>
    </a>

    <div class="page-header">
        <h1 class="page-title">User Details</h1>
        <p class="page-subtitle">Complete user information and activity overview</p>
    </div>

    <!-- User Profile -->
    <div class="user-profile">
        <div class="profile-header">
            <div class="profile-avatar" style="background: {{ $user->role === 'admin' ? '#ef4444' : ($user->role === 'vet' ? '#10b981' : '#3b82f6') }};">
                @if($user->profile_picture_path)
                    <img src="{{ $user->profile_picture_url }}" alt="{{ $user->name }}">
                @else
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                @endif
            </div>
            <div class="profile-info">
                <h2>{{ $user->role === 'vet' ? 'Dr. ' : '' }}{{ $user->name }}</h2>
                <p>{{ $user->email }}</p>
                <div class="profile-badges">
                    <span class="badge role-{{ $user->role }}">
                        {{ ucfirst($user->role) }}
                    </span>
                    <span class="badge status-{{ ($user->is_active ?? true) ? 'active' : 'inactive' }}">
                        {{ ($user->is_active ?? true) ? 'Active' : 'Inactive' }}
                    </span>
                    @if($user->role === 'vet')
                        <span class="badge {{ $user->is_verified_vet ? 'verified' : 'not-verified' }}">
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

    <!-- Detailed Statistics -->
    @if($user->role !== 'admin')
    <div class="stats-grid">
        @if($user->role === 'vet')
            <div class="stat-card appointments">
                <div class="stat-number">{{ number_format($stats['appointments_count'] ?? 0) }}</div>
                <div class="stat-label">Appointments</div>
            </div>
        @else
            <div class="stat-card adoptions">
                <div class="stat-number">{{ number_format($stats['adoption_listings_count'] ?? 0) }}</div>
                <div class="stat-label">Adoption Listings</div>
            </div>
            <div class="stat-card appointments">
                <div class="stat-number">{{ number_format($stats['appointments_count'] ?? 0) }}</div>
                <div class="stat-label">Appointments</div>
            </div>
            <div class="stat-card lost-found">
                <div class="stat-number">{{ number_format($stats['lost_found_count'] ?? 0) }}</div>
                <div class="stat-label">Lost & Found</div>
            </div>
        @endif
    </div>
    @endif

    <!-- Veterinarian Certificate Section -->
    @if($user->role === 'vet')
    <div class="certificate-section">
        <h3 class="section-title">
            <i class="fas fa-certificate"></i>
            Veterinarian Certificate
        </h3>
        @if($user->certificate_path)
            <p style="color: var(--text-secondary); margin-bottom: 1rem;">License Certificate:</p>
            <img src="{{ $user->certificate_url }}" alt="Veterinarian License Certificate" class="license-image">
        @else
            <p style="color: var(--text-secondary); margin: 0;">No certificate uploaded.</p>
        @endif
    </div>
    @endif

    <!-- Action Buttons -->
    @if($user->role === 'vet' && !$user->is_verified_vet)
    <div class="action-buttons">
        <form method="POST" action="{{ route('admin.users.verify-vet', $user) }}" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check"></i> Verify Veterinarian
            </button>
        </form>
        <form method="POST" action="{{ route('admin.users.reject-vet', $user) }}" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-warning">
                <i class="fas fa-times"></i> Reject Veterinarian
            </button>
        </form>
    </div>
    @endif
</div>
@endsection