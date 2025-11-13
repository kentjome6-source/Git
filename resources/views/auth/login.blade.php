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
        <h3 class="text-center mb-3">🐾 PawPortal Login</h3>
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('login.post') }}">
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
        <a href="{{ route('register') }}" class="register-link">Register as Pet Owner</a>
        <a href="{{ route('vet.register') }}" class="vet-register-link">⚕️ Register as Veterinarian</a>
    </div>
</body>
</html>