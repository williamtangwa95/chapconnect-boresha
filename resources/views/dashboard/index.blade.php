@extends('layouts.app')

@section('title', $user->role === 'admin' ? 'ChapConnect - Staff Account Settings' : 'ChapConnect - Dashboard Settings')

@section('content')
<main class="main admin-main-container" style="max-width: 100%; width: 100%; margin: 15px 0; padding: 0 30px;">
    <div class="dashboard-container">

        @if(in_array($user->role, ['admin', 'customer_care']))
        <!-- Staff Member Welcome Header -->
        <div class="dashboard-header" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; padding: 25px 30px; border-radius: 16px; margin-bottom: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div class="dashboard-welcome" style="display: flex; align-items: center; gap: 20px;">
                <div class="dashboard-avatar" style="width: 70px; height: 70px; border-radius: 50%; overflow: hidden; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); display: flex; align-items: center; justify-content: center; font-size: 28px; color: #fff; box-shadow: 0 4px 15px rgba(99,102,241,0.4); flex-shrink: 0;">
                    @if($user->profile_image)
                    <img src="{{ asset($user->profile_image) }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                    <i class="bi bi-shield-lock-fill"></i>
                    @endif
                </div>
                <div class="dashboard-welcome-text">
                    <h2 style="color: #ffffff; margin: 0 0 4px 0; font-size: 1.5rem; font-weight: 800;">Welcome, {{ $user->name }}</h2>
                    <p style="color: #94a3b8; margin: 0; font-size: 0.92rem;">Staff Account Settings (Role: {{ $user->role === 'admin' ? 'Super Administrator' : 'Customer Care' }}). Update your profile details and security credentials.</p>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <button type="button" onclick="$('#user-support-modal').fadeIn(200);" class="nav-btn" style="border-radius: 20px; font-weight: 700; padding: 10px 18px; background: rgba(99,102,241,0.12); color: #6366f1; border: 1px solid rgba(99,102,241,0.3); text-decoration: none; display: inline-flex; align-items: center; gap: 6px; cursor: pointer;">
                    <i class="bi bi-headset"></i> Need Help / Support
                </button>
                @if(in_array($user->role, ['admin']))
                <a href="{{ route('admin.dashboard') }}" class="nav-btn nav-btn-login" style="border-radius: 20px; font-weight: 700; padding: 10px 22px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(99,102,241,0.4); text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="bi bi-speedometer2"></i> Admin Panel
                </a>
                @endif
            </div>
        </div>

        <!-- Staff Profile Form -->
        <div class="dashboard-panel" style="background: #ffffff; border-radius: 16px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
            <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 1.2rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-person-gear" style="color: var(--primary);"></i> Staff Account Profile & Security Credentials
            </h3>

            <form action="{{ route('dashboard.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label for="name" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    </div>

                    <div class="form-group">
                        <label for="email" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Account Email Address</label>
                        <input type="email" id="email" class="form-control" value="{{ $user->email }}" disabled style="background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label for="phone" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Phone Number (WhatsApp)</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    </div>

                    <div class="form-group">
                        <label for="country" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Country Location</label>
                        <input type="text" id="country" name="country" class="form-control" value="{{ old('country', $user->country ?? 'Tanzania') }}" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label for="profile_image" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Update Profile Avatar Photo</label>
                    <input type="file" id="profile_image" name="profile_image" class="form-control" accept="image/*" style="padding: 8px; border: 1px solid #cbd5e1; border-radius: 10px;" onchange="previewProfileImage(event)">

                    <div id="image-preview-container" style="margin-top: 12px; display: none; align-items: center; gap: 15px; background: #f8fafc; padding: 12px 16px; border-radius: 12px; border: 1px dashed var(--primary);">
                        <div style="width: 60px; height: 60px; border-radius: 50%; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.15); flex-shrink: 0; border: 2px solid var(--primary);">
                            <img id="image-preview-element" src="#" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div>
                            <span style="font-size: 13px; font-weight: 700; color: var(--primary); display: block;">✨ New Image Selected!</span>
                            <span id="image-file-info" style="font-size: 12px; color: var(--text-muted);">Previewing selected file</span>
                        </div>
                    </div>
                </div>

                <h4 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin-top: 30px; margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-key-fill" style="color: #d97706;"></i> Update Security Password Credentials (Optional)
                </h4>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 25px;">
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
                    <button type="submit" style="padding: 12px 28px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(99,102,241,0.35); cursor: pointer;">
                        Save Staff Account Updates
                    </button>
                </div>
            </form>
        </div>

        @else
        <!-- Regular Talent Dashboard -->
        <!-- Welcome Header -->
        <div class="dashboard-header">
            <div class="dashboard-welcome">
                <div class="dashboard-avatar">
                    @if($user->profile_image)
                    <img src="{{ asset($user->profile_image) }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    @else
                    <i class="bi bi-person-fill"></i>
                    @endif
                </div>
                <div class="dashboard-welcome-text">
                    <h2>Welcome, {{ $user->name }}</h2>
                    <p>Glad to have you back! Manage your portfolio, profile details, and public visibility.</p>
                </div>
            </div>

            <div style="display: flex; gap: 10px; align-items: center;">
                @if($user->is_published)
                <span style="padding: 6px 14px; border-radius: 20px; background: rgba(16,185,129,0.15); color: #10b981; font-weight: 700; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                    <span style="width:8px;height:8px;background:#10b981;border-radius:50%;"></span> LIVE &amp; PUBLIC
                </span>
                @else
                <span style="padding: 6px 14px; border-radius: 20px; background: rgba(239,68,68,0.15); color: #ef4444; font-weight: 700; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                    <span style="width:8px;height:8px;background:#ef4444;border-radius:50%;"></span> HIDDEN (Draft)
                </span>
                @endif
                <a href="{{ route('profile', $user->id) }}" target="_blank" class="vbtn" style="width: auto; padding: 8px 16px; background: var(--primary); color: white; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="bi bi-box-arrow-up-right"></i> Preview Profile
                </a>
            </div>
        </div>

        @if(request('tab') === 'billing')
            @include('dashboard.billing_tab_stub')
        @else
        <!-- Stats Widgets Grid -->
        <div class="dashboard-stats" style="margin-top: 25px;">
            <div class="stat-card">
                <div class="stat-icon secondary">
                    <i class="bi bi-heart-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $user->likes_received_count ?? 0 }}</div>
                    <div class="stat-label">Likes</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon secondary">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $user->followers_received_count ?? 0 }}</div>
                    <div class="stat-label">Followers</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon secondary">
                    <i class="bi bi-chat-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $user->comments_received_count ?? 0 }}</div>
                    <div class="stat-label">Comments</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(14,165,233,0.12); color: #0284c7;">
                    <i class="bi bi-eye-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ number_format($user->views_count ?? 0) }}</div>
                    <div class="stat-label">Profile Views</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon primary">
                    <i class="bi bi-camera-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $user->media()->count() }}</div>
                    <div class="stat-label">Portfolio Items</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $completion }}%</div>
                    <div class="stat-label">Profile Completion</div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Grid -->
        <div class="dashboard-grid" style="margin-top: 25px;">
            <!-- Quick Actions Panel -->
            <div class="dashboard-panel">
                <h3><i class="bi bi-sliders2-vertical" style="color: var(--primary);"></i> Quick Actions</h3>
                <div class="action-grid">
                    <a href="{{ route('home') }}" class="action-card">
                        <i class="bi bi-grid-fill"></i>
                        <span>Browse Talent Feed</span>
                    </a>
                    <a href="{{ route('dashboard.photos') }}" class="action-card">
                        <i class="bi bi-camera-fill"></i>
                        <span>Manage Photos</span>
                    </a>
                    <a href="{{ route('dashboard.videos') }}" class="action-card">
                        <i class="bi bi-camera-video-fill"></i>
                        <span>Manage Videos</span>
                    </a>
                    <a href="{{ route('dashboard.news') }}" class="action-card">
                        <i class="bi bi-newspaper"></i>
                        <span>Manage News</span>
                    </a>
                    <a href="{{ route('dashboard.comments') }}" class="action-card">
                        <i class="bi bi-chat-left-text-fill"></i>
                        <span>Manage Comments</span>
                    </a>
                    <a href="{{ route('profile', $user->id) }}" target="_blank" class="action-card">
                        <i class="bi bi-person-badge-fill"></i>
                        <span>Public Profile</span>
                    </a>
                </div>
            </div>

            <!-- Profile Completion & Publish Control Panel -->
            <div class="dashboard-panel">
                <h3><i class="bi bi-shield-lock-fill" style="color: #10b981;"></i> Publishing Control</h3>

                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="font-size: 13px; color: var(--text-muted); font-weight: 500;">Profile Score</span>
                        <span style="font-size: 16px; font-weight: 800; color: {{ $completion >= 60 ? '#10b981' : 'var(--primary)' }};">{{ $completion }}%</span>
                    </div>
                    <div style="background: rgba(0,0,0,0.06); border-radius: 999px; height: 8px; overflow: hidden;">
                        <div style="height: 100%; width: {{ $completion }}%; border-radius: 999px; background: {{ $completion >= 60 ? 'linear-gradient(90deg, #10b981, #059669)' : 'var(--primary)' }}; transition: width 0.5s ease;"></div>
                    </div>
                </div>

                @if($user->is_published)
                <form action="{{ route('dashboard.unpublish') }}" method="POST">
                    @csrf
                    <button type="submit" style="width: 100%; padding: 10px; background: rgba(239,68,68,0.12); color: #ef4444; border: 1px solid rgba(239,68,68,0.3); border-radius: 8px; font-weight: 700; font-size: 14px; cursor: pointer; transition: all 0.2s;">
                        🔒 Unpublish Profile (Draft)
                    </button>
                </form>
                @else
                <form action="{{ route('dashboard.publish') }}" method="POST">
                    @csrf
                    <button type="submit"
                        @if($completion < 60) disabled title="Complete at least 60% of your profile to publish" @endif
                        style="width: 100%; padding: 10px; background: {{ $completion >= 60 ? 'var(--primary)' : 'rgba(100,100,100,0.2)' }}; color: {{ $completion >= 60 ? '#fff' : '#888' }}; border: none; border-radius: 8px; font-weight: 700; font-size: 14px; cursor: {{ $completion >= 60 ? 'pointer' : 'not-allowed' }}; transition: all 0.2s;">
                        🌐 Publish Profile Live
                    </button>
                </form>
                @endif

                @if($completion < 60)
                <p style="font-size: 12px; color: #888; margin-top: 10px; text-align: center;">Complete at least 60% of your profile to enable live publishing.</p>
                @endif
            </div>
        </div>

        <!-- Talent Payout & Performance Section -->
        <div class="dashboard-panel" style="margin-top: 25px; background: #ffffff; border-radius: 16px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-wallet2" style="color: var(--primary);"></i> Talent Performance Payout Section
                    </h3>
                    <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.88rem;">Track your progress towards unlocking cash payouts from the platform.</p>
                </div>
                <div>
                    <span style="font-weight: 800; color: #10b981; font-size: 1.1rem; background: rgba(16,185,129,0.1); padding: 6px 14px; border-radius: 10px; display: inline-block;">
                        Payout Amount: {{ number_format($paymentSettings['payment_amount'], 2) }} TZS
                    </span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 25px;">
                <!-- Target Checklist -->
                <!-- Like Requirement -->
                @php
                    $likesCount = $user->likesReceived()->count();
                    $likesRequired = $paymentSettings['payment_likes_required'];
                    $likesMet = $likesCount >= $likesRequired;
                @endphp
                <div style="background: #f8fafc; border-radius: 12px; padding: 16px; border: 1px solid {{ $likesMet ? '#bbf7d0' : '#e2e8f0' }}; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                    <div>
                        <span style="font-size: 0.78rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Likes Milestone</span>
                        <div style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-top: 4px;">{{ $likesCount }} / {{ $likesRequired }}</div>
                    </div>
                    <div style="font-size: 1.5rem; color: {{ $likesMet ? '#10b981' : '#cbd5e1' }}">
                        <i class="bi {{ $likesMet ? 'bi-patch-check-fill' : 'bi-dash-circle' }}"></i>
                    </div>
                </div>

                <!-- Followers Requirement -->
                @php
                    $followersCount = $user->followersReceived()->count();
                    $followersRequired = $paymentSettings['payment_followers_required'];
                    $followersMet = $followersCount >= $followersRequired;
                @endphp
                <div style="background: #f8fafc; border-radius: 12px; padding: 16px; border: 1px solid {{ $followersMet ? '#bbf7d0' : '#e2e8f0' }}; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                    <div>
                        <span style="font-size: 0.78rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Followers Milestone</span>
                        <div style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-top: 4px;">{{ $followersCount }} / {{ $followersRequired }}</div>
                    </div>
                    <div style="font-size: 1.5rem; color: {{ $followersMet ? '#10b981' : '#cbd5e1' }}">
                        <i class="bi {{ $followersMet ? 'bi-patch-check-fill' : 'bi-dash-circle' }}"></i>
                    </div>
                </div>

                <!-- Comments Requirement -->
                @php
                    $commentsCount = $user->commentsReceived()->count();
                    $commentsRequired = $paymentSettings['payment_comments_required'];
                    $commentsMet = $commentsCount >= $commentsRequired;
                @endphp
                <div style="background: #f8fafc; border-radius: 12px; padding: 16px; border: 1px solid {{ $commentsMet ? '#bbf7d0' : '#e2e8f0' }}; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                    <div>
                        <span style="font-size: 0.78rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Comments Milestone</span>
                        <div style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-top: 4px;">{{ $commentsCount }} / {{ $commentsRequired }}</div>
                    </div>
                    <div style="font-size: 1.5rem; color: {{ $commentsMet ? '#10b981' : '#cbd5e1' }}">
                        <i class="bi {{ $commentsMet ? 'bi-patch-check-fill' : 'bi-dash-circle' }}"></i>
                    </div>
                </div>

                <!-- Views Requirement -->
                @php
                    $viewsCount = $user->views_count;
                    $viewsRequired = $paymentSettings['payment_views_required'];
                    $viewsMet = $viewsCount >= $viewsRequired;
                @endphp
                <div style="background: #f8fafc; border-radius: 12px; padding: 16px; border: 1px solid {{ $viewsMet ? '#bbf7d0' : '#e2e8f0' }}; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                    <div>
                        <span style="font-size: 0.78rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Page Views Milestone</span>
                        <div style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-top: 4px;">{{ $viewsCount }} / {{ $viewsRequired }}</div>
                    </div>
                    <div style="font-size: 1.5rem; color: {{ $viewsMet ? '#10b981' : '#cbd5e1' }}">
                        <i class="bi {{ $viewsMet ? 'bi-patch-check-fill' : 'bi-dash-circle' }}"></i>
                    </div>
                </div>
            </div>

            <!-- Payout Action & Status Center -->
            @php
                $allMet = $likesMet && $followersMet && $commentsMet && $viewsMet;
            @endphp
            @if($paymentRequest)
                @if($paymentRequest->status === 'pending')
                    <div style="background: #fffbeb; border: 1px solid #fef3c7; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                        <div style="width: 48px; height: 48px; border-radius: 50%; background: rgba(245,158,11,0.1); color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div style="flex-grow: 1;">
                            <h4 style="margin: 0 0 4px 0; font-size: 0.95rem; font-weight: 700; color: #b45309;">Payout Request Submitted &amp; Pending</h4>
                            <p style="margin: 0; color: #64748b; font-size: 0.85rem;">You requested payment on {{ $paymentRequest->created_at->format('M d, Y') }}. Our administrative and customer care personnel are currently auditing your stats.</p>
                        </div>
                    </div>
                @elseif($paymentRequest->status === 'paid')
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                        <div style="width: 48px; height: 48px; border-radius: 50%; background: rgba(16,185,129,0.1); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <div style="flex-grow: 1;">
                            <h4 style="margin: 0 0 4px 0; font-size: 0.95rem; font-weight: 700; color: #15803d;">🎉 Payout Request Approved &amp; Paid!</h4>
                            <p style="margin: 0; color: #64748b; font-size: 0.85rem;">
                                You were successfully paid <strong>{{ number_format($paymentRequest->amount, 2) }} TZS</strong> on {{ $paymentRequest->paid_at->format('M d, Y') }} via <strong>{{ $paymentRequest->payment_method }}</strong>.
                                @if($paymentRequest->payment_reference)
                                    Reference ID: <strong>{{ $paymentRequest->payment_reference }}</strong>.
                                @endif
                                <br>
                                <span style="font-weight:700; color:#15803d;">Note: As per rules, you have received your milestone payment and cannot apply for additional payouts.</span>
                            </p>
                        </div>
                    </div>
                @elseif($paymentRequest->status === 'rejected')
                    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 20px; display: flex; flex-direction: column; gap: 15px;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 48px; height: 48px; border-radius: 50%; background: rgba(239,68,68,0.1); color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                                <i class="bi bi-x-circle-fill"></i>
                            </div>
                            <div style="flex-grow: 1;">
                                <h4 style="margin: 0 0 4px 0; font-size: 0.95rem; font-weight: 700; color: #991b1b;">Payout Request Rejected</h4>
                                <p style="margin: 0; color: #64748b; font-size: 0.85rem;">Reason: <strong style="color: #991b1b;">{{ $paymentRequest->admin_notes }}</strong></p>
                            </div>
                        </div>
                        @if($allMet)
                        <form action="{{ route('dashboard.request-payment') }}" method="POST" style="margin: 0; border-top: 1px solid #fecaca; padding-top: 15px;">
                            @csrf
                            <button type="submit" style="padding: 10px 22px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(59,130,246,0.35); cursor: pointer; font-size: 0.88rem;">
                                Re-submit Payout Request
                            </button>
                        </form>
                        @endif
                    </div>
                @endif
            @else
                <!-- No Request Exists Yet -->
                @if($allMet)
                    <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                        <div style="flex-grow: 1;">
                            <h4 style="margin: 0 0 4px 0; font-size: 0.95rem; font-weight: 700; color: #1e3a8a;">✨ You are Eligible for Payout!</h4>
                            <p style="margin: 0; color: #64748b; font-size: 0.85rem;">Congratulations, all performance milestones have been completed successfully. You can now request your payment.</p>
                        </div>
                        <form action="{{ route('dashboard.request-payment') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" style="padding: 12px 26px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(16,185,129,0.35); cursor: pointer; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px;">
                                <i class="bi bi-wallet2"></i> Submit Payout Request
                            </button>
                        </form>
                    </div>
                @else
                    <div style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                        <div style="width: 48px; height: 48px; border-radius: 50%; background: #e2e8f0; color: #64748b; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                            <i class="bi bi-lock-fill"></i>
                        </div>
                        <div style="flex-grow: 1;">
                            <h4 style="margin: 0 0 4px 0; font-size: 0.95rem; font-weight: 700; color: #334155;">Payout Eligibility Locked</h4>
                            <p style="margin: 0; color: #64748b; font-size: 0.85rem;">You need to complete all four milestone targets above to unlock the cash payout feature.</p>
                        </div>
                    </div>
                @endif
            @endif
        </div>

        <!-- Settings Form Panel -->
        <div class="dashboard-panel" style="margin-top: 25px;">
            <h3><i class="bi bi-gear-fill" style="color: var(--primary);"></i> Edit Profile Information</h3>

            <form action="{{ route('dashboard.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">
                    <div class="form-group">
                        <label for="name">Stage Name / Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="category_read">Registered Category</label>
                        <input type="text" id="category_read" class="form-control" value="{{ $user->category_label }}" disabled style="opacity: 0.7;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                    </div>

                    <div class="form-group">
                        <label for="country">Country Location</label>
                        <input type="text" id="country" name="country" class="form-control" value="{{ old('country', $user->country) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="profile_image">Change Profile Photo</label>
                    <input type="file" id="profile_image" name="profile_image" class="form-control" accept="image/*" style="padding: 8px;" onchange="previewProfileImage(event)">

                    <!-- Live Image Preview Box -->
                    <div id="image-preview-container" style="margin-top: 12px; display: none; align-items: center; gap: 15px; background: #f8fafc; padding: 12px 16px; border-radius: 12px; border: 1px dashed var(--primary);">
                        <div style="width: 70px; height: 70px; border-radius: 50%; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.15); flex-shrink: 0; border: 2px solid var(--primary);">
                            <img id="image-preview-element" src="#" alt="New Profile Preview" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div>
                            <span style="font-size: 13px; font-weight: 700; color: var(--primary); display: block;">✨ New Image Selected!</span>
                            <span id="image-file-info" style="font-size: 12px; color: var(--text-muted);">Previewing selected file</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Short Bio Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4" placeholder="Tell the world about yourself and your creative works...">{{ old('description', $user->description) }}</textarea>
                </div>

                <h4 style="font-size: 15px; color: var(--primary); margin-top: 25px; margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Social Media Links</h4>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px;">
                    <div class="form-group">
                        <label for="social_instagram">Instagram Link</label>
                        <input type="url" id="social_instagram" name="social_instagram" class="form-control @error('social_instagram') is-invalid @enderror" value="{{ old('social_instagram', $user->social_instagram) }}" placeholder="https://instagram.com/username">
                        <div id="social_instagram_status" style="display: none; margin-top: 4px; font-size: 0.78rem; font-weight: 600;"></div>
                        @error('social_instagram')
                            <span style="color: #ef4444; font-size: 0.78rem; font-weight: 600; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="social_facebook">Facebook Link</label>
                        <input type="url" id="social_facebook" name="social_facebook" class="form-control @error('social_facebook') is-invalid @enderror" value="{{ old('social_facebook', $user->social_facebook) }}" placeholder="https://facebook.com/page">
                        <div id="social_facebook_status" style="display: none; margin-top: 4px; font-size: 0.78rem; font-weight: 600;"></div>
                        @error('social_facebook')
                            <span style="color: #ef4444; font-size: 0.78rem; font-weight: 600; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="social_tiktok">TikTok Link</label>
                        <input type="url" id="social_tiktok" name="social_tiktok" class="form-control @error('social_tiktok') is-invalid @enderror" value="{{ old('social_tiktok', $user->social_tiktok) }}" placeholder="https://tiktok.com/@username">
                        <div id="social_tiktok_status" style="display: none; margin-top: 4px; font-size: 0.78rem; font-weight: 600;"></div>
                        @error('social_tiktok')
                            <span style="color: #ef4444; font-size: 0.78rem; font-weight: 600; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="social_youtube">YouTube Link</label>
                        <input type="url" id="social_youtube" name="social_youtube" class="form-control @error('social_youtube') is-invalid @enderror" value="{{ old('social_youtube', $user->social_youtube) }}" placeholder="https://youtube.com/channel">
                        <div id="social_youtube_status" style="display: none; margin-top: 4px; font-size: 0.78rem; font-weight: 600;"></div>
                        @error('social_youtube')
                            <span style="color: #ef4444; font-size: 0.78rem; font-weight: 600; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <h4 style="font-size: 15px; color: var(--primary); margin-top: 25px; margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-key-fill" style="color: #d97706;"></i> Update Security Password Credentials (Optional)
                </h4>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 25px;">
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

                <button type="submit" class="nav-btn nav-btn-register" style="margin-top: 15px; width: auto; padding: 10px 24px; cursor: pointer; border: none;">Save Changes</button>
            </form>
        </div>
        @endif
        @endif
    </div>
</main>

<script>
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
                    fileInfo.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                }

                // Also live-update the header avatar if present
                const headerAvatarImg = document.querySelector('.dashboard-avatar img');
                if (headerAvatarImg) {
                    headerAvatarImg.src = e.target.result;
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