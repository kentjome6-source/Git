<!DOCTYPE html>
<html>
<head>
    <title>PawPortal Veterinarian Register</title>
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
            background: rgba(255, 255, 255, 0.95); /* more opaque for better readability */
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.25);
            width: 100%;
            max-width: 500px;
            margin: 20px;
        }
        .card h3 {
            font-weight: bold;
            color: #28a745; /* green for register */
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            body {
                background-size: cover;
                padding: 10px;
                align-items: flex-start;
                padding-top: 20px;
            }
            
            .card {
                padding: 20px;
                margin: 10px;
                max-width: none;
            }
            
            .card h3 {
                font-size: 1.5rem;
                margin-bottom: 1rem;
            }
            
            .form-control {
                font-size: 1rem;
                padding: 12px;
                margin-bottom: 15px;
            }
            
            .btn {
                padding: 12px;
                font-size: 1.1rem;
                margin-top: 10px;
            }
            
            .form-text {
                font-size: 0.85rem;
                margin-top: 5px;
            }
            
            label {
                font-weight: 600;
                margin-bottom: 5px;
                display: block;
                font-size: 0.95rem;
            }
            
            .mb-3 {
                margin-bottom: 1rem !important;
            }
            
            textarea.form-control {
                min-height: 100px;
            }
        }
        
        @media (max-width: 400px) {
            body {
                padding: 5px;
                padding-top: 15px;
            }
            
            .card {
                padding: 15px;
                margin: 8px;
            }
            
            .card h3 {
                font-size: 1.3rem;
            }
            
            .form-control {
                padding: 10px;
                font-size: 0.95rem;
            }
            
            .btn {
                padding: 10px;
                font-size: 1rem;
            }
            
            label {
                font-size: 0.9rem;
            }
        }
        
        /* Extra small devices */
        @media (max-width: 350px) {
            body {
                padding: 5px;
                padding-top: 10px;
            }
            
            .card {
                padding: 12px;
                margin: 5px;
            }
            
            .card h3 {
                font-size: 1.2rem;
            }
            
            .form-control {
                padding: 8px;
                font-size: 0.9rem;
            }
            
            .btn {
                padding: 8px;
                font-size: 0.95rem;
            }
            
            label {
                font-size: 0.85rem;
            }
            
            .form-text {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <h3 class="text-center mb-3">🐾 Register as Veterinarian</h3>
        <form method="POST" action="{{ route('vet.register.post') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone') }}" required>
            </div>
            <div class="mb-3">
                <label for="address">Address</label>
                <textarea id="address" name="address" class="form-control" required>{{ old('address') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="certificate">Licensure Certificate</label>
                <input type="file" id="certificate" name="certificate" class="form-control" required>
                <div class="form-text">Upload a clear image of your veterinarian license certificate (JPG, PNG, GIF - max 2MB)</div>
            </div>
            <button class="btn btn-success w-100" type="submit">Register as Veterinarian</button>
            <p class="text-center mt-3">
                Already have an account? <a href="{{ route('login') }}">Login</a>
            </p>
        </form>
    </div>
</body>
</html>