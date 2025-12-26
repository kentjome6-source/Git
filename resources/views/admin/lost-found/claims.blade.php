@extends('layouts.admin')

@section('title', 'Review Claims - ' . $lostFound->pet_name)

@section('content')
<div class="claims-review-page">
    <div class="container-fluid px-4 py-5">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('admin.lost-found.index') }}" class="btn-back">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Back to Listings
            </a>
        </div>

        <!-- Pet Info -->
        <div class="pet-info-card mb-4">
            <div class="row align-items-center">
                <div class="col-md-2">
                    @if($lostFound->image_path)
                        <img src="{{ asset('storage/' . $lostFound->image_path) }}" alt="{{ $lostFound->pet_name }}" class="pet-image">
                    @else
                        <div class="pet-image-placeholder">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="col-md-10">
                    <div class="badge badge-{{ $lostFound->type }}">{{ ucfirst($lostFound->type) }}</div>
                    <h2 class="pet-name">{{ $lostFound->pet_name }}</h2>
                    <div class="pet-details">
                        <span><strong>Type:</strong> {{ ucfirst($lostFound->pet_type) }}</span>
                        <span><strong>Location:</strong> {{ $lostFound->location }}</span>
                        <span><strong>Date Found:</strong> {{ $lostFound->date_lost_found->format('M d, Y') }}</span>
                        <span><strong>Claims:</strong> {{ $claims->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Claims List -->
        <div class="page-header mb-4">
            <h1 class="page-title">Claims Review</h1>
            <p class="page-subtitle">Review and verify ownership claims for this found pet</p>
        </div>

        @if($claims->count() > 0)
            <div class="claims-grid">
                @foreach($claims as $claim)
                    <div class="claim-card">
                        <div class="claim-header">
                            <div class="claimer-info">
                                <div class="claimer-avatar">
                                    {{ substr($claim->claimer->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="claimer-name">{{ $claim->claimer->name }}</h3>
                                    <p class="claim-date">Submitted {{ $claim->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="status-badge status-{{ $claim->status }}">
                                {{ ucfirst($claim->status) }}
                            </div>
                        </div>

                        <div class="claim-body">
                            <!-- Contact Information -->
                            <div class="info-section">
                                <h4 class="section-title">Contact Information</h4>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="info-label">Email:</span>
                                        <span class="info-value">{{ $claim->claimer->email }}</span>
                                    </div>
                                    @if($claim->claimer->phone)
                                    <div class="info-item">
                                        <span class="info-label">Phone:</span>
                                        <span class="info-value">{{ $claim->claimer->phone }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Proof of Ownership Description -->
                            <div class="info-section">
                                <h4 class="section-title">Proof of Ownership Description</h4>
                                <p class="claim-description">{{ $claim->proof_description }}</p>
                            </div>

                            <!-- Identification Information -->
                            @if($claim->identification_info)
                            <div class="info-section">
                                <h4 class="section-title">Identification Information</h4>
                                <p class="claim-description">{{ $claim->identification_info }}</p>
                            </div>
                            @endif

                            <!-- Proof Images -->
                            @if($claim->proof_images && count($claim->proof_images) > 0)
                                <div class="info-section">
                                    <h4 class="section-title">Proof of Ownership</h4>
                                    <div class="proof-images">
                                        @foreach($claim->proof_images as $image)
                                            <a href="{{ asset('storage/' . $image) }}" target="_blank" class="proof-image">
                                                <img src="{{ asset('storage/' . $image) }}" alt="Proof">
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Vet Verification -->
                            @if($claim->vet_verified_at)
                                <div class="info-section verification-section">
                                    <div class="verification-badge">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                        </svg>
                                        Veterinarian Verified
                                    </div>
                                    <p class="verification-details">
                                        Verified by {{ $claim->vetVerifier->name }} on {{ $claim->vet_verified_at->format('M d, Y') }}
                                    </p>
                                    @if($claim->vet_notes)
                                        <p class="verification-notes"><strong>Notes:</strong> {{ $claim->vet_notes }}</p>
                                    @endif
                                </div>
                            @endif

                            <!-- Admin Review -->
                            @if($claim->status === 'pending')
                                <div class="claim-actions">
                                    <button class="btn btn-approve" onclick="approveClaim({{ $claim->id }})">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        Approve Claim
                                    </button>
                                    <button class="btn btn-reject" onclick="rejectClaim({{ $claim->id }})">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                        Reject Claim
                                    </button>
                                    <button class="btn btn-message" onclick="messageClaimer({{ $claim->claimer_id }})">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                        </svg>
                                        Message Claimer
                                    </button>
                                </div>
                            @elseif($claim->status === 'approved')
                                <div class="review-result success">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <div>
                                        <strong>Claim Approved</strong>
                                        <p>Reviewed by {{ $claim->adminReviewer->name }} on {{ $claim->admin_reviewed_at->format('M d, Y') }}</p>
                                        @if($claim->admin_notes)
                                            <p class="review-notes">{{ $claim->admin_notes }}</p>
                                        @endif
                                    </div>
                                </div>
                            @elseif($claim->status === 'rejected')
                                <div class="review-result rejected">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                    <div>
                                        <strong>Claim Rejected</strong>
                                        <p>Reviewed by {{ $claim->adminReviewer->name }} on {{ $claim->admin_reviewed_at->format('M d, Y') }}</p>
                                        @if($claim->admin_notes)
                                            <p class="review-notes">{{ $claim->admin_notes }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                </svg>
                <h3>No Claims Yet</h3>
                <p>No one has claimed this pet yet</p>
            </div>
        @endif
    </div>
</div>

<style>
    :root {
        --primary: #2563eb;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --gray: #64748b;
        --gray-light: #f1f5f9;
    }

    .claims-review-page {
        background: var(--gray-light);
        min-height: 100vh;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        color: #475569;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-back:hover {
        background: #f8fafc;
        transform: translateX(-4px);
    }

    .pet-info-card {
        background: white;
        padding: 24px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .pet-image {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
    }

    .pet-image-placeholder {
        width: 100%;
        height: 120px;
        background: #f8fafc;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #cbd5e1;
    }

    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .badge-lost {
        background: #fef2f2;
        color: #ef4444;
    }

    .badge-found {
        background: #f0fdf4;
        color: #10b981;
    }

    .pet-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 12px;
    }

    .pet-details {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        font-size: 0.9rem;
        color: var(--gray);
    }

    .page-header {
        margin-top: 32px;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .page-subtitle {
        color: var(--gray);
        font-size: 1rem;
    }

    .claims-grid {
        display: grid;
        gap: 24px;
    }

    .claim-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: all 0.3s;
    }

    .claim-card:hover {
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .claim-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .claimer-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .claimer-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: var(--primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.25rem;
    }

    .claimer-name {
        font-size: 1.125rem;
        font-weight: 600;
        color: #0f172a;
        margin: 0;
    }

    .claim-date {
        font-size: 0.875rem;
        color: var(--gray);
        margin: 0;
    }

    .status-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-pending {
        background: #fef3c7;
        color: #f59e0b;
    }

    .status-approved {
        background: #d1fae5;
        color: #10b981;
    }

    .status-rejected {
        background: #fee2e2;
        color: #ef4444;
    }

    .claim-body {
        padding: 24px;
    }

    .info-section {
        margin-bottom: 24px;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 12px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .info-label {
        font-size: 0.875rem;
        color: var(--gray);
        font-weight: 500;
    }

    .info-value {
        font-size: 1rem;
        color: #0f172a;
    }

    .claim-description {
        color: #475569;
        line-height: 1.6;
        padding: 16px;
        background: #f8fafc;
        border-radius: 8px;
        border-left: 3px solid var(--primary);
    }

    .proof-images {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 12px;
    }

    .proof-image {
        display: block;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid #e2e8f0;
        transition: all 0.2s;
    }

    .proof-image:hover {
        border-color: var(--primary);
        transform: scale(1.05);
    }

    .proof-image img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        display: block;
    }

    .verification-section {
        background: #f0fdf4;
        padding: 16px;
        border-radius: 8px;
        border-left: 3px solid var(--success);
    }

    .verification-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--success);
        font-weight: 600;
        margin-bottom: 8px;
    }

    .verification-details {
        color: #475569;
        margin: 0;
        font-size: 0.9rem;
    }

    .verification-notes {
        color: #475569;
        margin-top: 8px;
        font-size: 0.9rem;
    }

    .claim-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        padding-top: 16px;
        border-top: 1px solid #e2e8f0;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-approve {
        background: var(--success);
        color: white;
    }

    .btn-approve:hover {
        background: #059669;
    }

    .btn-reject {
        background: var(--danger);
        color: white;
    }

    .btn-reject:hover {
        background: #dc2626;
    }

    .btn-message {
        background: var(--primary);
        color: white;
    }

    .btn-message:hover {
        background: #1d4ed8;
    }

    .review-result {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 16px;
        border-radius: 8px;
        margin-top: 16px;
    }

    .review-result.success {
        background: #f0fdf4;
        border-left: 3px solid var(--success);
        color: var(--success);
    }

    .review-result.rejected {
        background: #fef2f2;
        border-left: 3px solid var(--danger);
        color: var(--danger);
    }

    .review-result strong {
        display: block;
        margin-bottom: 4px;
        color: #0f172a;
    }

    .review-result p {
        margin: 0;
        color: #475569;
        font-size: 0.9rem;
    }

    .review-notes {
        margin-top: 8px !important;
        padding: 12px;
        background: white;
        border-radius: 6px;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .empty-state svg {
        color: #cbd5e1;
        margin-bottom: 16px;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: var(--gray);
    }

    @media (max-width: 768px) {
        .pet-details {
            flex-direction: column;
            gap: 8px;
        }

        .claim-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .claim-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    function approveClaim(claimId) {
        const notes = prompt('Enter approval notes (optional):');
        
        fetch(`/admin/lost-found/claims/${claimId}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ notes })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Claim approved successfully');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('An error occurred');
        });
    }

    function rejectClaim(claimId) {
        const notes = prompt('Enter rejection reason:');
        if (!notes) return;
        
        fetch(`/admin/lost-found/claims/${claimId}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ notes })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Claim rejected');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('An error occurred');
        });
    }

    function messageClaimer(userId) {
        window.location.href = `/admin/messages?user=${userId}`;
    }
</script>
@endsection
