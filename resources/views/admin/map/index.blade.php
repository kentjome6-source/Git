@extends('layouts.admin')

@section('title', 'Map Management')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
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

    /* Two Column Layout */
    .main-layout {
        display: grid;
        grid-template-columns: 400px 1fr;
        gap: 1.5rem;
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.2s backwards;
    }

    /* Locations Sidebar */
    .locations-sidebar {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .sidebar-header {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
    }

    .sidebar-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        letter-spacing: -0.025em;
    }

    .sidebar-subtitle {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .locations-list {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        overflow: hidden;
        max-height: calc(100vh - 280px);
        overflow-y: auto;
    }

    .locations-list::-webkit-scrollbar {
        width: 6px;
    }

    .locations-list::-webkit-scrollbar-track {
        background: var(--bg-secondary);
    }

    .locations-list::-webkit-scrollbar-thumb {
        background: var(--border-color);
        border-radius: 3px;
    }

    .locations-list::-webkit-scrollbar-thumb:hover {
        background: var(--text-muted);
    }

    /* Map Section */
    .map-section {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-md);
        position: sticky;
        top: 2rem;
        height: calc(100vh - 280px);
        display: flex;
        flex-direction: column;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        letter-spacing: -0.025em;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
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
    }

    .btn-primary:hover {
        background: var(--accent);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-primary i {
        font-size: 0.875rem;
    }

    .map-container {
        flex: 1;
        border-radius: var(--radius);
        overflow: hidden;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        position: relative;
        min-height: 400px;
    }

    .map-controls {
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 1000;
        display: flex;
        gap: 0.5rem;
    }

    .map-btn {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: var(--shadow);
        transition: var(--transition);
    }

    .map-btn:hover {
        background: var(--bg-secondary);
        box-shadow: var(--shadow-md);
    }

    .map-btn i {
        font-size: 0.875rem;
        color: var(--text-primary);
    }

    /* Location Cards in Sidebar */
    .location-card {
        padding: 1.25rem;
        border-bottom: 1px solid var(--border-color);
        transition: var(--transition);
        cursor: pointer;
        animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) backwards;
    }

    .location-card:nth-child(1) { animation-delay: 0.1s; }
    .location-card:nth-child(2) { animation-delay: 0.15s; }
    .location-card:nth-child(3) { animation-delay: 0.2s; }
    .location-card:nth-child(4) { animation-delay: 0.25s; }
    .location-card:nth-child(5) { animation-delay: 0.3s; }

    .location-card:last-child {
        border-bottom: none;
    }

    .location-card:hover {
        background: var(--bg-secondary);
    }

    .location-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
    }

    .location-name {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .location-city {
        font-size: 0.8125rem;
        color: var(--text-secondary);
    }

    .location-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .location-info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8125rem;
        color: var(--text-secondary);
    }

    .location-info-item i {
        color: var(--accent);
        font-size: 0.75rem;
        width: 14px;
    }

    .location-actions {
        display: flex;
        gap: 0.375rem;
        padding-top: 0.75rem;
        border-top: 1px solid var(--border-color);
    }

    /* Table Section - Hidden on Desktop */
    .content-section {
        display: none;
    }

    .desktop-table {
        display: none;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead {
        background: var(--bg-secondary);
    }

    .data-table th {
        padding: 1rem 1.25rem;
        text-align: left;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        border-bottom: 1px solid var(--border-color);
    }

    .data-table td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-primary);
    }

    .data-table tbody tr {
        transition: var(--transition);
    }

    .data-table tbody tr:hover {
        background: var(--bg-secondary);
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .table-cell-content strong {
        display: block;
        margin-bottom: 0.25rem;
        font-weight: 600;
        font-size: 0.9375rem;
    }

    .table-cell-content small {
        color: var(--text-secondary);
        font-size: 0.8125rem;
    }

    .contact-info div {
        margin-bottom: 0.375rem;
        font-size: 0.8125rem;
        color: var(--text-secondary);
    }

    .contact-info div:last-child {
        margin-bottom: 0;
    }

    .contact-info i {
        color: var(--accent);
        margin-right: 0.375rem;
        font-size: 0.75rem;
    }

    /* Badges */
    .badge {
        padding: 0.375rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        letter-spacing: 0.025em;
    }

    .badge-info {
        background: rgba(6, 182, 212, 0.1);
        color: var(--info);
    }

    .badge-success {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .badge-warning {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.025em;
    }

    .status-active {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .status-inactive {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.375rem;
        padding: 0.5rem 0.875rem;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 0.8125rem;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.75rem;
    }

    .btn i {
        font-size: 0.75rem;
    }

    .btn-info {
        background: rgba(6, 182, 212, 0.1);
        color: var(--info);
    }

    .btn-info:hover {
        background: var(--info);
        color: white;
        transform: translateY(-2px);
    }

    .btn-warning {
        background: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    .btn-warning:hover {
        background: var(--warning);
        color: white;
        transform: translateY(-2px);
    }

    .btn-danger {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .btn-danger:hover {
        background: var(--danger);
        color: white;
        transform: translateY(-2px);
    }

    /* Mobile View */
    .mobile-swipeable-view {
        display: none;
    }

    .swipeable-location-card {
        background: var(--bg-primary);
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        margin-bottom: 1rem;
        box-shadow: var(--shadow);
        overflow: hidden;
        animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) backwards;
    }

    .swipeable-location-card:nth-child(1) { animation-delay: 0.1s; }
    .swipeable-location-card:nth-child(2) { animation-delay: 0.15s; }
    .swipeable-location-card:nth-child(3) { animation-delay: 0.2s; }
    .swipeable-location-card:nth-child(4) { animation-delay: 0.25s; }
    .swipeable-location-card:nth-child(5) { animation-delay: 0.3s; }

    .swipeable-item {
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        gap: 1rem;
    }

    .swipeable-item:last-of-type {
        border-bottom: none;
    }

    .swipeable-item .label {
        font-weight: 600;
        font-size: 0.75rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        min-width: 90px;
    }

    .swipeable-item .value {
        text-align: right;
        flex: 1;
        font-size: 0.9375rem;
        color: var(--text-primary);
    }

    .swipeable-actions {
        padding: 1rem;
        display: flex;
        gap: 0.5rem;
        background: var(--bg-secondary);
        border-top: 1px solid var(--border-color);
    }

    .swipeable-actions .btn {
        flex: 1;
    }

    .swipeable-actions form {
        flex: 1;
        margin: 0;
    }

    .swipeable-actions form .btn {
        width: 100%;
    }

    /* Fullscreen Overlay */
    .fullscreen-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.9);
        z-index: 9999;
        display: none;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--text-muted);
        margin-bottom: 1.5rem;
        opacity: 0.5;
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
        .main-layout {
            grid-template-columns: 350px 1fr;
        }
    }

    @media (max-width: 1024px) {
        .container-fluid {
            padding: 1.5rem 1.25rem;
        }

        .main-layout {
            grid-template-columns: 1fr;
        }

        .map-section {
            position: relative;
            top: 0;
            height: 450px;
            order: -1;
        }

        .map-container {
            min-height: 350px;
        }

        .locations-list {
            max-height: none;
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

        .sidebar-header {
            padding: 1.25rem;
        }

        .map-section {
            padding: 1.5rem;
            height: 400px;
        }

        .map-container {
            min-height: 300px;
        }

        .map-btn {
            width: 32px;
            height: 32px;
        }

        .map-btn i {
            font-size: 0.75rem;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-primary {
            width: 100%;
            justify-content: center;
        }

        .location-card {
            padding: 1rem;
        }

        .location-actions {
            flex-wrap: wrap;
        }

        .location-actions .btn {
            flex: 1;
        }

        /* Hide sidebar view on mobile */
        .locations-sidebar {
            display: none;
        }

        /* Show mobile swipeable view */
        .mobile-swipeable-view {
            display: block;
        }

        .data-table th,
        .data-table td {
            padding: 0.875rem 1rem;
            font-size: 0.875rem;
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

        .sidebar-header {
            padding: 1rem;
        }

        .map-section {
            padding: 1.25rem;
            height: 350px;
        }

        .map-container {
            min-height: 250px;
        }

        .map-btn {
            width: 28px;
            height: 28px;
        }

        .location-card {
            padding: 0.875rem;
        }

        .swipeable-item {
            padding: 0.875rem;
        }

        .swipeable-item .label {
            font-size: 0.6875rem;
            min-width: 80px;
        }

        .swipeable-item .value {
            font-size: 0.875rem;
        }

        .swipeable-actions {
            padding: 0.875rem;
            gap: 0.375rem;
        }

        .swipeable-actions .btn {
            padding: 0.5rem 0.625rem;
            font-size: 0.75rem;
        }

        .empty-state {
            padding: 3rem 1.5rem;
        }

        .empty-state i {
            font-size: 3rem;
        }

        .empty-state h3 {
            font-size: 1.25rem;
        }
    }

    /* Desktop - ensure sidebar shows */
    @media (min-width: 769px) {
        .mobile-swipeable-view {
            display: none !important;
        }
        .locations-sidebar {
            display: flex !important;
        }
    }

    /* Smooth Scrolling */
    html {
        scroll-behavior: smooth;
    }

    /* Leaflet Popup Customization */
    .leaflet-popup-content-wrapper {
        border-radius: var(--radius);
        box-shadow: var(--shadow-lg);
    }

    .leaflet-popup-content {
        font-family: inherit;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h1 class="page-title">Map Management</h1>
        <p class="page-subtitle">Manage veterinary clinic and pet shop locations</p>
    </div>

    @if($shelters->count() > 0)
        <div class="main-layout">
            <!-- Left Sidebar - Locations List -->
            <div class="locations-sidebar">
                <div class="sidebar-header">
                    <h2 class="sidebar-title">Locations</h2>
                </div>

                <div class="locations-list">
                    @foreach($shelters as $shelter)
                        <div class="location-card">
                            <div class="location-card-header">
                                <div>
                                    <div class="location-name">{{ $shelter->name }}</div>
                                    <div class="location-city">{{ $shelter->city }}, {{ $shelter->province }}</div>
                                </div>
                                <span class="badge badge-{{ $shelter->type === 'pet_shop' ? 'info' : ($shelter->type === 'veterinarian' ? 'success' : 'warning') }}">
                                    {{ $shelter->getTypeNameAttribute() }}
                                </span>
                            </div>

                            <div class="location-info">
                                <div class="location-info-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ Str::limit($shelter->address, 40) }}</span>
                                </div>
                                @if($shelter->phone)
                                    <div class="location-info-item">
                                        <i class="fas fa-phone"></i>
                                        <span>{{ $shelter->phone }}</span>
                                    </div>
                                @endif
                                <div class="location-info-item">
                                    <i class="fas fa-circle"></i>
                                    <span class="status-badge {{ $shelter->is_active ? 'status-active' : 'status-inactive' }}" style="padding: 0.25rem 0.625rem;">
                                        {{ $shelter->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>

                            <div class="location-actions">
                                <a href="{{ route('admin.map.show', $shelter) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.map.edit', $shelter) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.map.destroy', $shelter) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this location? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Side - Map -->
            <div class="map-section">
                <div class="section-header">
                    <h2 class="section-title">Location Map</h2>
                    <a href="{{ route('admin.map.create') }}" class="btn-primary">
                        <i class="fas fa-plus"></i>
                        <span>Add Location</span>
                    </a>
                </div>
                
                <div class="map-container">
                    <div id="shelterMap" style="height: 100%; width: 100%;"></div>
                    <div class="map-controls">
                        <button id="fullscreen-btn" class="map-btn" title="Fullscreen">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile View Only -->
        <div class="mobile-swipeable-view">
            @foreach($shelters as $shelter)
                <div class="swipeable-location-card">
                    <div class="swipeable-item">
                        <span class="label">Name</span>
                        <span class="value">
                            <strong>{{ $shelter->name }}</strong>
                            <br>
                            <small>{{ $shelter->city }}, {{ $shelter->province }}</small>
                        </span>
                    </div>
                    
                    <div class="swipeable-item">
                        <span class="label">Type</span>
                        <span class="value">
                            <span class="badge badge-{{ $shelter->type === 'pet_shop' ? 'info' : ($shelter->type === 'veterinarian' ? 'success' : 'warning') }}">
                                {{ $shelter->getTypeNameAttribute() }}
                            </span>
                        </span>
                    </div>
                    
                    <div class="swipeable-item">
                        <span class="label">Address</span>
                        <span class="value">{{ $shelter->address }}</span>
                    </div>
                    
                    <div class="swipeable-item">
                        <span class="label">Contact</span>
                        <span class="value">
                            @if($shelter->phone)
                                <div><i class="fas fa-phone"></i> {{ $shelter->phone }}</div>
                            @endif
                            @if($shelter->email)
                                <div><i class="fas fa-envelope"></i> {{ $shelter->email }}</div>
                            @endif
                        </span>
                    </div>
                    
                    <div class="swipeable-item">
                        <span class="label">Status</span>
                        <span class="value">
                            <span class="status-badge {{ $shelter->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $shelter->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </span>
                    </div>
                    
                    <div class="swipeable-actions">
                        <a href="{{ route('admin.map.show', $shelter) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('admin.map.edit', $shelter) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.map.destroy', $shelter) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this location? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-map-marker-alt"></i>
            <h3>No locations added yet</h3>
            <p>Start by adding your first location to the map</p>
            <a href="{{ route('admin.map.create') }}" class="btn-primary" style="margin-top: 1.5rem;">
                <i class="fas fa-plus"></i>
                <span>Add First Location</span>
            </a>
        </div>
    @endif
</div>

<!-- Fullscreen Overlay -->
<div id="fullscreen-overlay" class="fullscreen-overlay">
    <div id="fullscreen-map" style="height: 100%; width: 100%;"></div>
    <div class="map-controls">
        <button id="exit-fullscreen-btn" class="map-btn" title="Exit Fullscreen">
            <i class="fas fa-compress"></i>
        </button>
    </div>
</div>
@endsection

@section('scripts')
<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
// Location data from backend
const locations = @json($shelters->items());
let allLocations = locations;

// Define base route URL for map details
const baseUrl = "{{ url('admin/map') }}";

// Initialize map when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Wait a bit for the assets to load
    setTimeout(function() {
        if (typeof SharedMap !== 'undefined') {
            // Initialize the shared map component
            const sharedMap = new SharedMap('shelterMap', allLocations, {
                fullscreenEnabled: true,
                showViewDetails: true,
                viewDetailsRoute: baseUrl + '/',
                zoom: 15
            });
            
            // Store reference to map for potential future use
            window.shelterMap = sharedMap;
        } else {
            console.error('SharedMap is not available');
            // Fallback to basic map initialization
            initBasicMap();
        }
    }, 100);
});

// Fallback function for basic map initialization
function initBasicMap() {
    const map = L.map('shelterMap', {
        zoomControl: false
    }).setView([8.504588, 125.975800], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    allLocations.forEach(location => {
        if (location.latitude && location.longitude) {
            const lat = parseFloat(location.latitude);
            const lng = parseFloat(location.longitude);
            
            let iconClass = 'fas fa-user-md';
            let iconColor = '#10b981';
            
            const customIcon = L.divIcon({
                html: `<div style="background: ${iconColor}; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"><i class="${iconClass}" style="font-size: 12px;"></i></div>`,
                iconSize: [36, 36],
                iconAnchor: [18, 18],
                popupAnchor: [0, -18],
                className: 'custom-marker'
            });
            
            const marker = L.marker([lat, lng], { icon: customIcon });
            
            const popupContent = `
                <div style="min-width: 250px;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <div style="background: ${iconColor}; color: white; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                            <i class="${iconClass}"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0; font-size: 1.1rem; color: #1f2937;">${location.name}</h4>
                            <span style="background: #e5e7eb; color: #374151; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Veterinarian</span>
                        </div>
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-map-marker-alt" style="color: #3b82f6; margin-right: 6px;"></i>
                        ${location.address}<br>
                        ${location.city}, ${location.province}
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-phone" style="color: #3b82f6; margin-right: 6px;"></i>
                        ${location.phone || 'Not provided'}
                    </div>
                    ${location.email ? `
                        <div style="margin-bottom: 12px; color: #4b5563;">
                            <i class="fas fa-envelope" style="color: #3b82f6; margin-right: 6px;"></i>
                            ${location.email}
                        </div>
                    ` : ''}
                    <div style="display: flex; gap: 5px; margin-top: 12px;">
                        <a href="${baseUrl}/${location.id}" style="background: #3b82f6; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            marker.addTo(map);
        }
    });
    
    window.shelterMap = map;
    initFullscreenFunctionality();
}

function initFullscreenFunctionality() {
    const fullscreenBtn = document.getElementById('fullscreen-btn');
    const exitFullscreenBtn = document.getElementById('exit-fullscreen-btn');
    const fullscreenOverlay = document.getElementById('fullscreen-overlay');
    
    if (fullscreenBtn) {
        fullscreenBtn.addEventListener('click', handleFullscreenToggle);
        fullscreenBtn.addEventListener('touchstart', handleFullscreenToggle);
    }
    
    if (exitFullscreenBtn) {
        exitFullscreenBtn.addEventListener('click', exitFullscreen);
        exitFullscreenBtn.addEventListener('touchstart', exitFullscreen);
    }
    
    if (fullscreenOverlay) {
        fullscreenOverlay.addEventListener('click', handleOverlayClick);
        fullscreenOverlay.addEventListener('touchstart', handleOverlayClick);
    }
    
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && fullscreenOverlay && fullscreenOverlay.style.display === 'block') {
            exitFullscreen();
        }
    });
}

function handleFullscreenToggle(e) {
    e.preventDefault();
    const fullscreenOverlay = document.getElementById('fullscreen-overlay');
    if (fullscreenOverlay) {
        fullscreenOverlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        if (!window.fullscreenMap) {
            initFullscreenMap();
        } else {
            setTimeout(() => {
                window.fullscreenMap.invalidateSize();
            }, 100);
        }
    }
}

function handleOverlayClick(e) {
    if (e.target.id === 'fullscreen-overlay') {
        exitFullscreen();
    }
}

function initFullscreenMap() {
    const mapLocations = @json($shelters->items());
    
    window.fullscreenMap = L.map('fullscreen-map', {
        zoomControl: false
    }).setView([8.504588, 125.975800], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(window.fullscreenMap);
    
    mapLocations.forEach(location => {
        if (location.latitude && location.longitude) {
            const lat = parseFloat(location.latitude);
            const lng = parseFloat(location.longitude);
            
            let iconClass = 'fas fa-user-md';
            let iconColor = '#10b981';
            
            const customIcon = L.divIcon({
                html: `<div style="background: ${iconColor}; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"><i class="${iconClass}" style="font-size: 12px;"></i></div>`,
                iconSize: [36, 36],
                iconAnchor: [18, 18],
                popupAnchor: [0, -18],
                className: 'custom-marker'
            });
            
            const marker = L.marker([lat, lng], { icon: customIcon });
            
            const popupContent = `
                <div style="min-width: 250px;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <div style="background: ${iconColor}; color: white; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                            <i class="${iconClass}"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0; font-size: 1.1rem; color: #1f2937;">${location.name}</h4>
                            <span style="background: #e5e7eb; color: #374151; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Veterinarian</span>
                        </div>
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-map-marker-alt" style="color: #3b82f6; margin-right: 6px;"></i>
                        ${location.address}<br>
                        ${location.city}, ${location.province}
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-phone" style="color: #3b82f6; margin-right: 6px;"></i>
                        ${location.phone || 'Not provided'}
                    </div>
                    ${location.email ? `
                        <div style="margin-bottom: 12px; color: #4b5563;">
                            <i class="fas fa-envelope" style="color: #3b82f6; margin-right: 6px;"></i>
                            ${location.email}
                        </div>
                    ` : ''}
                    <div style="display: flex; gap: 5px; margin-top: 12px;">
                        <a href="${baseUrl}/${location.id}" style="background: #3b82f6; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            marker.addTo(window.fullscreenMap);
        }
    });
    
    window.fullscreenMap.on('click', function() {
        exitFullscreen();
    });
}

function exitFullscreen() {
    const fullscreenOverlay = document.getElementById('fullscreen-overlay');
    if (fullscreenOverlay) {
        fullscreenOverlay.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}
</script>
@endsection