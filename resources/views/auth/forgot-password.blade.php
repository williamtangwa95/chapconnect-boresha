@extends('layouts.app')

@section('title', 'ChapConnect - Forgot Password')

@section('content')
<main class="main" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px;">
    <div class="auth-wrapper" style="width: 100%; max-width: 460px;">
        <div class="container" style="width: 100%;">
            <div class="form-box active" id="forgot-password-form">
                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <h2>Forgot Password</h2>
                    <p style="text-align: center; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 25px; line-height: 1.5;">
                        Enter your registered email address or phone number to recover your password using your security question.
                    </p>

                    <div class="form-group">
                        <label for="login">Email Address or Phone Number</label>
                        <input type="text" id="login" name="login" value="{{ old('login') }}" placeholder="Enter your email or phone number" required autofocus>
                    </div>

                    <button type="submit" style="margin-top: 10px;">Recover Password</button>
                    <p>Remember your password? <a href="{{ route('login') }}">Sign In</a></p>
                </form>
                <a href="{{ route('home') }}" class="back-link">← Back to ChapConnect</a>
            </div>
        </div>
    </div>
</main>
@endsection
