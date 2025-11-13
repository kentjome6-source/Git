@extends('layouts.app')

@section('title', 'My Profile - PawPortal')

@section('styles')
<style>
    .profile-card {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border: none;
    }
    .profile-header {
        background: linear-gradient(135deg, #5b4b9b 0%, #6a5fac 100%) !important;
        color: white;
    }
    .btn-primary {
        background: linear-gradient(135deg, #5b4b9b 0%, #6a5fac 100%);
        border: none;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #4a3d82 0%, #5b4b9b 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(91, 75, 155, 0.3);
    }
    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }
    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }
    .profile-picture-container {
        text-align: center;
        margin-bottom: 30px;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .profile-picture {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #5b4b9b;
        margin: 0 auto 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }
    .profile-picture:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 16px rgba(91, 75, 155, 0.3);
    }
    .file-input-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        margin: 20px 0 0;
    }
    .file-input-wrapper .btn {
        background: linear-gradient(135deg, #5b4b9b 0%, #6a5fac 100%);
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .file-input-wrapper .btn:hover {
        background: linear-gradient(135deg, #4a3d82 0%, #5b4b9b 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(91, 75, 155, 0.3);
    }
    .file-input-wrapper input[type=file] {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }
    .form-control {
        border: 1px solid #ced4da;
        border-radius: 8px;
        padding: 12px 15px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .form-control:focus {
        border-color: #5b4b9b;
        box-shadow: 0 0 0 0.25rem rgba(91, 75, 155, 0.25);
    }
    .btn-outline-secondary {
        border: 2px solid #6c757d;
        color: #6c757d;
        border-radius: 8px;
        padding: 10px 15px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
        transform: translateY(-2px);
    }
    .card-body {
        padding: 30px;
    }
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #5b4b9b;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #eee;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="card profile-card shadow-lg border-0 rounded-4">
        <div class="card-header profile-header bg-primary text-white d-flex align-items-center rounded-top-4">
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

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="profile-picture-container">
                    <h5 class="section-title">Profile Picture</h5>
                    @if($user->profile_picture_path)
                        <img src="{{ asset('storage/' . $user->profile_picture_path) }}" alt="Profile Picture" class="profile-picture" id="profilePreview">
                    @else
                        <div class="profile-picture d-flex align-items-center justify-content-center bg-primary text-white fs-1" style="width: 150px; height: 150px; border-radius: 50%; margin: 0 auto 20px;" id="profilePreview">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                    @endif
                    <div class="file-input-wrapper">
                        <div class="btn">
                            <i class="fa-solid fa-camera me-1"></i> Choose Profile Picture
                        </div>
                        <input type="file" name="profile_picture" accept="image/*" id="profilePictureInput">
                    </div>
                </div>

                <h5 class="section-title">Personal Information</h5>
                <div class="mb-4">
                    <label class="form-label fw-semibold"><i class="fa-solid fa-user me-1"></i> Name</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold"><i class="fa-solid fa-envelope me-1"></i> Email (read-only)</label>
                    <input type="email" value="{{ $user->email }}" class="form-control" disabled>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold"><i class="fa-solid fa-phone me-1"></i> Phone</label>
                    <input type="text" name="phone" value="{{ $user->phone }}" class="form-control">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold"><i class="fa-solid fa-location-dot me-1"></i> Address</label>
                    <textarea name="address" class="form-control" rows="3">{{ $user->address }}</textarea>
                </div>

                <button class="btn btn-primary w-100 mb-3 py-3">
                    <i class="fa-solid fa-save me-1"></i> Update Profile
                </button>
            </form>

            <a href="{{ route('pet.multipet.index') }}" class="btn btn-outline-secondary w-100 py-2">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>

<script>
document.getElementById('profilePictureInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('profilePreview');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                // Create a new image element
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Profile Picture';
                img.className = 'profile-picture';
                img.id = 'profilePreview';
                // Replace the div with the image
                preview.parentNode.replaceChild(img, preview);
            }
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endsection