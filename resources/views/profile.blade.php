@extends('layouts.app')

@section('title', 'ChapConnect - ' . $talent->name)

@section('search_bar')
<form action="{{ route('home') }}" method="GET" class="Search">
    <input class="Srch" type="search" name="search" placeholder="Type to Search here">
    <button type="submit" class="btn" title="Search"><i class="bi bi-search"></i></button>
</form>
@endsection

@section('content')
<main class="main" style="max-width: 100%; width: 100%; margin: 10px 0; padding: 0 10px;">
    <div class="profile-wrapper" style="gap: 12px;">
        <!-- Sidebar -->
        <div class="profile-sidebar" style="padding: 16px 12px;">
            <div class="pimage">
                @if($talent->profile_image)
                <img src="{{ asset($talent->profile_image) }}" alt="{{ $talent->name }}">
                @else
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop" alt="{{ $talent->name }}">
                @endif
            </div>
            <h2>{{ $talent->name }}</h2>
            <p style="color: var(--text-muted); font-size: 14px; font-weight: 500; margin-bottom: 12px;">{{ __($talent->category_label) }}</p>

            <div class="like-container" style="display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom: 18px; gap: 4px; flex-wrap: nowrap; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; padding: 12px 0;">
                <div class="like" style="text-align: center; flex: 1;">
                    <button class="like-btn" id="likeBtn_{{ $talent->id }}" onclick="toggleCardLike({{ $talent->id }})" style="font-size: 0.78rem; font-weight: 700; white-space: nowrap;">{{ __('Like') }} 🤍</button>
                    <span class="like-count" id="likeCount_{{ $talent->id }}" style="display: block; font-size: 0.95rem; font-weight: 800; margin-top: 2px;">{{ $talent->likes_received_count ?? 0 }}</span>
                </div>
                <div class="comment" style="text-align: center; flex: 1;">
                    <a href="#comments-tab" style="text-decoration:none;" onclick="$('.menu a[href=\'#comments-tab\']').click();">
                        <button class="comment-btn {{ ($talent->comments_received_count ?? 0) > 0 ? 'has-comments' : '' }}" id="commentBtn_{{ $talent->id }}" style="font-size: 0.78rem; font-weight: 700; white-space: nowrap;">{{ __('Comments 💬') }}</button>
                    </a>
                    <span class="comment-count {{ ($talent->comments_received_count ?? 0) > 0 ? 'has-comments' : '' }}" id="commentCount_{{ $talent->id }}" style="display: block; font-size: 0.95rem; font-weight: 800; margin-top: 2px;">{{ $talent->comments_received_count ?? 0 }}</span>
                </div>
                <div class="follow" style="text-align: center; flex: 1;">
                    <button class="follow-btn" id="followBtn_{{ $talent->id }}" onclick="toggleCardFollow({{ $talent->id }})" style="font-size: 0.78rem; font-weight: 700; white-space: nowrap;">{{ __('Followers') }}</button>
                    <span class="followers-count" id="followersCount_{{ $talent->id }}" style="display: block; font-size: 0.95rem; font-weight: 800; margin-top: 2px;">{{ $talent->followers_received_count ?? 0 }}</span>
                </div>
            </div>

            <div class="menu">
                <ul>
                    <li><a href="#info-tab" class="active">{{ __('Information') }}</a></li>
                    <li><a href="#photos-tab">{{ __('Photos') }}</a></li>
                    <li><a href="#videos-tab">{{ __('Videos') }}</a></li>
                    <li><a href="#news-tab">{{ __('News') }}</a></li>
                    <li><a href="#comments-tab">{{ __('Comments') }}</a></li>
                    <li><a href="{{ route('home') }}"><i class="bi bi-arrow-left"></i> {{ __('Back to Home') }}</a></li>
                </ul>
            </div>
        </div>

        <!-- Content Area -->
        <div class="profile-content" style="padding: 12px 14px;">
            <!-- Info Section -->
            <div id="info-tab" class="profile-tab-section active">
                <div class="pdetails" style="padding: 0; border: none; box-shadow: none; background: transparent;">

                    <!-- Executive Information Header Banner -->
                    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; padding: 18px 22px; border-radius: 14px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; box-shadow: 0 4px 15px rgba(15,23,42,0.12);">
                        <div>
                            <h2 style="margin: 0 0 4px 0; font-size: 1.25rem; font-weight: 800; color: #ffffff; border: none; padding: 0; display: flex; align-items: center; gap: 10px;">
                                <i class="bi bi-person-badge-fill" style="color: #6366f1;"></i> {{ __('Profile Overview & Key Details') }}
                            </h2>
                            <p style="margin: 0; color: #94a3b8; font-size: 0.84rem;">{{ __('Official verified contact credentials, professional biography, and media channels for') }} {{ $talent->name }}.</p>
                        </div>
                        <div style="background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.35); padding: 6px 14px; border-radius: 20px; font-weight: 700; font-size: 0.78rem; color: #34d399; display: flex; align-items: center; gap: 6px;">
                            <i class="bi bi-patch-check-fill" style="color: #34d399; font-size: 0.9rem;"></i> {{ __('Verified Talent Profile') }}
                        </div>
                    </div>

                    <!-- Key Information Metric Cards Grid -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; margin-bottom: 20px;">

                        <!-- Card 1: Occupation -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px 18px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(99,102,241,0.1); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                                <i class="bi bi-briefcase-fill"></i>
                            </div>
                            <div>
                                <span style="display: block; font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px;">{{ __('Primary Occupation') }}</span>
                                <strong style="font-size: 0.95rem; font-weight: 800; color: #0f172a;">{{ __($talent->category_label) }}</strong>
                            </div>
                        </div>

                        <!-- Card 2: Location -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px 18px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(239,68,68,0.1); color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <span style="display: block; font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px;">{{ __('Country / Region') }}</span>
                                <strong style="font-size: 0.95rem; font-weight: 800; color: #0f172a;">{{ $talent->country }}</strong>
                            </div>
                        </div>

                        <!-- Card 3: Phone Contact -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px 18px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16,185,129,0.1); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                                <i class="bi bi-telephone-fill"></i>
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <span style="display: block; font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px;">{{ __('Direct Phone / Line') }}</span>
                                @if($talent->phone)
                                {{-- Phone is public — show it directly --}}
                                <a href="tel:{{ $talent->phone }}" style="font-size: 0.95rem; font-weight: 800; color: #0f172a; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                                    {{ $talent->phone }} <i class="bi bi-box-arrow-up-right" style="font-size: 0.72rem; color: #10b981;"></i>
                                </a>
                                @else
                                {{-- Phone is private — show Ask to Connect CTA. The number is NEVER rendered in HTML. --}}
                                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                    <span style="font-size: 0.82rem; color: #94a3b8; font-weight: 600; display: flex; align-items: center; gap: 5px;">
                                        <i class="bi bi-lock-fill" style="color: #cbd5e1; font-size: 0.75rem;"></i> {{ __('Contact Private') }}
                                    </span>
                                    <button type="button" id="askToConnectBtn" onclick="document.getElementById('ask-to-connect-modal').style.display='flex'"
                                        style="padding: 5px 13px; border-radius: 20px; font-size: 0.75rem; font-weight: 800; border: none; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; box-shadow: 0 3px 10px rgba(99,102,241,0.35); transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                                        <i class="bi bi-person-plus-fill" style="font-size: 0.7rem;"></i> {{ __('Ask to Connect') }}
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>

                    <!-- Biography Section Card -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); margin-bottom: 20px;">
                        <h3 style="margin: 0 0 12px 0; font-size: 1rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-card-text" style="color: #6366f1;"></i> {{ __('About & Professional Biography') }}
                        </h3>
                        <div style="background: #f8fafc; border-left: 4px solid #6366f1; border-radius: 0 10px 10px 0; padding: 16px 18px;">
                            <p style="color: #334155; font-size: 0.93rem; line-height: 1.7; margin: 0; white-space: pre-line;">
                                {{ $talent->description ?: __('No bio biography has been provided by the user yet.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Social & Web Media Channels Box -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); margin-bottom: 24px;">
                        <h3 style="margin: 0 0 14px 0; font-size: 1rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-share-fill" style="color: #6366f1;"></i> {{ __('Official Media & Social Channels') }}
                        </h3>
                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                            @if($talent->social_instagram)
                            <a href="{{ $talent->social_instagram }}" target="_blank" style="padding: 10px 18px; border-radius: 10px; font-size: 0.85rem; font-weight: 700; background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); color: #ffffff; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(220,39,67,0.25); transition: transform 0.2s ease;">
                                <i class="bi bi-instagram" style="font-size: 1.05rem;"></i> {{ __('Instagram Channel') }}
                            </a>
                            @endif
                            @if($talent->social_facebook)
                            <a href="{{ $talent->social_facebook }}" target="_blank" style="padding: 10px 18px; border-radius: 10px; font-size: 0.85rem; font-weight: 700; background: #1877f2; color: #ffffff; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(24,119,242,0.25); transition: transform 0.2s ease;">
                                <i class="bi bi-facebook" style="font-size: 1.05rem;"></i> {{ __('Facebook Page') }}
                            </a>
                            @endif
                            @if($talent->social_tiktok)
                            <a href="{{ $talent->social_tiktok }}" target="_blank" style="padding: 10px 18px; border-radius: 10px; font-size: 0.85rem; font-weight: 700; background: #000000; color: #ffffff; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.25); transition: transform 0.2s ease;">
                                <i class="bi bi-tiktok" style="font-size: 1.05rem;"></i> {{ __('TikTok Feed') }}
                            </a>
                            @endif
                            @if($talent->social_youtube)
                            <a href="{{ $talent->social_youtube }}" target="_blank" style="padding: 10px 18px; border-radius: 10px; font-size: 0.85rem; font-weight: 700; background: #ff0000; color: #ffffff; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(255,0,0,0.25); transition: transform 0.2s ease;">
                                <i class="bi bi-youtube" style="font-size: 1.05rem;"></i> {{ __('YouTube Channel') }}
                            </a>
                            @endif

                            @if(!$talent->social_instagram && !$talent->social_facebook && !$talent->social_tiktok && !$talent->social_youtube)
                            <div style="padding: 14px 18px; background: #f8fafc; border-radius: 10px; border: 1px dashed #cbd5e1; color: #64748b; font-size: 0.86rem; font-weight: 600; width: 100%;">
                                <i class="bi bi-info-circle"></i> {{ __('No social media channels linked yet by') }} {{ $talent->name }}.
                            </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            <!-- Photos Section -->
            <div id="photos-tab" class="profile-tab-section">
                <div class="pdetails" style="padding: 0; border: none; box-shadow: none; background: transparent;">

                    <!-- Photos Header Banner -->
                    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; padding: 18px 22px; border-radius: 14px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; box-shadow: 0 4px 15px rgba(15,23,42,0.12);">
                        <div>
                            <h2 style="margin: 0 0 4px 0; font-size: 1.25rem; font-weight: 800; color: #ffffff; border: none; padding: 0; display: flex; align-items: center; gap: 10px;">
                                <i class="bi bi-images" style="color: #6366f1;"></i> {{ __('Official Photo Gallery & Portfolio') }}
                            </h2>
                            <p style="margin: 0; color: #94a3b8; font-size: 0.84rem;">{{ __('High-resolution photography, event highlights, and press shots of') }} {{ $talent->name }}.</p>
                        </div>
                        <div style="background: rgba(99,102,241,0.2); border: 1px solid rgba(99,102,241,0.4); padding: 5px 14px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; color: #a5b4fc;">
                            {{ $miniPhotos->count() }} {{ __('Photos') }}
                        </div>
                    </div>

                    <!-- Photos Grid -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px;">
                        @forelse($miniPhotos as $photo)
                        <div class="profile-photo-card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; flex-direction: column; transition: transform 0.2s ease, box-shadow 0.2s ease;">
                            <div style="width: 100%; aspect-ratio: 4/3; overflow: hidden; position: relative; background: #0f172a; cursor: pointer;" onclick="openPhotoViewer('{{ asset($photo->file_path) }}', '{{ addslashes($photo->title ?: 'Portfolio Asset') }}', '{{ addslashes($photo->content ?: '') }}')">
                                <img src="{{ asset($photo->file_path) }}" alt="{{ $photo->title ?: $talent->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                                <div style="position: absolute; top: 10px; right: 10px; display: flex; gap: 6px;">
                                    <button type="button" onclick="event.stopPropagation(); openReportModal({{ $photo->id }});" title="{{ __('Report Inappropriate Content') }}" style="background: rgba(239, 68, 68, 0.85); backdrop-filter: blur(4px); padding: 4px 8px; border: none; border-radius: 12px; font-size: 0.72rem; font-weight: 700; color: #ffffff; cursor: pointer; display: inline-flex; align-items: center; gap: 3px;">
                                        <i class="bi bi-flag"></i>
                                    </button>
                                    <div style="background: rgba(15,23,42,0.7); backdrop-filter: blur(4px); padding: 4px 10px; border-radius: 12px; font-size: 0.72rem; font-weight: 700; color: #ffffff;">
                                        <i class="bi bi-arrows-angle-expand"></i> {{ __('View') }}
                                    </div>
                                </div>
                            </div>
                            <div style="padding: 12px 14px; background: #ffffff;">
                                <h4 style="margin: 0 0 4px 0; font-size: 0.92rem; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $photo->title ?: __('Portfolio Asset') }}</h4>
                                @if(!empty($photo->content))
                                <p style="margin: 0 0 6px 0; font-size: 0.8rem; color: #475569; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;" title="{{ $photo->content }}">{{ $photo->content }}</p>
                                @endif
                                <span style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;"><i class="bi bi-clock"></i> {{ $photo->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @empty
                        <div style="grid-column: 1/-1; text-align: center; padding: 50px 20px; background: #ffffff; border-radius: 14px; border: 1px dashed #cbd5e1;">
                            <i class="bi bi-camera" style="font-size: 2.5rem; color: #94a3b8; display: block; margin-bottom: 10px;"></i>
                            <p style="margin: 0; color: #64748b; font-weight: 600; font-size: 0.92rem;">{{ __('No portfolio photos uploaded yet for') }} {{ $talent->name }}.</p>
                        </div>
                        @endforelse
                    </div>

                </div>
            </div>

            <!-- Videos Section -->
            <div id="videos-tab" class="profile-tab-section">
                <div class="pdetails" style="padding: 0; border: none; box-shadow: none; background: transparent;">

                    <!-- Videos Header Banner -->
                    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; padding: 18px 22px; border-radius: 14px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; box-shadow: 0 4px 15px rgba(15,23,42,0.12);">
                        <div>
                            <h2 style="margin: 0 0 4px 0; font-size: 1.25rem; font-weight: 800; color: #ffffff; border: none; padding: 0; display: flex; align-items: center; gap: 10px;">
                                <i class="bi bi-camera-reels-fill" style="color: #ec4899;"></i> {{ __('Official Video Showcase & Reels') }}
                            </h2>
                            <p style="margin: 0; color: #94a3b8; font-size: 0.84rem;">{{ __('Official music videos, interviews, live performances, and video clips from') }} {{ $talent->name }}.</p>
                        </div>
                        @php
                        $videos = $talent->media()->where('type', 'video')->latest()->get();
                        @endphp
                        <div style="background: rgba(236,72,153,0.2); border: 1px solid rgba(236,72,153,0.4); padding: 5px 14px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; color: #f472b6;">
                            {{ $videos->count() }} {{ __('Videos') }}
                        </div>
                    </div>

                    <!-- Videos Grid -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 18px;">
                        @forelse($videos as $video)
                        <div class="profile-video-card" style="background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; display: flex; flex-direction: column;">
                            <div style="width: 100%; background: #0f172a; position: relative;">
                                {!! \App\Helpers\VideoHelper::renderEmbed($video->file_path) !!}
                            </div>
                            <div style="padding: 16px; background: #ffffff; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; gap: 10px;">
                                <div>
                                    <h4 style="margin: 0 0 6px 0; color: #0f172a; font-size: 0.98rem; font-weight: 800; display: flex; align-items: center; gap: 6px;">
                                        <i class="bi bi-play-btn-fill" style="color: #ec4899;"></i> {{ $video->title ?: __('Portfolio Video Showcase') }}
                                    </h4>
                                    @if($video->content)
                                    <p style="margin: 0 0 8px 0; color: #475569; font-size: 0.84rem; line-height: 1.5; white-space: pre-line;">{{ $video->content }}</p>
                                    @endif
                                </div>
                                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.76rem; color: #94a3b8; font-weight: 600; border-top: 1px solid #f1f5f9; padding-top: 10px;">
                                    <span><i class="bi bi-clock-history" style="color: #ec4899;"></i> {{ $video->created_at->diffForHumans() }}</span>
                                    <span style="background: #f1f5f9; padding: 3px 10px; border-radius: 10px; color: #475569;"><i class="bi bi-film"></i> {{ __('HD Video') }}</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div style="grid-column: 1/-1; text-align: center; padding: 50px 20px; background: #ffffff; border-radius: 14px; border: 1px dashed #cbd5e1;">
                            <i class="bi bi-film" style="font-size: 2.5rem; color: #94a3b8; display: block; margin-bottom: 10px;"></i>
                            <p style="margin: 0; color: #64748b; font-weight: 600; font-size: 0.92rem;">{{ __('No video showcases uploaded yet for') }} {{ $talent->name }}.</p>
                        </div>
                        @endforelse
                    </div>

                </div>
            </div>

            <!-- News Section -->
            <div id="news-tab" class="profile-tab-section">
                <div class="pdetails" style="background: transparent; border-radius: 0; padding: 0; box-shadow: none; border: none;">
                    <!-- News Header Banner (Reduced Size) -->
                    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; padding: 12px 18px; border-radius: 10px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                        <div>
                            <h2 style="margin: 0 0 2px 0; font-size: 1.05rem; font-weight: 800; color: #ffffff; border: none; padding: 0; display: flex; align-items: center; gap: 8px;">
                                <i class="bi bi-newspaper" style="color: #6366f1;"></i> {{ __('Official News & Press Bulletins') }}
                            </h2>
                            <p style="margin: 0; color: #94a3b8; font-size: 0.78rem;">{{ __('Latest announcements, tour dates, press releases, and media updates from') }} {{ $talent->name }}.</p>
                        </div>
                        @php
                        $newsItems = $talent->media()->where('type', 'news')->latest()->get();
                        @endphp
                        <div style="background: rgba(99,102,241,0.2); border: 1px solid rgba(99,102,241,0.4); padding: 4px 12px; border-radius: 20px; font-weight: 700; font-size: 0.75rem; color: #a5b4fc;">
                            {{ $newsItems->count() }} {{ __('Articles') }}
                        </div>
                    </div>

                    <!-- News Articles Feed -->
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        @forelse($newsItems as $news)
                        <article class="news-article-card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.02); transition: transform 0.2s ease, box-shadow 0.2s ease;">
                            @if($news->file_path)
                            <div style="position: relative; width: 100%; height: 420px; overflow: hidden; background: #0f172a; display: flex; justify-content: center; align-items: center; border-radius: 12px 12px 0 0;">
                                <img src="{{ asset($news->file_path) }}" alt="{{ $news->title }}" style="max-width: 100%; max-height: 100%; object-fit: contain; display: block;">
                                <div style="position: absolute; top: 12px; left: 12px; background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,0.15); padding: 4px 10px; border-radius: 12px; font-weight: 700; font-size: 0.7rem; color: #38bdf8; text-transform: uppercase; letter-spacing: 0.5px; z-index: 5;">
                                    <i class="bi bi-lightning-charge-fill" style="color: #f59e0b;"></i> Official Bulletin
                                </div>
                            </div>
                            @endif

                            <div style="padding: 14px 16px;">
                                <!-- Meta bar -->
                                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 10px; padding-bottom: 8px; border-bottom: 1px solid #f1f5f9;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 28px; height: 28px; border-radius: 50%; overflow: hidden; border: 1.5px solid #6366f1; flex-shrink: 0; background: #f1f5f9;">
                                            @if($talent->profile_image)
                                            <img src="{{ asset($talent->profile_image) }}" alt="{{ $talent->name }}" style="width:100%; height:100%; object-fit:cover;">
                                            @else
                                            <i class="bi bi-person-fill" style="color: #64748b; margin: 4px; font-size: 0.8rem;"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <span style="font-weight: 700; font-size: 0.8rem; color: #0f172a;">{{ $talent->name }}</span>
                                            <span style="font-size: 0.72rem; color: #64748b; margin-left: 4px;">&bull; {{ $talent->category_label }}</span>
                                        </div>
                                    </div>

                                    <div style="display: flex; align-items: center; gap: 10px; font-size: 0.72rem; color: #64748b; font-weight: 600; flex-wrap: wrap;">
                                        <span><i class="bi bi-calendar3" style="color: #6366f1;"></i> {{ $news->created_at->format('M d, Y') }}</span>
                                        <span><i class="bi bi-clock-history" style="color: #6366f1;"></i> {{ $news->created_at->diffForHumans() }}</span>
                                        <span style="background: #f1f5f9; padding: 2px 8px; border-radius: 8px; color: #475569;"><i class="bi bi-book"></i> {{ max(1, (int)ceil(str_word_count(strip_tags($news->content)) / 200)) }} min read</span>
                                    </div>
                                </div>

                                <!-- Headline -->
                                <h3 style="margin: 0 0 8px 0; font-size: 1.05rem; font-weight: 800; color: #0f172a; line-height: 1.3;">
                                    {{ $news->title }}
                                </h3>

                                <!-- Body content -->
                                <div style="color: #334155; font-size: 0.85rem; line-height: 1.6; white-space: pre-line; margin-bottom: 15px;">
                                    {{ $news->content }}
                                </div>

                                <!-- Footer Bar -->
                                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 10px; border-top: 1px dashed #e2e8f0; flex-wrap: wrap; gap: 8px;">
                                    <div style="font-size: 0.72rem; font-weight: 600; color: #64748b; display: flex; align-items: center; gap: 4px;">
                                        <i class="bi bi-shield-check" style="color: #10b981; font-size: 0.85rem;"></i> Verified Official Release
                                    </div>
                                    <button type="button" onclick="navigator.clipboard.writeText(window.location.origin + window.location.pathname + '#news-tab'); alert('News link copied to clipboard!');" style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; padding: 4px 10px; font-size: 0.72rem; font-weight: 700; color: #475569; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s ease;">
                                        <i class="bi bi-share-fill" style="color: #6366f1;"></i> Share Update
                                    </button>
                                </div>
                            </div>
                        </article>
                        @empty
                        <div style="padding: 30px 15px; text-align: center; background: #f8fafc; border-radius: 10px; border: 1px dashed #cbd5e1;">
                            <i class="bi bi-newspaper" style="font-size: 2rem; color: #94a3b8; display: block; margin-bottom: 8px;"></i>
                            <h4 style="margin: 0 0 4px 0; font-size: 0.95rem; font-weight: 700; color: #334155;">No Press Releases Published Yet</h4>
                            <p style="margin: 0; color: #64748b; font-weight: 500; font-size: 0.8rem;">Official announcements and tour updates for {{ $talent->name }} will appear here.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Comments Section — Standalone Tab -->
            <div id="comments-tab" class="profile-tab-section">
                <div class="pdetails" style="padding: 0; border: none; box-shadow: none; background: transparent;">

                    <!-- Comments Header Banner (Reduced Size) -->
                    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; padding: 12px 18px; border-radius: 10px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; box-shadow: 0 4px 12px rgba(15,23,42,0.1);">
                        <div>
                            <h2 style="margin: 0 0 2px 0; font-size: 1.05rem; font-weight: 800; color: #ffffff; border: none; padding: 0; display: flex; align-items: center; gap: 8px;">
                                <i class="bi bi-chat-quote-fill" style="color: #6366f1;"></i> Public Fan Reviews &amp; Remarks
                            </h2>
                            <p style="margin: 0; color: #94a3b8; font-size: 0.78rem;">Fan comments, reviews, and official replies for {{ $talent->name }}.</p>
                        </div>
                        <div style="background: rgba(99,102,241,0.2); border: 1px solid rgba(99,102,241,0.4); padding: 4px 12px; border-radius: 20px; font-weight: 700; font-size: 0.75rem; color: #a5b4fc;">
                            {{ $comments->count() }} {{ Str::plural('Comment', $comments->count()) }}
                        </div>
                    </div>

                    <!-- Comment Submission Form (Reduced Size) -->
                    <form action="{{ route('talent.comment', $talent->id) }}" method="POST" style="background: #f8fafc; padding: 12px; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 18px; box-shadow: 0 2px 6px rgba(0,0,0,0.01);">
                        @csrf
                        @guest
                        <div class="form-group" style="margin-bottom: 10px;">
                            <label style="font-size: 0.78rem; font-weight: 700; color: #475569; display: block; margin-bottom: 3px;">Your Name *</label>
                            <input type="text" name="author_name" class="form-control" placeholder="Enter your full name..." required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 6px; padding: 7px 11px; font-size: 0.82rem; width: 100%;">
                        </div>
                        @endguest
                        <div class="form-group" style="margin-bottom: 10px;">
                            <label style="font-size: 0.78rem; font-weight: 700; color: #475569; display: block; margin-bottom: 3px;">Leave a Comment or Fan Review *</label>
                            <textarea name="comment" rows="2" class="form-control" placeholder="Write a message or review..." required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px 11px; font-size: 0.82rem; width: 100%; line-height: 1.4;"></textarea>
                        </div>
                        <div style="display: flex; justify-content: flex-end;">
                            <button type="submit" style="padding: 7px 16px; border-radius: 8px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; border: none; font-weight: 700; font-size: 0.8rem; cursor: pointer; box-shadow: 0 3px 8px rgba(99,102,241,0.25);">
                                <i class="bi bi-send-fill"></i> Post Comment
                            </button>
                        </div>
                    </form>

                    <!-- Comments Stream List (Reduced Size) -->
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @forelse($comments as $cmt)
                        <div style="background: #ffffff; padding: 12px; border-radius: 10px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 8px; box-shadow: 0 1px 4px rgba(0,0,0,0.015);">
                            <div style="display: flex; gap: 10px; align-items: flex-start;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ $cmt->user_id == $talent->id ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)' : 'linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)' }}; color: #ffffff; font-weight: 800; font-size: 0.85rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 6px rgba(99,102,241,0.2);">
                                    {{ strtoupper(substr($cmt->author_name, 0, 1)) }}
                                </div>
                                <div style="flex-grow: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px; flex-wrap: wrap; gap: 4px;">
                                        <strong style="color: #0f172a; font-size: 0.82rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                            {{ $cmt->author_name }}
                                            @if($cmt->user_id == $talent->id)
                                                <span style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; font-size: 0.62rem; font-weight: 700; padding: 1px 5px; border-radius: 8px; display: inline-flex; align-items: center; gap: 2px;">
                                                    <i class="bi bi-patch-check-fill"></i> Owner
                                                </span>
                                            @endif
                                        </strong>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <span style="font-size: 0.7rem; color: #94a3b8; font-weight: 600;"><i class="bi bi-clock"></i> {{ $cmt->created_at->diffForHumans() }}</span>
                                            <button type="button" onclick="openReplyModal({{ $cmt->id }}, '{{ addslashes($cmt->author_name) }}')" style="background: none; border: none; color: #6366f1; font-size: 0.7rem; font-weight: 700; cursor: pointer; padding: 0; display: inline-flex; align-items: center; gap: 2px;" title="Reply to this comment">
                                                <i class="bi bi-reply-fill"></i> Reply
                                            </button>
                                        </div>
                                    </div>
                                    <p style="margin: 0; color: #475569; font-size: 0.8rem; line-height: 1.4; white-space: pre-line;">{{ $cmt->comment }}</p>
                                </div>
                            </div>

                            <!-- Nested Replies Stream (Reduced Indentation) -->
                            @if($cmt->replies->count() > 0)
                            <div style="margin-top: 4px; margin-left: 15px; padding-left: 10px; border-left: 2px solid #6366f1; display: flex; flex-direction: column; gap: 6px;">
                                @foreach($cmt->replies as $reply)
                                <div style="background: #f8fafc; padding: 8px 10px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; gap: 8px; align-items: flex-start;">
                                    <div style="width: 26px; height: 26px; border-radius: 50%; background: {{ $reply->user_id == $talent->id ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)' : '#475569' }}; color: #ffffff; font-weight: 800; font-size: 0.75rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        {{ strtoupper(substr($reply->author_name, 0, 1)) }}
                                    </div>
                                    <div style="flex-grow: 1;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px; flex-wrap: wrap; gap: 4px;">
                                            <strong style="color: #0f172a; font-size: 0.78rem; font-weight: 700; display: inline-flex; align-items: center; gap: 3px;">
                                                {{ $reply->author_name }}
                                                @if($reply->user_id == $talent->id)
                                                    <span style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; font-size: 0.62rem; font-weight: 700; padding: 1px 5px; border-radius: 8px; display: inline-flex; align-items: center; gap: 2px;">
                                                        <i class="bi bi-patch-check-fill"></i> Owner
                                                    </span>
                                                @endif
                                            </strong>
                                            <div style="display: flex; align-items: center; gap: 6px;">
                                                <span style="font-size: 0.68rem; color: #94a3b8; font-weight: 600;"><i class="bi bi-clock"></i> {{ $reply->created_at->diffForHumans() }}</span>
                                                <button type="button" onclick="openReplyModal({{ $cmt->id }}, '{{ addslashes($reply->author_name) }}')" style="background: none; border: none; color: #6366f1; font-size: 0.68rem; font-weight: 700; cursor: pointer; padding: 0; display: inline-flex; align-items: center; gap: 2px;">
                                                    <i class="bi bi-reply-fill"></i> Reply
                                                </button>
                                            </div>
                                        </div>
                                        <p style="margin: 0; color: #334155; font-size: 0.76rem; line-height: 1.35; white-space: pre-line;">{{ $reply->comment }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @empty
                        <div style="text-align: center; padding: 30px 15px; color: #94a3b8; font-size: 0.8rem; background: #f8fafc; border-radius: 10px; border: 1px dashed #cbd5e1;">
                            <i class="bi bi-chat-left-dots" style="font-size: 2rem; color: #cbd5e1; display: block; margin-bottom: 8px;"></i>
                            <p style="margin: 0; font-weight: 600;">No comments yet for {{ $talent->name }}.</p>
                            <p style="margin: 4px 0 0 0; font-size: 0.76rem;">Be the first to leave a fan review or message!</p>
                        </div>
                        @endforelse
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Photo Viewer Lightbox Modal -->
    <div id="photoViewerModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15,23,42,0.92); backdrop-filter: blur(8px); z-index: 99999; justify-content: center; align-items: center; padding: 20px;" onclick="closePhotoViewer()">
        <div style="position: relative; max-width: 90vw; max-height: 90vh; text-align: center;" onclick="event.stopPropagation();">
            <button type="button" onclick="closePhotoViewer()" style="position: absolute; top: -45px; right: -10px; background: rgba(255,255,255,0.2); border: none; border-radius: 50%; width: 36px; height: 36px; font-size: 1.6rem; color: #ffffff; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">&times;</button>
            <img id="viewerModalImg" src="" alt="Full View" style="max-width: 100%; max-height: 80vh; border-radius: 12px; box-shadow: 0 20px 50px rgba(0,0,0,0.6); object-fit: contain; border: 2px solid rgba(255,255,255,0.2);">
            <h4 id="viewerModalTitle" style="margin: 15px 0 0 0; color: #ffffff; font-size: 1.1rem; font-weight: 700;"></h4>
            <p id="viewerModalCaption" style="margin: 6px 0 0 0; color: #cbd5e1; font-size: 0.9rem; font-weight: 500; line-height: 1.4; max-width: 600px; text-align: center; margin-left: auto; margin-right: auto;"></p>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    window.openPhotoViewer = function(url, title, caption) {
        var modal = document.getElementById('photoViewerModal');
        var img = document.getElementById('viewerModalImg');
        var titleEl = document.getElementById('viewerModalTitle');
        var captionEl = document.getElementById('viewerModalCaption');
        if (modal && img) {
            img.src = url;
            if (titleEl) titleEl.innerText = title;
            if (captionEl) captionEl.innerText = caption || '';
            modal.style.display = 'flex';
        }
    };

    window.closePhotoViewer = function() {
        var modal = document.getElementById('photoViewerModal');
        if (modal) {
            modal.style.display = 'none';
        }
    };

    // Translation strings passed from Blade — must be declared before DOMContentLoaded
    const profileI18n = {
        liked:      "{{ __('Liked ❤️') }}",
        like:       "{{ __('Like') }} 🤍",
        following:  "{{ __('Following') }}",
        followers:  "{{ __('Followers') }}"
    };

    document.addEventListener("DOMContentLoaded", function() {
        if (typeof initProfileTabs === "function") {
            initProfileTabs();
        }

        // Fetch initial status for this talent
        $.get('{{ route("talent.interactions.status") }}', {
            talent_ids: '{{ $talent->id }}'
        }, function(res) {
            if (res.success && res.statuses && res.statuses['{{ $talent->id }}']) {
                const data = res.statuses['{{ $talent->id }}'];
                const likeBtn = document.getElementById('likeBtn_{{ $talent->id }}');
                const followBtn = document.getElementById('followBtn_{{ $talent->id }}');

                if (likeBtn && data.is_liked) {
                    likeBtn.classList.add('liked');
                    likeBtn.textContent = profileI18n.liked;
                }
                if (followBtn && data.is_following) {
                    followBtn.classList.add('following');
                    followBtn.textContent = profileI18n.following;
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
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                btn.disabled = false;
                if (res.already_done) {
                    alert(res.message);
                    return;
                }
                if (res.success) {
                    if (res.liked) {
                        btn.classList.add('liked');
                        btn.textContent = profileI18n.liked;
                    } else {
                        btn.classList.remove('liked');
                        btn.textContent = profileI18n.like;
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
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                btn.disabled = false;
                if (res.already_done) {
                    alert(res.message);
                    return;
                }
                if (res.success) {
                    if (res.following) {
                        btn.classList.add('following');
                        btn.textContent = profileI18n.following;
                    } else {
                        btn.classList.remove('following');
                        btn.textContent = profileI18n.followers;
                    }
                    count.textContent = res.count;
                }
            },
            error: function() {
                btn.disabled = false;
            }
        });
    }

    function toggleReplyBox(commentId) {
        const box = document.getElementById('reply-box-' + commentId);
        if (box) {
            if (box.style.display === 'none' || !box.style.display) {
                box.style.display = 'block';
            } else {
                box.style.display = 'none';
            }
        }
    }
</script>

<!-- ====================================================================
     ASK TO CONNECT MODAL — Guest Contact Request (No Login Required)
     ==================================================================== -->
@if(!$talent->phone)
<div id="ask-to-connect-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15,23,42,0.7); backdrop-filter: blur(6px); z-index: 99999; justify-content: center; align-items: center; padding: 20px;" onclick="if(event.target===this)this.style.display='none'">
    <div style="background: #ffffff; border-radius: 20px; max-width: 480px; width: 100%; padding: 28px; box-shadow: 0 25px 60px rgba(0,0,0,0.25); border: 1px solid #e2e8f0; position: relative;" onclick="event.stopPropagation()">
        <button type="button" onclick="document.getElementById('ask-to-connect-modal').style.display='none'" style="position: absolute; top: 16px; right: 16px; background: #f1f5f9; border: none; border-radius: 50%; width: 34px; height: 34px; font-size: 1.1rem; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>

        <div style="text-align: center; margin-bottom: 22px;">
            <div style="width: 56px; height: 56px; border-radius: 16px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 12px;">
                <i class="bi bi-person-plus-fill"></i>
            </div>
            <h3 style="margin: 0 0 4px 0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">{{ __('Ask to Connect') }}</h3>
            <p style="margin: 0; color: #64748b; font-size: 0.85rem;">{{ __('Leave your contact info —') }} <strong>{{ $talent->name }}</strong>.</p>
        </div>

        @if(session('success') && str_contains(session('success'), 'connect'))
        <div style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); border-radius: 10px; padding: 10px 14px; margin-bottom: 16px; color: #065f46; font-size: 0.85rem; font-weight: 600;">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
        @endif

        @if($errors->has('spam'))
        <div style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); border-radius: 10px; padding: 10px 14px; margin-bottom: 16px; color: #b91c1c; font-size: 0.85rem; font-weight: 600;">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first('spam') }}
        </div>
        @endif

        <form action="{{ route('profile.connect', $talent->id) }}" method="POST">
            @csrf

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #475569; margin-bottom: 6px;">{{ __('Full Name') }} <span style="color: #ef4444;">*</span></label>
                <input type="text" name="requester_full_name" required maxlength="255"
                    value="{{ old('requester_full_name') }}"
                    placeholder="{{ __('Enter your full name') }}"
                    style="width: 100%; padding: 10px 14px; border: 1.5px solid {{ $errors->has('requester_full_name') ? '#ef4444' : '#e2e8f0' }}; border-radius: 10px; font-size: 0.9rem; color: #1e293b; box-sizing: border-box; outline: none; transition: border 0.2s;"
                    onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">
                @error('requester_full_name')<div style="font-size: 0.78rem; color: #ef4444; margin-top: 4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #475569; margin-bottom: 6px;">{{ __('How should they reach you?') }} <span style="color: #ef4444;">*</span></label>
                <select name="contact_type" id="connectContactType" required onchange="updateConnectPlaceholder()"
                    style="width: 100%; padding: 10px 14px; border: 1.5px solid {{ $errors->has('contact_type') ? '#ef4444' : '#e2e8f0' }}; border-radius: 10px; font-size: 0.9rem; color: #1e293b; box-sizing: border-box; background: #fff; outline: none;">
                    <option value="">-- {{ __('Select Contact Type') }} --</option>
                    <option value="whatsapp" {{ old('contact_type') === 'whatsapp' ? 'selected' : '' }}><span class="bi bi-whatsapp" style="color: #6366f1"></span> {{ __('WhatsApp Number') }}</option>
                    <option value="phone" {{ old('contact_type') === 'phone' ? 'selected' : '' }}><span class="bi bi-phone" style="color: #6366f1"></span> {{ __('Phone Number') }}</option>
                    <option value="email" {{ old('contact_type') === 'email' ? 'selected' : '' }}><span class="bi bi-email" style="color: #6366f1"></span> {{ __('Email Address') }}</option>
                </select>
                @error('contact_type')<div style="font-size: 0.78rem; color: #ef4444; margin-top: 4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #475569; margin-bottom: 6px;">{{ __('Your Contact') }} <span style="color: #ef4444;">*</span></label>
                <input type="text" name="contact_value" id="connectContactValue" required maxlength="255"
                    value="{{ old('contact_value') }}"
                    placeholder="{{ __('Enter your contact details') }}"
                    style="width: 100%; padding: 10px 14px; border: 1.5px solid {{ $errors->has('contact_value') ? '#ef4444' : '#e2e8f0' }}; border-radius: 10px; font-size: 0.9rem; color: #1e293b; box-sizing: border-box; outline: none; transition: border 0.2s;"
                    onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">
                @error('contact_value')<div style="font-size: 0.78rem; color: #ef4444; margin-top: 4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #475569; margin-bottom: 6px;">
                    <i class="bi bi-geo-alt-fill" style="color: #f59e0b; font-size: 0.8rem;"></i> {{ __('Region / Location') }}
                    <span style="font-size: 0.75rem; font-weight: 500; color: #94a3b8;">({{ __('Optional') }})</span>
                </label>
                <input type="text" name="region" maxlength="255"
                    value="{{ old('region') }}"
                    placeholder="e.g. Dar es Salaam, Arusha, Mwanza, Dodoma..."
                    style="width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.9rem; color: #1e293b; box-sizing: border-box; outline: none; transition: border 0.2s;"
                    onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #475569; margin-bottom: 6px;">{{ __('Message') }} <span style="font-size: 0.75rem; font-weight: 500; color: #94a3b8;">({{ __('Optional') }})</span></label>
                <textarea name="message" rows="2" maxlength="1000"
                    placeholder="{{ __('Write a comment...') }}"
                    style="width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.88rem; color: #1e293b; box-sizing: border-box; resize: vertical; outline: none; transition: border 0.2s; font-family: inherit;"
                    onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">{{ old('message') }}</textarea>
            </div>

            <button type="submit" style="width: 100%; padding: 12px; border-radius: 12px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; border: none; font-weight: 800; font-size: 0.92rem; cursor: pointer; box-shadow: 0 4px 15px rgba(99,102,241,0.4); display: flex; align-items: center; justify-content: center; gap: 8px;">
                <i class="bi bi-send-fill"></i> {{ __('Send Connection Request') }}
            </button>
        </form>
    </div>
</div>

<script>
    function updateConnectPlaceholder() {
        const type = document.getElementById('connectContactType').value;
        const input = document.getElementById('connectContactValue');
        const placeholders = {
            'whatsapp': '+255712345678 (WhatsApp)',
            'phone': '+255712345678 (Phone)',
            'email': 'email@example.com',
            '': '{{ __("Enter your contact details") }}'
        };
        input.placeholder = placeholders[type] || placeholders[''];
    }

    // Auto-open modal if there were validation errors on this form
    @if($errors->any() && old('requester_full_name'))
    document.getElementById('ask-to-connect-modal').style.display = 'flex';
    @endif
</script>
@endif

<!-- ====================================================================
     COMMENT REPLY MODAL — Guest / Public Comment Reply (No Login Required)
     ==================================================================== -->
<div id="reply-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15,23,42,0.7); backdrop-filter: blur(6px); z-index: 99999; justify-content: center; align-items: center; padding: 20px;" onclick="if(event.target===this)closeReplyModal()">
    <div style="background: #ffffff; border-radius: 20px; max-width: 480px; width: 100%; padding: 28px; box-shadow: 0 25px 60px rgba(0,0,0,0.25); border: 1px solid #e2e8f0; position: relative;" onclick="event.stopPropagation()">
        <button type="button" onclick="closeReplyModal()" style="position: absolute; top: 16px; right: 16px; background: #f1f5f9; border: none; border-radius: 50%; width: 34px; height: 34px; font-size: 1.1rem; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>

        <div style="text-align: center; margin-bottom: 20px;">
            <div style="width: 52px; height: 52px; border-radius: 16px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; margin: 0 auto 10px; box-shadow: 0 4px 15px rgba(99,102,241,0.3);">
                <i class="bi bi-reply-fill"></i>
            </div>
            <h3 style="margin: 0 0 4px 0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">{{ __('Post a Reply') }}</h3>
            <p id="reply-modal-target-text" style="margin: 0; color: #6366f1; font-size: 0.85rem; font-weight: 700;">{{ __('Replying to comment...') }}</p>
        </div>

        <form action="{{ route('talent.comment', $talent->id) }}" method="POST">
            @csrf
            <input type="hidden" name="parent_id" id="reply_modal_parent_id" value="">

            @guest
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #475569; margin-bottom: 6px;">{{ __('Your Name') }} <span style="color: #ef4444;">*</span></label>
                <input type="text" name="author_name" required maxlength="255"
                    placeholder="{{ __('Enter your full name') }}..."
                    style="width: 100%; padding: 10px 14px; border: 1.5px solid #cbd5e1; border-radius: 10px; font-size: 0.88rem; color: #1e293b; box-sizing: border-box; outline: none; transition: border 0.2s;"
                    onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
            </div>
            @else
            <input type="hidden" name="author_name" value="{{ auth()->user()->name }}">
            <p style="font-size: 0.82rem; color: #64748b; margin-bottom: 14px; font-weight: 600; text-align: center; background: #f8fafc; padding: 8px 12px; border-radius: 8px;">
                Posting reply as <strong>{{ auth()->user()->name }}</strong>
            </p>
            @endguest

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #475569; margin-bottom: 6px;">{{ __('Write a comment...') }} <span style="color: #ef4444;">*</span></label>
                <textarea name="comment" rows="3" required maxlength="1000"
                    placeholder="{{ __('Write a comment...') }}"
                    style="width: 100%; padding: 10px 14px; border: 1.5px solid #cbd5e1; border-radius: 10px; font-size: 0.88rem; color: #1e293b; box-sizing: border-box; resize: vertical; outline: none; transition: border 0.2s; font-family: inherit;"
                    onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'"></textarea>
            </div>

            <button type="submit" style="width: 100%; padding: 12px; border-radius: 12px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; border: none; font-weight: 800; font-size: 0.92rem; cursor: pointer; box-shadow: 0 4px 15px rgba(99,102,241,0.4); display: flex; align-items: center; justify-content: center; gap: 8px;">
                <i class="bi bi-send-fill"></i> {{ __('Send Reply') }}
            </button>
        </form>
    </div>
</div>

<script>
    function openReplyModal(commentId, authorName) {
        document.getElementById('reply_modal_parent_id').value = commentId;
        document.getElementById('reply-modal-target-text').textContent = 'Replying to ' + authorName;
        document.getElementById('reply-modal').style.display = 'flex';
    }

    function closeReplyModal() {
        document.getElementById('reply-modal').style.display = 'none';
    }
</script>

@endsection