@extends('layouts.admin')

@section('title', 'My Profile - PawPortal Admin')

@section('styles')
<style>
    .profile-card {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border: none;
    }
    .profile-header {
        background-color: #e74c3c !important;
        color: white;
    }
    .btn-danger {
        background-color: #e74c3c;
        border-color: #e74c3c;
    }
    .btn-danger:hover {
        background-color: #c0392b;
        border-color: #c0392b;
    }
    .form-label {
        font-weight: 600;
        color: #333;
    }
    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="card profile-card shadow-lg border-0 rounded-4">
        <div class="card-header profile-header bg-danger text-white d-flex align-items-center rounded-top-4">
            <i class="fa-solid fa-user-circle me-2 fs-4"></i>
            <h4 class="mb-0">My Profile</h4>
        </div>
        <div class="card-body p-4">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="fa-solid fa-user me-1"></i>Name</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="fa-solid fa-envelope me-1"></i>Email (read-only)</label>
                    <input type="email" value="{{ $user->email }}" class="form-control" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="fa-solid fa-phone me-1"></i>Phone</label>
                    <input type="text" name="phone" value="{{ $user->phone }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="fa-solid fa-location-dot me-1"></i>Address</label>
                    <textarea name="address" class="form-control" rows="3">{{ $user->address }}</textarea>
                </div>

                <button class="btn btn-danger w-100 mb-2">
                    <i class="fa-solid fa-save me-1"></i> Update Profile
                </button>
            </form>

            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary w-100">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection