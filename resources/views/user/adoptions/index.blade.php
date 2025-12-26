@extends('layouts.app')

@section('title', 'Adoption Center')

@section('content')
<div class="adoption-center">
    <div class="container-fluid px-4 py-4">
        <!-- Header Section -->
        <div class="page-header mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="page-title mb-1">Adoption Center</h1>
                    <p class="page-subtitle text-muted mb-0">Find your perfect companion</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('adoptions.history') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-history me-2"></i>History
                    </a>
                    <a href="{{ route('adoptions.create') }}" class="btn btn-primary btn-create">
                        <i class="fas fa-plus me-2"></i>List Pet for Adoption
                    </a>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="card filter-card mb-4">
            <div class="card-body">
                <form action="{{ route('adoptions.index') }}" method="GET" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" id="search" name="search" 
                                       placeholder="Pet name or breed" value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="species" class="form-label">Species</label>
                            <select class="form-select" id="species" name="species">
                                <option value="">All Species</option>
                                <option value="dog" {{ request('species') == 'dog' ? 'selected' : '' }}>Dog</option>
                                <option value="cat" {{ request('species') == 'cat' ? 'selected' : '' }}>Cat</option>
                                <option value="bird" {{ request('species') == 'bird' ? 'selected' : '' }}>Bird</option>
                                <option value="rabbit" {{ request('species') == 'rabbit' ? 'selected' : '' }}>Rabbit</option>
                                <option value="other" {{ request('species') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="age" class="form-label">Age</label>
                            <select class="form-select" id="age" name="age">
                                <option value="">All Ages</option>
                                <option value="0-1" {{ request('age') == '0-1' ? 'selected' : '' }}>0-1 year</option>
                                <option value="1-3" {{ request('age') == '1-3' ? 'selected' : '' }}>1-3 years</option>
                                <option value="3-7" {{ request('age') == '3-7' ? 'selected' : '' }}>3-7 years</option>
                                <option value="7+" {{ request('age') == '7+' ? 'selected' : '' }}>7+ years</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="">All Genders</option>
                                <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <i class="fas fa-filter me-2"></i>Apply
                                </button>
                                <a href="{{ route('adoptions.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Count -->
        @if($adoptionPets->count() > 0)
        <div class="results-info mb-3">
            <p class="text-muted mb-0">
                <i class="fas fa-paw me-2"></i>
                Showing {{ $adoptionPets->count() }} {{ Str::plural('pet', $adoptionPets->count()) }} available for adoption
            </p>
        </div>
        @endif

        <!-- Pets Grid -->
        @if($adoptionPets->count() > 0)
        <div class="row g-4">
            @foreach($adoptionPets as $adoption)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="pet-card">
                    <!-- Pet Image -->
                    <div class="pet-image-wrapper">
                        @if($adoption->image_path)
                            <img src="{{ asset('storage/' . $adoption->image_path) }}" 
                                 class="pet-image" 
                                 alt="{{ $adoption->pet_name }}"
                                 loading="lazy">
                        @else
                            <img src="{{ asset('images/pawpatrol.jpg') }}" 
                                 class="pet-image" 
                                 alt="{{ $adoption->pet_name }}">
                        @endif
                        <div class="pet-status-badge">
                            @if($adoption->status === 'published')
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($adoption->status) }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Pet Info -->
                    <div class="pet-info">
                        <h5 class="pet-name">{{ $adoption->pet_name }}</h5>
                        
                        <div class="pet-meta mb-3">
                            @if($adoption->breed)
                            <span class="meta-item">
                                <i class="fas fa-tag"></i>
                                {{ $adoption->breed }}
                            </span>
                            @endif
                            @if($adoption->age)
                            <span class="meta-item">
                                <i class="fas fa-birthday-cake"></i>
                                {{ $adoption->age }} {{ Str::plural('year', $adoption->age) }}
                            </span>
                            @endif
                            @if($adoption->gender)
                            <span class="meta-item">
                                <i class="fas fa-{{ $adoption->gender === 'male' ? 'mars' : 'venus' }}"></i>
                                {{ ucfirst($adoption->gender) }}
                            </span>
                            @endif
                        </div>

                        @if($adoption->description)
                        <p class="pet-description">
                            {{ Str::limit($adoption->description, 100) }}
                        </p>
                        @endif

                        <!-- Action Button -->
                        <div class="pet-actions mt-3">
                            @if($adoption->user_id != auth()->id())
                            <button type="button" 
                                    class="btn btn-primary w-100 btn-adopt" 
                                    onclick="handleAdoptSubmit({{ $adoption->id }}, '{{ $adoption->pet_name }}')">
                                <i class="fas fa-heart me-2"></i>Apply to Adopt
                            </button>
                            @else
                            <div class="text-center py-2 text-muted">
                                <i class="fas fa-paw me-2"></i>Your Pet
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        {{-- @if($adoptionPets->hasPages())
        <div class="pagination-wrapper mt-5">
            {{ $adoptionPets->links() }}
        </div>
        @endif

        @else --}}
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-paw"></i>
            </div>
            <h3 class="empty-state-title">No Pets Available</h3>
            <p class="empty-state-text">
                There are currently no pets available for adoption matching your criteria.
                @if(request()->hasAny(['search', 'species', 'age', 'gender']))
                    <br>Try adjusting your filters or 
                    <a href="{{ route('adoptions.index') }}" class="text-primary">clear all filters</a>.
                @endif
            </p>
            <a href="{{ route('adoptions.create') }}" class="btn btn-primary mt-3">
                <i class="fas fa-plus me-2"></i>List a Pet for Adoption
            </a>
        </div>
        @endif
    </div>
</div>

<style>
:root {
    --primary: #2563eb;
    --primary-dark: #1e40af;
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
    --dark: #1e293b;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-600: #475569;
    --gray-700: #334155;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
}

.adoption-center {
    background: var(--gray-50);
    min-height: 100vh;
    padding-bottom: 2rem;
}

/* Page Header */
.page-header {
    animation: fadeInDown 0.5s ease-out;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
}

.page-subtitle {
    font-size: 1rem;
    color: var(--gray-600);
}

.btn-create {
    padding: 0.625rem 1.25rem;
    font-weight: 600;
    border-radius: 0.5rem;
    transition: all 0.2s;
    box-shadow: var(--shadow-sm);
}

.btn-create:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

/* Filter Card */
.filter-card {
    border: none;
    box-shadow: var(--shadow);
    border-radius: 0.75rem;
    animation: fadeIn 0.6s ease-out;
}

.filter-card .card-body {
    padding: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: var(--gray-700);
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border: 1px solid var(--gray-300);
    border-radius: 0.5rem;
    padding: 0.625rem 0.875rem;
    font-size: 0.9375rem;
    transition: all 0.2s;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.input-group-text {
    background: white;
    border: 1px solid var(--gray-300);
    border-right: none;
    color: var(--gray-600);
}

.input-group .form-control {
    border-left: none;
}

.input-group:focus-within .input-group-text {
    border-color: var(--primary);
}

/* Results Info */
.results-info {
    animation: fadeIn 0.7s ease-out;
}

/* Pet Cards */
.pet-card {
    background: white;
    border-radius: 0.75rem;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    animation: fadeInUp 0.5s ease-out backwards;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.pet-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
}

.col-lg-3:nth-child(1) .pet-card { animation-delay: 0.05s; }
.col-lg-3:nth-child(2) .pet-card { animation-delay: 0.1s; }
.col-lg-3:nth-child(3) .pet-card { animation-delay: 0.15s; }
.col-lg-3:nth-child(4) .pet-card { animation-delay: 0.2s; }
.col-lg-3:nth-child(5) .pet-card { animation-delay: 0.25s; }
.col-lg-3:nth-child(6) .pet-card { animation-delay: 0.3s; }
.col-lg-3:nth-child(7) .pet-card { animation-delay: 0.35s; }
.col-lg-3:nth-child(8) .pet-card { animation-delay: 0.4s; }

.pet-image-wrapper {
    position: relative;
    width: 100%;
    padding-top: 75%;
    overflow: hidden;
    background: var(--gray-100);
}

.pet-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.pet-card:hover .pet-image {
    transform: scale(1.1);
}

.pet-status-badge {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    z-index: 10;
}

.pet-status-badge .badge {
    padding: 0.375rem 0.75rem;
    font-weight: 600;
    font-size: 0.75rem;
    border-radius: 0.375rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.pet-info {
    padding: 1.25rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.pet-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 0.75rem;
}

.pet-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.meta-item {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.875rem;
    color: var(--gray-600);
}

.meta-item i {
    color: var(--primary);
    font-size: 0.875rem;
}

.pet-description {
    font-size: 0.9375rem;
    color: var(--gray-600);
    line-height: 1.6;
    margin-bottom: 0;
    flex: 1;
}

.pet-actions {
    margin-top: auto;
}

.btn-adopt {
    font-weight: 600;
    padding: 0.625rem 1rem;
    border-radius: 0.5rem;
    transition: all 0.2s;
}

.btn-adopt:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    animation: fadeIn 0.6s ease-out;
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--gray-100);
    border-radius: 50%;
}

.empty-state-icon i {
    font-size: 2.5rem;
    color: var(--gray-600);
}

.empty-state-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.empty-state-text {
    font-size: 1rem;
    color: var(--gray-600);
    max-width: 500px;
    margin: 0 auto;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    animation: fadeIn 0.8s ease-out;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
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

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.5rem;
    }
    
    .filter-card .card-body {
        padding: 1rem;
    }
    
    .pet-card {
        margin-bottom: 0;
    }
}
</style>

<script>
function handleAdoptSubmit(adoptionId, petName) {
    Swal.fire({
        title: 'Submit Application?',
        text: `Do you want to submit an adoption application for ${petName}? You'll need to fill out an application form.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Continue',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `/adoptions/${adoptionId}`;
        }
    });
}
</script>
@endsection
