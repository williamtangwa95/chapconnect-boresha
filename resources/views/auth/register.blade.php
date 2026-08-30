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

                    <div class="form-group">
                        <label for="security-question">Security Question <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 400;">*</span></label>
                        <input id="security-question" type="text" name="security_question" value="{{ old('security_question') }}" placeholder="e.g. What is the name of your first school?" required>
                    </div>

                    <div class="form-group">
                        <label for="security-answer">Security Answer <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 400;">*</span></label>
                        <input id="security-answer" type="text" name="security_answer" value="{{ old('security_answer') }}" placeholder="Enter security answer" required>
                    </div>

                    <div id="similarity-error" style="color: #ef4444; font-size: 0.82rem; font-weight: 600; margin-top: 10px; display: none; padding: 10px 14px; background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 8px;">
                        <i class="bi bi-exclamation-triangle-fill" style="margin-right: 4px;"></i> Question and Answer are too similar (must be less than 50% match).
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