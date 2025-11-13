@extends('layouts.admin')

@section('title', 'Create User - User Management')

@section('styles')
<style>
    .create-user-header {
        background: #fff; padding: 30px; border-radius: 15px; margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    .page-title { 
        font-size: 2.2rem; color: #2c3e50; margin-bottom: 10px; font-weight: 700; 
    }
    .page-subtitle { font-size: 1.1rem; color: #666; }
    .breadcrumb {
        display: flex; align-items: center; gap: 8px; margin-bottom: 20px;
        font-size: 14px; color: #666;
    }
    .breadcrumb a { color: #3498db; text-decoration: none; }
    .breadcrumb a:hover { text-decoration: underline; }

    .form-container {
        background: #fff; padding: 40px; border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08); max-width: 800px;
    }
    
    .form-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 20px;
        margin-bottom: 20px;
    }
    .form-group-full {
        grid-column: 1 / -1;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    .form-label {
        display: block; margin-bottom: 8px; font-weight: 600; 
        color: #2c3e50; font-size: 14px;
    }
    .form-label.required::after {
        content: ' *'; color: #e74c3c;
    }
    
    .form-input {
        width: 100%; padding: 12px 16px; border: 2px solid #e1e8ed;
        border-radius: 8px; font-size: 14px; transition: border-color 0.3s;
    }
    .form-input:focus {
        outline: none; border-color: #3498db; box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }
    .form-textarea {
        resize: vertical; min-height: 100px;
    }
    .form-select {
        appearance: none; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center; background-repeat: no-repeat; background-size: 16px;
        padding-right: 40px;
    }
    
    .form-help {
        font-size: 12px; color: #666; margin-top: 4px;
    }
    
    .form-error {
        color: #e74c3c; font-size: 12px; margin-top: 4px;
    }
    .form-input.error {
        border-color: #e74c3c;
    }
    
    .role-cards {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;
        margin-top: 10px;
    }
    .role-card {
        border: 2px solid #e1e8ed; border-radius: 8px; padding: 20px;
        text-align: center; cursor: pointer; transition: all 0.3s;
    }
    .role-card:hover { border-color: #3498db; }
    .role-card.selected { border-color: #3498db; background: #f8fbff; }
    .role-card input[type="radio"] { display: none; }
    .role-icon { font-size: 2rem; margin-bottom: 10px; }
    .role-name { font-weight: 600; margin-bottom: 5px; }
    .role-desc { font-size: 12px; color: #666; }
    
    .form-actions {
        display: flex; justify-content: space-between; align-items: center;
        margin-top: 30px; padding-top: 20px; border-top: 1px solid #e1e8ed;
    }
    .btn {
        padding: 12px 24px; border: none; border-radius: 8px;
        font-weight: 600; cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 8px;
        transition: background-color 0.3s;
    }
    .btn-primary { background: #3498db; color: white; }
    .btn-primary:hover { background: #2980b9; }
    .btn-secondary { background: #95a5a6; color: white; }
    .btn-secondary:hover { background: #7f8c8d; }
    
    .password-strength {
        margin-top: 8px;
    }
    .strength-bar {
        height: 4px; background: #e1e8ed; border-radius: 2px; overflow: hidden;
    }
    .strength-fill {
        height: 100%; width: 0%; transition: all 0.3s;
    }
    .strength-weak { background: #e74c3c; }
    .strength-medium { background: #f39c12; }
    .strength-strong { background: #27ae60; }
    .strength-text {
        font-size: 11px; margin-top: 4px; font-weight: 600;
    }
    
    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .create-user-header {
            padding: 20px 15px;
        }
        
        .page-title {
            font-size: 1.8rem;
            text-align: center;
        }
        
        .page-subtitle {
            font-size: 1rem;
            text-align: center;
        }
        
        .breadcrumb {
            flex-wrap: wrap;
            gap: 5px;
            font-size: 12px;
        }
        
        .form-container {
            padding: 20px 15px;
            max-width: 100%;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-input {
            padding: 10px 12px;
            font-size: 16px; /* Prevent zoom on iOS */
        }
        
        .role-cards {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .role-card {
            padding: 15px;
        }
        
        .role-icon {
            font-size: 1.5rem;
        }
        
        .form-actions {
            flex-direction: column;
            gap: 10px;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
    
    @media (max-width: 576px) {
        .form-container {
            padding: 15px;
        }
        
        .form-label {
            font-size: 13px;
        }
        
        .form-input {
            padding: 8px 10px;
            font-size: 16px;
        }
    }
</style>
@endsection

@section('content')
<div class="create-user-header">
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a> 
        <span>→</span>
        <a href="{{ route('admin.users.index') }}">User Management</a>
        <span>→</span>
        <span>Create User</span>
    </div>
    <h1 class="page-title">➕ Create New User</h1>
    <p class="page-subtitle">Add legitimate users only - avoid creating unnecessary test accounts</p>
</div>

<div class="form-container">
    <form method="POST" action="{{ route('admin.users.store') }}" id="create-user-form">
        @csrf
        
        <!-- Basic Information -->
        <div class="form-grid">
            <div class="form-group">
                <label for="name" class="form-label required">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" 
                       class="form-input {{ $errors->has('name') ? 'error' : '' }}" 
                       placeholder="Enter full name" required>
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label required">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" 
                       class="form-input {{ $errors->has('email') ? 'error' : '' }}" 
                       placeholder="Enter email address" required>
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <!-- Contact Information -->
        <div class="form-grid">
            <div class="form-group">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" 
                       class="form-input {{ $errors->has('phone') ? 'error' : '' }}" 
                       placeholder="Enter phone number">
                @error('phone')
                    <div class="form-error">{{ $message }}</div>
                @enderror
                <div class="form-help">Optional contact number</div>
            </div>
            
            <div class="form-group">
                <label for="address" class="form-label">Address</label>
                <input type="text" id="address" name="address" value="{{ old('address') }}" 
                       class="form-input {{ $errors->has('address') ? 'error' : '' }}" 
                       placeholder="Enter address">
                @error('address')
                    <div class="form-error">{{ $message }}</div>
                @enderror
                <div class="form-help">Optional address information</div>
            </div>
        </div>
        
        <!-- Password Fields -->
        <div class="form-grid">
            <div class="form-group">
                <label for="password" class="form-label required">Password</label>
                <input type="password" id="password" name="password" 
                       class="form-input {{ $errors->has('password') ? 'error' : '' }}" 
                       placeholder="Enter password" required>
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
                <div class="password-strength">
                    <div class="strength-bar">
                        <div class="strength-fill" id="strength-fill"></div>
                    </div>
                    <div class="strength-text" id="strength-text"></div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password_confirmation" class="form-label required">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" 
                       class="form-input" placeholder="Confirm password" required>
                <div class="form-help">Re-enter the password to confirm</div>
            </div>
        </div>
        
        <!-- Role Selection -->
        <div class="form-group-full">
            <label class="form-label required">User Role</label>
            <div class="role-cards">
                <div class="role-card {{ old('role') == 'user' ? 'selected' : '' }}" data-role="user">
                    <input type="radio" name="role" value="user" {{ old('role') == 'user' ? 'checked' : '' }}>
                    <div class="role-icon">🐾</div>
                    <div class="role-name">Pet Owner</div>
                    <div class="role-desc">Regular user who can manage pets and consultations</div>
                </div>
                

                <div class="role-card {{ old('role') == 'admin' ? 'selected' : '' }}" data-role="admin">
                    <input type="radio" name="role" value="admin" {{ old('role') == 'admin' ? 'checked' : '' }}>
                    <div class="role-icon">⚡</div>
                    <div class="role-name">Administrator</div>
                    <div class="role-desc">Full system access and user management</div>
                </div>
            </div>
            @error('role')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>
        
        <!-- Form Actions -->
        <div class="form-actions">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                ← Back to Users
            </a>
            <button type="submit" class="btn btn-primary">
                💾 Create User
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Role card selection
    const roleCards = document.querySelectorAll('.role-card');
    roleCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove selected class from all cards
            roleCards.forEach(c => c.classList.remove('selected'));
            // Add selected class to clicked card
            this.classList.add('selected');
            // Check the radio button
            this.querySelector('input[type="radio"]').checked = true;
        });
    });
    
    // Password strength checker
    const passwordInput = document.getElementById('password');
    const strengthFill = document.getElementById('strength-fill');
    const strengthText = document.getElementById('strength-text');
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        let text = '';
        
        if (password.length >= 8) strength += 25;
        if (/[a-z]/.test(password)) strength += 25;
        if (/[A-Z]/.test(password)) strength += 25;
        if (/[0-9]/.test(password) || /[^A-Za-z0-9]/.test(password)) strength += 25;
        
        strengthFill.style.width = strength + '%';
        
        if (strength < 50) {
            strengthFill.className = 'strength-fill strength-weak';
            text = 'Weak';
        } else if (strength < 75) {
            strengthFill.className = 'strength-fill strength-medium';
            text = 'Medium';
        } else {
            strengthFill.className = 'strength-fill strength-strong';
            text = 'Strong';
        }
        
        strengthText.textContent = password.length > 0 ? text : '';
        strengthText.className = 'strength-text ' + (strength < 50 ? 'strength-weak' : strength < 75 ? 'strength-medium' : 'strength-strong');
    });
});
</script>
@endsection