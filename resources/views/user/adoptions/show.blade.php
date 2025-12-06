@extends('layouts.app')

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
                            @if($adoption->isAvailable())
                                <div class="alert alert-info mt-3" role="alert">
                                    You can adopt this pet by clicking the "Adopt Pet" button below.
                                </div>
                            @elseif($adoption->hasPendingRequest() && $adoption->pendingRequest()->adopter_id == auth()->id())
                                <div class="alert alert-info mt-3" role="alert">
                                    Your adoption request is pending approval from the pet owner.
                                </div>
                            @elseif($adoption->hasApprovedRequest() && $adoption->adoptionRequests()->where('status', 'approved')->where('adopter_id', auth()->id())->exists())
                                <div class="alert alert-success mt-3" role="alert">
                                    Your adoption request has been approved! Click "Complete Adoption" to finalize the process.
                                    <br><small>Both you and the pet owner can now see this adoption in your history.</small>
                                </div>
                            @elseif($adoption->hasApprovedRequest())
                                <div class="alert alert-warning mt-3" role="alert">
                                    This pet has already been approved for adoption by another user.
                                </div>
                            @elseif($adoption->adoptionRequests()->where('status', 'rejected')->where('adopter_id', auth()->id())->exists())
                                <div class="alert alert-danger mt-3" role="alert">
                                    Your adoption request has been rejected by the pet owner.
                                </div>
                            @else
                                <div class="alert alert-info mt-3" role="alert">
                                    An adoption request for this pet is pending approval.
                                </div>
                            @endif
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
                            <div class="mt-3">
                                <div class="d-flex gap-2 flex-wrap adoption-detail-buttons">
                                    <!-- Edit button -->
                                    <a href="{{ route('adoptions.edit', $adoption) }}" class="btn btn-warning edit-btn" role="button" aria-label="Edit adoption post for {{ $adoption->pet_name }}">
                                        <i class="fas fa-edit me-2" aria-hidden="true"></i>Edit
                                    </a>
                                    
                                    <!-- Delete button -->
                                    <form action="{{ route('adoptions.destroy', $adoption) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger delete-btn" 
                                                onclick="return confirm('Are you sure you want to delete the adoption post for {{ $adoption->pet_name }}? This action cannot be undone.')"
                                                aria-label="Delete adoption post for {{ $adoption->pet_name }}">
                                            <i class="fas fa-trash me-2" aria-hidden="true"></i>Delete
                                        </button>
                                    </form>
                                    
                                    <a href="{{ route('adoptions.index') }}" class="btn btn-secondary ms-auto back-btn" role="button" aria-label="Back to Adoptions">
                                        Back to Adoptions
                                    </a>
                                </div>
                            </div>
                        @elseif($adoption->hasPendingRequest() && $adoption->pendingRequest()->adopter_id == auth()->id())
                            <!-- Adopter actions for pending request -->
                            <div class="mt-3">
                                <a href="{{ route('adoptions.index') }}" class="btn btn-secondary back-btn" role="button" aria-label="Back to Adoptions">Back to Adoptions</a>
                            </div>
                        @elseif($adoption->hasApprovedRequest() && $adoption->adoptionRequests()->where('status', 'approved')->where('adopter_id', auth()->id())->exists())
                            <!-- Adopter actions for approved request -->
                            <div class="d-flex gap-2 flex-wrap mt-3 adoption-detail-buttons">
                                <form action="{{ route('adoptions.complete', $adoption) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success adopt-btn" 
                                            onclick="return confirm('Are you sure you want to complete the adoption of {{ $adoption->pet_name }}?')"
                                            aria-label="Complete adoption of {{ $adoption->pet_name }}">
                                        <i class="fas fa-paw me-2" aria-hidden="true"></i>Complete Adoption
                                    </button>
                                </form>
                                <a href="{{ route('adoptions.index') }}" class="btn btn-secondary back-btn" role="button" aria-label="Back to Adoptions">
                                    Back to Adoptions
                                </a>
                            </div>
                        @elseif($adoption->hasApprovedRequest())
                            <!-- Adopter actions when pet is approved for someone else -->
                            <div class="alert alert-warning mt-3" role="alert">
                                This pet has already been approved for adoption by another user.
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('adoptions.index') }}" class="btn btn-secondary back-btn" role="button" aria-label="Back to Adoptions">Back to Adoptions</a>
                            </div>
                        @elseif($adoption->adoptionRequests()->where('status', 'rejected')->where('adopter_id', auth()->id())->exists())
                            <!-- Adopter actions for rejected request -->
                            <div class="d-flex gap-2 flex-wrap mt-3 adoption-detail-buttons">
                                <form action="{{ route('adoptions.adopt', $adoption) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success adopt-btn" 
                                            onclick="return confirm('Are you sure you want to re-request adoption of {{ $adoption->pet_name }}?')"
                                            aria-label="Re-request adoption of {{ $adoption->pet_name }}">
                                        <i class="fas fa-paw me-2" aria-hidden="true"></i>Re-request Adoption
                                    </button>
                                </form>
                                <a href="{{ route('adoptions.index') }}" class="btn btn-secondary back-btn" role="button" aria-label="Back to Adoptions">
                                    Back to Adoptions
                                </a>
                            </div>
                        @elseif($adoption->isAvailable())
                            <!-- Other user actions -->
                            <div class="d-flex gap-2 flex-wrap mt-3 adoption-detail-buttons">
                                <form action="{{ route('adoptions.adopt', $adoption) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success adopt-btn" 
                                            onclick="return confirm('Are you sure you want to adopt {{ $adoption->pet_name }}?')"
                                            aria-label="Adopt {{ $adoption->pet_name }}">
                                        <i class="fas fa-paw me-2" aria-hidden="true"></i>Adopt Pet
                                    </button>
                                </form>
                                <a href="{{ route('adoptions.index') }}" class="btn btn-secondary back-btn" role="button" aria-label="Back to Adoptions">
                                    Back to Adoptions
                                </a>
                            </div>
                        @else
                            <div class="mt-3">
                                <a href="{{ route('adoptions.index') }}" class="btn btn-secondary back-btn" role="button" aria-label="Back to Adoptions">Back to Adoptions</a>
                            </div>
                        @endif
                    @else
                        <div class="mt-3">
                            <a href="{{ route('adoptions.index') }}" class="btn btn-secondary back-btn" role="button" aria-label="Back to Adoptions">Back to Adoptions</a>
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
                            <span class="badge bg-success">Verified Veterinarian</span>
                        @elseif($adoption->uploader_type === 'user')
                            <span class="badge bg-primary">Pet Parent</span>
                        @else
                            <span class="badge bg-secondary">Unknown</span>
                        @endif
                    </p>
                    <!-- Add more owner information as needed -->
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 id="process-heading">Adoption Process</h5>
                </div>
                <div class="card-body">
                    <ol aria-labelledby="process-heading">
                        <li>Review the pet's information</li>
                        <li>Click the "Adopt Pet" button</li>
                        <li>Wait for owner approval (history created at this point)</li>
                        <li>Contact the owner to arrange pickup</li>
                        <li>Complete the adoption process</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media (min-width: 768px) {
    /* Desktop button size reduction */
    .adopt-btn,
    .approve-btn,
    .reject-btn,
    .back-btn,
    .edit-btn,
    .delete-btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        min-height: 36px;
    }
    
    /* Align back button to the right on desktop */
    .adoption-detail-buttons {
        justify-content: flex-start;
    }
    
    .back-btn {
        margin-left: auto;
    }
}

@media (max-width: 767.98px) {
    /* Mobile button size reduction */
    .adopt-btn,
    .approve-btn,
    .reject-btn,
    .back-btn,
    .edit-btn,
    .delete-btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        min-height: 32px;
    }
    
    .adoption-detail-buttons {
        gap: 0.5rem !important;
        flex-direction: column;
        align-items: stretch;
    }
    
    .approve-btn,
    .reject-btn,
    .back-btn,
    .edit-btn,
    .delete-btn {
        width: 100%;
    }
}

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