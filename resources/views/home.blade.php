@extends('layouts.app')

@section('title', 'ChapConnect - Talent Directory')

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
    <!-- Mobile Select2 category picker -->
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

    <!-- Desktop Category Links -->
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
    <div class="talent-grid">
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
                                <span class="like-count" id="likeCount_{{ $talent->id }}">0</span>
                            </div>
                            <div class="comment">
                                <a href="{{ route('profile', $talent->id) }}#info-tab" style="text-decoration:none;"><button class="comment-btn">Comments 💬</button></a>
                                <span class="comment-count">0</span>
                            </div>
                            <div class="follow">
                                <button class="follow-btn" id="followBtn_{{ $talent->id }}" onclick="toggleCardFollow({{ $talent->id }})">Followers</button>
                                <span class="followers-count" id="followersCount_{{ $talent->id }}">0</span>
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
</main>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('#menuSelect').select2({
            width: '100%',
            placeholder: '🔍 Browse Categories...',
            minimumResultsForSearch: 0
        });

        $('#menuSelect').on('select2:select', function () {
            var url = $(this).val();
            if (url) window.location.href = url;
        });
    });

    function toggleCardLike(id) {
        const btn = document.getElementById('likeBtn_' + id);
        const count = document.getElementById('likeCount_' + id);
        if (!btn || !count) return;
        let num = parseInt(count.textContent) || 0;
        if (btn.classList.contains('liked')) {
            btn.classList.remove('liked');
            btn.textContent = 'Like 🤍';
            num = Math.max(0, num - 1);
        } else {
            btn.classList.add('liked');
            btn.textContent = 'Liked ❤️';
            num += 1;
        }
        count.textContent = num;
    }

    function toggleCardFollow(id) {
        const btn = document.getElementById('followBtn_' + id);
        const count = document.getElementById('followersCount_' + id);
        if (!btn || !count) return;
        let num = parseInt(count.textContent) || 0;
        if (btn.classList.contains('following')) {
            btn.classList.remove('following');
            btn.textContent = 'Followers';
            num = Math.max(0, num - 1);
        } else {
            btn.classList.add('following');
            btn.textContent = 'Following';
            num += 1;
        }
        count.textContent = num;
    }
</script>
@endsection
