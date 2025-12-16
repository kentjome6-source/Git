<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawPortal Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            --gray: #64748b;
            --gray-light: #f1f5f9;
        }
        
        body {
            font-family: 'Sora', sans-serif;
            background: var(--slate);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        /* Animated background */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 1000px;
            height: 1000px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, transparent 70%);
            animation: pulse 8s ease-in-out infinite;
        }
        
        body::after {
            content: '';
            position: absolute;
            bottom: -50%;
            left: -20%;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
            animation: pulse 10s ease-in-out infinite reverse;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(1.1); opacity: 0.5; }
        }
        
        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-card {
            background: white;
            border-radius: 16px;
            padding: 48px 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .logo-section {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .logo-section img {
            height: 48px;
            margin-bottom: 16px;
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .logo-section h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--slate);
            margin-bottom: 8px;
            letter-spacing: -0.02em;
        }
        
        .logo-section p {
            color: var(--gray);
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-group label {
            display: block;
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--slate);
            margin-bottom: 8px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 16px;
            font-size: 0.95rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-family: 'Sora', sans-serif;
            transition: all 0.2s;
            background: white;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background: var(--slate);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 8px;
        }
        
        .btn-login:hover {
            background: var(--slate-light);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.2);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 32px 0;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }
        
        .divider span {
            padding: 0 16px;
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .btn-google {
            width: 100%;
            padding: 12px;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.95rem;
            color: var(--slate);
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            text-decoration: none;
        }
        
        .btn-google:hover {
            background: var(--gray-light);
            border-color: #cbd5e1;
            transform: translateY(-1px);
            color: var(--slate);
        }
        
        .btn-google svg {
            width: 20px;
            height: 20px;
        }
        
        .register-links {
            margin-top: 32px;
            padding-top: 32px;
            border-top: 1px solid #e2e8f0;
        }
        
        .register-link {
            display: block;
            text-align: center;
            padding: 12px;
            margin-bottom: 12px;
            color: var(--blue);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            border-radius: 8px;
            transition: all 0.2s;
        }
        
        .register-link:hover {
            background: rgba(59, 130, 246, 0.05);
        }
        
        .register-link-vet {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px;
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 8px;
            transition: all 0.2s;
        }
        
        .register-link-vet:hover {
            background: rgba(16, 185, 129, 0.15);
            transform: translateY(-1px);
            color: #059669;
        }
        
        .alert {
            padding: 14px 16px;
            background: #fee2e2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            color: #991b1b;
            font-size: 0.9rem;
            margin-bottom: 24px;
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .login-container {
                max-width: 100%;
            }
            
            .login-card {
                padding: 40px 32px;
            }
            
            body::before,
            body::after {
                width: 600px;
                height: 600px;
            }
        }
        
        @media (max-width: 576px) {
            body {
                padding: 10px;
                align-items: flex-start;
                padding-top: 40px;
            }
            
            .login-card {
                padding: 32px 24px;
                border-radius: 12px;
            }
            
            .logo-section {
                margin-bottom: 32px;
            }
            
            .logo-section h1 {
                font-size: 1.5rem;
            }
            
            .logo-section p {
                font-size: 0.9rem;
            }
            
            .logo-section img {
                height: 40px;
            }
            
            .form-group {
                margin-bottom: 20px;
            }
            
            .form-group label {
                font-size: 0.85rem;
            }
            
            .form-control {
                padding: 11px 14px;
                font-size: 16px; /* Prevents zoom on iOS */
            }
            
            .btn-login {
                padding: 13px;
                font-size: 0.95rem;
            }
            
            .btn-google {
                padding: 11px;
                font-size: 0.9rem;
            }
            
            .divider {
                margin: 28px 0;
            }
            
            .divider span {
                font-size: 0.7rem;
                padding: 0 12px;
            }
            
            .register-links {
                margin-top: 28px;
                padding-top: 28px;
            }
            
            .register-link,
            .register-link-vet {
                font-size: 0.9rem;
                padding: 11px;
            }
            
            body::before,
            body::after {
                width: 400px;
                height: 400px;
            }
        }
        
        @media (max-width: 400px) {
            body {
                padding: 8px;
                padding-top: 30px;
            }
            
            .login-card {
                padding: 24px 20px;
                border-radius: 10px;
            }
            
            .logo-section {
                margin-bottom: 28px;
            }
            
            .logo-section h1 {
                font-size: 1.35rem;
            }
            
            .logo-section img {
                height: 36px;
                margin-bottom: 12px;
            }
            
            .form-group {
                margin-bottom: 18px;
            }
            
            .btn-login,
            .btn-google {
                padding: 12px;
            }
            
            .alert {
                padding: 12px 14px;
                font-size: 0.85rem;
            }
        }
        
        @media (max-width: 360px) {
            .login-card {
                padding: 20px 16px;
            }
            
            .logo-section h1 {
                font-size: 1.25rem;
            }
            
            .logo-section p {
                font-size: 0.85rem;
            }
            
            .btn-google span {
                font-size: 0.85rem;
            }
        }
        
        /* Landscape mobile */
        @media (max-height: 600px) and (orientation: landscape) {
            body {
                padding-top: 20px;
                padding-bottom: 20px;
            }
            
            .login-card {
                padding: 24px 32px;
            }
            
            .logo-section {
                margin-bottom: 24px;
            }
            
            .logo-section img {
                height: 32px;
                margin-bottom: 8px;
            }
            
            .form-group {
                margin-bottom: 16px;
            }
            
            .divider {
                margin: 20px 0;
            }
            
            .register-links {
                margin-top: 20px;
                padding-top: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-section">
                <img src="{{ asset('images/logo/logo.png') }}" alt="PawPortal Logo">
                <h1>Welcome back</h1>
                <p>Sign in to your account</p>
            </div>
            
            @if(session('error'))
                <div class="alert">
                    {{ session('error') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required autocomplete="email">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password">
                </div>
                
                <button type="submit" class="btn-login">Sign In</button>
            </form>
            
            <div class="divider">
                <span>Or continue with</span>
            </div>
            
            <a href="{{ route('auth.google') }}" class="btn-google">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
                    <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                    <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                    <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
                    <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
                </svg>
                <span>Continue with Google</span>
            </a>
        </div>
    </div>
</body>
</html>