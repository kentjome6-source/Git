@extends('layouts.vet')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Pending Adopter Orientations</h4>
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
                                        <th>Contact</th>
                                        <th>Applied</th>
                                        <th>Admin Screening</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($adoptionRequests as $request)
                                        <tr>
                                            <td>
                                                <strong>{{ $request->adopter->name }}</strong><br>
                                                <small class="text-muted">{{ $request->full_name }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ $request->adoption->pet_name }}</strong><br>
                                                <small class="text-muted">{{ $request->adoption->breed }}</small>
                                            </td>
                                            <td>
                                                <i class="fas fa-envelope me-1"></i>{{ $request->email }}<br>
                                                <i class="fas fa-phone me-1"></i>{{ $request->phone }}
                                            </td>
                                            <td>{{ $request->created_at->diffForHumans() }}</td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Approved
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#orientationModal{{ $request->id }}">
                                                    <i class="fas fa-chalkboard-teacher me-1"></i>Conduct Orientation
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Orientation Modal -->
                                        <div class="modal fade" id="orientationModal{{ $request->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-xl modal-dialog-scrollable modal-fullscreen-lg-down">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title">Adopter Orientation - {{ $request->adopter->name }}</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <!-- Pet Information -->
                                                            <div class="col-md-6">
                                                                <h5 class="mb-3"><i class="fas fa-paw me-2"></i>Pet Information</h5>
                                                                <div class="card mb-3">
                                                                    <div class="card-body">
                                                                        @if($request->adoption->image_path)
                                                                            <img src="{{ asset('storage/' . $request->adoption->image_path) }}" 
                                                                                 class="img-fluid rounded mb-3" 
                                                                                 alt="{{ $request->adoption->pet_name }}">
                                                                        @endif
                                                                        <table class="table table-sm">
                                                                            <tr>
                                                                                <th width="40%">Pet Name:</th>
                                                                                <td>{{ $request->adoption->pet_name }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Breed:</th>
                                                                                <td>{{ $request->adoption->breed ?? 'Mixed' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Age:</th>
                                                                                <td>{{ $request->adoption->age ?? 'Unknown' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Gender:</th>
                                                                                <td>{{ ucfirst($request->adoption->gender ?? 'Unknown') }}</td>
                                                                            </tr>
                                                                        </table>
                                                                        @if($request->adoption->vet_health_notes)
                                                                            <div class="alert alert-info">
                                                                                <strong>Health Notes:</strong>
                                                                                <p class="mb-0 mt-2">{{ $request->adoption->vet_health_notes }}</p>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Adopter Application -->
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
                                                                                <th>Housing:</th>
                                                                                <td>{{ ucfirst($request->housing_type) }} ({{ $request->own_or_rent }})</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Has Yard:</th>
                                                                                <td>{{ $request->has_yard ? 'Yes' : 'No' }}</td>
                                                                            </tr>
                                                                        </table>

                                                                        @if($request->current_pets)
                                                                            <div class="mt-3">
                                                                                <strong>Current Pets:</strong>
                                                                                <p class="text-muted mb-0">{{ $request->current_pets }}</p>
                                                                            </div>
                                                                        @endif

                                                                        @if($request->experience_with_pets)
                                                                            <div class="mt-3">
                                                                                <strong>Experience:</strong>
                                                                                <p class="text-muted mb-0">{{ $request->experience_with_pets }}</p>
                                                                            </div>
                                                                        @endif

                                                                        @if($request->admin_screening_notes)
                                                                            <div class="alert alert-success mt-3">
                                                                                <strong>Admin Screening Notes:</strong>
                                                                                <p class="mb-0 mt-2">{{ $request->admin_screening_notes }}</p>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Orientation Checklist -->
                                                        <div class="card bg-light mb-3">
                                                            <div class="card-header">
                                                                <h6 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Orientation Topics to Cover</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <ul class="list-unstyled">
                                                                            <li><i class="fas fa-check text-success me-2"></i>Vaccination schedules and requirements</li>
                                                                            <li><i class="fas fa-check text-success me-2"></i>Regular health checkups</li>
                                                                            <li><i class="fas fa-check text-success me-2"></i>Proper nutrition and feeding</li>
                                                                            <li><i class="fas fa-check text-success me-2"></i>Exercise and activity needs</li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <ul class="list-unstyled">
                                                                            <li><i class="fas fa-check text-success me-2"></i>Common health issues to watch for</li>
                                                                            <li><i class="fas fa-check text-success me-2"></i>Emergency care procedures</li>
                                                                            <li><i class="fas fa-check text-success me-2"></i>Behavioral training tips</li>
                                                                            <li><i class="fas fa-check text-success me-2"></i>Legal responsibilities</li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Orientation Form -->
                                                        <form action="{{ route('vet.adoptions.orientation.conduct', $request) }}" method="POST">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Orientation Notes <span class="text-danger">*</span></label>
                                                                <textarea name="vet_orientation_notes" 
                                                                          class="form-control" 
                                                                          rows="6" 
                                                                          required
                                                                          placeholder="Document the orientation session including:&#10;- Topics covered&#10;- Adopter's understanding and readiness&#10;- Specific care instructions provided&#10;- Any concerns or recommendations&#10;- Follow-up items"></textarea>
                                                                <small class="text-muted">This information will be shared with the admin and pet owner.</small>
                                                            </div>

                                                            <div class="alert alert-info">
                                                                <i class="fas fa-info-circle me-2"></i>
                                                                By completing this orientation, you confirm that:
                                                                <ul class="mb-0 mt-2">
                                                                    <li>The adopter understands pet care responsibilities</li>
                                                                    <li>Vaccination and health requirements were explained</li>
                                                                    <li>The adopter is prepared for pet ownership</li>
                                                                    <li>All questions were answered satisfactorily</li>
                                                                </ul>
                                                            </div>

                                                            <div class="d-flex justify-content-end gap-2">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-success">
                                                                    <i class="fas fa-check me-1"></i>Complete Orientation
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $adoptionRequests->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Pending Orientations</h5>
                            <p class="text-muted">All approved adopters have completed their orientation.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
