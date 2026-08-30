@extends('layouts.app')

@section('title', 'ChapConnect - Verify Security Question')

@section('content')
<main class="main" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px;">
    <div class="auth-wrapper" style="width: 100%; max-width: 480px;">
        <div class="container" style="width: 100%;">
            <div class="form-box active">
                <form action="{{ route('password.verify-submit') }}" method="POST">
                    @csrf
                    
                    <input type="hidden" name="login" value="{{ $login }}">

                    <h2>Verify Identity</h2>
                    <p style="text-align: center; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 25px; line-height: 1.5;">
                        Please answer your security recovery question to reset your account password.
                    </p>

                    @if (session('success'))
                    <div style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 8px; padding: 10px 14px; color: #10b981; font-size: 0.88rem; font-weight: 600; margin-bottom: 20px;">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if ($errors->any())
                    <div style="background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 8px; padding: 10px 14px; color: #ef4444; font-size: 0.88rem; font-weight: 600; margin-bottom: 20px;">
                        <ul style="margin: 0; padding-left: 15px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 16px; margin-bottom: 20px; color: #15803d; font-size: 0.88rem; line-height: 1.5;">
                        <i class="bi bi-shield-lock-fill" style="margin-right: 4px;"></i> For security reasons, your recovery question is hidden. Please type your security answer below to verify your identity.
                        <div style="margin-top: 10px; font-weight: 600; color: #166534; font-size: 0.82rem;">
                            💡 If you forgot your security question or answer, please contact the HQ help desk/customer care to recover your account.
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="security_answer">Security Answer</label>
                        <input id="security_answer" type="text" name="security_answer" placeholder="Enter your answer" required autofocus autocomplete="off">
                    </div>

                    <div class="form-group" style="margin-top: 15px;">
                        <label for="password">New Password</label>
                        <div class="password-wrapper">
                            <input id="password" type="password" name="password" placeholder="Enter new password (min. 6 characters)" required>
                            <button type="button" class="toggle-password" onclick="togglePasswordVisibility(this)" title="Toggle password visibility">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 15px;">
                        <label for="password-confirm">Confirm New Password</label>
                        <div class="password-wrapper">
                            <input id="password-confirm" type="password" name="password_confirmation" placeholder="Confirm new password" required>
                            <button type="button" class="toggle-password" onclick="togglePasswordVisibility(this)" title="Toggle password visibility">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" style="margin-top: 20px;">Reset Password &amp; Log In</button>
                </form>
                <a href="{{ route('password.request') }}" class="back-link">← Choose another method</a>
            </div>
        </div>
    </div>
</main>
@endsection
