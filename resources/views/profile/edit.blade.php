<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - PawPortal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white d-flex align-items-center rounded-top-4">
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

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf

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

                <button class="btn btn-primary w-100 mb-2">
                    <i class="fa-solid fa-save me-1"></i> Update Profile
                </button>
            </form>

            <!-- Dynamic Back Button -->
            <a href="
                @if(Auth::user()->role == 'admin')
                    {{ route('admin.dashboard') }}
                @elseif(Auth::user()->role == 'user')
                    {{ route('pet.multipet.index') }}
                @elseif(Auth::user()->role == 'vet')
                    {{ route('vet.appointments') }}
                @else
                    {{ url('/') }}
                @endif
            " class="btn btn-outline-secondary w-100">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
