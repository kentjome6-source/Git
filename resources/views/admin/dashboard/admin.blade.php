@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>Admin Dashboard</h1>
            <p>Welcome to the PawPortal Admin Dashboard. Manage the platform efficiently using the options below.</p>
            
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                    <div class="card h-100 bg-primary text-white">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <h5 class="card-title">Total Users</h5>
                            <h2 class="mb-0">{{ $totalUsers ?? 0 }}</h2>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                    <div class="card h-100 bg-success text-white">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <h5 class="card-title">Total Pets</h5>
                            <h2 class="mb-0">{{ $totalPets ?? 0 }}</h2>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                    <div class="card h-100 bg-info text-white">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <h5 class="card-title">Adoptions</h5>
                            <h2 class="mb-0">{{ $totalAdoptions ?? 0 }}</h2>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                    <div class="card h-100 bg-warning text-white">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <h5 class="card-title">Lost & Found</h5>
                            <h2 class="mb-0">{{ $totalLostFound ?? 0 }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Cards -->
            <div class="row">
                <!-- Adoptions Card -->
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Adoption History</h5>
                            <p class="card-text">View complete adoption history and manage requests.</p>
                            <a href="{{ route('admin.adoptions.index') }}" class="btn btn-primary mt-auto">View History</a>
                        </div>
                    </div>
                </div>
                
                <!-- User Management Card -->
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">User Management</h5>
                            <p class="card-text">Manage registered users and veterinarians.</p>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-primary mt-auto">Manage Users</a>
                        </div>
                    </div>
                </div>
                
                <!-- Pet Management Card -->
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Pet Management</h5>
                            <p class="card-text">View and manage all registered pets.</p>
                            <a href="{{ route('admin.pets.index') }}" class="btn btn-primary mt-auto">Manage Pets</a>
                        </div>
                    </div>
                </div>
                
                <!-- Lost & Found Card -->
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Lost & Found</h5>
                            <p class="card-text">Manage lost and found pet listings.</p>
                            <a href="{{ route('admin.lost-found.index') }}" class="btn btn-primary mt-auto">Manage Listings</a>
                        </div>
                    </div>
                </div>
                
                <!-- Map Management Card -->
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Map Management</h5>
                            <p class="card-text">Manage shelter locations and map settings.</p>
                            <a href="{{ route('admin.map.index') }}" class="btn btn-primary mt-auto">Manage Map</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection