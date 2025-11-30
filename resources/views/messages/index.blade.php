@extends(Auth::user()->role === 'vet' ? 'layouts.vet' : 'layouts.app')

@section('title', 'Messages')

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
    
    // Store references to active channels
    let userChannel = null;
    let chatChannel = null;
    
    // Function to format timestamp consistently
    function formatTimestamp(timestamp) {
        // Create date object from timestamp (handles both string and Date objects)
        const date = new Date(timestamp);
        
        // Convert to Philippine timezone (UTC+8)
        const options = {
            timeZone: 'Asia/Manila',
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        };
        
        return date.toLocaleString('en-US', options);
    }
    
    // Function to update a contact's unread count badge
    function updateContactUnreadCount(contactId, count) {
        const badge = document.querySelector(`.unread-count-badge[data-contact-id="${contactId}"]`);
        const contactItem = document.querySelector(`.contact-item[data-contact-id="${contactId}"]`);
        
        if (count !== null) {
            // Direct update with provided count
            if (badge) {
                if (count > 0) {
                    badge.textContent = count;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            } else if (count > 0 && contactItem) {
                // Create badge if it doesn't exist
                const badgeContainer = contactItem.querySelector('.d-flex.align-items-center:last-child');
                if (badgeContainer) {
                    const newBadge = document.createElement('span');
                    newBadge.className = 'badge bg-danger rounded-pill unread-count-badge';
                    newBadge.setAttribute('data-contact-id', contactId);
                    newBadge.textContent = count;
                    badgeContainer.appendChild(newBadge);
                }
            }
        } else {
            // Fetch the actual count from the server
            let contactUnreadCountRoute;
            if ("{{ Auth::user()->role }}" === "vet") {
                contactUnreadCountRoute = "{{ route('vet.messages.contact-unread-count') }}";
            } else {
                contactUnreadCountRoute = "{{ route('user.messages.contact-unread-count') }}";
            }
            
            fetch(`${contactUnreadCountRoute}?contact_id=${contactId}`)
                .then(response => response.json())
                .then(data => {
                    if (badge) {
                        if (data.unread_count > 0) {
                            badge.textContent = data.unread_count;
                            badge.style.display = 'inline-block';
                        } else {
                            badge.style.display = 'none';
                        }
                    } else if (data.unread_count > 0 && contactItem) {
                        // Create badge if it doesn't exist
                        const badgeContainer = contactItem.querySelector('.d-flex.align-items-center:last-child');
                        if (badgeContainer) {
                            const newBadge = document.createElement('span');
                            newBadge.className = 'badge bg-danger rounded-pill unread-count-badge';
                            newBadge.setAttribute('data-contact-id', contactId);
                            newBadge.textContent = data.unread_count;
                            badgeContainer.appendChild(newBadge);
                        }
                    }
                })
                .catch(error => console.error('Error fetching contact unread count:', error));
        }
    }
    
    // Function to update overall unread message count in navigation
    function updateNavigationUnreadCount() {
        let unreadCountRoute;
        if ("{{ Auth::user()->role }}" === "vet") {
            unreadCountRoute = "{{ route('vet.messages.unread-count') }}";
        } else {
            unreadCountRoute = "{{ route('user.messages.unread-count') }}";
        }
        
        fetch(unreadCountRoute)
            .then(response => response.json())
            .then(data => {
                const unreadCountElement = document.getElementById('unread-message-count');
                if (unreadCountElement) {
                    if (data.unread_count > 0) {
                        unreadCountElement.textContent = data.unread_count;
                        unreadCountElement.style.display = 'inline-block';
                    } else {
                        unreadCountElement.style.display = 'none';
                    }
                }
            })
            .catch(error => console.error('Error fetching unread count:', error));
    }
    
    // Function to mark messages as read and update UI
    function markMessagesAsRead(contactId) {
        let markAsReadRoute;
        if ("{{ Auth::user()->role }}" === "vet") {
            markAsReadRoute = "{{ route('vet.messages.mark-as-read') }}";
        } else {
            markAsReadRoute = "{{ route('user.messages.mark-as-read') }}";
        }
        
        fetch(markAsReadRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                contact_id: contactId
            })
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
    
    // Function to subscribe to channels with error handling
    function subscribeToChannels() {
        // Subscribe to user channel for general notifications (always subscribe)
        if (typeof window.subscribeUserChannel === 'function') {
            try {
                userChannel = window.subscribeUserChannel();
                if (userChannel) {
                    setupUserChannelListeners();
                }
            } catch (error) {
                console.error('Error subscribing to user channel:', error);
            }
        }
        
        // Subscribe to chat channel if we have a selected user
        if (selectedUserId && typeof window.subscribeChatChannel === 'function') {
            try {
                chatChannel = window.subscribeChatChannel({{ Auth::id() }}, selectedUserId);
                if (chatChannel) {
                    setupChatChannelListeners();
                }
            } catch (error) {
                console.error('Error subscribing to chat channel:', error);
            }
        }
    }
    
    // Function to set up user channel listeners
    function setupUserChannelListeners() {
        if (!userChannel) return;
        
        try {
            // Listen for new messages on the user channel
            userChannel.listen('.message.sent', function (data) {
                console.log('Received message on user channel:', data);
                
                // Check if we're in a conversation with the sender
                if (selectedUserId && (data.sender_id == selectedUserId || data.receiver_id == selectedUserId)) {
                    // We're in the right conversation, add the message to the chat
                    addMessageToChat(data);
                } else {
                    // Not in the conversation, just update the unread counts
                    updateContactUnreadCount(data.sender_id, null); // null means we need to fetch the count
                    updateNavigationUnreadCount();
                }
            });
            
            // Listen for unread message count updates
            userChannel.listen('.unread.message.count.updated', function (data) {
                console.log('Received unread count update on user channel:', data);
                // Update the navigation unread count
                updateNavigationUnreadCount();
                
                // If we're viewing a conversation with this user, update that count too
                if (selectedUserId && data.userId == selectedUserId) {
                    updateContactUnreadCount(selectedUserId, data.unread_count);
                }
            });
        } catch (error) {
            console.error('Error setting up user channel listeners:', error);
        }
    }
    
    // Function to add a message to the chat display
    function addMessageToChat(data) {
        try {
            // Check if message already exists to prevent duplicates
            const existingMessage = document.querySelector(`.message[data-message-id="${data.id}"]`);
            if (existingMessage) {
                return; // Message already exists, don't add it again
            }
            
            const messageContainer = document.getElementById('message-container');
            if (!messageContainer) {
                console.warn('Message container not found');
                return;
            }
            
            const messageDiv = document.createElement('div');
            messageDiv.className = `mb-3 ${data.sender_id == {{ Auth::id() }} ? 'text-end' : 'text-start'} message`;
            messageDiv.setAttribute('data-message-id', data.id);
            
            const bgColorClass = data.sender_id == {{ Auth::id() }} ? 
                ("{{ Auth::user()->role }}" === "vet" ? 'bg-vet-green text-white' : 'bg-purple text-white') : 
                'bg-light';
            
            const messageContent = document.createElement('div');
            messageContent.className = `d-inline-block p-3 rounded-3 shadow-sm ${bgColorClass}`;
            messageContent.style.maxWidth = '80%';
            messageContent.style.wordWrap = 'break-word';
            
            // Format the timestamp from server data
            const timestamp = formatTimestamp(data.created_at);
            
            messageContent.innerHTML = `
                ${data.message}
                <div class="small mt-1">
                    <em>${timestamp}</em>
                </div>
            `;
            
            messageDiv.appendChild(messageContent);
            messageContainer.appendChild(messageDiv);
            
            // Force scroll to bottom with a small delay to ensure DOM is updated
            setTimeout(() => {
                messageContainer.scrollTop = messageContainer.scrollHeight;
            }, 100);
            
            // If this is a new message from the selected user, mark it as read
            if (data.sender_id == selectedUserId) {
                // Small delay to ensure the message is rendered before marking as read
                setTimeout(() => {
                    markMessagesAsRead(selectedUserId);
                }, 500);
            }
            
            // Trigger a custom event for mobile devices to ensure UI updates
            const event = new CustomEvent('messageAdded', { detail: data });
            document.dispatchEvent(event);
        } catch (error) {
            console.error('Error adding message to chat:', error);
        }
    }
    
    // Function to set up chat channel listeners
    function setupChatChannelListeners() {
        if (!chatChannel) return;
        
        try {
            // Listen for new messages
            chatChannel.listen('.message.sent', function (data) {
                console.log('Received message on chat channel:', data);
                // Add message to chat if it's from the selected user
                if (selectedUserId && (data.sender_id == selectedUserId || data.receiver_id == selectedUserId)) {
                    addMessageToChat(data);
                } else {
                    // Update unread count for the sender if they're not the selected user
                    updateContactUnreadCount(data.sender_id, null); // null means we need to fetch the count
                }
            });
        } catch (error) {
            console.error('Error setting up chat channel listeners:', error);
        }
    }
    
    // Function to unsubscribe from channels
    function unsubscribeFromChannels() {
        if (userChannel) {
            try {
                userChannel.stopListening('.message.sent');
                userChannel.stopListening('.unread.message.count.updated');
            } catch (error) {
                console.error('Error unsubscribing from user channel:', error);
            }
        }
        
        if (chatChannel) {
            try {
                chatChannel.stopListening('.message.sent');
            } catch (error) {
                console.error('Error unsubscribing from chat channel:', error);
            }
        }
    }
    
    // Handle page visibility changes
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible') {
            // Page became visible, resubscribe to channels if needed
            console.log('Page became visible, checking channel subscriptions');
            subscribeToChannels();
        }
    });
    
    // Mobile-specific enhancements for better reliability
    function setupMobileMessagingEnhancements() {
        // Check if we're on a mobile device
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        
        if (isMobile) {
            console.log('Setting up mobile messaging enhancements');
            
            // Force reconnection and resubscription when window gains focus
            window.addEventListener('focus', function() {
                console.log('Mobile window focused, resubscribing to channels');
                subscribeToChannels();
                
                // Also update unread counts when returning to the app
                if (selectedUserId) {
                    updateContactUnreadCount(selectedUserId, null);
                }
                updateNavigationUnreadCount();
            });
            
            // Handle mobile app going to background/foreground with more robust approach
            document.addEventListener('visibilitychange', function() {
                if (document.visibilityState === 'visible') {
                    console.log('Mobile app became visible, resubscribing to channels');
                    // Small delay to ensure DOM is ready
                    setTimeout(() => {
                        subscribeToChannels();
                        
                        // Also update unread counts when returning to the app
                        if (selectedUserId) {
                            updateContactUnreadCount(selectedUserId, null);
                        }
                        updateNavigationUnreadCount();
                    }, 500);
                }
            });
            
            // Periodic connection check every 1 minute (more frequent for mobile)
            setInterval(function() {
                if (document.visibilityState === 'visible') {
                    console.log('Performing periodic mobile connection check');
                    subscribeToChannels();
                }
            }, 60000); // 1 minute
            
            // Additional handling for mobile page lifecycle
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    console.log('Mobile page restored from cache, resubscribing to channels');
                    setTimeout(() => {
                        subscribeToChannels();
                        
                        // Also update unread counts
                        if (selectedUserId) {
                            updateContactUnreadCount(selectedUserId, null);
                        }
                        updateNavigationUnreadCount();
                    }, 500);
                }
            });
        }
    }
    
    if (selectedUserId) {
        // Mark messages as read when conversation is opened
        markMessagesAsRead(selectedUserId);
        
        // Scroll to bottom of message container
        const messageContainer = document.getElementById('message-container');
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }
    
    // Subscribe to user channel for real-time notifications (always subscribe)
    if (typeof window.subscribeUserChannel === 'function') {
        try {
            userChannel = window.subscribeUserChannel();
            if (userChannel) {
                setupUserChannelListeners();
            }
        } catch (error) {
            console.error('Error subscribing to user channel:', error);
        }
    }
    
    // Subscribe to chat channel if we have a selected user
    if (selectedUserId && typeof window.subscribeChatChannel === 'function') {
        try {
            chatChannel = window.subscribeChatChannel({{ Auth::id() }}, selectedUserId);
            if (chatChannel) {
                setupChatChannelListeners();
            }
        } catch (error) {
            console.error('Error subscribing to chat channel:', error);
        }
    }
    
    // Set up mobile enhancements
    setupMobileMessagingEnhancements();
    
    // Mobile-specific event listener for message updates
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    if (isMobile) {
        document.addEventListener('messageAdded', function(event) {
            console.log('Mobile: Message added event received', event.detail);
            // Ensure UI updates on mobile devices
            const messageContainer = document.getElementById('message-container');
            if (messageContainer) {
                // Force reflow to ensure mobile browsers update the UI
                messageContainer.style.display = 'block';
                
                // Scroll to bottom again after a short delay
                setTimeout(() => {
                    messageContainer.scrollTop = messageContainer.scrollHeight;
                }, 200);
            }
        });
    }
    
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
                let sendRoute;
                if ("{{ Auth::user()->role }}" === "vet") {
                    sendRoute = "{{ route('vet.messages.send') }}";
                } else {
                    sendRoute = "{{ route('user.messages.send') }}";
                }
                
                fetch(sendRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        receiver_id: receiverId,
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

                                    const bgColorClass = msg.sender_id == {{ Auth::id() }} ?
                                        ("{{ Auth::user()->role }}" === "vet" ? 'bg-vet-green text-white' : 'bg-purple text-white') :
                                        'bg-light';

                                    const messageContent = document.createElement('div');
                                    messageContent.className = `d-inline-block p-3 rounded-3 shadow-sm ${bgColorClass}`;
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
    
    // Clean up on page unload
    window.addEventListener('beforeunload', function() {
        unsubscribeFromChannels();
    });
});
</script>
@endsection