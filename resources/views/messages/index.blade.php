@extends(Auth::user()->role === 'vet' ? 'layouts.vet' : 'layouts.app')

@section('title', 'Messages')

@section('content')
<div class="container-fluid px-0 px-sm-2">
    <div class="row g-0 g-sm-3">
        <!-- Contacts Panel -->
        <div class="col-lg-4 col-md-5 mb-3 mb-md-0" id="contacts-panel">
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
                                            <img src="{{ asset('storage/' . $user->profile_picture_path) }}" alt="{{ $user->name }} Profile Picture" class="rounded-circle me-2 me-sm-3" style="width: 35px; height: 35px; object-fit: cover;">
                                        @else
                                            <div class="avatar {{ Auth::user()->role === 'vet' ? 'bg-vet-green' : 'bg-purple' }} text-white rounded-circle d-flex align-items-center justify-content-center me-2 me-sm-3" style="width: 35px; height: 35px; min-width: 35px;">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="fw-bold text-truncate">{{ $user->name }}</div>
                                            <small class="text-muted text-truncate d-block">
                                                {{ $user->email }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center flex-shrink-0">
                                        @if(Auth::user()->role === 'vet' && $user->role !== 'vet')
                                            <span class="badge {{ Auth::user()->role === 'vet' ? 'bg-vet-green' : 'bg-purple' }} me-1 me-sm-2 d-none d-sm-inline">User</span>
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
                                    <img src="{{ asset('storage/' . $selectedUser->profile_picture_path) }}" alt="{{ $selectedUser->name }} Profile Picture" class="rounded-circle me-2" style="width: 25px; height: 25px; object-fit: cover;">
                                @else
                                    <div class="avatar {{ Auth::user()->role === 'vet' ? 'bg-vet-green' : 'bg-purple' }} text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 25px; height: 25px; min-width: 25px; font-size: 0.7rem;">
                                        {{ substr($selectedUser->name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="d-none d-sm-inline">{{ $selectedUser->name }}</span>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="card-body d-flex flex-column p-2 p-sm-3">
                    @if($selectedUserId)
                        <div id="message-container" class="flex-grow-1 overflow-auto mb-2 mb-sm-3" style="max-height: 400px;">
                            @if($messages->isEmpty())
                                <div class="text-center text-muted my-4 my-sm-5">
                                    <i class="fas fa-comment-dots fa-2x fa-3x mb-2"></i>
                                    <p class="mb-0">No messages yet. Start the conversation!</p>
                                </div>
                            @else
                                @foreach($messages as $message)
                                    <div class="mb-2 mb-sm-3 {{ $message->sender_id == Auth::id() ? 'text-end' : 'text-start' }}">
                                        <div class="d-inline-block p-2 p-sm-3 rounded-3 shadow-sm 
                                            {{ $message->sender_id == Auth::id() ? (Auth::user()->role === 'vet' ? 'bg-vet-green text-white' : 'bg-purple text-white') : 'bg-light' }}" 
                                            style="max-width: 85%; word-wrap: break-word;">
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
                        <div class="text-center my-4 my-sm-5">
                            <i class="fas fa-envelope-open-text fa-2x fa-3x text-muted mb-2 mb-sm-3"></i>
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
    font-size: 0.8rem;
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

/* Ensure consistent scrollbar appearance across devices */
#message-container {
    scrollbar-width: thin;
    scrollbar-color: #c1c1c1 #f1f1f1;
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

/* Responsive improvements for all devices */
@media (max-width: 767.98px) {
    #message-container {
        max-height: 350px;
    }
    
    .card-body {
        padding: 0.5rem;
    }
    
    .contact-item {
        padding: 0.75rem 0.5rem;
    }
    
    .message .d-inline-block {
        max-width: 90%;
        padding: 0.75rem !important;
    }
    
    .card-header h5 {
        font-size: 1rem;
    }
}

/* Ensure consistent message display across all devices */
.message {
    transition: opacity 0.3s ease-in-out;
}

.message.new {
    opacity: 0;
    animation: fadeIn 0.3s ease-in-out forwards;
}

@keyframes fadeIn {
    to {
        opacity: 1;
    }
}

.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.min-w-0 {
    min-width: 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if we have a selected user
    const selectedUserId = {{ $selectedUserId ?? 'null' }};
    
    // Store references to active channels
    let userChannel = null;
    let chatChannel = null;
    
    // Set global variables for the messaging module
    window.userRole = "{{ Auth::user()->role }}";
    
    // Unified device detection - treat all devices equally for messaging
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
    // Function to check if messaging module is available with retry logic
    function isMessagingModuleAvailable() {
        // First check if it's directly available
        if (window.Messaging && typeof window.Messaging === 'object') {
            return true;
        }
        
        // On mobile devices, try to access it through the global scope
        if (typeof window.Messaging === 'undefined' && window.navigator.userAgent.match(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i)) {
            // Try to access it through alternative means
            if (window['Messaging'] && typeof window['Messaging'] === 'object') {
                window.Messaging = window['Messaging'];
                return true;
            }
        }
        
        return false;
    }
    
    // Function to subscribe to channels with error handling
    function subscribeToChannels() {
        // Subscribe to user channel for general notifications (always subscribe)
        if (typeof window.subscribeUserChannel === 'function') {
            try {
                userChannel = window.subscribeUserChannel();
                if (userChannel) {
                    // Use the centralized messaging module to set up user channel listeners
                    if (isMessagingModuleAvailable() && typeof window.Messaging.setupUserChannelListeners === 'function') {
                        window.Messaging.setupUserChannelListeners(userChannel, selectedUserId);
                    } else {
                        // Fallback implementation
                        setupUserChannelListenersFallback();
                    }
                }
            } catch (error) {
                console.error('Error subscribing to user channel:', error);
            }
        }
        
        // Subscribe to chat channel if we have a selected user
        if (selectedUserId && typeof window.subscribeChatChannel === 'function') {
            try {
                console.log('Subscribing to chat channel for users:', {{ Auth::id() }}, selectedUserId);
                chatChannel = window.subscribeChatChannel({{ Auth::id() }}, selectedUserId);
                if (chatChannel) {
                    console.log('Successfully subscribed to chat channel');
                    // Use the centralized messaging module to set up chat channel listeners
                    if (isMessagingModuleAvailable() && typeof window.Messaging.setupChatChannelListeners === 'function') {
                        window.Messaging.setupChatChannelListeners(chatChannel, selectedUserId);
                    } else {
                        // Fallback implementation
                        setupChatChannelListenersFallback();
                    }
                } else {
                    console.warn('Failed to subscribe to chat channel');
                }
            } catch (error) {
                console.error('Error subscribing to chat channel:', error);
            }
        }
    }
    
    // Fallback implementation for user channel listeners
    function setupUserChannelListenersFallback() {
        if (!userChannel) return;
        
        try {
            // Listen for new messages on the user channel
            userChannel.listen('.message.sent', function (data) {
                console.log('Received message on user channel:', data);
                
                // Check if we're in a conversation with the sender
                if (selectedUserId && (data.sender_id == selectedUserId || data.receiver_id == selectedUserId)) {
                    // We're in the right conversation, add the message to the chat
                    if (window.Messaging && typeof window.Messaging.addMessageToChat === 'function') {
                        window.Messaging.addMessageToChat(data, selectedUserId);
                    }
                } else {
                    // Not in the conversation, just update the unread counts
                    if (window.Messaging && typeof window.Messaging.updateContactUnreadCount === 'function') {
                        window.Messaging.updateContactUnreadCount(data.sender_id, null); // null means we need to fetch the count
                    }
                    if (window.Messaging && typeof window.Messaging.updateNavigationUnreadCount === 'function') {
                        window.Messaging.updateNavigationUnreadCount();
                    }
                }
            });
            
            // Listen for unread message count updates
            userChannel.listen('.unread.message.count.updated', function (data) {
                console.log('Received unread count update on user channel:', data);
                // Update the navigation unread count
                if (window.Messaging && typeof window.Messaging.updateNavigationUnreadCount === 'function') {
                    window.Messaging.updateNavigationUnreadCount();
                }
                
                // If we're viewing a conversation with this user, update that count too
                if (selectedUserId && data.userId == selectedUserId) {
                    if (window.Messaging && typeof window.Messaging.updateContactUnreadCount === 'function') {
                        window.Messaging.updateContactUnreadCount(selectedUserId, data.unread_count);
                    }
                }
            });
        } catch (error) {
            console.error('Error setting up user channel listeners:', error);
        }
    }
    
    // Fallback implementation for chat channel listeners
    function setupChatChannelListenersFallback() {
        if (!chatChannel) return;
        
        try {
            // Listen for new messages
            chatChannel.listen('.message.sent', function (data) {
                console.log('Received message on chat channel:', data);
                // Add message to chat if it's from the selected user
                if (selectedUserId && (data.sender_id == selectedUserId || data.receiver_id == selectedUserId)) {
                    if (window.Messaging && typeof window.Messaging.addMessageToChat === 'function') {
                        window.Messaging.addMessageToChat(data, selectedUserId);
                    }
                } else {
                    // Update unread count for the sender if they're not the selected user
                    if (window.Messaging && typeof window.Messaging.updateContactUnreadCount === 'function') {
                        window.Messaging.updateContactUnreadCount(data.sender_id, null); // null means we need to fetch the count
                    }
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
    
    // Unified enhancements for better reliability on all devices
    function setupUnifiedMessagingEnhancements() {
        console.log('Setting up unified messaging enhancements for all devices');
        
        // More frequent connection checks (every 30 seconds)
        setInterval(function() {
            if (document.visibilityState === 'visible') {
                console.log('Performing periodic connection check');
                subscribeToChannels();
                // Also check connection through messaging module
                if (isMessagingModuleAvailable() && typeof window.Messaging.checkConnectionAndResubscribe === 'function') {
                    window.Messaging.checkConnectionAndResubscribe();
                }
            }
        }, 30000); // 30 seconds
        
        // Force reconnection and resubscription when window gains focus
        window.addEventListener('focus', function() {
            console.log('Window focused, resubscribing to channels');
            subscribeToChannels();
            
            // Also update unread counts when returning to the app
            if (selectedUserId) {
                if (isMessagingModuleAvailable() && typeof window.Messaging.updateContactUnreadCount === 'function') {
                    window.Messaging.updateContactUnreadCount(selectedUserId, null);
                }
            }
            if (isMessagingModuleAvailable() && typeof window.Messaging.updateNavigationUnreadCount === 'function') {
                window.Messaging.updateNavigationUnreadCount();
            }
        });
        
        // Also check connection when page becomes visible
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'visible') {
                console.log('Page became visible, checking connection');
                subscribeToChannels();
                // Also check connection through messaging module
                if (isMessagingModuleAvailable() && typeof window.Messaging.checkConnectionAndResubscribe === 'function') {
                    window.Messaging.checkConnectionAndResubscribe();
                }
            }
        });
        
        // Additional handling for page lifecycle
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                console.log('Page was cached, resubscribing to channels');
                subscribeToChannels();
            }
        });
    }
    
    // Enhanced error handling for messaging module
    function checkMessagingModule() {
        if (!isMessagingModuleAvailable()) {
            console.error('Messaging module not available');
            // Instead of trying to load dynamically, show a more user-friendly error
            return false;
        }
        return true;
    }
    
    // Enhanced form submission handler with better error handling and mobile support
    function setupFormSubmission() {
        const messageForm = document.getElementById('message-form');
        if (messageForm) {
            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const messageInput = document.getElementById('message-input');
                const receiverId = document.getElementById('receiver-id').value;
                const message = messageInput.value.trim();
                
                if (message && receiverId) {
                    // Provide immediate feedback to user
                    const originalValue = messageInput.value;
                    messageInput.value = '';
                    
                    // Check if messaging module is available before sending
                    if (isMessagingModuleAvailable() && typeof window.Messaging.sendMessage === 'function') {
                        window.Messaging.sendMessage(receiverId, message, function(sentMessage) {
                            // The messaging module handles adding the message to the chat
                            // and clearing the input field
                            console.log('Message sent successfully');
                        });
                    } else {
                        console.error('Messaging module not available');
                        // Restore the message
                        messageInput.value = originalValue;
                        
                        // Try multiple recovery approaches
                        console.log('Attempting to recover messaging system...');
                        
                        // Approach 1: Resubscribe to channels
                        subscribeToChannels();
                        
                        // Approach 2: Check if we can access the module through alternative means
                        if (!isMessagingModuleAvailable()) {
                            // Try to initialize the messaging system again
                            if (typeof window.Messaging === 'undefined') {
                                console.log('Trying to reinitialize messaging module');
                                // Try to access through global scope
                                if (window['Messaging']) {
                                    window.Messaging = window['Messaging'];
                                }
                            }
                        }
                        
                        // Approach 3: If still not available, try a more direct approach
                        if (isMessagingModuleAvailable() && typeof window.Messaging.sendMessage === 'function') {
                            // Retry sending the message
                            window.Messaging.sendMessage(receiverId, message, function(sentMessage) {
                                console.log('Message sent successfully after recovery');
                            });
                        } else {
                            // Final fallback - direct AJAX request
                            console.log('Using fallback direct AJAX request');
                            sendDirectMessage(receiverId, message, messageInput, originalValue);
                        }
                    }
                }
            });
        }
    }
    
    // Fallback function to send message directly via AJAX
    function sendDirectMessage(receiverId, message, messageInput, originalValue) {
        let sendRoute;
        if (window.userRole === 'vet') {
            sendRoute = '/vet/messages/send';
        } else {
            sendRoute = '/user/messages/send';
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
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Message sent via fallback method');
                // Clear the input
                messageInput.value = '';
                // Try to add the message to the chat if possible
                if (isMessagingModuleAvailable() && typeof window.Messaging.addMessageToChat === 'function') {
                    window.Messaging.addMessageToChat(data.message, receiverId);
                }
            } else {
                // Restore the message on error
                messageInput.value = originalValue;
                alert('Error sending message: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error in fallback message sending:', error);
            // Restore the message
            messageInput.value = originalValue;
            alert('Error sending message. Please try again.');
        });
    }
    
    // Initialize everything with better error handling for mobile devices
    function initializeMessagingSystem() {
        console.log('Initializing messaging system');
        
        // Ensure we have the necessary global variables
        if (!window.userRole) {
            window.userRole = "{{ Auth::user()->role }}";
        }
        
        if (selectedUserId) {
            // Mark messages as read when conversation is opened
            if (isMessagingModuleAvailable() && typeof window.Messaging.markMessagesAsRead === 'function') {
                window.Messaging.markMessagesAsRead(selectedUserId);
            }
            
            // Scroll to bottom of message container
            const messageContainer = document.getElementById('message-container');
            if (messageContainer) {
                // Use a small delay to ensure proper rendering on mobile
                setTimeout(() => {
                    messageContainer.scrollTop = messageContainer.scrollHeight;
                }, 100);
            }
        }
        
        // Subscribe to channels
        subscribeToChannels();
        
        // Set up unified enhancements
        setupUnifiedMessagingEnhancements();
        
        // Set up form submission
        setupFormSubmission();
        
        // Additional mobile-specific initialization
        if (isMobile) {
            console.log('Additional mobile initialization');
            // Ensure we have proper visibility state handling
            if (document.visibilityState === 'hidden') {
                console.log('Page started hidden, will initialize when visible');
            }
            
            // Add additional mobile event listeners
            setupMobileSpecificHandlers();
        }
    }
    
    // Mobile-specific handlers
    function setupMobileSpecificHandlers() {
        // Handle mobile orientation changes
        window.addEventListener('orientationchange', function() {
            console.log('Orientation changed, reinitializing messaging');
            setTimeout(() => {
                // Resubscribe to channels after orientation change
                subscribeToChannels();
                
                // Scroll to bottom if we have a message container
                const messageContainer = document.getElementById('message-container');
                if (messageContainer) {
                    setTimeout(() => {
                        messageContainer.scrollTop = messageContainer.scrollHeight;
                    }, 100);
                }
            }, 500); // Small delay to allow UI to settle
        });
        
        // Handle mobile touch events that might affect messaging
        document.addEventListener('touchstart', function() {
            // Ensure messaging module is still available
            if (!isMessagingModuleAvailable()) {
                console.log('Touch detected but messaging module not available, attempting recovery');
                // Try to reinitialize
                subscribeToChannels();
            }
        }, { passive: true });
    }
    
    // Initialize with multiple attempts for mobile devices
    function robustInitialize() {
        try {
            initializeMessagingSystem();
        } catch (error) {
            console.error('Error during messaging system initialization:', error);
            
            // For mobile devices, try multiple initialization attempts
            if (isMobile) {
                console.log('Mobile device detected, attempting multiple initialization retries');
                
                // Retry after 1 second
                setTimeout(() => {
                    try {
                        initializeMessagingSystem();
                    } catch (retryError) {
                        console.error('Retry initialization failed:', retryError);
                        
                        // Final retry after 3 seconds
                        setTimeout(() => {
                            try {
                                initializeMessagingSystem();
                            } catch (finalError) {
                                console.error('Final initialization attempt failed:', finalError);
                                // Show a more specific error for mobile users
                                console.log('Mobile messaging system failed to initialize. Please check your connection and refresh the page.');
                            }
                        }, 3000);
                    }
                }, 1000);
            }
        }
    }
    
    // Start initialization
    if (document.readyState === 'loading') {
        // Document is still loading, wait for DOMContentLoaded
        document.addEventListener('DOMContentLoaded', robustInitialize);
    } else {
        // Document is already loaded, initialize immediately
        robustInitialize();
    }
    
    // Update unread count every 30 seconds as a fallback
    if (isMessagingModuleAvailable() && typeof window.Messaging.updateNavigationUnreadCount === 'function') {
        setInterval(window.Messaging.updateNavigationUnreadCount, 30000);
    }
    
    // Clean up on page unload
    window.addEventListener('beforeunload', function() {
        unsubscribeFromChannels();
    });
    
    // Handle window resize for responsive adjustments
    window.addEventListener('resize', function() {
        const messageContainer = document.getElementById('message-container');
        if (messageContainer) {
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }
    });
});
</script>
@endsection