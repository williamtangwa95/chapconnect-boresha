<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ChapConnect - Connecting Talents')</title>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- DataTables Online CDN CSS + Responsive Extension -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/Style.css') }}">
    @yield('styles')
</head>

<body class="{{ (Request::is('admin*') || Request::is('customer-care*')) ? 'admin-body' : '' }}">
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
                <a href="{{ route('dashboard') }}" class="nav-mobile-link"><i class="bi bi-speedometer2" style="color: var(--secondary);"></i> Dashboard Panel</a>
                @endif
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-mobile-link"><i class="bi bi-box-arrow-right" style="color: #ef4444;"></i> Logout</a>
                @else
                <a href="{{ route('login') }}" class="nav-mobile-link"><i class="bi bi-box-arrow-in-right" style="color: var(--primary);"></i> Login</a>
                <a href="{{ route('register') }}" class="nav-mobile-link"><i class="bi bi-person-plus" style="color: var(--primary);"></i> Register</a>
                @endauth
                <a href="tel:0710383352" class="nav-mobile-link"><i class="bi bi-telephone-fill" style="color: #2563eb;"></i> Call: 0710383352</a>
                <a href="https://wa.me/255710383352" target="_blank" class="nav-mobile-link"><i class="bi bi-whatsapp" style="color: #10b981;"></i> WhatsApp: 0710383352</a>
            </div>
            <div class="nav-auth">
                @auth
                @if(in_array(auth()->user()->role, ['admin', 'customer_care']))
                <!-- Sidebar Toggle Button for Mobile -->
                <!-- <button type="button" onclick="$('#adminSidebar').toggleClass('mobile-open');" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: #fff; border-radius: 20px; font-weight: 700; padding: 6px 14px; font-size: 0.82rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="bi bi-layout-sidebar"></i> Navigation Menu
                </button> -->
                <span style="font-weight: 700; font-size: 0.84rem; color: #fff; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2); padding: 6px 14px; border-radius: 20px; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="bi bi-person-circle" style="color: #38bdf8;"></i> {{ auth()->user()->name }}
                </span>
                @else
                <a href="{{ route('dashboard') }}" class="nav-btn nav-btn-login"><i class="bi bi-speedometer2"></i> Dashboard</a>
                @endif
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
    @if(Request::is('admin*') || Request::is('customer-care*'))
    <div class="admin-layout-container">
        <!-- Sidebar Navigation -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-sidebar-header">
                <div class="sidebar-user-avatar">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <div class="sidebar-user-info">
                    <span class="sidebar-user-name">{{ auth()->user()->name }}</span>
                    <span class="sidebar-user-role">{{ auth()->user()->role === 'admin' ? 'Super Administrator' : 'Customer Care' }}</span>
                </div>
            </div>

            <div class="admin-sidebar-nav">
                @if(auth()->user()->role === 'customer_care')
                <!-- <div class="sidebar-group-label">MAIN CONTROL</div> -->
                <!-- <a href="{{ route('customer-care.dashboard') }}" class="sidebar-link {{ Request::is('customer-care*') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> <span>View Dashboard</span>
                </a> -->

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
                @else
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
                <a href="{{ Request::is('admin*') ? '#customer-care' : route('customer-care.dashboard') }}" class="sidebar-link {{ Request::is('admin*') ? 'tab-link' : 'active' }}" data-tab="customer-care">
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

    @yield('scripts')
</body>

</html>