@extends('layouts.app')

@section('title', 'Chap Connect - Register')

@section('content')
<main class="main" style="max-width: 550px; margin: 120px auto 40px auto; padding: 0 20px;">
    <div class="pdetails" style="padding: 30px;">
        <h2 style="text-align: center; margin-bottom: 20px;">Join Chap Connect</h2>
        <p style="color: var(--text-muted); text-align: center; margin-bottom: 25px; font-size: 0.9rem;">Create your profile and start showcasing your talent</p>
        
        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="name">Full Name / Stage Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            
            <div class="form-group">
                <label for="category_input">Your Primary Category</label>
                <div class="searchable-select-container dark-searchable-select">
                    <input type="text" id="category_input" class="form-control select-search-input" placeholder="Type to search category..." autocomplete="off" required style="background-color: #0f1626; color: white;">
                    <input type="hidden" name="category" class="select-hidden-value" id="category" value="{{ old('category') }}" required>
                    <div class="select-dropdown-options">
                        @foreach($categories as $slug => $label)
                            <div class="select-option-item {{ old('category') === $slug ? 'selected' : '' }}" data-slug="{{ $slug }}" data-name="{{ $label }}">
                                {{ $label }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number (WhatsApp preferred)</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+255 ...">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div style="position: relative; display: flex; align-items: center;">
                    <input type="password" id="password" name="password" class="form-control" style="padding-right: 45px;" required placeholder="Minimum 6 characters">
                    <button type="button" id="togglePassword" style="position: absolute; right: 15px; background: transparent; border: none; cursor: pointer; color: var(--text-muted); display: flex; align-items: center; justify-content: center; outline: none; padding: 0;">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <div style="position: relative; display: flex; align-items: center;">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" style="padding-right: 45px;" required>
                    <button type="button" id="togglePasswordConfirm" style="position: absolute; right: 15px; background: transparent; border: none; cursor: pointer; color: var(--text-muted); display: flex; align-items: center; justify-content: center; outline: none; padding: 0;">
                        <svg id="eyeIconConfirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="btn-submit" style="width: 100%; margin-top: 10px;">Create Account</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px; font-size: 0.9rem; color: var(--text-muted);">
            Already have an account? <a href="{{ route('login') }}" style="color: var(--accent); font-weight: 600;">Sign In</a>
        </p>
    </div>
</main>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Initialize Custom Searchable Select Dropdowns
        document.querySelectorAll(".searchable-select-container").forEach(container => {
            const input = container.querySelector(".select-search-input");
            const hidden = container.querySelector(".select-hidden-value");
            const optionsPanel = container.querySelector(".select-dropdown-options");
            const optionItems = container.querySelectorAll(".select-option-item");

            const initialVal = hidden.value;
            if (initialVal) {
                optionItems.forEach(item => {
                    if (item.getAttribute("data-slug") === initialVal) {
                        item.classList.add("selected");
                        input.value = item.getAttribute("data-name");
                    }
                });
            }

            input.addEventListener("focus", () => {
                optionsPanel.classList.add("active");
                optionItems.forEach(item => item.style.display = "block");
            });

            document.addEventListener("click", (e) => {
                if (!container.contains(e.target)) {
                    optionsPanel.classList.remove("active");
                    const selected = container.querySelector(".select-option-item.selected");
                    if (selected) {
                        input.value = selected.getAttribute("data-name");
                        hidden.value = selected.getAttribute("data-slug");
                    } else {
                        input.value = "";
                        hidden.value = "";
                    }
                }
            });

            input.addEventListener("input", () => {
                const query = input.value.toLowerCase().trim();
                optionItems.forEach(item => {
                    const name = item.getAttribute("data-name").toLowerCase();
                    if (name.includes(query)) {
                        item.style.display = "block";
                    } else {
                        item.style.display = "none";
                    }
                });
            });

            optionItems.forEach(item => {
                item.addEventListener("click", () => {
                    input.value = item.getAttribute("data-name");
                    hidden.value = item.getAttribute("data-slug");
                    optionItems.forEach(opt => opt.classList.remove("selected"));
                    item.classList.add("selected");
                    optionsPanel.classList.remove("active");
                });
            });
        });

        // Toggle primary password
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");
        const eyeIcon = document.querySelector("#eyeIcon");

        togglePassword.addEventListener("click", () => {
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            
            if (type === "text") {
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                `;
            } else {
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                `;
            }
        });

        // Toggle confirmation password
        const togglePasswordConfirm = document.querySelector("#togglePasswordConfirm");
        const passwordConfirm = document.querySelector("#password_confirmation");
        const eyeIconConfirm = document.querySelector("#eyeIconConfirm");

        togglePasswordConfirm.addEventListener("click", () => {
            const type = passwordConfirm.getAttribute("type") === "password" ? "text" : "password";
            passwordConfirm.setAttribute("type", type);
            
            if (type === "text") {
                eyeIconConfirm.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                `;
            } else {
                eyeIconConfirm.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                `;
            }
        });
    });
</script>
@endsection
