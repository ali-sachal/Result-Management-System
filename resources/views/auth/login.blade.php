@extends('layouts.auth')

@section('title', 'Login - Result Management System')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <div class="auth-logo">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <h1 class="auth-title">Welcome Back!</h1>
        <p class="auth-subtitle">Sign in to continue to your dashboard</p>
    </div>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ $errors->first() }}
        </div>
    @endif
    
    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <div class="input-group-custom">
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus
                       placeholder="Enter your email">
                <span class="input-icon">
                    <i class="fas fa-envelope"></i>
                </span>
            </div>
        </div>
        
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div class="input-group-custom">
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       required
                       placeholder="Enter your password">
                <span class="input-icon" onclick="togglePassword()">
                    <i class="fas fa-eye" id="toggleIcon"></i>
                </span>
            </div>
        </div>
        
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">
                Remember Me
            </label>
        </div>
        
        @if (Route::has('password.request'))
            <div class="forgot-password">
                <a href="{{ route('password.request') }}">Forgot Password?</a>
            </div>
        @endif
        
        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt me-2"></i>Sign In
        </button>
    </form>
</div>
@endsection