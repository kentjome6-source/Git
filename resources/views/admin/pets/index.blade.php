@extends('layouts.admin')

@section('title', 'Manage Pets')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient bg-danger text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-paw me-2"></i>Manage Pets
                    </h4>
                    <a href="{{ route('admin.pets.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus-circle me-1"></i> Add New Pet
                    </a>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($pets->isEmpty())
                        <div class="alert alert-info text-center py-5 rounded-3 shadow-sm">
                            <h5 class="mb-0"><i class="fas fa-dog me-2"></i>No pets found</h5>
                            <small class="text-muted">Click "Add New Pet" to create your first pet listing.</small>
                        </div>
                    @else
                        <!-- Responsive Table View -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Animal Type</th>
                                        <th>Owner</th>
                                        <th>Description</th>
                                        <th>Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pets as $pet)
                                        <tr>
                                            <td>{{ $pet->name }}</td>
                                            <td>{{ $pet->user->name }}</td>
                                            <td>{{ Str::limit($pet->description, 50) }}</td>
                                            <td>
                                                @if($pet->image_path)
                                                    <img src="{{ asset('storage/' . $pet->image_path) }}" alt="{{ $pet->name }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 50px; height: 50px;">
                                                        <i class="fas fa-paw text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.pets.edit', $pet) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i> <span class="d-none d-lg-inline">Edit</span>
                                                    </a>
                                                    <form action="{{ route('admin.pets.destroy', $pet) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this pet?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash"></i> <span class="d-none d-lg-inline">Delete</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Responsive Improvements for All Devices */
    .table-responsive {
        border: none;
    }
    
    /* Desktop Improvements */
    @media (min-width: 769px) {
        .card-header {
            padding: 1rem 1.5rem;
        }
        
        .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .table th,
        .table td {
            padding: 0.75rem !important;
        }
    }
    
    /* Tablet Improvements */
    @media (max-width: 991px) {
        .table th,
        .table td {
            padding: 0.6rem !important;
            font-size: 0.85rem;
        }
        
        .btn {
            padding: 0.3rem 0.6rem;
            font-size: 0.8rem;
        }
    }

    /* Mobile Responsive Improvements */
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem !important;
        }
        
        .card-header {
            padding: 0.75rem 1rem;
        }
        
        .card-title {
            font-size: 1.25rem;
        }
        
        .btn {
            padding: 0.375rem 0.5rem !important;
            font-size: 0.8rem;
            white-space: nowrap;
        }
        
        .table th,
        .table td {
            font-size: 0.8rem;
            padding: 0.5rem !important;
        }
        
        /* Hide text on smaller buttons for mobile */
        .btn span {
            display: none;
        }
        
        /* Adjust image sizes for mobile */
        .table img,
        .table .bg-light {
            width: 40px !important;
            height: 40px !important;
        }
    }

    @media (max-width: 576px) {
        .card-body {
            padding: 0.75rem !important;
        }
        
        .card-header {
            padding: 0.6rem 0.8rem;
        }
        
        .card-title {
            font-size: 1.1rem;
        }
        
        .btn {
            padding: 0.25rem 0.4rem !important;
            font-size: 0.75rem;
        }
        
        .table th,
        .table td {
            font-size: 0.75rem;
            padding: 0.4rem !important;
        }
        
        /* Further reduce image sizes on very small screens */
        .table img,
        .table .bg-light {
            width: 35px !important;
            height: 35px !important;
        }
        
        /* Stack action buttons on very small screens */
        .btn-group {
            flex-direction: column;
            width: 100%;
        }
        
        .btn-group .btn {
            margin-bottom: 0.25rem;
            width: 100%;
        }
        
        .btn-group .btn:last-child {
            margin-bottom: 0;
        }
    }
    
    /* Ensure content fits within viewport */
    .container-fluid {
        max-width: 100%;
        overflow-x: hidden;
    }
    
    /* Prevent horizontal scrolling */
    body {
        overflow-x: hidden;
    }
</style>
@endsection