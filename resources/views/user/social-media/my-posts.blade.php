@extends('layouts.app')

@section('title', 'My Posts - Furparent Social Media')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden w-100">
                {{-- Header --}}
                <div class="card-header bg-gradient bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i>My Posts
                    </h4>
                    <a href="{{ route('social-media.create') }}" class="btn btn-light btn-lg px-4 fw-semibold create-post-btn">
                        <i class="fas fa-plus-circle me-1"></i> Create Post
                    </a>
                </div>

                <div class="card-body p-4">
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
                            <h5 class="mb-0"><i class="fas fa-dog me-2"></i>You haven't created any posts yet 🐶</h5>
                            <small class="text-muted">Share your pet's story with the community!</small>
                        </div>
                    @else
                        {{-- Posts List --}}
                        @foreach($posts as $post)
                            <div class="d-flex justify-content-center">
                                <div class="card mb-4 shadow-sm rounded-3 border-0" style="max-width: 800px; width: 100%;">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted" style="font-size: 1rem;">{{ $post->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('social-media.show', $post) }}">
                                                        <i class="fas fa-eye me-2"></i>View
                                                    </a>
                                                </li>
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
                                            <div class="d-flex align-items-center" style="font-size: 1rem;">
                                                <i class="fas fa-heart text-danger me-1"></i>
                                                {{ $post->likes()->count() }} Likes
                                            </div>
                                            <div class="d-flex align-items-center" style="font-size: 1rem;">
                                                <i class="fas fa-comment text-primary me-1"></i>
                                                {{ $post->comments()->count() }} Comments
                                            </div>
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
@endsection

<style>
/* Reduce button width on desktop */
@media (min-width: 768px) {
    .create-post-btn {
        width: auto !important;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
        font-size: 1rem !important;
    }
}
</style>
