// Messaging Module for Real-Time Chat Functionality
window.Messaging = (function() {
    'use strict';
    
    // Add mobile-specific event listeners
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    if (isMobile) {
        document.addEventListener('mobileMessageAdded', function(event) {
            console.log('Mobile message added:', event.detail);
            // Ensure UI is updated on mobile devices
            const messageContainer = document.getElementById('message-container');
            if (messageContainer) {
                // Force repaint on mobile
                messageContainer.style.transform = 'translateZ(0)';
                setTimeout(() => {
                    messageContainer.style.transform = '';
                }, 50);
            }
        });
        
        document.addEventListener('mobileMessageReceived', function(event) {
            console.log('Mobile message received:', event.detail);
            // Ensure UI is updated on mobile devices
            const messageContainer = document.getElementById('message-container');
            if (messageContainer) {
                // Force repaint on mobile
                messageContainer.style.transform = 'translateZ(0)';
                setTimeout(() => {
                    messageContainer.style.transform = '';
                }, 100);
            }
        });
        
        document.addEventListener('mobileUnreadCountUpdated', function(event) {
            console.log('Mobile unread count updated:', event.detail);
            // Ensure UI is updated on mobile devices
            const unreadCountElement = document.getElementById('unread-message-count');
            if (unreadCountElement) {
                // Force repaint on mobile
                unreadCountElement.style.transform = 'translateZ(0)';
                setTimeout(() => {
                    unreadCountElement.style.transform = '';
                }, 100);
            }
        });
        
        document.addEventListener('mobileChatMessageReceived', function(event) {
            console.log('Mobile chat message received:', event.detail);
            // Ensure UI is updated on mobile devices
            const messageContainer = document.getElementById('message-container');
            if (messageContainer) {
                // Force repaint on mobile
                messageContainer.style.transform = 'translateZ(0)';
                setTimeout(() => {
                    messageContainer.style.transform = '';
                }, 100);
            }
        });
    }
    
    // Format timestamp for messages
    function formatTimestamp(timestamp) {
        try {
            const date = new Date(timestamp);
            return date.toLocaleString('en-US', {
                timeZone: 'Asia/Manila',
                month: 'short',
                day: 'numeric',
                year: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        } catch (e) {
            console.error('Error formatting timestamp:', e);
            return new Date().toLocaleString('en-US', {
                timeZone: 'Asia/Manila',
                month: 'short',
                day: 'numeric',
                year: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        }
    }
    
    // Add a message to the chat container
    function addMessageToChat(data, selectedUserId) {
        try {
            const messageContainer = document.getElementById('message-container');
            if (!messageContainer) return;
            
            // Prevent duplicate messages
            const existingMessage = document.querySelector(`.message[data-message-id="${data.id}"]`);
            if (existingMessage) return;
            
            const messageDiv = document.createElement('div');
            messageDiv.className = `mb-2 mb-sm-3 ${data.sender_id == window.userId ? 'text-end' : 'text-start'} message`;
            messageDiv.setAttribute('data-message-id', data.id);
            
            const isCurrentUser = data.sender_id == window.userId;
            const bgColorClass = isCurrentUser ? 
                (window.userRole === "vet" ? 'bg-vet-green text-white' : 'bg-purple text-white') : 
                'bg-light';
            
            const messageContent = document.createElement('div');
            messageContent.className = `d-inline-block p-2 p-sm-3 rounded-3 shadow-sm ${bgColorClass}`;
            messageContent.style.maxWidth = '85%';
            messageContent.style.wordWrap = 'break-word';
            
            const formattedTimestamp = formatTimestamp(data.created_at);
            
            messageContent.innerHTML = `
                ${data.message}
                <div class="small mt-1">
                    <em>${formattedTimestamp}</em>
                </div>
            `;
            
            messageDiv.appendChild(messageContent);
            messageContainer.appendChild(messageDiv);
            
            // Scroll to bottom with enhanced mobile support
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            if (isMobile) {
                // On mobile, use a small delay to ensure DOM is updated before scrolling
                setTimeout(() => {
                    messageContainer.scrollTop = messageContainer.scrollHeight;
                    
                    // Force a reflow to ensure mobile browsers update the UI
                    messageContainer.style.overflow = 'hidden';
                    void messageContainer.offsetWidth; // Trigger reflow
                    messageContainer.style.overflow = 'auto';
                }, 150);
            } else {
                // Desktop behavior
                messageContainer.scrollTop = messageContainer.scrollHeight;
            }
            
            // Update unread count for the sender if they're not the current user
            if (!isCurrentUser && data.sender_id != selectedUserId) {
                updateContactUnreadCount(data.sender_id, null);
            }
            
            // Dispatch a custom event for mobile devices
            if (isMobile) {
                const event = new CustomEvent('mobileMessageAdded', { detail: { data, selectedUserId } });
                document.dispatchEvent(event);
            }
        } catch (error) {
            console.error('Error adding message to chat:', error);
        }
    }
    
    // Send a message
    function sendMessage(receiverId, message, callback) {
        if (!receiverId || !message) {
            console.error('Missing receiver ID or message content');
            return;
        }
        
        let sendRoute;
        if (window.userRole === "vet") {
            sendRoute = "/vet/messages/send";
        } else {
            sendRoute = "/user/messages/send";
        }
        
        fetch(sendRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
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
                // Clear the input field after successful send
                const messageInput = document.getElementById('message-input');
                if (messageInput) {
                    messageInput.value = '';
                }
                
                if (callback && typeof callback === 'function') {
                    callback(data.message);
                }
            } else {
                alert('Error sending message: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
            alert('Error sending message. Please try again.');
        });
    }
    
    // Update contact unread count
    function updateContactUnreadCount(contactId, unreadCount) {
        try {
            // If unreadCount is null, we need to fetch it
            if (unreadCount === null) {
                let fetchRoute;
                if (window.userRole === "vet") {
                    fetchRoute = "/vet/messages/contact-unread-count";
                } else {
                    fetchRoute = "/user/messages/contact-unread-count";
                }
                
                fetch(`${fetchRoute}?contact_id=${contactId}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    updateContactUnreadCount(contactId, data.unread_count);
                })
                .catch(error => {
                    console.error('Error fetching contact unread count:', error);
                });
                return;
            }
            
            // Update the badge for this contact
            const badge = document.querySelector(`.unread-count-badge[data-contact-id="${contactId}"]`);
            if (badge) {
                if (unreadCount > 0) {
                    badge.textContent = unreadCount;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            }
        } catch (error) {
            console.error('Error updating contact unread count:', error);
        }
    }
    
    // Update navigation unread count
    function updateNavigationUnreadCount() {
        try {
            let fetchRoute;
            if (window.userRole === "vet") {
                fetchRoute = "/vet/messages/unread-count";
            } else {
                fetchRoute = "/user/messages/unread-count";
            }
            
            fetch(fetchRoute, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                const unreadCountElements = document.querySelectorAll('#unread-message-count');
                unreadCountElements.forEach(element => {
                    if (data.unread_count > 0) {
                        element.textContent = data.unread_count;
                        element.style.display = 'inline-block';
                    } else {
                        element.style.display = 'none';
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching navigation unread count:', error);
            });
        } catch (error) {
            console.error('Error updating navigation unread count:', error);
        }
    }
    
    // Mark messages as read
    function markMessagesAsRead(contactId) {
        try {
            let markAsReadRoute;
            if (window.userRole === "vet") {
                markAsReadRoute = "/vet/messages/mark-as-read";
            } else {
                markAsReadRoute = "/user/messages/mark-as-read";
            }
            
            fetch(markAsReadRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    contact_id: contactId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the unread count for this contact
                    updateContactUnreadCount(contactId, 0);
                    // Update the navigation unread count
                    updateNavigationUnreadCount();
                }
            })
            .catch(error => {
                console.error('Error marking messages as read:', error);
            });
        } catch (error) {
            console.error('Error in markMessagesAsRead:', error);
        }
    }
    
    // Set up user channel listeners
    function setupUserChannelListeners(userChannel, selectedUserId) {
        if (!userChannel) return;
        
        try {
            // Listen for new messages on the user channel
            userChannel.listen('.message.sent', function (data) {
                console.log('Received message on user channel:', data);
                
                // Check if we're in a conversation with the sender
                if (selectedUserId && (data.sender_id == selectedUserId || data.receiver_id == selectedUserId)) {
                    // We're in the right conversation, add the message to the chat
                    addMessageToChat(data, selectedUserId);
                    
                    // Mobile-specific handling
                    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                    if (isMobile) {
                        // Ensure the UI updates on mobile devices
                        setTimeout(() => {
                            const event = new CustomEvent('mobileMessageReceived', { detail: data });
                            document.dispatchEvent(event);
                        }, 100);
                    }
                } else {
                    // Not in the conversation, just update the unread counts
                    updateContactUnreadCount(data.sender_id, null);
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
                
                // Mobile-specific handling
                const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                if (isMobile) {
                    // Ensure the UI updates on mobile devices
                    setTimeout(() => {
                        const event = new CustomEvent('mobileUnreadCountUpdated', { detail: data });
                        document.dispatchEvent(event);
                    }, 100);
                }
            });
        } catch (error) {
            console.error('Error setting up user channel listeners:', error);
        }
    }
    
    // Set up chat channel listeners
    function setupChatChannelListeners(chatChannel, selectedUserId) {
        if (!chatChannel) return;
        
        try {
            // Listen for new messages
            chatChannel.listen('.message.sent', function (data) {
                console.log('Received message on chat channel:', data);
                // Add message to chat if it's from the selected user
                if (selectedUserId && (data.sender_id == selectedUserId || data.receiver_id == selectedUserId)) {
                    addMessageToChat(data, selectedUserId);
                    
                    // Mobile-specific handling
                    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                    if (isMobile) {
                        // Ensure the UI updates on mobile devices
                        setTimeout(() => {
                            const event = new CustomEvent('mobileChatMessageReceived', { detail: data });
                            document.dispatchEvent(event);
                        }, 100);
                    }
                } else {
                    // Update unread count for the sender if they're not the selected user
                    updateContactUnreadCount(data.sender_id, null);
                }
            });
        } catch (error) {
            console.error('Error setting up chat channel listeners:', error);
        }
    }
    
    // Expose public methods
    return {
        formatTimestamp: formatTimestamp,
        addMessageToChat: addMessageToChat,
        sendMessage: sendMessage,
        updateContactUnreadCount: updateContactUnreadCount,
        updateNavigationUnreadCount: updateNavigationUnreadCount,
        markMessagesAsRead: markMessagesAsRead,
        setupUserChannelListeners: setupUserChannelListeners,
        setupChatChannelListeners: setupChatChannelListeners
    };
})();