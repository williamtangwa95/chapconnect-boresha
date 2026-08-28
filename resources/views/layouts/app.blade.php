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

    <link rel="stylesheet" type="text/css" href="{{ asset('css/Style.css') }}">
    @yield('styles')
</head>

<body class="{{ (Request::is('admin*') || Request::is('customer-care*') || Request::is('dashboard*')) ? 'admin-body' : '' }}">

    <!-- First-Time Visitor Typewriter Splash Loader Overlay -->
    @php
        $welcomeText = \App\Models\SystemSetting::get('welcome_text', 'Karibu sana ChapConnect...');
        $welcomeSpeed = (int) \App\Models\SystemSetting::get('welcome_typing_speed', 55);
        $welcomeDelay = (int) \App\Models\SystemSetting::get('welcome_delay', 300);
        $welcomeSound = \App\Models\SystemSetting::get('welcome_sound', '/sounds/welcome_default.wav');
    @endphp
    <div id="firstTimeLoaderOverlay" onmouseover="if(typeof playWelcomeSound==='function')playWelcomeSound(false)" onclick="if(typeof playWelcomeSound==='function')playWelcomeSound(true)" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%); z-index: 999999; flex-direction: column; justify-content: center; align-items: center; color: #ffffff; font-family: system-ui, -apple-system, sans-serif; opacity: 1; transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer;">
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
            <i class="bi bi-volume-up-fill" style="font-size: 1.1rem; color: #ffffff;"></i> <span id="welcomeAudioBtnText">Tap Anywhere to Enable Audio 🔊</span>
        </button>
        <audio id="welcomeAudioElement" src="{{ asset($welcomeSound) }}" preload="auto" playsinline style="display: none;"></audio>
    </div>

    <style>
    @keyframes blinkCursor {
        0%, 100% { opacity: 1; }
        50% { opacity: 0; }
    }
    @keyframes loaderPulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.15); opacity: 0.85; }
    }
    @keyframes pulseBtn {
        0%, 100% { transform: scale(1); box-shadow: 0 6px 20px rgba(99,102,241,0.5); }
        50% { transform: scale(1.06); box-shadow: 0 10px 25px rgba(56,189,248,0.7); }
    }
    </style>
    <nav class="nav">
        <div class="logo">
            <a href="{{ route('home') }}"><img src="{{ asset('logo.png') }}" alt="ChapConnect Logo" onerror="this.src='https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=100&auto=format&fit=crop'"></a>
            <div class="tname">
                <a href="{{ route('home') }}" style="text-decoration: none;">
                    <h1>ChapConnect</h1>
                </a>
                <div style="display: flex; gap: 4px; margin-top: 3px;">
                    <a href="https://wa.me/255710383352" target="_blank"
                        style="font-weight: 600; color: #ffffff; text-decoration: none; padding: 2px 7px; background: linear-gradient(135deg, #25d366 0%, #128c7e 100%); border-radius: 12px; font-size: 10px; letter-spacing: 0.2px; box-shadow: 0 2px 6px rgba(37, 211, 102, 0.3); border: 1px solid rgba(255, 255, 255, 0.3); transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 3px;"><i
                            class="bi bi-whatsapp" style="font-size: 11px; color: #ffffff;"></i> 0710383352</a>
                </div>
            </div>
        </div>
        <!-- Bootstrap Toggle Button for mobile -->
        <button class="nav-toggle" id="navToggleBtn" onclick="toggleMobileNav()" aria-label="Toggle navigation">
            <i class="bi bi-list"></i>
        </button>

        <div class="icon" id="navIconMenu">
            <div class="nav-mobile-list">
                @auth
                @if(in_array(auth()->user()->role, ['admin', 'customer_care']))
                @if(Request::is('admin*'))
                <a href="#dashboard" class="tab-link nav-mobile-link" data-tab="dashboard"><i class="bi bi-speedometer2" style="color: var(--secondary);"></i> Dashboard Overview</a>
                <a href="#talents" class="tab-link nav-mobile-link" data-tab="talents"><i class="bi bi-people-fill" style="color: var(--primary);"></i> Registered Talents</a>
                <a href="#categories" class="tab-link nav-mobile-link" data-tab="categories"><i class="bi bi-tags-fill" style="color: var(--accent);"></i> Manage Categories</a>
                <a href="#settings" class="tab-link nav-mobile-link" data-tab="settings"><i class="bi bi-person-gear" style="color: #0284c7;"></i> User Profile</a>
                <a href="#staff" class="tab-link nav-mobile-link" data-tab="staff"><i class="bi bi-person-plus-fill" style="color: #6366f1;"></i> Registered Staff</a>
                <a href="{{ route('customer-care.dashboard') }}" class="nav-mobile-link"><i class="bi bi-headset" style="color: #6366f1;"></i> Support Portal</a>
                <a href="{{ route('home') }}" class="nav-mobile-link"><i class="bi bi-house-door" style="color: var(--primary);"></i> Public Directory</a>
                @elseif(Request::is('customer-care*'))
                <a href="{{ route('customer-care.dashboard') }}" class="nav-mobile-link"><i class="bi bi-headset" style="color: #6366f1;"></i> Support Issues Roster</a>
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="nav-mobile-link"><i class="bi bi-speedometer2" style="color: var(--secondary);"></i> Super Admin Panel</a>
                @endif
                <a href="{{ route('home') }}" class="nav-mobile-link"><i class="bi bi-house-door" style="color: var(--primary);"></i> Public Directory</a>
                @else
                <a href="{{ route('home') }}" class="nav-mobile-link"><i class="bi bi-house-door" style="color: var(--primary);"></i> Home</a>
                <a href="{{ route('customer-care.dashboard') }}" class="nav-mobile-link"><i class="bi bi-headset" style="color: #6366f1;"></i> Support Portal</a>
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="nav-mobile-link"><i class="bi bi-speedometer2" style="color: var(--secondary);"></i> Admin Dashboard</a>
                @endif
                @endif
                @else
                <a href="{{ route('home') }}" class="nav-mobile-link"><i class="bi bi-house-door" style="color: var(--primary);"></i> Home</a>
                <a href="{{ route('dashboard') }}" class="nav-mobile-link"><i class="bi bi-speedometer2" style="color: var(--secondary);"></i> Dashboard Overview</a>
                <a href="{{ route('dashboard.photos') }}" class="nav-mobile-link"><i class="bi bi-camera-fill" style="color: var(--primary);"></i> Manage Photos</a>
                <a href="{{ route('dashboard.videos') }}" class="nav-mobile-link"><i class="bi bi-camera-video-fill" style="color: var(--primary);"></i> Manage Videos</a>
                <a href="{{ route('dashboard.news') }}" class="nav-mobile-link"><i class="bi bi-newspaper" style="color: var(--primary);"></i> Manage News</a>
                @endif
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-mobile-link"><i class="bi bi-box-arrow-right" style="color: #ef4444;"></i> Logout</a>
                @else
                <a href="{{ route('home') }}" class="nav-mobile-link"><i class="bi bi-house-door-fill" style="color: var(--primary);"></i> Home</a>
                <a href="#" onclick="event.preventDefault(); handlePwaInstallClick();" class="nav-mobile-link" style="background: rgba(16,185,129,0.08); color: #059669 !important; font-weight: 700;"><i class="bi bi-download" style="color: #10b981;"></i> Install ChapConnect App</a>
                <a href="{{ route('login') }}" class="nav-mobile-link"><i class="bi bi-box-arrow-in-right" style="color: var(--primary);"></i> Login</a>
                <a href="{{ route('register') }}" class="nav-mobile-link"><i class="bi bi-person-plus" style="color: var(--primary);"></i> Register</a>
                @endauth
                <a href="tel:0710383352" class="nav-mobile-link"><i class="bi bi-telephone-fill" style="color: #2563eb;"></i> Call: 0710383352</a>
                <a href="https://wa.me/255710383352" target="_blank" class="nav-mobile-link"><i class="bi bi-whatsapp" style="color: #10b981;"></i> WhatsApp: 0710383352</a>
            </div>
            <div class="nav-auth">
                @auth
                <!-- Sidebar Toggle Button for Mobile -->
                <button type="button" onclick="$('#adminSidebar').toggleClass('mobile-open');" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: #fff; border-radius: 20px; font-weight: 700; padding: 6px 14px; font-size: 0.82rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; margin-right: 6px;">
                    <i class="bi bi-layout-sidebar"></i> Menu
                </button>
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

                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-btn nav-btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                @else
                <a href="{{ route('home') }}" class="nav-btn nav-btn-home"><i class="bi bi-house-door-fill" style="color: #6366f1;"></i> Home</a>
                <a href="{{ route('login') }}" class="nav-btn nav-btn-login"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                <a href="{{ route('register') }}" class="nav-btn nav-btn-register"><i class="bi bi-person-plus-fill"></i> Register</a>
                @endauth
            </div>
            @yield('search_bar')
        </div>
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
                    <img src="{{ asset(auth()->user()->profile_image) }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">
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
                <a href="{{ route('customer-care.dashboard') }}" class="sidebar-link {{ Request::is('customer-care*') ? 'active' : '' }}">
                    <i class="bi bi-headset"></i> <span>Customer Care</span>
                </a>

                <div class="sidebar-group-label">SETTINGS & PROFILES</div>
                <a href="{{ Request::is('admin*') ? '#settings' : route('admin.dashboard') . '#settings' }}" class="sidebar-link {{ Request::is('admin*') ? 'tab-link' : '' }}" data-tab="settings">
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
                <a href="{{ Request::is('admin*') ? '#staff' : route('admin.dashboard') . '#staff' }}" class="sidebar-link {{ Request::is('admin*') ? 'tab-link' : '' }}" data-tab="staff">
                    <i class="bi bi-person-plus-fill"></i> <span>Registered Staff</span>
                </a>

                <div class="sidebar-group-label">OPERATIONS & SUPPORT</div>
                <a href="{{ Request::is('admin*') ? '#customer-care' : route('customer-care.dashboard') }}" class="sidebar-link {{ Request::is('admin*') ? 'tab-link' : '' }}" data-tab="customer-care">
                    <i class="bi bi-headset"></i> <span>Customer Care Portal</span>
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
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> <span>Dashboard Overview</span>
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
                setTimeout(function() {
                    dismissToast(toast);
                }, 4000); // Automatically disappears after 4 seconds
            });
        });
    </script>
    <!-- Notification Sound Chime Player -->
    <audio id="chapconnect-notification-audio" src="{{ \App\Models\SystemSetting::get('notification_sound', 'https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3') }}" preload="auto"></audio>

    @auth
    <script>
        let lastUnreadCount = 0;

        function fetchNotifications() {
            $.ajax({
                url: "{{ route('notifications.unread') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    const count = data.count || 0;
                    const $badge = $('#notifBadge');
                    const $list = $('#notifList');

                    if (count > 0) {
                        $badge.text(count).show();
                        if (count > lastUnreadCount && lastUnreadCount !== 0) {
                            const audio = document.getElementById('chapconnect-notification-audio');
                            if (audio) {
                                audio.play().catch(e => console.log('Audio playback policy', e));
                            }
                        }
                    } else {
                        $badge.hide();
                    }

                    lastUnreadCount = count;

                    if (data.notifications && data.notifications.length > 0) {
                        let html = '';
                        data.notifications.forEach(function(n) {
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
                        $list.html('<div style="padding: 20px; text-align: center; color: #94a3b8; font-size: 0.85rem;"><i class="bi bi-check-circle" style="font-size: 1.2rem; display: block; margin-bottom: 4px;"></i> No new notifications</div>');
                    }
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
            fetchNotifications();
            setInterval(fetchNotifications, 15000);

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
            document.addEventListener("DOMContentLoaded", function() {
                var loaderOverlay = document.getElementById('firstTimeLoaderOverlay');
                var typewriterEl = document.getElementById('typewriterText');
                var progressBar = document.getElementById('loaderProgressBar');
                if (!loaderOverlay || !typewriterEl) return;

                // Lock scrolling & show loader overlay
                loaderOverlay.style.display = 'flex';
                document.body.style.overflow = 'hidden';

                var textToType = @json($welcomeText);
                var charIndex = 0;
                var typingSpeed = {{ $welcomeSpeed }};
                var welcomeDelay = {{ $welcomeDelay }};
                var welcomeSoundUrl = @json($welcomeSound);

                var audioEl = document.getElementById('welcomeAudioElement');
                var welcomeAudio = audioEl || (welcomeSoundUrl ? new Audio(welcomeSoundUrl) : null);
                var audioPlayed = false;

                window.playWelcomeSound = function(userAction) {
                    if (audioPlayed && !userAction) return;

                    var audioEl = document.getElementById('welcomeAudioElement');
                    var audioInstance = audioEl || (welcomeSoundUrl ? new Audio(welcomeSoundUrl) : null);

                    if (audioInstance) {
                        try {
                            audioInstance.currentTime = 0;
                            audioInstance.volume = 1.0;
                            var p = audioInstance.play();
                            if (p !== undefined) {
                                p.then(function() {
                                    audioPlayed = true;
                                    var btnText = document.getElementById('welcomeAudioBtnText');
                                    if (btnText) btnText.textContent = "Welcome Audio Playing ♪";
                                }).catch(function(e) {
                                    speakFemaleVoiceFallback();
                                });
                            }
                        } catch(e) {
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
                            utterance.pitch = 1.2;
                            var voices = window.speechSynthesis.getVoices();
                            var femaleVoice = voices.find(function(v) {
                                return v.name.toLowerCase().includes('female') || 
                                       v.name.toLowerCase().includes('zira') || 
                                       v.name.toLowerCase().includes('samantha') || 
                                       v.name.toLowerCase().includes('google us english');
                            });
                            if (femaleVoice) utterance.voice = femaleVoice;
                            window.speechSynthesis.speak(utterance);
                            audioPlayed = true;
                        } catch(err) {}
                    }
                }

                function triggerHoverSound() {
                    if (!audioPlayed) {
                        playWelcomeSound(false);
                    }
                }

                ['mousemove', 'pointermove', 'mouseover', 'pointerover', 'mouseenter', 'touchstart', 'pointerdown', 'click'].forEach(function(evt) {
                    window.addEventListener(evt, triggerHoverSound, { passive: true });
                    document.addEventListener(evt, triggerHoverSound, { passive: true });
                    if (loaderOverlay) {
                        loaderOverlay.addEventListener(evt, triggerHoverSound, { passive: true });
                    }
                });

                function typeNextChar() {
                    if (charIndex < textToType.length) {
                        typewriterEl.textContent += textToType.charAt(charIndex);
                        charIndex++;
                        var progressPercent = Math.min(100, Math.round((charIndex / textToType.length) * 88));
                        if (progressBar) progressBar.style.width = progressPercent + '%';
                        setTimeout(typeNextChar, typingSpeed);
                    } else {
                        if (progressBar) progressBar.style.width = '100%';
                        setTimeout(dismissIntroLoader, 700);
                    }
                }

                function dismissIntroLoader() {
                    loaderOverlay.style.opacity = '0';
                    document.body.style.overflow = '';
                    sessionStorage.setItem('chap_first_visit_done', 'true');
                    if (welcomeAudio) {
                        welcomeAudio.pause();
                        welcomeAudio.currentTime = 0;
                    }
                    setTimeout(function() {
                        loaderOverlay.style.display = 'none';
                    }, 600);
                }

                // Kickoff welcome sound and typing after admin delay timeout
                setTimeout(function() {
                    playWelcomeSound();
                    typeNextChar();
                }, welcomeDelay);
            });
        }
    })();
    </script>

    @if(Request::is('/'))
    <!-- Floating Bottom-Right Corner Install App Link Banner (Home Page Only) -->
    <div id="pwaInstallBanner" style="display: flex; position: fixed; bottom: 25px; right: 25px; z-index: 999999; background: #0f172a; border: 1px solid rgba(16,185,129,0.5); border-radius: 30px; padding: 10px 18px; box-shadow: 0 10px 30px rgba(0,0,0,0.35); color: #ffffff; align-items: center; gap: 10px; backdrop-filter: blur(10px);">
        <img src="{{ asset('logo.png') }}" alt="ChapConnect App" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 1px solid rgba(255,255,255,0.3); flex-shrink: 0;" onerror="this.src='https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=100&auto=format&fit=crop'">
        <a href="#" onclick="event.preventDefault(); handlePwaInstallClick();" style="color: #ffffff; text-decoration: none; font-weight: 700; font-size: 0.86rem; display: inline-flex; align-items: center; gap: 6px;">
            <i class="bi bi-download" style="color: #10b981; font-size: 1rem;"></i> Install ChapConnect App
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
        }
    }

    function closePwaGuideModal() {
        const modal = document.getElementById('pwaGuideModal');
        if (modal) modal.style.display = 'none';
    }
    </script>

    @yield('scripts')
</body>

</html>