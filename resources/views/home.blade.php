@extends('layouts.app')

@section('title', 'ChapConnect - Talent Directory')

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
        }

        .mobile-search-input {
            flex: 1;
            border: none;
            outline: none;
            padding: 11px 12px 11px 38px;
            font-size: 13px;
            font-family: inherit;
            color: var(--text-main);
            background: transparent;
        }

        .mobile-search-btn {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border: none;
            color: #ffffff !important;
            padding: 0 16px;
            height: 42px;
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
    <input class="Srch" type="search" name="search" value="{{ request('search') }}" placeholder="Type to Search here">
    <button type="submit" class="btn" title="Search"><i class="bi bi-search"></i></button>
</form>
@endsection

@section('content')
<!-- Category Menu Navigation -->
<div class="menu">
    <!-- Mobile Select2 category picker (desktop only) -->
    <div class="menu-filter-wrap">
        <select id="menuSelect" class="menu-select">
            <option value="">🔍 Browse Categories...</option>
            <option value="{{ route('home', ['search' => request('search')]) }}" {{ $currentCategory === 'all' ? 'selected' : '' }}>
                All Talents ({{ $totalTalents }})
            </option>
            @foreach($categories as $slug => $label)
            <option value="{{ route('home', ['category' => $slug, 'search' => request('search')]) }}" {{ $currentCategory === $slug ? 'selected' : '' }}>
                {{ $label }} ({{ $categoryCounts[$slug] ?? 0 }})
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
            <input class="mobile-search-input" type="search" name="search" value="{{ request('search') }}" placeholder="Search talents, categories...">
            <button type="submit" class="mobile-search-btn" aria-label="Search" title="Search">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </form>

    <!-- Category Links (horizontal scroll on all screens) -->
    <ul id="menuList">
        <li class="{{ $currentCategory === 'all' ? 'active' : '' }}">
            <a href="{{ route('home', ['search' => request('search')]) }}">
                All Talents <span class="filter-badge" style="background: rgba(255,255,255,0.2); padding: 2px 6px; border-radius: 10px; font-size: 11px;">{{ $totalTalents }}</span>
            </a>
        </li>
        @foreach($categories as $slug => $label)
        <li class="{{ $currentCategory === $slug ? 'active' : '' }}">
            <a href="{{ route('home', ['category' => $slug, 'search' => request('search')]) }}">
                {{ $label }} <span class="filter-badge" style="background: rgba(255,255,255,0.2); padding: 2px 6px; border-radius: 10px; font-size: 11px;">{{ $categoryCounts[$slug] ?? 0 }}</span>
            </a>
        </li>
        @endforeach
    </ul>
</div>

<main class="main">
    <div class="row" style="width: 100%;">
        <!-- Profile View Column (Left - col-9) -->
        <div class="col-9">
            <div class="talent-grid" style="padding-top: 0;">
                @forelse($talents as $talent)
                <div class="container">
                    <div class="imagea">
                        @if($talent->profile_image)
                        <img src="{{ asset($talent->profile_image) }}" alt="{{ $talent->name }}">
                        @else
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop&q=80" alt="{{ $talent->name }}">
                        @endif
                        <div class="details">
                            <h2 title="{{ $talent->name }}">{{ $talent->name }}</h2>
                            <h5>{{ $talent->category_label }}</h5>

                            <div class="like-container">
                                <div class="like">
                                    <button class="like-btn" id="likeBtn_{{ $talent->id }}" onclick="toggleCardLike({{ $talent->id }})">Like 🤍</button>
                                    <span class="like-count" id="likeCount_{{ $talent->id }}">{{ $talent->likes_received_count ?? 0 }}</span>
                                </div>
                                <div class="comment">
                                    <a href="{{ route('profile', $talent->id) }}#info-tab" style="text-decoration:none;">
                                        <button class="comment-btn {{ ($talent->comments_received_count ?? 0) > 0 ? 'has-comments' : '' }}" id="commentBtn_{{ $talent->id }}">Comments 💬</button>
                                    </a>
                                    <span class="comment-count {{ ($talent->comments_received_count ?? 0) > 0 ? 'has-comments' : '' }}" id="commentCount_{{ $talent->id }}">{{ $talent->comments_received_count ?? 0 }}</span>
                                </div>
                                <div class="follow">
                                    <button class="follow-btn" id="followBtn_{{ $talent->id }}" onclick="toggleCardFollow({{ $talent->id }})">Followers</button>
                                    <span class="followers-count" id="followersCount_{{ $talent->id }}">{{ $talent->followers_received_count ?? 0 }}</span>
                                </div>
                            </div>

                            <a href="{{ route('profile', $talent->id) }}" class="vbtn">View Full Profile</a>

                            <div class="card-quick-links">
                                <a href="{{ route('profile', $talent->id) }}#photos-tab"><span class="bi bi-camera"></span> Photos</a>
                                <a href="{{ route('profile', $talent->id) }}#videos-tab"><span class="bi bi-camera-video"></span> Videos</a>
                                <a href="{{ route('profile', $talent->id) }}#news-tab"><span class="bi bi-newspaper"></span> News</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="no-results" style="grid-column: 1/-1; text-align: center; padding: 50px 20px; background: white; border-radius: 16px; box-shadow: var(--shadow);">
                    <i class="bi bi-people" style="font-size: 48px; color: var(--primary); display: block; margin-bottom: 10px;"></i>
                    <h3 style="color: var(--text-main); margin-bottom: 5px;">No Talents Found</h3>
                    <p style="color: var(--text-muted); font-size: 14px;">No talents registered yet under this category selection.</p>
                </div>
                @endforelse
            </div>
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
                        <i class="bi bi-camera-reels-fill header-icon-desktop"></i> Post View
                    </span>
                    <!-- add live search to filter the contents -->
                    <div class="media-search-container" style="position: relative; width: 170px;">
                        <i class="bi bi-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 12px;"></i>
                        <input type="text" id="mediaSearch" placeholder="Search..." style="width: 100%; padding: 6px 10px 6px 30px; border: 1px solid #cbd5e1; border-radius: 30px; background: #f8fafc; color: var(--text-main); font-family: inherit; font-size: 12px; outline: none; transition: all 0.2s ease-in-out;">
                    </div>
                </h3>



                <div class="media-feed-list">
                    <div id="no-media-search-results" style="text-align: center; padding: 40px 10px; color: var(--text-muted); display: none;">
                        <i class="bi bi-search" style="font-size: 2.5rem; color: #cbd5e1; display: block; margin-bottom: 8px;"></i>
                        <p style="font-size: 0.88rem; font-weight: 500; margin: 0;">No matching previews found.</p>
                    </div>
                    @forelse($recentMedia as $media)
                    <div class="media-feed-item">
                        <!-- User Info Header -->
                        <div class="media-feed-user">
                            @if($media->user->profile_image)
                            <img class="media-feed-avatar" src="{{ asset($media->user->profile_image) }}" alt="{{ $media->user->name }}">
                            @else
                            <img class="media-feed-avatar" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=60&auto=format&fit=crop&q=80" alt="{{ $media->user->name }}">
                            @endif
                            <div class="media-feed-user-info">
                                <a class="media-feed-username" href="{{ route('profile', $media->user->id) }}">
                                    {{ $media->user->name }}
                                </a>
                                <span class="media-feed-userrole">{{ $media->user->category_label }}</span>
                            </div>
                        </div>

                        <!-- Media Content (Photo or Video) -->
                        <div class="media-feed-content">
                            @if($media->type === 'photo')
                            <a href="{{ route('profile', $media->user->id) }}#photos-tab">
                                <img class="media-feed-image" src="{{ asset($media->file_path) }}" alt="{{ $media->title ?: 'Portfolio image' }}">
                            </a>
                            @elseif($media->type === 'video')
                            <div class="media-feed-video">
                                {!! \App\Helpers\VideoHelper::renderEmbed($media->file_path) !!}
                            </div>
                            @endif
                        </div>

                        <!-- Text Details & Caption -->
                        <div class="media-feed-info">
                            @if($media->title)
                            <h4 class="media-feed-title">{{ $media->title }}</h4>
                            @endif

                            @if($media->content)
                            <p class="media-feed-caption">{{ $media->content }}</p>
                            @endif

                            <div class="media-feed-meta">
                                <span class="media-feed-badge {{ $media->type }}">
                                    <i class="bi bi-{{ $media->type === 'photo' ? 'image' : 'film' }}"></i> {{ $media->type }}
                                </span>
                                <span>{{ $media->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div style="text-align: center; padding: 30px 10px; color: var(--text-muted);">
                        <i class="bi bi-images" style="font-size: 2.5rem; color: #cbd5e1; display: block; margin-bottom: 8px;"></i>
                        <p style="font-size: 0.88rem; font-weight: 500;">No photos or videos uploaded yet.</p>
                    </div>
                    @endforelse
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
                            likeBtn.textContent = 'Liked ❤️';
                        }
                        if (followBtn && data.is_following) {
                            followBtn.classList.add('following');
                            followBtn.textContent = 'Following';
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
        const $mediaFeedItems = $('.media-feed-item');
        const $noResultsPlaceholder = $('#no-media-search-results');

        if ($mediaSearch.length && $mediaFeedItems.length) {
            $mediaSearch.on('input', function() {
                const query = $(this).val().toLowerCase().trim();
                let visibleCount = 0;

                $mediaFeedItems.each(function() {
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
                        btn.textContent = 'Liked ❤️';
                    } else {
                        btn.classList.remove('liked');
                        btn.textContent = 'Like 🤍';
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
                        btn.textContent = 'Following';
                    } else {
                        btn.classList.remove('following');
                        btn.textContent = 'Followers';
                    }
                    count.textContent = res.count;
                }
            },
            error: function() {
                btn.disabled = false;
            }
        });
    }
</script>
@endsection