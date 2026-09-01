@extends('layouts.app')

@section('title', 'ChapConnect - Login')

@section('content')
<main class="main" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px;">
    <div class="auth-wrapper" style="width: 100%; max-width: 460px;">
        <div class="container" style="width: 100%;">
            <div class="form-box active" id="login-form">
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <h2>{{ __('Login') }}</h2>

                    <div class="form-group">
                        <label for="login-email">{{ __('Username') }}</label>
                        <input id="login-email" type="text" name="login" value="{{ old('login') }}" placeholder="{{ __('Enter your email or phone number') }}" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="login-password">{{ __('Password') }}</label>
                        <div class="password-wrapper">
                            <input id="login-password" type="password" name="password" placeholder="{{ __('Enter password') }}" required>
                            <button type="button" class="toggle-password" onclick="togglePasswordVisibility(this)" title="{{ __('Toggle password visibility') }}">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; font-size: 13px;">
                        <label style="display: flex; align-items: center; gap: 6px; margin: 0; cursor: pointer;">
                            <input type="checkbox" name="remember" style="width: auto;"> {{ __('Remember Me') }}
                        </label>
                        <a href="{{ route('password.request') }}" style="color: var(--primary); text-decoration: none; font-weight: 500;">{{ __('Forgot Password?') }}</a>
                    </div>

                    <button type="submit">{{ __('Login') }}</button>
                    <p>{{ __("Don't have an account?") }} <a href="{{ route('register') }}">{{ __('Register') }}</a></p>
                </form>
                <a href="{{ route('home') }}" class="back-link">← {{ __('Back to ChapConnect') }}</a>
            </div>
        </div>
    </div>
</main>
@endsection