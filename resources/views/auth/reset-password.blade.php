@extends('layouts.app')

@section('title', 'ChapConnect - Reset Password')

@section('content')
<main class="main" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px;">
    <div class="auth-wrapper" style="width: 100%; max-width: 460px;">
        <div class="container" style="width: 100%;">
            <div class="form-box active" id="reset-password-form">
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <h2>Reset Password</h2>
                    <p style="text-align: center; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 25px; line-height: 1.5;">
                        Please enter the 6-digit verification code sent to your email along with your new password.
                    </p>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', request('email')) }}" placeholder="Enter your email address" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="code">Verification Code</label>
                        <input type="text" id="code" name="code" placeholder="Enter 6-digit code" required maxlength="6" pattern="[0-9]{6}" style="letter-spacing: 3px; font-weight: 600; font-size: 1.1rem; text-align: center;">
                    </div>

                    <div class="form-group">
                        <label for="password">New Password</label>
                        <div class="password-wrapper" style="position: relative;">
                            <input type="password" id="password" name="password" placeholder="Enter new password" required style="padding-right: 45px;">
                            <button type="button" class="toggle-password" id="togglePassword" style="background: transparent; border: none; cursor: pointer; color: var(--text-muted); outline: none; position: absolute; right: 15px; top: 50%; transform: translateY(-50%); display: flex; align-items: center; justify-content: center; padding: 0;" title="Toggle password visibility">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm New Password</label>
                        <div class="password-wrapper" style="position: relative;">
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password" required style="padding-right: 45px;">
                            <button type="button" class="toggle-password" id="togglePasswordConfirm" style="background: transparent; border: none; cursor: pointer; color: var(--text-muted); outline: none; position: absolute; right: 15px; top: 50%; transform: translateY(-50%); display: flex; align-items: center; justify-content: center; padding: 0;" title="Toggle password visibility">
                                <svg id="eyeIconConfirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" style="margin-top: 10px;">Reset Password</button>
                    <p>Didn't receive code? <a href="{{ route('password.request') }}">Resend Code</a></p>
                </form>
                <a href="{{ route('home') }}" class="back-link">← Back to ChapConnect</a>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const setupToggle = (btnId, inputId, iconId) => {
            const btn = document.querySelector("#" + btnId);
            const input = document.querySelector("#" + inputId);
            const icon = document.querySelector("#" + iconId);
            if (!btn || !input || !icon) return;

            btn.addEventListener("click", () => {
                const type = input.getAttribute("type") === "password" ? "text" : "password";
                input.setAttribute("type", type);
                
                if (type === "text") {
                    icon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    `;
                } else {
                    icon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    `;
                }
            });
        };

        setupToggle("togglePassword", "password", "eyeIcon");
        setupToggle("togglePasswordConfirm", "password_confirmation", "eyeIconConfirm");
    });
</script>
@endsection
