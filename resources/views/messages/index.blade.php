@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <!-- Contacts Panel -->
        <div class="col-lg-8 col-md-10 col-12" id="contacts-panel">
            <div class="card shadow-sm">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #5b4b9b;">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Contacts</h5>
                    <span class="badge bg-light text-dark">{{ $users->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" id="contact-list">
                        @if($users->isEmpty())
                            <div class="text-center p-4 text-muted">
                                <i class="fas fa-user-friends fa-2x mb-2"></i>
                                <p class="mb-0">No contacts available</p>
                            </div>
                        @else
                            @foreach($users as $user)
                                <a href="{{ route('messages.conversation', ['user' => $user->id]) }}" 
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center contact-item">
                                    <div class="d-flex align-items-center">
                                        @if($user->profile_picture_path)
                                            <img src="{{ $user->profile_picture_url }}" alt="{{ $user->name }} Profile Picture" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="avatar bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: 600; font-size: 0.9rem;">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $user->name }}</div>
                                            <div class="small text-muted">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        @if($user->unread_count > 0)
                                            <span class="badge bg-danger rounded-pill unread-count-badge" data-contact-id="{{ $user->id }}">{{ $user->unread_count }}</span>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar {
    font-weight: 600;
    font-size: 0.9rem;
}

.contact-item {
    transition: all 0.2s ease;
}

.contact-item:hover {
    background-color: #f8f9fa;
}

.unread-count-badge {
    font-size: 0.75rem;
    min-width: 20px;
    padding: 0.25em 0.4em;
}

/* Pet parent contact list background to match sidebar */
#contact-list {
    background-color: #fff; /* Match the sidebar background color */
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .avatar {
        width: 35px;
        height: 35px;
        font-size: 0.8rem;
    }
}
</style>
@endsection