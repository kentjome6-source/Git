@extends('layouts.vet')

@section('title', 'Veterinarian Conversation')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-12">
            <!-- Back Button -->
            <div class="col-12 mb-3">
                <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Contacts
                </a>
            </div>
            
            <!-- Conversation Header -->
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            @if($selectedUser->profile_picture_path)
                                <img src="{{ $selectedUser->profile_picture_url }}" alt="{{ $selectedUser->name }} Profile Picture" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="avatar bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; font-weight: 600; font-size: 1.2rem;">
                                    {{ substr($selectedUser->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <h5 class="mb-0">{{ $selectedUser->name }}</h5>
                                <div class="small text-muted">{{ $selectedUser->email }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Messages Panel -->
            <div class="col-12">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <div id="message-container" class="flex-grow-1 overflow-auto mb-3" style="max-height: 500px;">
                            @if($messages->isEmpty())
                                <div class="text-center text-muted my-5">
                                    <i class="fas fa-comment-dots fa-3x mb-3"></i>
                                    <p class="mb-0">No messages yet. Start the conversation!</p>
                                </div>
                            @else
                                @foreach($messages as $message)
                                    <div class="mb-3 {{ $message->sender_id == Auth::id() ? 'text-end' : 'text-start' }} message" data-message-id="{{ $message->id }}">
                                        <div class="d-inline-block p-3 rounded-3 shadow-sm 
                                            {{ $message->sender_id == Auth::id() ? 'bg-success text-white' : 'bg-light' }}" 
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
                            <input type="hidden" id="receiver-id" value="{{ $selectedUser->id }}">
                            <div class="input-group">
                                <input type="text" id="message-input" class="form-control" placeholder="Type your message..." required>
                                <button class="btn btn-success" type="submit">
                                    <i class="fas fa-paper-plane"></i>
                                    <span class="d-none d-sm-inline ms-1">Send</span>
                                </button>
                            </div>
                        </form>
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

/* Responsive adjustments */
@media (max-width: 768px) {
    .avatar {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
}
</style>

<script>
let selectedUserId = {{ $selectedUser->id }};
let userChannel = null;
let chatChannel = null;
let pollingInterval = null;
let lastMessageId = {{ $messages->last()->id ?? 0 }};

document.addEventListener('DOMContentLoaded', function() {
    // Function to format timestamps
    function formatTimestamp(isoString) {
        const date = new Date(isoString);
        return date.toLocaleString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    }
    
    function pollForNewMessages() {
        if (!selectedUserId) return;
        
        fetch(`{{ url('/messages/poll') }}/${selectedUserId}?last_message_id=${lastMessageId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.messages && data.messages.length > 0) {
                const messageContainer = document.getElementById('message-container');
                
                data.messages.forEach(msg => {
                    const existingMessage = document.querySelector(`.message[data-message-id="${msg.id}"]`);
                    if (!existingMessage) {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = `mb-3 ${msg.is_sender ? 'text-end' : 'text-start'} message`;
                        messageDiv.setAttribute('data-message-id', msg.id);
                        
                        const messageContent = document.createElement('div');
                        messageContent.className = msg.is_sender ? 
                            'd-inline-block p-3 rounded-3 shadow-sm bg-success text-white' : 
                            'd-inline-block p-3 rounded-3 shadow-sm bg-light';
                        messageContent.style.maxWidth = '80%';
                        messageContent.style.wordWrap = 'break-word';
                        
                        const timestamp = formatTimestamp(msg.created_at);
                        
                        messageContent.innerHTML = `
                            ${msg.message}
                            <div class="small mt-1">
                                <em>${timestamp}</em>
                            </div>
                        `;
                        
                        messageDiv.appendChild(messageContent);
                        messageContainer.appendChild(messageDiv);
                        messageContainer.scrollTop = messageContainer.scrollHeight;
                        
                        if (msg.id > lastMessageId) {
                            lastMessageId = msg.id;
                        }
                    }
                });
            }
        })
        .catch(error => console.error('Error polling for messages:', error));
    }
    
    function startPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
        }
        
        if (selectedUserId) {
            pollingInterval = setInterval(pollForNewMessages, 2000);
        }
    }
    
    function stopPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
    }
    
    // Function to update contact unread count
    function updateContactUnreadCount(contactId, count) {
        const badge = document.querySelector(`.unread-count-badge[data-contact-id="${contactId}"]`);
        if (badge) {
            if (count === 0) {
                badge.remove();
            } else if (count !== null) {
                badge.textContent = count;
            }
        } else if (count > 0) {
            // Create new badge if it doesn't exist
            const contactItem = document.querySelector(`.contact-item[data-contact-id="${contactId}"]`);
            if (contactItem) {
                const badgeContainer = contactItem.querySelector('.d-flex.align-items-center:last-child');
                if (badgeContainer) {
                    const newBadge = document.createElement('span');
                    newBadge.className = 'badge bg-danger rounded-pill unread-count-badge';
                    newBadge.setAttribute('data-contact-id', contactId);
                    newBadge.textContent = count;
                    badgeContainer.appendChild(newBadge);
                }
            }
        }
    }
    
    // Function to update navigation unread count
    function updateNavigationUnreadCount() {
        fetch('{{ route("messages.unread-count") }}', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            const navBadge = document.querySelector('#unread-message-count');
            if (navBadge) {
                if (data.unread_count > 0) {
                    navBadge.textContent = data.unread_count;
                    navBadge.style.display = 'inline-block';
                } else {
                    navBadge.style.display = 'none';
                }
            }
        })
        .catch(error => console.error('Error fetching unread count:', error));
    }
    
    // Function to mark messages as read
    function markMessagesAsRead(contactId) {
        fetch(`/messages/mark-as-read/${contactId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the contact's unread count to 0
                updateContactUnreadCount(contactId, 0);
                
                // Update overall navigation count
                updateNavigationUnreadCount();
            }
        })
        .catch(error => console.error('Error marking messages as read:', error));
    }
    
    // Scroll to bottom of message container
    const messageContainer = document.getElementById('message-container');
    messageContainer.scrollTop = messageContainer.scrollHeight;
    
    // Mark messages as read when conversation is opened
    markMessagesAsRead(selectedUserId);
    
    // Handle form submission
    const messageForm = document.getElementById('message-form');
    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const messageInput = document.getElementById('message-input');
            const receiverId = document.getElementById('receiver-id').value;
            const message = messageInput.value.trim();
            
            if (message && receiverId) {
                // Send message via AJAX
                fetch('{{ route("messages.send") }}', {
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
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Append the sent message locally so the sender doesn't need to refresh
                        try {
                            const messageContainer = document.getElementById('message-container');
                            if (messageContainer) {
                                const msg = data.message;
                                // Prevent duplicate if already added via WebSocket
                                const existingMessage = document.querySelector(`.message[data-message-id="${msg.id}"]`);
                                if (!existingMessage) {
                                    const messageDiv = document.createElement('div');
                                    messageDiv.className = `mb-3 ${msg.sender_id == {{ Auth::id() }} ? 'text-end' : 'text-start'} message`;
                                    messageDiv.setAttribute('data-message-id', msg.id);

                                    const messageContent = document.createElement('div');
                                    messageContent.className = msg.sender_id == {{ Auth::id() }} ?
                                        'd-inline-block p-3 rounded-3 shadow-sm bg-success text-white' :
                                        'd-inline-block p-3 rounded-3 shadow-sm bg-light';
                                    messageContent.style.maxWidth = '80%';
                                    messageContent.style.wordWrap = 'break-word';

                                    const timestamp = formatTimestamp(msg.created_at);

                                    messageContent.innerHTML = `
                                        ${msg.message}
                                        <div class="small mt-1">
                                            <em>${timestamp}</em>
                                        </div>
                                    `;

                                    messageDiv.appendChild(messageContent);
                                    messageContainer.appendChild(messageDiv);
                                    messageContainer.scrollTop = messageContainer.scrollHeight;
                                }
                            }
                        } catch (err) {
                            console.error('Error appending sent message locally:', err);
                        }

                        // Clear input
                        messageInput.value = '';
                    } else {
                        alert('Error sending message: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error sending message. Please try again.');
                });
            }
        });
    }
    
    // Update unread count every 30 seconds as a fallback
    setInterval(updateNavigationUnreadCount, 30000);
    startPolling();
    
    // Clean up on page unload
    window.addEventListener('beforeunload', function() {
        stopPolling();
    });
});
</script>
@endsection