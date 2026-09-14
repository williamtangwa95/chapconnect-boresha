@extends('layouts.app')

@section('title', 'ChapConnect - Fast Finder')

@section('styles')
<style>
    /* Widen content container and minimize margins/paddings */
    body>.menu {
        max-width: 1550px !important;
        width: 98% !important;
        padding: 6px 12px !important;
        margin: 8px auto !important;
    }

    .main {
        max-width: 1550px !important;
        width: 98% !important;
        padding: 20px 0 !important;
        margin: 0 auto !important;
    }

    /* Grid overrides with extremely narrow gutter spacing */
    .row {
        display: flex !important;
        flex-wrap: wrap !important;
        margin-right: -4px !important;
        margin-left: -4px !important;
        width: calc(100% + 8px) !important;
    }

    .col-9 {
        flex: 0 0 75% !important;
        max-width: 75% !important;
        padding-right: 12px !important;
        padding-left: 4px !important;
        max-height: calc(100vh - 120px) !important;
        overflow-y: auto !important;
    }

    /* Scrollbar styling for the profiles column */
    .col-9::-webkit-scrollbar {
        width: 6px !important;
    }

    .col-9::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.02) !important;
        border-radius: 10px !important;
    }

    .col-9::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.1) !important;
        border-radius: 10px !important;
    }

    .col-9::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.2) !important;
    }

    .col-3 {
        flex: 0 0 25% !important;
        max-width: 25% !important;
        padding-right: 4px !important;
        padding-left: 4px !important;
    }

    .media-preview-sidebar {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 16px;
        box-shadow: var(--shadow);
        border: 1px solid rgba(255, 255, 255, 0.6);
        position: sticky;
        top: 90px;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
    }

    .media-preview-title {
        position: sticky !important;
        top: -16px !important;
        background: var(--card-bg) !important;
        z-index: 10 !important;
        margin-top: -16px !important;
        padding-top: 16px !important;
        padding-bottom: 12px !important;
        margin-bottom: 16px !important;
        border-bottom: 2px solid var(--border-color) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 15px !important;
    }

    .media-search-container input:focus {
        border-color: #f59e0b !important;
        background: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15) !important;
    }

    /* Mobile search bar - hidden on desktop */
    .mobile-search-form {
        display: none;
    }

    @media (max-width: 680px) {
        .mobile-search-form {
            display: block !important;
            padding: 6px 8px 2px 8px;
            width: 100%;
            box-sizing: border-box;
        }

        .mobile-search-inner {
            position: relative;
            display: flex;
            align-items: center;
            background: #ffffff;
            border-radius: 30px;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            transition: box-shadow 0.2s, border-color 0.2s;
            width: 100%;
            box-sizing: border-box;
        }

        .mobile-search-inner:focus-within {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }

        .mobile-search-icon {
            position: absolute;
            left: 14px;
            color: #94a3b8;
            font-size: 14px;
            pointer-events: none;
            z-index: 2;
        }

        .mobile-search-input {
            flex: 1 1 auto;
            min-width: 0;
            width: 0;
            border: none;
            outline: none;
            padding: 10px 10px 10px 38px;
            font-size: 13px;
            font-family: inherit;
            color: var(--text-main);
            background: transparent;
            -webkit-appearance: none;
            appearance: none;
            box-sizing: border-box;
        }

        .mobile-search-btn {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border: none;
            color: #ffffff !important;
            padding: 0 14px;
            height: 42px;
            width: 48px;
            min-width: 48px;
            cursor: pointer;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s, transform 0.15s;
            flex-shrink: 0;
            border-radius: 0 25px 25px 0;
            -webkit-appearance: none;
            appearance: none;
            box-sizing: border-box;
        }

        .mobile-search-btn:hover {
            background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
        }

        .mobile-search-btn svg,
        .mobile-search-btn i {
            color: #ffffff !important;
            fill: currentColor;
            display: inline-block;
            vertical-align: middle;
            line-height: 1;
            font-size: 16px;
        }
    }

    /* Floating indicator button styles */
    .sidebar-toggle-btn {
        position: fixed;
        right: 0;
        top: 55%;
        transform: translateY(-50%);
        z-index: 1000;
        background: #f59e0b;
        color: #ffffff;
        padding: 14px 6px 14px 10px;
        border-radius: 20px 0 0 20px;
        box-shadow: -2px 4px 15px rgba(245, 158, 11, 0.4);
        cursor: pointer;
        display: none;
        align-items: center;
        gap: 6px;
        writing-mode: vertical-rl;
        text-orientation: mixed;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: right 0.3s ease-in-out, background 0.2s;
        border: none !important;
        outline: none !important;
    }

    .sidebar-toggle-btn:hover {
        background: #d97706;
    }

    .sidebar-toggle-btn i {
        font-size: 14px;
        writing-mode: horizontal-tb;
        display: inline-block;
        animation: pulseArrow 1.5s infinite ease-in-out;
    }

    .drawer-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 1040;
        display: none;
    }

    @keyframes pulseArrow {

        0%,
        100% {
            transform: translateX(0);
        }

        50% {
            transform: translateX(-4px);
        }
    }

    /* Mobile Responsive slide-out configurations */
    @media (max-width: 991.98px) {
        .sidebar-toggle-btn {
            display: flex !important;
        }

        .row {
            width: 100% !important;
            margin-right: 0 !important;
            margin-left: 0 !important;
        }

        .col-9 {
            flex: 0 0 100% !important;
            max-width: 100% !important;
            padding-right: 0 !important;
            padding-left: 0 !important;
            max-height: none !important;
            overflow-y: visible !important;
        }

        .col-3 {
            position: fixed !important;
            top: 0 !important;
            right: -100% !important;
            /* slide off screen full width */
            width: 100% !important;
            max-width: 100% !important;
            height: 100vh !important;
            z-index: 1050 !important;
            background: var(--card-bg) !important;
            box-shadow: none !important;
            transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            display: block !important;
            padding: 0 !important;
        }

        .col-3.open {
            right: 0 !important;
        }

        .media-preview-sidebar {
            height: 100% !important;
            max-height: 100vh !important;
            border-radius: 0 !important;
            border: none !important;
            overflow-y: auto !important;
            position: relative !important;
            top: 0 !important;
            box-shadow: none !important;
        }

        .drawer-close-btn {
            display: inline-flex !important;
        }

        .header-icon-desktop {
            display: none !important;
        }
    }
</style>
@endsection

@section('search_bar')
<form action="{{ route('home') }}" method="GET" class="Search">
    @if(request('category'))
    <input type="hidden" name="category" value="{{ request('category') }}">
    @endif
    <input class="Srch" type="search" name="search" value="{{ request('search') }}" placeholder="{{ __('Type to Search here') }}">
    <button type="submit" class="btn" title="{{ __('Search') }}"><i class="bi bi-search"></i></button>
</form>
@endsection

@section('content')
@if(\App\Services\MaintenanceService::isEnabled() || \App\Services\MaintenanceService::isLoginRestrictedFlag() || \App\Services\MaintenanceService::isRegisterRestrictedFlag() || \App\Services\MaintenanceService::isConnectRestrictedFlag())
<div class="maintenance-marquee-bar">
    <div class="maintenance-badge">
        <i class="bi bi-megaphone-fill maintenance-icon"></i>
        <span class="badge-text-desktop">{{ __('GENERAL NOTICE') }}</span>
        <span class="badge-text-mobile">{{ __('NOTICE') }}</span>
    </div>
    <div class="maintenance-marquee-content">
        <marquee behavior="scroll" direction="left" scrollamount="6" onmouseover="this.stop();" onmouseout="this.start();">
            📢 {{ \App\Services\MaintenanceService::getMessage() }}
        </marquee>
    </div>
</div>
<style>
    .maintenance-marquee-bar {
        max-width: 1550px;
        width: 98%;
        margin: 10px auto 14px auto;
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 50%, #991b1b 100%);
        color: #ffffff;
        padding: 10px 16px;
        border-radius: 12px;
        box-shadow: 0 4px 18px rgba(220, 38, 38, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        gap: 14px;
        overflow: hidden;
    }

    .maintenance-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        color: #dc2626;
        padding: 5px 12px;
        border-radius: 20px;
        flex-shrink: 0;
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.6px;
        border: 1px solid rgba(255, 255, 255, 0.9);
        text-transform: uppercase;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .maintenance-icon {
        color: #dc2626;
        font-size: 1rem;
        animation: wrenchPulse 1.8s infinite ease-in-out;
    }

    .badge-text-desktop {
        display: inline;
    }

    .badge-text-mobile {
        display: none;
    }

    .maintenance-marquee-content {
        flex: 1;
        overflow: hidden;
        white-space: nowrap;
        min-width: 0;
    }

    .maintenance-marquee-content marquee {
        font-weight: 600;
        font-size: 0.92rem;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
        vertical-align: middle;
    }

    @keyframes wrenchPulse {

        0%,
        100% {
            transform: scale(1) rotate(0deg);
        }

        50% {
            transform: scale(1.15) rotate(-10deg);
        }
    }

    @media (max-width: 680px) {
        .maintenance-marquee-bar {
            padding: 6px 10px;
            margin: 6px auto 10px auto;
            gap: 8px;
            border-radius: 10px;
        }

        .maintenance-badge {
            padding: 3px 8px;
            gap: 5px;
            font-size: 0.7rem;
        }

        .maintenance-icon {
            font-size: 0.85rem;
        }

        .badge-text-desktop {
            display: none;
        }

        .badge-text-mobile {
            display: inline;
        }

        .maintenance-marquee-content marquee {
            font-size: 0.84rem;
        }
    }
</style>
@endif

<!-- Category Menu Navigation -->
<div class="menu">
    <!-- Mobile Select2 category picker (desktop only) -->
    <div class="menu-filter-wrap">
        <select id="menuSelect" class="menu-select">
            <option value="">🔍 {{ __('Browse Categories...') }}</option>
            <option value="{{ route('home', ['search' => request('search')]) }}" {{ $currentCategory === 'all' ? 'selected' : '' }}>
                {{ __('All Talents') }} ({{ $totalTalents }})
            </option>
            @foreach($categories as $slug => $label)
            <option value="{{ route('home', ['category' => $slug, 'search' => request('search')]) }}" {{ $currentCategory === $slug ? 'selected' : '' }}>
                {{ __($label) }} ({{ $categoryCounts[$slug] ?? 0 }})
            </option>
            @endforeach
        </select>
    </div>

    <!-- Mobile Search Bar (shown below nav, above categories) -->
    <form action="{{ route('home') }}" method="GET" class="mobile-search-form" id="mobileSearchBar">
        @if(request('category'))
        <input type="hidden" name="category" value="{{ request('category') }}">
        @endif
        <div class="mobile-search-inner">
            <i class="bi bi-search mobile-search-icon"></i>
            <input class="mobile-search-input" type="search" name="search" value="{{ request('search') }}" placeholder="{{ __('Search talents, categories...') }}">
            <button type="submit" class="mobile-search-btn" aria-label="{{ __('Search') }}" title="{{ __('Search') }}">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </form>

    <!-- Category Links (horizontal scroll on all screens with forward/backward arrow buttons) -->
    <div class="category-scroll-wrapper">
        <button type="button" class="category-arrow-btn prev-arrow" id="categoryScrollPrev" aria-label="{{ __('Previous') }}" title="{{ __('Previous') }}">
            <i class="bi bi-chevron-left"></i>
        </button>

        <div class="category-scroll-track" id="categoryScrollTrack">
            <ul id="menuList">
                <li class="{{ $currentCategory === 'all' ? 'active' : '' }}">
                    <a href="{{ route('home', ['search' => request('search')]) }}">
                        {{ __('All Talents') }} <span class="filter-badge" style="background: rgba(255,255,255,0.2); padding: 2px 6px; border-radius: 10px; font-size: 11px;">{{ $totalTalents }}</span>
                    </a>
                </li>
                @foreach($categories as $slug => $label)
                <li class="{{ $currentCategory === $slug ? 'active' : '' }}">
                    <a href="{{ route('home', ['category' => $slug, 'search' => request('search')]) }}">
                        {{ __($label) }} <span class="filter-badge" style="background: rgba(255,255,255,0.2); padding: 2px 6px; border-radius: 10px; font-size: 11px;">{{ $categoryCounts[$slug] ?? 0 }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>

        <button type="button" class="category-arrow-btn next-arrow" id="categoryScrollNext" aria-label="{{ __('Next') }}" title="{{ __('Next') }}">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>
</div>

<main class="main">
    <div class="row" style="width: 100%;">
        <!-- Profile View Column (Left - col-9) -->
        <div class="col-9">
            <div class="talent-grid" style="padding-top: 0;">
                @include('partials.talent-card-items', ['talents' => $talents])
            </div>

            <!-- AJAX Load More Talent Cards -->
            @if(count($talents) >= 12 || $talents->hasPages())
            <div id="viewMoreTalentsContainer" style="text-align: center; padding: 25px 10px 30px 10px; width: 100%;">
                <button type="button" id="loadMoreTalentsBtn" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); color: #ffffff; border: none; padding: 12px 32px; border-radius: 30px; font-weight: 700; font-size: 0.95rem; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 16px rgba(79, 70, 229, 0.35); transition: all 0.25s ease-in-out;">
                    <i class="bi bi-arrow-down-circle-fill" id="loadMoreTalentsIcon" style="font-size: 1.1rem;"></i>
                    <span id="loadMoreTalentsText">{{ __('View More') }}</span>
                </button>
            </div>
            @endif
        </div>

        <!-- Backdrop overlay for mobile drawer -->
        <div class="drawer-backdrop"></div>

        <!-- Photos & Videos Preview Column (Right - col-3) -->
        <div class="col-3">
            <div class="media-preview-sidebar">
                <h3 class="media-preview-title">
                    <span style="font-size: 1.15rem; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px; white-space: nowrap;">
                        <!-- Close Arrow Button for Mobile Drawer -->
                        <button type="button" id="closeDrawerBtn" class="drawer-close-btn" style="background: none; border: none; padding: 0; margin-right: 4px; color: var(--text-main); cursor: pointer; display: none; align-items: center; justify-content: center;">
                            <i class="bi bi-arrow-left" style="font-size: 1.4rem; font-weight: bold;"></i>
                        </button>
                        <i class="bi bi-camera-reels-fill header-icon-desktop"></i> {{ __('Post View') }}
                    </span>
                    <!-- add live search to filter the contents -->
                    <div class="media-search-container" style="position: relative; width: 170px;">
                        <i class="bi bi-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 12px;"></i>
                        <input type="text" id="mediaSearch" placeholder="{{ __('Search...') }}" style="width: 100%; padding: 6px 10px 6px 30px; border: 1px solid #cbd5e1; border-radius: 30px; background: #f8fafc; color: var(--text-main); font-family: inherit; font-size: 12px; outline: none; transition: all 0.2s ease-in-out;">
                    </div>
                </h3>



                <div class="media-feed-list">
                    <div id="no-media-search-results" style="text-align: center; padding: 40px 10px; color: var(--text-muted); display: none;">
                        <i class="bi bi-search" style="font-size: 2.5rem; color: #cbd5e1; display: block; margin-bottom: 8px;"></i>
                        <p style="font-size: 0.88rem; font-weight: 500; margin: 0;">{{ __('No matching previews found.') }}</p>
                    </div>
                    @include('partials.media-feed-items', ['recentMedia' => $recentMedia])

                    @if(count($recentMedia) >= 20)
                    <div id="viewMoreMediaContainer" style="text-align: center; padding: 20px 10px 15px 10px;">
                        <button type="button" id="loadMoreMediaBtn" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #ffffff; border: none; padding: 10px 24px; border-radius: 30px; font-weight: 600; font-size: 0.88rem; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.3); transition: all 0.25s ease-in-out;">
                            <i class="bi bi-arrow-down-circle-fill" id="loadMoreMediaIcon" style="font-size: 1rem;"></i>
                            <span id="loadMoreMediaText">{{ __('View More') }}</span>
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Floating Swipe Indicator / Button for Mobile Previews -->
<button id="media-sidebar-toggle" class="sidebar-toggle-btn" aria-label="Open Previews">
    <i class="bi bi-chevron-left"></i>
    <span>All Post</span>
</button>
@endsection

@section('scripts')
<script>
    // Translation strings passed from Blade for dynamic JS buttons
    const i18n = {
        liked: "{{ __('Liked ❤️') }}",
        like: "{{ __('Like') }} 🤍",
        following: "{{ __('Following') }}",
        followers: "{{ __('Followers') }}"
    };

    $(document).ready(function() {
        $('#menuSelect').select2({
            width: '100%',
            placeholder: '🔍 Browse Categories...',
            minimumResultsForSearch: 0
        });

        $('#menuSelect').on('select2:select', function() {
            var url = $(this).val();
            if (url) window.location.href = url;
        });

        // ── Category Horizontal Scroll Arrow Buttons ──
        const catTrack = document.getElementById('categoryScrollTrack');
        const prevBtn = document.getElementById('categoryScrollPrev');
        const nextBtn = document.getElementById('categoryScrollNext');

        if (catTrack && prevBtn && nextBtn) {
            function updateArrowButtons() {
                const maxScrollLeft = catTrack.scrollWidth - catTrack.clientWidth;
                if (maxScrollLeft <= 5) {
                    prevBtn.style.opacity = '0.25';
                    prevBtn.style.pointerEvents = 'none';
                    nextBtn.style.opacity = '0.25';
                    nextBtn.style.pointerEvents = 'none';
                } else {
                    if (catTrack.scrollLeft <= 5) {
                        prevBtn.style.opacity = '0.25';
                        prevBtn.style.pointerEvents = 'none';
                    } else {
                        prevBtn.style.opacity = '1';
                        prevBtn.style.pointerEvents = 'auto';
                    }

                    if (catTrack.scrollLeft >= maxScrollLeft - 5) {
                        nextBtn.style.opacity = '0.25';
                        nextBtn.style.pointerEvents = 'none';
                    } else {
                        nextBtn.style.opacity = '1';
                        nextBtn.style.pointerEvents = 'auto';
                    }
                }
            }

            prevBtn.addEventListener('click', function() {
                catTrack.scrollBy({
                    left: -240,
                    behavior: 'smooth'
                });
            });

            nextBtn.addEventListener('click', function() {
                catTrack.scrollBy({
                    left: 240,
                    behavior: 'smooth'
                });
            });

            catTrack.addEventListener('scroll', updateArrowButtons, {
                passive: true
            });
            window.addEventListener('resize', updateArrowButtons, {
                passive: true
            });

            // Initial check & auto-scroll active category into view
            updateArrowButtons();
            const activeItem = catTrack.querySelector('li.active');
            if (activeItem) {
                const itemLeft = activeItem.offsetLeft - catTrack.offsetLeft;
                const itemWidth = activeItem.offsetWidth;
                const trackWidth = catTrack.clientWidth;
                catTrack.scrollTo({
                    left: Math.max(0, itemLeft - (trackWidth / 2) + (itemWidth / 2)),
                    behavior: 'smooth'
                });
                setTimeout(updateArrowButtons, 350);
            }
        }

        // Mobile Sidebar Drawer Control Functions
        function openMediaSidebar() {
            $('.col-3').addClass('open');
            $('.drawer-backdrop').fadeIn(200);
            $('#media-sidebar-toggle').css('right', '-60px'); // hide toggle button
        }

        function closeMediaSidebar() {
            $('.col-3').removeClass('open');
            $('.drawer-backdrop').fadeOut(200);
            $('#media-sidebar-toggle').css('right', '0'); // show toggle button
        }

        // Bind clicks
        $('#media-sidebar-toggle').on('click', openMediaSidebar);
        $('.drawer-backdrop').on('click', closeMediaSidebar);
        $('#closeDrawerBtn').on('click', closeMediaSidebar);

        // Touch Swipe Gesture Detection
        let touchStartX = 0;
        let touchStartY = 0;
        let swipeIgnored = false;

        document.addEventListener('touchstart', function(e) {
            touchStartX = e.touches[0].clientX;
            touchStartY = e.touches[0].clientY;

            // Check if touch started inside a horizontally scrollable element (categories list, etc.)
            // to avoid conflicting with their native horizontal scroll
            let el = e.target;
            swipeIgnored = false;
            while (el && el !== document.body) {
                // Ignore swipes that start inside the category menu or any overflow-x scroll area
                // NOTE: We do NOT ignore .col-3 / .media-feed-list so swipe-right-to-close still works
                if (
                    el.id === 'menuList' ||
                    el.classList.contains('menu') ||
                    el.classList.contains('menu-filter-wrap') ||
                    el.classList.contains('mobile-search-form') ||
                    (el.scrollWidth > el.clientWidth && getComputedStyle(el).overflowX !== 'hidden')
                ) {
                    swipeIgnored = true;
                    break;
                }
                el = el.parentElement;
            }
        }, {
            passive: true
        });

        document.addEventListener('touchend', function(e) {
            if (!touchStartX || !touchStartY || swipeIgnored) {
                touchStartX = 0;
                touchStartY = 0;
                swipeIgnored = false;
                return;
            }

            let touchEndX = e.changedTouches[0].clientX;
            let touchEndY = e.changedTouches[0].clientY;

            let diffX = touchStartX - touchEndX;
            let diffY = touchStartY - touchEndY;

            // Only detect horizontal swipe if horizontal displacement is greater than vertical displacement
            if (Math.abs(diffX) > Math.abs(diffY)) {
                if (Math.abs(diffX) > 60) {
                    if (diffX > 0) {
                        // Swiped left (reveal drawer) — only if drawer is closed
                        if (!$('.col-3').hasClass('open')) {
                            openMediaSidebar();
                        }
                    } else {
                        // Swiped right (hide drawer) — only if drawer is open
                        if ($('.col-3').hasClass('open')) {
                            closeMediaSidebar();
                        }
                    }
                }
            }

            // Reset touch coordinates
            touchStartX = 0;
            touchStartY = 0;
            swipeIgnored = false;
        }, {
            passive: true
        });

        // ── Dedicated swipe-right-to-close listener on the Post View drawer ──
        // This runs even when the touch starts inside the vertically-scrollable feed list.
        (function() {
            const drawer = document.querySelector('.col-3');
            if (!drawer) return;

            let drawerTouchStartX = 0;
            let drawerTouchStartY = 0;
            let drawerSwipeActive = false;

            drawer.addEventListener('touchstart', function(e) {
                drawerTouchStartX = e.touches[0].clientX;
                drawerTouchStartY = e.touches[0].clientY;
                drawerSwipeActive = true;
            }, {
                passive: true
            });

            drawer.addEventListener('touchmove', function(e) {
                if (!drawerSwipeActive) return;
                const dx = drawerTouchStartX - e.touches[0].clientX;
                const dy = drawerTouchStartY - e.touches[0].clientY;
                // If vertical movement dominates → this is a scroll, not a swipe
                if (Math.abs(dy) > Math.abs(dx) + 10) {
                    drawerSwipeActive = false;
                }
            }, {
                passive: true
            });

            drawer.addEventListener('touchend', function(e) {
                if (!drawerSwipeActive) {
                    drawerTouchStartX = 0;
                    drawerTouchStartY = 0;
                    return;
                }
                const drawerTouchEndX = e.changedTouches[0].clientX;
                const drawerTouchEndY = e.changedTouches[0].clientY;
                const dx = drawerTouchStartX - drawerTouchEndX; // negative = rightward
                const dy = drawerTouchStartY - drawerTouchEndY;

                // Swipe RIGHT: negative dx, horizontal dominant, threshold 60px
                if (dx < -60 && Math.abs(dx) > Math.abs(dy)) {
                    closeMediaSidebar();
                }
                drawerTouchStartX = 0;
                drawerTouchStartY = 0;
                drawerSwipeActive = false;
            }, {
                passive: true
            });
        })();

        // Fetch initial user interaction statuses for visible talent cards
        const talentIds = [];
        $('.like-btn').each(function() {
            const id = this.id.replace('likeBtn_', '');
            if (id) talentIds.push(id);
        });

        if (talentIds.length > 0) {
            $.get('{{ route("talent.interactions.status") }}', {
                talent_ids: talentIds.join(',')
            }, function(res) {
                if (res.success && res.statuses) {
                    $.each(res.statuses, function(id, data) {
                        const likeBtn = document.getElementById('likeBtn_' + id);
                        const likeCount = document.getElementById('likeCount_' + id);
                        const followBtn = document.getElementById('followBtn_' + id);
                        const followCount = document.getElementById('followersCount_' + id);
                        const commentCount = document.getElementById('commentCount_' + id);
                        const commentBtn = document.getElementById('commentBtn_' + id);

                        if (likeCount) likeCount.textContent = data.likes_count;
                        if (followCount) followCount.textContent = data.followers_count;
                        if (commentCount) {
                            commentCount.textContent = data.comments_count;
                            if (data.comments_count > 0) {
                                if (commentBtn) commentBtn.classList.add('has-comments');
                                commentCount.classList.add('has-comments');
                            } else {
                                if (commentBtn) commentBtn.classList.remove('has-comments');
                                commentCount.classList.remove('has-comments');
                            }
                        }

                        if (likeBtn && data.is_liked) {
                            likeBtn.classList.add('liked');
                            likeBtn.textContent = i18n.liked;
                        }
                        if (followBtn && data.is_following) {
                            followBtn.classList.add('following');
                            followBtn.textContent = i18n.following;
                        }
                    });
                }
            });
        }

        // Ensure only one video in the Recent Upload Previews sidebar plays at a time,
        // and pause any video that scrolls out of view.
        const sidebarVideos = document.querySelectorAll('.media-preview-sidebar video');
        const sidebarIframes = document.querySelectorAll('.media-preview-sidebar iframe');
        const ytPlayers = [];

        // Load YouTube IFrame Player API
        if (sidebarIframes.length > 0) {
            const hasYouTube = Array.from(sidebarIframes).some(iframe => iframe.src.includes('youtube.com'));
            if (hasYouTube) {
                var tag = document.createElement('script');
                tag.src = "https://www.youtube.com/iframe_api";
                var firstScriptTag = document.getElementsByTagName('script')[0];
                firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
            }
        }

        function initializeYTPlayers() {
            document.querySelectorAll('.media-preview-sidebar iframe').forEach((iframe, index) => {
                if (iframe.src.includes('youtube.com')) {
                    // Ensure enablejsapi=1 is present
                    if (!iframe.src.includes('enablejsapi=1')) {
                        const glue = iframe.src.includes('?') ? '&' : '?';
                        iframe.src += glue + 'enablejsapi=1';
                    }
                    if (!iframe.id) {
                        iframe.id = 'yt-player-sidebar-' + index;
                    }
                    if (ytPlayers.some(item => item.id === iframe.id)) {
                        return; // already instantiated
                    }
                    try {
                        const player = new YT.Player(iframe.id, {
                            events: {
                                'onStateChange': function(event) {
                                    if (event.data === YT.PlayerState.PLAYING) {
                                        pauseAllSidebarMediaExcept(iframe);
                                    }
                                }
                            }
                        });
                        ytPlayers.push({
                            id: iframe.id,
                            player: player
                        });
                    } catch (e) {
                        console.error('Error instantiating YT Player:', e);
                    }
                }
            });
        }

        window.onYouTubeIframeAPIReady = function() {
            initializeYTPlayers();
        };

        // If YT is already loaded (due to cached api script or fast execution)
        if (typeof YT !== 'undefined' && YT.Player) {
            initializeYTPlayers();
        }

        function pauseAllSidebarMediaExcept(activeMedia) {
            // Pause HTML5 videos
            sidebarVideos.forEach(v => {
                if (v !== activeMedia && !v.paused) {
                    v.pause();
                }
            });

            // Pause YouTube/Vimeo iframe videos
            sidebarIframes.forEach(iframe => {
                if (iframe !== activeMedia) {
                    if (iframe.src.includes('youtube.com')) {
                        const found = ytPlayers.find(item => item.id === iframe.id);
                        if (found && found.player && typeof found.player.pauseVideo === 'function') {
                            try {
                                found.player.pauseVideo();
                            } catch (e) {}
                        } else {
                            iframe.contentWindow.postMessage(JSON.stringify({
                                event: 'command',
                                func: 'pauseVideo',
                                args: ''
                            }), '*');
                        }
                    } else if (iframe.src.includes('vimeo.com')) {
                        iframe.contentWindow.postMessage(JSON.stringify({
                            method: 'pause'
                        }), '*');
                    }
                }
            });
        }

        // 1. Detect when HTML5 video starts playing
        sidebarVideos.forEach(video => {
            video.addEventListener('play', function() {
                pauseAllSidebarMediaExcept(video);
            });
        });

        // 2. Fallback blur listener for iframe focus transitions (especially Vimeo)
        window.addEventListener('blur', function() {
            setTimeout(() => {
                if (document.activeElement && document.activeElement.tagName === 'IFRAME') {
                    const iframe = document.activeElement;
                    if (iframe.closest('.media-preview-sidebar')) {
                        pauseAllSidebarMediaExcept(iframe);
                    }
                }
            }, 150);
        });

        // 3. Pause any media that is scrolled out of the visible screen space
        const sidebarObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) {
                    const el = entry.target;
                    if (el.tagName === 'VIDEO') {
                        if (!el.paused) {
                            el.pause();
                        }
                    } else if (el.tagName === 'IFRAME') {
                        if (el.src.includes('youtube.com')) {
                            const found = ytPlayers.find(item => item.id === el.id);
                            if (found && found.player && typeof found.player.pauseVideo === 'function') {
                                try {
                                    found.player.pauseVideo();
                                } catch (e) {}
                            } else {
                                el.contentWindow.postMessage(JSON.stringify({
                                    event: 'command',
                                    func: 'pauseVideo',
                                    args: ''
                                }), '*');
                            }
                        } else if (el.src.includes('vimeo.com')) {
                            el.contentWindow.postMessage(JSON.stringify({
                                method: 'pause'
                            }), '*');
                        }
                    }
                }
            });
        }, {
            threshold: 0.15 // trigger when less than 15% visible
        });

        // Live search/filter for Recent Upload Previews sidebar
        const $mediaSearch = $('#mediaSearch');
        const $noResultsPlaceholder = $('#no-media-search-results');

        if ($mediaSearch.length) {
            $mediaSearch.on('input', function() {
                const query = $(this).val().toLowerCase().trim();
                let visibleCount = 0;

                $('.media-feed-item').each(function() {
                    const $item = $(this);

                    // Extract text details
                    const username = $item.find('.media-feed-username').text().toLowerCase();
                    const userrole = $item.find('.media-feed-userrole').text().toLowerCase();
                    const title = $item.find('.media-feed-title').text().toLowerCase();
                    const caption = $item.find('.media-feed-caption').text().toLowerCase();
                    const type = $item.find('.media-feed-badge').text().toLowerCase();

                    // Check matches
                    const isMatch = username.includes(query) ||
                        userrole.includes(query) ||
                        title.includes(query) ||
                        caption.includes(query) ||
                        type.includes(query);

                    if (isMatch) {
                        $item.show();
                        visibleCount++;
                    } else {
                        $item.hide();

                        // Also pause any HTML5 video or YouTube iframe if it gets hidden
                        const video = $item.find('video')[0];
                        if (video && !video.paused) {
                            video.pause();
                        }
                        const iframe = $item.find('iframe')[0];
                        if (iframe) {
                            if (iframe.src.includes('youtube.com')) {
                                const found = ytPlayers.find(item => item.id === iframe.id);
                                if (found && found.player && typeof found.player.pauseVideo === 'function') {
                                    try {
                                        found.player.pauseVideo();
                                    } catch (e) {}
                                }
                            } else if (iframe.src.includes('vimeo.com')) {
                                iframe.contentWindow.postMessage(JSON.stringify({
                                    method: 'pause'
                                }), '*');
                            }
                        }
                    }
                });

                if (visibleCount === 0) {
                    $noResultsPlaceholder.show();
                } else {
                    $noResultsPlaceholder.hide();
                }
            });
        }

        // AJAX Load More Recent Media Posts
        let currentMediaOffset = {{ count($recentMedia) }};

        $('#loadMoreMediaBtn').on('click', function() {
            const $btn = $(this);
            const $icon = $('#loadMoreMediaIcon');
            const $text = $('#loadMoreMediaText');

            // If button is transformed to 'Move to Beginning', scroll smoothly to top
            if ($btn.data('action') === 'top') {
                const sidebar = document.querySelector('.media-preview-sidebar');
                if (sidebar) {
                    sidebar.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
                return;
            }

            if ($btn.prop('disabled')) return;

            $btn.prop('disabled', true);
            $icon.attr('class', 'spinner-border spinner-border-sm me-1').attr('role', 'status');
            $text.text("{{ __('Loading...') }}");

            $.ajax({
                url: '{{ route("media.load-more") }}',
                type: 'GET',
                data: {
                    offset: currentMediaOffset
                },
                success: function(res) {
                    if (res.success && res.html && res.count > 0) {
                        $('#viewMoreMediaContainer').before(res.html);
                        currentMediaOffset += res.count;

                        // Fetch interaction statuses for newly appended media items
                        if (res.media_ids && res.media_ids.length > 0) {
                            $.get('{{ route("media.interactions.status") }}', {
                                media_ids: res.media_ids.join(',')
                            }, function(statusRes) {
                                if (statusRes.success && statusRes.statuses) {
                                    $.each(statusRes.statuses, function(id, data) {
                                        const likeBtn = document.getElementById('mediaLikeBtn_' + id);
                                        const likeIcon = document.querySelector('.media-like-icon_' + id);
                                        const likeCount = document.getElementById('mediaLikeCount_' + id);
                                        const commentCount = document.getElementById('mediaCommentCount_' + id);
                                        const shareCount = document.getElementById('mediaShareCount_' + id);

                                        if (likeCount) likeCount.textContent = data.likes_count;
                                        if (commentCount) commentCount.textContent = data.comments_count;
                                        if (shareCount) shareCount.textContent = data.shares_count;

                                        if (data.is_liked) {
                                            if (likeBtn) likeBtn.style.color = '#ef4444';
                                            if (likeIcon) {
                                                likeIcon.classList.remove('bi-heart');
                                                likeIcon.classList.add('bi-heart-fill');
                                            }
                                        }
                                    });
                                }
                            });
                        }

                        // Re-observe videos/iframes for auto-pause on scroll
                        document.querySelectorAll('.media-preview-sidebar video').forEach(v => sidebarObserver.observe(v));
                        document.querySelectorAll('.media-preview-sidebar iframe').forEach(iframe => sidebarObserver.observe(iframe));

                        // Re-trigger live search filter if query exists
                        if ($mediaSearch.length && $mediaSearch.val().trim() !== '') {
                            $mediaSearch.trigger('input');
                        }

                        if (!res.has_more) {
                            // Reached the end -> change button to "MOVE TO TOP"
                            $btn.prop('disabled', false);
                            $btn.data('action', 'top');
                            $icon.attr('class', 'bi bi-arrow-up-circle-fill');
                            $text.text("{{ __('MOVE TO TOP') }}");
                        } else {
                            $btn.prop('disabled', false);
                            $icon.attr('class', 'bi bi-arrow-down-circle-fill');
                            $text.text("{{ __('View More') }}");
                        }
                    } else {
                        // Reached the end (no more items) -> change button to "MOVE TO TOP"
                        $btn.prop('disabled', false);
                        $btn.data('action', 'top');
                        $icon.attr('class', 'bi bi-arrow-up-circle-fill');
                        $text.text("{{ __('MOVE TO TOP') }}");
                    }
                },
                error: function() {
                    $btn.prop('disabled', false);
                    $icon.attr('class', 'bi bi-arrow-down-circle-fill');
                    $text.text("{{ __('View More') }}");
                }
            });
        });

        // AJAX Load More Talent Cards
        let currentTalentsOffset = {{ count($talents) }};
        const currentCategory = "{{ request('category', 'all') }}";
        const currentSearch = "{{ request('search', '') }}";

        $('#loadMoreTalentsBtn').on('click', function() {
            const $btn = $(this);
            const $icon = $('#loadMoreTalentsIcon');
            const $text = $('#loadMoreTalentsText');

            if ($btn.data('action') === 'top') {
                const talentGrid = document.querySelector('.talent-grid');
                if (talentGrid) {
                    talentGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
                return;
            }

            if ($btn.prop('disabled')) return;

            $btn.prop('disabled', true);
            $icon.attr('class', 'spinner-border spinner-border-sm me-1').attr('role', 'status');
            $text.text("{{ __('Loading...') }}");

            $.ajax({
                url: '{{ route("talents.load-more") }}',
                type: 'GET',
                data: {
                    offset: currentTalentsOffset,
                    category: currentCategory,
                    search: currentSearch
                },
                success: function(res) {
                    if (res.success && res.html && res.count > 0) {
                        $('.talent-grid').append(res.html);
                        currentTalentsOffset += res.count;

                        if (!res.has_more) {
                            $btn.prop('disabled', false);
                            $btn.data('action', 'top');
                            $icon.attr('class', 'bi bi-arrow-up-circle-fill');
                            $text.text("{{ __('MOVE TO TOP') }}");
                        } else {
                            $btn.prop('disabled', false);
                            $icon.attr('class', 'bi bi-arrow-down-circle-fill');
                            $text.text("{{ __('View More') }}");
                        }
                    } else {
                        $btn.prop('disabled', false);
                        $btn.data('action', 'top');
                        $icon.attr('class', 'bi bi-arrow-up-circle-fill');
                        $text.text("{{ __('MOVE TO TOP') }}");
                    }
                },
                error: function() {
                    $btn.prop('disabled', false);
                    $icon.attr('class', 'bi bi-arrow-down-circle-fill');
                    $text.text("{{ __('View More') }}");
                }
            });
        });

        sidebarVideos.forEach(v => sidebarObserver.observe(v));
        sidebarIframes.forEach(iframe => {
            // Append JS API support parameters dynamically to YouTube source URLs if not already present
            if (iframe.src.includes('youtube.com') && !iframe.src.includes('enablejsapi=1')) {
                const glue = iframe.src.includes('?') ? '&' : '?';
                iframe.src += glue + 'enablejsapi=1';
            }
            sidebarObserver.observe(iframe);
        });
    });

    const i18nCard = {
        like: "{{ __('Like') }}",
        liked: "{{ __('Liked') }}",
        followers: "{{ __('Followers') }}",
        following: "{{ __('Following') }}"
    };

    function toggleCardLike(id) {
        const btn = document.getElementById('likeBtn_' + id);
        const count = document.getElementById('likeCount_' + id);
        if (!btn || !count) return;

        btn.disabled = true;
        $.ajax({
            url: '/talent/' + id + '/like',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                btn.disabled = false;
                if (res.success) {
                    if (res.liked) {
                        btn.classList.add('liked');
                        btn.innerHTML = `<i class="bi bi-heart-fill" style="margin-right: 4px; color: #ef4444;"></i> ${i18nCard.liked}`;
                    } else {
                        btn.classList.remove('liked');
                        btn.innerHTML = `<i class="bi bi-heart" style="margin-right: 4px;"></i> ${i18nCard.like}`;
                    }
                    count.textContent = res.count;
                }
            },
            error: function() {
                btn.disabled = false;
            }
        });
    }

    function toggleCardFollow(id) {
        const btn = document.getElementById('followBtn_' + id);
        const count = document.getElementById('followersCount_' + id);
        if (!btn || !count) return;

        btn.disabled = true;
        $.ajax({
            url: '/talent/' + id + '/follow',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                btn.disabled = false;
                if (res.success) {
                    if (res.following) {
                        btn.classList.add('following');
                        btn.innerHTML = `<i class="bi bi-person-check-fill" style="margin-right: 4px; color: #10b981;"></i> ${i18nCard.following}`;
                    } else {
                        btn.classList.remove('following');
                        btn.innerHTML = `<i class="bi bi-person-plus" style="margin-right: 4px;"></i> ${i18nCard.followers}`;
                    }
                    count.textContent = res.count;
                }
            },
            error: function() {
                btn.disabled = false;
            }
        });
    }

    // Media Interactions JS (Like, Comment, Share)
    let currentShareData = {
        id: null,
        title: '',
        url: ''
    };

    function toggleMediaLike(id) {
        const btn = document.getElementById('mediaLikeBtn_' + id);
        const icon = document.querySelector('.media-like-icon_' + id);
        const countEl = document.getElementById('mediaLikeCount_' + id);
        if (!btn || !countEl) return;

        btn.disabled = true;
        $.ajax({
            url: '/media/' + id + '/like',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                btn.disabled = false;
                if (res.success) {
                    countEl.textContent = res.count;
                    if (res.liked) {
                        btn.style.color = '#ef4444';
                        if (icon) icon.className = 'bi bi-heart-fill media-like-icon_' + id;
                    } else {
                        btn.style.color = '#64748b';
                        if (icon) icon.className = 'bi bi-heart media-like-icon_' + id;
                    }
                }
            },
            error: function() {
                btn.disabled = false;
            }
        });
    }

    function openMediaCommentsModal(id, title) {
        $('#activeMediaId').val(id);
        $('#activeParentId').val('');
        $('#mediaCommentsModalTitle').text(title || 'Post Comments');
        $('#mediaCommentText').val('');
        $('#mediaCommentsList').html('<div style="text-align: center; padding: 30px; color: #94a3b8;"><i class="bi bi-hourglass-split"></i> Loading comments...</div>');
        $('#mediaCommentsModal').css('display', 'flex').hide().fadeIn(200);

        $.ajax({
            url: '/media/' + id + '/comments',
            type: 'GET',
            success: function(res) {
                if (res.success) {
                    renderMediaComments(res.comments);
                    $('#mediaCommentCount_' + id).text(res.count);
                }
            }
        });
    }

    function renderMediaComments(comments) {
        const list = $('#mediaCommentsList');
        if (!comments || comments.length === 0) {
            list.html('<div style="text-align: center; padding: 30px 10px; color: #94a3b8;"><i class="bi bi-chat-left-text" style="font-size: 2rem; display: block; margin-bottom: 6px;"></i>No comments yet. Be the first to comment!</div>');
            return;
        }

        let html = '';
        comments.forEach(c => {
            const avatar = c.user_avatar || "{{ asset('images/default-avatar.png') }}";
            html += `
                <div style="background: #f8fafc; border-radius: 12px; padding: 10px 14px; border: 1px solid #e2e8f0;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <img src="${avatar}" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover;">
                            <span style="font-weight: 700; font-size: 0.84rem; color: #0f172a;">${c.author_name}</span>
                        </div>
                        <span style="font-size: 0.72rem; color: #94a3b8;">${c.created_at_human}</span>
                    </div>
                    <p style="margin: 0; font-size: 0.84rem; color: #334155; line-height: 1.4;">${c.comment}</p>
                </div>
            `;
        });
        list.html(html);
    }

    function submitMediaComment(e) {
        e.preventDefault();
        const id = $('#activeMediaId').val();
        const text = $('#mediaCommentText').val().trim();
        const author = $('#mediaCommentAuthor').val() ? $('#mediaCommentAuthor').val().trim() : '';

        if (!text) return;
        const btn = $('#btnSubmitMediaComment');
        btn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i>');

        $.ajax({
            url: '/media/' + id + '/comment',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                comment: text,
                author_name: author
            },
            success: function(res) {
                btn.prop('disabled', false).html('<i class="bi bi-send-fill"></i>');
                if (res.success) {
                    $('#mediaCommentText').val('');
                    $('#mediaCommentCount_' + id).text(res.count);
                    openMediaCommentsModal(id, $('#mediaCommentsModalTitle').text());
                }
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="bi bi-send-fill"></i>');
            }
        });
    }

    function openMediaShareModal(id, title, url) {
        currentShareData = {
            id: id,
            title: title,
            url: url
        };
        $('#mediaShareTitle').text(title);
        if (navigator.share) {
            $('#btnNativeShare').css('display', 'flex');
        } else {
            $('#btnNativeShare').hide();
        }
        $('#mediaShareModal').css('display', 'flex').hide().fadeIn(200);
    }

    function recordMediaShare(id, platform) {
        $.ajax({
            url: '/media/' + id + '/share',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                platform: platform
            },
            success: function(res) {
                if (res.success) {
                    $('#mediaShareCount_' + id).text(res.count);
                }
            }
        });
    }

    function shareToPlatform(platform) {
        if (!currentShareData.id) return;
        const url = encodeURIComponent(currentShareData.url);
        const title = encodeURIComponent(currentShareData.title);
        recordMediaShare(currentShareData.id, platform);

        let shareUrl = '';
        if (platform === 'whatsapp') {
            shareUrl = `https://api.whatsapp.com/send?text=${title}%20${url}`;
        } else if (platform === 'facebook') {
            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
        } else if (platform === 'twitter') {
            shareUrl = `https://twitter.com/intent/tweet?text=${title}&url=${url}`;
        }
        if (shareUrl) {
            window.open(shareUrl, '_blank', 'width=600,height=500');
        }
        $('#mediaShareModal').fadeOut(200);
    }

    function copyMediaLink() {
        if (!currentShareData.url) return;
        const textToCopy = currentShareData.url;

        function onCopySuccess() {
            recordMediaShare(currentShareData.id, 'copy_link');
            alert('Link copied to clipboard!');
            $('#mediaShareModal').fadeOut(200);
        }

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(textToCopy)
                .then(onCopySuccess)
                .catch(() => fallbackCopyText(textToCopy, onCopySuccess));
        } else {
            fallbackCopyText(textToCopy, onCopySuccess);
        }
    }

    function fallbackCopyText(text, callback) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.width = "2em";
        textArea.style.height = "2em";
        textArea.style.padding = "0";
        textArea.style.border = "none";
        textArea.style.outline = "none";
        textArea.style.boxShadow = "none";
        textArea.style.background = "transparent";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            const successful = document.execCommand('copy');
            if (successful && callback) {
                callback();
            } else {
                prompt('Copy this link:', text);
            }
        } catch (err) {
            prompt('Copy this link:', text);
        }
        document.body.removeChild(textArea);
    }

    function shareNative() {
        if (navigator.share && currentShareData.url) {
            navigator.share({
                title: currentShareData.title,
                url: currentShareData.url
            }).then(() => {
                recordMediaShare(currentShareData.id, 'native');
                $('#mediaShareModal').fadeOut(200);
            }).catch(() => {});
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const mediaIds = [];
        $('.media-feed-item').each(function() {
            const mid = $(this).data('media-id');
            if (mid) mediaIds.push(mid);
        });

        if (mediaIds.length > 0) {
            $.ajax({
                url: '/media/interactions/status',
                type: 'GET',
                data: {
                    media_ids: mediaIds
                },
                success: function(res) {
                    if (res.success && res.statuses) {
                        Object.keys(res.statuses).forEach(id => {
                            const st = res.statuses[id];
                            $('#mediaLikeCount_' + id).text(st.likes_count);
                            $('#mediaCommentCount_' + id).text(st.comments_count);
                            $('#mediaShareCount_' + id).text(st.shares_count);
                            if (st.is_liked) {
                                const btn = document.getElementById('mediaLikeBtn_' + id);
                                const icon = document.querySelector('.media-like-icon_' + id);
                                if (btn) btn.style.color = '#ef4444';
                                if (icon) icon.className = 'bi bi-heart-fill media-like-icon_' + id;
                            }
                        });
                    }
                }
            });
        }
    });
</script>

<!-- Media Comments Modal HTML -->
<div id="mediaCommentsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15,23,42,0.65); backdrop-filter: blur(5px); z-index: 99999; justify-content: center; align-items: center; padding: 15px;">
    <div style="background: #ffffff; border-radius: 18px; width: 100%; max-width: 520px; padding: 22px; box-shadow: 0 20px 40px rgba(0,0,0,0.25); position: relative; max-height: 90vh; display: flex; flex-direction: column;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
            <h3 style="margin: 0; font-size: 1.08rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-chat-dots-fill" style="color: #0284c7;"></i> <span id="mediaCommentsModalTitle">Post Comments</span>
            </h3>
            <button type="button" onclick="$('#mediaCommentsModal').fadeOut(200);" style="background: none; border: none; font-size: 1.4rem; color: #64748b; cursor: pointer;">&times;</button>
        </div>

        <div id="mediaCommentsList" style="flex-grow: 1; overflow-y: auto; max-height: 360px; padding-right: 6px; margin-bottom: 16px; display: flex; flex-direction: column; gap: 12px;">
            <div style="text-align: center; padding: 30px; color: #94a3b8;"><i class="bi bi-hourglass-split"></i> Loading comments...</div>
        </div>

        <form id="mediaCommentForm" onsubmit="submitMediaComment(event)" style="border-top: 1px solid #e2e8f0; padding-top: 12px; display: flex; flex-direction: column; gap: 10px;">
            <input type="hidden" id="activeMediaId" value="">
            <input type="hidden" id="activeParentId" value="">

            @guest
            <div style="display: flex; align-items: center; gap: 8px;">
                <input type="text" id="mediaCommentAuthor" placeholder="Your Name (Optional)" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.82rem; color: #0f172a; outline: none;">
            </div>
            @endguest

            <div style="display: flex; gap: 8px;">
                <textarea id="mediaCommentText" placeholder="Write a comment..." rows="2" required style="flex: 1; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; color: #0f172a; outline: none; resize: none; line-height: 1.4;"></textarea>
                <button type="submit" id="btnSubmitMediaComment" style="padding: 0 18px; border-radius: 10px; background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #fff; border: none; font-weight: 700; font-size: 0.86rem; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Media Share Modal HTML -->
<div id="mediaShareModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15,23,42,0.65); backdrop-filter: blur(5px); z-index: 99999; justify-content: center; align-items: center; padding: 15px;">
    <div style="background: #ffffff; border-radius: 18px; width: 100%; max-width: 440px; padding: 22px; box-shadow: 0 20px 40px rgba(0,0,0,0.25); position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
            <h3 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-share-fill" style="color: #6366f1;"></i> Share Post
            </h3>
            <button type="button" onclick="$('#mediaShareModal').fadeOut(200);" style="background: none; border: none; font-size: 1.4rem; color: #64748b; cursor: pointer;">&times;</button>
        </div>

        <p id="mediaShareTitle" style="font-size: 0.88rem; font-weight: 700; color: #334155; margin-bottom: 16px; text-align: center;"></p>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 18px;">
            <button type="button" onclick="shareToPlatform('whatsapp')" style="padding: 12px; border-radius: 12px; background: #25D366; color: #ffffff; border: none; font-weight: 700; font-size: 0.88rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <i class="bi bi-whatsapp" style="font-size: 1.2rem;"></i> WhatsApp
            </button>
            <button type="button" onclick="shareToPlatform('facebook')" style="padding: 12px; border-radius: 12px; background: #1877F2; color: #ffffff; border: none; font-weight: 700; font-size: 0.88rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <i class="bi bi-facebook" style="font-size: 1.2rem;"></i> Facebook
            </button>
            <button type="button" onclick="shareToPlatform('twitter')" style="padding: 12px; border-radius: 12px; background: #000000; color: #ffffff; border: none; font-weight: 700; font-size: 0.88rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <i class="bi bi-twitter-x" style="font-size: 1.2rem;"></i> X (Twitter)
            </button>
            <button type="button" onclick="copyMediaLink()" style="padding: 12px; border-radius: 12px; background: #6366f1; color: #ffffff; border: none; font-weight: 700; font-size: 0.88rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <i class="bi bi-link-45deg" style="font-size: 1.2rem;"></i> Copy Link
            </button>
        </div>

        <button type="button" id="btnNativeShare" onclick="shareNative()" style="display: none; width: 100%; padding: 12px; border-radius: 12px; background: #f1f5f9; color: #0f172a; border: 1px solid #cbd5e1; font-weight: 700; font-size: 0.86rem; cursor: pointer; align-items: center; justify-content: center; gap: 8px;">
            <i class="bi bi-box-arrow-up"></i> More Share Options
        </button>
    </div>
</div>
@endsection