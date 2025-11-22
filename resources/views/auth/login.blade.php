<!DOCTYPE html>
<html>
<head>
    <title>PawPortal Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            /* Paw Patrol background from public/images */
            background: url("{{ asset('images/pawpatrol.jpg') }}") no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            background: rgba(255, 255, 255, 0.9); /* semi-transparent for readability */
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            margin: 15px;
        }
        .card h3 {
            font-weight: bold;
            color: #007bff; /* blue for login */
        }
        .register-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
        }
        .register-link:hover {
            text-decoration: underline;
        }
        .vet-register-link {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #28a745; /* green for veterinarian */
            text-decoration: none;
            font-weight: bold;
        }
        .vet-register-link:hover {
            text-decoration: underline;
        }
        
        /* Responsive adjustments */
        @media (max-width: 576px) {
            body {
                background-size: cover;
                padding: 10px;
            }
            
            .card {
                padding: 15px;
                margin: 10px;
                max-width: none;
            }
            
            .card h3 {
                font-size: 1.5rem;
            }
            
            .form-control {
                font-size: 1rem;
                padding: 10px;
            }
            
            .btn {
                padding: 10px;
                font-size: 1rem;
            }
        }
        
        @media (max-width: 400px) {
            .card {
                padding: 10px;
                margin: 5px;
            }
            
            .card h3 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <h3 class="text-center mb-3">
            <img src="{{ asset('images/logo/logo.png') }}" alt="PawPortal Logo" class="img-fluid me-2" style="max-height: 40px; width: auto;">
            PawPortal Login
        </h3>
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100">Login</button>
        </form>
        
        <div class="text-center my-4">
            <div class="d-flex align-items-center mb-3">
                <hr class="flex-grow-1" style="opacity: 0.2;">
                <span class="px-3 text-muted small fw-medium">OR CONTINUE WITH</span>
                <hr class="flex-grow-1" style="opacity: 0.2;">
            </div>
            <a href="{{ route('auth.google') }}" class="btn btn-light border w-100 d-flex align-items-center justify-content-center py-2 shadow-sm" style="max-width: 300px; margin: 0 auto; background-color: #fff;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48" style="margin-right: 12px;">
                    <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                    <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                    <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
                    <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
                </svg>
                <span class="fw-medium text-dark">Continue with Google</span>
            </a>
        </div>
        
        <a href="{{ route('register') }}" class="register-link">Register as Pet Parent</a>
        <a href="{{ route('register.vet') }}" class="vet-register-link">⚕️ Register as Veterinarian</a>
    </div>
</body>
</html>
