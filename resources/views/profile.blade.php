@extends('layouts.app')

@section('title', 'Chap Connect - ' . $talent->name)

@section('content')
<main class="profile-hero">
    <!-- Sidebar profile card -->
    <div class="profile-sidebar">
        <div class="pimage">
            @if($talent->profile_image)
                <img src="{{ $talent->profile_image }}" alt="{{ $talent->name }}">
            @else
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop" alt="{{ $talent->name }}">
            @endif
        </div>
        <h2>{{ $talent->name }}</h2>
        <h5>{{ $talent->category_label }}</h5>
        
        <div class="profile-menu-vertical">
            <a class="active" href="{{ route('profile', $talent->id) }}">Overview</a>
            <a href="{{ route('profile.photos', $talent->id) }}">Photos Gallery</a>
            <a href="{{ route('profile.videos', $talent->id) }}">Videos Showcase</a>
            <a href="{{ route('home') }}" style="margin-top: 15px; background: rgba(255, 255, 255, 0.05); border-color: var(--border-color);">Back to Directory</a>
        </div>
    </div>

    <!-- Main Details -->
    <div class="pdetails">
        <h2>{{ $talent->name }}</h2>
        <p style="color: var(--text-muted); font-size: 1.05rem; margin-bottom: 25px;">
            {{ $talent->description ?: 'No bio biography has been provided by the user yet.' }}
        </p>
        
        <h3>Occupations</h3>
        <h5>{{ $talent->category_label }}</h5>

        <h3>Country</h3>
        <h5>{{ $talent->country }}</h5>

        <h3>Phone</h3>
        <h5>{{ $talent->phone ?: 'Not provided' }}</h5>

        <h3>Social Channels</h3>
        <div class="social-links">
            @if($talent->social_instagram)
                <a href="{{ $talent->social_instagram }}" target="_blank" class="social-btn">Instagram</a>
            @endif
            @if($talent->social_facebook)
                <a href="{{ $talent->social_facebook }}" target="_blank" class="social-btn">Facebook</a>
            @endif
            @if($talent->social_tiktok)
                <a href="{{ $talent->social_tiktok }}" target="_blank" class="social-btn">TikTok</a>
            @endif
            @if($talent->social_youtube)
                <a href="{{ $talent->social_youtube }}" target="_blank" class="social-btn">YouTube</a>
            @endif
            
            @if(!$talent->social_instagram && !$talent->social_facebook && !$talent->social_tiktok && !$talent->social_youtube)
                <span style="color: var(--text-muted); font-size: 0.85rem;">No social channels linked.</span>
            @endif
        </div>
    </div>
</main>

<!-- Mini gallery showcase at the bottom -->
<section class="media-container" style="margin-top: 40px;">
    <div class="section-header">
        <h2>Recent Photos</h2>
        <a href="{{ route('profile.photos', $talent->id) }}" class="back-link">See All Photos &rarr;</a>
    </div>
    <div class="photos-grid">
        @forelse($miniPhotos as $photo)
            <div class="photo-item">
                <img src="{{ $photo->file_path }}" alt="{{ $talent->name }} Photo">
            </div>
        @empty
            <div class="no-results" style="grid-column: 1/-1;">
                No portfolio photos uploaded yet.
            </div>
        @endforelse
    </div>
</section>
@endsection
