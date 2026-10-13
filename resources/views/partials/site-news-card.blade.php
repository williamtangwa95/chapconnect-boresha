<div class="media-feed-item site-news-item talent-card-anim" data-news-id="{{ $news->id }}" style="border: 1px solid rgba(245, 158, 11, 0.35); box-shadow: 0 4px 14px rgba(245, 158, 11, 0.08); background: #ffffff;">
    <!-- Site News Header -->
    <div class="media-feed-user" style="background: linear-gradient(135deg, rgba(254, 243, 199, 0.6) 0%, rgba(255, 251, 235, 0.4) 100%); border-bottom: 1px solid rgba(245, 158, 11, 0.2); padding: 10px 12px; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 9px;">
            <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); display: flex; align-items: center; justify-content: center; color: #ffffff; box-shadow: 0 2px 6px rgba(245, 158, 11, 0.35); flex-shrink: 0;">
                <i class="bi bi-megaphone-fill" style="font-size: 1rem;"></i>
            </div>
            <div style="display: flex; flex-direction: column;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="font-size: 0.86rem; font-weight: 700; color: #0f172a;">{{ $news->author_name }}</span>
                    <i class="bi bi-patch-check-fill" style="color: #0284c7; font-size: 0.8rem;" title="{{ __('Official') }}"></i>
                </div>
                <span style="font-size: 0.72rem; font-weight: 600; color: #b45309; display: inline-flex; align-items: center; gap: 4px;">
                    <i class="bi bi-broadcast"></i> {{ __('Site News') }}
                </span>
            </div>
        </div>
        <span style="font-size: 0.72rem; color: #94a3b8; font-weight: 500;">
            {{ $news->created_at->diffForHumans() }}
        </span>
    </div>

    <!-- Media Content Section based on type -->
    @if(in_array($news->type, ['photo', 'text_photo']) && !empty($news->image_path))
    <div class="media-feed-content" style="background: #000000; position: relative;">
        <img class="media-feed-image" src="{{ asset($news->image_path) }}" alt="{{ $news->title ?: 'Site News Image' }}" loading="lazy" style="width: 100%; max-height: 480px; object-fit: contain; display: block;">
    </div>
    @endif

    @if(in_array($news->type, ['video', 'text_video']) && !empty($news->video_source))
    <div class="media-feed-content" style="background: #000000; position: relative;">
        <div class="media-feed-video" style="width: 100%;">
            {!! \App\Helpers\VideoHelper::renderEmbed($news->video_source) !!}
        </div>
    </div>
    @endif

    <!-- Text / Info Section -->
    <div class="media-feed-info" style="padding: 12px 14px;">
        @if($news->title)
        <h4 class="media-feed-title" style="margin: 0 0 6px 0; font-size: 0.95rem; font-weight: 800; color: #0f172a; line-height: 1.35;">
            {{ $news->title }}
        </h4>
        @endif

        @if($news->body)
        <div class="news-body-container" style="font-size: 0.84rem; color: #334155; line-height: 1.55; white-space: pre-line;">
            @if(strlen($news->body) > 260)
            <div id="newsBodyShort_{{ $news->id }}">
                {{ Str::limit($news->body, 240) }}
                <a href="javascript:void(0)" onclick="$('#newsBodyShort_{{ $news->id }}').hide(); $('#newsBodyFull_{{ $news->id }}').show();" style="color: #f59e0b; font-weight: 700; text-decoration: none; margin-left: 4px;">{{ __('Read more') }} &rarr;</a>
            </div>
            <div id="newsBodyFull_{{ $news->id }}" style="display: none;">
                {{ $news->body }}
                <a href="javascript:void(0)" onclick="$('#newsBodyFull_{{ $news->id }}').hide(); $('#newsBodyShort_{{ $news->id }}').show();" style="color: #f59e0b; font-weight: 700; text-decoration: none; margin-left: 4px;">{{ __('Show less') }}</a>
            </div>
            @else
            {{ $news->body }}
            @endif
        </div>
        @endif

        <div class="media-feed-meta" style="margin-top: 10px; display: flex; align-items: center; justify-content: space-between;">
            <span class="media-feed-badge" style="background: #fef3c7; color: #b45309; font-weight: 700; font-size: 0.72rem; padding: 3px 8px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;">
                <i class="bi bi-megaphone"></i> {{ strtoupper(str_replace('_', ' + ', $news->type)) }}
            </span>
            <span style="font-size: 0.75rem; color: #94a3b8; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                <i class="bi bi-eye"></i> <span id="newsViewsCount_{{ $news->id }}">{{ $news->views_count }}</span> {{ __('views') }}
            </span>
        </div>
    </div>

    <!-- Actions Toolbar: Like and Share ONLY (No Comments) -->
    @php
        $isNewsLiked = $news->isLikedBy(auth()->id(), request()->ip());
    @endphp
    <div class="media-post-actions" style="display: flex; align-items: center; justify-content: space-around; padding: 10px 14px; border-top: 1px solid rgba(245, 158, 11, 0.18); background: rgba(254, 243, 199, 0.25); border-radius: 0 0 12px 12px;">
        <!-- Like Action -->
        <button type="button" class="post-action-btn site-news-like-btn" id="newsLikeBtn_{{ $news->id }}" onclick="toggleNewsLike({{ $news->id }})" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 0.85rem; font-weight: 700; color: {{ $isNewsLiked ? '#ef4444' : '#64748b' }}; transition: all 0.2s ease;">
            <i class="bi {{ $isNewsLiked ? 'bi-heart-fill' : 'bi-heart' }} news-like-icon_{{ $news->id }}" style="font-size: 1.05rem;"></i>
            <span id="newsLikeCount_{{ $news->id }}">{{ $news->likes_count ?? $news->likes()->count() }}</span>
        </button>

        <!-- Share Action -->
        <button type="button" class="post-action-btn site-news-share-btn" onclick="openMediaShareModal({{ $news->id }}, '{{ addslashes($news->title ?: 'ChapConnect Official News') }}', '{{ url('/') }}#news-{{ $news->id }}', 'news')" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 0.85rem; font-weight: 700; color: #64748b; transition: all 0.2s ease;">
            <i class="bi bi-share-fill" style="font-size: 1.05rem; color: #6366f1;"></i>
            <span id="newsShareCount_{{ $news->id }}">{{ $news->shares_count ?? $news->shares()->count() }}</span>
        </button>
    </div>
</div>
