@extends('layouts.app')

@section('title', 'My Listings')

@section('styles')
<style>
        
        .page-header {
            text-align: center; margin-bottom: 40px;
        }
        .page-title { font-size: 2.5rem; color: #5b4b9b; margin-bottom: 10px; }
        .page-subtitle { font-size: 1.1rem; color: #666; margin-bottom: 30px; }

        .action-buttons {
            display: flex; gap: 15px; justify-content: center; margin-bottom: 40px;
        }
        .btn {
            padding: 12px 24px; border: none; border-radius: 8px;
            text-decoration: none; font-weight: 600; cursor: pointer;
            transition: 0.2s; display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-primary { background: #5b4b9b; color: #fff; }
        .btn-primary:hover { background: #4a3d7a; }
        .btn-secondary { background: #6c757d; color: #fff; }
        .btn-secondary:hover { background: #5a6268; }

        .listings-table {
            background: #fff; border-radius: 15px; overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        .table-header {
            background: #f8f9fa; padding: 20px; border-bottom: 1px solid #eee;
        }
        .table-title {
            font-size: 1.3rem; font-weight: 600; color: #333;
            display: flex; align-items: center; gap: 10px;
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
        .pet-image {
            width: 80px; height: 80px; border-radius: 8px; overflow: hidden;
            background: #f8f9fa; display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: transform 0.2s;
        }
        .pet-image:hover { transform: scale(1.1); }
        .pet-image img { width: 100%; height: 100%; object-fit: cover; }
        .pet-image .no-image { color: #999; font-size: 1.5rem; }
        .pet-details h4 {
            font-size: 1.1rem; font-weight: 600; color: #333; margin-bottom: 5px;
        }
        .pet-details p {
            font-size: 0.9rem; color: #666;
        }

        .type-badge {
            padding: 4px 12px; border-radius: 20px; font-size: 0.8rem;
            font-weight: 600; text-transform: uppercase;
        }
        .type-badge.lost { background: #e74c3c; color: #fff; }
        .type-badge.found { background: #27ae60; color: #fff; }

        .status-badge {
            padding: 4px 12px; border-radius: 20px; font-size: 0.8rem;
            font-weight: 600; text-transform: uppercase;
        }
        .status-badge.active { background: #3498db; color: #fff; }
        .status-badge.resolved { background: #95a5a6; color: #fff; }
        .status-badge.pending { background: #f39c12; color: #fff; }
        .status-badge.approved { background: #27ae60; color: #fff; }
        .status-badge.rejected { background: #e74c3c; color: #fff; }

        .action-buttons-cell {
            display: flex; gap: 8px; justify-content: flex-end;
        }
        .btn-sm {
            padding: 6px 12px; font-size: 0.8rem; border-radius: 6px;
        }
        .btn-outline { background: transparent; border: 2px solid #5b4b9b; color: #5b4b9b; }
        .btn-outline:hover { background: #5b4b9b; color: #fff; }
        .btn-danger { background: #e74c3c; color: #fff; }
        .btn-danger:hover { background: #c0392b; }
        .btn-success { background: #27ae60; color: #fff; }
        .btn-success:hover { background: #229954; }

        .pagination {
            display: flex; justify-content: center; gap: 10px; margin-top: 40px;
        }
        .pagination a, .pagination span {
            padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px;
            text-decoration: none; color: #666; transition: 0.2s;
        }
        .pagination a:hover { background: #5b4b9b; color: #fff; border-color: #5b4b9b; }
        .pagination .current { background: #5b4b9b; color: #fff; border-color: #5b4b9b; }

        .alert {
            padding: 15px 20px; border-radius: 8px; margin-bottom: 20px;
            font-weight: 500;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }

        .empty-state {
            text-align: center; padding: 60px 20px; color: #666;
        }
        .empty-state i { font-size: 4rem; color: #ddd; margin-bottom: 20px; }
        .empty-state h3 { font-size: 1.5rem; margin-bottom: 10px; }

        .stats-cards {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px; margin-bottom: 40px;
        }
        .stat-card {
            background: #fff; padding: 25px; border-radius: 12px;
            text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .stat-number {
            font-size: 2rem; font-weight: 700; color: #5b4b9b; margin-bottom: 5px;
        }
        .stat-label {
            font-size: 0.9rem; color: #666; text-transform: uppercase;
        }

        /* Image Modal */
        .image-modal {
            display: none; position: fixed; z-index: 1000; left: 0; top: 0;
            width: 100%; height: 100%; background-color: rgba(0,0,0,0.9);
            animation: fadeIn 0.3s ease-in-out;
        }
        .image-modal.show { display: flex; align-items: center; justify-content: center; }
        .modal-content {
            position: relative; max-width: 90%; max-height: 90%;
            animation: zoomIn 0.3s ease-in-out;
        }
        .modal-content img {
            width: 100%; height: auto; max-height: 90vh;
            border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .modal-close {
            position: absolute; top: -40px; right: 0;
            color: #fff; font-size: 2rem; font-weight: bold;
            cursor: pointer; transition: color 0.2s;
        }
        .modal-close:hover { color: #5b4b9b; }
        .modal-info {
            position: absolute; bottom: -50px; left: 0; right: 0;
            text-align: center; color: #fff; font-size: 1.1rem;
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes zoomIn { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }


        /* Responsive styles for mobile */
        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }
            
            .page-subtitle {
                font-size: 1rem;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
            
            .stats-cards {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .stat-card {
                padding: 20px;
            }
            
            .stat-number {
                font-size: 1.5rem;
            }
            
            .table-header {
                padding: 15px;
            }
            
            .table-title {
                font-size: 1.1rem;
            }
            
            /* Convert table to card layout on mobile */
            .table, .table thead, .table tbody, .table th, .table td, .table tr {
                display: block;
            }
            
            .table thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            
            .table tr {
                border: 1px solid #ccc;
                margin-bottom: 15px;
                padding: 15px;
                border-radius: 10px;
                background: #fff;
            }
            
            .table td {
                border: none;
                position: relative;
                padding: 10px 10px 10px 40%;
                text-align: right;
            }
            
            .table td:before {
                content: attr(data-label) ": ";
                position: absolute;
                left: 10px;
                width: 35%;
                text-align: left;
                font-weight: 600;
            }
            
            .pet-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .pet-image {
                width: 70px;
                height: 70px;
            }
            
            .action-buttons-cell {
                flex-direction: column;
                gap: 5px;
                align-items: flex-end;
            }
            
            .btn-sm {
                width: 100%;
                text-align: center;
                justify-content: center;
            }
        }
        
        @media (max-width: 576px) {
            .page-title {
                font-size: 1.75rem;
            }
            
            .stat-card {
                padding: 15px;
            }
            
            .stat-number {
                font-size: 1.3rem;
            }
            
            .stat-label {
                font-size: 0.8rem;
            }
            
            .table td {
                padding: 8px 8px 8px 45%;
            }
            
            .pet-image {
                width: 60px;
                height: 60px;
            }
            
            .pet-details h4 {
                font-size: 1rem;
            }
            
            .pet-details p {
                font-size: 0.85rem;
            }
            
            /* Ensure content fits within mobile screen without horizontal scrolling */
            .container, .container-fluid {
                padding-left: 10px;
                padding-right: 10px;
            }
            
            .listings-table {
                margin-left: 0;
                margin-right: 0;
            }
        }
        
        /* Additional fix to prevent horizontal scrolling */
        body {
            overflow-x: hidden;
        }
        
        .main-content {
            overflow-x: hidden;
        }

    </style>
@endsection

@section('content')
        <div class="page-header">
            <h1 class="page-title">My Listings</h1>
            <p class="page-subtitle">Manage your lost and found pet listings</p>
            
            <div class="action-buttons">
                <a href="{{ route('lost-found.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create a Report
                </a>
                <a href="{{ route('pet.lostfound') }}" class="btn btn-secondary">
                    <i class="fas fa-search-location"></i> Browse All Listings
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($myListings->count() > 0)
            @php
                $totalListings = $myListings->total();
                $activeListings = $myListings->where('is_resolved', false)->count();
                $resolvedListings = $myListings->where('is_resolved', true)->count();
            @endphp

            <div class="stats-cards">
                <div class="stat-card">
                    <div class="stat-number">{{ $totalListings }}</div>
                    <div class="stat-label">Total Listings</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $activeListings }}</div>
                    <div class="stat-label">Active Listings</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $resolvedListings }}</div>
                    <div class="stat-label">Resolved</div>
                </div>
            </div>

            <div class="listings-table">
                <div class="table-header">
                    <h2 class="table-title">
                        <i class="fas fa-list"></i>
                        Your Pet Listings
                    </h2>
                </div>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>Pet</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Date</th>
                            <th>Approval</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($myListings as $listing)
                            <tr>
                                <td data-label="Pet">
                                    <div class="pet-info">
                                        <div class="pet-image" onclick="openImageModal('{{ asset('storage/' . $listing->image_path) }}', '{{ $listing->pet_name }}')">
                                            @if($listing->image_path)
                                                <img src="{{ asset('storage/' . $listing->image_path) }}" alt="{{ $listing->pet_name }}">
                                            @else
                                                <div class="no-image">
                                                    <i class="fas fa-paw"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="pet-details">
                                            <h4>{{ $listing->pet_name }}</h4>
                                            <p>{{ ucfirst($listing->pet_type) }} @if($listing->breed) - {{ $listing->breed }} @endif</p>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Type">
                                    <span class="type-badge {{ $listing->type }}">{{ $listing->type }}</span>
                                </td>
                                <td data-label="Location">{{ $listing->location }}</td>
                                <td data-label="Date">{{ $listing->date_lost_found->format('M d, Y') }}</td>
                                <td data-label="Approval">
                                    <span class="status-badge {{ $listing->is_resolved ? 'resolved' : 'active' }}">
                                        {{ $listing->is_resolved ? 'Resolved' : 'Active' }}
                                    </span>
                                </td>
                                <td data-label="Status">
                                    <div class="action-buttons-cell">
                                        <a href="{{ route('lost-found.show', $listing) }}" class="btn btn-outline btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('lost-found.edit', $listing) }}" class="btn btn-outline btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        @if(!$listing->is_resolved)
                                        <form action="{{ route('lost-found.resolve', $listing) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i> Resolve
                                            </button>
                                        </form>
                                        @endif
                                        <form action="{{ route('lost-found.destroy', $listing) }}" method="POST" style="display: inline;" 
                                              onsubmit="return confirm('Are you sure you want to delete this listing?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $myListings->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-list"></i>
                <h3>No listings yet</h3>
                <p>Start by reporting a lost or found pet to help reunite pets with their families!</p>
            </div>
        @endif

    <!-- Image Modal -->
    <div id="imageModal" class="image-modal" onclick="closeImageModal()">
        <div class="modal-content" onclick="event.stopPropagation()">
            <span class="modal-close" onclick="closeImageModal()">&times;</span>
            <img id="modalImage" src="" alt="">
            <div class="modal-info" id="modalInfo"></div>
        </div>
    </div>

    <script>
        function openImageModal(imageSrc, petName) {
            if (imageSrc && imageSrc !== '{{ asset("storage/") }}') {
                document.getElementById('modalImage').src = imageSrc;
                document.getElementById('modalInfo').textContent = petName;
                document.getElementById('imageModal').classList.add('show');
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            }
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.remove('show');
            document.body.style.overflow = 'auto'; // Restore scrolling
        }

        // Close modal when pressing Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
@endsection