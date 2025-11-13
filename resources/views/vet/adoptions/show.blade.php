@extends('layouts.vet')

@section('title', 'Adoption Details - ' . $adoption->pet_name)

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                @if($adoption->image_path)
                    <img src="{{ asset('storage/' . $adoption->image_path) }}" class="card-img-top" alt="Photo of {{ $adoption->pet_name }}" style="height: 400px; object-fit: cover;" loading="lazy">
                @else
                    <img src="{{ asset('images/pawpatrol.jpg') }}" class="card-img-top" alt="Default image for {{ $adoption->pet_name }}" style="height: 400px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <h2 class="card-title" id="pet-name-heading">{{ $adoption->pet_name }}</h2>
                    
                    <div class="mb-3">
                        @if($adoption->breed)
                        <span class="badge bg-secondary me-1">{{ $adoption->breed }}</span>
                        @endif
                        @if($adoption->age)
                        <span class="badge bg-info me-1">{{ $adoption->age }} years old</span>
                        @endif
                        @if($adoption->gender)
                        <span class="badge bg-primary">{{ ucfirst($adoption->gender) }}</span>
                        @endif
                        @if($adoption->uploader_type === 'vet')
                            <span class="badge bg-success">Vet Upload</span>
                        @endif
                    </div>
                    
                    <h5>Description</h5>
                    @if($adoption->description)
                        <p class="card-text">{{ $adoption->description }}</p>
                    @else
                        <p class="text-muted">No description provided.</p>
                    @endif
                    
                    <p class="card-text">
                        <small class="text-muted">
                            Listed by {{ $adoption->user->name }} on 
                            <time datetime="{{ $adoption->created_at->toIso8601String() }}">{{ $adoption->created_at->format('M d, Y') }}</time>
                        </small>
                    </p>
                    
                    @if(!$adoption->is_adopted)
                        @if($adoption->user_id != auth()->id())
                            <!-- Vets can only view pet details, not adopt -->
                        @else
                            <div class="alert alert-info mt-3" role="alert">
                                This is your pet listing. You cannot adopt your own pet.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning mt-3" role="alert">
                            This pet has already been adopted.
                        </div>
                    @endif
                    
                    <!-- Action Buttons at Bottom -->
                    @if(!$adoption->is_adopted)
                        @if($adoption->user_id == auth()->id())
                            <!-- Pet owner actions -->
                            @if($adoption->hasPendingRequest())
                                <!-- Display adopter information -->
                                @php
                                    $pendingRequest = $adoption->pendingRequest();
                                @endphp
                                @if($pendingRequest && $pendingRequest->adopter)
                                <div class="alert alert-info mt-3" role="alert">
                                    <strong>Pending Adoption Request:</strong> 
                                    {{ $pendingRequest->adopter->name }} ({{ $pendingRequest->adopter->email }}) has requested to adopt this pet.
                                    <br>
                                    <small>Requested on: {{ $pendingRequest->requested_at->format('M d, Y h:i A') }}</small>
                                </div>
                                @endif
                                
                                <div class="d-flex gap-2 flex-wrap mt-3 adoption-detail-buttons">
                                    <form action="{{ route('vet.adoptions.management.approve', $adoption) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-lg" 
                                                onclick="return confirm('Are you sure you want to approve the adoption request for {{ $adoption->pet_name }}?')"
                                                aria-label="Approve adoption request for {{ $adoption->pet_name }}">
                                            <i class="fas fa-check me-2" aria-hidden="true"></i>Approve Request
                                        </button>
                                    </form>
                                    <form action="{{ route('vet.adoptions.management.reject', $adoption) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-lg" 
                                                onclick="return confirm('Are you sure you want to reject the adoption request for {{ $adoption->pet_name }}?')"
                                                aria-label="Reject adoption request for {{ $adoption->pet_name }}">
                                            <i class="fas fa-times me-2" aria-hidden="true"></i>Reject Request
                                        </button>
                                    </form>
                                    <a href="{{ route('vet.adoptions.management.index') }}" class="btn btn-secondary btn-lg back-btn" role="button" aria-label="Back to Adoptions">
                                        Back to Adoptions
                                    </a>
                                </div>
                            @elseif($adoption->hasApprovedRequest())
                                <div class="alert alert-success mt-3" role="alert">
                                    <i class="fas fa-check-circle me-1"></i>
                                    This pet has been approved for adoption.
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('vet.adoptions.management.index') }}" class="btn btn-secondary back-btn" role="button" aria-label="Back to Adoptions">Back to Adoptions</a>
                                </div>
                            @else
                                <div class="mt-3">
                                    <a href="{{ route('vet.adoptions.management.index') }}" class="btn btn-secondary back-btn" role="button" aria-label="Back to Adoptions">Back to Adoptions</a>
                                </div>
                            @endif
                        @else
                            <div class="mt-3">
                                <a href="{{ route('vet.adoptions.management.index') }}" class="btn btn-secondary back-btn" role="button" aria-label="Back to Adoptions">Back to Adoptions</a>
                            </div>
                        @endif
                    @else
                        <div class="mt-3">
                            <a href="{{ route('vet.adoptions.management.index') }}" class="btn btn-secondary back-btn" role="button" aria-label="Back to Adoptions">Back to Adoptions</a>
                        </div>
                    @endif
                    
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 id="owner-info-heading">Pet Owner Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $adoption->user->name }}</p>
                    <p><strong>Email:</strong> {{ $adoption->user->email }}</p>
                    <p><strong>Uploader Type:</strong> 
                        @if($adoption->uploader_type === 'vet')
                            Verified Veterinarian
                        @elseif($adoption->uploader_type === 'user')
                            Pet Parent
                        @else
                            {{ ucfirst($adoption->uploader_type ?? 'Unknown') }}
                        @endif
                    </p>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 id="process-heading">Pet Information</h5>
                </div>
                <div class="card-body">
                    <p>This pet was uploaded for adoption by a verified veterinarian.</p>
                </div>
            </div>
            
            @if($adoption->uploader_type === 'vet' && $adoption->user_id === auth()->id() && !$adoption->is_adopted)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 id="actions-heading">Actions</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('vet.adoptions.management.destroy', $adoption) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" 
                                onclick="return confirm('Are you sure you want to remove this adoption listing?')">
                            <i class="fas fa-trash me-1" aria-hidden="true"></i>Remove Listing
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Focus indicators for keyboard navigation */
.btn:focus, 
a:focus {
    outline: 2px solid #5b4b9b;
    outline-offset: 2px;
}

/* Screen reader only class */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* Improved color contrast */
.text-muted {
    color: #6c757d !important;
}
</style>
@endsection