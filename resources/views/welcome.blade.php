<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawPortal - Pet Care Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --slate: #0f172a;
            --slate-light: #1e293b;
            --blue: #3b82f6;
            --cyan: #06b6d4;
            --orange: #f97316;
            --gray: #64748b;
            --gray-light: #f1f5f9;
            --white: #ffffff;
        }
        
        body {
            font-family: 'Sora', sans-serif;
            background: var(--white);
            color: var(--slate);
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        /* Navigation */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--slate);
            letter-spacing: -0.02em;
        }
        
        .nav-links {
            display: flex;
            gap: 40px;
            align-items: center;
        }
        
        .nav-links a {
            color: var(--gray);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
        }
        
        .nav-links a:hover {
            color: var(--slate);
        }
        
        .btn-nav {
            background: var(--slate);
            color: white;
            padding: 10px 24px;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-nav:hover {
            background: var(--slate-light);
            transform: translateY(-1px);
            color: white;
        }
        
        /* Hero Section */
        .hero {
            margin-top: 80px;
            padding: 100px 40px 120px;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
            position: relative;
        }
        
        .hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }
        
        .hero-content {
            opacity: 0;
            animation: fadeInLeft 1s ease forwards;
        }
        
        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .label {
            display: inline-block;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--blue);
            margin-bottom: 24px;
            font-weight: 500;
        }
        
        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 28px;
            letter-spacing: -0.02em;
        }
        
        .hero h1 .highlight {
            background: linear-gradient(120deg, var(--blue), var(--cyan));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero-description {
            font-size: 1.15rem;
            color: var(--gray);
            line-height: 1.7;
            margin-bottom: 40px;
            max-width: 540px;
        }
        
        .hero-actions {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }
        
        .btn-primary {
            background: var(--slate);
            color: white;
            padding: 16px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            display: inline-block;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid var(--slate);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.15);
            color: white;
        }
        
        .btn-secondary {
            background: white;
            color: var(--slate);
            padding: 16px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            display: inline-block;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid var(--slate);
        }
        
        .btn-secondary:hover {
            background: var(--slate);
            color: white;
        }
        
        .hero-visual {
            position: relative;
            height: 500px;
            opacity: 0;
            animation: fadeInRight 1s 0.2s ease forwards;
        }
        
        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .visual-card {
            position: absolute;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 24px;
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .visual-card-1 {
            top: 0;
            right: 0;
            width: 280px;
            border-left: 4px solid var(--blue);
            animation-delay: 0s;
        }
        
        .visual-card-2 {
            top: 180px;
            right: 100px;
            width: 260px;
            border-left: 4px solid var(--cyan);
            animation-delay: 0.5s;
        }
        
        .visual-card-3 {
            top: 340px;
            right: 20px;
            width: 240px;
            border-left: 4px solid var(--orange);
            animation-delay: 1s;
        }
        
        .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .card-icon-1 { background: rgba(59, 130, 246, 0.1); color: var(--blue); }
        .card-icon-2 { background: rgba(6, 182, 212, 0.1); color: var(--cyan); }
        .card-icon-3 { background: rgba(249, 115, 22, 0.1); color: var(--orange); }
        
        .card-title {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 8px;
        }
        
        .card-text {
            font-size: 0.85rem;
            color: var(--gray);
            line-height: 1.5;
        }
        
        /* Services Section */
        .services {
            background: var(--gray-light);
            padding: 100px 40px;
        }
        
        .services-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .section-header {
            margin-bottom: 60px;
        }
        
        .section-header .label {
            color: var(--cyan);
        }
        
        .section-header h2 {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 700;
            margin-bottom: 16px;
            letter-spacing: -0.02em;
        }
        
        .section-header p {
            font-size: 1.1rem;
            color: var(--gray);
            max-width: 600px;
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 24px;
        }
        
        .service-item {
            background: white;
            padding: 24px 20px;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            transform: translateY(20px);
        }
        
        .service-item.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .service-item:hover {
            border-color: var(--blue);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            transform: translateY(-4px);
        }
        
        .service-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }
        
        .service-number {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--blue);
            background: rgba(59, 130, 246, 0.1);
            padding: 6px 12px;
            border-radius: 6px;
        }
        
        .service-item h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 12px;
            letter-spacing: -0.01em;
        }
        
        .service-item p {
            color: var(--gray);
            line-height: 1.6;
            font-size: 0.95rem;
        }
        
        /* Stats Section */
        .stats {
            background: var(--slate);
            color: white;
            padding: 80px 40px;
        }
        
        .stats-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 60px;
            text-align: center;
        }
        
        .stat-item {
            opacity: 0;
            transform: scale(0.9);
            animation: scaleIn 0.6s ease forwards;
        }
        
        .stat-item:nth-child(1) { animation-delay: 0.1s; }
        .stat-item:nth-child(2) { animation-delay: 0.2s; }
        .stat-item:nth-child(3) { animation-delay: 0.3s; }
        .stat-item:nth-child(4) { animation-delay: 0.4s; }
        
        @keyframes scaleIn {
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            background: linear-gradient(120deg, var(--blue), var(--cyan));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }
        
        .stat-label {
            font-size: 1rem;
            color: var(--gray);
            font-weight: 500;
        }
        
        /* CTA Section */
        .cta {
            padding: 120px 40px;
            text-align: center;
        }
        
        .cta-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .cta h2 {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 700;
            margin-bottom: 24px;
            letter-spacing: -0.02em;
        }
        
        .cta p {
            font-size: 1.15rem;
            color: var(--gray);
            margin-bottom: 40px;
            line-height: 1.7;
        }
        
        /* Footer */
        footer {
            background: var(--gray-light);
            padding: 40px;
            text-align: center;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
        }
        
        footer p {
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .hero-grid {
                grid-template-columns: 1fr;
                gap: 60px;
            }
            
            .hero-visual {
                height: 400px;
            }
            
            .nav-links {
                display: none;
            }
        }
        
        @media (max-width: 768px) {
            .nav-container {
                padding: 20px;
            }
            
            .hero {
                padding: 60px 20px 80px;
            }
            
            .services,
            .stats,
            .cta {
                padding: 60px 20px;
            }
            
            .services-grid {
                grid-template-columns: 1fr;
            }
            
            .hero-actions {
                flex-direction: column;
            }
            
            .btn-primary,
            .btn-secondary {
                width: 100%;
                text-align: center;
            }
            
            .stats-container {
                gap: 40px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <div class="logo">PawPortal</div>
            <div class="nav-links">
                {{-- <a href="#services">Services</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a> --}}
                <a href="{{ route('login') }}" class="btn-nav">Sign In</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-grid">
            <div class="hero-content">
                <span class="label">Pet Care Platform</span>
                <h1>
                    Complete care for <span class="highlight">your companion</span>
                </h1>
                <p class="hero-description">
                    Connect with trusted veterinarians, reunite lost pets with their families, and join a community of pet lovers in your area. Everything you need in one platform.
                </p>
                <div class="hero-actions">
                    {{-- <a href="{{ route('register') }}" class="btn-primary">Get Started</a> --}}
                    <a href="{{ route('login') }}" class="btn-secondary">Sign In</a>
                </div>
            </div>
            
            <div class="hero-visual">
                <div class="visual-card visual-card-1">
                    <div class="card-icon card-icon-1">🏥</div>
                    <div class="card-title">Vet Appointments</div>
                    <p class="card-text">Book consultations with licensed professionals</p>
                </div>
                <div class="visual-card visual-card-2">
                    <div class="card-icon card-icon-2">📍</div>
                    <div class="card-title">Local Services</div>
                    <p class="card-text">Find nearby clinics and pet alerts</p>
                </div>
                <div class="visual-card visual-card-3">
                    <div class="card-icon card-icon-3">🤝</div>
                    <div class="card-title">Community</div>
                    <p class="card-text">Connect with fellow pet parents</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <div class="services-container">
            <div class="section-header">
                <span class="label">What We Offer</span>
                <h2>Comprehensive pet care solutions</h2>
                <p>Everything you need to care for your pet, connect with professionals, and stay involved in your community.</p>
            </div>
            
            <div class="services-grid">
                <div class="service-item">
                    <div class="service-header">
                        <span class="service-number">01</span>
                    </div>
                    <h3>Veterinary Appointments</h3>
                    <p>Book consultations with licensed veterinarians in your area. Get professional medical advice when you need it most.</p>
                </div>
                
                <div class="service-item">
                    <div class="service-header">
                        <span class="service-number">02</span>
                    </div>
                    <h3>Location Services</h3>
                    <p>Find veterinary clinics near you and see real-time updates about lost and found pets in your neighborhood.</p>
                </div>
                
                <div class="service-item">
                    <div class="service-header">
                        <span class="service-number">03</span>
                    </div>
                    <h3>Lost & Found Network</h3>
                    <p>Report missing pets or help reunite others with their companions through our community alert network.</p>
                </div>
                
                <div class="service-item">
                    <div class="service-header">
                        <span class="service-number">04</span>
                    </div>
                    <h3>Pet Adoption</h3>
                    <p>Browse pets available for adoption and find your next family member from local shelters and rescues.</p>
                </div>
                
                <div class="service-item">
                    <div class="service-header">
                        <span class="service-number">05</span>
                    </div>
                    <h3>Community Feed</h3>
                    <p>Share photos, ask questions, and connect with other pet parents who understand the joys and challenges.</p>
                </div>
                
                <div class="service-item">
                    <div class="service-header">
                        <span class="service-number">06</span>
                    </div>
                    <h3>Easy Scheduling</h3>
                    <p>Manage all your pet care appointments in one place with reminders and flexible rescheduling options.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pets for Adoption Section -->
    <section class="services" style="background: white;">
        <div class="services-container">
            <div class="section-header">
                <span class="label">Adoption</span>
                <h2>Pets looking for homes</h2>
                <p>Give a loving pet a second chance at happiness. Browse available pets ready for adoption.</p>
            </div>
            
            <div class="services-grid">
                @forelse($adoptionPets as $adoption)
                <div class="service-item">
                    @if($adoption->image_path)
                        <img src="{{ asset('storage/' . $adoption->image_path) }}" 
                             alt="{{ $adoption->pet_name }}" 
                             style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 16px;">
                    @else
                        <div style="width: 100%; height: 200px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; border-radius: 8px; margin-bottom: 16px;">
                            <span style="color: #9ca3af;">No Image</span>
                        </div>
                    @endif
                    <h3>{{ $adoption->pet_name ?? 'N/A' }}</h3>
                    <p style="margin-bottom: 8px;">
                        <strong>Breed:</strong> {{ $adoption->breed ?? 'N/A' }}<br>
                        <strong>Age:</strong> {{ $adoption->age ?? 'N/A' }}<br>
                        <strong>Gender:</strong> {{ ucfirst($adoption->gender ?? 'N/A') }}
                    </p>
                    <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 12px;">
                        {{ Str::limit($adoption->description ?? '', 80) }}
                    </p>
                    <a href="{{ route('login') }}" class="btn-primary" style="display: inline-block; margin-top: 12px; padding: 12px 24px; font-size: 0.9rem;">
                        View Details
                    </a>
                </div>
                @empty
                <div class="service-item" style="grid-column: 1 / -1; text-align: center;">
                    <p style="color: var(--gray);">No pets available for adoption at the moment.</p>
                </div>
                @endforelse
            </div>
            
            @if(isset($adoptionPets) && $adoptionPets->count() > 0)
            <div style="text-align: center; margin-top: 40px;">
                <a href="{{ route('login') }}" class="btn-primary">View All Adoptions</a>
            </div>
            @endif
        </div>
    </section>

    <!-- Lost & Found Section -->
    <section class="services" style="background: var(--gray-light);">
        <div class="services-container">
            <div class="section-header">
                <span class="label">Lost & Found</span>
                <h2>Help reunite pets with their families</h2>
                <p>Check recent reports of lost and found pets in your area. Every share could help bring a pet home.</p>
            </div>
            
            <div class="services-grid">
                @forelse($lostFoundPets as $lostFound)
                <div class="service-item">
                    @if($lostFound->image_path)
                        <img src="{{ asset('storage/' . $lostFound->image_path) }}" 
                             alt="{{ $lostFound->pet_name }}" 
                             style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 16px;">
                    @endif
                    <div style="display: inline-block; padding: 4px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; margin-bottom: 12px;
                                {{ $lostFound->type === 'lost' ? 'background: rgba(239, 68, 68, 0.1); color: #dc2626;' : 'background: rgba(34, 197, 94, 0.1); color: #16a34a;' }}">
                        {{ ucfirst($lostFound->type) }}
                    </div>
                    <h3>{{ $lostFound->pet_name }}</h3>
                    <p style="margin-bottom: 8px;">
                        <strong>Type:</strong> {{ ucfirst($lostFound->pet_type) }}<br>
                        <strong>Breed:</strong> {{ $lostFound->breed ?? 'N/A' }}<br>
                        <strong>Date:</strong> {{ $lostFound->type === 'lost' ? $lostFound->last_seen_date : $lostFound->found_date }}<br>
                        <strong>Location:</strong> {{ Str::limit($lostFound->location, 50) }}
                    </p>
                    <a href="{{ route('login') }}" class="btn-secondary" style="display: inline-block; margin-top: 12px; padding: 12px 24px; font-size: 0.9rem;">
                        View Details
                    </a>
                </div>
                @empty
                <div class="service-item" style="grid-column: 1 / -1; text-align: center;">
                    <p style="color: var(--gray);">No lost or found pets reported recently.</p>
                </div>
                @endforelse
            </div>
            
            @if(isset($lostFoundPets) && $lostFoundPets->count() > 0)
            <div style="text-align: center; margin-top: 40px;">
                <a href="{{ route('login') }}" class="btn-primary">View All Reports</a>
            </div>
            @endif
        </div>
    </section>

    <script>
        const observerOptions = {
            threshold: 0.15,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('visible');
                    }, index * 100);
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.service-item').forEach(item => {
            observer.observe(item);
        });
    </script>
</body>
</html>