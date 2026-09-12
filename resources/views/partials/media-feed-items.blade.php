@forelse($recentMedia as $media)
<div class="media-feed-item" data-media-id="{{ $media->id }}">
    <!-- User Info Header -->
    <div class="media-feed-user">
        <img class="media-feed-avatar" src="{{ $media->user->avatar_url }}" alt="{{ $media->user->name }}" loading="lazy">
        <div class="media-feed-user-info">
            <a class="media-feed-username" href="{{ route('profile', $media->user->id) }}">
                {{ $media->user->name }}
            </a>
            <span class="media-feed-userrole">{{ $media->user->category_label }}</span>
        </div>
    </div>

    <!-- Media Content (Photo, Video, or Attachment) -->
    @if(!empty($media->file_path))
    <div class="media-feed-content">
        @if($media->type === 'photo')
        <a href="{{ route('profile', $media->user->id) }}#photos-tab">
            <img class="media-feed-image" src="{{ asset($media->file_path) }}" alt="{{ $media->title ?: 'Portfolio image' }}" loading="lazy">
        </a>
        @elseif($media->type === 'video')
        <div class="media-feed-video">
            {!! \App\Helpers\VideoHelper::renderEmbed($media->file_path) !!}
        </div>
        @elseif($media->type === 'news')
        @if(Str::contains($media->file_path, ['.mp4', '.mov', '.webm', 'youtube.com', 'youtu.be', 'vimeo.com']))
        <div class="media-feed-video">
            {!! \App\Helpers\VideoHelper::renderEmbed($media->file_path) !!}
        </div>
        @else
        <a href="{{ route('profile', $media->user->id) }}#news-tab">
            <img class="media-feed-image" src="{{ asset($media->file_path) }}" alt="{{ $media->title ?: 'News image' }}" loading="lazy">
        </a>
        @endif
        @endif
    </div>
    @endif

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
                <i class="bi bi-{{ $media->type === 'photo' ? 'image' : ($media->type === 'news' ? 'newspaper' : 'film') }}"></i> {{ $media->type }}
            </span>
            <span>{{ $media->created_at->diffForHumans() }}</span>
        </div>
    </div>

    <!-- Post Interactions Toolbar (Like, Comment, Share) -->
    <div class="media-post-actions" style="display: flex; align-items: center; justify-content: space-around; padding: 10px 12px; border-top: 1px solid rgba(203, 213, 225, 0.4); margin-top: 8px; background: rgba(248, 250, 252, 0.8); border-radius: 0 0 12px 12px;">
        <button type="button" class="post-action-btn media-like-btn" id="mediaLikeBtn_{{ $media->id }}" onclick="toggleMediaLike({{ $media->id }})" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 5px; font-size: 0.82rem; font-weight: 700; color: #64748b; transition: all 0.2s ease;">
            <i class="bi bi-heart media-like-icon_{{ $media->id }}" style="font-size: 1.05rem;"></i>
            <span id="mediaLikeCount_{{ $media->id }}">{{ $media->likes_count ?? 0 }}</span>
        </button>

        <button type="button" class="post-action-btn media-comment-btn" onclick="openMediaCommentsModal({{ $media->id }}, '{{ addslashes($media->title ?: 'Post Comment') }}')" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 5px; font-size: 0.82rem; font-weight: 700; color: #64748b; transition: all 0.2s ease;">
            <i class="bi bi-chat-dots" style="font-size: 1.05rem; color: #0284c7;"></i>
            <span id="mediaCommentCount_{{ $media->id }}">{{ $media->comments_count ?? 0 }}</span>
        </button>

        <button type="button" class="post-action-btn media-share-btn" onclick="openMediaShareModal({{ $media->id }}, '{{ addslashes($media->title ?: 'ChapConnect Post') }}', '{{ route('profile', $media->user_id) }}')" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 5px; font-size: 0.82rem; font-weight: 700; color: #64748b; transition: all 0.2s ease;">
            <i class="bi bi-share-fill" style="font-size: 1.05rem; color: #6366f1;"></i>
            <span id="mediaShareCount_{{ $media->id }}">{{ $media->shares_count ?? 0 }}</span>
        </button>
    </div>
</div>
@empty
<div style="text-align: center; padding: 30px 10px; color: var(--text-muted);">
    <i class="bi bi-images" style="font-size: 2.5rem; color: #cbd5e1; display: block; margin-bottom: 8px;"></i>
    <p style="font-size: 0.88rem; font-weight: 500;">No photos or videos uploaded yet.</p>
</div>
@endforelse
