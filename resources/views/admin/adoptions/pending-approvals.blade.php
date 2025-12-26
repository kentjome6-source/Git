@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Pending Adoption Listing Approvals</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($adoptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Pet Name</th>
                                        <th>Breed</th>
                                        <th>Owner</th>
                                        <th>Vet Certified By</th>
                                        <th>Certified Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($adoptions as $adoption)
                                        <tr>
                                            <td>
                                                <strong>{{ $adoption->pet_name }}</strong>
                                            </td>
                                            <td>{{ $adoption->breed ?? 'Not specified' }}</td>
                                            <td>
                                                {{ $adoption->user->name }}<br>
                                                <small class="text-muted">{{ $adoption->user->email }}</small>
                                            </td>
                                            <td>
                                                @if($adoption->vet)
                                                    {{ $adoption->vet->name }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>{{ $adoption->vet_certification_date ? $adoption->vet_certification_date->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#reviewModal{{ $adoption->id }}">
                                                    <i class="fas fa-search me-1"></i>Review
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Review Modal -->
                                        <div class="modal fade" id="reviewModal{{ $adoption->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-xl modal-dialog-scrollable modal-fullscreen-lg-down">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title">Review Adoption Listing</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h5 class="mb-3">Pet Information</h5>
                                                                @if($adoption->image_path)
                                                                    <img src="{{ asset('storage/' . $adoption->image_path) }}" 
                                                                         class="img-fluid rounded mb-3" 
                                                                         alt="{{ $adoption->pet_name }}">
                                                                @endif
                                                                <table class="table table-sm">
                                                                    <tr>
                                                                        <th width="40%">Name:</th>
                                                                        <td>{{ $adoption->pet_name }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Breed:</th>
                                                                        <td>{{ $adoption->breed ?? 'Not specified' }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Age:</th>
                                                                        <td>{{ $adoption->age ?? 'Unknown' }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Gender:</th>
                                                                        <td>{{ ucfirst($adoption->gender ?? 'Unknown') }}</td>
                                                                    </tr>
                                                                </table>
                                                                @if($adoption->description)
                                                                    <div class="mb-3">
                                                                        <strong>Description:</strong>
                                                                        <p class="text-muted">{{ $adoption->description }}</p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h5 class="mb-3">Owner Information</h5>
                                                                <table class="table table-sm">
                                                                    <tr>
                                                                        <th width="40%">Name:</th>
                                                                        <td>{{ $adoption->user->name }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Email:</th>
                                                                        <td>{{ $adoption->user->email }}</td>
                                                                    </tr>
                                                                    @if($adoption->user->phone)
                                                                        <tr>
                                                                            <th>Phone:</th>
                                                                            <td>{{ $adoption->user->phone }}</td>
                                                                        </tr>
                                                                    @endif
                                                                </table>

                                                                <h5 class="mb-3 mt-4">Veterinary Certification</h5>
                                                                <div class="card bg-success text-white mb-3">
                                                                    <div class="card-body">
                                                                        <h6 class="card-title">
                                                                            <i class="fas fa-check-circle me-2"></i>Certified by {{ $adoption->vet->name ?? 'Veterinarian' }}
                                                                        </h6>
                                                                        <small>{{ $adoption->vet_certification_date ? $adoption->vet_certification_date->format('F d, Y') : '' }}</small>
                                                                    </div>
                                                                </div>
                                                                
                                                                @if($adoption->vet_health_notes)
                                                                    <div class="alert alert-info">
                                                                        <strong>Health Assessment Notes:</strong>
                                                                        <p class="mb-0 mt-2">{{ $adoption->vet_health_notes }}</p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <hr>

                                                        <!-- Decision Forms -->
                                                        <ul class="nav nav-tabs mb-3" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-bs-toggle="tab" href="#approve{{ $adoption->id }}">
                                                                    <i class="fas fa-check-circle text-success me-1"></i>Approve Listing
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-bs-toggle="tab" href="#reject{{ $adoption->id }}">
                                                                    <i class="fas fa-times-circle text-danger me-1"></i>Reject Listing
                                                                </a>
                                                            </li>
                                                        </ul>

                                                        <div class="tab-content">
                                                            <!-- Approve Tab -->
                                                            <div class="tab-pane fade show active" id="approve{{ $adoption->id }}">
                                                                <form action="{{ route('admin.adoptions.approve', $adoption) }}" method="POST">
                                                                    @csrf
                                                                    <div class="alert alert-success">
                                                                        <i class="fas fa-info-circle me-2"></i>
                                                                        By approving this listing, the pet will be published and visible to potential adopters.
                                                                    </div>
                                                                    <div class="d-flex justify-content-end gap-2">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                        <button type="submit" class="btn btn-success">
                                                                            <i class="fas fa-check me-1"></i>Approve & Publish
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                            <!-- Reject Tab -->
                                                            <div class="tab-pane fade" id="reject{{ $adoption->id }}">
                                                                <form action="{{ route('admin.adoptions.reject', $adoption) }}" method="POST">
                                                                    @csrf
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold">Rejection Reason <span class="text-danger">*</span></label>
                                                                        <textarea name="admin_rejection_reason" 
                                                                                  class="form-control" 
                                                                                  rows="5" 
                                                                                  required
                                                                                  placeholder="Please provide detailed reasons for rejecting this listing. The owner will be notified."></textarea>
                                                                    </div>
                                                                    <div class="alert alert-warning">
                                                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                                                        The pet owner will be notified of the rejection and can address the concerns.
                                                                    </div>
                                                                    <div class="d-flex justify-content-end gap-2">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                        <button type="submit" class="btn btn-danger">
                                                                            <i class="fas fa-times me-1"></i>Reject Listing
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $adoptions->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Pending Approvals</h5>
                            <p class="text-muted">All vet-certified listings have been reviewed.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
