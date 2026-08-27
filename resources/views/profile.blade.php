@extends('layouts.app')

@section('title', 'ChapConnect - ' . $talent->name)

@section('search_bar')
<form action="{{ route('home') }}" method="GET" class="Search">
    <input class="Srch" type="search" name="search" placeholder="Type to Search here">
    <button type="submit" class="btn" title="Search"><i class="bi bi-search"></i></button>
</form>
@endsection

@section('content')
<main class="main" style="max-width: 1200px; margin: 20px auto; padding: 0 15px;">
    <div class="profile-wrapper">
        <!-- Sidebar -->
        <div class="profile-sidebar">
            <div class="pimage">
                @if($talent->profile_image)
                    <img src="{{ asset($talent->profile_image) }}" alt="{{ $talent->name }}">
                @else
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop" alt="{{ $talent->name }}">
                @endif
            </div>
            <h2>{{ $talent->name }}</h2>
            <p style="color: var(--text-muted); font-size: 14px; font-weight: 500; margin-bottom: 12px;">{{ $talent->category_label }}</p>

            <div class="like-container" style="justify-content: center; margin-bottom: 18px; gap: 8px;">
                <div class="like">
                    <button class="like-btn" id="likeBtn_{{ $talent->id }}" onclick="toggleCardLike({{ $talent->id }})">Like 🤍</button>
                    <span class="like-count" id="likeCount_{{ $talent->id }}">{{ $talent->likes_received_count ?? 0 }}</span>
                </div>
                <div class="comment">
                    <a href="#info-tab" style="text-decoration:none;" onclick="$('.menu a[href=\'#info-tab\']').click();">
                        <button class="comment-btn {{ ($talent->comments_received_count ?? 0) > 0 ? 'has-comments' : '' }}" id="commentBtn_{{ $talent->id }}">Comments 💬</button>
                    </a>
                    <span class="comment-count {{ ($talent->comments_received_count ?? 0) > 0 ? 'has-comments' : '' }}" id="commentCount_{{ $talent->id }}">{{ $talent->comments_received_count ?? 0 }}</span>
                </div>
                <div class="follow">
                    <button class="follow-btn" id="followBtn_{{ $talent->id }}" onclick="toggleCardFollow({{ $talent->id }})">Followers</button>
                    <span class="followers-count" id="followersCount_{{ $talent->id }}">{{ $talent->followers_received_count ?? 0 }}</span>
                </div>
            </div>

            <div class="menu">
                <ul>
                    <li><a href="#info-tab" class="active">Information</a></li>
                    <li><a href="#photos-tab">Photos</a></li>
                    <li><a href="#videos-tab">Videos</a></li>
                    <li><a href="#news-tab">News</a></li>
                    <li><a href="{{ route('home') }}"><i class="bi bi-arrow-left"></i> Back to Directory</a></li>
                </ul>
            </div>
        </div>

        <!-- Content Area -->
        <div class="profile-content">
            <!-- Info Section -->
            <div id="info-tab" class="profile-tab-section active">
                <div class="pdetails">
                    <h2>Personal Information</h2>
                    
                    <h3>Occupations</h3>
                    <h5>{{ $talent->category_label }}</h5>
                    
                    <h3>Country</h3>
                    <h5>{{ $talent->country }}</h5>
                    
                    <h3>Phone</h3>
                    <h5>{{ $talent->phone ?: 'Not provided' }}</h5>
                    
                    <h3>Bio Description</h3>
                    <p style="color: var(--text-muted); font-size: 15px; line-height: 1.6; margin-top: 8px;">
                        {{ $talent->description ?: 'No bio biography has been provided by the user yet.' }}
                    </p>
                    
                    <h3 style="margin-top: 25px;">Social &amp; Web Channels</h3>
                    <div style="margin-top: 12px; display: flex; flex-wrap: wrap; gap: 10px;">
                        @if($talent->social_instagram)
                            <a href="{{ $talent->social_instagram }}" target="_blank" class="nav-btn nav-btn-login" style="color: #e1306c; border: 1px solid rgba(225, 48, 108, 0.4);"><i class="bi bi-instagram"></i> Instagram</a>
                        @endif
                        @if($talent->social_facebook)
                            <a href="{{ $talent->social_facebook }}" target="_blank" class="nav-btn nav-btn-login" style="color: #1877f2; border: 1px solid rgba(24, 119, 242, 0.4);"><i class="bi bi-facebook"></i> Facebook</a>
                        @endif
                        @if($talent->social_tiktok)
                            <a href="{{ $talent->social_tiktok }}" target="_blank" class="nav-btn nav-btn-login" style="color: #111; border: 1px solid rgba(0, 0, 0, 0.4);"><i class="bi bi-tiktok"></i> TikTok</a>
                        @endif
                        @if($talent->social_youtube)
                            <a href="{{ $talent->social_youtube }}" target="_blank" class="nav-btn nav-btn-login" style="color: #ff0000; border: 1px solid rgba(255, 0, 0, 0.4);"><i class="bi bi-youtube"></i> YouTube</a>
                        @endif

                        @if(!$talent->social_instagram && !$talent->social_facebook && !$talent->social_tiktok && !$talent->social_youtube)
                            <span style="color: var(--text-muted); font-size: 14px;">No social channels linked yet.</span>
                        @endif
                    </div>

                    <!-- Fan Comments & Reviews Section -->
                    <h3 style="margin-top: 30px; display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-chat-dots-fill" style="color: var(--primary);"></i> User Comments ({{ $comments->count() }})
                    </h3>

                    <!-- Comment Submission Form -->
                    <form action="{{ route('talent.comment', $talent->id) }}" method="POST" style="margin-top: 15px; background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #cbd5e1;">
                        @csrf
                        @guest
                        <div class="form-group" style="margin-bottom: 12px;">
                            <label style="font-size: 13px; font-weight: 600; color: #475569; display: block; margin-bottom: 4px;">Your Name</label>
                            <input type="text" name="author_name" class="form-control" placeholder="Enter your name..." required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 12px; width: 100%;">
                        </div>
                        @endguest
                        <div class="form-group" style="margin-bottom: 12px;">
                            <label style="font-size: 13px; font-weight: 600; color: #475569; display: block; margin-bottom: 4px;">Leave a Comment or Review</label>
                            <textarea name="comment" rows="3" class="form-control" placeholder="Write a public comment for {{ $talent->name }}..." required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 12px; width: 100%;"></textarea>
                        </div>
                        <div style="display: flex; justify-content: flex-end;">
                            <button type="submit" style="padding: 9px 20px; border-radius: 8px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; border: none; font-weight: 700; cursor: pointer; box-shadow: 0 4px 12px rgba(99,102,241,0.3);">Post Comment</button>
                        </div>
                    </form>

                    <!-- Comments List Stream -->
                    <div style="margin-top: 20px; display: flex; flex-direction: column; gap: 12px;">
                        @forelse($comments as $cmt)
                            <div style="background: #ffffff; padding: 14px 18px; border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                    <strong style="color: #0f172a; font-size: 0.9rem;">{{ $cmt->author_name }}</strong>
                                    <span style="font-size: 0.75rem; color: #94a3b8;"><i class="bi bi-clock"></i> {{ $cmt->created_at->diffForHumans() }}</span>
                                </div>
                                <p style="margin: 0; color: #475569; font-size: 0.88rem; line-height: 1.5; white-space: pre-line;">{{ $cmt->comment }}</p>
                            </div>
                        @empty
                            <div style="text-align: center; padding: 25px; color: #94a3b8; font-size: 0.88rem; background: #f8fafc; border-radius: 10px; border: 1px dashed #cbd5e1;">
                                No comments posted yet for {{ $talent->name }}. Be the first to leave a comment!
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Photos Section -->
            <div id="photos-tab" class="profile-tab-section">
                <div class="pdetails">
                    <h2>Photos Portfolio</h2>
                    <div class="photos-grid">
                        @forelse($miniPhotos as $photo)
                            <div class="photo-item">
                                <img src="{{ asset($photo->file_path) }}" alt="{{ $talent->name }} Photo">
                                <div class="photo-overlay">
                                    <h3>{{ $photo->title ?: 'Portfolio Photo' }}</h3>
                                    <p>{{ $talent->name }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="no-results" style="grid-column: 1/-1; padding: 30px; text-align: center; color: var(--text-muted);">
                                No portfolio photos uploaded yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Videos Section -->
            <div id="videos-tab" class="profile-tab-section">
                <div class="pdetails">
                    <h2>Videos Showcase</h2>
                    <div class="videos-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-top: 15px;">
                        @php
                            $videos = $talent->media()->where('type', 'video')->get();
                        @endphp
                        @forelse($videos as $video)
                            <div class="video-item" style="background: white; border-radius: 12px; overflow: hidden; box-shadow: var(--shadow); border: 1px solid var(--border-color);">
                                @if(str_contains($video->file_path, 'youtube.com') || str_contains($video->file_path, 'youtu.be'))
                                    <iframe width="100%" height="200" src="{{ $video->file_path }}" frameborder="0" allowfullscreen></iframe>
                                @else
                                    <video width="100%" height="200" controls src="{{ asset($video->file_path) }}"></video>
                                @endif
                                <div style="padding: 12px;">
                                    <h4 style="margin: 0; color: var(--text-main); font-size: 15px;">{{ $video->title ?: 'Portfolio Video' }}</h4>
                                </div>
                            </div>
                        @empty
                            <div class="no-results" style="grid-column: 1/-1; padding: 30px; text-align: center; color: var(--text-muted);">
                                No video showcases uploaded yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- News Section -->
            <div id="news-tab" class="profile-tab-section">
                <div class="pdetails">
                    <h2>Latest News &amp; Updates</h2>
                    <div style="display: flex; flex-direction: column; gap: 20px; margin-top: 20px;">
                        @php
                            $newsItems = $talent->media()->where('type', 'news')->latest()->get();
                        @endphp
                        @forelse($newsItems as $news)
                            <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: var(--shadow); border: 1px solid var(--border-color); display: flex; flex-direction: column; gap: 14px;">
                                @if($news->file_path)
                                    <div style="max-height: 320px; overflow: hidden; border-radius: 10px;">
                                        <img src="{{ asset($news->file_path) }}" alt="{{ $news->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                @endif
                                <div>
                                    <h3 style="margin: 0 0 6px 0; color: var(--text-main); font-size: 20px; font-weight: 700;">{{ $news->title }}</h3>
                                    <span style="font-size: 13px; color: var(--text-muted); font-weight: 500;"><i class="bi bi-clock"></i> {{ $news->created_at->format('M d, Y - h:i A') }}</span>
                                </div>
                                <p style="color: #475569; font-size: 15px; line-height: 1.6; margin: 0; white-space: pre-line;">{{ $news->content }}</p>
                            </div>
                        @empty
                            <div class="no-results" style="padding: 30px; text-align: center; color: var(--text-muted);">
                                No official news articles posted yet for {{ $talent->name }}. Check back soon for event updates and announcements!
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof initProfileTabs === "function") {
            initProfileTabs();
        }

        // Fetch initial status for this talent
        $.get('{{ route("talent.interactions.status") }}', { talent_ids: '{{ $talent->id }}' }, function(res) {
            if (res.success && res.statuses && res.statuses['{{ $talent->id }}']) {
                const data = res.statuses['{{ $talent->id }}'];
                const likeBtn = document.getElementById('likeBtn_{{ $talent->id }}');
                const followBtn = document.getElementById('followBtn_{{ $talent->id }}');

                if (likeBtn && data.is_liked) {
                    likeBtn.classList.add('liked');
                    likeBtn.textContent = 'Liked ❤️';
                }
                if (followBtn && data.is_following) {
                    followBtn.classList.add('following');
                    followBtn.textContent = 'Following';
                }
            }
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
            data: { _token: '{{ csrf_token() }}' },
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
            data: { _token: '{{ csrf_token() }}' },
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
