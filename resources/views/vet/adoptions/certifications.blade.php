@extends('layouts.vet')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Pending Pet Certifications</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
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
                                        <th>Age</th>
                                        <th>Gender</th>
                                        <th>Owner</th>
                                        <th>Submitted</th>
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
                                            <td>{{ $adoption->age ? $adoption->age . ' years' : 'Unknown' }}</td>
                                            <td>
                                                @if($adoption->gender)
                                                    <span class="badge bg-{{ $adoption->gender == 'male' ? 'info' : 'danger' }}">
                                                        {{ ucfirst($adoption->gender) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Unknown</span>
                                                @endif
                                            </td>
                                            <td>{{ $adoption->user->name }}</td>
                                            <td>{{ $adoption->created_at->diffForHumans() }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#certifyModal{{ $adoption->id }}">
                                                    <i class="fas fa-stethoscope me-1"></i>Review
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Certify Modal -->
                                        <div class="modal fade" id="certifyModal{{ $adoption->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg modal-dialog-scrollable modal-fullscreen-md-down">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title">Pet Health Certification</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-4">
                                                            <div class="col-md-6">
                                                                @if($adoption->image_path)
                                                                    <img src="{{ asset('storage/' . $adoption->image_path) }}" 
                                                                         class="img-fluid rounded" 
                                                                         alt="{{ $adoption->pet_name }}">
                                                                @else
                                                                    <div class="bg-light p-5 text-center rounded">
                                                                        <i class="fas fa-paw fa-3x text-muted"></i>
                                                                        <p class="text-muted mt-2">No image available</p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h5 class="mb-3">Pet Information</h5>
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
                                                                    <tr>
                                                                        <th>Owner:</th>
                                                                        <td>{{ $adoption->user->name }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Contact:</th>
                                                                        <td>{{ $adoption->user->email }}</td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        @if($adoption->description)
                                                            <div class="mb-4">
                                                                <h6>Description</h6>
                                                                <p class="text-muted">{{ $adoption->description }}</p>
                                                            </div>
                                                        @endif

                                                        <!-- Certification Form -->
                                                        <ul class="nav nav-tabs mb-3" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-bs-toggle="tab" href="#certify{{ $adoption->id }}">
                                                                    <i class="fas fa-check-circle text-success me-1"></i>Certify Pet
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-bs-toggle="tab" href="#reject{{ $adoption->id }}">
                                                                    <i class="fas fa-times-circle text-danger me-1"></i>Reject Listing
                                                                </a>
                                                            </li>
                                                        </ul>

                                                        <div class="tab-content">
                                                            <!-- Certify Tab -->
                                                            <div class="tab-pane fade show active" id="certify{{ $adoption->id }}">
                                                                <form action="{{ route('vet.adoptions.certify', $adoption) }}" method="POST">
                                                                    @csrf
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold">Health Assessment Notes <span class="text-danger">*</span></label>
                                                                        <textarea name="vet_health_notes" 
                                                                                  class="form-control" 
                                                                                  rows="5" 
                                                                                  required
                                                                                  placeholder="Enter detailed health assessment including:&#10;- Vaccination status&#10;- Overall health condition&#10;- Any medical concerns&#10;- Recommendations for adopter"></textarea>
                                                                        <small class="text-muted">This information will be visible to admin and potential adopters.</small>
                                                                    </div>
                                                                    <div class="alert alert-info">
                                                                        <i class="fas fa-info-circle me-2"></i>
                                                                        By certifying this pet, you confirm that:
                                                                        <ul class="mb-0 mt-2">
                                                                            <li>The pet is in good health and fit for adoption</li>
                                                                            <li>All vaccinations are up to date or scheduled</li>
                                                                            <li>There are no serious health concerns that would prevent adoption</li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="d-flex justify-content-end gap-2">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                        <button type="submit" class="btn btn-success">
                                                                            <i class="fas fa-check me-1"></i>Certify Pet
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                            <!-- Reject Tab -->
                                                            <div class="tab-pane fade" id="reject{{ $adoption->id }}">
                                                                <form action="{{ route('vet.adoptions.reject', $adoption) }}" method="POST">
                                                                    @csrf
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold">Rejection Reason <span class="text-danger">*</span></label>
                                                                        <textarea name="vet_rejection_reason" 
                                                                                  class="form-control" 
                                                                                  rows="5" 
                                                                                  required
                                                                                  placeholder="Please provide detailed reasons for rejection, including any health concerns or requirements that need to be met before the pet can be listed for adoption."></textarea>
                                                                        <small class="text-muted">This will be sent to the pet owner.</small>
                                                                    </div>
                                                                    <div class="alert alert-warning">
                                                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                                                        The pet owner will be notified and can resubmit after addressing the concerns.
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
                            <h5 class="text-muted">No Pending Certifications</h5>
                            <p class="text-muted">All adoption listings have been reviewed.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
