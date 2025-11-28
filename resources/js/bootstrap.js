import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Attach Pusher to the window object for global access
window.Pusher = Pusher;

// Configure Pusher for desktop
window.Pusher.prototype.originalConnection = window.Pusher.prototype.connect;
window.Pusher.prototype.connect = function(options) {
    // Simplified connection handling for desktop
    const desktopOptions = {
        ...options,
        enabledTransports: ['ws', 'wss'],
        activityTimeout: 30000, // 30 seconds
        pongTimeout: 15000, // 15 seconds
        maxReconnectionAttempts: 5,
        reconnectInterval: 1000 // 1 second
    };
    
    return this.originalConnection(desktopOptions);
};

// Initialize Laravel Echo with Pusher configuration
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    encrypted: true,
    disableStats: true, // Disable stats collection for better performance
    enabledTransports: ['ws', 'wss'],
    // Enable connection recovery
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        }
    }
});

// Connection handling
window.Echo.connector.pusher.connection.bind('connecting', function() {
    console.log('Pusher connecting...');
});

window.Echo.connector.pusher.connection.bind('connected', function() {
    console.log('Pusher connected');
    resubscribeToChannels();
});

window.Echo.connector.pusher.connection.bind('disconnected', function() {
    console.log('Pusher disconnected');
});

window.Echo.connector.pusher.connection.bind('reconnecting', function() {
    console.log('Pusher reconnecting...');
});

window.Echo.connector.pusher.connection.bind('error', function(err) {
    console.error('Pusher connection error:', err);
});

// Store active channel subscriptions
window.activeChannels = new Set();

// Function to track channel subscriptions
window.trackChannelSubscription = function(channelName) {
    window.activeChannels.add(channelName);
};

// Function to untrack channel subscriptions
window.untrackChannelSubscription = function(channelName) {
    window.activeChannels.delete(channelName);
};

// Function to resubscribe to all channels
window.resubscribeToChannels = function() {
    console.log('Resubscribing to channels:', Array.from(window.activeChannels));
    
    // Resubscribe to user channel if it was previously subscribed
    if (window.userId && window.activeChannels.has('users.' + window.userId)) {
        const userChannel = window.subscribeUserChannel();
        if (userChannel) {
            console.log('Resubscribed to user channel');
        }
    }
    
    // Resubscribe to chat channels if they were previously subscribed
    // Note: This requires storing chat channel information in a more persistent way
    // For now, we'll handle this in the individual pages
};

// Function to subscribe to the user's private channel with better error handling
window.subscribeUserChannel = function() {
    if (window.Echo && window.userId) {
        try {
            const channel = window.Echo.private('users.' + window.userId);
            window.trackChannelSubscription('users.' + window.userId);
            
            // Handle subscription errors
            channel.error(function(error) {
                console.error('User channel subscription error:', error);
            });
            
            return channel;
        } catch (error) {
            console.error('Error subscribing to user channel:', error);
            return null;
        }
    }
    return null;
};

// Function to subscribe to a shared chat channel with better error handling
window.subscribeChatChannel = function(userId1, userId2) {
    if (window.Echo) {
        try {
            // Create channel name using min and max user IDs
            const minUserId = Math.min(userId1, userId2);
            const maxUserId = Math.max(userId1, userId2);
            const channelName = `chat.${minUserId}.${maxUserId}`;
            
            const channel = window.Echo.private(channelName);
            window.trackChannelSubscription(channelName);
            
            // Handle subscription errors
            channel.error(function(error) {
                console.error('Chat channel subscription error:', error);
            });
            
            return channel;
        } catch (error) {
            console.error('Error subscribing to chat channel:', error);
            return null;
        }
    }
    return null;
};

// Add page visibility API handling
document.addEventListener('visibilitychange', function() {
    if (!window.Echo || !window.Echo.connector || !window.Echo.connector.pusher) {
        return;
    }
    
    if (document.visibilityState === 'visible') {
        // Page became visible, check connection state
        const state = window.Echo.connector.pusher.connection.state;
        console.log('Page became visible, connection state:', state);
        
        // If disconnected, try to reconnect
        if (state === 'disconnected') {
            console.log('Attempting to reconnect Pusher');
            window.Echo.connector.pusher.connect();
        }
    }
});

// Add online/offline handling
window.addEventListener('online', function() {
    console.log('Browser went online');
    if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
        // Try to reconnect when browser comes online
        window.Echo.connector.pusher.connect();
    }
});

window.addEventListener('offline', function() {
    console.log('Browser went offline');
    // Connection will be automatically handled by Pusher
});

// Mobile-specific connection handling for better reliability
function setupMobileConnectionHandling() {
    // Check if we're on a mobile device
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
    if (isMobile) {
        console.log('Setting up mobile-specific connection handling');
        
        // Force reconnection every 2 minutes to prevent mobile connection issues
        setInterval(function() {
            if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
                const state = window.Echo.connector.pusher.connection.state;
                console.log('Mobile connection check - Current state:', state);
                
                // If not connected, try to reconnect
                if (state !== 'connected') {
                    console.log('Forcing reconnection for mobile device');
                    window.Echo.connector.pusher.connect();
                }
            }
        }, 120000); // 2 minutes
        
        // Additional handling for mobile page lifecycle events
        let hiddenTime = null;
        
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                // Page is hidden, record the time
                hiddenTime = Date.now();
            } else {
                // Page is visible again
                if (hiddenTime) {
                    const timeHidden = Date.now() - hiddenTime;
                    console.log('Page was hidden for', timeHidden, 'ms');
                    
                    // If page was hidden for more than 15 seconds, force reconnection
                    if (timeHidden > 15000) {
                        console.log('Page was hidden for >15s, forcing reconnection');
                        if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
                            window.Echo.connector.pusher.connect();
                        }
                    }
                }
            }
        });
        
        // Handle mobile app going to background/foreground
        window.addEventListener('focus', function() {
            console.log('Window focused, checking connection');
            if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
                const state = window.Echo.connector.pusher.connection.state;
                if (state !== 'connected') {
                    window.Echo.connector.pusher.connect();
                }
            }
        });
    }
}

// Automatically subscribe to user channel on all pages if user is logged in
// This needs to work on both desktop and mobile
document.addEventListener('DOMContentLoaded', function() {
    if (window.userId && typeof window.subscribeUserChannel === 'function') {
        console.log('Subscribing to user channel for user:', window.userId);
        try {
            const userChannel = window.subscribeUserChannel();
            if (userChannel) {
                // Set up listener for unread count updates
                userChannel.listen('.unread.message.count.updated', function (data) {
                    console.log('Received global unread count update:', data);
                    // Check if this update is for the current user
                    if (data.userId == window.userId) {
                        const unreadCountElement = document.getElementById('unread-message-count');
                        if (unreadCountElement) {
                            if (data.unread_count > 0) {
                                unreadCountElement.textContent = data.unread_count;
                                unreadCountElement.style.display = 'inline-block';
                            } else {
                                unreadCountElement.style.display = 'none';
                            }
                        }
                        
                        // Also update any other unread count elements (for different layouts)
                        const allUnreadCountElements = document.querySelectorAll('[id="unread-message-count"]');
                        allUnreadCountElements.forEach(element => {
                            // Skip the element we already updated
                            if (element === unreadCountElement) return;
                            
                            if (data.unread_count > 0) {
                                element.textContent = data.unread_count;
                                element.style.display = 'inline-block';
                            } else {
                                element.style.display = 'none';
                            }
                        });
                    }
                });
            }
        } catch (error) {
            console.error('Error subscribing to user channel globally:', error);
        }
    }
    
    // Set up mobile connection handling
    setupMobileConnectionHandling();
});

// Also subscribe when page becomes visible (important for mobile)
document.addEventListener('visibilitychange', function() {
    if (document.visibilityState === 'visible') {
        if (window.userId && typeof window.subscribeUserChannel === 'function') {
            console.log('Page became visible, ensuring user channel subscription');
            try {
                // Check if we're already subscribed
                const expectedChannel = 'users.' + window.userId;
                if (!window.activeChannels || !window.activeChannels.has(expectedChannel)) {
                    const userChannel = window.subscribeUserChannel();
                    console.log('Re-subscribed to user channel on page visibility change');
                }
            } catch (error) {
                console.error('Error re-subscribing to user channel on visibility change:', error);
            }
        }
    }
});