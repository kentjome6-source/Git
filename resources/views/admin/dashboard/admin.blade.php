@extends('layouts.admin')

@section('title', 'Dashboard')

@section('styles')
<style>
    :root {
        --primary: #0f172a;
        --primary-light: #1e293b;
        --accent: #3b82f6;
        --accent-light: #60a5fa;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #06b6d4;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --bg-primary: #ffffff;
        --bg-secondary: #f8fafc;
        --border-color: #e2e8f0;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        --radius: 12px;
        --radius-lg: 16px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
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

    /* Header Section */
    .dashboard-header {
        margin-bottom: 2.5rem;
        animation: slideDown 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .header-text h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        letter-spacing: -0.025em;
    }

    .header-text p {
        font-size: 1rem;
        color: var(--text-secondary);
        font-weight: 400;
    }

    .header-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 1.25rem;
        background: var(--bg-primary);
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
    }

    .header-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .header-meta-dot {
        width: 6px;
        height: 6px;
        background: var(--accent);
        border-radius: 50%;
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    /* Stats Grid */
    .stats-grid {
        margin-bottom: 2.5rem;
    }

    .stat-card {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) backwards;
        height: 100%;
    }

    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    .stat-card:nth-child(4) { animation-delay: 0.4s; }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--card-color), var(--card-color-light));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: var(--card-color);
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-card.primary {
        --card-color: var(--accent);
        --card-color-light: var(--accent-light);
    }

    .stat-card.success {
        --card-color: var(--success);
        --card-color-light: #34d399;
    }

    .stat-card.info {
        --card-color: var(--info);
        --card-color-light: #22d3ee;
    }

    .stat-card.warning {
        --card-color: var(--warning);
        --card-color-light: #fbbf24;
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }

    .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--card-color), var(--card-color-light));
        transition: var(--transition);
    }

    .stat-card:hover .stat-icon-wrapper {
        transform: scale(1.1);
    }

    .stat-icon {
        width: 24px;
        height: 24px;
        color: white;
        stroke-width: 2;
    }

    .stat-trend {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--success);
        padding: 0.25rem 0.5rem;
        background: rgba(16, 185, 129, 0.1);
        border-radius: 6px;
    }

    .stat-body h3 {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 2.25rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
        letter-spacing: -0.025em;
    }

    /* Actions Grid */
    .actions-grid {
        margin-bottom: 2rem;
    }

    .action-card {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        transition: var(--transition);
        overflow: hidden;
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) backwards;
        height: 100%;
    }

    .action-card:nth-child(1) { animation-delay: 0.5s; }
    .action-card:nth-child(2) { animation-delay: 0.6s; }
    .action-card:nth-child(3) { animation-delay: 0.7s; }
    .action-card:nth-child(4) { animation-delay: 0.8s; }
    .action-card:nth-child(5) { animation-delay: 0.9s; }

    .action-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
        border-color: var(--accent);
    }

    .action-card-content {
        padding: 2rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .action-icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--accent), var(--accent-light));
        margin-bottom: 1.5rem;
        transition: var(--transition);
    }

    .action-card:hover .action-icon-wrapper {
        transform: scale(1.05);
    }

    .action-icon {
        width: 28px;
        height: 28px;
        color: white;
        stroke-width: 2;
    }

    .action-card-content h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        letter-spacing: -0.025em;
    }

    .action-card-content p {
        font-size: 0.9375rem;
        color: var(--text-secondary);
        line-height: 1.6;
        margin-bottom: 1.5rem;
        flex: 1;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: var(--primary);
        color: white;
        font-size: 0.9375rem;
        font-weight: 600;
        border-radius: var(--radius);
        text-decoration: none;
        transition: var(--transition);
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 0;
        height: 100%;
        background: var(--accent);
        transition: width 0.3s ease;
        z-index: 0;
    }

    .action-btn:hover::before {
        width: 100%;
    }

    .action-btn:hover {
        color: white;
        transform: translateX(2px);
    }

    .action-btn span {
        position: relative;
        z-index: 1;
    }

    .action-btn svg {
        width: 18px;
        height: 18px;
        transition: transform 0.3s ease;
        position: relative;
        z-index: 1;
    }

    .action-btn:hover svg {
        transform: translateX(4px);
    }

    /* Section Title */
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        letter-spacing: -0.025em;
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) backwards;
        animation-delay: 0.4s;
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

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .container-fluid {
            padding: 1.5rem 1.25rem;
        }
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding: 1.25rem 1rem;
        }

        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-text h1 {
            font-size: 1.75rem;
        }

        .header-meta {
            width: 100%;
            justify-content: space-between;
        }

        .stat-value {
            font-size: 2rem;
        }

        .section-title {
            font-size: 1.25rem;
        }
    }

    @media (max-width: 480px) {
        .container-fluid {
            padding: 1rem 0.75rem;
        }

        .header-text h1 {
            font-size: 1.5rem;
        }

        .header-text p {
            font-size: 0.875rem;
        }

        .header-meta {
            flex-direction: column;
            align-items: stretch;
        }

        .header-meta-item {
            justify-content: center;
        }

        .stat-card {
            padding: 1.5rem;
        }

        .stat-value {
            font-size: 1.75rem;
        }

        .action-card-content {
            padding: 1.5rem;
        }

        .action-btn {
            width: 100%;
        }
    }

    /* Smooth Scrolling */
    html {
        scroll-behavior: smooth;
    }

    /* Focus Styles */
    .action-btn:focus-visible {
        outline: 2px solid var(--accent);
        outline-offset: 2px;
    }

    /* Loading State */
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <div class="header-content">
                    <div class="header-text">
                        <h1>Dashboard Overview</h1>
                        <p>Monitor and manage your pet adoption platform</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="row stats-grid">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="stat-card primary">
                <div class="stat-header">
                    <div class="stat-icon-wrapper">
                        <svg class="stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <div class="stat-body">
                    <h3>Total Users</h3>
                    <div class="stat-value">{{ number_format($totalUsers ?? 0) }}</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="stat-card success">
                <div class="stat-header">
                    <div class="stat-icon-wrapper">
                        <svg class="stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="stat-body">
                    <h3>Total Pets</h3>
                    <div class="stat-value">{{ number_format($totalPets ?? 0) }}</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="stat-card info">
                <div class="stat-header">
                    <div class="stat-icon-wrapper">
                        <svg class="stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                </div>
                <div class="stat-body">
                    <h3>Adoptions</h3>
                    <div class="stat-value">{{ number_format($totalAdoptions ?? 0) }}</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="stat-card warning">
                <div class="stat-header">
                    <div class="stat-icon-wrapper">
                        <svg class="stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
                <div class="stat-body">
                    <h3>Lost & Found</h3>
                    <div class="stat-value">{{ number_format($totalLostFound ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Title -->
    <div class="row">
        <div class="col-12">
            <h2 class="section-title">Quick Actions</h2>
        </div>
    </div>

    <!-- Actions Grid -->
    <div class="row actions-grid">
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="action-card">
                <div class="action-card-content">
                    <div class="action-icon-wrapper">
                        <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3>Adoption History</h3>
                    <p>View complete adoption history and manage requests</p>
                    <a href="{{ route('admin.adoptions.index') }}" class="action-btn">
                        <span>View History</span>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="action-card">
                <div class="action-card-content">
                    <div class="action-icon-wrapper">
                        <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3>User Management</h3>
                    <p>Manage registered users and veterinarians</p>
                    <a href="{{ route('admin.users.index') }}" class="action-btn">
                        <span>Manage Users</span>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="action-card">
                <div class="action-card-content">
                    <div class="action-icon-wrapper">
                        <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3>Pet Management</h3>
                    <p>View and manage all registered pets</p>
                    <a href="{{ route('admin.pets.index') }}" class="action-btn">
                        <span>Manage Pets</span>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="action-card">
                <div class="action-card-content">
                    <div class="action-icon-wrapper">
                        <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3>Lost & Found</h3>
                    <p>View lost and found pet listings</p>
                    <a href="{{ route('admin.lost-found.index') }}" class="action-btn">
                        <span>View Records</span>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="action-card">
                <div class="action-card-content">
                    <div class="action-icon-wrapper">
                        <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                    </div>
                    <h3>Map Management</h3>
                    <p>Manage shelter locations and map settings</p>
                    <a href="{{ route('admin.map.index') }}" class="action-btn">
                        <span>Manage Map</span>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection