@extends('layouts.app')

@section('title', 'ChapConnect - Register')

@section('content')
<main class="main" style="min-height: 85vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px;">
    <div class="auth-wrapper" style="width: 100%; max-width: 500px;">
        <div class="container" style="width: 100%;">
            <div class="form-box active" id="register-form">
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <h2>Register Account</h2>
                    <p style="color: var(--text-muted); text-align: center; margin-bottom: 20px; font-size: 14px;">
                        Create your profile and start showcasing your talent
                    </p>

                    <div class="form-group">
                        <label for="reg-name">Full Name / Stage Name <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 400;">*</span></label>
                        <input id="reg-name" type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="reg-email">Email Address <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 400;">(Optional)</span></label>
                        <input id="reg-email" type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address">
                    </div>

                    <div class="form-group">
                        <label for="reg-phone">Phone Number <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 400;">*</span></label>
                        <input id="reg-phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="+255 ...">
                    </div>

                    <div class="form-group">
                        <label for="categories">Talent Category <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 400;">*</span></label>
                        <select name="category" id="categories" required>
                            <option value="" selected disabled>-- Select Category --</option>
                            @foreach($categories as $slug => $label)
                            <option value="{{ $slug }}" {{ old('category') === $slug ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="reg-password">Password <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 400;">*</span></label>
                        <div class="password-wrapper">
                            <input id="reg-password" type="password" name="password" placeholder="Enter password (min. 6 characters)" required>
                            <button type="button" class="toggle-password" onclick="togglePasswordVisibility(this)" title="Toggle password visibility">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reg-password-confirm">Confirm Password <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 400;">*</span></label>
                        <div class="password-wrapper">
                            <input id="reg-password-confirm" type="password" name="password_confirmation" placeholder="Confirm password" required>
                            <button type="button" class="toggle-password" onclick="togglePasswordVisibility(this)" title="Toggle password visibility">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit">Register</button>
                    <p>Already have an account? <a href="{{ route('login') }}">Sign In</a></p>
                </form>
                <a href="{{ route('home') }}" class="back-link">← Back to ChapConnect</a>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#categories').select2({
            width: '100%',
            placeholder: '-- Select Category --',
            minimumResultsForSearch: 0
        });
    });
</script>
@endsection