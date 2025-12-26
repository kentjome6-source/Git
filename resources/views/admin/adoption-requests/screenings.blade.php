@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="fas fa-user-check me-2"></i>Pending Adopter Screenings</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($adoptionRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Adopter</th>
                                        <th>Pet</th>
                                        <th>Housing</th>
                                        <th>Experience</th>
                                        <th>Applied</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($adoptionRequests as $request)
                                        <tr>
                                            <td>
                                                <strong>{{ $request->full_name }}</strong><br>
                                                <small class="text-muted">{{ $request->email }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ $request->adoption->pet_name }}</strong><br>
                                                <small class="text-muted">{{ $request->adoption->breed }}</small>
                                            </td>
                                            <td>
                                                {{ ucfirst($request->housing_type) }}<br>
                                                <small class="text-muted">{{ ucfirst($request->own_or_rent) }}</small>
                                            </td>
                                            <td>
                                                @if($request->experience_with_pets)
                                                    <span class="badge bg-success">Has Experience</span>
                                                @else
                                                    <span class="badge bg-warning">First Time</span>
                                                @endif
                                            </td>
                                            <td>{{ $request->created_at->diffForHumans() }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#screenModal{{ $request->id }}">
                                                    <i class="fas fa-search me-1"></i>Screen
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Screening Modal -->
                                        <div class="modal fade" id="screenModal{{ $request->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-xl modal-dialog-scrollable modal-fullscreen-lg-down">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title">Adopter Screening - {{ $request->full_name }}</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <!-- Pet Information -->
                                                            <div class="col-md-6">
                                                                <h5 class="mb-3"><i class="fas fa-paw me-2"></i>Pet Being Adopted</h5>
                                                                <div class="card mb-3">
                                                                    <div class="card-body">
                                                                        @if($request->adoption->image_path)
                                                                            <img src="{{ asset('storage/' . $request->adoption->image_path) }}" 
                                                                                 class="img-fluid rounded mb-3" 
                                                                                 alt="{{ $request->adoption->pet_name }}">
                                                                        @endif
                                                                        <h6>{{ $request->adoption->pet_name }}</h6>
                                                                        <p class="text-muted mb-2">{{ $request->adoption->breed }} • {{ $request->adoption->age }} years • {{ ucfirst($request->adoption->gender ?? 'Unknown') }}</p>
                                                                        @if($request->adoption->description)
                                                                            <p class="small">{{ $request->adoption->description }}</p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Application Details -->
                                                            <div class="col-md-6">
                                                                <h5 class="mb-3"><i class="fas fa-user me-2"></i>Adopter Information</h5>
                                                                <div class="card mb-3">
                                                                    <div class="card-body">
                                                                        <table class="table table-sm">
                                                                            <tr>
                                                                                <th width="40%">Full Name:</th>
                                                                                <td>{{ $request->full_name }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Email:</th>
                                                                                <td>{{ $request->email }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Phone:</th>
                                                                                <td>{{ $request->phone }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Address:</th>
                                                                                <td>{{ $request->address }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Housing Type:</th>
                                                                                <td>{{ ucfirst($request->housing_type) }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Own/Rent:</th>
                                                                                <td>{{ ucfirst($request->own_or_rent) }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Has Yard:</th>
                                                                                <td>
                                                                                    @if($request->has_yard)
                                                                                        <span class="badge bg-success">Yes</span>
                                                                                    @else
                                                                                        <span class="badge bg-secondary">No</span>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                            @if($request->own_or_rent === 'rent')
                                                                                <tr>
                                                                                    <th>Landlord Approval:</th>
                                                                                    <td>
                                                                                        @if($request->landlord_approval)
                                                                                            <span class="badge bg-success">Approved</span>
                                                                                        @else
                                                                                            <span class="badge bg-danger">Not Obtained</span>
                                                                                        @endif
                                                                                    </td>
                                                                                </tr>
                                                                            @endif
                                                                            <tr>
                                                                                <th>Hours Alone:</th>
                                                                                <td>{{ $request->hours_alone }} hours/day</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Home Visit:</th>
                                                                                <td>
                                                                                    @if($request->agree_to_home_visit)
                                                                                        <span class="badge bg-success">Agreed</span>
                                                                                    @else
                                                                                        <span class="badge bg-warning">Not Agreed</span>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Additional Information -->
                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                @if($request->current_pets)
                                                                    <div class="card mb-3">
                                                                        <div class="card-header">
                                                                            <strong>Current Pets</strong>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <p class="mb-0">{{ $request->current_pets }}</p>
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                @if($request->veterinarian_info)
                                                                    <div class="card mb-3">
                                                                        <div class="card-header">
                                                                            <strong>Veterinarian Information</strong>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <p class="mb-0">{{ $request->veterinarian_info }}</p>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <div class="col-md-6">
                                                                @if($request->experience_with_pets)
                                                                    <div class="card mb-3">
                                                                        <div class="card-header">
                                                                            <strong>Experience with Pets</strong>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <p class="mb-0">{{ $request->experience_with_pets }}</p>
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                <div class="card mb-3">
                                                                    <div class="card-header">
                                                                        <strong>Reason for Adoption</strong>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <p class="mb-0">{{ $request->reason_for_adoption }}</p>
                                                                    </div>
                                                                </div>

                                                                @if($request->additional_info)
                                                                    <div class="card mb-3">
                                                                        <div class="card-header">
                                                                            <strong>Additional Information</strong>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <p class="mb-0">{{ $request->additional_info }}</p>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <hr>

                                                        <!-- Screening Form -->
                                                        <form action="{{ route('admin.adoption-requests.screen', $request) }}" method="POST">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Screening Notes <span class="text-danger">*</span></label>
                                                                <textarea name="admin_screening_notes" 
                                                                          class="form-control" 
                                                                          rows="5" 
                                                                          required
                                                                          placeholder="Document your screening assessment including:&#10;- Background check results&#10;- Residence verification&#10;- Financial capability&#10;- Experience assessment&#10;- Overall readiness for adoption"></textarea>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Decision <span class="text-danger">*</span></label>
                                                                <div class="btn-group w-100" role="group">
                                                                    <input type="radio" class="btn-check" name="action" value="approve" id="approve{{ $request->id }}" required>
                                                                    <label class="btn btn-outline-success" for="approve{{ $request->id }}">
                                                                        <i class="fas fa-check me-1"></i>Approve for Vet Orientation
                                                                    </label>
                                                                    
                                                                    <input type="radio" class="btn-check" name="action" value="reject" id="reject{{ $request->id }}">
                                                                    <label class="btn btn-outline-danger" for="reject{{ $request->id }}">
                                                                        <i class="fas fa-times me-1"></i>Reject Application
                                                                    </label>
                                                                </div>
                                                            </div>

                                                            <div id="rejectionReason{{ $request->id }}" style="display: none;">
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold">Rejection Reason <span class="text-danger">*</span></label>
                                                                    <textarea name="admin_screening_rejection" 
                                                                              class="form-control" 
                                                                              rows="4" 
                                                                              placeholder="Provide detailed reasons for rejection. This will be shared with the applicant."></textarea>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex justify-content-end gap-2">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">
                                                                    <i class="fas fa-save me-1"></i>Submit Screening
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            document.getElementById('reject{{ $request->id }}').addEventListener('change', function() {
                                                document.getElementById('rejectionReason{{ $request->id }}').style.display = 'block';
                                                document.querySelector('#rejectionReason{{ $request->id }} textarea').required = true;
                                            });
                                            document.getElementById('approve{{ $request->id }}').addEventListener('change', function() {
                                                document.getElementById('rejectionReason{{ $request->id }}').style.display = 'none';
                                                document.querySelector('#rejectionReason{{ $request->id }} textarea').required = false;
                                            });
                                        </script>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $adoptionRequests->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-user-check fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Pending Screenings</h5>
                            <p class="text-muted">All adoption applications have been screened.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
