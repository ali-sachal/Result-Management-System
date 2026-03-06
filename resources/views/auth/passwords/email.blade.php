@extends('layouts.auth')

@section('title', 'Reset Password - Result Management System')

@section('content')
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">
                <i class="fas fa-key"></i>
            </div>
            <h1 class="auth-title">Reset Password</h1>
            <p class="auth-subtitle">Enter your email to receive a password reset link</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group mb-4">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group-custom">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ old('email') }}" required autocomplete="email" autofocus
                        placeholder="Enter your email address">
                    <span class="input-icon">
                        <i class="fas fa-envelope"></i>
                    </span>
                </div>
                @error('email')
                    <div class="text-danger mt-1 small">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn-login mb-3">
                <i class="fas fa-paper-plane me-2"></i>Send Password Reset Link
            </button>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}"
                    style="color: #667eea; text-decoration: none; font-weight: 500; font-size: 14px; transition: color 0.3s ease;"
                    onmouseover="this.style.textDecoration='underline'; this.style.color='#764ba2'"
                    onmouseout="this.style.textDecoration='none'; this.style.color='#667eea'">
                    <i class="fas fa-arrow-left me-1"></i>Back to Login
                </a>
            </div>
        </form>
    </div>
@endsection