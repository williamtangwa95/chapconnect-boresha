@forelse($talents as $talent)
<div class="container">
    <div class="imagea">
        <img src="{{ $talent->avatar_url }}" alt="{{ $talent->name }}" loading="lazy">
        <div class="details">
            <h2 title="{{ $talent->name }}">{{ $talent->name }}</h2>
            <h5>{{ __($talent->category_label) }}</h5>

            <div class="like-container">
                <div class="like">
                    <button class="like-btn" id="likeBtn_{{ $talent->id }}" onclick="toggleCardLike({{ $talent->id }})"><i class="bi bi-heart" style="margin-right: 4px;"></i>{{ __('Like') }}</button>
                    <span class="like-count" id="likeCount_{{ $talent->id }}">{{ $talent->likes_received_count ?? 0 }}</span>
                </div>
                <div class="comment">
                    <a href="{{ route('profile', $talent->id) }}#comments-tab" style="text-decoration:none;">
                        <button class="comment-btn {{ ($talent->comments_received_count ?? 0) > 0 ? 'has-comments' : '' }}" id="commentBtn_{{ $talent->id }}"><i class="bi bi-chat-dots" style="margin-right: 4px; color: #0284c7;"></i>{{ __('Comments') }}</button>
                    </a>
                    <span class="comment-count {{ ($talent->comments_received_count ?? 0) > 0 ? 'has-comments' : '' }}" id="commentCount_{{ $talent->id }}">{{ $talent->comments_received_count ?? 0 }}</span>
                </div>
                <div class="follow">
                    <button class="follow-btn" id="followBtn_{{ $talent->id }}" onclick="toggleCardFollow({{ $talent->id }})"><i class="bi bi-person-plus" style="margin-right: 4px;"></i>{{ __('Followers') }}</button>
                    <span class="followers-count" id="followersCount_{{ $talent->id }}">{{ $talent->followers_received_count ?? 0 }}</span>
                </div>
            </div>

            <a href="{{ route('profile', $talent->id) }}" class="vbtn">{{ __('View Full Profile') }}</a>

            <div class="card-quick-links">
                <a href="{{ route('profile', $talent->id) }}#photos-tab"><span class="bi bi-camera"></span> {{ __('Photos') }}</a>
                <a href="{{ route('profile', $talent->id) }}#videos-tab"><span class="bi bi-camera-video"></span> {{ __('Videos') }}</a>
                <a href="{{ route('profile', $talent->id) }}#news-tab"><span class="bi bi-newspaper"></span> {{ __('News') }}</a>
            </div>
        </div>
    </div>
</div>
@empty
@if(!request()->ajax())
<div class="no-results" style="grid-column: 1/-1; text-align: center; padding: 50px 20px; background: white; border-radius: 16px; box-shadow: var(--shadow);">
    <i class="bi bi-people" style="font-size: 48px; color: var(--primary); display: block; margin-bottom: 10px;"></i>
    <h3 style="color: var(--text-main); margin-bottom: 5px;">{{ __('No Talents Found') }}</h3>
    <p style="color: var(--text-muted); font-size: 14px;">{{ __('No talents registered yet under this category selection.') }}</p>
</div>
@endif
@endforelse
