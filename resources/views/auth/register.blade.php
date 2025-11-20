<!DOCTYPE html>
<html>
<head>
    <title>PawPortal Register</title>
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
            color: #5b4b9b; /* purple for pet parent */
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
        <h3 class="text-center mb-3">🐾 Register Pet Parent</h3>
        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            <div class="mb-3">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button class="btn w-100" style="background-color: #5b4b9b; border-color: #5b4b9b; color: white;">Register</button>
        </form>
        
        <div class="text-center my-3">
            <div class="d-flex align-items-center mb-3">
                <hr class="flex-grow-1">
                <span class="px-3 text-muted">OR</span>
                <hr class="flex-grow-1">
            </div>
            <a href="{{ route('auth.google') }}" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-google me-2" viewBox="0 0 16 16">
                    <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
                </svg>
                Continue with Google
            </a>
        </div>
        
        <p class="text-center mt-3">
            Already have an account? <a href="{{ route('login') }}">Login</a>
        </p>
        
        <a href="{{ route('register.vet') }}" class="vet-register-link">⚕️ Register as Veterinarian</a>
    </div>
</body>
</html>
