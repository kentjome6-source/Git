import './bootstrap';
import './sweetalert-config';
import SharedMap from './shared-map';

// Make SharedMap available globally
window.SharedMap = SharedMap;

// Get the current user ID from meta tag
const userIdMeta = document.querySelector('meta[name="current-user-id"]');
window.userId = userIdMeta ? userIdMeta.getAttribute('content') : null;

// Initialize sidebar based on screen sizes
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing sidebar');
    
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    
    if (sidebar) {
        // Check screen size
        if (window.innerWidth <= 768) {
            // Mobile: hide sidebar by default
            sidebar.classList.add('collapsed');
        } else {
            // Desktop: show sidebar
            sidebar.classList.add('active');
            sidebar.style.transform = 'translateX(0)';
        }
    }
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (sidebar) {
            if (window.innerWidth > 768) {
                // On desktop, always show sidebar
                sidebar.classList.remove('active', 'collapsed');
                sidebar.style.transform = 'translateX(0)';
                if (overlay) {
                    overlay.classList.remove('active');
                }
            } else {
                // On mobile, hide sidebar by default
                sidebar.classList.remove('active');
                sidebar.classList.add('collapsed');
                if (overlay) {
                    overlay.classList.remove('active');
                }
            }
        }
    });
});