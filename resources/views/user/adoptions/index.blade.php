@extends('layouts.app')

@section('title', 'Pets Available for Adoption')

@section('content')
<div class="adoption-page">
    <div class="container-fluid px-4 py-5">
        <!-- Header Section -->
        <div class="page-header mb-5">
            <div class="header-content">
                <div class="header-text">
                    <span class="label">Adoption Center</span>
                </div>
                <div class="header-actions">
                    <a href="{{ route('adoptions.history') }}" class="btn-secondary-action">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span>History</span>
                    </a>
                    <a href="{{ route('adoptions.create') }}" class="btn-primary-action" data-modal data-modal-title="List Pet for Adoption">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>List Pet</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Adoption Feed -->
        <div class="adoption-grid">
            @forelse($adoptionPets as $adoption)
            <div class="adoption-card">
                <!-- Pet Image -->
                <div class="pet-image-container" onclick="openImageModal('{{ $adoption->image_path ? asset('storage/' . $adoption->image_path) : '' }}')" 
                     role="button" tabindex="0">
                    @if($adoption->image_path)
                        <img src="{{ asset('storage/' . $adoption->image_path) }}" 
                             class="pet-image" 
                             alt="{{ $adoption->pet_name }}" 
                             loading="lazy">
                    @else
                        <div class="pet-image-placeholder">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                                <line x1="9" y1="9" x2="9.01" y2="9"></line>
                                <line x1="15" y1="9" x2="15.01" y2="9"></line>
                            </svg>
                        </div>
                    @endif
                    
                    @if($adoption->uploader_type === 'vet')
                        <span class="vet-badge">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                            </svg>
                            Verified Vet
                        </span>
                    @endif
                </div>
                
                <!-- Card Body -->
                <div class="card-body">
                    <div class="pet-header">
                        <h3 class="pet-name">{{ $adoption->pet_name }}</h3>
                    </div>
                    
                    <div class="pet-tags">
                        @if($adoption->breed)
                            <span class="tag tag-breed">{{ $adoption->breed }}</span>
                        @endif
                        @if($adoption->age)
                            <span class="tag tag-age">{{ $adoption->age }} yrs</span>
                        @endif
                        @if($adoption->gender)
                            <span class="tag tag-gender">{{ ucfirst($adoption->gender) }}</span>
                        @endif
                    </div>
                    
                    @if($adoption->description)
                        <p class="pet-description">{{ Str::limit($adoption->description, 100) }}</p>
                    @endif
                    
                    <!-- Status Alerts -->
                    @if($adoption->user_id == auth()->id() && $adoption->hasPendingRequest())
                        @php
                            $pendingRequest = $adoption->pendingRequest();
                        @endphp
                        @if($pendingRequest && $pendingRequest->adopter)
                            <div class="status-alert status-info">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="16" x2="12" y2="12"></line>
                                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                </svg>
                                <span><strong>{{ $pendingRequest->adopter->name }}</strong> wants to adopt</span>
                            </div>
                        @endif
                    @endif
                    
                    @if($adoption->user_id == auth()->id() && !$adoption->hasPendingRequest() && $adoption->hasApprovedRequest())
                        <div class="status-alert status-success">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <span>Adoption approved</span>
                        </div>
                    @endif
                    
                    @if($adoption->user_id != auth()->id() && $adoption->hasApprovedRequest() && $adoption->adoptionRequests()->where('status', 'approved')->where('adopter_id', auth()->id())->exists())
                        <div class="status-alert status-success">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <span>Your request was approved!</span>
                        </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    <div class="card-actions">
                        <a href="{{ route('adoptions.show', $adoption) }}" class="btn-view-details">
                            View Details
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                        
                        @if($adoption->user_id != auth()->id())
                            @if($adoption->isAvailable())
                                <button type="button" class="btn-adopt w-100" onclick="handleAdoptSubmit({{ $adoption->id }}, '{{ $adoption->pet_name }}')">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                    </svg>
                                    Adopt {{ $adoption->pet_name }}
                                </button>
                            @elseif($adoption->hasPendingRequest() && $adoption->pendingRequest()->adopter_id == auth()->id())
                                <button class="btn-status btn-pending" disabled>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    Pending Approval
                                </button>
                            @elseif($adoption->hasApprovedRequest() && $adoption->adoptionRequests()->where('status', 'approved')->where('adopter_id', auth()->id())->exists())
                                <button class="btn-status btn-approved" disabled>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    Approved
                                </button>
                            @else
                                <button class="btn-status btn-unavailable" disabled>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="15" y1="9" x2="9" y2="15"></line>
                                        <line x1="9" y1="9" x2="15" y2="15"></line>
                                    </svg>
                                    Unavailable
                                </button>
                            @endif
                        @else
                            @if($adoption->hasPendingRequest())
                                <a href="{{ route('adoptions.show', $adoption) }}" class="btn-review">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    Review Request
                                </a>
                            @elseif($adoption->hasApprovedRequest())
                                <button class="btn-status btn-approved" disabled>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    Approved
                                </button>
                            @endif
                        @endif
                    </div>
                    
                    <!-- Card Footer -->
                    <div class="card-footer">
                        <div class="footer-info">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span>{{ $adoption->user->name }}</span>
                        </div>
                        <div class="footer-date">
                            {{ $adoption->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                        <line x1="9" y1="9" x2="9.01" y2="9"></line>
                        <line x1="15" y1="9" x2="15.01" y2="9"></line>
                    </svg>
                </div>
                <h3 class="empty-title">No pets available yet</h3>
                <p class="empty-text">Be the first to list a pet for adoption!</p>
                <a href="{{ route('adoptions.create') }}" class="btn-empty-action">
                    List Your Pet
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pet Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <img id="modalImage" src="" class="w-100" alt="Full size pet image" style="max-height: 80vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

<script>
function openImageModal(src) {
    if (src) {
        document.getElementById('modalImage').src = src;
        var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
        myModal.show();
    }
}

function handleAdoptSubmit(adoptionId, petName) {
    showConfirm(
        `Do you want to submit an adoption application for ${petName}? You'll need to fill out an application form.`,
        'Submit Application?',
        'Continue',
        'Cancel'
    ).then((result) => {
        if (result.isConfirmed) {
            // Redirect to application page or show application form
            window.location.href = `/adoptions/${adoptionId}`;
        }
    });
}
</script>

<style>
    :root {
        --slate: #0f172a;
        --slate-light: #1e293b;
        --blue: #3b82f6;
        --purple: #8b5cf6;
        --green: #10b981;
        --orange: #f59e0b;
        --red: #ef4444;
        --gray: #64748b;
        --gray-light: #f1f5f9;
        --gray-lighter: #f8fafc;
    }

    .adoption-page {
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

    .header-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-secondary-action,
    .btn-primary-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-secondary-action {
        background: white;
        color: var(--slate);
        border: 2px solid #e2e8f0;
    }

    .btn-secondary-action:hover {
        background: var(--gray-light);
        border-color: #cbd5e1;
        color: var(--slate);
    }

    .btn-primary-action {
        background: var(--purple);
        color: white;
        border: 2px solid var(--purple);
    }

    .btn-primary-action:hover {
        background: #7c3aed;
        border-color: #7c3aed;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        color: white;
    }

    /* Adoption Grid */
    .adoption-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
        animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Adoption Card */
    .adoption-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .adoption-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        border-color: var(--blue);
    }

    /* Pet Image */
    .pet-image-container {
        position: relative;
        width: 100%;
        height: 280px;
        overflow: hidden;
        cursor: pointer;
        background: var(--gray-light);
    }

    .pet-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .adoption-card:hover .pet-image {
        transform: scale(1.08);
    }

    .pet-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--blue) 0%, var(--purple) 100%);
        color: white;
    }

    .vet-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(16, 185, 129, 0.95);
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
        backdrop-filter: blur(8px);
    }

    /* Card Body */
    .card-body {
        padding: 24px;
    }

    .pet-header {
        margin-bottom: 12px;
    }

    .pet-name {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--slate);
        margin-bottom: 0;
        letter-spacing: -0.01em;
    }

    .pet-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 16px;
    }

    .tag {
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .tag-breed {
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
    }

    .tag-age {
        background: rgba(59, 130, 246, 0.1);
        color: var(--blue);
    }

    .tag-gender {
        background: rgba(139, 92, 246, 0.1);
        color: var(--purple);
    }

    .pet-description {
        font-size: 0.95rem;
        color: var(--gray);
        line-height: 1.6;
        margin-bottom: 16px;
    }

    /* Status Alerts */
    .status-alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 0.9rem;
        margin-bottom: 16px;
    }

    .status-info {
        background: rgba(59, 130, 246, 0.1);
        color: #1e40af;
    }

    .status-success {
        background: rgba(16, 185, 129, 0.1);
        color: #065f46;
    }

    /* Card Actions */
    .card-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 16px;
    }

    .btn-view-details,
    .btn-adopt,
    .btn-review,
    .btn-status {
        width: 100%;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-view-details {
        background: white;
        color: var(--slate);
        border: 2px solid #e2e8f0;
    }

    .btn-view-details:hover {
        background: var(--gray-light);
        border-color: #cbd5e1;
        color: var(--slate);
    }

    .btn-adopt {
        background: var(--purple);
        color: white;
    }

    .btn-adopt:hover {
        background: #7c3aed;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .btn-review {
        background: var(--blue);
        color: white;
    }

    .btn-review:hover {
        background: #2563eb;
        color: white;
    }

    .btn-status {
        cursor: not-allowed;
        opacity: 0.7;
    }

    .btn-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .btn-approved {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .btn-unavailable {
        background: rgba(100, 116, 139, 0.1);
        color: var(--gray);
    }

    /* Card Footer */
    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 16px;
        border-top: 1px solid #e2e8f0;
        font-size: 0.85rem;
        color: var(--gray);
    }

    .footer-info {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Empty State */
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 80px 20px;
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

    /* Modal */
    .modal-content {
        border-radius: 12px;
        border: none;
    }

    .modal-header {
        border-bottom: 1px solid #e2e8f0;
    }

    .modal-title {
        font-weight: 600;
        color: var(--slate);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .adoption-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }
    }

    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
        }

        .btn-secondary-action,
        .btn-primary-action {
            flex: 1;
            justify-content: center;
        }

        .page-title {
            font-size: 1.75rem;
        }

        .page-subtitle {
            font-size: 1rem;
        }

        .adoption-grid {
            grid-template-columns: 1fr;
        }

        .pet-image-container {
            height: 240px;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 16px !important;
            padding-right: 16px !important;
        }

        .pet-image-container {
            height: 220px;
        }

        .card-body {
            padding: 20px;
        }

        .pet-name {
            font-size: 1.25rem;
        }

        .btn-view-details,
        .btn-adopt,
        .btn-review,
        .btn-status {
            padding: 11px 16px;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 400px) {
        .pet-image-container {
            height: 200px;
        }

        .card-body {
            padding: 16px;
        }

        .pet-name {
            font-size: 1.1rem;
        }
    }
</style>
@endsection