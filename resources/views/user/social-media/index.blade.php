@extends('layouts.app')

@section('title', 'Furparent Social Media')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden w-100">
                {{-- Header --}}
                <div class="card-header text-white d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 py-3" style="background: #5b4b9b;">
                    <h4 class="mb-0 text-center text-md-start fs-5">
                        <i class="fas fa-users me-2"></i>Furparent Social Media
                    </h4>
                    <a href="{{ route('social-media.create') }}" class="btn btn-light btn-md px-3 px-md-4 fw-semibold w-100 w-md-auto create-post-btn">
                        <i class="fas fa-plus-circle me-1"></i> Create Post
                    </a>
                </div>

                <div class="card-body p-3 p-md-4">
                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Empty Posts --}}
                    @if($posts->isEmpty())
                        <div class="alert alert-info text-center py-5 rounded-3 shadow-sm">
                            <h5 class="mb-0"><i class="fas fa-dog me-2"></i>No posts found 🐶🐱</h5>
                            <small class="text-muted">Be the first to share your pet's story!</small>
                        </div>
                    @else
                        {{-- Posts Feed --}}
                        @foreach($posts as $post)
                            <div class="d-flex justify-content-center">
                                <div class="card mb-4 shadow-sm rounded-3 border-0 w-100" style="max-width: 800px;">
                                    <div class="card-header bg-white d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 py-3">
                                        <div class="d-flex align-items-center mb-2 mb-sm-0">
                                            @if($post->user->profile_picture_path)
                                                <img src="{{ $post->user->profile_picture_url }}" alt="{{ $post->user->name }} Profile Picture" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="profile-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 50px; height: 50px; font-size: 1.2rem; min-width: 50px;">
                                                    {{ substr($post->user->name, 0, 2) }}
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0 fw-bold" style="font-size: 1rem;">{{ $post->user->name }}</h6>
                                                <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        @if($post->user_id == Auth::id())
                                            <div class="dropdown ms-auto">
                                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="#" 
                                                           onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this post?')) { document.getElementById('delete-post-{{ $post->id }}').submit(); }">
                                                            <i class="fas fa-trash-alt me-2"></i>Delete
                                                        </a>
                                                        <form id="delete-post-{{ $post->id }}" 
                                                              action="{{ route('social-media.destroy', $post->id) }}" 
                                                              method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="card-body py-3">
                                        <p class="card-text" style="font-size: 1rem; line-height: 1.6;">{{ $post->content }}</p>
                                        
                                        @if($post->image_path)
                                            <div class="text-center mb-3 post-image-container">
                                                <img src="{{ $post->image_url }}" alt="Post Image" class="img-fluid post-image rounded-3">
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="card-footer bg-white py-3">
                                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2">
                                            <button class="btn btn-sm {{ $post->isLikedByUser(Auth::id()) ? 'btn-danger' : 'btn-outline-danger' }} like-button w-100 w-sm-auto" 
                                                    data-post-id="{{ $post->id }}" style="font-size: 0.85rem; padding: 0.3rem 0.6rem;">
                                                <i class="fas fa-heart me-1"></i>
                                                <span class="like-count">{{ $post->likes()->count() }}</span>
                                            </button>
                                            
                                            <a href="{{ route('social-media.show', $post) }}" class="btn btn-sm btn-outline-primary w-100 w-sm-auto" style="font-size: 0.85rem; padding: 0.3rem 0.6rem;">
                                                <i class="fas fa-comment me-1"></i>
                                                <span class="d-none d-sm-inline">{{ $post->comments()->count() }} Comments</span>
                                                <span class="d-inline d-sm-none">{{ $post->comments()->count() }}</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center mt-4">
                            {{ $posts->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Optimize for small screens */
@media (max-width: 576px) {
    .card-header {
        padding: 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .profile-avatar {
        width: 40px !important;
        height: 40px !important;
        font-size: 1rem !important;
        min-width: 40px !important;
    }
    
    .post-image-container {
        margin-bottom: 1rem;
    }
    
    .post-image {
        border-radius: 0.375rem;
    }
    
    .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
    
    .dropdown-menu {
        min-width: 120px;
    }
    
    .dropdown-item {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
}

@media (max-width: 400px) {
    .card {
        margin: 0 0.25rem;
    }
    
    .card-header {
        padding: 0.75rem;
    }
    
    .card-body {
        padding: 0.75rem;
    }
    
    .btn {
        padding: 0.2rem 0.4rem;
        font-size: 0.75rem;
    }
}

/* Reduce button width on desktop */
@media (min-width: 768px) {
    .create-post-btn {
        width: auto !important;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Like button functionality
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            const likeCountElement = this.querySelector('.like-count');
            
            fetch(`/social-media/${postId}/toggle-like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                likeCountElement.textContent = data.likes_count;
                
                if (data.liked) {
                    this.classList.remove('btn-outline-danger');
                    this.classList.add('btn-danger');
                } else {
                    this.classList.remove('btn-danger');
                    this.classList.add('btn-outline-danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while liking the post.');
            });
        });
    });
});
</script>
@endsection