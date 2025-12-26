<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PawPortal')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
        <meta name="current-user-id" content="{{ Auth::id() }}">
    @endauth
    {{-- FontAwesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    {{-- ✅ Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Responsive CSS --}}
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">
    {{-- Vite --}}
    @vite(['resources/js/app.js'])

    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f4f6f8; color: #333; }
        .dashboard-container { display:flex; min-height:100vh; }
        
        /* Typography improvements for mobile */
        html {
            font-size: 16px;
        }
        
        body {
            font-size: 1rem;
            line-height: 1.6;
        }
        
        h1, .h1 { font-size: 2.5rem; }
        h2, .h2 { font-size: 2rem; }
        h3, .h3 { font-size: 1.75rem; }
        h4, .h4 { font-size: 1.5rem; }
        h5, .h5 { font-size: 1.25rem; }
        h6, .h6 { font-size: 1.1rem; }
        
        /* Sidebar */
        .sidebar {
            width:250px; background:#fff; border-right:1px solid #ddd;
            display:flex; flex-direction:column; position:fixed; height:100vh; padding-top:20px;
            z-index: 1001; /* Increased to overlay Leaflet map elements */
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
            overflow-y: hidden; /* Changed from auto to hidden to control scrolling manually */
        }

        /* Added scrollable container for menu items */
        .sidebar-menu-container {
            flex: 1;
            overflow-y: auto;
            padding: 0 20px;
            margin-bottom: 20px;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .sidebar-header { text-align:center; margin-bottom:30px; padding: 0 20px; }
        .sidebar-header .logo { font-size:1.8rem; font-weight:bold; color:#5b4b9b; }
        .sidebar-header .subtitle { font-size:0.85rem; color:#888; }

        .menu { }
        .menu-item { margin:10px 0; }
        .menu-link {
            display:flex; align-items:center; padding:12px 20px;
            text-decoration:none; color:#333; border-radius:10px; transition:0.2s;
            font-size: 0.95rem;
        }
        .menu-link:hover { background:#f0f0f0; }
        .menu-link.active { background:#5b4b9b; color:#fff; }
        .menu-icon { width:25px; text-align:center; margin-right:12px; color:#5b4b9b; }
        .menu-link.active .menu-icon { color:#fff; }
        .menu-text { font-weight:500; font-size:0.95rem; }

        /* Profile Section */
        .profile-section { padding:20px; border-top:1px solid #ddd; }
        .profile-info { display:flex; align-items:center; margin-bottom:15px; }
        .profile-avatar {
            width:45px; height:45px; border-radius:50%;
            background: linear-gradient(135deg, #5b4b9b 0%, #6a5fac 100%); 
            color:#fff; display:flex; align-items:center; justify-content:center; 
            font-weight:bold; margin-right:12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .profile-details h4 { font-size:0.95rem; margin-bottom:2px; font-weight: 600; }
        .profile-details p { font-size:0.8rem; color:#777; margin-bottom: 0; }
        .profile-links { display:flex; flex-direction:column; gap:8px; }
        .profile-btn {
            display:flex; align-items:center; justify-content:center; gap:5px;
            padding:10px; border:none; border-radius:8px; font-size:0.85rem; cursor:pointer; text-decoration:none; text-align:center;
            transition: all 0.2s ease;
        }
        .profile-btn.edit { 
            background: linear-gradient(135deg, #5b4b9b 0%, #6a5fac 100%);
            color:#fff; 
        }
        .profile-btn.logout { 
            background: linear-gradient(135deg, #e74c3c 0%, #ff6b6b 100%);
            color:#fff; 
        }
        .profile-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .profile-btn.edit:hover { 
            background: linear-gradient(135deg, #4a3d82 0%, #5b4b9b 100%);
        }
        .profile-btn.logout:hover { 
            background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%);
        }

        /* Main Content */
        .main-content { flex:1; margin-left:250px; padding:40px; padding-top: 20px; transition: margin-left 0.3s ease; }

        /* Mobile Header */
        .mobile-header {
            display: none;
            background: #5b4b9b;
            color: white;
            padding: 15px 20px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            height: 63.59px;
        }
        
        .mobile-header .logo.d-flex.align-items-center {
            height: 33.59px;
            display: flex !important;
            align-items: center !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            gap: 0 !important;
        }
        
        .mobile-header .logo span {
            display: inline-block;
            height: 33.59px;
            line-height: 33.59px;
        }
        
        .menu-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            width: 18.38px;
            height: 33.59px;
        }

        /* Focus indicators for keyboard navigation */
        .menu-link:focus, 
        .profile-btn:focus, 
        a:focus, 
        button:focus {
            outline: 2px solid #5b4b9b;
            outline-offset: 2px;
        }

        /* Skip to main content link for screen readers */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 6px;
            background: #5b4b9b;
            color: white;
            padding: 8px;
            z-index: 1000;
            border-radius: 4px;
        }

        .skip-link:focus {
            top: 6px;
        }

        /* Screen reader only class */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* Social Media Post Image */
        .post-image-container {
            max-height: 400px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    
        .post-image {
            max-height: 400px;
            width: auto;
            object-fit: cover;
            border-radius: 0.5rem;
        }
    
        /* Profile picture in sidebar */
        .sidebar-profile-picture {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 12px;
            border: 2px solid #5b4b9b;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        
        /* Overlay for mobile */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.4);
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .overlay.active {
            display: block;
            opacity: 1;
        }
        
        /* Prevent background scrolling when sidebar is open on mobile */
        .sidebar-open {
            overflow: hidden;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            html {
                font-size: 15px;
            }
            
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                box-shadow: 2px 0 10px rgba(0,0,0,0.2);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 80px 20px 20px 20px;
            }
            
            .mobile-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            h1, .h1 { font-size: 2rem; }
            h2, .h2 { font-size: 1.75rem; }
            h3, .h3 { font-size: 1.5rem; }
            h4, .h4 { font-size: 1.25rem; }
            h5, .h5 { font-size: 1.1rem; }
            h6, .h6 { font-size: 1rem; }
        }
        
        @media (max-width: 576px) {
            html {
                font-size: 14px;
            }
            
            .sidebar {
                width: 230px; /* Slightly smaller on very small screens */
            }
            
            .main-content {
                padding: 80px 15px 15px 15px;
            }
            
            h1, .h1 { font-size: 1.75rem; }
            h2, .h2 { font-size: 1.5rem; }
            h3, .h3 { font-size: 1.35rem; }
            h4, .h4 { font-size: 1.2rem; }
            h5, .h5 { font-size: 1.1rem; }
            h6, .h6 { font-size: 1rem; }
        }

    </style>

@yield('styles')
</head>
<body>
    <!-- Skip to main content link for accessibility -->
    <a href="#main-content" class="skip-link sr-only">Skip to main content</a>
    
    <!-- Mobile Header -->
    <div class="mobile-header">
        <div class="logo d-flex align-items-center">
            <img src="{{ asset('images/logo/logo.png') }}" alt="PawPortal Logo" class="img-fluid" style="width: 30px; height: 30px; padding: 0; margin: 0;">
            <span style="width: 99.94px; height: 33.59px; display: inline-block; line-height: 33.59px; padding: 0; margin: 0;">PawPortal</span>
        </div>
        <button class="menu-toggle" id="menu-toggle">
            <i class="fas fa-bars" style="width: 18.38px; height: 21px;"></i>
        </button>
    </div>
    
    <!-- Overlay for mobile -->
    <div class="overlay" id="overlay"></div>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar" role="navigation" aria-label="Main navigation">
        <div class="sidebar-header">
            <div class="logo d-flex align-items-center justify-content-center mb-2">
                <img src="{{ asset('images/logo/logo.png') }}" alt="PawPortal Logo" class="img-fluid me-2" style="max-height: 40px; width: auto;">
                <span class="fs-5 fw-bold">PawPortal</span>
            </div>
            <div class="subtitle text-center">Your Pet's Digital Home</div>
        </div>

        <!-- Scrollable container for menu items -->
        <div class="sidebar-menu-container">
            <div class="menu">
                <div class="menu-item">
                    <a href="{{ route('appointments.index') }}" class="menu-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                        <i class="fas fa-stethoscope menu-icon" aria-hidden="true"></i>
                        <span class="menu-text">Appointment</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('pet.multipet.index') }}" class="menu-link {{ request()->routeIs('pet.multipet.*') ? 'active' : '' }}">
                        <i class="fas fa-dog menu-icon" aria-hidden="true"></i>
                        <span class="menu-text">Multi-Pet Dashboard</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('adoptions.index') }}" class="menu-link {{ request()->routeIs('adoptions.index') || request()->routeIs('adoptions.show') || request()->routeIs('adoptions.create') ? 'active' : '' }}">
                        <i class="fas fa-heart menu-icon" aria-hidden="true"></i>
                        <span class="menu-text">Adoption Center</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('adoptions.my-applications') }}" class="menu-link {{ request()->routeIs('adoptions.my-applications') ? 'active' : '' }}">
                        <i class="fas fa-file-alt menu-icon" aria-hidden="true"></i>
                        <span class="menu-text">My Applications</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('view.map') }}" class="menu-link {{ request()->routeIs('view.map*') || request()->routeIs('view-map.show') ? 'active' : '' }}">
                        <i class="fas fa-map menu-icon" aria-hidden="true"></i>
                        <span class="menu-text">Services</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('my.pets') }}" class="menu-link {{ request()->routeIs('my.pets*') ? 'active' : '' }}">
                        <i class="fas fa-paw menu-icon" aria-hidden="true"></i>
                        <span class="menu-text">My Pet</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('social-media.index') }}" class="menu-link {{ request()->routeIs('social-media.*') ? 'active' : '' }}">
                        <i class="fas fa-users menu-icon" aria-hidden="true"></i>
                        <span class="menu-text">Feed</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('pet.lostfound') }}" class="menu-link {{ request()->routeIs('pet.lostfound') || request()->routeIs('lost-found.*') ? 'active' : '' }}">
                        <i class="fas fa-search-location menu-icon" aria-hidden="true"></i>
                        <span class="menu-text">Lost & Found</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('messages.index') }}" class="menu-link {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                        <i class="fas fa-comments menu-icon" aria-hidden="true"></i>
                        <span class="menu-text">Messages</span>
                        <span id="unread-message-count" class="badge bg-danger ms-2" style="display: none;">0</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="profile-section">
            <div class="profile-info">
                @if(Auth::user()->profile_picture_path)
                    <img src="{{ Auth::user()->profile_picture_url }}" alt="Profile Picture" class="sidebar-profile-picture">
                @else
                    <div class="profile-avatar" aria-label="User avatar">{{ substr(Auth::user()->name,0,2) }}</div>
                @endif
                <div class="profile-details">
                    <h4>{{ Auth::user()->name }}</h4>
                    <p>Pet Parent</p>
                </div>
            </div>

            <div class="profile-links">
                <a href="{{ route('user.profile.edit') }}" class="profile-btn edit" aria-label="Edit profile">
                    <i class="fas fa-user-edit" aria-hidden="true"></i> Profile
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="profile-btn logout" aria-label="Logout">
                        <i class="fas fa-sign-out-alt" aria-hidden="true"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="main-content" tabindex="-1">
        @yield('content')
    </div>
</div>

{{-- ✅ Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- SweetAlert2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- SweetAlert2 Helper Functions --}}
<script>
    function showSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: message,
            confirmButtonColor: '#10b981',
            confirmButtonText: 'OK'
        });
    }

    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: message,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'OK'
        });
    }

    function showWarning(message) {
        Swal.fire({
            icon: 'warning',
            title: 'Warning!',
            text: message,
            confirmButtonColor: '#f59e0b',
            confirmButtonText: 'OK'
        });
    }

    function showInfo(message) {
        Swal.fire({
            icon: 'info',
            title: 'Information',
            text: message,
            confirmButtonColor: '#3b82f6',
            confirmButtonText: 'OK'
        });
    }

    function showConfirm(message, title = 'Are you sure?', confirmText = 'Yes', cancelText = 'No') {
        return Swal.fire({
            title: title,
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: confirmText,
            cancelButtonText: cancelText
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        
        // Toggle sidebar on menu button click
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            // Prevent background scrolling when sidebar is open
            document.body.classList.toggle('sidebar-open', sidebar.classList.contains('active'));
        });
        
        // Close sidebar when clicking overlay
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            // Allow background scrolling when sidebar is closed
            document.body.classList.remove('sidebar-open');
        });
        
        // Close sidebar when clicking a menu item (on mobile)
        const menuLinks = document.querySelectorAll('.menu-link');
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    // Allow background scrolling when sidebar is closed
                    document.body.classList.remove('sidebar-open');
                }
            });
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                // On desktop, always show sidebar
                sidebar.classList.remove('active');
                sidebar.classList.remove('collapsed');
                overlay.classList.remove('active');
                // Allow background scrolling on desktop
                document.body.classList.remove('sidebar-open');
            } else {
                // On mobile, hide sidebar by default
                sidebar.classList.add('collapsed');
                // Maintain sidebar-open class if sidebar is active
                document.body.classList.toggle('sidebar-open', sidebar.classList.contains('active'));
            }
        });
        
    });
</script>

{{-- SweetAlert Messages --}}
<x-sweetalert />

@yield('scripts')
</body>
</html>