<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ChapConnect - Connecting Talents')</title>
    <!-- Favicon / Title Icon -->
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- DataTables Online CDN CSS + Responsive Extension -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" />
    <!-- PWA Web App Manifest & Mobile App Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#0f172a">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="ChapConnect">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/Style.css') }}?v={{ filemtime(public_path('css/Style.css')) }}">
    @yield('styles')
</head>

<body class="{{ (Request::is('admin*') || Request::is('customer-care*') || Request::is('dashboard*')) ? 'admin-body' : '' }}">

    <!-- First-Time Visitor Typewriter Splash Loader Overlay -->
    @php
    $welcomeText = \App\Models\SystemSetting::get('welcome_text', 'Karibu sana ChapConnect...');
    $welcomeSpeed = (int) \App\Models\SystemSetting::get('welcome_typing_speed', 55);
    $welcomeDelay = (int) \App\Models\SystemSetting::get('welcome_delay', 300);
    $welcomeSound = \App\Models\SystemSetting::get('welcome_sound', '/sounds/welcome_default.wav');
    $welcomeSoundUrl = str_starts_with($welcomeSound, 'http') ? $welcomeSound : asset(ltrim($welcomeSound, '/'));
    @endphp
    <div id="firstTimeLoaderOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%); z-index: 999999; flex-direction: column; justify-content: center; align-items: center; color: #ffffff; font-family: system-ui, -apple-system, sans-serif; opacity: 1; transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer;">
        <!-- Glowing Brand Logo Icon -->
        <div style="position: relative; margin-bottom: 24px;">
            <div style="position: absolute; top: -10px; left: -10px; right: -10px; bottom: -10px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #38bdf8); filter: blur(20px); opacity: 0.6; animation: loaderPulse 2s infinite ease-in-out;"></div>
            <img src="{{ asset('logo.png') }}" alt="ChapConnect" style="position: relative; width: 85px; height: 85px; border-radius: 50%; object-fit: cover; border: 3px solid rgba(255,255,255,0.8); box-shadow: 0 10px 30px rgba(0,0,0,0.5);" onerror="this.src='https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=100&auto=format&fit=crop'">
        </div>

        <!-- Typewriter Animated Text -->
        <div style="font-size: 1.6rem; font-weight: 800; letter-spacing: 0.5px; color: #ffffff; text-shadow: 0 2px 10px rgba(0,0,0,0.5); min-height: 40px; display: flex; align-items: center; justify-content: center; padding: 0 20px; text-align: center;">
            <span id="typewriterText" style="background: linear-gradient(135deg, #ffffff 0%, #cbd5e1 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></span>
            <span id="typewriterCursor" style="display: inline-block; width: 3px; height: 26px; background: #6366f1; margin-left: 4px; animation: blinkCursor 0.7s infinite;"></span>
        </div>

        <!-- Animated Loading Progress Line -->
        <div style="width: 220px; height: 4px; background: rgba(255,255,255,0.12); border-radius: 10px; margin-top: 25px; overflow: hidden; position: relative;">
            <div id="loaderProgressBar" style="width: 0%; height: 100%; background: linear-gradient(90deg, #6366f1, #38bdf8); border-radius: 10px; transition: width 0.1s linear;"></div>
        </div>

        <!-- Audio Welcome Interactive Pill Button -->
        <button type="button" id="welcomeAudioTriggerBtn" onclick="playWelcomeSound(true)" style="margin-top: 20px; background: linear-gradient(135deg, #6366f1 0%, #38bdf8 100%); border: none; color: #ffffff; padding: 10px 22px; border-radius: 25px; font-size: 0.9rem; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 6px 20px rgba(99,102,241,0.5); animation: pulseBtn 1.8s infinite ease-in-out;">
            <i class="bi bi-volume-up-fill" style="font-size: 1.1rem; color: #ffffff;"></i> <span id="welcomeAudioBtnText">Welcome Audio Playing 🔊</span>
        </button>
        <audio id="welcomeAudioElement" src="{{ $welcomeSoundUrl }}" preload="auto" playsinline style="display: none;"></audio>
    </div>

    <style>
        @keyframes blinkCursor {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        @keyframes loaderPulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.15);
                opacity: 0.85;
            }
        }

        @keyframes pulseBtn {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
            }

            50% {
                transform: scale(1.06);
                box-shadow: 0 10px 25px rgba(56, 189, 248, 0.7);
            }
        }
    </style>
    <!-- Off-Canvas Drawer Backdrop -->
    <div class="nav-backdrop" id="drawerBackdrop" onclick="toggleMobileNav()"></div>

    <nav class="nav">
        <div class="logo">
            <a href="{{ route('home') }}"><img src="{{ asset(\App\Models\SystemSetting::get('site_logo', 'logo.png')) }}" alt="{{ \App\Models\SystemSetting::get('site_title', 'ChapConnect') }} Logo" onerror="this.src='https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=100&auto=format&fit=crop'"></a>
            <div class="tname">
                <a href="{{ route('home') }}" style="text-decoration: none;">
                    <h1>{{ \App\Models\SystemSetting::get('site_title', 'ChapConnect') }}</h1>
                </a>
                <div style="display: flex; gap: 4px; margin-top: 3px;">
                    <a href="#" onclick="openGlobalSupportModal(); return false;" style="font-weight: 600; color: #ffffff; text-decoration: none; padding: 2px 7px; background: linear-gradient(135deg, #25d366 0%, #128c7e 100%); border-radius: 12px; font-size: 10px; letter-spacing: 0.2px; box-shadow: 0 2px 6px rgba(99, 102, 241, 0.3); border: 1px solid rgba(255, 255, 255, 0.3); transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 3px;"><i
                            class="bi bi-headset" style="font-size: 11px; color: #ffffff;"></i>{{ __('Help desk & Support') }}</a>
                </div>
            </div>
        </div>
        <!-- Toggle Button for mobile/sidebar -->
        <button class="nav-toggle" id="navToggleBtn" onclick="toggleMobileNav()" aria-label="Toggle navigation">
            <i class="bi bi-list"></i>
        </button>

        <!-- Right Side Desktop Actions (Buttons & Search Bar) -->
        <div class="nav-actions-wrapper" style="margin-left: auto; display: flex; align-items: center; gap: 8px;">
            <div class="nav-auth" style="display: flex; align-items: center; gap: 8px;">
                <!-- Desktop Language Switcher -->
                <div class="lang-switcher" style="position: relative; display: inline-block;">
                    <button type="button" id="langSwitchBtn" onclick="$('#langDropdown').fadeToggle(150);" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: #fff; border-radius: 20px; font-weight: 700; padding: 6px 12px; font-size: 0.82rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s ease;">
                        <span style="font-size: 0.95rem;">{{ app()->getLocale() === 'sw' ? '🇹🇿' : '🇬🇧' }}</span>
                        <span>{{ app()->getLocale() === 'sw' ? 'SW' : 'EN' }}</span>
                        <i class="bi bi-chevron-down" style="font-size: 0.7rem; opacity: 0.8;"></i>
                    </button>
                    <div id="langDropdown" style="display: none; position: absolute; right: 0; top: 44px; width: 145px; background: #ffffff; border-radius: 12px; box-shadow: 0 12px 30px rgba(0,0,0,0.18); border: 1px solid #e2e8f0; z-index: 10000; overflow: hidden; padding: 5px;">
                        <a href="{{ route('lang.switch', 'sw') }}" style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; font-size: 0.82rem; font-weight: 700; color: {{ app()->getLocale() === 'sw' ? '#6366f1' : '#1e293b' }}; background: {{ app()->getLocale() === 'sw' ? '#f1f5f9' : 'transparent' }}; border-radius: 8px; text-decoration: none; transition: background 0.2s;">
                            <span>🇹🇿</span> Kiswahili
                            @if(app()->getLocale() === 'sw') <i class="bi bi-check-lg" style="margin-left: auto; color: #6366f1; font-weight: 900;"></i> @endif
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}" style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; font-size: 0.82rem; font-weight: 700; color: {{ app()->getLocale() === 'en' ? '#6366f1' : '#1e293b' }}; background: {{ app()->getLocale() === 'en' ? '#f1f5f9' : 'transparent' }}; border-radius: 8px; text-decoration: none; transition: background 0.2s;">
                            <span>🇬🇧</span> English
                            @if(app()->getLocale() === 'en') <i class="bi bi-check-lg" style="margin-left: auto; color: #6366f1; font-weight: 900;"></i> @endif
                        </a>
                    </div>
                </div>

                @auth
                <span style="font-weight: 700; font-size: 0.84rem; color: #fff; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2); padding: 6px 14px; border-radius: 20px; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="bi bi-person-circle" style="color: #38bdf8;"></i> {{ auth()->user()->name }}
                </span>

                <!-- In-App Notification Bell -->
                <div class="notification-wrapper" style="position: relative; display: inline-block;">
                    <button type="button" id="notifBellBtn" onclick="$('#notifDropdown').fadeToggle(150);" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: #fff; border-radius: 50%; width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; position: relative; font-size: 1.1rem; transition: all 0.2s ease;">
                        <i class="bi bi-bell-fill"></i>
                        <span id="notifBadge" style="display: none; position: absolute; top: -4px; right: -4px; background: #ef4444; color: #fff; font-size: 0.68rem; font-weight: 800; border-radius: 10px; padding: 2px 6px; border: 2px solid #0f172a; line-height: 1;">0</span>
                    </button>

                    <!-- Notification Dropdown Menu -->
                    <div id="notifDropdown" style="display: none; position: absolute; right: 0; top: 48px; width: 330px; background: #ffffff; border-radius: 16px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); border: 1px solid #e2e8f0; z-index: 9999; overflow: hidden;">
                        <div style="padding: 14px 16px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                            <div style="font-weight: 800; font-size: 0.9rem; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                                <i class="bi bi-bell-fill" style="color: #6366f1;"></i> Notifications
                            </div>
                            <form action="{{ route('notifications.mark-all-read') }}" method="POST" style="margin:0;">
                                @csrf
                                <button type="submit" style="background: none; border: none; font-size: 0.76rem; color: #6366f1; font-weight: 700; cursor: pointer;">Mark all read</button>
                            </form>
                        </div>
                        <div id="notifList" style="max-height: 300px; overflow-y: auto;">
                            <div style="padding: 20px; text-align: center; color: #94a3b8; font-size: 0.85rem;">Loading notifications...</div>
                        </div>
                    </div>
                </div>

                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-btn nav-btn-logout"><i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                @else
                <a href="{{ route('home') }}" class="nav-btn nav-btn-home"><i class="bi bi-house-door-fill" style="color: #6366f1;"></i> {{ __('Home') }}</a>
                <a href="{{ route('login') }}" class="nav-btn nav-btn-login"><i class="bi bi-box-arrow-in-right"></i> {{ __('Login') }}</a>
                <a href="{{ route('register') }}" class="nav-btn nav-btn-register"><i class="bi bi-person-plus-fill"></i> {{ __('Register') }}</a>
                @endauth
            </div>
            @yield('search_bar')
        </div>

        <!-- Left Side Navigation Off-Canvas Drawer (Picture 1 style) -->
        <div class="icon" id="navIconMenu">
            <!-- Drawer Header -->
            <div class="drawer-header">
                <div class="drawer-brand">
                    <div class="drawer-logo-box">
                        <img src="{{ asset(\App\Models\SystemSetting::get('site_logo', 'logo.png')) }}" alt="Logo" onerror="this.src='https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=100&auto=format&fit=crop'">
                    </div>
                    <div class="drawer-title-box">
                        <h2>{{ \App\Models\SystemSetting::get('site_title', 'ChapConnect') }}</h2>
                    </div>
                </div>
                <button type="button" class="drawer-close-btn" onclick="toggleMobileNav()" aria-label="Close menu">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <!-- Mobile Language Switcher Row -->
            <div class="drawer-lang-row">
                <span class="drawer-lang-label">
                    <i class="bi bi-translate" style="color: #38bdf8; font-size: 1.05rem;"></i> {{ __('Language') }}:
                </span>
                <div class="drawer-lang-btns">
                    <a href="{{ route('lang.switch', 'sw') }}" class="drawer-lang-btn {{ app()->getLocale() === 'sw' ? 'active' : 'inactive' }}">🇹🇿 SW</a>
                    <a href="{{ route('lang.switch', 'en') }}" class="drawer-lang-btn {{ app()->getLocale() === 'en' ? 'active' : 'inactive' }}">🇬🇧 EN</a>
                </div>
            </div>

            <!-- Drawer Body -->
            <div class="drawer-body">
                <div class="nav-mobile-list">
                    @auth
                    @if(in_array(auth()->user()->role, ['admin', 'customer_care']))
                    @if(Request::is('admin*'))
                    <div class="drawer-section-label">MAIN CONTROL</div>
                    <a href="#dashboard" class="tab-link nav-mobile-link active" data-tab="dashboard">
                        <i class="bi bi-speedometer2"></i> {{ __('Dashboard Overview') }}
                        <i class="bi bi-chevron-right chevron-arrow"></i>
                    </a>
                    <a href="#talents" class="tab-link nav-mobile-link" data-tab="talents">
                        <i class="bi bi-people-fill"></i> Registered Talents
                        <i class="bi bi-chevron-right chevron-arrow"></i>
                    </a>
                    <a href="#categories" class="tab-link nav-mobile-link" data-tab="categories">
                        <i class="bi bi-tags-fill"></i> Manage Categories
                        <i class="bi bi-chevron-right chevron-arrow"></i>
                    </a>
                    <a href="#packages" class="tab-link nav-mobile-link" data-tab="packages">
                        <i class="bi bi-box-seam-fill"></i> Membership Packages
                        <i class="bi bi-chevron-right chevron-arrow"></i>
                    </a>
                    <a href="#invoices" class="tab-link nav-mobile-link" data-tab="invoices">
                        <i class="bi bi-receipt-cutoff"></i> Invoices & Billing
                        <i class="bi bi-chevron-right chevron-arrow"></i>
                    </a>
                    <a href="#payments" class="tab-link nav-mobile-link" data-tab="payments">
                        <i class="bi bi-wallet2"></i> Talent Payments
                        <i class="bi bi-chevron-right chevron-arrow"></i>
                    </a>

                    <div class="drawer-section-label">MANAGEMENT & SYSTEM</div>
                    <a href="#settings" class="tab-link nav-mobile-link" data-tab="settings">
                        <i class="bi bi-person-gear"></i> User Profile
                        <i class="bi bi-chevron-right chevron-arrow"></i>
                    </a>
                    <a href="#staff" class="tab-link nav-mobile-link" data-tab="staff">
                        <i class="bi bi-person-plus-fill"></i> Registered Staff
                        <i class="bi bi-chevron-right chevron-arrow"></i>
                    </a>
                    <a href="#analytics" class="tab-link nav-mobile-link" data-tab="analytics">
                        <i class="bi bi-graph-up-arrow"></i> Visitor Analytics
                        <i class="bi bi-chevron-right chevron-arrow"></i>
                    </a>
                    <a href="#activity-logs" class="tab-link nav-mobile-link" data-tab="activity-logs">
                        <i class="bi bi-journal-text"></i> User Activity Logs
                        <i class="bi bi-chevron-right chevron-arrow"></i>
                    </a>

                    <div class="drawer-section-label">SUPPORT & DIRECTORY</div>
                    <a href="{{ route('customer-care.dashboard') }}#tickets" class="nav-mobile-link">
                        <i class="bi bi-life-preserver"></i> Support Issues & Tickets
                    </a>
                    <a href="{{ route('customer-care.dashboard') }}#blocked" class="nav-mobile-link">
                        <i class="bi bi-shield-slash-fill"></i> Blocked Accounts
                    </a>
                    <a href="{{ route('customer-care.dashboard') }}#requests" class="nav-mobile-link">
                        <i class="bi bi-person-check-fill"></i> Contact Requests
                    </a>
                    <a href="{{ route('customer-care.dashboard') }}#payments" class="nav-mobile-link">
                        <i class="bi bi-wallet2"></i> Talent Payment Requests
                    </a>
                    <a href="{{ route('home') }}" class="nav-mobile-link">
                        <i class="bi bi-house-door-fill"></i> {{ __('Home Directory') }}
                    </a>
                    @elseif(Request::is('customer-care*'))
                    <div class="drawer-section-label">OPERATIONS & SUPPORT</div>
                    <a href="{{ route('customer-care.dashboard') }}#tickets" class="nav-mobile-link cc-tab-link active" data-cctab="tickets">
                        <i class="bi bi-life-preserver"></i> Support Issues & Tickets
                    </a>
                    <a href="{{ route('customer-care.dashboard') }}#blocked" class="nav-mobile-link cc-tab-link" data-cctab="blocked">
                        <i class="bi bi-shield-slash-fill"></i> Blocked Accounts & Login
                    </a>
                    <a href="{{ route('customer-care.dashboard') }}#talents" class="nav-mobile-link cc-tab-link" data-cctab="talents">
                        <i class="bi bi-people-fill"></i> Talents Directory & Q&A
                    </a>
                    <a href="{{ route('customer-care.dashboard') }}#requests" class="nav-mobile-link cc-tab-link" data-cctab="requests">
                        <i class="bi bi-person-check-fill"></i> Guest Contact Requests
                    </a>
                    <a href="{{ route('customer-care.dashboard') }}#payments" class="nav-mobile-link cc-tab-link" data-cctab="payments">
                        <i class="bi bi-wallet2"></i> Talent Payment Requests
                    </a>

                    <div class="drawer-section-label">NAVIGATION</div>
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="nav-mobile-link">
                        <i class="bi bi-speedometer2"></i> Super Admin Panel
                    </a>
                    @endif
                    <a href="{{ route('home') }}" class="nav-mobile-link">
                        <i class="bi bi-house-door-fill"></i> {{ __('Home Directory') }}
                    </a>
                    @else
                    <div class="drawer-section-label">MAIN CONTROL</div>
                    <a href="{{ route('home') }}" class="nav-mobile-link">
                        <i class="bi bi-house-door-fill"></i> {{ __('Home') }}
                    </a>
                    <a href="{{ route('customer-care.dashboard') }}" class="nav-mobile-link">
                        <i class="bi bi-headset"></i> Support Portal
                    </a>
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="nav-mobile-link">
                        <i class="bi bi-speedometer2"></i> Admin Dashboard
                    </a>
                    @endif
                    @endif
                    @else
                    <!-- REGULAR USER / TALENT SIDEBAR LINKS -->
                    <div class="drawer-section-label">MAIN NAVIGATION</div>
                    <a href="{{ route('home') }}" class="nav-mobile-link {{ Request::routeIs('home') ? 'active' : '' }}">
                        <i class="bi bi-house-door-fill"></i> {{ __('Home') }}
                    </a>
                    <a href="{{ route('dashboard') }}" class="nav-mobile-link {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> {{ __('Dashboard') }}
                    </a>

                    <div class="drawer-section-label">PORTFOLIO CONTENT</div>
                    <a href="{{ route('dashboard.photos') }}" class="nav-mobile-link {{ Request::routeIs('dashboard.photos') ? 'active' : '' }}">
                        <i class="bi bi-camera-fill"></i> {{ __('Photos') }}
                    </a>
                    <a href="{{ route('dashboard.videos') }}" class="nav-mobile-link {{ Request::routeIs('dashboard.videos') ? 'active' : '' }}">
                        <i class="bi bi-camera-video-fill"></i> {{ __('Videos') }}
                    </a>
                    <a href="{{ route('dashboard.news') }}" class="nav-mobile-link {{ Request::routeIs('dashboard.news') ? 'active' : '' }}">
                        <i class="bi bi-newspaper"></i> Manage News
                    </a>
                    <a href="{{ route('dashboard.comments') }}" class="nav-mobile-link {{ Request::routeIs('dashboard.comments') ? 'active' : '' }}">
                        <i class="bi bi-chat-left-text-fill"></i> {{ __('Comments') }}
                    </a>
                    @endif

                    <div class="drawer-section-label">ACCOUNT</div>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-mobile-link" style="color: #fca5a5 !important;">
                        <i class="bi bi-box-arrow-right" style="color: #ef4444;"></i> {{ __('Logout') }}
                    </a>
                    @else
                    <!-- GUEST USER NAVIGATION -->
                    <div class="drawer-section-label">MAIN MENU</div>
                    <a href="{{ route('home') }}" class="nav-mobile-link {{ Request::routeIs('home') ? 'active' : '' }}">
                        <i class="bi bi-house-door-fill"></i> {{ __('Home') }}
                    </a>

                    <div class="drawer-section-label">ACCOUNT ACCESS</div>
                    <a href="{{ route('login') }}" class="nav-mobile-link {{ Request::routeIs('login') ? 'active' : '' }}">
                        <i class="bi bi-box-arrow-in-right"></i> {{ __('Login') }}
                    </a>
                    <a href="{{ route('register') }}" class="nav-mobile-link {{ Request::routeIs('register') ? 'active' : '' }}">
                        <i class="bi bi-person-plus-fill"></i> {{ __('Register') }}
                    </a>
                    @endauth

                    <div class="drawer-section-label">CONTACT US</div>
                    <a href="tel:0710383352" class="nav-mobile-link">
                        <i class="bi bi-telephone-fill" style="color: #38bdf8;"></i> Call: 0710383352
                    </a>
                    <a href="https://wa.me/255710383352" target="_blank" class="nav-mobile-link">
                        <i class="bi bi-whatsapp" style="color: #34d399;"></i> WhatsApp: 0710383352
                    </a>
                </div>
            </div>

            <!-- Drawer Footer (User Profile & Quick Actions as in Picture 1) -->
            <div class="drawer-footer">
                @auth
                <div class="drawer-user-info">
                    <div class="drawer-avatar">
                        @if(auth()->user()->profile_image)
                        <img src="{{ asset(auth()->user()->profile_image) }}" alt="{{ auth()->user()->name }}">
                        @else
                        <i class="bi bi-person-circle"></i>
                        @endif
                    </div>
                    <div class="drawer-user-text">
                        <span class="drawer-user-name">{{ auth()->user()->name }}</span>
                        <span class="drawer-user-email">{{ auth()->user()->email }}</span>
                    </div>
                </div>
                <div class="drawer-footer-actions">
                    <a href="{{ route('dashboard') }}" class="drawer-action-btn" title="Account Settings">
                        <i class="bi bi-gear-fill"></i>
                    </a>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="drawer-action-btn drawer-action-logout" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </div>
                @else
                <div class="drawer-user-info">
                    <div class="drawer-avatar">
                        <i class="bi bi-person"></i>
                    </div>
                    <div class="drawer-user-text">
                        <span class="drawer-user-name">Welcome Guest</span>
                        <span class="drawer-user-email">ChapConnect Portal</span>
                    </div>
                </div>
                <div class="drawer-footer-actions">
                    <a href="{{ route('login') }}" class="drawer-action-btn" title="Login">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </a>
                </div>
                @endauth
            </div>
        </div>
    </nav>
    </nav>

    <!-- Global Floating Notification Alerts Container -->
    <div class="toast-alert-container">
        @if(session('success'))
        <div class="toast-alert toast-success auto-toast-item">
            <div class="toast-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="toast-body">
                <strong class="toast-title">Success</strong>
                <p class="toast-message">{{ session('success') }}</p>
            </div>
            <button type="button" class="toast-close" onclick="dismissToast(this.parentElement)">&times;</button>
            <div class="toast-progress toast-progress-success"></div>
        </div>
        @endif

        @if(session('error'))
        <div class="toast-alert toast-error auto-toast-item">
            <div class="toast-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="toast-body">
                <strong class="toast-title">Action Alert</strong>
                <p class="toast-message">{{ session('error') }}</p>
            </div>
            <button type="button" class="toast-close" onclick="dismissToast(this.parentElement)">&times;</button>
            <div class="toast-progress toast-progress-error"></div>
        </div>
        @endif

        @if($errors->any())
        <div class="toast-alert toast-error auto-toast-item">
            <div class="toast-icon">
                <i class="bi bi-exclamation-octagon-fill"></i>
            </div>
            <div class="toast-body">
                <strong class="toast-title">Please check form errors:</strong>
                <ul class="toast-error-list">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="toast-close" onclick="dismissToast(this.parentElement)">&times;</button>
            <div class="toast-progress toast-progress-error"></div>
        </div>
        @endif
    </div>

    @auth
    @if(Request::is('admin*') || Request::is('customer-care*') || Request::is('dashboard*'))
    <div class="admin-layout-container">
        <!-- Sidebar Navigation -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-sidebar-header">
                <div class="sidebar-user-avatar">
                    @if(auth()->user()->profile_image)
                    <img src="{{ asset(auth()->user()->profile_image) }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; object-fit: cover; object-position: top center; border-radius: 10px;">
                    @else
                    <i class="bi bi-person-circle"></i>
                    @endif
                </div>
                <div class="sidebar-user-info">
                    <span class="sidebar-user-name">{{ auth()->user()->name }}</span>
                    <span class="sidebar-user-role">
                        @if(auth()->user()->role === 'admin')
                        Super Administrator
                        @elseif(auth()->user()->role === 'customer_care')
                        Customer Care
                        @else
                        {{ auth()->user()->category_label ?? 'Talent Account' }}
                        @endif
                    </span>
                </div>
            </div>

            <div class="admin-sidebar-nav">
                @if(auth()->user()->role === 'customer_care')
                <div class="sidebar-group-label">OPERATIONS & SUPPORT</div>
                <a href="{{ route('customer-care.dashboard') }}#tickets" class="sidebar-link cc-tab-link {{ Request::is('customer-care*') ? 'active' : '' }}" data-cctab="tickets">
                    <i class="bi bi-life-preserver"></i> <span>Support Issues & Tickets Roster</span>
                </a>
                <a href="{{ route('customer-care.dashboard') }}#blocked" class="sidebar-link cc-tab-link" data-cctab="blocked">
                    <i class="bi bi-shield-slash-fill" style="color:#dc2626;"></i> <span>Blocked Accounts & Login Control</span>
                </a>
                <a href="{{ route('admin.moderation') }}" class="sidebar-link {{ Request::is('admin/moderation*') ? 'active' : '' }}">
                    <i class="bi bi-shield-exclamation" style="color:#f59e0b;"></i> <span>Content Moderation & NSFW</span>
                    @php
                    $flaggedCount = \App\Models\Media::where('moderation_status', 'flagged')->count();
                    @endphp
                    @if($flaggedCount > 0)
                    <span style="margin-left: auto; background: #ef4444; color: #fff; font-size: 0.72rem; font-weight: 800; padding: 2px 7px; border-radius: 10px;">{{ $flaggedCount }}</span>
                    @endif
                </a>
                <a href="{{ route('customer-care.dashboard') }}#talents" class="sidebar-link cc-tab-link" data-cctab="talents">
                    <i class="bi bi-people-fill" style="color:var(--primary);"></i> <span>Talents Directory & Q&A</span>
                </a>
                <a href="{{ route('customer-care.dashboard') }}#requests" class="sidebar-link cc-tab-link" data-cctab="requests">
                    <i class="bi bi-person-check-fill" style="color:#6366f1;"></i> <span>Guest Contact Requests</span>
                </a>
                <a href="{{ route('customer-care.dashboard') }}#payments" class="sidebar-link cc-tab-link" data-cctab="payments">
                    <i class="bi bi-wallet2"></i> <span>Talent Payment Requests</span>
                </a>

                <div class="sidebar-group-label">SETTINGS & PROFILES</div>
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i> <span>My Account Profile</span>
                </a>

                <div class="sidebar-group-label">DIRECTORY</div>
                <a href="{{ route('home') }}" target="_blank" class="sidebar-link">
                    <i class="bi bi-grid-fill"></i> <span>Public Directory</span>
                    <i class="bi bi-box-arrow-up-right" style="font-size: 0.75rem; margin-left: auto; color: #94a3b8;"></i>
                </a>
                @elseif(auth()->user()->role === 'admin')
                <div class="sidebar-group-label">MAIN CONTROL</div>
                <a href="{{ Request::is('admin*') ? '#dashboard' : route('admin.dashboard') . '#dashboard' }}" class="sidebar-link {{ Request::is('admin*') ? 'tab-link' : '' }}" data-tab="dashboard">
                    <i class="bi bi-speedometer2"></i> <span>Dashboard Overview</span>
                </a>
                <a href="{{ Request::is('admin*') ? '#talents' : route('admin.dashboard') . '#talents' }}" class="sidebar-link {{ Request::is('admin*') ? 'tab-link' : '' }}" data-tab="talents">
                    <i class="bi bi-people-fill"></i> <span>Registered Talents</span>
                </a>
                <a href="{{ Request::is('admin*') ? '#categories' : route('admin.dashboard') . '#categories' }}" class="sidebar-link {{ Request::is('admin*') ? 'tab-link' : '' }}" data-tab="categories">
                    <i class="bi bi-tags-fill"></i> <span>Manage Categories</span>
                </a>
                <a href="{{ Request::is('admin*') ? '#packages' : route('admin.dashboard') . '#packages' }}" class="sidebar-link {{ Request::is('admin*') ? 'tab-link' : '' }}" data-tab="packages">
                    <i class="bi bi-box-seam-fill"></i> <span>Membership Packages</span>
                </a>
                <a href="{{ Request::is('admin*') ? '#invoices' : route('admin.dashboard') . '#invoices' }}" class="sidebar-link {{ Request::is('admin*') ? 'tab-link' : '' }}" data-tab="invoices">
                    <i class="bi bi-receipt-cutoff"></i> <span>Invoices & Billing</span>
                </a>
                <a href="{{ Request::is('admin*') ? '#payments' : route('admin.dashboard') . '#payments' }}" class="sidebar-link {{ Request::is('admin*') ? 'tab-link' : '' }}" data-tab="payments">
                    <i class="bi bi-wallet2"></i> <span>Talent Payments</span>
                </a>
                <a href="{{ Request::is('admin*') ? '#staff' : route('admin.dashboard') . '#staff' }}" class="sidebar-link {{ Request::is('admin*') ? 'tab-link' : '' }}" data-tab="staff">
                    <i class="bi bi-person-plus-fill"></i> <span>Registered Staff</span>
                </a>
                <a href="{{ Request::is('admin*') ? '#analytics' : route('admin.dashboard') . '#analytics' }}" class="sidebar-link {{ Request::is('admin*') ? 'tab-link' : '' }}" data-tab="analytics">
                    <i class="bi bi-graph-up-arrow"></i> <span>Visitor Analytics</span>
                </a>
                <a href="{{ Request::is('admin*') ? '#activity-logs' : route('admin.dashboard') . '#activity-logs' }}" class="sidebar-link {{ Request::is('admin*') ? 'tab-link' : '' }}" data-tab="activity-logs">
                    <i class="bi bi-journal-text"></i> <span>User Activity Logs</span>
                </a>

                <div class="sidebar-group-label">OPERATIONS & SUPPORT</div>
                <a href="{{ route('admin.moderation') }}" class="sidebar-link {{ Request::is('admin/moderation*') ? 'active' : '' }}">
                    <i class="bi bi-shield-exclamation" style="color:#f59e0b;"></i> <span>Content Moderation & NSFW</span>
                    @php
                    $flaggedCount = \App\Models\Media::where('moderation_status', 'flagged')->count();
                    @endphp
                    @if($flaggedCount > 0)
                    <span style="margin-left: auto; background: #ef4444; color: #fff; font-size: 0.72rem; font-weight: 800; padding: 2px 7px; border-radius: 10px;">{{ $flaggedCount }}</span>
                    @endif
                </a>
                <a href="{{ route('customer-care.dashboard') }}#tickets" class="sidebar-link {{ Request::is('customer-care*') ? 'cc-tab-link' : '' }}" data-cctab="tickets">
                    <i class="bi bi-life-preserver"></i> <span>Support Issues & Tickets Roster</span>
                </a>
                <a href="{{ route('customer-care.dashboard') }}#blocked" class="sidebar-link {{ Request::is('customer-care*') ? 'cc-tab-link' : '' }}" data-cctab="blocked">
                    <i class="bi bi-shield-slash-fill" style="color:#dc2626;"></i> <span>Blocked Accounts & Login Control</span>
                </a>
                <a href="{{ route('customer-care.dashboard') }}#talents" class="sidebar-link {{ Request::is('customer-care*') ? 'cc-tab-link' : '' }}" data-cctab="talents">
                    <i class="bi bi-people-fill" style="color:var(--primary);"></i> <span>Talents Directory & Q&amp;A</span>
                </a>
                <a href="{{ route('customer-care.dashboard') }}#requests" class="sidebar-link {{ Request::is('customer-care*') ? 'cc-tab-link' : '' }}" data-cctab="requests">
                    <i class="bi bi-person-check-fill" style="color:#6366f1;"></i> <span>Guest Contact Requests</span>
                </a>
                <a href="{{ route('customer-care.dashboard') }}#payments" class="sidebar-link {{ Request::is('customer-care*') ? 'cc-tab-link' : '' }}" data-cctab="payments">
                    <i class="bi bi-wallet2"></i> <span>Talent Payment Requests</span>
                </a>

                <div class="sidebar-group-label">SETTINGS & PROFILES</div>
                <a href="{{ Request::is('admin*') ? '#system-settings' : route('admin.dashboard') . '#system-settings' }}" class="sidebar-link {{ Request::is('admin*') ? 'tab-link' : '' }}" data-tab="system-settings">
                    <i class="bi bi-gear-wide-connected"></i> <span>System Settings</span>
                </a>
                <a href="{{ Request::is('admin*') ? '#settings' : route('admin.dashboard') . '#settings' }}" class="sidebar-link {{ Request::is('admin*') ? 'tab-link' : '' }}" data-tab="settings">
                    <i class="bi bi-person-gear"></i> <span>My Account Profile</span>
                </a>

                <div class="sidebar-group-label">DIRECTORY</div>
                <a href="{{ route('home') }}" target="_blank" class="sidebar-link">
                    <i class="bi bi-grid-fill"></i> <span>Public Directory</span>
                    <i class="bi bi-box-arrow-up-right" style="font-size: 0.75rem; margin-left: auto; color: #94a3b8;"></i>
                </a>
                @else
                <!-- ROLE USER SIDEBAR NAVIGATION -->
                <div class="sidebar-group-label">MAIN CONTROL</div>
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ Request::routeIs('dashboard') && !Request::has('tab') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> <span>Dashboard Overview</span>
                </a>
                <a href="{{ route('dashboard') }}?tab=billing" class="sidebar-link {{ Request::routeIs('dashboard') && Request::input('tab') === 'billing' ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i> <span>My Package & Bills</span>
                </a>

                <div class="sidebar-group-label">PORTFOLIO CONTENT</div>
                <a href="{{ route('dashboard.photos') }}" class="sidebar-link {{ Request::routeIs('dashboard.photos') ? 'active' : '' }}">
                    <i class="bi bi-camera-fill"></i> <span>Manage Photos</span>
                </a>
                <a href="{{ route('dashboard.videos') }}" class="sidebar-link {{ Request::routeIs('dashboard.videos') ? 'active' : '' }}">
                    <i class="bi bi-camera-video-fill"></i> <span>Manage Videos</span>
                </a>
                <a href="{{ route('dashboard.news') }}" class="sidebar-link {{ Request::routeIs('dashboard.news') ? 'active' : '' }}">
                    <i class="bi bi-newspaper"></i> <span>Manage News</span>
                </a>
                <a href="{{ route('dashboard.comments') }}" class="sidebar-link {{ Request::routeIs('dashboard.comments') ? 'active' : '' }}">
                    <i class="bi bi-chat-left-text-fill"></i> <span>Manage Comments</span>
                </a>

                <div class="sidebar-group-label">DIRECTORY & PUBLIC</div>
                <a href="{{ route('profile', auth()->user()->id) }}" target="_blank" class="sidebar-link">
                    <i class="bi bi-person-badge-fill"></i> <span>Public Profile</span>
                    <i class="bi bi-box-arrow-up-right" style="font-size: 0.75rem; margin-left: auto; color: #94a3b8;"></i>
                </a>
                <a href="{{ route('home') }}" class="sidebar-link">
                    <i class="bi bi-grid-fill"></i> <span>Public Directory</span>
                </a>

                <div class="sidebar-group-label">SUPPORT</div>
                <a href="#" onclick="event.preventDefault(); $('#user-support-modal').fadeIn(200);" class="sidebar-link">
                    <i class="bi bi-headset"></i> <span>Need Help / Support</span>
                </a>
                @endif
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="admin-main-wrapper">
            @yield('content')
        </div>
    </div>
    @else
    @yield('content')
    @endif
    @else
    @yield('content')
    @endauth

    <!-- jQuery + Select2 + DataTables + Responsive Extension + Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('js/image-compressor.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script>
        function dismissToast(element) {
            if (!element) return;
            element.classList.add('toast-hiding');
            setTimeout(() => {
                element.remove();
            }, 400);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const autoToasts = document.querySelectorAll('.auto-toast-item');
            autoToasts.forEach(function(toast) {
                let timer = null;
                const duration = 10000; // 10 seconds (smooth, readable pace)

                function startTimer() {
                    timer = setTimeout(function() {
                        dismissToast(toast);
                    }, duration);
                }

                // Pause timer & progress bar on hover so customer can read comfortably
                toast.addEventListener('mouseenter', function() {
                    if (timer) clearTimeout(timer);
                    const progress = toast.querySelector('.toast-progress');
                    if (progress) progress.style.animationPlayState = 'paused';
                });

                toast.addEventListener('mouseleave', function() {
                    const progress = toast.querySelector('.toast-progress');
                    if (progress) progress.style.animationPlayState = 'running';
                    startTimer();
                });

                startTimer();
            });
        });
    </script>
    <!-- Floating Real-Time In-App Alert Toasts Container -->
    <div id="liveInstantAlertContainer" style="position: fixed; top: 20px; right: 20px; z-index: 999999; display: flex; flex-direction: column; gap: 12px; max-width: 380px; width: calc(100% - 40px); pointer-events: none;"></div>

    <style>
        .live-instant-toast {
            pointer-events: auto;
            background: #ffffff;
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-left: 4px solid #6366f1;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.18), 0 4px 8px rgba(0, 0, 0, 0.05);
            padding: 14px 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            cursor: pointer;
            animation: slideInNotif 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .live-instant-toast:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 35px rgba(15, 23, 42, 0.22);
        }

        .live-toast-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: rgba(99, 102, 241, 0.12);
            color: #6366f1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
            animation: notifPulse 1.8s infinite ease-in-out;
        }

        @keyframes slideInNotif {
            from {
                opacity: 0;
                transform: translateX(50px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        @keyframes notifPulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>

    <!-- Notification Sound Chime Player -->
    @php
    $rawSound = \App\Models\SystemSetting::get('notification_sound', '/sounds/notification_default.wav');
    $notifSoundUrl = str_starts_with($rawSound, 'http') ? $rawSound : '/' . ltrim($rawSound, '/');
    $soundEnabled = \App\Models\SystemSetting::get('notification_sound_enabled', '1') == '1';
    @endphp
    <audio id="chapconnect-notification-audio" src="{{ $notifSoundUrl }}" preload="auto"></audio>

    <script>
        const isNotificationSoundEnabled = {
            {
                $soundEnabled ? 'true' : 'false'
            }
        };
        let audioContext = null;
        let audioUnlocked = false;

        // Auto-unlock AudioContext on user interaction
        function unlockAudioEngine() {
            try {
                if (!audioContext) {
                    const AudioCtxClass = window.AudioContext || window.webkitAudioContext;
                    if (AudioCtxClass) {
                        audioContext = new AudioCtxClass();
                    }
                }
                if (audioContext && audioContext.state === 'suspended') {
                    audioContext.resume();
                }
                const audio = document.getElementById('chapconnect-notification-audio');
                if (audio && !audioUnlocked) {
                    audio.muted = true;
                    const p = audio.play();
                    if (p !== undefined) {
                        p.then(() => {
                            audio.pause();
                            audio.currentTime = 0;
                            audio.muted = false;
                            console.log('[Notification Engine] Audio tag unlocked successfully.');
                        }).catch(() => {
                            audio.muted = false;
                        });
                    }
                }
                audioUnlocked = true;
            } catch (e) {
                console.warn('[Notification Engine] Audio unlock warning:', e);
            }
        }

        // Global event listeners to prime audio on any interaction
        $(window).on('mousemove scroll focus keydown touchstart pointerdown mousedown click', function() {
            unlockAudioEngine();
        });

        // Web Audio API Synthesizer fallback
        function doSynthesize(ctx) {
            try {
                const now = ctx.currentTime;
                const masterGain = ctx.createGain();
                masterGain.gain.setValueAtTime(0.8, now);
                masterGain.connect(ctx.destination);

                // High chime tone 1 (E6 - 1318.5 Hz)
                const osc1 = ctx.createOscillator();
                const gain1 = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(1318.5, now);
                gain1.gain.setValueAtTime(0.7, now);
                gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.45);
                osc1.connect(gain1);
                gain1.connect(masterGain);
                osc1.start(now);
                osc1.stop(now + 0.45);

                // High chime tone 2 (A6 - 1760 Hz)
                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(1760.0, now + 0.1);
                gain2.gain.setValueAtTime(0.8, now + 0.1);
                gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.75);
                osc2.connect(gain2);
                gain2.connect(masterGain);
                osc2.start(now + 0.1);
                osc2.stop(now + 0.75);
                console.log('[Notification Engine] WebAudio synthesizer chime played.');
            } catch (e) {
                console.warn('[Notification Engine] Synthesis play error:', e);
            }
        }

        window.playSynthesizedBell = function() {
            try {
                const AudioCtxClass = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtxClass) return;

                const ctx = audioContext || new AudioCtxClass();
                audioContext = ctx;

                if (ctx.state === 'suspended') {
                    ctx.resume().then(() => doSynthesize(ctx)).catch(() => doSynthesize(ctx));
                } else {
                    doSynthesize(ctx);
                }
            } catch (err) {
                console.warn('[Notification Engine] Web Audio synthesis unavailable:', err);
            }
        };

        window.triggerNotificationSound = function(force = false) {
            if (!force && !isNotificationSoundEnabled) {
                console.log('[Notification Engine] Sound is disabled in settings.');
                return;
            }

            unlockAudioEngine();

            const audio = document.getElementById('chapconnect-notification-audio');
            if (audio) {
                audio.muted = false;
                audio.currentTime = 0;
                const playPromise = audio.play();
                if (playPromise !== undefined) {
                    playPromise.then(function() {
                        console.log('[Notification Engine] HTML5 Audio chime played successfully.');
                    }).catch(function(error) {
                        console.warn('[Notification Engine] HTML5 audio blocked/failed, playing WebAudio synthesizer:', error);
                        window.playSynthesizedBell();
                    });
                }
            } else {
                window.playSynthesizedBell();
            }
        };

        // Self-diagnostic test function available in browser console
        window.debugNotificationSound = function() {
            console.log('=== [Notification Engine Diagnostics] ===');
            console.log('1. Sound enabled in settings:', isNotificationSoundEnabled);
            console.log('2. Audio element src:', document.getElementById('chapconnect-notification-audio')?.src);
            console.log('3. Audio unlocked:', audioUnlocked);
            console.log('4. AudioContext state:', audioContext?.state);
            console.log('5. Triggering test sound now...');
            window.triggerNotificationSound(true);
            window.showInstantNotificationToast('Test Notification Alert', 'If you heard the chime and see this toast, notifications are working 100%!', '#');
        };

        // Display Live Floating Toast on screen for new alerts
        window.showInstantNotificationToast = function(title, message, link) {
            const toastId = 'toast_' + Date.now() + '_' + Math.floor(Math.random() * 1000);
            const targetLink = link || '#';
            const toastHtml = `
                <div class="live-instant-toast" id="${toastId}" onclick="window.location.href='${targetLink}'">
                    <div class="live-toast-icon">
                        <i class="bi bi-bell-fill"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3px;">
                            <strong style="font-size: 0.88rem; color: #0f172a; font-weight: 800; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${title}</strong>
                            <span style="font-size: 0.7rem; color: #6366f1; font-weight: 700; background: rgba(99,102,241,0.1); padding: 1px 6px; border-radius: 10px;">New</span>
                        </div>
                        <p style="margin: 0; font-size: 0.8rem; color: #475569; line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">${message}</p>
                    </div>
                    <button type="button" onclick="event.stopPropagation(); $('#${toastId}').fadeOut(200, function(){ $(this).remove(); });" style="background: none; border: none; font-size: 1.1rem; color: #94a3b8; cursor: pointer; padding: 0 4px; line-height: 1;">&times;</button>
                </div>
            `;

            $('#liveInstantAlertContainer').append(toastHtml);

            setTimeout(function() {
                $('#' + toastId).fadeOut(300, function() {
                    $(this).remove();
                });
            }, 7000);
        };
    </script>

    @auth
    <script>
        // Track alerted notification IDs in sessionStorage so sound plays immediately on login
        function getAlertedNotifIds() {
            try {
                const stored = sessionStorage.getItem('chapconnect_alerted_notifs');
                return stored ? new Set(JSON.parse(stored)) : new Set();
            } catch (e) {
                return new Set();
            }
        }

        function persistAlertedNotifIds(setObj) {
            try {
                sessionStorage.setItem('chapconnect_alerted_notifs', JSON.stringify(Array.from(setObj)));
            } catch (e) {}
        }

        let knownNotifIds = getAlertedNotifIds();
        let isPollingInProgress = false;
        let notificationChannel = null;

        // Setup BroadcastChannel for 0ms instant sync across tabs
        if ('BroadcastChannel' in window) {
            try {
                notificationChannel = new BroadcastChannel('chapconnect_live_alerts');
                notificationChannel.onmessage = function(event) {
                    if (event.data && event.data.type === 'REFRESH_NOTIFICATIONS') {
                        console.log('[Notification Engine] ⚡ Instant Broadcast received from another tab. Fetching immediately...');
                        fetchNotifications();
                    }
                };
            } catch (e) {}
        }

        // Global helper to trigger instant 0ms alert in all open tabs
        window.broadcastNotificationAlert = function() {
            try {
                if (notificationChannel) {
                    notificationChannel.postMessage({
                        type: 'REFRESH_NOTIFICATIONS',
                        time: Date.now()
                    });
                }
                localStorage.setItem('chapconnect_alert_broadcast', Date.now());
            } catch (e) {}
        };

        // Also listen to localStorage changes across browser windows
        window.addEventListener('storage', function(e) {
            if (e.key === 'chapconnect_alert_broadcast') {
                console.log('[Notification Engine] ⚡ Storage event received. Fetching notifications instantly...');
                fetchNotifications();
            }
        });

        function fetchNotifications() {
            if (isPollingInProgress) return;
            isPollingInProgress = true;

            $.ajax({
                url: "{{ route('notifications.unread') }}",
                type: "GET",
                dataType: "json",
                timeout: 4000,
                success: function(data) {
                    const count = data.count || 0;
                    const $badge = $('#notifBadge');
                    const $list = $('#notifList');
                    const notifs = data.notifications || [];
                    const currentIds = notifs.map(n => n.id);

                    // Brand new notifications not yet alerted in this session
                    const brandNewItems = notifs.filter(n => !knownNotifIds.has(n.id));

                    if (count > 0) {
                        $badge.text(count).show();
                        if (brandNewItems.length > 0) {
                            console.log('[Notification Engine] 🔔 ALERT TRIGGERED! Playing sound & toast instantly for:', brandNewItems);

                            // 1. Play sound immediately (including on login)
                            window.triggerNotificationSound();

                            // 2. Show floating instant alert toast
                            brandNewItems.forEach(function(item) {
                                window.showInstantNotificationToast(item.title, item.message, item.link);
                            });

                            // Add to known IDs and persist in sessionStorage
                            brandNewItems.forEach(item => knownNotifIds.add(item.id));
                            persistAlertedNotifIds(knownNotifIds);
                        }
                    } else {
                        $badge.hide();
                    }

                    if (notifs.length > 0) {
                        let html = '';
                        notifs.forEach(function(n) {
                            html += `
                                <div style="padding: 12px 16px; border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc';" onmouseout="this.style.background='#fff';">
                                    <a href="${n.link || '#'}" style="text-decoration: none; color: inherit; display: block;">
                                        <div style="font-weight: 700; font-size: 0.84rem; color: #0f172a; margin-bottom: 2px;">${n.title}</div>
                                        <div style="font-size: 0.78rem; color: #475569; line-height: 1.3;">${n.message}</div>
                                    </a>
                                    <button onclick="markNotificationRead(${n.id}, this)" style="margin-top: 6px; background: none; border: none; font-size: 0.72rem; color: #6366f1; font-weight: 600; cursor: pointer; padding: 0;">✓ Mark Read</button>
                                </div>
                            `;
                        });
                        $list.html(html);
                    } else {
                        $list.html('<div style="padding: 20px; text-align: center; color: #94a3b8; font-size: 0.85rem;"><i class="bi bi-check-circle" style="font-size: 1.2rem; display: block; margin-bottom: 4px;"></i> {{ __("No new notifications") }}</div>');
                    }
                },
                complete: function() {
                    isPollingInProgress = false;
                }
            });
        }

        function markNotificationRead(id, btn) {
            $.ajax({
                url: '/notifications/' + id + '/mark-read',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    $(btn).closest('div').fadeOut(200, function() {
                        $(this).remove();
                        fetchNotifications();
                    });
                }
            });
        }

        $(document).ready(function() {
            // Immediate fetch on page load / login
            fetchNotifications();

            // Real-time polling every 1 second for instant 0-delay notification delivery
            setInterval(fetchNotifications, 1000);

            // Trigger immediate fetch whenever window/tab becomes active
            $(window).on('focus visibilitychange', function() {
                if (document.visibilityState === 'visible') {
                    fetchNotifications();
                }
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('.notification-wrapper').length) {
                    $('#notifDropdown').fadeOut(150);
                }
            });
        });
    </script>
    @endauth

    <!-- First-Time Typewriter Splash Loader Script -->
    <script>
        (function() {
            var isTest = window.location.search.indexOf('test_loader=1') !== -1;
            var hasVisited = sessionStorage.getItem('chap_first_visit_done');

            if (!hasVisited || isTest) {
                var loaderOverlay = document.getElementById('firstTimeLoaderOverlay');
                if (loaderOverlay) {
                    loaderOverlay.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                }

                document.addEventListener("DOMContentLoaded", function() {
                    var loaderOverlay = document.getElementById('firstTimeLoaderOverlay');
                    var typewriterEl = document.getElementById('typewriterText');
                    var progressBar = document.getElementById('loaderProgressBar');
                    var audioEl = document.getElementById('welcomeAudioElement');
                    var audioBtnText = document.getElementById('welcomeAudioBtnText');
                    if (!loaderOverlay || !typewriterEl) return;

                    loaderOverlay.style.display = 'flex';
                    document.body.style.overflow = 'hidden';

                    var textToType = @json($welcomeText);
                    var charIndex = 0;
                    var typingSpeed = {
                        {
                            max(15, (int) $welcomeSpeed)
                        }
                    };
                    var welcomeDelay = {
                        {
                            max(0, (int) $welcomeDelay)
                        }
                    };
                    var welcomeSoundUrl = @json($welcomeSoundUrl);

                    var audioInstance = audioEl || (welcomeSoundUrl ? new Audio(welcomeSoundUrl) : null);
                    var audioPlayed = false;

                    window.playWelcomeSound = function(userAction) {
                        if (audioPlayed && !userAction) return;

                        if (audioInstance) {
                            try {
                                audioInstance.currentTime = 0;
                                audioInstance.volume = 1.0;
                                var p = audioInstance.play();
                                if (p !== undefined) {
                                    p.then(function() {
                                        audioPlayed = true;
                                        if (audioBtnText) audioBtnText.textContent = "Welcome Audio Playing ♪";
                                    }).catch(function(e) {
                                        speakFemaleVoiceFallback();
                                    });
                                }
                            } catch (e) {
                                speakFemaleVoiceFallback();
                            }
                        } else {
                            speakFemaleVoiceFallback();
                        }
                    };

                    function speakFemaleVoiceFallback() {
                        if ('speechSynthesis' in window) {
                            try {
                                window.speechSynthesis.cancel();
                                var utterance = new SpeechSynthesisUtterance(textToType);
                                utterance.rate = 0.95;
                                utterance.pitch = 1.15;
                                var voices = window.speechSynthesis.getVoices();
                                var femaleVoice = voices.find(function(v) {
                                    var n = v.name.toLowerCase();
                                    return n.includes('female') || n.includes('zira') || n.includes('samantha') || n.includes('google us english') || n.includes('swahili') || n.includes('sw');
                                });
                                if (femaleVoice) utterance.voice = femaleVoice;
                                window.speechSynthesis.speak(utterance);
                                audioPlayed = true;
                                if (audioBtnText) audioBtnText.textContent = "Welcome Audio Playing ♪";
                            } catch (err) {}
                        }
                    }

                    function triggerUserInteractionSound() {
                        if (!audioPlayed) {
                            playWelcomeSound(true);
                        }
                    }

                    ['mousemove', 'pointermove', 'mouseover', 'touchstart', 'pointerdown', 'click', 'keydown'].forEach(function(evt) {
                        window.addEventListener(evt, triggerUserInteractionSound, {
                            passive: true,
                            once: false
                        });
                        document.addEventListener(evt, triggerUserInteractionSound, {
                            passive: true,
                            once: false
                        });
                        if (loaderOverlay) {
                            loaderOverlay.addEventListener(evt, triggerUserInteractionSound, {
                                passive: true,
                                once: false
                            });
                        }
                    });

                    function typeNextChar() {
                        if (charIndex < textToType.length) {
                            typewriterEl.textContent += textToType.charAt(charIndex);
                            charIndex++;
                            var progressPercent = Math.min(100, Math.round((charIndex / textToType.length) * 90));
                            if (progressBar) progressBar.style.width = progressPercent + '%';
                            setTimeout(typeNextChar, typingSpeed);
                        } else {
                            if (progressBar) progressBar.style.width = '100%';
                            setTimeout(dismissIntroLoader, 900);
                        }
                    }

                    function dismissIntroLoader() {
                        if (!loaderOverlay) return;
                        loaderOverlay.style.opacity = '0';
                        document.body.style.overflow = '';
                        sessionStorage.setItem('chap_first_visit_done', 'true');
                        setTimeout(function() {
                            loaderOverlay.style.display = 'none';
                        }, 600);
                    }

                    // Kickoff welcome sound and typing after delay
                    setTimeout(function() {
                        playWelcomeSound(false);
                        typeNextChar();
                    }, welcomeDelay);

                    // Allow clicking overlay to trigger sound immediately
                    loaderOverlay.addEventListener('click', function(e) {
                        if (e.target && e.target.id === 'welcomeAudioTriggerBtn') return;
                        if (!audioPlayed) {
                            playWelcomeSound(true);
                        }
                    });
                });
            }
        })();
    </script>

    @if(Request::is('/'))
    <!-- Floating Bottom-Right Corner Install App Link Banner (Home Page Only) -->
    <div id="pwaInstallBanner" style="display: flex; position: fixed; bottom: 25px; right: 25px; z-index: 999999; background: #0f172a; border: 1px solid rgba(16,185,129,0.5); border-radius: 30px; padding: 10px 18px; box-shadow: 0 10px 30px rgba(0,0,0,0.35); color: #ffffff; align-items: center; gap: 10px; backdrop-filter: blur(10px);">
        <img src="{{ asset('logo.png') }}" alt="ChapConnect App" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 1px solid rgba(255,255,255,0.3); flex-shrink: 0;" onerror="this.src='https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=100&auto=format&fit=crop'">
        <a href="#" onclick="event.preventDefault(); handlePwaInstallClick();" style="color: #ffffff; text-decoration: none; font-weight: 700; font-size: 0.86rem; display: inline-flex; align-items: center; gap: 6px;">
            <i class="bi bi-download" style="color: #10b981; font-size: 1rem;"></i>
        </a>
        <button type="button" onclick="dismissPwaBanner()" style="background: none; border: none; color: #94a3b8; font-size: 1.1rem; cursor: pointer; padding: 0; margin-left: 4px; display: inline-flex; align-items: center;" title="Dismiss">&times;</button>
    </div>
    @endif

    <!-- PWA Step-by-Step Install Guide Modal -->
    <div id="pwaGuideModal" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15,23,42,0.85); backdrop-filter: blur(8px); z-index: 999999; justify-content: center; align-items: center; padding: 20px;" onclick="closePwaGuideModal()">
        <div style="background: #ffffff; border-radius: 20px; max-width: 440px; width: 100%; padding: 25px; box-shadow: 0 20px 50px rgba(0,0,0,0.3); border: 1px solid #e2e8f0; position: relative;" onclick="event.stopPropagation();">
            <button type="button" onclick="closePwaGuideModal()" style="position: absolute; top: 16px; right: 16px; background: #f1f5f9; border: none; border-radius: 50%; width: 32px; height: 32px; font-size: 1.2rem; color: #64748b; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">&times;</button>

            <div style="text-align: center; margin-bottom: 18px;">
                <img src="{{ asset('logo.png') }}" alt="ChapConnect" style="width: 64px; height: 64px; border-radius: 16px; object-fit: cover; box-shadow: 0 6px 18px rgba(99,102,241,0.25); margin-bottom: 10px;" onerror="this.src='https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=100&auto=format&fit=crop'">
                <h3 style="margin: 0 0 4px 0; font-size: 1.25rem; font-weight: 800; color: #0f172a;">Install ChapConnect App</h3>
                <p style="margin: 0 0 14px 0; color: #64748b; font-size: 0.84rem;">Add ChapConnect directly to your Android or iOS home screen.</p>
            </div>

            <div style="background: #f8fafc; border-radius: 14px; padding: 16px; border: 1px solid #e2e8f0; margin-bottom: 16px;">
                <div style="font-weight: 700; font-size: 0.86rem; color: #0f172a; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-phone-fill" style="color: #6366f1; font-size: 1.1rem;"></i> 1-Tap Mobile Installation:
                </div>
                <ul style="margin: 0; padding-left: 20px; font-size: 0.84rem; color: #475569; line-height: 1.6;">
                    <li><strong>Android (Chrome / Edge):</strong> Tap browser menu (<i class="bi bi-three-dots-vertical"></i>) &rarr; Select <strong>"Install App"</strong> or <strong>"Add to Home Screen"</strong>.</li>
                    <li><strong>iPhone (Safari):</strong> Tap Share icon (<i class="bi bi-box-arrow-up"></i>) &rarr; Select <strong>"Add to Home Screen"</strong>.</li>
                </ul>
            </div>

            <button type="button" onclick="closePwaGuideModal()" style="width: 100%; padding: 11px; border-radius: 12px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; border: none; font-weight: 700; font-size: 0.9rem; cursor: pointer; box-shadow: 0 4px 14px rgba(99,102,241,0.4);">
                Got It!
            </button>
        </div>
    </div>

    <!-- PWA Service Worker Registration & Install Script -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').then(function(reg) {
                    console.log('PWA ServiceWorker registered successfully:', reg.scope);
                }).catch(function(err) {
                    console.log('PWA ServiceWorker registration failed:', err);
                });
            });
        }

        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
        });

        function handlePwaInstallClick() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    console.log('User outcome:', choiceResult.outcome);
                    deferredPrompt = null;
                });
            } else {
                var modal = document.getElementById('pwaGuideModal');
                if (modal) modal.style.display = 'flex';
            }
        }

        function dismissPwaBanner() {
            const banner = document.getElementById('pwaInstallBanner');
            if (banner) {
                banner.style.display = 'none';
                // Remember dismissal for this browser session only (clears on browser close)
                try {
                    sessionStorage.setItem('pwaBannerDismissed', '1');
                } catch (e) {}
            }
        }

        // Hide banner immediately if already dismissed this session
        document.addEventListener('DOMContentLoaded', function() {
            try {
                if (sessionStorage.getItem('pwaBannerDismissed') === '1') {
                    const banner = document.getElementById('pwaInstallBanner');
                    if (banner) banner.style.display = 'none';
                }
            } catch (e) {}
        });

        function closePwaGuideModal() {
            const modal = document.getElementById('pwaGuideModal');
            if (modal) modal.style.display = 'none';
        }

        function openGlobalSupportModal() {
            const modal = document.getElementById('globalSupportModal');
            if (modal) {
                modal.style.display = 'flex';
                modal.style.opacity = '0';
                setTimeout(() => {
                    modal.style.opacity = '1';
                }, 10);
            }
        }

        function closeGlobalSupportModal() {
            const modal = document.getElementById('globalSupportModal');
            if (modal) {
                modal.style.opacity = '0';
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 200);
            }
        }
    </script>

    <!-- Global Support Modal Overlay -->
    <div id="globalSupportModal" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15,23,42,0.8); backdrop-filter: blur(8px); z-index: 999999; justify-content: center; align-items: center; padding: 20px; transition: opacity 0.2s ease-in-out; opacity: 0;" onclick="closeGlobalSupportModal()">
        <div style="background: #ffffff; border-radius: 20px; max-width: 480px; width: 100%; padding: 25px; box-shadow: 0 20px 50px rgba(0,0,0,0.3); border: 1px solid #e2e8f0; position: relative;" onclick="event.stopPropagation();">
            <button type="button" onclick="closeGlobalSupportModal()" style="position: absolute; top: 16px; right: 16px; background: #f1f5f9; border: none; border-radius: 50%; width: 32px; height: 32px; font-size: 1.2rem; color: #64748b; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #cbd5e1;">&times;</button>

            <div style="text-align: center; margin-bottom: 20px;">
                <img src="{{ asset(\App\Models\SystemSetting::get('site_logo', 'logo.png')) }}" alt="{{ \App\Models\SystemSetting::get('site_title', 'ChapConnect') }} Logo" style="width: 64px; height: 64px; border-radius: 16px; object-fit: cover; box-shadow: 0 6px 18px rgba(99,102,241,0.2); margin-bottom: 10px;" onerror="this.src='https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=100&auto=format&fit=crop'">
                <h3 style="margin: 0 0 4px 0; font-size: 1.3rem; font-weight: 800; color: #0f172a;">{{ \App\Models\SystemSetting::get('site_title', 'ChapConnect') }} {{ __('Customer Support') }}</h3>
                <p style="margin: 0; color: #64748b; font-size: 0.86rem;">{{ __('Help desk & Support') }}</p>
            </div>

            <!-- Short Summary about ChapConnect -->
            <div style="background: #f8fafc; border-radius: 12px; padding: 14px 16px; border: 1px solid #cbd5e1; margin-bottom: 20px; font-size: 0.85rem; color: #334155; line-height: 1.5; text-align: center;">
                {{ __('Connecting Talent & Opportunities across Tanzania') }}
            </div>

            <!-- Contact Channels -->
            <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px;">
                <!-- WhatsApp -->
                @if(\App\Models\SystemSetting::get('whatsapp_number'))
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\SystemSetting::get('whatsapp_number')) }}" target="_blank" style="display: flex; align-items: center; gap: 12px; padding: 10px 15px; border-radius: 12px; background: linear-gradient(135deg, #25d366 0%, #128c7e 100%); color: #ffffff; text-decoration: none; font-weight: 700; font-size: 0.88rem; box-shadow: 0 4px 12px rgba(37,211,102,0.2);">
                    <i class="bi bi-whatsapp" style="font-size: 1.3rem;"></i>
                    <div style="text-align: left;">
                        <span style="font-size: 0.72rem; display: block; opacity: 0.85; font-weight: 500;">{{ __('WhatsApp Number') }}</span>
                        <span>{{ \App\Models\SystemSetting::get('whatsapp_number') }}</span>
                    </div>
                </a>
                @endif

                <!-- Phone -->
                @if(\App\Models\SystemSetting::get('support_phone'))
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', \App\Models\SystemSetting::get('support_phone')) }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 15px; border-radius: 12px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: #ffffff; text-decoration: none; font-weight: 700; font-size: 0.88rem; box-shadow: 0 4px 12px rgba(59,130,246,0.2);">
                    <i class="bi bi-telephone-fill" style="font-size: 1.2rem;"></i>
                    <div style="text-align: left;">
                        <span style="font-size: 0.72rem; display: block; opacity: 0.85; font-weight: 500;">{{ __('Phone Number') }}</span>
                        <span>{{ \App\Models\SystemSetting::get('support_phone') }}</span>
                    </div>
                </a>
                @endif

                <!-- Email -->
                @if(\App\Models\SystemSetting::get('support_email'))
                <a href="mailto:{{ \App\Models\SystemSetting::get('support_email') }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 15px; border-radius: 12px; background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); color: #ffffff; text-decoration: none; font-weight: 700; font-size: 0.88rem; box-shadow: 0 4px 12px rgba(239,68,68,0.2);">
                    <i class="bi bi-envelope-fill" style="font-size: 1.2rem;"></i>
                    <div style="text-align: left;">
                        <span style="font-size: 0.72rem; display: block; opacity: 0.85; font-weight: 500;">{{ __('Email Address') }}</span>
                        <span>{{ \App\Models\SystemSetting::get('support_email') }}</span>
                    </div>
                </a>
                @endif
            </div>

            <!-- Social Media Channels Links -->
            <div style="border-top: 1px solid #e2e8f0; padding-top: 16px; text-align: center;">
                <span style="font-size: 0.78rem; font-weight: 700; color: #64748b; display: block; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">{{ __('Official Media & Social Channels') }}</span>
                <div style="display: flex; justify-content: center; gap: 12px;">
                    @if(\App\Models\SystemSetting::get('site_facebook'))
                    <a href="{{ \App\Models\SystemSetting::get('site_facebook') }}" target="_blank" style="width: 36px; height: 36px; border-radius: 50%; background: #3b5998; color: #fff; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 1.15rem; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><i class="bi bi-facebook"></i></a>
                    @endif
                    @if(\App\Models\SystemSetting::get('site_instagram'))
                    <a href="{{ \App\Models\SystemSetting::get('site_instagram') }}" target="_blank" style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); color: #fff; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 1.15rem; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><i class="bi bi-instagram"></i></a>
                    @endif
                    @if(\App\Models\SystemSetting::get('site_tiktok'))
                    <a href="{{ \App\Models\SystemSetting::get('site_tiktok') }}" target="_blank" style="width: 36px; height: 36px; border-radius: 50%; background: #000000; color: #fff; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 1.1rem; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><i class="bi bi-tiktok"></i></a>
                    @endif
                    @if(\App\Models\SystemSetting::get('site_youtube'))
                    <a href="{{ \App\Models\SystemSetting::get('site_youtube') }}" target="_blank" style="width: 36px; height: 36px; border-radius: 50%; background: #ff0000; color: #fff; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 1.15rem; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><i class="bi bi-youtube"></i></a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @auth
    <!-- Support Modal for Talent / User / Staff -->
    <div id="user-support-modal" class="admin-modal">
        <div class="admin-modal-content" style="border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-width: 520px; width: 90%; margin: auto;">
            <div class="admin-modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">
                <h3 style="margin: 0; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-headset" style="color: var(--primary);"></i> {{ __('Submit Support Ticket to Customer Care') }}
                </h3>
                <button type="button" class="admin-modal-close" onclick="$('#user-support-modal').fadeOut(200);" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
            </div>
            <form action="{{ route('dashboard.support.submit') }}" method="POST">
                @csrf
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">{{ __('Issue Category') }}</label>
                    <select name="category" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        <option value="Account Access & Credentials">{{ __('Account Access & Credentials') }}</option>
                        <option value="Profile Verification">{{ __('Profile Verification') }}</option>
                        <option value="Media Uploads (Photos/Videos)">{{ __('Media Uploads (Photos/Videos)') }}</option>
                        <option value="Billing & Subscription">{{ __('Billing & Subscription') }}</option>
                        <option value="Report Abuse / Content Guidelines">{{ __('Report Abuse / Content Guidelines') }}</option>
                        <option value="General Inquiry" selected>{{ __('General Inquiry') }}</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">{{ __('Subject Title') }}</label>
                    <input type="text" name="subject" class="form-control" placeholder="{{ __('Subject Title') }}..." required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">{{ __('Priority Level') }}</label>
                    <select name="priority" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        <option value="low">{{ __('Low Priority') }}</option>
                        <option value="medium" selected>{{ __('Medium Priority') }}</option>
                        <option value="high">{{ __('High Priority') }}</option>
                        <option value="urgent">{{ __('Urgent Priority') }}</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">{{ __('Detailed Explanation') }}</label>
                    <textarea name="description" rows="4" class="form-control" placeholder="{{ __('Detailed Explanation') }}..." required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;"></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--border-color); padding-top: 15px;">
                    <button type="button" onclick="$('#user-support-modal').fadeOut(200);" style="padding: 10px 18px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; font-weight: 600; cursor: pointer;">{{ __('Cancel') }}</button>
                    <button type="submit" style="padding: 10px 24px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(99,102,241,0.35); cursor: pointer;">{{ __('Submit Support Ticket') }}</button>
                </div>
            </form>
        </div>
    </div>
    @endauth

    <!-- Global Community Media Report Modal (Report Inappropriate / Nudity / Explicit Media) -->
    <div id="community-report-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(4px); z-index: 100000; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: #ffffff; border-radius: 18px; max-width: 480px; width: 100%; padding: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.25); border: 1px solid #e2e8f0; animation: modalPop 0.25s cubic-bezier(0.16, 1, 0.3, 1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="width: 34px; height: 34px; border-radius: 10px; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                        <i class="bi bi-shield-exclamation"></i>
                    </span>
                    <h3 style="margin: 0; font-size: 1.15rem; font-weight: 700; color: #0f172a;">{{ __('Report Inappropriate Content') }}</h3>
                </div>
                <button type="button" onclick="closeReportModal()" style="background: none; border: none; font-size: 1.3rem; color: #94a3b8; cursor: pointer; padding: 0; line-height: 1;">&times;</button>
            </div>

            <form id="communityReportForm" onsubmit="submitMediaReport(event)">
                <input type="hidden" id="reportMediaId" name="media_id" value="">

                <p style="font-size: 0.86rem; color: #64748b; margin-top: 0; margin-bottom: 16px;">
                    {{ __('Help us keep ChapConnect safe. Please select why this photo/video violates our community standards:') }}
                </p>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label style="color: #334155; font-size: 0.82rem; font-weight: 700; display: block; margin-bottom: 6px;">{{ __('Violation Category') }} *</label>
                    <select id="reportReason" name="reason" required style="width: 100%; background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 12px; font-size: 0.88rem;">
                        <option value="nudity_nsfw" selected>🔞 {{ __('Nudity, Sexual or Adult Content') }}</option>
                        <option value="violence">🩸 {{ __('Violence, Gore or Physical Harm') }}</option>
                        <option value="harassment">⚠️ {{ __('Harassment, Hate Speech or Bullying') }}</option>
                        <option value="copyright">©️ {{ __('Copyright / Intellectual Property Infringement') }}</option>
                        <option value="spam">🚫 {{ __('Spam, Scam or Misleading Content') }}</option>
                        <option value="other">📝 {{ __('Other Policy Violation') }}</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="color: #334155; font-size: 0.82rem; font-weight: 700; display: block; margin-bottom: 6px;">{{ __('Additional Details (Optional)') }}</label>
                    <textarea id="reportDetails" name="details" rows="3" placeholder="{{ __('Provide any additional context to help our moderation team...') }}" style="width: 100%; box-sizing: border-box; background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 12px; font-size: 0.88rem; resize: vertical;"></textarea>
                </div>

                <div id="reportAlertBox" style="display: none; padding: 10px 14px; border-radius: 8px; margin-bottom: 15px; font-size: 0.85rem; font-weight: 600;"></div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #f1f5f9; padding-top: 14px;">
                    <button type="button" onclick="closeReportModal()" style="padding: 9px 16px; border-radius: 8px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; font-weight: 600; font-size: 0.85rem; cursor: pointer;">{{ __('Cancel') }}</button>
                    <button type="submit" id="submitReportBtn" style="padding: 9px 20px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; background: #ef4444; border: none; color: #fff; box-shadow: 0 4px 12px rgba(239,68,68,0.3); cursor: pointer;">{{ __('Submit Report') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.openReportModal = function(mediaId) {
            $('#reportMediaId').val(mediaId);
            $('#reportDetails').val('');
            $('#reportAlertBox').hide();
            $('#community-report-modal').css('display', 'flex').hide().fadeIn(180);
        };

        window.closeReportModal = function() {
            $('#community-report-modal').fadeOut(150);
        };

        window.submitMediaReport = function(e) {
            e.preventDefault();
            const mediaId = $('#reportMediaId').val();
            const reason = $('#reportReason').val();
            const details = $('#reportDetails').val();
            const btn = $('#submitReportBtn');

            btn.prop('disabled', true).text('Submitting...');

            $.ajax({
                url: '/media/' + mediaId + '/report',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    reason: reason,
                    details: details
                },
                success: function(res) {
                    btn.prop('disabled', false).text('{{ __("Submit Report") }}');
                    $('#reportAlertBox').css({
                        'background': 'rgba(16,185,129,0.12)',
                        'border': '1px solid rgba(16,185,129,0.3)',
                        'color': '#065f46'
                    }).text(res.message || 'Report received. Thank you.').fadeIn(200);

                    setTimeout(function() {
                        closeReportModal();
                    }, 1800);
                },
                error: function(xhr) {
                    btn.prop('disabled', false).text('{{ __("Submit Report") }}');
                    const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Failed to submit report. Please try again.';
                    $('#reportAlertBox').css({
                        'background': 'rgba(239,68,68,0.12)',
                        'border': '1px solid rgba(239,68,68,0.3)',
                        'color': '#be123c'
                    }).text(msg).fadeIn(200);
                }
            });
        };

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.lang-switcher').length) {
                $('#langDropdown').fadeOut(120);
            }
        });
    </script>
    <script async src="//www.instagram.com/embed.js"></script>

    {{-- Global Video Playback Controller: Single video at a time + auto-pause on scroll --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let activeVideoElement = null;

            /**
             * Pause a managed video element (iframe or HTML5 video).
             */
            function pauseVideo(el) {
                if (!el) return;
                const platform = el.getAttribute('data-platform');

                if (platform === 'local' && el.tagName === 'VIDEO') {
                    el.pause();
                } else if (platform === 'youtube' && el.tagName === 'IFRAME') {
                    try {
                        el.contentWindow.postMessage(JSON.stringify({
                            event: 'command',
                            func: 'pauseVideo',
                            args: []
                        }), '*');
                    } catch (e) {}
                } else if (platform === 'vimeo' && el.tagName === 'IFRAME') {
                    try {
                        el.contentWindow.postMessage(JSON.stringify({
                            method: 'pause'
                        }), '*');
                    } catch (e) {}
                } else if (el.tagName === 'IFRAME') {
                    // For TikTok, Instagram, Facebook: reload iframe src to stop playback
                    const src = el.getAttribute('data-video-src') || el.src;
                    if (src && el.src !== '') {
                        el.setAttribute('data-was-playing', 'true');
                        el.src = '';
                    }
                }
            }

            /**
             * Resume a managed iframe by restoring its src.
             */
            function resumeIframe(el) {
                if (!el || el.tagName !== 'IFRAME') return;
                if (el.getAttribute('data-was-playing') === 'true') {
                    const originalSrc = el.getAttribute('data-video-src');
                    if (originalSrc && el.src !== originalSrc) {
                        el.src = originalSrc;
                    }
                    el.removeAttribute('data-was-playing');
                }
            }

            /**
             * Pause ALL other managed videos except the given element.
             */
            function pauseAllExcept(activeEl) {
                document.querySelectorAll('.cc-managed-video').forEach(function(el) {
                    if (el !== activeEl) {
                        pauseVideo(el);
                    }
                });
            }

            // ── HTML5 <video> elements: single-play enforcement ──
            document.querySelectorAll('video.cc-managed-video').forEach(function(video) {
                video.addEventListener('play', function() {
                    pauseAllExcept(video);
                    activeVideoElement = video;
                });
            });

            // ── Iframes: detect click to play (user interaction) ──
            document.querySelectorAll('iframe.cc-managed-video').forEach(function(iframe) {
                // Wrap each iframe in a click-detection overlay
                const parent = iframe.parentElement;
                if (!parent) return;

                // Use a focus-based detection: when user clicks into iframe, it gains focus
                iframe.addEventListener('mouseenter', function() {
                    // Mark this iframe as the one the user is interacting with
                    window.__ccHoveredIframe = iframe;
                });
                iframe.addEventListener('mouseleave', function() {
                    if (window.__ccHoveredIframe === iframe) {
                        window.__ccHoveredIframe = null;
                    }
                });
            });

            // Detect when an iframe gains focus (user clicked play inside it)
            window.addEventListener('blur', function() {
                setTimeout(function() {
                    if (window.__ccHoveredIframe && document.activeElement === document.body) {
                        // An iframe just stole focus — this means user clicked play
                        const clickedIframe = window.__ccHoveredIframe;
                        pauseAllExcept(clickedIframe);
                        activeVideoElement = clickedIframe;
                    }
                }, 100);
            });

            // ── IntersectionObserver: auto-pause videos when scrolled out of view ──
            if ('IntersectionObserver' in window) {
                const videoObserver = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        const el = entry.target;
                        if (!entry.isIntersecting) {
                            // Video scrolled out of view — pause it
                            pauseVideo(el);
                            if (activeVideoElement === el) {
                                activeVideoElement = null;
                            }
                        } else {
                            // Video scrolled back into view — restore iframe src if it was playing
                            if (el.tagName === 'IFRAME') {
                                resumeIframe(el);
                            }
                        }
                    });
                }, {
                    threshold: 0.15 // At least 15% visible to be considered "in view"
                });

                document.querySelectorAll('.cc-managed-video').forEach(function(el) {
                    videoObserver.observe(el);
                });
            }

            // ── Page visibility: pause all when tab is hidden ──
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    document.querySelectorAll('.cc-managed-video').forEach(function(el) {
                        pauseVideo(el);
                    });
                    activeVideoElement = null;
                }
            });
        });
    </script>

    @yield('scripts')
</body>

</html>