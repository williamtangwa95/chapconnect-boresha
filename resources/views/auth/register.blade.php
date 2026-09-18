@extends('layouts.app')

@section('title', 'ChapConnect - Register')

@section('styles')
<style>
    .register-auth-wrapper {
        width: 100%;
        max-width: 860px;
        margin: auto;
    }

    .register-form-box {
        max-width: 860px !important;
        padding: 38px 44px !important;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
        border: 1px solid #e2e8f0;
    }

    .register-grid-2col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        column-gap: 24px;
        row-gap: 18px;
    }

    .register-grid-span-2 {
        grid-column: 1 / -1;
    }

    @media (max-width: 768px) {
        .register-auth-wrapper {
            max-width: 100% !important;
            padding: 20px 12px !important;
        }

        .register-form-box {
            padding: 24px 18px !important;
            border-radius: 16px !important;
        }

        .register-grid-2col {
            grid-template-columns: 1fr !important;
            row-gap: 14px !important;
        }
    }
</style>
@endsection

@section('content')
<main class="main" style="min-height: 85vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px;">
    <div class="auth-wrapper register-auth-wrapper">
        <div class="container" style="width: 100%; max-width: 100%;">
            <div class="form-box active register-form-box" id="register-form">
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <h2>{{ __('Register Account') }}</h2>
                    <p style="color: var(--text-muted); text-align: center; margin-bottom: 24px; font-size: 14px;">
                        {{ __('Create your profile and start showcasing your talent') }}
                    </p>

                    <div class="register-grid-2col">
                        <!-- Row 1: Full Name & Email -->
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="reg-name">{{ __('Full Name / Stage Name') }} <span style="font-size: 0.8rem; color: #ef4444; font-weight: 600;">*</span></label>
                            <input id="reg-name" type="text" name="name" value="{{ old('name') }}" placeholder="{{ __('Enter your full name') }}" required autofocus>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="reg-email">{{ __('Email Address') }} <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 400;">({{ __('Optional') }})</span></label>
                            <input id="reg-email" type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('Enter your email address') }}">
                        </div>

                        <!-- Row 2: Phone & Category -->
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="reg-phone">{{ __('Phone Number') }} <span style="font-size: 0.8rem; color: #ef4444; font-weight: 600;">*</span></label>
                            <input id="reg-phone" type="tel" name="phone" value="{{ old('phone') }}" placeholder="e.g. 0678429492 / +255678429492" required>
                            <small style="display: block; margin-top: 4px; font-size: 0.76rem; color: var(--text-muted);">
                                {{ __('Allowed formats: 06XXXXXXXX, 07XXXXXXXX, +255..., or 255...') }}
                            </small>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="categories">{{ __('Talent Category') }} <span style="font-size: 0.8rem; color: #ef4444; font-weight: 600;">*</span></label>
                            <select name="category" id="categories" required>
                                <option value="" selected disabled>-- {{ __('Select Category') }} --</option>
                                @foreach($categories as $slug => $label)
                                <option value="{{ $slug }}" {{ old('category') === $slug ? 'selected' : '' }}>
                                    {{ __($label) }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Row 3 (Full Width): Phone Visibility -->
                        <div class="form-group register-grid-span-2" style="background: #f8fafc; border: 1px solid #cbd5e1; padding: 14px; border-radius: 12px; margin-bottom: 0;">
                            <label for="phone-visibility" style="font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                                <i class="bi bi-telephone-fill" style="color: #6366f1;"></i> {{ __('Phone Number Visibility') }} <span style="font-size: 0.8rem; color: #ef4444;">*</span>
                            </label>
                            <select name="phone_visibility" id="phone-visibility" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; margin-top: 6px; font-weight: 600; font-size: 0.88rem; color: #1e293b;">
                                <option value="Yes" {{ old('phone_visibility', 'Yes') === 'Yes' ? 'selected' : '' }}>
                                    {{ __('Yes — Show Phone Number Publicly') }}
                                </option>
                                <option value="No" {{ old('phone_visibility') === 'No' ? 'selected' : '' }}>
                                    {{ __('No — Keep Phone Number Private / Hidden') }}
                                </option>
                            </select>
                            <div id="pkg-info-badge" style="margin-top: 10px; font-size: 0.8rem; padding: 8px 12px; border-radius: 8px; font-weight: 600; background: rgba(99,102,241,0.1); color: #4338ca; border: 1px solid rgba(99,102,241,0.2);">
                                <i class="bi bi-shield-check"></i> <span id="pkg-info-text">{{ __('Public visitors can view your contact details.') }}</span>
                            </div>
                        </div>

                        <!-- Row 4: Password & Confirm Password -->
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="reg-password">{{ __('Password') }} <span style="font-size: 0.8rem; color: #ef4444; font-weight: 600;">*</span></label>
                            <div class="password-wrapper" style="margin-bottom: 0;">
                                <input id="reg-password" type="password" name="password" placeholder="{{ __('Enter password (min. 6 characters)') }}" required>
                                <button type="button" class="toggle-password" onclick="togglePasswordVisibility(this)" title="{{ __('Toggle password visibility') }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="reg-password-confirm">{{ __('Confirm Password') }} <span style="font-size: 0.8rem; color: #ef4444; font-weight: 600;">*</span></label>
                            <div class="password-wrapper" style="margin-bottom: 0;">
                                <input id="reg-password-confirm" type="password" name="password_confirmation" placeholder="{{ __('Confirm password') }}" required>
                                <button type="button" class="toggle-password" onclick="togglePasswordVisibility(this)" title="{{ __('Toggle password visibility') }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Row 5: Security Question & Security Answer -->
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="security-question">{{ __('Security Question') }} <span style="font-size: 0.8rem; color: #ef4444; font-weight: 600;">*</span></label>
                            <input id="security-question" type="text" name="security_question" value="{{ old('security_question') }}" placeholder="{{ __('e.g. What is the name of your first school?') }}" required>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="security-answer">{{ __('Security Answer') }} <span style="font-size: 0.8rem; color: #ef4444; font-weight: 600;">*</span></label>
                            <input id="security-answer" type="text" name="security_answer" value="{{ old('security_answer') }}" placeholder="{{ __('Enter security answer') }}" required>
                        </div>

                        <!-- Row 6 (Full Width): Similarity Error -->
                        <div class="register-grid-span-2" id="similarity-error" style="color: #ef4444; font-size: 0.82rem; font-weight: 600; display: none; padding: 10px 14px; background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 8px;">
                            <i class="bi bi-exclamation-triangle-fill" style="margin-right: 4px;"></i> {{ __('Question and Answer are too similar (must be less than 50% match).') }}
                        </div>

                        <!-- Row 7 (Full Width): Submit & Login Link -->
                        <div class="register-grid-span-2" style="margin-top: 10px;">
                            <button type="submit" style="width: 100%;">{{ __('Register') }}</button>
                            <p style="text-align: center; margin-top: 14px;">{{ __('Already have an account?') }} <a href="{{ route('login') }}">{{ __('Sign In') }}</a></p>
                        </div>
                    </div>
                </form>
                <a href="{{ route('home') }}" class="back-link">← {{ __('Back to ChapConnect') }}</a>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#phone-visibility').on('change', function() {
            if ($(this).val() === 'No') {
                $('#pkg-info-text').text('{{ __("Phone number will be kept hidden/private from public visitors.") }}');
            } else {
                $('#pkg-info-text').text('{{ __("Public visitors can view your contact details.") }}');
            }
        });

        $('#categories').select2({
            width: '100%',
            placeholder: '-- {{ __("Select Category") }} --',
            minimumResultsForSearch: 0
        });

        // Similarity check logic
        const qInput = $('#security-question');
        const aInput = $('#security-answer');
        const errDiv = $('#similarity-error');
        const submitBtn = $('button[type="submit"]');

        function getEditDistance(s1, s2) {
            let costs = new Array();
            for (let i = 0; i <= s1.length; i++) {
                let lastValue = i;
                for (let j = 0; j <= s2.length; j++) {
                    if (i == 0) {
                        costs[j] = j;
                    } else {
                        if (j > 0) {
                            let newValue = costs[j - 1];
                            if (s1.charAt(i - 1) != s2.charAt(j - 1)) {
                                newValue = Math.min(Math.min(newValue, lastValue), costs[j]) + 1;
                            }
                            costs[j - 1] = lastValue;
                            lastValue = newValue;
                        }
                    }
                }
                if (i > 0) costs[s2.length] = lastValue;
            }
            return costs[s2.length];
        }

        function calculateSimilarity(str1, str2) {
            str1 = str1.trim().toLowerCase();
            str2 = str2.trim().toLowerCase();
            if (str1 === "" || str2 === "") return 0;
            if (str1 === str2) return 100;

            let longer = str1.length > str2.length ? str1 : str2;
            let shorter = str1.length > str2.length ? str2 : str1;
            let longerLength = longer.length;
            if (longerLength === 0) return 100;

            let editDistance = getEditDistance(longer, shorter);
            return ((longerLength - editDistance) / longerLength) * 100;
        }

        function checkSimilarity() {
            const qVal = qInput.val() || '';
            const aVal = aInput.val() || '';
            const sim = calculateSimilarity(qVal, aVal);

            if (sim >= 50) {
                errDiv.fadeIn(150);
                submitBtn.prop('disabled', true).css('opacity', '0.6');
            } else {
                errDiv.fadeOut(150);
                submitBtn.prop('disabled', false).css('opacity', '1');
            }
        }

        qInput.on('input change', checkSimilarity);
        aInput.on('input change', checkSimilarity);
    });
</script>
@endsection