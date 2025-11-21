@extends('layouts.admin')

@section('title', 'Map Management')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .page-header { 
        background: #e74c3c; padding: 30px; border-radius: 15px; margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    
    .page-header h1 { color: white; }
    .page-header p { color: white; opacity: 0.9; }
    .page-title { font-size: 2rem; font-weight: 700; color: white; margin-bottom: 8px; }
    .page-subtitle { font-size: 1rem; color: white; opacity: 0.9; }

    /* Stats Cards */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); display: flex; align-items: center; }
    .stat-icon { width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px; font-size: 1.5rem; color: white; }
    .stat-info h3 { font-size: 1.75rem; font-weight: 700; margin-bottom: 5px; color: #1f2937; }
    .stat-info p { margin: 0; color: #6b7280; font-size: 0.9rem; }
    
    .bg-blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .bg-green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .bg-purple { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
    .bg-yellow { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }

    /* Section Header */
    .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px; }
    .section-title { font-size: 1.5rem; font-weight: 700; color: #1f2937; margin: 0; }
    
    /* Search and Filter */
    .search-filter-container { display: flex; gap: 10px; flex-wrap: wrap; }
    .search-form { display: flex; }
    .search-input { padding: 10px 15px; border: 1px solid #d1d5db; border-radius: 6px 0 0 6px; width: 250px; font-size: 0.9rem; }
    .search-btn { background: #667eea; color: white; border: none; border-radius: 0 6px 6px 0; padding: 0 15px; cursor: pointer; }
    .filter-dropdown select { padding: 10px 15px; border: 1px solid #d1d5db; border-radius: 6px; background: white; font-size: 0.9rem; }
    
    /* Map Styles */
    .map-section { background: white; border-radius: 12px; padding: 25px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .map-container { 
        height: 450px; 
        border-radius: 10px; 
        overflow: hidden; 
        border: 2px solid #e5e7eb; 
        position: relative; 
        width: 100%;
        max-width: 100vw;
    }
    
    /* Map Controls */
    .map-controls { position: absolute; top: 10px; right: 10px; z-index: 1000; display: flex; gap: 5px; }
    .map-btn { background: white; border: 1px solid #ccc; border-radius: 4px; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.2s; }
    .map-btn:hover { background: #f5f5f5; box-shadow: 0 2px 6px rgba(0,0,0,0.15); }
    .map-btn i { font-size: 14px; color: #333; }
    
    .fullscreen-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.8); z-index: 9999; display: none; }
    
    /* Table Styles */
    .table-responsive { overflow-x: auto; }
    .data-table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .data-table thead { background: #f3f4f6; }
    .data-table th { padding: 15px; text-align: left; font-weight: 600; color: #374151; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px; }
    .data-table td { padding: 15px; border-top: 1px solid #e5e7eb; }
    .data-table tbody tr:hover { background: #f9fafb; }
    
    .table-cell-content strong { display: block; margin-bottom: 3px; }
    .table-cell-content small { color: #6b7280; }
    
    .contact-info div { margin-bottom: 3px; }
    .contact-info div:last-child { margin-bottom: 0; }
    
    .badge { padding: 5px 10px; border-radius: 16px; font-size: 0.75rem; font-weight: 600; display: inline-block; }
    .badge-info { background: #ddd6fe; color: #5b21b6; }
    .badge-success { background: #dcfce7; color: #166534; }
    .badge-warning { background: #fef3c7; color: #92400e; }
    
    .status-badge { padding: 5px 10px; border-radius: 16px; font-size: 0.8rem; font-weight: 600; }
    .status-active { background: #dcfce7; color: #166534; }
    .status-inactive { background: #fee2e2; color: #991b1b; }
    
    .action-buttons { display: flex; gap: 5px; }
    .btn { padding: 8px 12px; border-radius: 6px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; text-align: center; display: inline-block; font-size: 0.85rem; }
    .btn-sm { padding: 6px 10px; font-size: 0.8rem; }
    .btn-info { background: #93c5fd; color: #1e40af; }
    .btn-warning { background: #fcd34d; color: #92400e; }
    .btn-secondary { background: #d1d5db; color: #374151; }
    .btn-success { background: #86efac; color: #166534; }
    .btn-danger { background: #fca5a5; color: #991b1b; }
    
    .btn:hover { transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    
    /* Pagination */
    .pagination-container { margin-top: 25px; display: flex; justify-content: center; }
    
    /* Empty State */
    .empty-state { text-align: center; padding: 60px 20px; color: #6b7280; }
    .empty-state i { font-size: 3rem; margin-bottom: 20px; opacity: 0.3; }
    .empty-state h3 { margin-bottom: 10px; color: #374151; font-size: 1.5rem; }
    
    /* Responsive styles */
    @media (max-width: 768px) {
        .page-header {
            padding: 20px 15px;
            background: #e74c3c;
        }
        
        .page-title {
            font-size: 1.75rem;
            color: white;
        }
        
        .page-subtitle {
            font-size: 0.9rem;
            color: white;
        }
        
        .stats-grid { grid-template-columns: 1fr; gap: 15px; margin-bottom: 25px; }
        .stat-card { padding: 15px; }
        .stat-icon { width: 50px; height: 50px; font-size: 1.25rem; margin-right: 12px; }
        .stat-info h3 { font-size: 1.5rem; }
        
        .section-header { flex-direction: column; align-items: flex-start; gap: 15px; width: 100%; }
        .section-header .btn-primary { align-self: flex-end; }
        .section-title { font-size: 1.35rem; }
        
        .search-filter-container { width: 100%; }
        .search-input { width: 100%; }
        
        .map-section { padding: 20px; margin-bottom: 25px; }
        .map-container { height: 350px; border-radius: 8px; }
        
        .map-btn { width: 28px; height: 28px; }
        .map-btn i { font-size: 12px; }
        
        .data-table th, .data-table td { padding: 12px 10px; }
        
        .action-buttons { flex-wrap: wrap; justify-content: flex-end; }
        
        .empty-state { padding: 40px 15px; }
        .empty-state i { font-size: 2.5rem; margin-bottom: 15px; }
        .empty-state h3 { font-size: 1.25rem; }
        
        /* Mobile specific adjustments for map */
        .map-container {
            height: 300px;
            max-width: 100vw;
            overflow: hidden;
        }
        
        /* Ensure table fits on mobile */
        .table-responsive {
            overflow-x: auto;
            max-width: 100vw;
        }
        
        .data-table {
            min-width: 600px; /* Ensures table doesn't shrink too much */
        }
        
        .data-table th, .data-table td {
            white-space: nowrap;
            font-size: 0.8rem;
        }
    }
    
    @media (max-width: 576px) {
        .page-title { font-size: 1.5rem; }
        
        .stat-card { padding: 12px; }
        .stat-icon { width: 45px; height: 45px; font-size: 1rem; margin-right: 10px; }
        .stat-info h3 { font-size: 1.25rem; }
        .stat-info p { font-size: 0.8rem; }
        
        .section-title { font-size: 1.25rem; }
        
        .search-input { padding: 8px 12px; font-size: 0.85rem; }
        .search-btn { padding: 0 12px; }
        .filter-dropdown select { padding: 8px 12px; font-size: 0.85rem; }
        
        .map-section { padding: 15px; margin-bottom: 20px; }
        .map-container { height: 250px; border-radius: 6px; }
        
        .map-btn { width: 24px; height: 24px; }
        .map-btn i { font-size: 10px; }
        
        .data-table th, .data-table td { padding: 8px 6px; font-size: 0.75rem; }
        
        .btn { padding: 5px 8px; font-size: 0.75rem; }
        .btn-sm { padding: 3px 6px; font-size: 0.7rem; }
        
        .empty-state { padding: 20px 10px; }
        .empty-state i { font-size: 1.75rem; margin-bottom: 10px; }
        .empty-state h3 { font-size: 1rem; }
        
        /* Further mobile optimizations */
        .map-container {
            height: 250px;
            max-width: 100vw;
        }
        
        .data-table th, .data-table td {
            padding: 6px 4px;
            font-size: 0.7rem;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 3px;
        }
        
        .action-buttons .btn {
            width: 100%;
            margin-bottom: 3px;
        }
    }
    
    /* Mobile swipeable view styles */
    .mobile-swipeable-view {
        display: none;
        padding: 15px;
    }
    
    .swipeable-location-card {
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
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }
    
    .swipeable-actions .btn {
        flex: 1;
        min-width: 80px;
    }
    
    .swipeable-actions form {
        flex: 1;
        margin: 0;
    }
    
    .swipeable-actions form .btn {
        width: 100%;
    }
    
    /* Mobile responsiveness improvements */
    @media (max-width: 768px) {
        .desktop-table {
            display: none;
        }
        
        .mobile-swipeable-view {
            display: block;
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
@endsection

@section('content')
<div class="content-section">
    <div class="page-header">
        <h1 class="page-title">Vet Shop Locations</h1>
        <p class="page-subtitle">Manage veterinarian locations and services</p>
    </div>

    <!-- Map Section -->
    <div class="map-section">
        <div class="section-header">
            <h2 class="section-title">Location Map</h2>
            <a href="{{ route('admin.map.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Location
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

    <!-- Vet Shops List -->
    <div class="content-section">
        @if($shelters->count() > 0)
            <!-- Desktop Table View -->
            <div class="desktop-table">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Address</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shelters as $shelter)
                                <tr>
                                    <td>
                                        <div class="table-cell-content">
                                            <strong>{{ $shelter->name }}</strong>
                                            <small class="text-muted">{{ $shelter->city }}, {{ $shelter->province }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $shelter->type === 'pet_shop' ? 'info' : ($shelter->type === 'veterinarian' ? 'success' : 'warning') }}">
                                            {{ $shelter->getTypeNameAttribute() }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-cell-content">
                                            <small>{{ $shelter->address }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="contact-info">
                                            @if($shelter->phone)
                                                <div><i class="fas fa-phone"></i> {{ $shelter->phone }}</div>
                                            @endif
                                            @if($shelter->email)
                                                <div><i class="fas fa-envelope"></i> {{ $shelter->email }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $shelter->is_active ? 'status-active' : 'status-inactive' }}">
                                            {{ $shelter->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.map.show', $shelter) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.map.edit', $shelter) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.map.destroy', $shelter) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this location? This action cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Swipeable View -->
            <div class="mobile-swipeable-view">
                @foreach($shelters as $shelter)
                    <div class="swipeable-location-card">
                        <div class="swipeable-item">
                            <span class="label">Name:</span>
                            <span class="value">
                                <strong>{{ $shelter->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $shelter->city }}, {{ $shelter->province }}</small>
                            </span>
                        </div>
                        
                        <div class="swipeable-item">
                            <span class="label">Type:</span>
                            <span class="value">
                                <span class="badge badge-{{ $shelter->type === 'pet_shop' ? 'info' : ($shelter->type === 'veterinarian' ? 'success' : 'warning') }}">
                                    {{ $shelter->getTypeNameAttribute() }}
                                </span>
                            </span>
                        </div>
                        
                        <div class="swipeable-item">
                            <span class="label">Address:</span>
                            <span class="value">
                                {{ $shelter->address }}
                            </span>
                        </div>
                        
                        <div class="swipeable-item">
                            <span class="label">Contact:</span>
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
                            <span class="label">Status:</span>
                            <span class="value">
                                <span class="status-badge {{ $shelter->is_active ? 'status-active' : 'status-inactive' }}">
                                    {{ $shelter->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </span>
                        </div>
                        
                        <div class="swipeable-actions">
                            <a href="{{ route('admin.map.show', $shelter) }}" class="btn btn-sm btn-info" title="View">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('admin.map.edit', $shelter) }}" class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.map.destroy', $shelter) }}" method="POST" style="display: inline; margin: 0;" onsubmit="return confirm('Are you sure you want to delete this location? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination-container">  
                {{ $shelters->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
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
                zoom: 15 // Focus more closely on locations
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
    // Create map centered on San Francisco, Agusan del Sur
    const map = L.map('shelterMap', {
        zoomControl: false // Disable zoom controls
    }).setView([8.504588, 125.975800], 15);
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Add markers
    allLocations.forEach(location => {
        if (location.latitude && location.longitude) {
            const lat = parseFloat(location.latitude);
            const lng = parseFloat(location.longitude);
            
            // Use only veterinarian icon for all locations
            let iconClass = 'fas fa-user-md';
            let iconColor = '#10b981';
            
            // Create custom icon
            const customIcon = L.divIcon({
                html: `<div style="background: ${iconColor}; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"><i class="${iconClass}" style="font-size: 12px;"></i></div>`,
                iconSize: [36, 36],
                iconAnchor: [18, 18],
                popupAnchor: [0, -18],
                className: 'custom-marker'
            });
            
            // Create marker
            const marker = L.marker([lat, lng], { icon: customIcon });
            
            // Create popup content
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
                        <i class="fas fa-map-marker-alt" style="color: #667eea; margin-right: 6px;"></i>
                        ${location.address}<br>
                        ${location.city}, ${location.province}
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-phone" style="color: #667eea; margin-right: 6px;"></i>
                        ${location.phone || 'Not provided'}
                    </div>
                    ${location.email ? `
                        <div style="margin-bottom: 12px; color: #4b5563;">
                            <i class="fas fa-envelope" style="color: #667eea; margin-right: 6px;"></i>
                            ${location.email}
                        </div>
                    ` : ''}
                    <div style="display: flex; gap: 5px; margin-top: 12px;">
                        <a href="${baseUrl}/${location.id}" style="background: #667eea; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            marker.addTo(map);
        }
    });
    
    // Store reference to map
    window.shelterMap = map;
    
    // Initialize fullscreen functionality manually if SharedMap is not available
    initFullscreenFunctionality();
}

function initFullscreenFunctionality() {
    const fullscreenBtn = document.getElementById('fullscreen-btn');
    const exitFullscreenBtn = document.getElementById('exit-fullscreen-btn');
    const fullscreenOverlay = document.getElementById('fullscreen-overlay');
    
    if (fullscreenBtn) {
        // Add both click and touch events for better mobile support
        fullscreenBtn.addEventListener('click', handleFullscreenToggle);
        fullscreenBtn.addEventListener('touchstart', handleFullscreenToggle);
    }
    
    if (exitFullscreenBtn) {
        // Add both click and touch events for better mobile support
        exitFullscreenBtn.addEventListener('click', exitFullscreen);
        exitFullscreenBtn.addEventListener('touchstart', exitFullscreen);
    }
    
    if (fullscreenOverlay) {
        // Add both click and touch events for better mobile support
        fullscreenOverlay.addEventListener('click', handleOverlayClick);
        fullscreenOverlay.addEventListener('touchstart', handleOverlayClick);
    }
    
    // Exit fullscreen on ESC key
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
        zoomControl: false // Disable zoom controls
    }).setView([8.504588, 125.975800], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(window.fullscreenMap);
    
    // Add markers to fullscreen map
    mapLocations.forEach(location => {
        if (location.latitude && location.longitude) {
            const lat = parseFloat(location.latitude);
            const lng = parseFloat(location.longitude);
            
            // Use only veterinarian icon for all locations
            let iconClass = 'fas fa-user-md';
            let iconColor = '#10b981';
            
            // Create custom icon
            const customIcon = L.divIcon({
                html: `<div style="background: ${iconColor}; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"><i class="${iconClass}" style="font-size: 12px;"></i></div>`,
                iconSize: [36, 36],
                iconAnchor: [18, 18],
                popupAnchor: [0, -18],
                className: 'custom-marker'
            });
            
            // Create marker
            const marker = L.marker([lat, lng], { icon: customIcon });
            
            // Create popup content
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
                        <i class="fas fa-map-marker-alt" style="color: #667eea; margin-right: 6px;"></i>
                        ${location.address}<br>
                        ${location.city}, ${location.province}
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-phone" style="color: #667eea; margin-right: 6px;"></i>
                        ${location.phone || 'Not provided'}
                    </div>
                    ${location.email ? `
                        <div style="margin-bottom: 12px; color: #4b5563;">
                            <i class="fas fa-envelope" style="color: #667eea; margin-right: 6px;"></i>
                            ${location.email}
                        </div>
                    ` : ''}
                    <div style="display: flex; gap: 5px; margin-top: 12px;">
                        <a href="${baseUrl}/${location.id}" style="background: #667eea; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            marker.addTo(window.fullscreenMap);
        }
    });
    
    // Add click handler to exit fullscreen when clicking on the map
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