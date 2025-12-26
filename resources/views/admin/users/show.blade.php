@extends('layouts.admin')

@section('title', 'User Details - User Management')

@section('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --primary: #2563eb;
        --secondary: #64748b;
        --success: #16a34a;
        --danger: #dc2626;
        --warning: #ea580c;
        --info: #0891b2;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --bg-primary: #ffffff;
        --bg-secondary: #f8fafc;
        --bg-tertiary: #f1f5f9;
        --border-light: #e2e8f0;
        --border-medium: #cbd5e1;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        --radius-sm: 6px;
        --radius: 8px;
        --radius-lg: 12px;
        --radius-xl: 16px;
        --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: var(--bg-secondary);
        color: var(--text-primary);
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .container-fluid {
        max-width: 1280px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* Back Navigation */
    .back-navigation {
        margin-bottom: 2rem;
        animation: slideDown 0.4s ease-out;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1rem;
        background: var(--bg-primary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius);
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: var(--transition);
    }

    .back-link:hover {
        color: var(--primary);
        background: var(--bg-tertiary);
        border-color: var(--primary-light);
        transform: translateX(-2px);
    }

    .back-link i {
        font-size: 0.875rem;
        transition: var(--transition);
    }

    .back-link:hover i {
        transform: translateX(-2px);
    }

    /* Page Header */
    .page-header {
        background: var(--bg-primary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-xl);
        padding: 2.5rem 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-sm);
        animation: slideDown 0.5s ease-out;
    }

    .page-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        letter-spacing: -0.025em;
    }

    .page-subtitle {
        font-size: 0.9375rem;
        color: var(--text-secondary);
        margin: 0;
    }

    /* Card Base */
    .card {
        background: var(--bg-primary);
        border-radius: var(--radius-xl);
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow);
        overflow: hidden;
        transition: var(--transition);
    }

    .card:hover {
        box-shadow: var(--shadow-md);
    }

    /* User Profile Card */
    .user-profile-card {
        margin-bottom: 2rem;
        animation: fadeInUp 0.5s ease-out 0.1s backwards;
    }

    .profile-header {
        background: var(--bg-secondary);
        padding: 3rem 2rem 2rem;
        text-align: center;
        border-bottom: 1px solid var(--border-light);
    }

    .profile-avatar-wrapper {
        display: inline-block;
        position: relative;
        margin-bottom: 1.5rem;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        box-shadow: var(--shadow-xl);
        border: 4px solid white;
        overflow: hidden;
        transition: var(--transition);
    }

    .profile-avatar-wrapper:hover .profile-avatar {
        transform: scale(1.05);
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-name {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        letter-spacing: -0.025em;
    }

    .profile-email {
        font-size: 1rem;
        color: var(--text-secondary);
        margin-bottom: 1.25rem;
    }

    .profile-badges {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.8125rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        transition: var(--transition);
    }

    .badge:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .badge-admin {
        background: rgba(220, 38, 38, 0.1);
        color: var(--danger);
        border: 1px solid rgba(220, 38, 38, 0.2);
    }

    .badge-vet {
        background: rgba(22, 163, 74, 0.1);
        color: var(--success);
        border: 1px solid rgba(22, 163, 74, 0.2);
    }

    .badge-user {
        background: rgba(234, 88, 12, 0.1);
        color: var(--warning);
        border: 1px solid rgba(234, 88, 12, 0.2);
    }

    .badge-active {
        background: rgba(22, 163, 74, 0.1);
        color: var(--success);
        border: 1px solid rgba(22, 163, 74, 0.2);
    }

    .badge-inactive {
        background: rgba(220, 38, 38, 0.1);
        color: var(--danger);
        border: 1px solid rgba(220, 38, 38, 0.2);
    }

    .badge-verified {
        background: rgba(22, 163, 74, 0.1);
        color: var(--success);
        border: 1px solid rgba(22, 163, 74, 0.2);
    }

    .badge-pending {
        background: rgba(234, 88, 12, 0.1);
        color: var(--warning);
        border: 1px solid rgba(234, 88, 12, 0.2);
    }

    /* Profile Meta */
    .profile-meta {
        padding: 2rem;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
    }

    .meta-item {
        padding: 1.25rem;
        background: var(--bg-secondary);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-light);
        transition: var(--transition);
    }

    .meta-item:hover {
        background: var(--bg-tertiary);
        border-color: var(--border-medium);
        transform: translateY(-2px);
    }

    .meta-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.625rem;
    }

    .meta-label i {
        font-size: 0.875rem;
        color: var(--primary);
    }

    .meta-value {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-primary);
        word-break: break-word;
    }

    /* Vet Shop Card */
    .vetshop-card {
        margin-bottom: 2rem;
        animation: fadeInUp 0.5s ease-out 0.2s backwards;
    }

    .vetshop-header {
        background: var(--primary);
        padding: 2rem;
        color: white;
    }

    .vetshop-header-content {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .vetshop-icon {
        width: 64px;
        height: 64px;
        min-width: 64px;
        border-radius: var(--radius-lg);
        background: rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
    }

    .vetshop-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .vetshop-description {
        font-size: 0.9375rem;
        opacity: 0.9;
    }

    .vetshop-body {
        padding: 2rem;
    }

    .vetshop-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .vetshop-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-light);
    }

    /* Form Group */
    .form-section {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 0.9375rem;
        border: 1px solid var(--border-light);
        border-radius: var(--radius);
        background: var(--bg-primary);
        transition: var(--transition);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .form-control:hover {
        border-color: var(--border-medium);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 1rem;
        align-items: end;
    }

    /* Statistics Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--bg-primary);
        border-radius: var(--radius-xl);
        padding: 2rem;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow);
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: var(--transition);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--stat-color);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
    }

    .stat-card:hover::before {
        transform: scaleX(1);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .stat-card.appointments {
        --stat-color: var(--primary);
        animation: fadeInUp 0.5s ease-out 0.3s backwards;
    }

    .stat-card.adoptions {
        --stat-color: var(--success);
        animation: fadeInUp 0.5s ease-out 0.35s backwards;
    }

    .stat-card.lost-found {
        --stat-color: var(--warning);
        animation: fadeInUp 0.5s ease-out 0.4s backwards;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        margin: 0 auto 1rem;
        border-radius: var(--radius-lg);
        background: var(--bg-tertiary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--stat-color);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Certificate Section */
    .certificate-section {
        margin-bottom: 2rem;
        animation: fadeInUp 0.5s ease-out 0.25s backwards;
    }

    .certificate-header {
        background: var(--primary);
        padding: 1.5rem 2rem;
        color: white;
        border-radius: var(--radius-xl) var(--radius-xl) 0 0;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
    }

    .section-title i {
        font-size: 1.25rem;
    }

    .certificate-body {
        padding: 2rem;
        background: var(--bg-primary);
        border: 1px solid var(--border-light);
        border-top: none;
        border-radius: 0 0 var(--radius-xl) var(--radius-xl);
    }

    .certificate-empty {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-muted);
    }

    .certificate-empty i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .license-image {
        width: 100%;
        max-width: 700px;
        height: auto;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-md);
        transition: var(--transition);
        display: block;
        margin: 0 auto;
    }

    .license-image:hover {
        transform: scale(1.02);
        box-shadow: var(--shadow-lg);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        flex-wrap: wrap;
        animation: fadeInUp 0.5s ease-out 0.45s backwards;
    }

    .action-buttons form {
        margin: 0;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-size: 0.9375rem;
        font-weight: 600;
        border: none;
        border-radius: var(--radius);
        cursor: pointer;
        text-decoration: none;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn i {
        font-size: 0.875rem;
        position: relative;
        z-index: 1;
    }

    .btn span {
        position: relative;
        z-index: 1;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
        box-shadow: var(--shadow-sm);
    }

    .btn-primary:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-secondary {
        background: var(--secondary);
        color: white;
        box-shadow: var(--shadow-sm);
    }

    .btn-secondary:hover {
        background: #475569;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-success {
        background: var(--success);
        color: white;
        box-shadow: var(--shadow-sm);
    }

    .btn-success:hover {
        background: #15803d;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-warning {
        background: var(--warning);
        color: white;
        box-shadow: var(--shadow-sm);
    }

    .btn-warning:hover {
        background: #c2410c;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-danger {
        background: var(--danger);
        color: white;
        box-shadow: var(--shadow-sm);
    }

    .btn-danger:hover {
        background: #b91c1c;
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

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .container-fluid {
            padding: 1.5rem;
        }

        .page-title {
            font-size: 1.625rem;
        }

        .profile-name {
            font-size: 1.75rem;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            font-size: 2rem;
        }
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding: 1.25rem;
        }

        .page-header {
            padding: 2rem 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .page-subtitle {
            font-size: 0.875rem;
        }

        .profile-header {
            padding: 2.5rem 1.5rem 1.5rem;
        }

        .profile-avatar {
            width: 90px;
            height: 90px;
            font-size: 1.75rem;
        }

        .profile-name {
            font-size: 1.5rem;
        }

        .profile-meta,
        .vetshop-details {
            grid-template-columns: repeat(2, 1fr);
        }

        .vetshop-header-content {
            flex-direction: column;
            text-align: center;
        }

        .vetshop-header,
        .vetshop-body,
        .profile-meta,
        .certificate-body,
        .form-section {
            padding: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .stat-number {
            font-size: 2rem;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .action-buttons .btn {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding: 1rem;
        }

        .page-header {
            padding: 1.75rem 1.25rem;
        }

        .page-title {
            font-size: 1.375rem;
        }

        .profile-header {
            padding: 2rem 1.25rem 1.25rem;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            font-size: 1.5rem;
            border-width: 3px;
        }

        .profile-name {
            font-size: 1.375rem;
        }

        .profile-email {
            font-size: 0.9375rem;
        }

        .profile-badges {
            flex-direction: column;
        }

        .badge {
            width: 100%;
            max-width: 280px;
            justify-content: center;
        }

        .profile-meta,
        .vetshop-details {
            grid-template-columns: 1fr;
        }

        .vetshop-header,
        .vetshop-body,
        .profile-meta,
        .certificate-body,
        .form-section {
            padding: 1.25rem;
        }

        .vetshop-icon {
            width: 56px;
            height: 56px;
            min-width: 56px;
            font-size: 1.5rem;
        }

        .vetshop-title {
            font-size: 1.25rem;
        }

        .vetshop-actions {
            flex-direction: column;
        }

        .vetshop-actions .btn {
            width: 100%;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-card {
            padding: 1.5rem;
        }

        .stat-number {
            font-size: 1.875rem;
        }
    }

    /* Smooth Scrolling */
    html {
        scroll-behavior: smooth;
    }

    /* Loading States */
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    /* Focus Visible */
    .btn:focus-visible,
    .form-control:focus-visible,
    .back-link:focus-visible {
        outline: 2px solid var(--primary);
        outline-offset: 2px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Back Navigation -->
    <div class="back-navigation">
        <a href="{{ route('admin.users.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Users</span>
        </a>
    </div>

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">User Details</h1>
        <p class="page-subtitle">Complete user information and activity overview</p>
    </div>

    <!-- User Profile Card -->
    <div class="card user-profile-card">
        <div class="profile-header">
            <div class="profile-avatar-wrapper">
                <div class="profile-avatar" style="background: {{ $user->role === 'admin' ? '#dc2626' : ($user->role === 'vet' ? '#16a34a' : '#2563eb') }};">
                    @if($user->profile_picture_path)
                        <img src="{{ $user->profile_picture_url }}" alt="{{ $user->name }}">
                    @else
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    @endif
                </div>
            </div>
            <h2 class="profile-name">{{ $user->role === 'vet' ? 'Dr. ' : '' }}{{ $user->name }}</h2>
            <p class="profile-email">{{ $user->email }}</p>
            <div class="profile-badges">
                <span class="badge badge-{{ $user->role }}">
                    <i class="fas fa-{{ $user->role === 'admin' ? 'shield-halved' : ($user->role === 'vet' ? 'user-doctor' : 'user') }}"></i>
                    {{ ucfirst($user->role) }}
                </span>
                <span class="badge badge-{{ $user->is_active ? 'active' : 'inactive' }}">
                    <i class="fas fa-{{ $user->is_active ? 'check-circle' : 'times-circle' }}"></i>
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
                @if($user->role === 'vet')
                    <span class="badge badge-{{ $user->is_verified_vet ? 'verified' : 'pending' }}">
                        <i class="fas fa-{{ $user->is_verified_vet ? 'certificate' : 'clock' }}"></i>
                        {{ $user->is_verified_vet ? 'Verified' : 'Pending Verification' }}
                    </span>
                @endif
            </div>
        </div>
        
        <div class="profile-meta">
            <div class="meta-item">
                <div class="meta-label">
                    <i class="fas fa-calendar-plus"></i>
                    Account Created
                </div>
                <div class="meta-value">{{ $user->created_at->format('M d, Y') }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">
                    <i class="fas fa-calendar-check"></i>
                    Last Updated
                </div>
                <div class="meta-value">{{ $user->updated_at->format('M d, Y') }}</div>
            </div>
            @if($user->phone)
            <div class="meta-item">
                <div class="meta-label">
                    <i class="fas fa-phone"></i>
                    Phone Number
                </div>
                <div class="meta-value">{{ $user->phone }}</div>
            </div>
            @endif
            @if($user->address)
            <div class="meta-item">
                <div class="meta-label">
                    <i class="fas fa-map-marker-alt"></i>
                    Address
                </div>
                <div class="meta-value">{{ $user->address }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Veterinarian Vet Shop Section (Assigned) -->
    @if($user->role === 'vet' && $user->vetShop)
    <div class="card vetshop-card">
        <div class="vetshop-header">
            <div class="vetshop-header-content">
                <div class="vetshop-icon">
                    <i class="fas fa-hospital"></i>
                </div>
                <div>
                    <h3 class="vetshop-title">Assigned Veterinary Clinic</h3>
                    <p class="vetshop-description">This veterinarian is assigned to the following clinic</p>
                </div>
            </div>
        </div>
        
        <div class="vetshop-body">
            <div class="vetshop-details">
                <div class="meta-item">
                    <div class="meta-label">
                        <i class="fas fa-clinic-medical"></i>
                        Clinic Name
                    </div>
                    <div class="meta-value">{{ $user->vetShop->name }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">
                        <i class="fas fa-tag"></i>
                        Clinic Type
                    </div>
                    <div class="meta-value">{{ $user->vetShop->type_name ?? ucfirst($user->vetShop->type) }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">
                        <i class="fas fa-location-dot"></i>
                        Address
                    </div>
                    <div class="meta-value">{{ $user->vetShop->address }}, {{ $user->vetShop->city }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">
                        <i class="fas fa-phone"></i>
                        Phone
                    </div>
                    <div class="meta-value">{{ $user->vetShop->phone }}</div>
                </div>
                @if($user->vetShop->animal_types)
                <div class="meta-item">
                    <div class="meta-label">
                        <i class="fas fa-paw"></i>
                        Animal Types
                    </div>
                    <div class="meta-value">{{ implode(', ', array_map('ucfirst', $user->vetShop->animal_types)) }}</div>
                </div>
                @endif
            </div>
            
            <div class="vetshop-actions">
                <a href="{{ route('admin.map.show', $user->vetShop) }}" class="btn btn-primary">
                    <i class="fas fa-eye"></i>
                    <span>View Clinic</span>
                </a>
                <a href="{{ route('admin.map.edit', $user->vetShop) }}" class="btn btn-secondary">
                    <i class="fas fa-edit"></i>
                    <span>Edit Clinic</span>
                </a>
                <form method="POST" action="{{ route('admin.users.remove-from-shop', $user) }}">
                    @csrf
                    @method('POST')
                    <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to remove this veterinarian from the clinic?')">
                        <i class="fas fa-user-times"></i>
                        <span>Remove from Clinic</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @elseif($user->role === 'vet' && !$user->vetShop)
    <!-- Veterinarian Vet Shop Section (Not Assigned) -->
    <div class="card vetshop-card">
        <div class="vetshop-header">
            <div class="vetshop-header-content">
                <div class="vetshop-icon">
                    <i class="fas fa-hospital"></i>
                </div>
                <div>
                    <h3 class="vetshop-title">Assign to Veterinary Clinic</h3>
                    <p class="vetshop-description">This veterinarian is not assigned to any clinic</p>
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <form method="POST" action="{{ route('admin.users.assign-to-shop', $user) }}">
                @csrf
                @method('POST')
                <div class="form-row">
                    <div class="form-group">
                        <label for="vet_shop_id" class="form-label">Select Veterinary Clinic</label>
                        <select name="vet_shop_id" id="vet_shop_id" class="form-control" required>
                            <option value="">Choose a clinic</option>
                            @foreach(App\Models\Vetshop::active()->get() as $vetshop)
                                <option value="{{ $vetshop->id }}">{{ $vetshop->name }} - {{ $vetshop->address }}, {{ $vetshop->city }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-link"></i>
                        <span>Assign to Clinic</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Statistics Grid -->
    @if($user->role !== 'admin')
    <div class="stats-grid">
        @if($user->role === 'vet')
            <div class="stat-card appointments">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-number">{{ number_format($stats['appointments_count'] ?? 0) }}</div>
                <div class="stat-label">Appointments</div>
            </div>
            <div class="stat-card adoptions">
                <div class="stat-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="stat-number">{{ number_format($stats['adoptions_count'] ?? 0) }}</div>
                <div class="stat-label">Adoptions Handled</div>
            </div>
        @else
            <div class="stat-card adoptions">
                <div class="stat-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="stat-number">{{ number_format($stats['adoption_listings_count'] ?? 0) }}</div>
                <div class="stat-label">Adoption Listings</div>
            </div>
            <div class="stat-card appointments">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-number">{{ number_format($stats['appointments_count'] ?? 0) }}</div>
                <div class="stat-label">Appointments</div>
            </div>
            <div class="stat-card lost-found">
                <div class="stat-icon">
                    <i class="fas fa-search"></i>
                </div>
                <div class="stat-number">{{ number_format($stats['lost_found_count'] ?? 0) }}</div>
                <div class="stat-label">Lost & Found</div>
            </div>
        @endif
    </div>
    @endif

    <!-- Veterinarian Certificate Section -->
    @if($user->role === 'vet')
    <div class="card certificate-section">
        <div class="certificate-header">
            <h3 class="section-title">
                <i class="fas fa-certificate"></i>
                <span>Veterinarian Certificate</span>
            </h3>
        </div>
        <div class="certificate-body">
            @if($user->certificate_path)
                <img src="{{ $user->certificate_url }}" alt="Veterinarian License Certificate" class="license-image">
            @else
                <div class="certificate-empty">
                    <i class="fas fa-file-circle-xmark"></i>
                    <p>No certificate uploaded</p>
                </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i>
            <span>Edit User</span>
        </a>
        
        @if($user->role === 'vet' && !$user->is_verified_vet)
        <form method="POST" action="{{ route('admin.users.verify-vet', $user) }}">
            @csrf
            @method('POST')
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check"></i>
                <span>Verify Veterinarian</span>
            </button>
        </form>
        @endif
        
        @if($user->role === 'vet' && $user->is_verified_vet)
        <form method="POST" action="{{ route('admin.users.reject-vet', $user) }}">
            @csrf
            @method('POST')
            <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to revoke veterinarian verification?')">
                <i class="fas fa-times"></i>
                <span>Revoke Verification</span>
            </button>
        </form>
        @endif
        
        @if(auth()->user()->role === 'admin')
        <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                <i class="fas fa-trash"></i>
                <span>Delete User</span>
            </button>
        </form>
        @endif
    </div>
</div>
@endsection