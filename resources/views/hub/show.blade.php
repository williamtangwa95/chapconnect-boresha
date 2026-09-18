@extends('layouts.app')

@section('title', $post->title . ' - ' . __('ChapConnect Hub'))

@section('styles')
<style>
    main.main,
    .main {
        display: block !important;
        width: 100% !important;
        max-width: 1000px !important;
        margin: 0 auto !important;
        padding: 20px 16px 80px 16px !important;
        box-sizing: border-box !important;
    }
</style>
@endsection

@section('content')
<main class="main">

    <!-- Breadcrumbs -->
    <nav style="display: flex; align-items: center; gap: 8px; font-size: 0.82rem; font-weight: 600; color: #64748b; margin-bottom: 20px; flex-wrap: wrap;">
        <a href="{{ route('home') }}" style="color: #64748b; text-decoration: none;">{{ __('Home') }}</a>
        <i class="bi bi-chevron-right" style="font-size: 0.7rem;"></i>
        <a href="{{ route('hub') }}" style="color: #64748b; text-decoration: none;">{{ __('ChapConnect Hub') }}</a>
        <i class="bi bi-chevron-right" style="font-size: 0.7rem;"></i>
        <a href="{{ route('hub', ['category' => $post->category]) }}" style="color: #6366f1; text-decoration: none;">{{ $post->category_details['label'] }}</a>
    </nav>

    <!-- Main Article Card -->
    <article style="background: #ffffff; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 6px 28px rgba(0,0,0,0.04); margin-bottom: 36px;">
        
        <!-- Header Top: Category Badge & Meta -->
        <div style="padding: 28px 28px 14px 28px;">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 12px; flex-wrap: wrap;">
                <span style="background: {{ $post->category_details['bg'] }}; color: {{ $post->category_details['color'] }}; border: 1px solid {{ $post->category_details['border'] }}; font-size: 0.8rem; font-weight: 800; padding: 4px 12px; border-radius: 12px; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="bi {{ $post->category_details['icon'] }}"></i> {{ $post->category_details['label'] }}
                </span>

                <div style="display: flex; align-items: center; gap: 12px; font-size: 0.82rem; color: #64748b; font-weight: 600;">
                    <span><i class="bi bi-calendar3"></i> {{ $post->created_at->format('d M Y') }}</span>
                    <span>•</span>
                    <span><i class="bi bi-eye"></i> {{ $post->views_count }} {{ __('views') }}</span>
                </div>
            </div>

            <h1 style="font-size: clamp(1.5rem, 3.2vw, 2.1rem); font-weight: 900; color: #0f172a; line-height: 1.25; margin: 0 0 14px 0; letter-spacing: -0.5px;">
                {{ $post->title }}
            </h1>

            @if($post->summary)
            <p style="font-size: 1.05rem; color: #475569; line-height: 1.6; margin: 0; font-weight: 500; border-left: 3px solid #6366f1; padding-left: 14px;">
                {{ $post->summary }}
            </p>
            @endif
        </div>

        <!-- Featured Media Section -->
        @if($post->video_url || $post->video_path)
        <div style="background: #0f172a; width: 100%; margin: 10px 0 20px 0; overflow: hidden; position: relative;">
            @if($post->video_url)
            <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                {!! \App\Helpers\VideoHelper::renderEmbed($post->video_url) !!}
            </div>
            @elseif($post->video_path)
            <video controls preload="metadata" style="width: 100%; max-height: 520px; display: block; margin: 0 auto;">
                <source src="{{ asset($post->video_path) }}" type="video/mp4">
                {{ __('Your browser does not support the video element.') }}
            </video>
            @endif
        </div>
        @elseif($post->featured_image)
        <div style="width: 100%; max-height: 480px; overflow: hidden; background: #0f172a; margin: 10px 0 20px 0;">
            <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" style="width: 100%; height: auto; max-height: 480px; object-fit: cover; display: block; margin: 0 auto;">
        </div>
        @endif

        <!-- Audio Player Block (If audio attached) -->
        @if($post->audio_path)
        <div style="margin: 0 28px 24px 28px; background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); border-radius: 18px; padding: 20px; color: #ffffff; box-shadow: 0 6px 20px rgba(49,46,129,0.25);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                <div style="width: 42px; height: 42px; border-radius: 50%; background: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="bi bi-headphones"></i>
                </div>
                <div>
                    <h4 style="margin: 0; font-size: 0.95rem; font-weight: 800; color: #ffffff;">{{ __('Official Audio Guide / Podcast') }}</h4>
                    <span style="font-size: 0.76rem; color: #c7d2fe;">{{ __('Listen directly using the player below') }}</span>
                </div>
            </div>
            <audio controls preload="auto" style="width: 100%; border-radius: 30px;">
                <source src="{{ asset($post->audio_path) }}">
                {{ __('Your browser does not support the audio element.') }}
            </audio>
        </div>
        @endif

        <!-- Full Rich Body Content -->
        <div style="padding: 10px 28px 28px 28px; font-size: 1.02rem; line-height: 1.8; color: #334155;">
            {!! nl2br(e($post->content)) !!}
        </div>

        <!-- Author & Interaction Footer -->
        <div style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 28px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: #0f172a; color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.95rem;">
                    CC
                </div>
                <div>
                    <strong style="display: block; font-size: 0.88rem; color: #0f172a;">{{ $post->author ? $post->author->name : 'ChapConnect Official' }}</strong>
                    <span style="font-size: 0.74rem; color: #64748b;">{{ __('Official Platform Post') }}</span>
                </div>
            </div>

            <!-- Like & Social Share -->
            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <button type="button" id="btnLikeHubPost" onclick="toggleHubLike({{ $post->id }})" style="background: #ffffff; border: 1px solid #cbd5e1; padding: 8px 16px; border-radius: 20px; font-size: 0.85rem; font-weight: 700; color: #475569; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
                    <i id="likeIcon" class="bi {{ session()->has('liked_hub_post_' . $post->id) ? 'bi-heart-fill' : 'bi-heart' }}" style="color: {{ session()->has('liked_hub_post_' . $post->id) ? '#ef4444' : '#64748b' }};"></i>
                    <span id="likeCount">{{ $post->likes_count }}</span> {{ __('Likes') }}
                </button>

                <!-- Share Buttons -->
                <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" style="background: #25d366; color: #ffffff; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 1rem;" title="Share on WhatsApp">
                    <i class="bi bi-whatsapp"></i>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" style="background: #1877f2; color: #ffffff; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 1rem;" title="Share on Facebook">
                    <i class="bi bi-facebook"></i>
                </a>
                <button type="button" onclick="navigator.clipboard.writeText('{{ url()->current() }}'); alert('Link copied to clipboard!');" style="background: #64748b; color: #ffffff; border: none; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 0.9rem;" title="Copy Link">
                    <i class="bi bi-link-45deg"></i>
                </button>
            </div>
        </div>
    </article>

    <!-- Related Hub Posts -->
    @if($relatedPosts->count() > 0)
    <div style="margin-top: 40px;">
        <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <i class="bi bi-collection-fill" style="color: #6366f1;"></i> {{ __('Related in') }} {{ $post->category_details['label'] }}
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
            @foreach($relatedPosts as $rel)
            <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.03); display: flex; flex-direction: column;">
                @if($rel->featured_image)
                <div style="width: 100%; aspect-ratio: 16/9; overflow: hidden; background: #0f172a;">
                    <img src="{{ asset($rel->featured_image) }}" alt="{{ $rel->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                @endif
                <div style="padding: 16px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <strong style="display: block; font-size: 0.95rem; color: #0f172a; margin-bottom: 6px; line-height: 1.3;">
                            <a href="{{ route('hub.show', $rel->slug) }}" style="color: inherit; text-decoration: none;">{{ $rel->title }}</a>
                        </strong>
                        <p style="font-size: 0.8rem; color: #64748b; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $rel->summary ?: Str::limit(strip_tags($rel->content), 80) }}
                        </p>
                    </div>
                    <div style="margin-top: 14px; display: flex; justify-content: space-between; align-items: center; font-size: 0.76rem; color: #94a3b8;">
                        <span>{{ $rel->created_at->format('d M Y') }}</span>
                        <a href="{{ route('hub.show', $rel->slug) }}" style="color: #6366f1; font-weight: 700; text-decoration: none;">{{ __('Read') }} →</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</main>

<script>
function toggleHubLike(postId) {
    if (typeof $ === 'undefined') return;
    $.ajax({
        url: "/hub/" + postId + "/like",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}"
        },
        dataType: "json",
        success: function(res) {
            if (res.success) {
                $('#likeCount').text(res.likes_count);
                if (res.liked) {
                    $('#likeIcon').removeClass('bi-heart').addClass('bi-heart-fill').css('color', '#ef4444');
                } else {
                    $('#likeIcon').removeClass('bi-heart-fill').addClass('bi-heart').css('color', '#64748b');
                }
            }
        }
    });
}
</script>
@endsection
