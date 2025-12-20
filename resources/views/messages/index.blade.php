@extends('layouts.app')

@section('title', 'Messenger')

@section('content')
<div class="container-fluid px-0">
    <div class="row g-0" style="height: calc(100vh - 100px);">
        <!-- Sidebar - Contacts & Requests -->
        <div class="col-lg-4 col-md-5 col-12 border-end">
            <div class="d-flex flex-column h-100">
                
                <!-- Search -->
                <div class="p-3 border-bottom">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-0 bg-light" id="search-contacts" placeholder="Search messages...">
                    </div>
                </div>
                
                <!-- Tabs -->
                <ul class="nav nav-tabs border-bottom px-3" id="messagesTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="chats-tab" data-bs-toggle="tab" data-bs-target="#chats" type="button" role="tab">
                            <i class="fas fa-comment-dots me-1"></i> Chats
                            @if($contacts->count() > 0)
                                <span class="badge bg-primary rounded-pill ms-1">{{ $contacts->count() }}</span>
                            @endif
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="requests-tab" data-bs-toggle="tab" data-bs-target="#requests" type="button" role="tab">
                            <i class="fas fa-user-clock me-1"></i> Requests
                            @if($pendingRequests->count() > 0)
                                <span class="badge bg-danger rounded-pill ms-1">{{ $pendingRequests->count() }}</span>
                            @endif
                        </button>
                    </li>
                </ul>
                
                <!-- Tab Content -->
                <div class="tab-content flex-grow-1 overflow-auto">
                    <!-- Chats Tab -->
                    <div class="tab-pane fade show active h-100" id="chats" role="tabpanel">
                        <div class="list-group list-group-flush" id="contact-list">
                            @if($contacts->isEmpty())
                                <div class="text-center p-5 text-muted">
                                    <i class="fas fa-user-friends fa-2x mb-3"></i>
                                    <p class="mb-0">No conversations yet</p>
                                </div>
                            @else
                                @foreach($contacts as $user)
                                    <a href="javascript:void(0);" 
                                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center contact-item"
                                       onclick="loadConversation({{ $user->id }}, '{{ $user->name }}')">
                                        <div class="d-flex align-items-center">
                                            @if($user->profile_picture_path)
                                                <img src="{{ $user->profile_picture_url }}" 
                                                     alt="{{ $user->name }}" 
                                                     class="rounded-circle me-3"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="avatar rounded-circle d-flex align-items-center justify-content-center me-3"
                                                     style="width: 50px; height: 50px; background-color: #e9ecef; color: #495057; font-weight: 600; font-size: 1.1rem;">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="fw-bold text-truncate" style="max-width: 150px;">{{ $user->name }}</div>
                                                    <small class="text-muted">{{ $user->last_message_time ?? '' }}</small>
                                                </div>
                                                <div class="small text-muted text-truncate" style="max-width: 180px;">
                                                    {{ $user->last_message ?? 'No messages yet' }}
                                                </div>
                                            </div>
                                        </div>
                                        @if($user->unread_count > 0)
                                            <span class="badge bg-danger rounded-pill">{{ $user->unread_count }}</span>
                                        @endif
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    
                    <!-- Requests Tab -->
                    <div class="tab-pane fade h-100" id="requests" role="tabpanel">
                        <div class="list-group list-group-flush">
                            <!-- Pending Requests -->
                            @if($pendingRequests->isEmpty() && $sentRequests->isEmpty())
                                <div class="text-center p-5 text-muted">
                                    <i class="fas fa-user-clock fa-2x mb-3"></i>
                                    <p class="mb-0">No message requests</p>
                                </div>
                            @else
                                @if($pendingRequests->count() > 0)
                                    <div class="px-3 py-2 small fw-bold text-muted">REQUESTS</div>
                                    @foreach($pendingRequests as $request)
                                        <div class="list-group-item">
                                            <div class="d-flex align-items-center">
                                                @if($request->sender->profile_picture_path)
                                                    <img src="{{ $request->sender->profile_picture_url }}" 
                                                         alt="{{ $request->sender->name }}" 
                                                         class="rounded-circle me-3"
                                                         style="width: 45px; height: 45px; object-fit: cover;">
                                                @else
                                                    <div class="avatar rounded-circle d-flex align-items-center justify-content-center me-3"
                                                         style="width: 45px; height: 45px; background-color: #e9ecef; color: #495057; font-weight: 600;">
                                                        {{ substr($request->sender->name, 0, 1) }}
                                                    </div>
                                                @endif
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold">{{ $request->sender->name }}</div>
                                                    <small class="text-muted">Wants to message you</small>
                                                </div>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-success" onclick="acceptRequest({{ $request->id }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-danger" onclick="declineRequest({{ $request->id }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                
                                @if($sentRequests->count() > 0)
                                    <div class="px-3 py-2 small fw-bold text-muted">SENT REQUESTS</div>
                                    @foreach($sentRequests as $request)
                                        <div class="list-group-item">
                                            <div class="d-flex align-items-center">
                                                @if($request->recipient->profile_picture_path)
                                                    <img src="{{ $request->recipient->profile_picture_url }}" 
                                                         alt="{{ $request->recipient->name }}" 
                                                         class="rounded-circle me-3"
                                                         style="width: 45px; height: 45px; object-fit: cover;">
                                                @else
                                                    <div class="avatar rounded-circle d-flex align-items-center justify-content-center me-3"
                                                         style="width: 45px; height: 45px; background-color: #e9ecef; color: #495057; font-weight: 600;">
                                                        {{ substr($request->recipient->name, 0, 1) }}
                                                    </div>
                                                @endif
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold">{{ $request->recipient->name }}</div>
                                                    <small class="text-muted text-warning">Waiting for acceptance</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Chat Area -->
        <div class="col-lg-8 col-md-7 col-12 d-flex flex-column">
            <!-- Chat Header (initially hidden) -->
            <div class="border-bottom p-3 d-none" id="chat-header">
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm btn-outline-secondary me-2 d-md-none" onclick="closeChat()">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    @if($selectedUser ?? false)
                        @if($selectedUser->profile_picture_path)
                            <img src="{{ $selectedUser->profile_picture_url }}" 
                                 alt="{{ $selectedUser->name }}" 
                                 class="rounded-circle me-3"
                                 style="width: 40px; height: 40px; object-fit: cover;">
                        @else
                            <div class="avatar rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 40px; height: 40px; background-color: #e9ecef; color: #495057; font-weight: 600;">
                                {{ substr($selectedUser->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <h5 class="mb-0">{{ $selectedUser->name }}</h5>
                            <small class="text-muted">Active now</small>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Messages Container -->
            <div class="flex-grow-1 overflow-auto p-3" id="message-container" style="background-color: #f8f9fa;">
                <!-- Initial state -->
                <div class="h-100 d-flex align-items-center justify-content-center" id="initial-state">
                    <div class="text-center text-muted">
                        <i class="fas fa-comments fa-3x mb-3"></i>
                        <h4>Your Messages</h4>
                        <p class="mb-0">Select a conversation to start messaging</p>
                    </div>
                </div>
                
                <!-- Messages will be loaded here -->
                <div id="conversation-messages" style="display: none;"></div>
            </div>
            
            <!-- Message Input (initially hidden) -->
            <div class="border-top p-3 bg-white d-none" id="message-input-container">
                <form id="message-form">
                    @csrf
                    <input type="hidden" id="receiver-id">
                    <div class="input-group">
                        <input type="text" id="message-input" class="form-control" placeholder="Type a message..." required>
                        <button class="btn text-white" type="submit" style="background-color: #5b4b9b;">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- WebSocket Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/8.4.0/pusher.min.js"></script>

<style>
#messagesTab .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 500;
}

#messagesTab .nav-link.active {
    color: #5b4b9b;
    border-bottom: 2px solid #5b4b9b;
    background: transparent;
}

.contact-item:hover {
    background-color: #f8f9fa;
}

.message-bubble {
    max-width: 70%;
    word-wrap: break-word;
}

.sent-message {
    background-color: #5b4b9b;
    color: white;
    border-radius: 18px 18px 4px 18px;
}

.received-message {
    background-color: white;
    border-radius: 18px 18px 18px 4px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

#message-container::-webkit-scrollbar {
    width: 6px;
}

#message-container::-webkit-scrollbar-track {
    background: transparent;
}

#message-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

@media (max-width: 768px) {
    #chat-sidebar {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 1050;
        background: white;
    }
    
    #chat-main {
        display: none;
    }
    
    #chat-main.active {
        display: flex;
    }
}
</style>

<script>
let currentChatUserId = null;
let pusher = null;
let channel = null;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Pusher
    initializePusher();
    
    // Search functionality
    document.getElementById('search-contacts').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const contactItems = document.querySelectorAll('.contact-item');
        
        contactItems.forEach(item => {
            const name = item.querySelector('.fw-bold').textContent.toLowerCase();
            if (name.includes(searchTerm)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });
    
    // Message form submission
    const messageForm = document.getElementById('message-form');
    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            sendMessage();
        });
    }
});

function initializePusher() {
    // Replace with your Pusher credentials
    pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
        cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
        encrypted: true,
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }
    });
    
    // Subscribe to private channel
    channel = pusher.subscribe('private-messages.{{ Auth::id() }}');
    
    channel.bind('pusher:subscription_succeeded', function() {
        console.log('Successfully subscribed to messages channel');
    });
    
    channel.bind('message-sent', function(data) {
        console.log('Message sent event:', data);
        if (currentChatUserId && currentChatUserId == data.message.sender_id) {
            appendMessage(data.message, false);
        } else if (data.message.sender_id != {{ Auth::id() }}) {
            // Update unread count for this contact
            updateUnreadCount(data.message.sender_id, true);
        }
    });
    
    channel.bind('message-request-sent', function(data) {
        console.log('Message request sent event:', data);
        if (data.message_request.recipient_id == {{ Auth::id() }}) {
            // Show notification for new message request
            showNotification('New message request from ' + data.message.sender.name);
            
            // Update requests badge
            updateRequestsBadge(true);
            
            // If we're on the requests tab, refresh it
            if (document.querySelector('#requests-tab').classList.contains('active')) {
                loadRequests();
            }
        }
    });
    
    channel.bind('message-request-updated', function(data) {
        console.log('Message request updated event:', data);
        
        const request = data.message_request;
        
        if (request.status === 'accepted') {
            // If we're chatting with this user
            const otherUserId = request.sender_id == {{ Auth::id() }} ? request.recipient_id : request.sender_id;
            if (currentChatUserId && currentChatUserId == otherUserId) {
                // Enable messaging
                const messageInput = document.getElementById('message-input');
                if (messageInput) {
                    messageInput.disabled = false;
                    messageInput.placeholder = "Type a message...";
                }
                
                // Show acceptance message
                showInChatNotification('Conversation accepted! You can now message freely.');
            }
        }
        
        // Update requests badge
        updateRequestsBadge(false);
        
        // Refresh requests list if on that tab
        if (document.querySelector('#requests-tab').classList.contains('active')) {
            loadRequests();
        }
        
        // If this user is now in our contacts, refresh contacts
        if (request.status === 'accepted') {
            // You might want to refresh the contacts list here
            // For simplicity, we'll just reload the page
            setTimeout(() => {
                if (document.querySelector('#chats-tab').classList.contains('active')) {
                    window.location.reload();
                }
            }, 1000);
        }
    });
}

function updateRequestsBadge(increment) {
    const badge = document.querySelector('#requests-tab .badge');
    if (badge) {
        let count = parseInt(badge.textContent) || 0;
        if (increment) {
            count++;
        } else {
            // Fetch updated count from server
            fetch('/messages/requests/count')
                .then(response => response.json())
                .then(data => {
                    count = data.count || 0;
                    updateBadgeDisplay(badge, count);
                })
                .catch(() => {
                    // Decrement by 1 as fallback
                    count = Math.max(0, count - 1);
                    updateBadgeDisplay(badge, count);
                });
            return;
        }
        updateBadgeDisplay(badge, count);
    }
}

function showInChatNotification(message) {
    const container = document.getElementById('conversation-messages');
    if (container) {
        const notification = document.createElement('div');
        notification.className = 'text-center text-success my-2';
        notification.innerHTML = `<small><i class="fas fa-check-circle me-1"></i>${message}</small>`;
        container.appendChild(notification);
    }
}

function updateBadgeDisplay(badge, count) {
    if (count > 0) {
        badge.textContent = count;
        badge.style.display = 'inline-block';
    } else {
        badge.style.display = 'none';
    }
}

function loadConversation(userId, userName) {
    currentChatUserId = userId;
    
    // Show chat header
    document.getElementById('chat-header').classList.remove('d-none');
    document.getElementById('chat-header').querySelector('h5').textContent = userName;
    
    // Hide initial state
    document.getElementById('initial-state').style.display = 'none';
    
    // Show conversation area
    document.getElementById('conversation-messages').style.display = 'block';
    
    // Show message input
    document.getElementById('message-input-container').classList.remove('d-none');
    
    // Set receiver ID
    document.getElementById('receiver-id').value = userId;
    
    // Load messages via AJAX
    fetch(`/messages/conversation/${userId}/load`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const container = document.getElementById('conversation-messages');
            container.innerHTML = '';
            
            if (data.messages.length === 0) {
                container.innerHTML = `
                    <div class="text-center text-muted my-5">
                        <p>No messages yet. Start the conversation!</p>
                    </div>
                `;
            } else {
                data.messages.forEach(message => {
                    appendMessage(message, true);
                });
            }
            
            // Mark messages as read
            markMessagesAsRead(userId);
            
            // Scroll to bottom
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
            }, 100);
        } else {
            alert(data.message || 'Failed to load conversation');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
    
    // On mobile, hide sidebar and show chat
    if (window.innerWidth < 768) {
        document.querySelector('#chat-sidebar').style.display = 'none';
        document.querySelector('#chat-main').classList.add('active');
    }
}

function appendMessage(message, isInitialLoad) {
    const container = document.getElementById('conversation-messages');
    const isSender = message.sender_id == {{ Auth::id() }};
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `mb-3 ${isSender ? 'text-end' : 'text-start'}`;
    messageDiv.setAttribute('data-message-id', message.id);
    
    const bubble = document.createElement('div');
    bubble.className = `d-inline-block p-3 message-bubble ${isSender ? 'sent-message' : 'received-message'}`;
    bubble.style.maxWidth = '70%';
    
    // Format timestamp
    const timestamp = new Date(message.created_at).toLocaleTimeString([], { 
        hour: '2-digit', 
        minute: '2-digit' 
    });
    
    bubble.innerHTML = `
        ${message.message}
        <div class="small mt-1 ${isSender ? 'text-white-50' : 'text-muted'}">
            ${timestamp}
        </div>
    `;
    
    messageDiv.appendChild(bubble);
    container.appendChild(messageDiv);
    
    if (!isInitialLoad) {
        container.scrollTop = container.scrollHeight;
    }
}

function sendMessage() {
    const messageInput = document.getElementById('message-input');
    const receiverId = document.getElementById('receiver-id').value;
    const message = messageInput.value.trim();
    
    if (!message || !receiverId) {
        return;
    }
    
    fetch('/messages/send', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            recipient_id: receiverId,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageInput.value = '';
            
            // If this is the first message (request), show appropriate message
            if (data.message_type === 'request') {
                if (data.request_status === 'pending') {
                    const container = document.getElementById('conversation-messages');
                    container.innerHTML += `
                        <div class="text-center text-muted my-3">
                            <small>Message request sent. Waiting for acceptance.</small>
                        </div>
                    `;
                }
            }
        } else {
            alert(data.message || 'Error sending message');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error sending message');
    });
}

function acceptRequest(requestId) {
    fetch(`/messages/requests/${requestId}/accept`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (currentChatUserId && data.message_request) {
                const otherUserId = data.message_request.sender_id == {{ Auth::id() }} ? 
                    data.message_request.recipient_id : data.message_request.sender_id;
                if (currentChatUserId == otherUserId) {
                    loadConversation(currentChatUserId);
                }
            }
        } else {
            alert(data.message || 'Failed to accept request');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error accepting request');
    });
}

function declineRequest(requestId) {
    if (!confirm('Are you sure you want to decline this message request?')) {
        return;
    }
    
    fetch(`/messages/requests/${requestId}/decline`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadRequests();
        }
    })
    .catch(error => console.error('Error:', error));
}

function loadRequests() {
    // This would reload the requests section via AJAX
    // For simplicity, we'll reload the page
    window.location.reload();
}

function markMessagesAsRead(userId) {
    fetch(`/messages/mark-as-read/${userId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update unread count badge for this contact
            updateUnreadCount(userId, false);
        }
    });
}

function updateUnreadCount(userId, increment) {
    const badge = document.querySelector(`.contact-item[onclick*="${userId}"] .badge`);
    if (badge) {
        let count = parseInt(badge.textContent) || 0;
        if (increment) {
            count++;
        } else {
            count = 0;
        }
        
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    }
}

function closeChat() {
    // On mobile, show sidebar again
    document.querySelector('#chat-sidebar').style.display = 'block';
    document.querySelector('#chat-main').classList.remove('active');
}

function showNotification(message) {
    // Create a simple notification
    const notification = document.createElement('div');
    notification.className = 'alert alert-info alert-dismissible fade show position-fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.remove();
    }, 5000);
}
</script>
@endsection