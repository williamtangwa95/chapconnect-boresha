@extends('layouts.app')

@section('title', 'ChapConnect - Edit Profile & Settings')

@section('styles')
<style>
    .profile-main-container {
        max-width: 100%;
        width: 100%;
        margin: 15px 0;
        padding: 0 24px;
    }

    .profile-header-card {
        background: #ffffff;
        color: #0f172a;
        padding: 20px 24px;
        border-radius: 16px;
        margin-bottom: 22px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .profile-panel-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 26px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        border: 1px solid var(--border-color, #e2e8f0);
    }

    .form-grid-2col {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
    }

    .form-grid-3col {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
    }

    @media (max-width: 768px) {
        .profile-main-container {
            padding: 0 12px !important;
            margin: 10px 0 !important;
        }

        .profile-header-card {
            padding: 16px !important;
            border-radius: 14px !important;
            flex-direction: column;
            align-items: flex-start !important;
            gap: 10px !important;
        }

        .profile-panel-card {
            padding: 16px !important;
            border-radius: 16px !important;
        }

        .form-grid-2col,
        .form-grid-3col {
            grid-template-columns: 1fr !important;
            gap: 12px !important;
        }
    }
</style>
@endsection

@section('content')
<main class="main admin-main-container profile-main-container">
    <div class="dashboard-container">
        <!-- Page Header -->
        <div class="profile-header-card">
            <div>
                <h2 style="margin: 0 0 4px 0; font-size: 1.25rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-person-gear" style="color: var(--primary);"></i> Edit Profile Information & Security
                </h2>
                <p style="margin: 0; color: #64748b; font-size: 0.85rem;">Update your profile details, stage name, bio, social media links, and security credentials.</p>
            </div>
            <div>
                <a href="{{ route('profile', $user->id) }}" target="_blank" style="padding: 8px 16px; border-radius: 10px; background: rgba(99,102,241,0.12); color: #4f46e5; border: 1px solid rgba(99,102,241,0.25); font-weight: 700; font-size: 0.82rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="bi bi-box-arrow-up-right"></i> View Public Profile
                </a>
            </div>
        </div>

        <!-- Standalone Settings Form Panel -->
        <div class="dashboard-panel profile-panel-card">
            <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 1.15rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-gear-fill" style="color: var(--primary);"></i> Account Details & Password Settings
            </h3>

            <form action="{{ route('dashboard.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-grid-2col" style="margin-bottom: 16px;">
                    <div class="form-group">
                        <label for="name" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Stage Name / Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    </div>

                    <div class="form-group">
                        <label for="email" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Account Email Address</label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        @error('email')
                            <span style="color: #ef4444; font-size: 0.78rem; font-weight: 600; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-grid-2col" style="margin-bottom: 16px;">
                    <div class="form-group">
                        <label for="phone" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Phone Number</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    </div>

                    <div class="form-group">
                        <label for="country" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Country Location</label>
                        <input type="text" id="country" name="country" class="form-control" value="{{ old('country', $user->country) }}" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label for="category_read" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Registered Category</label>
                    <input type="text" id="category_read" class="form-control" value="{{ $user->category_label }}" disabled style="background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="color: #475569; font-weight: 600; margin-bottom: 8px; display: block;">Profile Avatar Photo</label>
                    
                    <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap; background: #f8fafc; padding: 16px; border-radius: 14px; border: 1px solid #cbd5e1;">
                        <!-- Current Avatar Display -->
                        <div style="position: relative; width: 68px; height: 68px; border-radius: 50%; overflow: hidden; flex-shrink: 0; border: 3px solid var(--primary); box-shadow: 0 4px 12px rgba(99,102,241,0.25);">
                            <img id="current-avatar-img" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                        </div>

                        <!-- Avatar Controls -->
                        <div style="flex-grow: 1;">
                            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 4px;">
                                <label for="profile_image" style="padding: 8px 16px; background: linear-gradient(135deg, var(--primary) 0%, #2563eb 100%); color: #ffffff; border-radius: 10px; font-weight: 700; font-size: 0.82rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; margin: 0; box-shadow: 0 4px 12px rgba(59,130,246,0.25); border: none;">
                                    <i class="bi bi-camera-fill"></i> {{ $user->profile_image ? 'Change / Edit Photo' : 'Upload Photo' }}
                                </label>
                                <input type="file" id="profile_image" name="profile_image" class="form-control" accept="image/*" style="display: none;" onchange="previewProfileImage(event)">
                                @if($user->profile_image)
                                    <span style="font-size: 0.78rem; color: #10b981; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="bi bi-check-circle-fill"></i> Photo Active
                                    </span>
                                @endif
                            </div>
                            <span style="font-size: 0.78rem; color: #64748b; display: block; margin-top: 4px;">JPG, PNG, GIF or WEBP. Max size 10MB.</span>
                        </div>
                    </div>

                    <!-- Live Image Preview Box -->
                    <div id="image-preview-container" style="margin-top: 12px; display: none; align-items: center; gap: 15px; background: #eff6ff; padding: 12px 16px; border-radius: 12px; border: 1px dashed var(--primary);">
                        <div style="width: 52px; height: 52px; border-radius: 50%; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.12); flex-shrink: 0; border: 2px solid var(--primary);">
                            <img id="image-preview-element" src="#" alt="New Profile Preview" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div style="flex-grow: 1;">
                            <span style="font-size: 13px; font-weight: 700; color: var(--primary); display: block;">✨ New Image Selected!</span>
                            <span id="image-file-info" style="font-size: 12px; color: var(--text-muted);">Previewing selected file</span>
                        </div>
                        <button type="button" onclick="cancelImageSelection()" style="background: none; border: none; color: #ef4444; font-size: 1.15rem; cursor: pointer; padding: 2px 6px;" title="Cancel image selection">
                            <i class="bi bi-x-circle-fill"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="description" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Short Bio Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4" placeholder="Tell the world about yourself and your creative works..." style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">{{ old('description', $user->description) }}</textarea>
                </div>

                <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--primary); margin-top: 25px; margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Social Media Links</h4>

                <div class="form-grid-2col" style="margin-bottom: 20px;">
                    <div class="form-group">
                        <label for="social_instagram" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Instagram Link</label>
                        <input type="url" id="social_instagram" name="social_instagram" class="form-control @error('social_instagram') is-invalid @enderror" value="{{ old('social_instagram', $user->social_instagram) }}" placeholder="https://instagram.com/username" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        <div id="social_instagram_status" style="display: none; margin-top: 4px; font-size: 0.78rem; font-weight: 600;"></div>
                        @error('social_instagram')
                            <span style="color: #ef4444; font-size: 0.78rem; font-weight: 600; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="social_facebook" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Facebook Link</label>
                        <input type="url" id="social_facebook" name="social_facebook" class="form-control @error('social_facebook') is-invalid @enderror" value="{{ old('social_facebook', $user->social_facebook) }}" placeholder="https://facebook.com/page" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        <div id="social_facebook_status" style="display: none; margin-top: 4px; font-size: 0.78rem; font-weight: 600;"></div>
                        @error('social_facebook')
                            <span style="color: #ef4444; font-size: 0.78rem; font-weight: 600; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="social_tiktok" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">TikTok Link</label>
                        <input type="url" id="social_tiktok" name="social_tiktok" class="form-control @error('social_tiktok') is-invalid @enderror" value="{{ old('social_tiktok', $user->social_tiktok) }}" placeholder="https://tiktok.com/@username" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        <div id="social_tiktok_status" style="display: none; margin-top: 4px; font-size: 0.78rem; font-weight: 600;"></div>
                        @error('social_tiktok')
                            <span style="color: #ef4444; font-size: 0.78rem; font-weight: 600; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="social_youtube" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">YouTube Link</label>
                        <input type="url" id="social_youtube" name="social_youtube" class="form-control @error('social_youtube') is-invalid @enderror" value="{{ old('social_youtube', $user->social_youtube) }}" placeholder="https://youtube.com/channel" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        <div id="social_youtube_status" style="display: none; margin-top: 4px; font-size: 0.78rem; font-weight: 600;"></div>
                        @error('social_youtube')
                            <span style="color: #ef4444; font-size: 0.78rem; font-weight: 600; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <h4 style="font-size: 0.95rem; font-weight: 700; color: #0f172a; margin-top: 25px; margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-shield-lock-fill" style="color: #6366f1;"></i> Security Verification Question & Answer (Optional)
                </h4>

                <div class="form-grid-2col" style="margin-bottom: 25px;">
                    <div class="form-group">
                        <label for="security_question" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Security Question</label>
                        <input type="text" id="security_question" name="security_question" class="form-control @error('security_question') is-invalid @enderror" value="{{ old('security_question', $user->security_question) }}" placeholder="e.g. What is the name of your first school or favorite pet?" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        <span style="font-size: 0.76rem; color: #64748b; margin-top: 4px; display: block;">Used for identity verification when resetting passwords or contacting support.</span>
                        @error('security_question')
                            <span style="color: #ef4444; font-size: 0.78rem; font-weight: 600; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="security_answer" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Security Answer</label>
                        <input type="text" id="security_answer" name="security_answer" class="form-control @error('security_answer') is-invalid @enderror" value="{{ old('security_answer', $user->decrypted_security_answer) }}" placeholder="Enter your confidential security answer..." style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        <span style="font-size: 0.76rem; color: #64748b; margin-top: 4px; display: block;">Keep your security answer memorable and confidential.</span>
                        @error('security_answer')
                            <span style="color: #ef4444; font-size: 0.78rem; font-weight: 600; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <h4 style="font-size: 0.95rem; font-weight: 700; color: #0f172a; margin-top: 25px; margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-key-fill" style="color: #d97706;"></i> Update Security Password Credentials (Optional)
                </h4>

                <div class="form-grid-3col" style="margin-bottom: 25px;">
                    <div class="form-group">
                        <label for="current_password" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Current Password</label>
                        <div style="position: relative;">
                            <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Enter current password" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 42px 10px 14px;">
                            <button type="button" class="toggle-password-btn" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; font-size: 1.1rem; padding: 0; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">New Password</label>
                        <div style="position: relative;">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Leave blank to keep current password" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 42px 10px 14px;">
                            <button type="button" class="toggle-password-btn" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; font-size: 1.1rem; padding: 0; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Confirm New Password</label>
                        <div style="position: relative;">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Re-type new password" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 42px 10px 14px;">
                            <button type="button" class="toggle-password-btn" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; font-size: 1.1rem; padding: 0; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" style="padding: 11px 26px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, var(--primary) 0%, #2563eb 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(59,130,246,0.35); cursor: pointer;">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    function cancelImageSelection() {
        const input = document.getElementById('profile_image');
        if (input) input.value = '';
        const previewContainer = document.getElementById('image-preview-container');
        if (previewContainer) previewContainer.style.display = 'none';
    }

    function previewProfileImage(event) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById('image-preview-container');
        const previewElement = document.getElementById('image-preview-element');
        const fileInfo = document.getElementById('image-file-info');

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewElement.src = e.target.result;
                previewContainer.style.display = 'flex';
                if (fileInfo) {
                    fileInfo.textContent = file.name;
                }
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
            previewElement.src = '#';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-password-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const icon = this.querySelector('i');
                if (input) {
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    }
                }
            });
        });

        // Real-time client side validation for social links
        const socialConfigs = [
            { id: 'social_instagram', statusId: 'social_instagram_status', name: 'Instagram', allowed: ['instagram.com', 'www.instagram.com', 'instagr.am', 'www.instagr.am', 'm.instagram.com'] },
            { id: 'social_facebook', statusId: 'social_facebook_status', name: 'Facebook', allowed: ['facebook.com', 'www.facebook.com', 'fb.com', 'www.fb.com', 'm.facebook.com', 'web.facebook.com', 'fb.watch'] },
            { id: 'social_tiktok', statusId: 'social_tiktok_status', name: 'TikTok', allowed: ['tiktok.com', 'www.tiktok.com', 'vm.tiktok.com', 'm.tiktok.com', 'vt.tiktok.com'] },
            { id: 'social_youtube', statusId: 'social_youtube_status', name: 'YouTube', allowed: ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'music.youtube.com', 'youtu.be', 'www.youtu.be'] }
        ];

        socialConfigs.forEach(config => {
            const inputEl = document.getElementById(config.id);
            const statusEl = document.getElementById(config.statusId);
            if (!inputEl || !statusEl) return;

            inputEl.addEventListener('input', function() {
                const val = inputEl.value.trim();
                if (!val) {
                    statusEl.style.display = 'none';
                    return;
                }

                try {
                    const parsed = new URL(val);
                    const host = parsed.hostname.toLowerCase();
                    if (config.allowed.includes(host)) {
                        statusEl.style.display = 'block';
                        statusEl.style.color = '#10b981';
                        statusEl.innerHTML = `<i class="bi bi-check-circle-fill"></i> Valid ${config.name} link.`;
                    } else {
                        statusEl.style.display = 'block';
                        statusEl.style.color = '#ef4444';
                        statusEl.innerHTML = `<i class="bi bi-exclamation-triangle-fill"></i> Link must be a valid ${config.name} URL.`;
                    }
                } catch (e) {
                    statusEl.style.display = 'block';
                    statusEl.style.color = '#ef4444';
                    statusEl.innerHTML = `<i class="bi bi-exclamation-triangle-fill"></i> Please enter a complete URL starting with http:// or https://.`;
                }
            });
        });
    });
</script>
@endsection
