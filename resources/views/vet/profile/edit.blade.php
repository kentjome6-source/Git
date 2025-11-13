@extends('layouts.vet')

@section('title', 'My Profile - PawPortal Vet')

@section('styles')
<style>
    .profile-card {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border: none;
        background-color: #2c2c2c;
        color: #f1f1f1;
    }
    .profile-header {
        background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%) !important;
        color: white;
    }
    .btn-primary {
        background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
        border: none;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #219653 0%, #27ae60 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(39, 174, 96, 0.3);
    }
    .form-label {
        font-weight: 600;
        color: #f1f1f1;
        margin-bottom: 8px;
    }
    .alert-success {
        background-color: #2c5a2d;
        border-color: #3c7a3d;
        color: #d4edda;
    }
    .form-control {
        background-color: #1e1e1e;
        color: #f1f1f1;
        border: 1px solid #444;
        border-radius: 8px;
        padding: 12px 15px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .form-control:focus {
        background-color: #1e1e1e;
        color: #f1f1f1;
        border-color: #27ae60;
        box-shadow: 0 0 0 0.25rem rgba(39, 174, 96, 0.25);
    }
    .form-control:disabled {
        background-color: #333;
        color: #bbb;
    }
    .btn-outline-secondary {
        color: #f1f1f1;
        border: 2px solid #666;
        border-radius: 8px;
        padding: 10px 15px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-outline-secondary:hover {
        background-color: #444;
        color: #f1f1f1;
        border-color: #777;
        transform: translateY(-2px);
    }
    .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
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
        border: 4px solid #27ae60;
        margin: 0 auto 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }
    .profile-picture:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 16px rgba(39, 174, 96, 0.4);
    }
    .file-input-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        margin: 20px 0 0;
    }
    .file-input-wrapper .btn {
        background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .file-input-wrapper .btn:hover {
        background: linear-gradient(135deg, #219653 0%, #27ae60 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(39, 174, 96, 0.3);
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
    .card-body {
        padding: 30px;
    }
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #27ae60;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #444;
    }
    
    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .container {
            padding: 0 8px;
        }
        
        .card {
            margin-bottom: 15px;
        }
        
        .card-body {
            padding: 20px 15px;
        }
        
        .profile-header {
            padding: 15px !important;
        }
        
        .profile-picture {
            width: 120px;
            height: 120px;
            border-width: 3px;
        }
        
        .file-input-wrapper .btn {
            padding: 8px 16px;
            font-size: 0.9rem;
        }
        
        .section-title {
            font-size: 1.1rem;
            margin-bottom: 15px;
            padding-bottom: 8px;
            text-align: center;
        }
        
        .form-label {
            font-size: 0.95rem;
            margin-bottom: 6px;
        }
        
        .form-control {
            padding: 10px 12px;
            font-size: 1rem;
        }
        
        .btn {
            padding: 12px;
            font-size: 1rem;
            margin-bottom: 10px;
        }
        
        .btn-primary {
            margin-bottom: 15px;
        }
    }
    
    @media (max-width: 576px) {
        .container {
            padding: 0 6px;
        }
        
        .card-body {
            padding: 15px 12px;
        }
        
        .profile-picture {
            width: 100px;
            height: 100px;
            border-width: 2px;
        }
        
        .section-title {
            font-size: 1rem;
            margin-bottom: 12px;
            padding-bottom: 6px;
        }
        
        .form-label {
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        
        .form-control {
            padding: 8px 10px;
            font-size: 0.95rem;
        }
        
        .btn {
            padding: 10px;
            font-size: 0.95rem;
            margin-bottom: 8px;
        }
        
        .btn-primary {
            margin-bottom: 12px;
        }
    }
    
    @media (max-width: 400px) {
        .card-body {
            padding: 12px 10px;
        }
        
        .profile-picture {
            width: 80px;
            height: 80px;
        }
        
        .section-title {
            font-size: 0.95rem;
        }
        
        .form-label {
            font-size: 0.85rem;
        }
        
        .form-control {
            padding: 6px 8px;
            font-size: 0.9rem;
        }
        
        .btn {
            padding: 8px;
            font-size: 0.9rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-5 px-1 px-md-2">
    <div class="card profile-card shadow-lg border-0 rounded-4">
        <div class="card-header profile-header text-white d-flex align-items-center rounded-top-4">
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
                        <div class="profile-picture d-flex align-items-center justify-content-center bg-success text-white fs-1" style="width: 150px; height: 150px; border-radius: 50%; margin: 0 auto 20px;" id="profilePreview">
                            {{ 'Vet' }}
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

            <a href="{{ route('vet.records') }}" class="btn btn-outline-secondary w-100 py-2">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Records
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