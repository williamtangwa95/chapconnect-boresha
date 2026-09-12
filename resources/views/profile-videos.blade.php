@extends('layouts.app')

@section('title', $talent->name . ' - Videos')

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
            <a href="{{ route('profile.photos', $talent->id) }}">Photos Gallery</a>
            <a class="active" href="{{ route('profile.videos', $talent->id) }}">Videos Showcase</a>
            <a href="{{ route('home') }}" style="margin-top: 15px; background: rgba(255, 255, 255, 0.05); border-color: var(--border-color);">Back to Directory</a>
        </div>
    </div>

    <!-- Videos Grid -->
    <div class="pdetails" style="padding: 30px;">
        <h2>{{ $talent->name }}'s Videos</h2>
        <p style="color: var(--text-muted); margin-bottom: 25px; font-size: 0.9rem;">Watch uploaded videos and showreel files.</p>
        
        <div class="videos-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
            @forelse($videos as $index => $video)
                <div class="video-item media-feed-item" data-media-id="{{ $video->id }}" style="position: relative; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; overflow: hidden; display: flex; flex-direction: column;">
                    {!! \App\Helpers\VideoHelper::renderEmbed($video->file_path) !!}
                    
                    <div style="padding: 12px 15px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color);">
                        <div>
                            <h4 style="font-size: 0.96rem; margin-bottom: 3px; color: var(--text-primary); font-weight: 700;">{{ $video->title ?: __('Video Showcase #' . ($index + 1)) }}</h4>
                            <p style="font-size: 0.78rem; color: var(--text-muted); margin: 0;">Added {{ $video->created_at->diffForHumans() }}</p>
                        </div>
                        <button type="button" onclick="openReportModal({{ $video->id }})" title="{{ __('Report Inappropriate Content') }}" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.25); border-radius: 20px; padding: 4px 10px; font-size: 0.75rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="bi bi-flag"></i> {{ __('Report') }}
                        </button>
                    </div>

                    <!-- Interactions Bar (Like, Comment, Share) -->
                    <div class="media-post-actions" style="display: flex; align-items: center; justify-content: space-around; padding: 8px 10px; background: rgba(248, 250, 252, 0.05); margin-top: auto;">
                        <button type="button" class="post-action-btn media-like-btn" id="mediaLikeBtn_{{ $video->id }}" onclick="toggleMediaLike({{ $video->id }})" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 5px; font-size: 0.82rem; font-weight: 700; color: var(--text-muted); transition: all 0.2s ease;">
                            <i class="bi bi-heart media-like-icon_{{ $video->id }}" style="font-size: 1.05rem;"></i>
                            <span id="mediaLikeCount_{{ $video->id }}">{{ $video->likes_count ?? 0 }}</span>
                        </button>

                        <button type="button" class="post-action-btn media-comment-btn" onclick="openMediaCommentsModal({{ $video->id }}, '{{ addslashes($video->title ?: 'Video Comment') }}')" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 5px; font-size: 0.82rem; font-weight: 700; color: var(--text-muted); transition: all 0.2s ease;">
                            <i class="bi bi-chat-dots" style="font-size: 1.05rem; color: #0284c7;"></i>
                            <span id="mediaCommentCount_{{ $video->id }}">{{ $video->comments_count ?? 0 }}</span>
                        </button>

                        <button type="button" class="post-action-btn media-share-btn" onclick="openMediaShareModal({{ $video->id }}, '{{ addslashes($video->title ?: $talent->name . '\'s Video') }}', '{{ route('profile.videos', $talent->id) }}')" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 5px; font-size: 0.82rem; font-weight: 700; color: var(--text-muted); transition: all 0.2s ease;">
                            <i class="bi bi-share-fill" style="font-size: 1.05rem; color: #6366f1;"></i>
                            <span id="mediaShareCount_{{ $video->id }}">{{ $video->shares_count ?? 0 }}</span>
                        </button>
                    </div>
                </div>
            @empty
                <div class="no-results" style="grid-column: 1/-1;">
                    {{ __('No videos uploaded to this portfolio yet.') }}
                </div>
            @endforelse
        </div>
    </div>
</main>
@endsection
