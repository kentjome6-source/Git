@extends('layouts.app')

@section('title', 'Post Details - Furparent Social Media')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-gradient bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-users me-2"></i>Post Details
                    </h4>
                    <a href="{{ route('social-media.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Feed
                    </a>
                </div>

                <div class="card-body">
                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Post --}}
                    <div class="d-flex justify-content-center">
                        <div class="card mb-4 shadow-sm rounded-3 border-0" style="max-width: 800px; width: 100%;">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    @if($post->user->profile_picture_path)
                                        <img src="{{ asset('storage/' . $post->user->profile_picture_path) }}" alt="{{ $post->user->name }} Profile Picture" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="profile-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 50px; height: 50px; font-size: 1.2rem;">
                                            {{ substr($post->user->name, 0, 2) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0 fw-bold" style="font-size: 1.1rem;">{{ $post->user->name }}</h6>
                                        <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                @if($post->user_id == Auth::id())
                                    <div class="dropdown">
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
                                                      action="{{ route('social-media.destroy', $post) }}" 
                                                      method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="card-body">
                                <p class="card-text" style="font-size: 1.1rem; line-height: 1.6;">{{ $post->content }}</p>
                                
                                @if($post->image_path)
                                    <div class="text-center mb-3 post-image-container">
                                        <img src="{{ $post->image_url }}" alt="Post Image" class="img-fluid post-image">
                                    </div>
                                @endif
                            </div>
                            
                            <div class="card-footer bg-white">
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-sm {{ $post->isLikedByUser(Auth::id()) ? 'btn-danger' : 'btn-outline-danger' }} like-button" 
                                            data-post-id="{{ $post->id }}" style="font-size: 1rem; padding: 0.375rem 0.75rem;">
                                        <i class="fas fa-heart me-1"></i>
                                        <span class="like-count">{{ $post->likes()->count() }}</span> Likes
                                    </button>
                                    
                                    <div class="d-flex align-items-center" style="font-size: 1rem;">
                                        <i class="fas fa-comment text-primary me-1"></i>
                                        {{ $post->comments()->count() }} Comments
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Comments Section --}}
                    <div class="card shadow-sm rounded-3 border-0">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="fas fa-comments me-2"></i>Comments
                            </h5>
                        </div>
                        
                        <div class="card-body">
                            {{-- Add Comment Form --}}
                            <form action="{{ route('comments.store') }}" method="POST" class="mb-4">
                                @csrf
                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                
                                <div class="mb-3">
                                    <textarea class="form-control rounded-3 @error('content') is-invalid @enderror" 
                                              name="content" rows="3" placeholder="Write a comment..." style="font-size: 1rem;">{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary rounded-3" style="font-size: 1rem; padding: 0.5rem 1.5rem;">
                                        <i class="fas fa-paper-plane me-1"></i> Post Comment
                                    </button>
                                </div>
                            </form>
                            
                            {{-- Comments List --}}
                            @if($post->comments->isEmpty())
                                <div class="alert alert-info text-center py-3 rounded-3">
                                    <i class="fas fa-comment-slash me-2"></i>No comments yet. Be the first to comment!
                                </div>
                            @else
                                @foreach($post->comments as $comment)
                                    <div class="card mb-3 rounded-3 border-0 shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-2">
                                                @if($comment->user->profile_picture_path)
                                                    <img src="{{ asset('storage/' . $comment->user->profile_picture_path) }}" alt="{{ $comment->user->name }} Profile Picture" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="profile-avatar bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 40px; height: 40px; font-size: 1rem;">
                                                        {{ substr($comment->user->name, 0, 2) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0 fw-bold" style="font-size: 1rem;">{{ $comment->user->name }}</h6>
                                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                            
                                            <p class="mb-0" style="font-size: 1rem; line-height: 1.6;">{{ $comment->content }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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