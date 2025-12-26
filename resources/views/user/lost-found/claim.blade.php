@extends('layouts.app')

@section('content')
<div class="py-4">
    <div class="container">
        <!-- Header -->
        <div class="mb-4 animate-fade-in">
            <a href="{{ route('lost-found.show', $lostFound) }}" class="btn btn-link text-decoration-none mb-3">
                <i class="fas fa-arrow-left me-2"></i>Back to Listing
            </a>
            <h1 class="h2 fw-bold">Submit Claim Request</h1>
            <p class="text-muted">Provide verification details to prove ownership of this pet</p>
        </div>

        <!-- Pet Info Card -->
        <div class="card border-0 shadow-sm mb-4 animate-slide-up">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-3 col-12">
                        @if($lostFound->image_path)
                            <img src="{{ Storage::url($lostFound->image_path) }}" 
                                 alt="{{ $lostFound->pet_name }}" 
                                 class="img-fluid rounded" style="height: 150px; width: 100%; object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                                <i class="fas fa-image fa-3x text-secondary"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-9 col-12">
                        <h3 class="h5 fw-bold">{{ $lostFound->pet_name ?? 'Unknown Pet' }}</h3>
                        <div class="mt-2">
                            <p class="mb-2 small">
                                <strong>Type:</strong> {{ ucfirst($lostFound->pet_type) }} 
                                @if($lostFound->breed)
                                    <span class="text-muted">•</span> {{ $lostFound->breed }}
                                @endif
                            </p>
                            <p class="mb-2 small">
                                <strong>Status:</strong> {{ ucfirst($lostFound->type) }}
                            </p>
                            <p class="mb-0 small">
                                <strong>Location:</strong> {{ $lostFound->location }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Claim Form -->
        <div class="card border-0 shadow-sm animate-slide-up" style="animation-delay: 0.1s;">
            <div class="card-body p-4">
                <form action="{{ route('lost-found.claim.store', $lostFound) }}" method="POST" enctype="multipart/form-data" id="claimForm">
                    @csrf

                    <h2 class="h5 fw-bold mb-4">Verification Details</h2>

                    <!-- Proof of Ownership -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Proof of Ownership <span class="text-danger">*</span>
                        </label>
                        <textarea name="proof_description" 
                                  rows="5" 
                                  required
                                  class="form-control"
                                  placeholder="Provide specific details that prove ownership (e.g., microchip number, unique markings, medical history, vaccination records, distinctive behaviors, etc.)"></textarea>
                        <div class="form-text">Include as many specific details as possible. This information will be used to verify your ownership.</div>
                    </div>

                    <!-- Contact Information -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Contact Number <span class="text-danger">*</span>
                        </label>
                        <input type="tel" 
                               name="contact_number" 
                               required
                               class="form-control"
                               placeholder="+63 XXX XXX XXXX"
                               value="{{ auth()->user()->phone ?? '' }}">
                        <div class="form-text">We'll use this number to contact you for verification and coordination.</div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Additional Information</label>
                        <textarea name="additional_notes" 
                                  rows="4" 
                                  class="form-control"
                                  placeholder="Any other information that may help verify your claim or coordinate the pet's return"></textarea>
                    </div>

                    <!-- Supporting Documents -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Supporting Documents <span class="text-muted fw-normal">(Optional)</span>
                        </label>
                        <div class="border border-2 border-dashed rounded p-4 text-center bg-light">
                            <input type="file" 
                                   name="supporting_documents[]" 
                                   multiple
                                   accept="image/*,.pdf"
                                   class="d-none"
                                   id="fileInput">
                            <label for="fileInput" class="cursor-pointer d-block">
                                <i class="fas fa-cloud-upload-alt fa-3x text-secondary mb-2"></i>
                                <p class="mb-1">Click to upload files</p>
                                <p class="text-muted small mb-0">PNG, JPG, PDF up to 10MB each</p>
                            </label>
                        </div>
                        <div class="form-text">Upload photos with your pet, veterinary records, adoption papers, or any documents proving ownership.</div>
                        <div id="fileList" class="mt-3"></div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-3 pt-4 border-top">
                        <a href="{{ route('lost-found.show', $lostFound) }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            Submit Claim Request
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Information Box -->
        <div class="alert alert-info mt-4 animate-slide-up" style="animation-delay: 0.2s;" role="alert">
            <div class="d-flex align-items-start">
                <i class="fas fa-info-circle me-3 mt-1"></i>
                <div>
                    <h6 class="fw-bold mb-3">Claim Verification Process</h6>
                    <ol class="mb-0 ps-3">
                        <li class="mb-2">Your claim will be reviewed by the person who reported finding the pet</li>
                        <li class="mb-2">You may be contacted for additional verification or to answer questions</li>
                        <li class="mb-2">Admin may facilitate the verification and coordinate the process</li>
                        <li class="mb-0">Once verified, you'll be notified to arrange safe retrieval of your pet</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slide-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.5s ease-out;
    }
    
    .animate-slide-up {
        animation: slide-up 0.6s ease-out;
    }
    
    .cursor-pointer {
        cursor: pointer;
    }
</style>

@push('scripts')
<script type="module">
    import { showLoading, closeLoading, showSuccess, showError } from '/js/sweetalert-config.js';

    // File upload preview
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');
    
    fileInput.addEventListener('change', function(e) {
        fileList.innerHTML = '';
        const files = Array.from(e.target.files);
        
        files.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'd-flex align-items-center justify-content-between p-3 bg-light rounded border mb-2';
            fileItem.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-file text-secondary me-3"></i>
                    <div>
                        <p class="mb-0 small fw-medium">${file.name}</p>
                        <p class="mb-0 text-muted" style="font-size: 0.75rem;">${(file.size / 1024).toFixed(2)} KB</p>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;
            fileList.appendChild(fileItem);
        });
    });

    // Form submission
    document.getElementById('claimForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        showLoading('Submitting your claim request...');
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            closeLoading();
            if (data.success) {
                showSuccess(data.message || 'Claim submitted successfully!').then(() => {
                    window.location.href = data.redirect || '{{ route("lost-found.show", $lostFound) }}';
                });
            } else {
                showError(data.message || 'Failed to submit claim');
            }
        })
        .catch(error => {
            closeLoading();
            console.error('Error:', error);
            showError('An error occurred while submitting your claim');
        });
    });
</script>
@endpush
@endsection
