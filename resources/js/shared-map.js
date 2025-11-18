/**
 * Shared Map Component
 * Provides consistent map functionality for both admin and user views
 */

class SharedMap {
    constructor(mapElementId, locations, options = {}) {
        this.mapElementId = mapElementId;
        this.locations = locations;
        this.options = {
            center: [8.507, 125.977], // Updated to correct coordinates for San Francisco, Agusan del Sur
            zoom: 15, // Changed from 13 to 15 to focus more closely on San Francisco, Agusan del Sur
            fullscreenEnabled: false,
            showToggleButtons: false,
            showViewDetails: false,
            viewDetailsRoute: '/admin/map/location/', // Default route
            ...options
        };
        
        this.map = null;
        this.markersLayer = null;
        this.fullscreenMap = null;
        this.fullscreenMarkersLayer = null;
        
        this.init();
    }
    
    init() {
        // Initialize the main map
        this.initMap();
        
        // Initialize fullscreen map if enabled
        if (this.options.fullscreenEnabled) {
            this.initFullscreenFunctionality();
        }
        
        // Add resize handling for mobile responsiveness
        this.addResizeHandling();
        
        // Add specific mobile handling
        this.addMobileHandling();
    }
    
    initMap() {
        // Create map centered on San Francisco, Agusan del Sur
        this.map = L.map(this.mapElementId, {
            zoomControl: false // Disable zoom controls
        }).setView(this.options.center, this.options.zoom);
        
        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(this.map);
        
        // Create markers layer group
        this.markersLayer = L.layerGroup().addTo(this.map);
        
        // Load markers
        this.loadMarkers(this.locations);
        
        // Ensure map renders properly
        this.map.whenReady(() => {
            setTimeout(() => {
                this.map.invalidateSize();
            }, 100);
        });
    }
    
    initFullscreenMap() {
        this.fullscreenMap = L.map('fullscreen-map', {
            zoomControl: false // Disable zoom controls
        }).setView(this.options.center, this.options.zoom);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(this.fullscreenMap);
        
        this.fullscreenMarkersLayer = L.layerGroup().addTo(this.fullscreenMap);
        
        // Load markers to fullscreen map
        this.loadMarkersToMap(this.fullscreenMap, this.fullscreenMarkersLayer, this.locations);
        
        // Ensure map renders properly
        setTimeout(() => {
            this.fullscreenMap.invalidateSize();
        }, 100);
        
        // Additional fix for clean desktop design
        setTimeout(() => {
            this.fullscreenMap.invalidateSize();
        }, 1000);
    }
    
    initFullscreenFunctionality() {
        const fullscreenBtn = document.getElementById('fullscreen-btn');
        const exitFullscreenBtn = document.getElementById('exit-fullscreen-btn');
        const fullscreenOverlay = document.getElementById('fullscreen-overlay');
        
        if (fullscreenBtn) {
            fullscreenBtn.addEventListener('click', () => {
                fullscreenOverlay.style.display = 'block';
                document.body.style.overflow = 'hidden';
                
                if (!this.fullscreenMap) {
                    this.initFullscreenMap();
                } else {
                    this.loadMarkersToMap(this.fullscreenMap, this.fullscreenMarkersLayer, this.locations);
                    setTimeout(() => {
                        this.fullscreenMap.invalidateSize();
                    }, 100);
                }
            });
        }
        
        if (exitFullscreenBtn) {
            exitFullscreenBtn.addEventListener('click', () => {
                this.exitFullscreen();
            });
        }
        
        if (fullscreenOverlay) {
            fullscreenOverlay.addEventListener('click', (e) => {
                if (e.target === fullscreenOverlay) {
                    this.exitFullscreen();
                }
            });
        }
        
        // Exit fullscreen on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && fullscreenOverlay && fullscreenOverlay.style.display === 'block') {
                this.exitFullscreen();
            }
        });
    }
    
    exitFullscreen() {
        const fullscreenOverlay = document.getElementById('fullscreen-overlay');
        if (fullscreenOverlay) {
            fullscreenOverlay.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }
    
    loadMarkers(locationData) {
        this.loadMarkersToMap(this.map, this.markersLayer, locationData);
    }
    
    loadMarkersToMap(targetMap, targetMarkersLayer, locationData) {
        // Clear existing markers
        targetMarkersLayer.clearLayers();
        
        if (!locationData || locationData.length === 0) {
            return;
        }
        
        const bounds = [];
        
        locationData.forEach(location => {
            if (location.latitude && location.longitude) {
                const lat = parseFloat(location.latitude);
                const lng = parseFloat(location.longitude);
                
                // Check if this is a lost/found item or a shelter/service
                let customIcon;
                if (location.hasOwnProperty('image_path') || location.hasOwnProperty('type') && (location.type === 'lost' || location.type === 'found')) {
                    // Handle lost/found items
                    customIcon = this.createLostFoundIcon(location);
                } else {
                    // Handle shelter/service items
                    customIcon = this.createShelterIcon(location);
                }
                
                // Create marker
                const marker = L.marker([lat, lng], { icon: customIcon });
                
                // Create popup content
                const popupContent = this.createPopupContent(location);
                
                marker.bindPopup(popupContent, {
                    maxWidth: 300,
                    minWidth: 250,
                    autoPan: true,
                    autoPanPadding: [20, 20],
                    className: 'custom-popup'
                });
                
                marker.on('popupopen', (e) => {
                    // Ensure popup is visible on desktop devices
                    setTimeout(() => {
                        targetMap.invalidateSize();
                    }, 100);
                });
                
                targetMarkersLayer.addLayer(marker);
                bounds.push([lat, lng]);
            }
        });
        
        // Fit map to markers if we have any
        if (bounds.length > 0) {
            if (bounds.length === 1) {
                targetMap.setView(bounds[0], 14);
            } else {
                targetMap.fitBounds(bounds, { padding: [20, 20] });
            }
        }
        
        // Add additional event listeners for clean desktop design
        targetMap.on('popupopen', (e) => {
            // Ensure popup is visible on desktop devices
            setTimeout(() => {
                targetMap.invalidateSize();
            }, 100);
        });
    }
    
    createShelterIcon(location) {
        // Use only veterinarian icon for all locations
        let iconClass = 'fas fa-user-md';
        let iconColor = '#10b981';
        
        // Create custom icon
        return L.divIcon({
            html: `<div style="background: ${iconColor}; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"><i class="${iconClass}" style="font-size: 12px;"></i></div>`,
            iconSize: [36, 36],
            iconAnchor: [18, 18],
            popupAnchor: [0, -18],
            className: 'custom-marker'
        });
    }
    
    createLostFoundIcon(location) {
        // Create custom icon with pet image if available
        let iconHtml = '';
        if (location.image_path) {
            // Use the pet image as the marker without any icon overlay
            iconHtml = `<div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                <img src="/storage/${location.image_path}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>`;
        } else {
            // Use the default icon if no image is available
            const color = location.type === 'lost' ? '#e74c3c' : '#27ae60';
            const iconClass = location.type === 'lost' ? 'fas fa-heart-broken' : 'fas fa-heart';
            iconHtml = `<div style="background: ${color}; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                <i class="${iconClass}" style="font-size: 16px;"></i>
            </div>`;
        }
        
        return L.divIcon({
            html: iconHtml,
            iconSize: location.image_path ? [56, 56] : [46, 46],
            iconAnchor: location.image_path ? [28, 28] : [23, 23],
            popupAnchor: [0, -28],
            className: 'custom-marker'
        });
    }
    
    createPopupContent(location) {
        // Check if this is a lost/found item
        if (location.hasOwnProperty('image_path') || location.hasOwnProperty('type') && (location.type === 'lost' || location.type === 'found')) {
            return this.createLostFoundPopupContent(location);
        } else {
            return this.createShelterPopupContent(location);
        }
    }
    
    createShelterPopupContent(location) {
        let viewDetailsButton = '';
        if (this.options.showViewDetails && location.id) {
            viewDetailsButton = `
                <div style="display: flex; gap: 5px; margin-top: 12px;">
                    <a href="${this.options.viewDetailsRoute}${location.id}" style="background: #667eea; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
                        <i class="fas fa-eye"></i> View Details
                    </a>
                </div>
            `;
        }
        
        return `
            <div style="min-width: 250px; max-width: 300px;">
                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                    <div style="background: #10b981; color: white; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 1.1rem; color: #1f2937;">${location.name}</h4>
                        <span style="background: #e5e7eb; color: #374151; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Veterinarian</span>
                    </div>
                </div>
                <div style="margin-bottom: 8px; color: #4b5563;">
                    <i class="fas fa-map-marker-alt" style="color: #667eea; margin-right: 6px;"></i>
                    ${location.address}<br>
                    ${location.city}, ${location.province}
                </div>
                <div style="margin-bottom: 8px; color: #4b5563;">
                    <i class="fas fa-phone" style="color: #667eea; margin-right: 6px;"></i>
                    ${location.phone || 'Not provided'}
                </div>
                ${location.email ? `
                    <div style="margin-bottom: 12px; color: #4b5563;">
                        <i class="fas fa-envelope" style="color: #667eea; margin-right: 6px;"></i>
                        ${location.email}
                    </div>
                ` : ''}
                ${viewDetailsButton}
            </div>
        `;
    }
    
    createLostFoundPopupContent(location) {
        let viewDetailsRoute = '/lost-found/';
        if (this.options.viewDetailsRoute && this.options.viewDetailsRoute.includes('admin')) {
            viewDetailsRoute = '/admin/lost-found/';
        }
        
        const color = location.type === 'lost' ? '#e74c3c' : '#27ae60';
        const iconClass = location.type === 'lost' ? 'fas fa-heart-broken' : 'fas fa-heart';
        
        return `
            <div style="min-width: 250px; max-width: 300px;">
                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                    ${location.image_path ? 
                        `<div style="width: 60px; height: 60px; border-radius: 8px; overflow: hidden; margin-right: 12px;">
                            <img src="/storage/${location.image_path}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>` : 
                        `<div style="background: ${color}; color: white; width: 60px; height: 60px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                            <i class="${iconClass}" style="font-size: 24px;"></i>
                        </div>`
                    }
                    <div>
                        <h4 style="margin: 0; font-size: 1.1rem; color: #1f2937;">${location.pet_name}</h4>
                        <span style="background: #e5e7eb; color: #374151; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">${location.type.charAt(0).toUpperCase() + location.type.slice(1)} Pet</span>
                    </div>
                </div>
                <div style="margin-bottom: 8px; color: #4b5563;">
                    <i class="fas fa-paw" style="color: #667eea; margin-right: 6px;"></i>
                    ${location.pet_type} ${location.breed ? `(${location.breed})` : ''}
                </div>
                <div style="margin-bottom: 8px; color: #4b5563;">
                    <i class="fas fa-map-marker-alt" style="color: #667eea; margin-right: 6px;"></i>
                    ${location.location}
                </div>
                <div style="margin-bottom: 8px; color: #4b5563;">
                    <i class="fas fa-calendar" style="color: #667eea; margin-right: 6px;"></i>
                    ${new Date(location.date_lost_found).toLocaleDateString()}
                </div>
                <div style="margin-bottom: 12px; color: #4b5563;">
                    <i class="fas fa-user" style="color: #667eea; margin-right: 6px;"></i>
                    Reported by ${location.user.name}
                </div>
                <div style="display: flex; gap: 5px; margin-top: 12px;">
                    <a href="${viewDetailsRoute}${location.id}" style="background: #667eea; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
                        <i class="fas fa-eye"></i> View Details
                    </a>
                </div>
            </div>
        `;
    }
    
    getIconClass(type) {
        // Always return veterinarian icon
        return 'fas fa-user-md';
    }

    getIconColor(type) {
        // Always return veterinarian color
        return '#10b981';
    }
    
    getTypeName(type) {
        switch(type) {
            case 'pet_shop': return 'Pet Shop';
            case 'veterinarian': return 'Veterinarian';
            case 'grooming': return 'Grooming Service';
            default: return type;
        }
    }
    
    // Update markers with new data
    updateMarkers(newLocations) {
        this.locations = newLocations;
        this.loadMarkers(this.locations);
        
        if (this.fullscreenMap) {
            this.loadMarkersToMap(this.fullscreenMap, this.fullscreenMarkersLayer, this.locations);
        }
    }
    
    // Add resize handling for mobile responsiveness
    addResizeHandling() {
        // Handle window resize events
        let resizeTimeout;
        window.addEventListener('resize', () => {
            // Clear the timeout
            clearTimeout(resizeTimeout);
            
            // Set a new timeout
            resizeTimeout = setTimeout(() => {
                // Invalidate map size when window is resized
                if (this.map) {
                    this.map.invalidateSize();
                }
                
                // Invalidate fullscreen map size if it exists
                if (this.fullscreenMap) {
                    this.fullscreenMap.invalidateSize();
                }
            }, 100);
        });
        
        // Also handle orientation change events for mobile devices
        window.addEventListener('orientationchange', () => {
            // Small delay to allow orientation change to complete
            setTimeout(() => {
                // Invalidate map size when orientation changes
                if (this.map) {
                    this.map.invalidateSize();
                }
                
                // Invalidate fullscreen map size if it exists
                if (this.fullscreenMap) {
                    this.fullscreenMap.invalidateSize();
                }
            }, 300);
        });
    }
    
    // Add specific mobile handling for better map visibility
    addMobileHandling() {
        // Check if we're on a mobile device
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        
        if (isMobile) {
            // Add a small delay to ensure the map container is properly rendered
            setTimeout(() => {
                if (this.map) {
                    this.map.invalidateSize();
                }
            }, 500);
            
            // Additional resize handling for mobile
            let mobileResizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(mobileResizeTimeout);
                mobileResizeTimeout = setTimeout(() => {
                    if (this.map) {
                        this.map.invalidateSize();
                        // Re-center the map on the location
                        if (this.locations && this.locations.length > 0 && this.locations[0].latitude && this.locations[0].longitude) {
                            this.map.setView([this.locations[0].latitude, this.locations[0].longitude], this.options.zoom);
                        }
                    }
                }, 300);
            });
        }
    }
}

// Export for use in other modules
export default SharedMap;