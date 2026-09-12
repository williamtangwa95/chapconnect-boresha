@extends('layouts.app')

@section('title', $talent->name . ' - Photos')

@section('content')
<main class="profile-hero">
    <!-- Sidebar profile card -->
    <div class="profile-sidebar">
        <div class="pimage">
                <img src="{{ $talent->avatar_url }}" alt="{{ $talent->name }}">
        </div>
        <h2>{{ $talent->name }}</h2>
        <h5>{{ $talent->category_label }}</h5>
        
        <div class="profile-menu-vertical">
            <a href="{{ route('profile', $talent->id) }}">Overview</a>
            <a class="active" href="{{ route('profile.photos', $talent->id) }}">Photos Gallery</a>
            <a href="{{ route('profile.videos', $talent->id) }}">Videos Showcase</a>
            <a href="{{ route('home') }}" style="margin-top: 15px; background: rgba(255, 255, 255, 0.05); border-color: var(--border-color);">Back to Directory</a>
        </div>
    </div>

    <!-- Photos Grid -->
    <div class="pdetails" style="padding: 30px;">
        <h2>{{ $talent->name }}'s Photos</h2>
        <p style="color: var(--text-muted); margin-bottom: 20px; font-size: 0.9rem;">Browse visual work and portfolio uploads.</p>
        
        <div class="photos-grid">
            @forelse($photos as $photo)
                <div class="photo-item media-feed-item" data-media-id="{{ $photo->id }}" style="position: relative; overflow: hidden; border-radius: 12px; background: var(--bg-card); border: 1px solid var(--border-color); display: flex; flex-direction: column;">
                    <div style="position: relative; aspect-ratio: 4/3; overflow: hidden;">
                        <img src="{{ asset($photo->file_path) }}" alt="{{ $photo->title ?: $talent->name . ' Portfolio Photo' }}" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                        <button type="button" onclick="openReportModal({{ $photo->id }})" title="{{ __('Report Inappropriate Content') }}" style="position: absolute; top: 8px; right: 8px; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); color: #f87171; border: 1px solid rgba(248, 113, 113, 0.4); border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 0.75rem; transition: all 0.2s ease;">
                            <i class="bi bi-flag"></i>
                        </button>
                    </div>

                    <!-- Photo Details & Caption -->
                    @if($photo->title || $photo->content)
                    <div style="padding: 10px 12px; border-bottom: 1px solid var(--border-color);">
                        @if($photo->title)<h4 style="font-size: 0.88rem; font-weight: 700; margin: 0 0 2px 0;">{{ $photo->title }}</h4>@endif
                        @if($photo->content)<p style="font-size: 0.78rem; color: var(--text-muted); margin: 0;">{{ $photo->content }}</p>@endif
                    </div>
                    @endif

                    <!-- Interactions Bar (Like, Comment, Share) -->
                    <div class="media-post-actions" style="display: flex; align-items: center; justify-content: space-around; padding: 8px 10px; background: rgba(248, 250, 252, 0.05); border-top: 1px solid var(--border-color); margin-top: auto;">
                        <button type="button" class="post-action-btn media-like-btn" id="mediaLikeBtn_{{ $photo->id }}" onclick="toggleMediaLike({{ $photo->id }})" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 5px; font-size: 0.82rem; font-weight: 700; color: var(--text-muted); transition: all 0.2s ease;">
                            <i class="bi bi-heart media-like-icon_{{ $photo->id }}" style="font-size: 1.05rem;"></i>
                            <span id="mediaLikeCount_{{ $photo->id }}">{{ $photo->likes_count ?? 0 }}</span>
                        </button>

                        <button type="button" class="post-action-btn media-comment-btn" onclick="openMediaCommentsModal({{ $photo->id }}, '{{ addslashes($photo->title ?: 'Photo Comment') }}')" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 5px; font-size: 0.82rem; font-weight: 700; color: var(--text-muted); transition: all 0.2s ease;">
                            <i class="bi bi-chat-dots" style="font-size: 1.05rem; color: #0284c7;"></i>
                            <span id="mediaCommentCount_{{ $photo->id }}">{{ $photo->comments_count ?? 0 }}</span>
                        </button>

                        <button type="button" class="post-action-btn media-share-btn" onclick="openMediaShareModal({{ $photo->id }}, '{{ addslashes($photo->title ?: $talent->name . '\'s Photo') }}', '{{ route('profile.photos', $talent->id) }}')" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 5px; font-size: 0.82rem; font-weight: 700; color: var(--text-muted); transition: all 0.2s ease;">
                            <i class="bi bi-share-fill" style="font-size: 1.05rem; color: #6366f1;"></i>
                            <span id="mediaShareCount_{{ $photo->id }}">{{ $photo->shares_count ?? 0 }}</span>
                        </button>
                    </div>
                </div>
            @empty
                <div class="no-results" style="grid-column: 1/-1;">
                    {{ __('No photos uploaded to this portfolio yet.') }}
                </div>
            @endforelse
        </div>
    </div>
</main>
@endsection
