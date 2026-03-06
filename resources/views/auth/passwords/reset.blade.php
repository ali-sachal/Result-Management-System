@extends('layouts.auth')

@section('title', 'Set New Password - Result Management System')

@section('content')
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">
                <i class="fas fa-unlock-alt"></i>
            </div>
            <h1 class="auth-title">Set New Password</h1>
            <p class="auth-subtitle">Please enter your new password below</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group-custom">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ $email ?? old('email') }}" required autocomplete="email" readonly>
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

            <div class="form-group">
                <label for="password" class="form-label">New Password</label>
                <div class="input-group-custom">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="new-password" autofocus placeholder="Enter new password">
                    <span class="input-icon">
                        <i class="fas fa-lock"></i>
                    </span>
                </div>
                @error('password')
                    <div class="text-danger mt-1 small">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label for="password-confirm" class="form-label">Confirm Password</label>
                <div class="input-group-custom">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                        autocomplete="new-password" placeholder="Confirm new password">
                    <span class="input-icon">
                        <i class="fas fa-check-double"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn-login mb-3">
                <i class="fas fa-save me-2"></i>Reset Password
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