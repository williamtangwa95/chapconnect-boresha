@extends('layouts.app')

@section('title', __('ChapConnect Hub - Learn, News, Tutorials & Testimonies'))

@section('styles')
<style>
    /* Force main container to block layout so children stack vertically */
    main.main,
    .main {
        display: block !important;
        width: 100% !important;
        max-width: 1300px !important;
        margin: 0 auto !important;
        padding: 20px 16px 80px 16px !important;
        box-sizing: border-box !important;
    }

    .hub-hero-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 60%, #311042 100%);
        border-radius: 20px;
        padding: 32px 28px;
        color: #ffffff;
        margin-bottom: 28px;
        box-shadow: 0 16px 40px rgba(15,23,42,0.16);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.12);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 28px;
        flex-wrap: wrap;
    }

    .hub-hero-glow-1 {
        position: absolute;
        top: -80px;
        right: -60px;
        width: 320px;
        height: 320px;
        background: radial-gradient(circle, rgba(99,102,241,0.35) 0%, rgba(99,102,241,0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .hub-hero-glow-2 {
        position: absolute;
        bottom: -100px;
        left: 20%;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(236,72,153,0.3) 0%, rgba(236,72,153,0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .hub-pill-btn {
        padding: 9px 18px;
        border-radius: 12px;
        font-size: 0.86rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }

    .hub-pill-btn:hover {
        transform: translateY(-2px);
    }

    .hub-post-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        display: flex;
        flex-direction: column;
        transition: all 0.25s ease;
    }

    .hub-post-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(99,102,241,0.12);
        border-color: #cbd5e1;
    }

    .hub-post-card:hover .hub-post-img {
        transform: scale(1.04);
    }

    .hub-feature-badge {
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 14px;
        padding: 10px 14px;
    }

    @media (max-width: 900px) {
        .hub-hero-card {
            padding: 24px 18px;
        }
    }
</style>
@endsection

@section('content')
<main class="main">

    <!-- ====================================================
         HERO SECTION: ABOUT CHAPCONNECT & LEARNING CENTER
         ==================================================== -->
    <section class="hub-hero-card">
        <div class="hub-hero-glow-1"></div>
        <div class="hub-hero-glow-2"></div>

        <div style="position: relative; z-index: 2; flex: 1 1 500px; max-width: 680px;">
            <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(99,102,241,0.25); border: 1px solid rgba(165,180,252,0.35); padding: 4px 12px; border-radius: 20px; font-size: 0.72rem; font-weight: 800; color: #c7d2fe; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">
                <i class="bi bi-stars" style="color: #fbbf24; font-size: 0.75rem;"></i> {{ __('Official ChapConnect Hub') }}
            </div>

            <h1 style="font-size: clamp(1.35rem, 2.4vw, 1.85rem); font-weight: 800; line-height: 1.25; margin: 0 0 12px 0; color: #ffffff; letter-spacing: -0.3px;">
                {{ __('Learn, Discover & Grow with') }} <span style="background: linear-gradient(135deg, #a5b4fc 0%, #f472b6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">ChapConnect</span>
            </h1>

            <p style="font-size: clamp(0.82rem, 1.05vw, 0.88rem); color: #cbd5e1; line-height: 1.6; margin: 0 0 20px 0;">
                {{ __('ChapConnect is Tanzania\'s premier creative talent ecosystem, seamlessly connecting singers, dancers, actors, comedians, MCs, DJs, and media creators with clients, event managers, and producers. Access official step-by-step guides, audio voice lessons, platform news, and inspiring user testimonies right here.') }}
            </p>

            <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                <a href="{{ route('register') }}" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; padding: 10px 20px; border-radius: 12px; font-weight: 700; font-size: 0.82rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 14px rgba(99,102,241,0.4); transition: transform 0.2s ease;">
                    <i class="bi bi-person-plus-fill"></i> {{ __('Join as Talent / Client') }}
                </a>
                <a href="{{ route('home') }}" style="background: rgba(255,255,255,0.12); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.22); color: #ffffff; padding: 10px 18px; border-radius: 12px; font-weight: 600; font-size: 0.82rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="bi bi-grid-fill"></i> {{ __('Browse Public Directory') }}
                </a>
            </div>
        </div>

        <!-- Right Side Highlight Stats -->
        <div style="position: relative; z-index: 2; flex: 1 1 280px; display: flex; flex-direction: column; gap: 10px;">
            <div class="hub-feature-badge">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); display: flex; align-items: center; justify-content: center; font-size: 1.05rem; flex-shrink: 0; box-shadow: 0 4px 10px rgba(16,185,129,0.3);">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
                <div>
                    <strong style="display: block; font-size: 0.84rem; color: #ffffff;">{{ __('100% Verified Profiles') }}</strong>
                    <span style="font-size: 0.72rem; color: #94a3b8;">{{ __('Authentic portfolios and direct contacts') }}</span>
                </div>
            </div>

            <div class="hub-feature-badge">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); display: flex; align-items: center; justify-content: center; font-size: 1.05rem; flex-shrink: 0; box-shadow: 0 4px 10px rgba(139,92,246,0.3);">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div>
                    <strong style="display: block; font-size: 0.84rem; color: #ffffff;">{{ __('Free Educational Hub') }}</strong>
                    <span style="font-size: 0.72rem; color: #94a3b8;">{{ __('Guides, audios & showreel tips') }}</span>
                </div>
            </div>

            <div class="hub-feature-badge">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #ec4899 0%, #f43f5e 100%); display: flex; align-items: center; justify-content: center; font-size: 1.05rem; flex-shrink: 0; box-shadow: 0 4px 10px rgba(236,72,153,0.3);">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div>
                    <strong style="display: block; font-size: 0.84rem; color: #ffffff;">{{ __('Direct Bookings & Payouts') }}</strong>
                    <span style="font-size: 0.72rem; color: #94a3b8;">{{ __('Connect directly with zero middleman fees') }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ====================================================
         PINNED / FEATURED STORY BANNER (IF AVAILABLE)
         ==================================================== -->
    @if($pinnedPost)
    <section style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 18px; border: 1.5px solid #e0e7ff; padding: 20px 24px; margin-bottom: 28px; box-shadow: 0 6px 24px rgba(99,102,241,0.06); display: flex; flex-wrap: wrap; gap: 20px; align-items: center;">
        @if($pinnedPost->featured_image)
        <div style="flex: 1 1 300px; max-width: 400px; aspect-ratio: 16/9; border-radius: 14px; overflow: hidden; background: #0f172a; position: relative;">
            <img src="{{ asset($pinnedPost->featured_image) }}" alt="{{ $pinnedPost->title }}" style="width: 100%; height: 100%; object-fit: cover;">
            <span style="position: absolute; top: 10px; left: 10px; background: #ef4444; color: #fff; font-size: 0.68rem; font-weight: 800; padding: 3px 10px; border-radius: 16px; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 6px rgba(0,0,0,0.25);">
                <i class="bi bi-pin-angle-fill"></i> {{ __('FEATURED SPOTLIGHT') }}
            </span>
        </div>
        @endif
        <div style="flex: 2 1 300px;">
            <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 8px; flex-wrap: wrap;">
                <span style="background: {{ $pinnedPost->category_details['bg'] }}; color: {{ $pinnedPost->category_details['color'] }}; border: 1px solid {{ $pinnedPost->category_details['border'] }}; font-size: 0.72rem; font-weight: 800; padding: 3px 10px; border-radius: 10px; display: inline-flex; align-items: center; gap: 4px;">
                    <i class="bi {{ $pinnedPost->category_details['icon'] }}"></i> {{ $pinnedPost->category_details['label'] }}
                </span>
                <span style="font-size: 0.74rem; color: #64748b; font-weight: 600;"><i class="bi bi-clock"></i> {{ $pinnedPost->created_at->format('d M Y') }}</span>
                <span style="font-size: 0.74rem; color: #64748b; font-weight: 600;"><i class="bi bi-eye"></i> {{ $pinnedPost->views_count }} {{ __('views') }}</span>
            </div>

            <h2 style="font-size: clamp(1.1rem, 2vw, 1.35rem); font-weight: 800; color: #0f172a; margin: 0 0 8px 0; line-height: 1.3;">
                <a href="{{ route('hub.show', $pinnedPost->slug) }}" style="color: inherit; text-decoration: none;">{{ $pinnedPost->title }}</a>
            </h2>

            <p style="font-size: 0.86rem; color: #475569; line-height: 1.55; margin: 0 0 14px 0;">
                {{ $pinnedPost->summary ?: Str::limit(strip_tags($pinnedPost->content), 190) }}
            </p>

            <div>
                <a href="{{ route('hub.show', $pinnedPost->slug) }}" style="background: #6366f1; color: #ffffff; padding: 8px 18px; border-radius: 10px; font-weight: 700; font-size: 0.82rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 3px 12px rgba(99,102,241,0.25);">
                    {{ __('Read Full Story') }} <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- ====================================================
         FILTER TABS & SEARCH BAR
         ==================================================== -->
    <section style="background: #ffffff; border-radius: 20px; padding: 20px 24px; border: 1px solid #e2e8f0; box-shadow: 0 4px 18px rgba(0,0,0,0.03); margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            
            <!-- Category Pills -->
            <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                <a href="{{ route('hub', ['category' => 'all', 'type' => $mediaType, 'q' => $search]) }}" 
                   class="hub-pill-btn"
                   style="{{ $category === 'all' ? 'background: #0f172a; color: #ffffff; box-shadow: 0 3px 10px rgba(15,23,42,0.25);' : 'background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;' }}">
                    <i class="bi bi-grid-fill"></i> {{ __('All Hub Content') }} <span style="background: rgba(255,255,255,0.2); padding: 1px 7px; border-radius: 10px; font-size: 0.74rem;">{{ $counts['all'] }}</span>
                </a>

                <a href="{{ route('hub', ['category' => 'tutorial', 'type' => $mediaType, 'q' => $search]) }}" 
                   class="hub-pill-btn"
                   style="{{ $category === 'tutorial' ? 'background: #6366f1; color: #ffffff; box-shadow: 0 3px 10px rgba(99,102,241,0.3);' : 'background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;' }}">
                    <i class="bi bi-mortarboard-fill"></i> {{ __('Tutorials & Guides') }} <span style="background: rgba(255,255,255,0.2); padding: 1px 7px; border-radius: 10px; font-size: 0.74rem;">{{ $counts['tutorial'] }}</span>
                </a>

                <a href="{{ route('hub', ['category' => 'news', 'type' => $mediaType, 'q' => $search]) }}" 
                   class="hub-pill-btn"
                   style="{{ $category === 'news' ? 'background: #16a34a; color: #ffffff; box-shadow: 0 3px 10px rgba(22,163,74,0.3);' : 'background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;' }}">
                    <i class="bi bi-newspaper"></i> {{ __('News') }} <span style="background: rgba(255,255,255,0.2); padding: 1px 7px; border-radius: 10px; font-size: 0.74rem;">{{ $counts['news'] }}</span>
                </a>

                <a href="{{ route('hub', ['category' => 'announcement', 'type' => $mediaType, 'q' => $search]) }}" 
                   class="hub-pill-btn"
                   style="{{ $category === 'announcement' ? 'background: #d97706; color: #ffffff; box-shadow: 0 3px 10px rgba(217,119,6,0.3);' : 'background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;' }}">
                    <i class="bi bi-megaphone-fill"></i> {{ __('Announcements') }} <span style="background: rgba(255,255,255,0.2); padding: 1px 7px; border-radius: 10px; font-size: 0.74rem;">{{ $counts['announcement'] }}</span>
                </a>

                <a href="{{ route('hub', ['category' => 'testimony', 'type' => $mediaType, 'q' => $search]) }}" 
                   class="hub-pill-btn"
                   style="{{ $category === 'testimony' ? 'background: #db2777; color: #ffffff; box-shadow: 0 3px 10px rgba(219,39,119,0.3);' : 'background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;' }}">
                    <i class="bi bi-trophy-fill"></i> {{ __('Testimonies') }} <span style="background: rgba(255,255,255,0.2); padding: 1px 7px; border-radius: 10px; font-size: 0.74rem;">{{ $counts['testimony'] }}</span>
                </a>
            </div>

            <!-- Search Form -->
            <form action="{{ route('hub') }}" method="GET" style="display: flex; gap: 8px; flex: 1; min-width: 240px; max-width: 360px;">
                <input type="hidden" name="category" value="{{ $category }}">
                <input type="hidden" name="type" value="{{ $mediaType }}">
                <input type="text" name="q" value="{{ $search }}" placeholder="{{ __('Search guides, news, audio...') }}" style="width: 100%; padding: 10px 16px; border: 1px solid #cbd5e1; border-radius: 12px; font-size: 0.88rem; box-sizing: border-box;">
                <button type="submit" style="background: #6366f1; color: #ffffff; border: none; padding: 10px 18px; border-radius: 12px; font-weight: 700; cursor: pointer;">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>

        <!-- Secondary Media Format Filter Pills -->
        <div style="margin-top: 16px; padding-top: 14px; border-top: 1px solid #f1f5f9; display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
            <span style="font-size: 0.8rem; font-weight: 700; color: #64748b; margin-right: 6px;">{{ __('Media Format:') }}</span>
            
            <a href="{{ route('hub', ['category' => $category, 'type' => 'all', 'q' => $search]) }}" style="font-size: 0.78rem; font-weight: 700; padding: 5px 12px; border-radius: 18px; text-decoration: none; {{ $mediaType === 'all' ? 'background: #6366f1; color: #ffffff;' : 'background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">
                {{ __('All Formats') }}
            </a>
            
            <a href="{{ route('hub', ['category' => $category, 'type' => 'audio', 'q' => $search]) }}" style="font-size: 0.78rem; font-weight: 700; padding: 5px 12px; border-radius: 18px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; {{ $mediaType === 'audio' ? 'background: #8b5cf6; color: #ffffff;' : 'background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">
                <i class="bi bi-music-note-beamed"></i> {{ __('Audios / Podcasts') }}
            </a>
            
            <a href="{{ route('hub', ['category' => $category, 'type' => 'video', 'q' => $search]) }}" style="font-size: 0.78rem; font-weight: 700; padding: 5px 12px; border-radius: 18px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; {{ $mediaType === 'video' ? 'background: #ef4444; color: #ffffff;' : 'background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">
                <i class="bi bi-play-circle-fill"></i> {{ __('Videos & Clips') }}
            </a>
            
            <a href="{{ route('hub', ['category' => $category, 'type' => 'photo', 'q' => $search]) }}" style="font-size: 0.78rem; font-weight: 700; padding: 5px 12px; border-radius: 18px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; {{ $mediaType === 'photo' ? 'background: #3b82f6; color: #ffffff;' : 'background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">
                <i class="bi bi-image-fill"></i> {{ __('Photos / Posters') }}
            </a>
            
            <a href="{{ route('hub', ['category' => $category, 'type' => 'article', 'q' => $search]) }}" style="font-size: 0.78rem; font-weight: 700; padding: 5px 12px; border-radius: 18px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; {{ $mediaType === 'article' ? 'background: #475569; color: #ffffff;' : 'background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">
                <i class="bi bi-file-text-fill"></i> {{ __('Written Articles') }}
            </a>
        </div>
    </section>

    <!-- ====================================================
         POSTS GRID (PROPER FULL-WIDTH 3 COLUMNS)
         ==================================================== -->
    @if($posts->count() > 0)
    <section style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 26px; margin-bottom: 40px; width: 100%;">
        @foreach($posts as $post)
        <article class="hub-post-card">
            
            <!-- Media Preview Header -->
            @if($post->featured_image)
            <div style="width: 100%; aspect-ratio: 16/9; overflow: hidden; position: relative; background: #0f172a;">
                <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="hub-post-img" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.35s ease;">
                <span style="position: absolute; top: 12px; left: 12px; background: {{ $post->category_details['bg'] }}; color: {{ $post->category_details['color'] }}; border: 1px solid {{ $post->category_details['border'] }}; font-size: 0.74rem; font-weight: 800; padding: 4px 10px; border-radius: 10px; display: inline-flex; align-items: center; gap: 4px; backdrop-filter: blur(4px);">
                    <i class="bi {{ $post->category_details['icon'] }}"></i> {{ $post->category_details['short_label'] }}
                </span>
                <span style="position: absolute; bottom: 10px; right: 10px; background: rgba(15,23,42,0.85); color: #fff; font-size: 0.72rem; font-weight: 700; padding: 3px 9px; border-radius: 8px; display: inline-flex; align-items: center; gap: 4px;">
                    <i class="bi {{ $post->media_type_details['icon'] }}"></i> {{ $post->media_type_details['label'] }}
                </span>
            </div>
            @elseif($post->audio_path)
            <div style="background: linear-gradient(135deg, #1e1b4b 0%, #4338ca 100%); padding: 26px 22px; color: #ffffff; display: flex; align-items: center; gap: 16px;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                    <i class="bi bi-music-note-beamed"></i>
                </div>
                <div>
                    <span style="font-size: 0.74rem; font-weight: 800; text-transform: uppercase; color: #a5b4fc; display: block;">{{ __('Audio Guide') }}</span>
                    <strong style="font-size: 1rem; color: #ffffff;">{{ Str::limit($post->title, 45) }}</strong>
                </div>
            </div>
            @elseif($post->video_url || $post->video_path)
            <div style="background: linear-gradient(135deg, #881337 0%, #be123c 100%); padding: 26px 22px; color: #ffffff; display: flex; align-items: center; gap: 16px;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                    <i class="bi bi-play-btn-fill"></i>
                </div>
                <div>
                    <span style="font-size: 0.74rem; font-weight: 800; text-transform: uppercase; color: #fecdd3; display: block;">{{ __('Video Tutorial') }}</span>
                    <strong style="font-size: 1rem; color: #ffffff;">{{ Str::limit($post->title, 45) }}</strong>
                </div>
            </div>
            @endif

            <!-- Body Details -->
            <div style="padding: 22px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    @if(!$post->featured_image)
                    <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 10px;">
                        <span style="background: {{ $post->category_details['bg'] }}; color: {{ $post->category_details['color'] }}; border: 1px solid {{ $post->category_details['border'] }}; font-size: 0.74rem; font-weight: 800; padding: 3px 10px; border-radius: 8px;">
                            <i class="bi {{ $post->category_details['icon'] }}"></i> {{ $post->category_details['label'] }}
                        </span>
                    </div>
                    @endif

                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0 0 10px 0; line-height: 1.35;">
                        <a href="{{ route('hub.show', $post->slug) }}" style="color: inherit; text-decoration: none;">
                            {{ $post->title }}
                        </a>
                    </h3>

                    <p style="font-size: 0.88rem; color: #64748b; line-height: 1.6; margin: 0 0 18px 0; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $post->summary ?: Str::limit(strip_tags($post->content), 130) }}
                    </p>
                </div>

                <!-- Integrated Audio Player for Audio Posts -->
                @if($post->audio_path)
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px; margin-bottom: 16px;">
                    <audio controls preload="none" style="width: 100%; height: 36px;">
                        <source src="{{ asset($post->audio_path) }}">
                        {{ __('Your browser does not support the audio element.') }}
                    </audio>
                </div>
                @endif

                <!-- Card Footer Info -->
                <div style="border-top: 1px solid #f1f5f9; padding-top: 14px; display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 0.78rem; color: #94a3b8; font-weight: 600;">
                        <span><i class="bi bi-clock"></i> {{ $post->created_at->format('d M Y') }}</span>
                        <span>•</span>
                        <span><i class="bi bi-eye"></i> {{ $post->views_count }}</span>
                    </div>

                    <a href="{{ route('hub.show', $post->slug) }}" style="color: #6366f1; font-weight: 800; font-size: 0.86rem; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                        {{ __('Open') }} <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </article>
        @endforeach
    </section>

    <!-- Pagination -->
    <div style="display: flex; justify-content: center; margin-top: 20px;">
        {{ $posts->links() }}
    </div>

    @else
    <!-- Empty State -->
    <section style="text-align: center; padding: 60px 20px; background: #ffffff; border-radius: 20px; border: 1px dashed #cbd5e1; color: #94a3b8; margin-bottom: 30px;">
        <i class="bi bi-journal-text" style="font-size: 3.5rem; color: #cbd5e1; display: block; margin-bottom: 14px;"></i>
        <h3 style="color: #475569; font-weight: 800; margin: 0 0 6px 0;">{{ __('No Hub Posts Found') }}</h3>
        <p style="color: #64748b; font-size: 0.9rem; margin: 0 0 16px 0;">{{ __('No content currently matches your filter selection. Try changing the category or search query.') }}</p>
        <a href="{{ route('hub') }}" style="background: #6366f1; color: #ffffff; padding: 10px 20px; border-radius: 10px; font-weight: 700; text-decoration: none; font-size: 0.86rem;">
            {{ __('View All Content') }}
        </a>
    </section>
    @endif

</main>
@endsection
