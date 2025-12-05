@extends(Auth::user()->role === 'vet' ? 'layouts.vet' : 'layouts.app')

@section('title', 'Messages')

@section('styles')
<meta name="current-user-role" content="{{ Auth::user()->role }}">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Contacts Panel -->
        <div class="col-lg-4 col-md-5 mb-4 mb-md-0" id="contacts-panel">
            <div class="card shadow-sm h-100">
                <div class="card-header {{ Auth::user()->role === 'vet' ? 'bg-vet-green' : 'bg-purple' }} text-white d-flex justify-content-between align-items-center">
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
                                <a href="{{ route(Auth::user()->role === 'vet' ? 'vet.messages.index' : 'user.messages.index', ['user' => $user->id]) }}" 
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center contact-item {{ $selectedUserId == $user->id ? 'active' : '' }}"
                                   data-contact-id="{{ $user->id }}">
                                    <div class="d-flex align-items-center">
                                        @if($user->profile_picture_path)
                                            <img src="{{ asset('storage/' . $user->profile_picture_path) }}" alt="{{ $user->name }} Profile Picture" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="avatar {{ Auth::user()->role === 'vet' ? 'bg-vet-green' : 'bg-purple' }} text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $user->name }}</div>
                                            <small class="text-muted">
                                                {{ $user->email }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        @if(Auth::user()->role === 'vet' && $user->role !== 'vet')
                                            <span class="badge {{ Auth::user()->role === 'vet' ? 'bg-vet-green' : 'bg-purple' }} me-2">User</span>
                                        @endif
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
        
        <!-- Messages Panel -->
        <div class="col-lg-8 col-md-7" id="messages-panel">
            <div class="card shadow-sm h-100">
                <div class="card-header {{ Auth::user()->role === 'vet' ? 'bg-vet-green' : 'bg-purple' }} text-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-comments me-2"></i>Messages</h5>
                    @if($selectedUserId)
                        @php
                            $selectedUser = $users->firstWhere('id', $selectedUserId);
                        @endphp
                        @if($selectedUser)
                            <div class="d-flex align-items-center">
                                @if($selectedUser->profile_picture_path)
                                    <img src="{{ asset('storage/' . $selectedUser->profile_picture_path) }}" alt="{{ $selectedUser->name }} Profile Picture" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                @else
                                    <div class="avatar {{ Auth::user()->role === 'vet' ? 'bg-vet-green' : 'bg-purple' }} text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                        {{ substr($selectedUser->name, 0, 1) }}
                                    </div>
                                @endif
                                <span>{{ $selectedUser->name }}</span>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="card-body d-flex flex-column">
                    @if($selectedUserId)
                        <div id="message-container" class="flex-grow-1 overflow-auto mb-3" style="max-height: 450px;">
                            @if($messages->isEmpty())
                                <div class="text-center text-muted my-5">
                                    <i class="fas fa-comment-dots fa-3x mb-3"></i>
                                    <p class="mb-0">No messages yet. Start the conversation!</p>
                                </div>
                            @else
                                @foreach($messages as $message)
                                    <div class="mb-3 {{ $message->sender_id == Auth::id() ? 'text-end' : 'text-start' }}">
                                        <div class="d-inline-block p-3 rounded-3 shadow-sm 
                                            {{ $message->sender_id == Auth::id() ? (Auth::user()->role === 'vet' ? 'bg-vet-green text-white' : 'bg-purple text-white') : 'bg-light' }}" 
                                            style="max-width: 80%; word-wrap: break-word;">
                                            {{ $message->message }}
                                            <div class="small mt-1">
                                                <em>{{ $message->created_at->timezone('Asia/Manila')->format('M j, Y g:i A') }}</em>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <form id="message-form" class="mt-auto">
                            @csrf
                            <input type="hidden" id="receiver-id" value="{{ $selectedUserId }}">
                            <div class="input-group">
                                <input type="text" id="message-input" class="form-control" placeholder="Type your message..." required>
                                <button class="btn {{ Auth::user()->role === 'vet' ? 'btn-vet-green' : 'btn-purple' }}" type="submit">
                                    <i class="fas fa-paper-plane"></i>
                                    <span class="d-none d-sm-inline ms-1">Send</span>
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-center my-5">
                            <i class="fas fa-envelope-open-text fa-3x text-muted mb-3"></i>
                            <h5>Select a contact to start messaging</h5>
                            <p class="text-muted">Choose a contact from the list to begin a conversation.</p>
                        </div>
                    @endif
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

#message-container::-webkit-scrollbar {
    width: 6px;
}
#message-container::-webkit-scrollbar-track {
    background: #f1f1f1;
}
#message-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}
#message-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Pet parent purple theme for message headers and buttons */
.bg-purple {
    background-color: #5b4b9b !important;
}
.btn-purple {
    background-color: #5b4b9b !important;
    border-color: #5b4b9b !important;
    color: #fff !important;
}
.btn-purple:hover {
    background-color: #4a3d82 !important;
    border-color: #4a3d82 !important;
}
/* Vet green theme for message headers, buttons, and bubbles */
.bg-vet-green {
    background-color: #27ae60 !important;
}
.btn-vet-green {
    background-color: #27ae60 !important;
    border-color: #27ae60 !important;
    color: #fff !important;
}
.btn-vet-green:hover {
    background-color: #219653 !important;
    border-color: #219653 !important;
}
.badge.bg-success,
.badge.bg-vet-green {
    background-color: #27ae60 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if we have a selected user
    const selectedUserId = {{ $selectedUserId ?? 'null' }};
    
    // Make selectedUserId available to the messaging module
    window.selectedUserId = selectedUserId;
    
    // Initialize the messaging system using the external module
    if (typeof window.Messaging !== 'undefined' && typeof window.Messaging.initialize === 'function') {
        console.log('Initializing messaging system with external module');
        window.Messaging.initialize(selectedUserId);
    } else {
        console.error('Messaging module not found or initialize function missing. Please ensure messaging.js is loaded.');
    }
});
</script>
@endsection