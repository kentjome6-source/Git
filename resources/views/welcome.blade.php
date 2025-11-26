<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to PawPortal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
        }
        
        .feature-card {
            transition: transform 0.3s ease;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-section {
                padding: 40px 0;
            }
            
            .display-4 {
                font-size: 2rem;
            }
            
            .lead {
                font-size: 1rem;
            }
            
            .btn-lg {
                padding: 10px 16px;
                font-size: 1rem;
            }
            
            h2.fw-bold {
                font-size: 1.75rem;
            }
            
            .feature-card {
                margin-bottom: 20px;
            }
        }
        
        @media (max-width: 576px) {
            .hero-section {
                padding: 30px 0;
            }
            
            .display-4 {
                font-size: 1.75rem;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
            
            .d-flex.flex-column.flex-sm-row {
                flex-direction: column !important;
                align-items: center;
            }
        }
        
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <img src="{{ asset('images/logo/logo.png') }}" alt="PawPortal Logo" class="mb-4" style="max-height: 150px;">
            <h1 class="display-4 fw-bold mb-3">Welcome to PawPortal</h1>
            <p class="lead mb-4">Find veterinarian services and lost/found pets in your area</p>
            <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                <a href="{{ route('login') }}" class="btn btn-light btn-lg px-4">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4">
                    <i class="fas fa-user-plus me-2"></i>Register as Pet Parent
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="fw-bold">Why Choose PawPortal?</h2>
                    <p class="text-muted">Find veterinarian services and lost/found pets in your area</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 feature-card border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-stethoscope fa-3x text-success"></i>
                            </div>
                            <h5 class="card-title">Vet Appointments</h5>
                            <p class="card-text text-muted">Connect with qualified veterinarians for consultations and professional advice.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 feature-card border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-map-marked-alt fa-3x text-success"></i>
                            </div>
                            <h5 class="card-title">Interactive Maps</h5>
                            <p class="card-text text-muted">Locate nearby veterinarian shops and view lost/found pets in your area.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 feature-card border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-search fa-3x text-warning"></i>
                            </div>
                            <h5 class="card-title">Lost & Found</h5>
                            <p class="card-text text-muted">Help reunite lost pets with their families through our community network.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 feature-card border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-paw fa-3x text-info"></i>
                            </div>
                            <h5 class="card-title">Pet Adoption</h5>
                            <p class="card-text text-muted">Find your perfect companion from our selection of pets waiting for their forever homes.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 feature-card border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-users fa-3x text-purple"></i>
                            </div>
                            <h5 class="card-title">Community Feed</h5>
                            <p class="card-text text-muted">Connect with fellow pet lovers, share photos, and engage with our social community.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-light py-5">
        <div class="container text-center">
            <h3 class="fw-bold mb-3">Ready to Get Started?</h3>
            <p class="text-muted mb-4">Find veterinarian services and lost/found pets in your area.</p>
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">
                <i class="fas fa-paw me-2"></i>Start Your Journey
            </a>
        </div>
    </section>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .text-purple {
            color: #6f42c1;
        }
    </style>
</body>
</html>