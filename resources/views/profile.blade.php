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
            <p style="color: var(--text-muted); font-size: 14px; font-weight: 500; margin-bottom: 15px;">{{ $talent->category_label }}</p>

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
    });
</script>
@endsection
