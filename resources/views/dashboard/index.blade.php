@extends('layouts.app')

@section('title', $user->role === 'admin' ? 'ChapConnect - Staff Account Settings' : 'ChapConnect - Dashboard Settings')

@section('styles')
<style>
    /* Dashboard Main Responsive Styles */
    .dash-main-container {
        max-width: 100%;
        width: 100%;
        margin: 15px 0;
        padding: 0 24px;
    }

    .dash-header-card {
        background: #ffffff;
        color: #0f172a;
        padding: 22px 28px;
        border-radius: 20px;
        margin-bottom: 22px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .dash-avatar-wrapper {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        overflow: hidden;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        color: #fff;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.25);
        flex-shrink: 0;
        border: 2px solid #e0e7ff;
    }

    .dash-welcome-title {
        color: #0f172a;
        margin: 0 0 4px 0;
        font-size: 1.4rem;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .dash-welcome-sub {
        color: #64748b;
        margin: 0;
        font-size: 0.9rem;
        line-height: 1.4;
    }

    .dash-panel-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid var(--border-color, #e2e8f0);
    }

    /* Stats Grid Styling */
    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
    }

    .stat-card-custom {
        background: #ffffff;
        border-radius: 16px;
        padding: 18px 20px;
        border: 1px solid var(--border-color, #e2e8f0);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        display: flex;
        align-items: center;
        gap: 14px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
    }

    /* Quick Action Grid */
    .action-grid-custom {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 12px;
    }

    .action-card-custom {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        gap: 8px;
        text-decoration: none;
        color: #334155;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }

    .action-card-custom i {
        font-size: 1.4rem;
        color: var(--primary, #3b82f6);
    }

    .action-card-custom:hover {
        background: #ffffff;
        border-color: var(--primary, #3b82f6);
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.12);
        transform: translateY(-2px);
        color: var(--primary, #3b82f6);
    }

    /* Payout Milestone Cards Grid */
    .payout-milestone-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .payout-card {
        background: #f8fafc;
        border-radius: 14px;
        padding: 16px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
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
        .dash-main-container {
            padding: 0 12px !important;
            margin: 10px 0 !important;
        }

        .dash-header-card {
            padding: 16px !important;
            border-radius: 16px !important;
            flex-direction: column;
            align-items: flex-start !important;
            gap: 14px !important;
            margin-bottom: 16px !important;
        }

        .dash-header-actions {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
        }

        .dash-header-actions a,
        .dash-header-actions button,
        .dash-header-actions span {
            font-size: 12px !important;
            padding: 7px 12px !important;
        }

        .dash-welcome-text h2,
        .dash-welcome-title {
            font-size: 1.15rem !important;
        }

        .dash-welcome-text p,
        .dash-welcome-sub {
            font-size: 0.8rem !important;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .dash-avatar-wrapper,
        .dashboard-avatar {
            width: 48px !important;
            height: 48px !important;
            font-size: 20px !important;
        }

        .dash-panel-card,
        .dashboard-panel {
            padding: 16px !important;
            border-radius: 16px !important;
        }

        /* 2 Column Stat Cards on Mobile */
        .dashboard-stats {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px !important;
            margin-top: 16px !important;
        }

        .stat-card-custom,
        .stat-card {
            padding: 12px 14px !important;
            gap: 10px !important;
            border-radius: 12px !important;
        }

        .stat-icon {
            width: 38px !important;
            height: 38px !important;
            font-size: 1.1rem !important;
            border-radius: 10px !important;
        }

        .stat-value {
            font-size: 1.15rem !important;
        }

        .stat-label {
            font-size: 0.72rem !important;
        }

        /* 2 Column Quick Actions on Mobile */
        .action-grid,
        .action-grid-custom {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px !important;
        }

        .action-card,
        .action-card-custom {
            padding: 12px 10px !important;
            font-size: 0.78rem !important;
            border-radius: 12px !important;
        }

        .action-card i,
        .action-card-custom i {
            font-size: 1.25rem !important;
        }

        /* 2 Column Payout Grid on Mobile */
        .payout-milestone-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px !important;
        }

        .payout-card {
            padding: 12px 10px !important;
            border-radius: 10px !important;
        }

        .payout-card-title {
            font-size: 0.7rem !important;
        }

        .payout-card-val {
            font-size: 0.95rem !important;
        }

        .payout-card-icon {
            font-size: 1.2rem !important;
        }

        /* Forms on Mobile */
        .form-grid-2col,
        .form-grid-3col {
            grid-template-columns: 1fr !important;
            gap: 12px !important;
        }
    }
</style>
@endsection

@section('content')
<main class="main admin-main-container dash-main-container">
    <div class="dashboard-container">

        @if(in_array($user->role, ['admin', 'customer_care']))
        <!-- Staff Member Welcome Header -->
        <div class="dash-header-card">
            <div class="dashboard-welcome" style="display: flex; align-items: center; gap: 16px;">
                <div class="dash-avatar-wrapper">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover; object-position: top center;">
                </div>
                <div class="dashboard-welcome-text">
                    <h2 class="dash-welcome-title">{{ __('Welcome') }}, {{ $user->name }}</h2>
                    <p class="dash-welcome-sub">{{ __('Dashboard') }} ({{ __('Role') }}: {{ $user->role === 'admin' ? __('Administration') : __('Customer Care') }}). {{ __('Manage your profile settings and security credentials.') }}</p>
                </div>
            </div>
            <div class="dash-header-actions">
                <button type="button" onclick="$('#user-support-modal').fadeIn(200);" class="nav-btn" style="border-radius: 20px; font-weight: 700; padding: 9px 16px; background: rgba(99,102,241,0.15); color: #818cf8; border: 1px solid rgba(99,102,241,0.3); text-decoration: none; display: inline-flex; align-items: center; gap: 6px; cursor: pointer;">
                    <i class="bi bi-headset"></i> {{ __('Need Help / Support') }}
                </button>
                @if(in_array($user->role, ['admin']))
                <a href="{{ route('admin.dashboard') }}" class="nav-btn nav-btn-login" style="border-radius: 20px; font-weight: 700; padding: 9px 18px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(99,102,241,0.4); text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="bi bi-speedometer2"></i> {{ __('Admin Panel') }}
                </a>
                @endif
            </div>
        </div>

        <!-- Staff Profile Form -->
        <div class="dash-panel-card">
            <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 1.15rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-person-gear" style="color: var(--primary);"></i> {{ __('Staff Account Profile & Security Credentials') }}
            </h3>

            <form action="{{ route('dashboard.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-grid-2col" style="margin-bottom: 20px;">
                    <div class="form-group">
                        <label for="name" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">{{ __('Full Name') }}</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    </div>

                    <div class="form-group">
                        <label for="email" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">{{ __('Account Email Address') }}</label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        @error('email')
                        <span style="color: #ef4444; font-size: 0.78rem; font-weight: 600; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-grid-2col" style="margin-bottom: 20px;">
                    <div class="form-group">
                        <label for="phone" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">{{ __('Phone Number (WhatsApp)') }}</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    </div>

                    <div class="form-group">
                        <label for="country" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">{{ __('Country Location') }}</label>
                        <input type="text" id="country" name="country" class="form-control" value="{{ old('country', $user->country ?? 'Tanzania') }}" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label for="profile_image" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">{{ __('Update Profile Avatar Photo') }}</label>
                    <input type="file" id="profile_image" name="profile_image" class="form-control" accept="image/*" style="padding: 8px; border: 1px solid #cbd5e1; border-radius: 10px;" onchange="previewProfileImage(event)">

                    <div id="image-preview-container" style="margin-top: 12px; display: none; align-items: center; gap: 15px; background: #f8fafc; padding: 12px 16px; border-radius: 12px; border: 1px dashed var(--primary);">
                        <div style="width: 54px; height: 54px; border-radius: 50%; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.15); flex-shrink: 0; border: 2px solid var(--primary);">
                            <img id="image-preview-element" src="#" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div>
                            <span style="font-size: 13px; font-weight: 700; color: var(--primary); display: block;">✨ New Image Selected!</span>
                            <span id="image-file-info" style="font-size: 12px; color: var(--text-muted);">Previewing selected file</span>
                        </div>
                    </div>
                </div>

                <h4 style="font-size: 0.95rem; font-weight: 700; color: #0f172a; margin-top: 25px; margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-key-fill" style="color: #d97706;"></i> {{ __('Update Security Password Credentials') }} ({{ __('Optional') }})
                </h4>

                <div class="form-grid-3col" style="margin-bottom: 25px;">
                    <div class="form-group">
                        <label for="current_password" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">{{ __('Current Password') }}</label>
                        <div style="position: relative;">
                            <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Enter current password" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 42px 10px 14px;">
                            <button type="button" class="toggle-password-btn" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; font-size: 1.1rem; padding: 0; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">{{ __('New Password') }}</label>
                        <div style="position: relative;">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Leave blank to keep current password" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 42px 10px 14px;">
                            <button type="button" class="toggle-password-btn" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; font-size: 1.1rem; padding: 0; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">{{ __('Confirm New Password') }}</label>
                        <div style="position: relative;">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Re-type new password" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 42px 10px 14px;">
                            <button type="button" class="toggle-password-btn" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; font-size: 1.1rem; padding: 0; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" style="padding: 10px 24px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(99,102,241,0.35); cursor: pointer;">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>

        @else
        <!-- Regular Talent Dashboard -->
        <!-- Welcome Header -->
        <div class="dash-header-card">
            <div class="dashboard-welcome" style="display: flex; align-items: center; gap: 16px;">
                <div class="dash-avatar-wrapper">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="dashboard-welcome-text">
                    <h2 class="dash-welcome-title">{{ __('Welcome') }}, {{ $user->name }}</h2>
                    <p class="dash-welcome-sub">{{ __('Glad to have you back! Manage your portfolio, profile details, and public visibility.') }}</p>
                </div>
            </div>

            <div class="dash-header-actions">
                @if($user->is_published)
                <span style="padding: 6px 14px; border-radius: 20px; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; font-weight: 700; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                    <span style="width:8px;height:8px;background:#10b981;border-radius:50%;"></span> {{ __('LIVE & PUBLIC') }}
                </span>
                @else
                <span style="padding: 6px 14px; border-radius: 20px; background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; font-weight: 700; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                    <span style="width:8px;height:8px;background:#ef4444;border-radius:50%;"></span> {{ __('HIDDEN (Draft)') }}
                </span>
                @endif
                <a href="{{ route('profile', $user->id) }}" target="_blank" class="vbtn" style="width: auto; padding: 9px 18px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: white; border-radius: 12px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; font-weight: 700; box-shadow: 0 4px 15px rgba(99,102,241,0.28);">
                    <i class="bi bi-box-arrow-up-right"></i> {{ __('Preview Profile') }}
                </a>
            </div>
        </div>

        @if(request('tab') === 'billing')
        @include('dashboard.billing_tab_stub')
        @else
        <!-- Stats Widgets Grid -->
        <div class="dashboard-stats">
            <div class="stat-card stat-card-custom">
                <div class="stat-icon secondary" style="background: rgba(239,68,68,0.12); color: #ef4444;">
                    <i class="bi bi-heart-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $user->likes_received_count ?? 0 }}</div>
                    <div class="stat-label">{{ __('Likes') }}</div>
                </div>
            </div>
            <div class="stat-card stat-card-custom">
                <div class="stat-icon secondary" style="background: rgba(99,102,241,0.12); color: #6366f1;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $user->followers_received_count ?? 0 }}</div>
                    <div class="stat-label">{{ __('Followers') }}</div>
                </div>
            </div>
            <div class="stat-card stat-card-custom">
                <div class="stat-icon secondary" style="background: rgba(245,158,11,0.12); color: #f59e0b;">
                    <i class="bi bi-chat-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $user->comments_received_count ?? 0 }}</div>
                    <div class="stat-label">{{ __('Comments') }}</div>
                </div>
            </div>
            <div class="stat-card stat-card-custom">
                <div class="stat-icon" style="background: rgba(14,165,233,0.12); color: #0284c7;">
                    <i class="bi bi-eye-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ number_format($user->views_count ?? 0) }}</div>
                    <div class="stat-label">{{ __('Profile Views') }}</div>
                </div>
            </div>
            <div class="stat-card stat-card-custom">
                <div class="stat-icon primary" style="background: rgba(59,130,246,0.12); color: #2563eb;">
                    <i class="bi bi-camera-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $user->media()->count() }}</div>
                    <div class="stat-label">{{ __('Portfolio Items') }}</div>
                </div>
            </div>
            <div class="stat-card stat-card-custom">
                <div class="stat-icon success" style="background: rgba(16,185,129,0.12); color: #10b981;">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $completion }}%</div>
                    <div class="stat-label">{{ __('Profile Completion') }}</div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Grid -->
        <div class="dashboard-grid" style="margin-top: 22px;">
            <!-- Quick Actions Panel -->
            <div class="dashboard-panel dash-panel-card">
                <h3 style="margin-top: 0; margin-bottom: 16px; font-size: 1.1rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-sliders2-vertical" style="color: var(--primary);"></i> {{ __('Quick Actions') }}
                </h3>
                <div class="action-grid action-grid-custom">
                    <a href="{{ route('home') }}" class="action-card action-card-custom">
                        <i class="bi bi-grid-fill"></i>
                        <span>{{ __('All Talents') }}</span>
                    </a>
                    <a href="{{ route('dashboard.photos') }}" class="action-card action-card-custom">
                        <i class="bi bi-camera-fill"></i>
                        <span>{{ __('Post Photos') }}</span>
                    </a>
                    <a href="{{ route('dashboard.videos') }}" class="action-card action-card-custom">
                        <i class="bi bi-camera-video-fill"></i>
                        <span>{{ __('Post Videos') }}</span>
                    </a>
                    <a href="{{ route('dashboard.news') }}" class="action-card action-card-custom">
                        <i class="bi bi-newspaper"></i>
                        <span>{{ __('Post News') }}</span>
                    </a>
                    <a href="{{ route('dashboard.comments') }}" class="action-card action-card-custom">
                        <i class="bi bi-chat-left-text-fill"></i>
                        <span>{{ __('Post Comments') }}</span>
                    </a>
                    <a href="{{ route('profile', $user->id) }}" target="_blank" class="action-card action-card-custom">
                        <i class="bi bi-person-badge-fill"></i>
                        <span>{{ __('View Profile') }}</span>
                    </a>
                </div>
            </div>

            <!-- Profile Completion & Publish Control Panel -->
            <div class="dashboard-panel dash-panel-card">
                <h3 style="margin-top: 0; margin-bottom: 16px; font-size: 1.1rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-shield-lock-fill" style="color: #10b981;"></i> {{ __('Publishing Control') }}
                </h3>

                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="font-size: 13px; color: var(--text-muted); font-weight: 500;">{{ __('Profile Completion') }}</span>
                        <span style="font-size: 16px; font-weight: 800; color: {{ $completion >= 60 ? '#10b981' : 'var(--primary)' }};">{{ $completion }}%</span>
                    </div>
                    <div style="background: rgba(0,0,0,0.06); border-radius: 999px; height: 8px; overflow: hidden;">
                        <div style="height: 100%; width: {{ $completion }}%; border-radius: 999px; background: {{ $completion >= 60 ? 'linear-gradient(90deg, #10b981, #059669)' : 'var(--primary)' }}; transition: width 0.5s ease;"></div>
                    </div>
                </div>

                @if($user->is_published)
                <form action="{{ route('dashboard.unpublish') }}" method="POST">
                    @csrf
                    <button type="submit" style="width: 100%; padding: 10px; background: rgba(239,68,68,0.12); color: #ef4444; border: 1px solid rgba(239,68,68,0.3); border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; transition: all 0.2s;">
                        <i class="bi bi-eye-slash-fill"></i> {{ __('HIDDEN (Draft - Click to Hide)') }}
                    </button>
                </form>
                @elseif($user->requiresPaymentToPublish())
                @php
                $unpaidInvoice = $user->invoices()->where('payment_status', 'Unpaid')->latest()->first();
                $hasSubmittedPaymentRef = $unpaidInvoice && !empty($unpaidInvoice->notes);
                $publishingFeeAmount = number_format(floatval(\App\Models\SystemSetting::get('payment_amount', 10000.00)));
                @endphp
                @if($hasSubmittedPaymentRef)
                <div style="text-align: center;">
                    <span style="font-size: 0.76rem; font-weight: 800; color: #0284c7; background: rgba(14,165,233,0.12); border: 1px solid rgba(14,165,233,0.3); padding: 4px 12px; border-radius: 20px; display: inline-block; margin-bottom: 10px;">
                        <i class="bi bi-hourglass-split"></i> {{ __('Payment Submitted - Verification Pending') }}
                    </span>
                    <button type="button" onclick="$('#helpdesk-contacts-modal').fadeIn(200);" style="width: 100%; padding: 11px; background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%); color: #ffffff; border: none; border-radius: 10px; font-weight: 800; font-size: 14px; cursor: pointer; box-shadow: 0 4px 14px rgba(2,132,199,0.35); transition: all 0.2s;">
                        <i class="bi bi-headset"></i> {{ __('Notify Help Desk / Contact Support') }}
                    </button>
                    <small style="display: block; font-size: 0.75rem; color: #64748b; margin-top: 6px;">
                        {{ __('Submitted Ref:') }} <strong style="color: #0f172a;">{{ str_replace('Transaction Ref: ', '', $unpaidInvoice->notes) }}</strong>
                    </small>
                </div>
                @else
                <div style="text-align: center;">
                    <span style="font-size: 0.76rem; font-weight: 800; color: #d97706; background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.3); padding: 4px 12px; border-radius: 20px; display: inline-block; margin-bottom: 10px;">
                        <i class="bi bi-wallet2"></i> {{ __('Payment Confirmation Required') }}
                    </span>
                    <button type="button" onclick="$('#publish-payment-modal').fadeIn(200);" style="width: 100%; padding: 11px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #ffffff; border: none; border-radius: 10px; font-weight: 800; font-size: 14px; cursor: pointer; box-shadow: 0 4px 14px rgba(245,158,11,0.35); transition: all 0.2s;">
                        <i class="bi bi-credit-card"></i> {{ __('PAY TZS') }} {{ $publishingFeeAmount }}/- {{ __('TO PUBLISH') }}
                    </button>
                </div>
                @endif
                @else
                <form action="{{ route('dashboard.publish') }}" method="POST">
                    @csrf
                    <button type="submit"
                        @if($completion < 60) disabled title="{{ __('Complete at least 60% of your profile to publish') }}" @endif
                        style="width: 100%; padding: 10px; background: {{ $completion >= 60 ? 'var(--primary)' : 'rgba(100,100,100,0.2)' }}; color: {{ $completion >= 60 ? '#fff' : '#888' }}; border: none; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: {{ $completion >= 60 ? 'pointer' : 'not-allowed' }}; transition: all 0.2s;">
                        <i class="bi bi-broadcast"></i> {{ __('LIVE & PUBLIC') }}
                    </button>
                </form>
                @endif

                @if($completion < 60)
                    <p style="font-size: 12px; color: #888; margin-top: 10px; text-align: center; margin-bottom: 0;">{{ __('Complete at least 60% of your profile to enable live publishing.') }}</p>
                    @endif
            </div>
        </div>

        <!-- Talent Payout & Performance Section -->
        <div class="dashboard-panel dash-panel-card" style="margin-top: 22px;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
                <div>
                    <h3 style="margin: 0; font-size: 1.15rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-wallet2" style="color: var(--primary);"></i> {{ __('Talent Performance Payout Section') }}
                    </h3>
                    <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.85rem;">{{ __('Track your progress towards unlocking cash payouts from the platform.') }}</p>
                </div>
                <div>
                    <span style="font-weight: 800; color: #10b981; font-size: 1rem; background: rgba(16,185,129,0.1); padding: 6px 14px; border-radius: 10px; display: inline-block;">
                        {{ __('Payout Amount') }}: {{ number_format($paymentSettings['payment_amount'], 2) }} TZS
                    </span>
                </div>
            </div>

            <div class="payout-milestone-grid">
                <!-- Target Checklist -->
                <!-- Like Requirement -->
                @php
                $likesCount = $user->likesReceived()->count();
                $likesRequired = $paymentSettings['payment_likes_required'];
                $likesMet = $likesCount >= $likesRequired;
                @endphp
                <div class="payout-card" style="border-color: {{ $likesMet ? '#bbf7d0' : '#e2e8f0' }};">
                    <div>
                        <span class="payout-card-title" style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">{{ __('Likes') }}</span>
                        <div class="payout-card-val" style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-top: 3px;">{{ $likesCount }} / {{ $likesRequired }}</div>
                    </div>
                    <div class="payout-card-icon" style="font-size: 1.4rem; color: {{ $likesMet ? '#10b981' : '#cbd5e1' }}">
                        <i class="bi {{ $likesMet ? 'bi-patch-check-fill' : 'bi-dash-circle' }}"></i>
                    </div>
                </div>

                <!-- Followers Requirement -->
                @php
                $followersCount = $user->followersReceived()->count();
                $followersRequired = $paymentSettings['payment_followers_required'];
                $followersMet = $followersCount >= $followersRequired;
                @endphp
                <div class="payout-card" style="border-color: {{ $followersMet ? '#bbf7d0' : '#e2e8f0' }};">
                    <div>
                        <span class="payout-card-title" style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">{{ __('Followers') }}</span>
                        <div class="payout-card-val" style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-top: 3px;">{{ $followersCount }} / {{ $followersRequired }}</div>
                    </div>
                    <div class="payout-card-icon" style="font-size: 1.4rem; color: {{ $followersMet ? '#10b981' : '#cbd5e1' }}">
                        <i class="bi {{ $followersMet ? 'bi-patch-check-fill' : 'bi-dash-circle' }}"></i>
                    </div>
                </div>

                <!-- Comments Requirement -->
                @php
                $commentsCount = $user->commentsReceived()->count();
                $commentsRequired = $paymentSettings['payment_comments_required'];
                $commentsMet = $commentsCount >= $commentsRequired;
                @endphp
                <div class="payout-card" style="border-color: {{ $commentsMet ? '#bbf7d0' : '#e2e8f0' }};">
                    <div>
                        <span class="payout-card-title" style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">{{ __('Comments') }}</span>
                        <div class="payout-card-val" style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-top: 3px;">{{ $commentsCount }} / {{ $commentsRequired }}</div>
                    </div>
                    <div class="payout-card-icon" style="font-size: 1.4rem; color: {{ $commentsMet ? '#10b981' : '#cbd5e1' }}">
                        <i class="bi {{ $commentsMet ? 'bi-patch-check-fill' : 'bi-dash-circle' }}"></i>
                    </div>
                </div>

                <!-- Views Requirement -->
                @php
                $viewsCount = $user->views_count;
                $viewsRequired = $paymentSettings['payment_views_required'];
                $viewsMet = $viewsCount >= $viewsRequired;
                @endphp
                <div class="payout-card" style="border-color: {{ $viewsMet ? '#bbf7d0' : '#e2e8f0' }};">
                    <div>
                        <span class="payout-card-title" style="font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Page Views</span>
                        <div class="payout-card-val" style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-top: 3px;">{{ $viewsCount }} / {{ $viewsRequired }}</div>
                    </div>
                    <div class="payout-card-icon" style="font-size: 1.4rem; color: {{ $viewsMet ? '#10b981' : '#cbd5e1' }}">
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
            <div style="background: #fffbeb; border: 1px solid #fef3c7; border-radius: 12px; padding: 18px; display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: rgba(245,158,11,0.12); color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div style="flex-grow: 1;">
                    <h4 style="margin: 0 0 4px 0; font-size: 0.92rem; font-weight: 700; color: #b45309;">Payout Request Submitted &amp; Pending</h4>
                    <p style="margin: 0; color: #64748b; font-size: 0.82rem;">You requested payment on {{ $paymentRequest->created_at->format('M d, Y') }}. Our administrative and customer care personnel are currently auditing your stats.</p>
                </div>
            </div>
            @elseif($paymentRequest->status === 'paid')
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 18px; display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: rgba(16,185,129,0.12); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
                <div style="flex-grow: 1;">
                    <h4 style="margin: 0 0 4px 0; font-size: 0.92rem; font-weight: 700; color: #15803d;">🎉 Payout Request Approved &amp; Paid!</h4>
                    <p style="margin: 0; color: #64748b; font-size: 0.82rem;">
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
            <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 18px; display: flex; flex-direction: column; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 44px; height: 44px; border-radius: 50%; background: rgba(239,68,68,0.12); color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <div style="flex-grow: 1;">
                        <h4 style="margin: 0 0 4px 0; font-size: 0.92rem; font-weight: 700; color: #991b1b;">Payout Request Rejected</h4>
                        <p style="margin: 0; color: #64748b; font-size: 0.82rem;">Reason: <strong style="color: #991b1b;">{{ $paymentRequest->admin_notes }}</strong></p>
                    </div>
                </div>
                @if($allMet)
                <form action="{{ route('dashboard.request-payment') }}" method="POST" style="margin: 0; border-top: 1px solid #fecaca; padding-top: 12px;">
                    @csrf
                    <button type="submit" style="padding: 9px 20px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(59,130,246,0.35); cursor: pointer; font-size: 0.85rem;">
                        Re-submit Payout Request
                    </button>
                </form>
                @endif
            </div>
            @endif
            @else
            <!-- No Request Exists Yet -->
            @if($allMet)
            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
                <div style="flex-grow: 1;">
                    <h4 style="margin: 0 0 4px 0; font-size: 0.92rem; font-weight: 700; color: #1e3a8a;">✨ You are Eligible for Payout!</h4>
                    <p style="margin: 0; color: #64748b; font-size: 0.82rem;">Congratulations, all performance milestones have been completed successfully. You can now request your payment.</p>
                </div>
                <form action="{{ route('dashboard.request-payment') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" style="padding: 10px 22px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(16,185,129,0.35); cursor: pointer; font-size: 0.88rem; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="bi bi-wallet2"></i> Submit Payout Request
                    </button>
                </form>
            </div>
            @else
            <div style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 12px; padding: 16px 18px; display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                <div style="width: 42px; height: 42px; border-radius: 50%; background: #e2e8f0; color: #64748b; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                    <i class="bi bi-lock-fill"></i>
                </div>
                <div style="flex-grow: 1;">
                    <h4 style="margin: 0 0 3px 0; font-size: 0.9rem; font-weight: 700; color: #334155;">Payout Eligibility Locked</h4>
                    <p style="margin: 0; color: #64748b; font-size: 0.82rem;">You need to complete all four milestone targets above to unlock the cash payout feature.</p>
                </div>
            </div>
            @endif
            @endif
        </div>
        <!-- Settings Form Panel removed to profile -->
        @endif
        @endif
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
        const socialConfigs = [{
                id: 'social_instagram',
                statusId: 'social_instagram_status',
                name: 'Instagram',
                allowed: ['instagram.com', 'www.instagram.com', 'instagr.am', 'www.instagr.am', 'm.instagram.com']
            },
            {
                id: 'social_facebook',
                statusId: 'social_facebook_status',
                name: 'Facebook',
                allowed: ['facebook.com', 'www.facebook.com', 'fb.com', 'www.fb.com', 'm.facebook.com', 'web.facebook.com', 'fb.watch']
            },
            {
                id: 'social_tiktok',
                statusId: 'social_tiktok_status',
                name: 'TikTok',
                allowed: ['tiktok.com', 'www.tiktok.com', 'vm.tiktok.com', 'm.tiktok.com', 'vt.tiktok.com']
            },
            {
                id: 'social_youtube',
                statusId: 'social_youtube_status',
                name: 'YouTube',
                allowed: ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'music.youtube.com', 'youtu.be', 'www.youtu.be']
            }
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

<!-- Publish Account Payment Modal -->
@php
$activePaymentMethods = \App\Models\PaymentMethod::active()->get();
$publishingFee = floatval(\App\Models\SystemSetting::get('payment_amount', 10000.00));
@endphp
<div id="publish-payment-modal" class="admin-modal" style="display: {{ session('open_payment_modal') ? 'flex' : 'none' }};">
    <div class="admin-modal-content" style="border-radius: 20px; max-width: 620px; width: 92%; margin: auto; padding: 25px; box-shadow: 0 25px 50px rgba(0,0,0,0.25);">
        <div class="admin-modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 14px; margin-bottom: 20px;">
            <h3 style="margin: 0; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px; font-size: 1.15rem;">
                <i class="bi bi-wallet2" style="color: #6366f1; font-size: 1.3rem;"></i> {{ __('Account Publishing Payment Required') }}
            </h3>
            <button type="button" class="admin-modal-close" onclick="$('#publish-payment-modal').fadeOut(200);" style="background: none; border: none; font-size: 26px; cursor: pointer; color: #64748b;">&times;</button>
        </div>

        <!-- Payment Notice Banner -->
        <div style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border: 1px solid #bfdbfe; border-radius: 14px; padding: 18px; margin-bottom: 20px; display: flex; align-items: center; gap: 14px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #2563eb; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div>
                <h4 style="margin: 0 0 4px 0; font-size: 1.05rem; font-weight: 800; color: #1e3a8a;">
                    {{ __('You have to pay:') }} <span style="color: #2563eb;">TZS {{ number_format($publishingFee) }}/-</span>
                </h4>
                <p style="margin: 0; font-size: 0.85rem; color: #1e40af; line-height: 1.4;">
                    {{ __('Please send TZS') }} {{ number_format($publishingFee) }}/- {{ __('to any of the official ChapConnect accounts below to complete payment and make your profile visible to the public.') }}
                </p>
            </div>
        </div>

        <!-- Configured Payment Channels Grid -->
        <h4 style="font-size: 0.88rem; text-transform: uppercase; font-weight: 800; color: #475569; letter-spacing: 0.5px; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
            <i class="bi bi-bank2" style="color: #6366f1;"></i> {{ __('Select Payment Account / Channel:') }}
        </h4>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 12px; margin-bottom: 22px; max-height: 280px; overflow-y: auto; padding-right: 4px;">
            @forelse($activePaymentMethods as $pm)
            <div style="background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 14px; transition: border-color 0.2s ease; position: relative;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                    <img src="{{ $pm->logo_url }}" alt="{{ $pm->company }}" style="width: 32px; height: 32px; object-fit: contain; border-radius: 6px; border: 1px solid #e2e8f0; padding: 2px; background: #fff;" onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.png') }}';">
                    <div>
                        <strong style="font-size: 0.92rem; color: #0f172a; display: block;">{{ $pm->company }}</strong>
                        <span style="font-size: 0.74rem; color: #64748b; font-weight: 600;">{{ $pm->account_name }}</span>
                    </div>
                </div>
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 10px; font-family: monospace; font-size: 0.95rem; font-weight: 800; color: #2563eb; letter-spacing: 0.5px; word-break: break-all;">
                    {{ $pm->account_number }}
                </div>
                @if($pm->instructions)
                <p style="margin: 6px 0 0 0; font-size: 0.75rem; color: #64748b; line-height: 1.3;">
                    {{ $pm->instructions }}
                </p>
                @endif
            </div>
            @empty
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; text-align: center; color: #64748b; font-size: 0.88rem; grid-column: 1/-1;">
                {{ __('No payment channels configured yet. Please contact Customer Care for payment details.') }}
            </div>
            @endforelse
        </div>

        <!-- Submit Payment Reference Confirmation Form -->
        <form action="{{ route('dashboard.submit-payment-confirmation') }}" method="POST" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px;">
            @csrf
            <h4 style="margin: 0 0 12px 0; font-size: 0.88rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                <i class="bi bi-check2-circle" style="color: #10b981; font-size: 1.1rem;"></i> {{ __('Submit Payment Reference for Instant Confirmation') }}
            </h4>

            <div class="form-group" style="margin-bottom: 12px;">
                <label style="display: block; font-weight: 700; color: #334155; font-size: 0.82rem; margin-bottom: 4px;">{{ __('Transaction Reference No. / Receipt ID') }} *</label>
                <input type="text" name="transaction_reference" placeholder="e.g. CRDB982019382 or 554433-89102" required style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 700; color: #334155; font-size: 0.82rem; margin-bottom: 4px;">{{ __('Sender Phone / Payment Notes (Optional)') }}</label>
                <input type="text" name="notes" placeholder="e.g. Sent from 0712345678 - John Doe" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="$('#publish-payment-modal').fadeOut(200);" style="padding: 10px 18px; border-radius: 8px; background: #e2e8f0; border: none; color: #475569; font-weight: 600; font-size: 0.85rem; cursor: pointer;">{{ __('Close') }}</button>
                <button type="submit" style="padding: 10px 22px; border-radius: 8px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; color: #ffffff; font-weight: 800; font-size: 0.88rem; cursor: pointer; box-shadow: 0 4px 14px rgba(16,185,129,0.35);">
                    {{ __('Submit Confirmation') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Help Desk Quick Approval Support Modal -->
<div id="helpdesk-contacts-modal" class="admin-modal" style="display: none;">
    <div class="admin-modal-content" style="border-radius: 20px; max-width: 520px; width: 92%; margin: auto; padding: 25px; box-shadow: 0 25px 50px rgba(0,0,0,0.25);">
        <div class="admin-modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 14px; margin-bottom: 20px;">
            <h3 style="margin: 0; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px; font-size: 1.15rem;">
                <i class="bi bi-headset" style="color: #2563eb; font-size: 1.3rem;"></i> {{ __('Help Desk & Quick Payment Approval Support') }}
            </h3>
            <button type="button" class="admin-modal-close" onclick="$('#helpdesk-contacts-modal').fadeOut(200);" style="background: none; border: none; font-size: 26px; cursor: pointer; color: #64748b;">&times;</button>
        </div>

        <p style="color: #475569; font-size: 0.88rem; margin-top: 0; margin-bottom: 18px; line-height: 1.5;">
            {{ __('If you have submitted your payment reference and require instant verification and approval, please contact our Help Desk team directly via any of the channels below:') }}
        </p>

        <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 22px;">
            <!-- WhatsApp Channel -->
            <a href="https://wa.me/255710383352?text={{ urlencode('Hello ChapConnect Helpdesk, I have submitted my payment reference for profile publishing verification.') }}" target="_blank" style="display: flex; align-items: center; gap: 14px; padding: 14px 18px; border-radius: 14px; background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); color: #ffffff; text-decoration: none; font-weight: 700; font-size: 0.95rem; box-shadow: 0 4px 15px rgba(22,163,74,0.3);">
                <i class="bi bi-whatsapp" style="font-size: 1.5rem;"></i>
                <div>
                    <div style="font-size: 0.78rem; opacity: 0.9; text-transform: uppercase;">{{ __('WhatsApp Help Desk (Instant Response)') }}</div>
                    <div>+255 710 383 352</div>
                </div>
                <i class="bi bi-arrow-right-short" style="font-size: 1.4rem; margin-left: auto;"></i>
            </a>

            <!-- Phone Call Channel -->
            <a href="tel:+255710383352" style="display: flex; align-items: center; gap: 14px; padding: 14px 18px; border-radius: 14px; background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%); color: #ffffff; text-decoration: none; font-weight: 700; font-size: 0.95rem; box-shadow: 0 4px 15px rgba(2,132,199,0.3);">
                <i class="bi bi-telephone-fill" style="font-size: 1.4rem;"></i>
                <div>
                    <div style="font-size: 0.78rem; opacity: 0.9; text-transform: uppercase;">{{ __('Direct Phone Line') }}</div>
                    <div>+255 710 383 352</div>
                </div>
                <i class="bi bi-arrow-right-short" style="font-size: 1.4rem; margin-left: auto;"></i>
            </a>

            <!-- Email Support Channel -->
            <a href="mailto:support@chapconnect.com?subject=Payment%20Verification%20Request" style="display: flex; align-items: center; gap: 14px; padding: 14px 18px; border-radius: 14px; background: #ffffff; border: 1.5px solid #cbd5e1; color: #1e293b; text-decoration: none; font-weight: 700; font-size: 0.92rem;">
                <i class="bi bi-envelope-fill" style="font-size: 1.4rem; color: #dc2626;"></i>
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase;">{{ __('Support Email Address') }}</div>
                    <div>support@chapconnect.com</div>
                </div>
            </a>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e2e8f0; padding-top: 15px;">
            <button type="button" onclick="$('#helpdesk-contacts-modal').fadeOut(200); $('#user-support-modal').fadeIn(200);" style="padding: 10px 16px; border-radius: 10px; background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.25); color: #4338ca; font-weight: 700; font-size: 0.84rem; cursor: pointer;">
                💬 {{ __('Open Support Ticket') }}
            </button>
            <button type="button" onclick="$('#helpdesk-contacts-modal').fadeOut(200);" style="padding: 10px 20px; border-radius: 10px; background: #e2e8f0; border: none; color: #475569; font-weight: 700; font-size: 0.85rem; cursor: pointer;">
                {{ __('Close') }}
            </button>
        </div>
    </div>
</div>
@endsection