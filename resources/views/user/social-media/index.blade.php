@extends('layouts.app')

@section('title', 'Furparent Social Media')

@section('content')
<div class="social-page">
    <div class="container-fluid px-4 py-5">
        <!-- Page Header -->
        <div class="page-header mb-5">
            <div class="header-content">
                <div class="header-text">
                    <span class="label">Community</span>
                    <h1 class="page-title">Furparent Social Feed</h1>
                    <p class="page-subtitle">Share moments and connect with fellow pet parents</p>
                </div>
                <a href="{{ route('social-media.create') }}" class="btn-create-post">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Create Post</span>
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert-success-custom mb-4">
                <div class="alert-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <div class="alert-content">{{ session('success') }}</div>
                <button type="button" class="alert-close" data-bs-dismiss="alert">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Posts Feed -->
        <div class="posts-container">
            @if($posts->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                            <line x1="9" y1="9" x2="9.01" y2="9"></line>
                            <line x1="15" y1="9" x2="15.01" y2="9"></line>
                        </svg>
                    </div>
                    <h3 class="empty-title">No posts yet</h3>
                    <p class="empty-text">Be the first to share your pet's story!</p>
                    {{-- <a href="{{ route('social-media.create') }}" class="btn-empty-action">
                        Create First Post
                    </a> --}}
                </div>
            @else
                <div class="posts-feed">
                    @foreach($posts as $post)
                        <div class="post-card">
                            <!-- Post Header -->
                            <div class="post-header">
                                <div class="author-info">
                                    @if($post->user->profile_picture_path)
                                        <img src="{{ $post->user->profile_picture_url }}" 
                                             alt="{{ $post->user->name }}" 
                                             class="author-avatar">
                                    @else
                                        <div class="author-avatar-placeholder">
                                            {{ substr($post->user->name, 0, 2) }}
                                        </div>
                                    @endif
                                    <div class="author-details">
                                        <h3 class="author-name">{{ $post->user->name }}</h3>
                                        <span class="post-time">{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                
                                @if($post->user_id == Auth::id())
                                    <div class="post-actions">
                                        <button class="btn-post-menu" type="button" data-bs-toggle="dropdown">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="1"></circle>
                                                <circle cx="12" cy="5" r="1"></circle>
                                                <circle cx="12" cy="19" r="1"></circle>
                                            </svg>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item delete-item" href="#" 
                                                   onclick="event.preventDefault(); if(confirm('Delete this post?')) { document.getElementById('delete-post-{{ $post->id }}').submit(); }">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                    Delete
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
                            
                            <!-- Post Content -->
                            <div class="post-content">
                                <p class="post-text">{{ $post->content }}</p>
                                
                                @if($post->image_path)
                                    <div class="post-image-wrapper">
                                        <img src="{{ $post->image_url }}" 
                                             alt="Post image" 
                                             class="post-image">
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Post Footer -->
                            <div class="post-footer">
                                <button class="btn-like {{ $post->isLikedByUser(Auth::id()) ? 'active' : '' }}" 
                                        data-post-id="{{ $post->id }}">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                    </svg>
                                    <span class="like-count">{{ $post->likes()->count() }}</span>
                                </button>
                                
                                <a href="{{ route('social-media.show', $post) }}" class="btn-comment">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                    <span class="comment-count">{{ $post->comments()->count() }}</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    :root {
        --slate: #0f172a;
        --slate-light: #1e293b;
        --blue: #3b82f6;
        --purple: #8b5cf6;
        --red: #ef4444;
        --gray: #64748b;
        --gray-light: #f1f5f9;
        --gray-lighter: #f8fafc;
    }

    .social-page {
        font-family: 'Sora', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--gray-lighter);
        min-height: 100vh;
    }

    /* Page Header */
    .page-header {
        animation: fadeInDown 0.6s ease-out;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 24px;
    }

    .label {
        display: block;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--blue);
        margin-bottom: 12px;
        font-weight: 600;
    }

    .page-title {
        font-size: clamp(2rem, 4vw, 2.75rem);
        font-weight: 700;
        color: var(--slate);
        margin-bottom: 8px;
        letter-spacing: -0.02em;
    }

    .page-subtitle {
        font-size: 1.05rem;
        color: var(--gray);
    }

    .btn-create-post {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: var(--purple);
        color: white;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
    }

    .btn-create-post:hover {
        background: #7c3aed;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        color: white;
    }

    /* Success Alert */
    .alert-success-custom {
        background: #d1fae5;
        border: 1px solid #a7f3d0;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        animation: slideDown 0.4s ease-out;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-icon {
        flex-shrink: 0;
        color: #059669;
    }

    .alert-content {
        flex: 1;
        color: #065f46;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .alert-close {
        flex-shrink: 0;
        background: none;
        border: none;
        color: #059669;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        transition: opacity 0.2s;
    }

    .alert-close:hover {
        opacity: 0.7;
    }

    /* Posts Container */
    .posts-container {
        max-width: 800px;
        margin: 0 auto;
        animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Post Card */
    .post-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .post-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }

    /* Post Header */
    .post-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
    }

    .author-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .author-avatar,
    .author-avatar-placeholder {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .author-avatar {
        object-fit: cover;
    }

    .author-avatar-placeholder {
        background: linear-gradient(135deg, var(--blue), var(--purple));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.1rem;
        text-transform: uppercase;
    }

    .author-name {
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--slate);
        margin-bottom: 2px;
    }

    .post-time {
        font-size: 0.85rem;
        color: var(--gray);
    }

    .btn-post-menu {
        background: none;
        border: none;
        color: var(--gray);
        padding: 8px;
        cursor: pointer;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .btn-post-menu:hover {
        background: var(--gray-light);
    }

    .dropdown-menu {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 8px;
    }

    .dropdown-item {
        border-radius: 6px;
        padding: 10px 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .delete-item {
        color: var(--red);
    }

    .dropdown-item:hover {
        background: var(--gray-light);
    }

    /* Post Content */
    .post-content {
        padding: 24px;
    }

    .post-text {
        font-size: 1rem;
        line-height: 1.6;
        color: var(--slate);
        margin-bottom: 20px;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .post-image-wrapper {
        border-radius: 12px;
        overflow: hidden;
        margin-top: 16px;
    }

    .post-image {
        width: 100%;
        height: auto;
        display: block;
    }

    /* Post Footer */
    .post-footer {
        display: flex;
        gap: 12px;
        padding: 16px 24px;
        border-top: 1px solid #e2e8f0;
    }

    .btn-like,
    .btn-comment {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        text-decoration: none;
        background: white;
        border: 1px solid #e2e8f0;
        color: var(--gray);
    }

    .btn-like:hover,
    .btn-comment:hover {
        background: var(--gray-light);
        border-color: #cbd5e1;
    }

    .btn-like svg {
        stroke: currentColor;
    }

    .btn-like.active {
        background: rgba(239, 68, 68, 0.1);
        color: var(--red);
        border-color: var(--red);
    }

    .btn-like.active svg {
        fill: var(--red);
    }

    .btn-comment {
        color: var(--blue);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
    }

    .empty-icon {
        margin-bottom: 24px;
        color: var(--gray);
        opacity: 0.4;
    }

    .empty-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: var(--slate);
        margin-bottom: 12px;
    }

    .empty-text {
        font-size: 1.05rem;
        color: var(--gray);
        margin-bottom: 28px;
    }

    .btn-empty-action {
        display: inline-flex;
        padding: 14px 28px;
        background: var(--purple);
        color: white;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-empty-action:hover {
        background: #7c3aed;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        color: white;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 40px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-create-post {
            width: 100%;
            justify-content: center;
        }

        .page-title {
            font-size: 1.75rem;
        }

        .page-subtitle {
            font-size: 1rem;
        }

        .post-header {
            padding: 16px 20px;
        }

        .post-content {
            padding: 20px;
        }

        .post-footer {
            padding: 12px 20px;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 16px !important;
            padding-right: 16px !important;
        }

        .post-card {
            margin-bottom: 16px;
        }

        .post-header {
            padding: 14px 16px;
        }

        .author-avatar,
        .author-avatar-placeholder {
            width: 40px;
            height: 40px;
            font-size: 0.95rem;
        }

        .author-name {
            font-size: 0.95rem;
        }

        .post-time {
            font-size: 0.8rem;
        }

        .post-content {
            padding: 16px;
        }

        .post-text {
            font-size: 0.95rem;
        }

        .post-footer {
            padding: 12px 16px;
            gap: 8px;
        }

        .btn-like,
        .btn-comment {
            flex: 1;
            justify-content: center;
            padding: 10px 12px;
            font-size: 0.85rem;
        }
    }

    @media (max-width: 400px) {
        .post-header {
            padding: 12px;
        }

        .post-content {
            padding: 12px;
        }

        .post-footer {
            padding: 10px 12px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Like button functionality
    document.querySelectorAll('.btn-like').forEach(button => {
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
                    this.classList.add('active');
                } else {
                    this.classList.remove('active');
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