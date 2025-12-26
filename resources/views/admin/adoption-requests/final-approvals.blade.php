@extends('layouts.admin')

@section('title', 'Final Approvals')

@section('content')
<div class="admin-final-approval-page">
    <div class="container-fluid px-4 py-4">
        <!-- Header -->
        <div class="page-header mb-4">
            <h1 class="page-title">Final Adoption Approvals</h1>
            <p class="page-subtitle text-muted">Review and approve adoption requests that have been approved by pet owners</p>
        </div>

        @if($adoptionRequests->count() > 0)
        <!-- Requests Table -->
        <div class="card table-card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Adopter</th>
                            <th>Pet & Owner</th>
                            <th>Contact</th>
                            <th>Status Timeline</th>
                            <th>Applied</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($adoptionRequests as $request)
                        <tr class="table-row-animated">
                            <td>
                                <div class="fw-bold">{{ $request->full_name }}</div>
                                <div class="text-muted small">{{ $request->adopter->email }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="pet-thumb-sm">
                                        @if($request->adoption->image_path)
                                            <img src="{{ asset('storage/' . $request->adoption->image_path) }}" alt="{{ $request->adoption->pet_name }}">
                                        @else
                                            <i class="fas fa-paw"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $request->adoption->pet_name }}</div>
                                        <div class="text-muted small">{{ ucfirst($request->adoption->species) }}</div>
                                    </div>
                                </div>
                                <div class="text-muted small">
                                    <i class="fas fa-user me-1"></i>Owner: {{ $request->adoption->user->name }}
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <div><i class="fas fa-envelope me-1"></i>{{ $request->email }}</div>
                                    <div><i class="fas fa-phone me-1"></i>{{ $request->phone }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    @if($request->admin_screened)
                                    <div class="text-success mb-1">
                                        <i class="fas fa-check-circle me-1"></i>Admin Screened
                                        <div class="text-muted">{{ $request->admin_screening_date->format('M d, Y') }}</div>
                                    </div>
                                    @endif
                                    @if($request->vet_orientation_completed)
                                    <div class="text-success mb-1">
                                        <i class="fas fa-check-circle me-1"></i>Vet Orientation
                                        <div class="text-muted">{{ $request->vet_orientation_date->format('M d, Y') }}</div>
                                    </div>
                                    @endif
                                    @if($request->owner_approved)
                                    <div class="text-success mb-1">
                                        <i class="fas fa-check-circle me-1"></i>Owner Approved
                                        <div class="text-muted">{{ $request->owner_approval_date->format('M d, Y') }}</div>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="small text-muted">{{ $request->created_at->format('M d, Y') }}</div>
                                <div class="small text-muted">{{ $request->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewApplication({{ $request->id }})">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button type="button" class="btn btn-sm btn-success" onclick="approveAdoption({{ $request->id }})">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="rejectAdoption({{ $request->id }})">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $adoptionRequests->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                <h4>No Pending Final Approvals</h4>
                <p class="text-muted">There are no adoption requests awaiting your final approval at this time.</p>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Application Details Modal -->
<div class="modal fade" id="applicationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Application Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="applicationDetails">
                <div class="text-center py-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.admin-final-approval-page {
    background: #f8f9fa;
    min-height: 100vh;
}

.page-header {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.page-subtitle {
    font-size: 1rem;
    margin: 0.5rem 0 0 0;
}

.table-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    overflow: hidden;
}

.table {
    margin: 0;
}

.table thead {
    background: #f8f9fa;
}

.table thead th {
    border: none;
    color: #6c757d;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem;
}

.table tbody td {
    vertical-align: middle;
    padding: 1rem;
    border-bottom: 1px solid #f0f0f0;
}

.table-row-animated {
    transition: all 0.2s ease;
}

.table-row-animated:hover {
    background-color: #f8f9fa;
}

.pet-thumb-sm {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e9ecef;
    flex-shrink: 0;
}

.pet-thumb-sm img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.pet-thumb-sm i {
    font-size: 1.5rem;
    color: #6c757d;
}

.btn-group .btn {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}
</style>

<script>
function viewApplication(requestId) {
    const modal = new bootstrap.Modal(document.getElementById('applicationModal'));
    modal.show();
    
    fetch(`/admin/adoption-requests/${requestId}/details`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('applicationDetails').innerHTML = `
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Personal Information</h6>
                        <p><strong>Full Name:</strong> ${data.full_name}</p>
                        <p><strong>Email:</strong> ${data.email}</p>
                        <p><strong>Phone:</strong> ${data.phone}</p>
                        <p><strong>Address:</strong> ${data.address}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Housing Information</h6>
                        <p><strong>Housing Type:</strong> ${data.housing_type}</p>
                        <p><strong>Has Yard:</strong> ${data.has_yard ? 'Yes' : 'No'}</p>
                        <p><strong>Own/Rent:</strong> ${data.own_or_rent}</p>
                    </div>
                    <div class="col-12">
                        <h6 class="text-muted mb-2">Pet Experience</h6>
                        <p><strong>Current Pets:</strong> ${data.current_pets || 'None'}</p>
                        <p><strong>Experience:</strong> ${data.experience_with_pets || 'N/A'}</p>
                        <p><strong>Reason for Adoption:</strong> ${data.reason_for_adoption}</p>
                    </div>
                    ${data.admin_screening_notes ? `
                    <div class="col-12">
                        <h6 class="text-muted mb-2">Admin Screening Notes</h6>
                        <p>${data.admin_screening_notes}</p>
                    </div>
                    ` : ''}
                    ${data.vet_orientation_notes ? `
                    <div class="col-12">
                        <h6 class="text-muted mb-2">Vet Orientation Notes</h6>
                        <p>${data.vet_orientation_notes}</p>
                    </div>
                    ` : ''}
                </div>
            `;
        })
        .catch(error => {
            document.getElementById('applicationDetails').innerHTML = `
                <div class="alert alert-danger">Failed to load application details.</div>
            `;
        });
}

function approveAdoption(requestId) {
    Swal.fire({
        title: 'Final Approval',
        html: `
            <textarea id="approvalNotes" class="form-control" rows="4" 
                placeholder="Enter approval notes (optional)"></textarea>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Approve Adoption',
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            return document.getElementById('approvalNotes').value;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/adoption-requests/${requestId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    admin_approval_notes: result.value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Failed to approve adoption', 'error');
            });
        }
    });
}

function rejectAdoption(requestId) {
    Swal.fire({
        title: 'Reject Adoption',
        html: `
            <textarea id="rejectionReason" class="form-control" rows="4" 
                placeholder="Enter reason for rejection (required)" required></textarea>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Reject Application',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const reason = document.getElementById('rejectionReason').value;
            if (!reason) {
                Swal.showValidationMessage('Please enter a rejection reason');
                return false;
            }
            return reason;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/adoption-requests/${requestId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    rejection_reason: result.value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Rejected',
                        text: data.message,
                        icon: 'success'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Failed to reject adoption', 'error');
            });
        }
    });
}
</script>
@endsection
