<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - PawPortal')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', Roboto, sans-serif; }
        body { background: #fafafa; color: #1a1a1a; }
        .dashboard-container { display:flex; min-height:100vh; }
        
        /* Typography */
        html { font-size: 16px; }
        body { font-size: 1rem; line-height: 1.6; }
        h1, .h1 { font-size: 2.5rem; font-weight: 600; }
        h2, .h2 { font-size: 2rem; font-weight: 600; }
        h3, .h3 { font-size: 1.75rem; font-weight: 600; }
        h4, .h4 { font-size: 1.5rem; font-weight: 600; }
        h5, .h5 { font-size: 1.25rem; font-weight: 600; }
        h6, .h6 { font-size: 1.1rem; font-weight: 600; }
        
        /* Sidebar */
        .sidebar {
            width: 260px; 
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            display: flex; 
            flex-direction: column; 
            position: fixed; 
            height: 100vh; 
            z-index: 1001;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: hidden;
        }

        .sidebar-menu-container {
            flex: 1;
            overflow-y: auto;
            padding: 0 16px;
            margin-bottom: 16px;
        }

        .sidebar.collapsed { transform: translateX(-100%); }

        .sidebar-header { 
            padding: 24px 20px 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        .sidebar-header .logo { 
            font-size: 1.5rem; 
            font-weight: 700; 
            color: #dc2626;
            letter-spacing: -0.02em;
        }
        .sidebar-header .subtitle { 
            font-size: 0.813rem; 
            color: #6b7280;
            font-weight: 500;
            margin-top: 4px;
        }

        .menu { padding-top: 8px; }
        .menu-item { margin: 4px 0; }
        .menu-link {
            display: flex; 
            align-items: center; 
            padding: 11px 14px;
            text-decoration: none; 
            color: #4b5563; 
            border-radius: 8px; 
            transition: all 0.15s ease;
            font-size: 0.938rem;
            font-weight: 500;
        }
        .menu-link:hover { 
            background: #f3f4f6; 
            color: #1f2937;
        }
        .menu-link.active { 
            background: #dc2626; 
            color: #ffffff;
        }
        .menu-icon { 
            width: 20px; 
            text-align: center; 
            margin-right: 12px; 
            font-size: 0.938rem;
        }
        .menu-link:hover .menu-icon { color: #1f2937; }
        .menu-link.active .menu-icon { color: #ffffff; }
        .menu-text { font-weight: 500; }

        /* Profile Section */
        .profile-section { 
            padding: 16px; 
            border-top: 1px solid #e5e7eb;
            background: #fafafa;
        }
        .profile-info { 
            display: flex; 
            align-items: center; 
            margin-bottom: 12px; 
        }
        .profile-avatar {
            width: 40px; 
            height: 40px; 
            border-radius: 50%;
            background: #dc2626; 
            color: #ffffff; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-weight: 600; 
            font-size: 0.875rem; 
            margin-right: 12px;
        }
        .profile-details h4 { 
            font-size: 0.938rem; 
            margin-bottom: 2px; 
            font-weight: 600;
            color: #1f2937;
        }
        .profile-details p { 
            font-size: 0.813rem; 
            color: #6b7280; 
            margin-bottom: 0; 
        }
        .profile-links { 
            display: flex; 
            flex-direction: column; 
            gap: 8px; 
        }
        .profile-btn {
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 6px;
            padding: 9px 12px; 
            border: none; 
            border-radius: 6px; 
            font-size: 0.875rem; 
            cursor: pointer; 
            text-decoration: none; 
            text-align: center;
            transition: all 0.15s ease;
            font-weight: 500;
        }
        .profile-btn.edit { 
            background: #dc2626;
            color: #ffffff; 
        }
        .profile-btn.logout { 
            background: #ffffff;
            color: #4b5563;
            border: 1px solid #d1d5db;
        }
        .profile-btn:hover { opacity: 0.9; }
        .profile-btn.edit:hover { background: #b91c1c; }
        .profile-btn.logout:hover { 
            background: #f9fafb;
            border-color: #9ca3af;
        }

        /* Main Content */
        .main-content { 
            flex: 1; 
            margin-left: 260px; 
            padding: 32px; 
            transition: margin-left 0.3s ease; 
            max-width: calc(100vw - 260px); 
            overflow-x: hidden; 
        }

        /* Mobile Header */
        .mobile-header {
            display: none;
            background: #dc2626;
            color: white;
            padding: 16px 20px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .mobile-header .logo {
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        
        .menu-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Focus indicators */
        .menu-link:focus, 
        .profile-btn:focus, 
        a:focus, 
        button:focus {
            outline: 2px solid #dc2626;
            outline-offset: 2px;
        }
        
        .skip-link {
            position: absolute;
            top: -40px;
            left: 6px;
            background: #dc2626;
            color: white;
            padding: 8px;
            z-index: 1000;
            border-radius: 4px;
        }

        .skip-link:focus { top: 6px; }
        
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
        
        .sidebar-profile-picture,
        .profile-section .sidebar-profile-picture,
        .admin-avatar {
            width: 40px !important;
            height: 40px !important;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 12px;
            flex-shrink: 0;
        }
        
        .profile-avatar,
        .profile-section .profile-avatar,
        .admin-avatar {
            width: 40px !important;
            height: 40px !important;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            color: white;
            margin-right: 12px;
            flex-shrink: 0;
            background: #dc2626;
        }
        
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
        
        .sidebar-open { overflow: hidden; }

        /* Responsive styles */
        @media (max-width: 768px) {
            html { font-size: 15px; }
            
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
                max-width: 100vw;
            }
            
            .mobile-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .overlay.active { display: block; }
            
            h1, .h1 { font-size: 2rem; }
            h2, .h2 { font-size: 1.75rem; }
            h3, .h3 { font-size: 1.5rem; }
            h4, .h4 { font-size: 1.25rem; }
            h5, .h5 { font-size: 1.1rem; }
            h6, .h6 { font-size: 1rem; }
            
            .profile-avatar, .admin-avatar { font-size: 0.813rem; }
            
            .container-fluid {
                max-width: 100%;
                overflow-x: hidden;
                padding-left: 10px;
                padding-right: 10px;
            }
            
            body { overflow-x: hidden; }
        }
        
        @media (max-width: 576px) {
            html { font-size: 14px; }
            
            .sidebar { width: 240px; }
            
            .main-content {
                padding: 80px 16px 16px 16px;
                max-width: 100vw;
            }
            
            h1, .h1 { font-size: 1.75rem; }
            h2, .h2 { font-size: 1.5rem; }
            h3, .h3 { font-size: 1.35rem; }
            h4, .h4 { font-size: 1.2rem; }
            h5, .h5 { font-size: 1.1rem; }
            h6, .h6 { font-size: 1rem; }
            
            .profile-avatar, .admin-avatar { font-size: 0.75rem; }
            
            .card-body { padding: 1rem !important; }
            .card-header { padding: 0.75rem 1rem; }
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
            <img src="{{ asset('images/logo/logo.png') }}" alt="PawPortal Logo" class="img-fluid me-2" style="max-height: 30px; width: auto;">
            <span>PawPortal Admin</span>
        </div>
        <button class="menu-toggle" id="menu-toggle">
            <i class="fas fa-bars"></i>
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
                <div class="subtitle text-center">Administration Panel</div>
            </div>

            <!-- Scrollable container for menu items -->
            <div class="sidebar-menu-container">
                <div class="menu">
                    <div class="menu-item">
                        <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt menu-icon" aria-hidden="true"></i>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('admin.lost-found.index') }}" class="menu-link {{ request()->routeIs('admin.lost-found.*') ? 'active' : '' }}">
                            <i class="fas fa-search-location menu-icon" aria-hidden="true"></i>
                            <span class="menu-text">Lost & Found Records</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('admin.map.index') }}" class="menu-link {{ request()->routeIs('admin.map.*') ? 'active' : '' }}">
                            <i class="fas fa-map-marked-alt menu-icon" aria-hidden="true"></i>
                            <span class="menu-text">Map Management</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('admin.users.index') }}" class="menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fas fa-users menu-icon" aria-hidden="true"></i>
                            <span class="menu-text">User Management</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('admin.pets.index') }}" class="menu-link {{ request()->routeIs('admin.pets.*') ? 'active' : '' }}">
                            <i class="fas fa-paw menu-icon" aria-hidden="true"></i>
                            <span class="menu-text">Pet Management</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('admin.adoptions.index') }}" class="menu-link {{ request()->routeIs('admin.adoptions.index') || request()->routeIs('admin.adoptions.show') ? 'active' : '' }}">
                            <i class="fas fa-history menu-icon" aria-hidden="true"></i>
                            <span class="menu-text">Adoption History</span>
                        </a>
                    </div>
                    
                    <!-- Adoption Workflow Section -->
                    <div class="menu-item mt-3">
                        <div class="px-3 mb-2">
                            <small class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem;">Adoption Workflow</small>
                        </div>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('admin.adoptions.pending') }}" class="menu-link {{ request()->routeIs('admin.adoptions.pending') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-check menu-icon" aria-hidden="true"></i>
                            <span class="menu-text">Listing Approvals</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('admin.adoption-requests.screening') }}" class="menu-link {{ request()->routeIs('admin.adoption-requests.screening') ? 'active' : '' }}">
                            <i class="fas fa-user-check menu-icon" aria-hidden="true"></i>
                            <span class="menu-text">Adopter Screening</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('admin.adoption-requests.final-approval') }}" class="menu-link {{ request()->routeIs('admin.adoption-requests.final-approval') ? 'active' : '' }}">
                            <i class="fas fa-certificate menu-icon" aria-hidden="true"></i>
                            <span class="menu-text">Final Approvals</span>
                        </a>
                    </div>
                    
                    <!-- Messages -->
                    <div class="menu-item mt-3">
                        <a href="{{ route('admin.messages.index') }}" class="menu-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                            <i class="fas fa-comments menu-icon" aria-hidden="true"></i>
                            <span class="menu-text">Messages</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="profile-section">
                <div class="profile-info">
                    @if(Auth::user()->profile_picture_path)
                        <img src="{{ Auth::user()->profile_picture_url }}" alt="Admin Profile Picture" class="sidebar-profile-picture admin-avatar" width="45" height="45">
                    @else
                        <div class="profile-avatar admin-avatar" aria-label="Admin avatar">AD</div>
                    @endif
                    <div class="profile-details">
                        <h4>Administrator</h4>
                        <p>System Admin</p>
                    </div>
                </div>

                <div class="profile-links">
                    <a href="{{ route('admin.profile.edit') }}" class="profile-btn edit" aria-label="Edit profile">
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
</div>
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