@extends('layouts.app')

@section('title', 'Chap Connect - Forgot Password')

@section('content')
<main class="main" style="max-width: 500px; margin: 120px auto 40px auto; padding: 0 20px;">
    <div class="pdetails" style="padding: 30px;">
        <h2 style="text-align: center; margin-bottom: 10px;">Forgot Password</h2>
        <p style="text-align: center; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 25px;">
            Enter your registered email address and we will send you a 6-digit verification code to reset your password.
        </p>

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="Enter your email address">
            </div>

            <button type="submit" class="btn-submit" style="width: 100%; margin-top: 10px;">Send Verification Code</button>
        </form>

        <p style="text-align: center; margin-top: 20px; font-size: 0.9rem; color: var(--text-muted);">
            Remember your password? <a href="{{ route('login') }}" style="color: var(--accent); font-weight: 600;">Sign In</a>
        </p>
    </div>
</main>
@endsection
