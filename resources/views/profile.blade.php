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
            <p style="color: var(--text-muted); font-size: 14px; font-weight: 500; margin-bottom: 12px;">{{ $talent->category_label }}</p>

            <div class="like-container" style="display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom: 18px; gap: 4px; flex-wrap: nowrap; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; padding: 12px 0;">
                <div class="like" style="text-align: center; flex: 1;">
                    <button class="like-btn" id="likeBtn_{{ $talent->id }}" onclick="toggleCardLike({{ $talent->id }})" style="font-size: 0.78rem; font-weight: 700; white-space: nowrap;">Liked ❤️</button>
                    <span class="like-count" id="likeCount_{{ $talent->id }}" style="display: block; font-size: 0.95rem; font-weight: 800; margin-top: 2px;">{{ $talent->likes_received_count ?? 0 }}</span>
                </div>
                <div class="comment" style="text-align: center; flex: 1;">
                    <a href="#info-tab" style="text-decoration:none;" onclick="$('.menu a[href=\'#info-tab\']').click();">
                        <button class="comment-btn {{ ($talent->comments_received_count ?? 0) > 0 ? 'has-comments' : '' }}" id="commentBtn_{{ $talent->id }}" style="font-size: 0.78rem; font-weight: 700; white-space: nowrap;">Comments 💬</button>
                    </a>
                    <span class="comment-count {{ ($talent->comments_received_count ?? 0) > 0 ? 'has-comments' : '' }}" id="commentCount_{{ $talent->id }}" style="display: block; font-size: 0.95rem; font-weight: 800; margin-top: 2px;">{{ $talent->comments_received_count ?? 0 }}</span>
                </div>
                <div class="follow" style="text-align: center; flex: 1;">
                    <button class="follow-btn" id="followBtn_{{ $talent->id }}" onclick="toggleCardFollow({{ $talent->id }})" style="font-size: 0.78rem; font-weight: 700; white-space: nowrap;">Following</button>
                    <span class="followers-count" id="followersCount_{{ $talent->id }}" style="display: block; font-size: 0.95rem; font-weight: 800; margin-top: 2px;">{{ $talent->followers_received_count ?? 0 }}</span>
                </div>
            </div>

            <div class="menu">
                <ul>
                    <li><a href="#info-tab" class="active">Information</a></li>
                    <li><a href="#photos-tab">Photos</a></li>
                    <li><a href="#videos-tab">Videos</a></li>
                    <li><a href="#news-tab">News</a></li>
                    <li><a href="{{ route('home') }}"><i class="bi bi-arrow-left"></i> Back to Home</a></li>
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
                                <i class="bi bi-person-badge-fill" style="color: #6366f1;"></i> Profile Overview &amp; Key Details
                            </h2>
                            <p style="margin: 0; color: #94a3b8; font-size: 0.84rem;">Official verified contact credentials, professional biography, and media channels for {{ $talent->name }}.</p>
                        </div>
                        <div style="background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.35); padding: 6px 14px; border-radius: 20px; font-weight: 700; font-size: 0.78rem; color: #34d399; display: flex; align-items: center; gap: 6px;">
                            <i class="bi bi-patch-check-fill" style="color: #34d399; font-size: 0.9rem;"></i> Verified Talent Profile
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
                                <span style="display: block; font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px;">Primary Occupation</span>
                                <strong style="font-size: 0.95rem; font-weight: 800; color: #0f172a;">{{ $talent->category_label }}</strong>
                            </div>
                        </div>

                        <!-- Card 2: Location -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px 18px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(239,68,68,0.1); color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <span style="display: block; font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px;">Country / Region</span>
                                <strong style="font-size: 0.95rem; font-weight: 800; color: #0f172a;">{{ $talent->country }}</strong>
                            </div>
                        </div>

                        <!-- Card 3: Phone Contact -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px 18px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16,185,129,0.1); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                                <i class="bi bi-telephone-fill"></i>
                            </div>
                            <div>
                                <span style="display: block; font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px;">Direct Phone / Line</span>
                                @if($talent->phone)
                                <a href="tel:{{ $talent->phone }}" style="font-size: 0.95rem; font-weight: 800; color: #0f172a; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                                    {{ $talent->phone }} <i class="bi bi-box-arrow-up-right" style="font-size: 0.72rem; color: #10b981;"></i>
                                </a>
                                @else
                                <span style="font-size: 0.9rem; color: #94a3b8; font-weight: 500;">Not Provided</span>
                                @endif
                            </div>
                        </div>

                    </div>

                    <!-- Biography Section Card -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); margin-bottom: 20px;">
                        <h3 style="margin: 0 0 12px 0; font-size: 1rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-card-text" style="color: #6366f1;"></i> About &amp; Professional Biography
                        </h3>
                        <div style="background: #f8fafc; border-left: 4px solid #6366f1; border-radius: 0 10px 10px 0; padding: 16px 18px;">
                            <p style="color: #334155; font-size: 0.93rem; line-height: 1.7; margin: 0; white-space: pre-line;">
                                {{ $talent->description ?: 'No bio biography has been provided by the user yet.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Social & Web Media Channels Box -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); margin-bottom: 24px;">
                        <h3 style="margin: 0 0 14px 0; font-size: 1rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-share-fill" style="color: #6366f1;"></i> Official Media &amp; Social Channels
                        </h3>
                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                            @if($talent->social_instagram)
                            <a href="{{ $talent->social_instagram }}" target="_blank" style="padding: 10px 18px; border-radius: 10px; font-size: 0.85rem; font-weight: 700; background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); color: #ffffff; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(220,39,67,0.25); transition: transform 0.2s ease;">
                                <i class="bi bi-instagram" style="font-size: 1.05rem;"></i> Instagram Channel
                            </a>
                            @endif
                            @if($talent->social_facebook)
                            <a href="{{ $talent->social_facebook }}" target="_blank" style="padding: 10px 18px; border-radius: 10px; font-size: 0.85rem; font-weight: 700; background: #1877f2; color: #ffffff; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(24,119,242,0.25); transition: transform 0.2s ease;">
                                <i class="bi bi-facebook" style="font-size: 1.05rem;"></i> Facebook Page
                            </a>
                            @endif
                            @if($talent->social_tiktok)
                            <a href="{{ $talent->social_tiktok }}" target="_blank" style="padding: 10px 18px; border-radius: 10px; font-size: 0.85rem; font-weight: 700; background: #000000; color: #ffffff; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.25); transition: transform 0.2s ease;">
                                <i class="bi bi-tiktok" style="font-size: 1.05rem;"></i> TikTok Feed
                            </a>
                            @endif
                            @if($talent->social_youtube)
                            <a href="{{ $talent->social_youtube }}" target="_blank" style="padding: 10px 18px; border-radius: 10px; font-size: 0.85rem; font-weight: 700; background: #ff0000; color: #ffffff; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(255,0,0,0.25); transition: transform 0.2s ease;">
                                <i class="bi bi-youtube" style="font-size: 1.05rem;"></i> YouTube Channel
                            </a>
                            @endif

                            @if(!$talent->social_instagram && !$talent->social_facebook && !$talent->social_tiktok && !$talent->social_youtube)
                            <div style="padding: 14px 18px; background: #f8fafc; border-radius: 10px; border: 1px dashed #cbd5e1; color: #64748b; font-size: 0.86rem; font-weight: 600; width: 100%;">
                                <i class="bi bi-info-circle"></i> No social media channels linked yet by {{ $talent->name }}.
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Fan Comments & Reviews Section -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                            <h3 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                                <i class="bi bi-chat-quote-fill" style="color: #6366f1;"></i> Public Fan Reviews &amp; Remarks
                            </h3>
                            <span style="background: rgba(99,102,241,0.1); color: #6366f1; font-weight: 700; font-size: 0.8rem; padding: 4px 12px; border-radius: 20px;">
                                {{ $comments->count() }} {{ Str::plural('Comment', $comments->count()) }}
                            </span>
                        </div>

                        <!-- Comment Submission Form -->
                        <form action="{{ route('talent.comment', $talent->id) }}" method="POST" style="background: #f8fafc; padding: 18px; border-radius: 12px; border: 1px solid #cbd5e1; margin-bottom: 20px;">
                            @csrf
                            @guest
                            <div class="form-group" style="margin-bottom: 12px;">
                                <label style="font-size: 0.82rem; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Your Name *</label>
                                <input type="text" name="author_name" class="form-control" placeholder="Enter your full name..." required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 8px; padding: 9px 13px; font-size: 0.88rem; width: 100%;">
                            </div>
                            @endguest
                            <div class="form-group" style="margin-bottom: 12px;">
                                <label style="font-size: 0.82rem; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Leave a Comment or Fan Review *</label>
                                <textarea name="comment" rows="3" class="form-control" placeholder="Write a message or review for {{ $talent->name }}..." required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 13px; font-size: 0.88rem; width: 100%; line-height: 1.5;"></textarea>
                            </div>
                            <div style="display: flex; justify-content: flex-end;">
                                <button type="submit" style="padding: 10px 22px; border-radius: 10px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; border: none; font-weight: 700; font-size: 0.86rem; cursor: pointer; box-shadow: 0 4px 12px rgba(99,102,241,0.35);">
                                    <i class="bi bi-send-fill"></i> Post Comment
                                </button>
                            </div>
                        </form>

                        <!-- Comments Stream List -->
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            @forelse($comments as $cmt)
                            <div style="background: #f8fafc; padding: 14px 16px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; gap: 12px; align-items: flex-start;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; font-weight: 800; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 8px rgba(99,102,241,0.25);">
                                    {{ strtoupper(substr($cmt->author_name, 0, 1)) }}
                                </div>
                                <div style="flex-grow: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                        <strong style="color: #0f172a; font-size: 0.88rem; font-weight: 700;">{{ $cmt->author_name }}</strong>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <span style="font-size: 0.74rem; color: #94a3b8; font-weight: 600;"><i class="bi bi-clock"></i> {{ $cmt->created_at->diffForHumans() }}</span>
                                            @php
                                                $isCommentAuthor = false;
                                                if (auth()->check() && ($cmt->user_id == auth()->id() || auth()->user()->role === 'admin')) {
                                                    $isCommentAuthor = true;
                                                } elseif (!$cmt->user_id && ($cmt->ip_address == request()->ip() || ($cmt->device_fingerprint && $cmt->device_fingerprint == request()->cookie('device_token')))) {
                                                    $isCommentAuthor = true;
                                                }
                                            @endphp
                                            @if($isCommentAuthor)
                                                <form action="{{ route('talent.comment.delete', $cmt->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this comment?');" style="margin: 0;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="background: none; border: none; color: #ef4444; font-size: 0.76rem; font-weight: 700; cursor: pointer; padding: 0; display: inline-flex; align-items: center; gap: 3px;" title="Delete this comment (Uncomment)">
                                                        <i class="bi bi-trash-fill"></i> Uncomment
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <p style="margin: 0; color: #475569; font-size: 0.86rem; line-height: 1.5; white-space: pre-line;">{{ $cmt->comment }}</p>
                                </div>
                            </div>
                            @empty
                            <div style="text-align: center; padding: 30px 20px; color: #94a3b8; font-size: 0.88rem; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
                                <i class="bi bi-chat-left-dots" style="font-size: 2rem; color: #cbd5e1; display: block; margin-bottom: 8px;"></i>
                                No comments posted yet for {{ $talent->name }}. Be the first to leave a comment!
                            </div>
                            @endforelse
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
                                <i class="bi bi-images" style="color: #6366f1;"></i> Official Photo Gallery &amp; Portfolio
                            </h2>
                            <p style="margin: 0; color: #94a3b8; font-size: 0.84rem;">High-resolution photography, event highlights, and press shots of {{ $talent->name }}.</p>
                        </div>
                        <div style="background: rgba(99,102,241,0.2); border: 1px solid rgba(99,102,241,0.4); padding: 5px 14px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; color: #a5b4fc;">
                            {{ $miniPhotos->count() }} {{ Str::plural('Photo', $miniPhotos->count()) }}
                        </div>
                    </div>

                    <!-- Photos Grid -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px;">
                        @forelse($miniPhotos as $photo)
                        <div class="profile-photo-card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; flex-direction: column; transition: transform 0.2s ease, box-shadow 0.2s ease;">
                            <div style="width: 100%; aspect-ratio: 4/3; overflow: hidden; position: relative; background: #0f172a; cursor: pointer;" onclick="openPhotoViewer('{{ asset($photo->file_path) }}', '{{ addslashes($photo->title ?: 'Portfolio Asset') }}')">
                                <img src="{{ asset($photo->file_path) }}" alt="{{ $photo->title ?: $talent->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                                <div style="position: absolute; top: 10px; right: 10px; background: rgba(15,23,42,0.7); backdrop-filter: blur(4px); padding: 4px 10px; border-radius: 12px; font-size: 0.72rem; font-weight: 700; color: #ffffff;">
                                    <i class="bi bi-arrows-angle-expand"></i> View
                                </div>
                            </div>
                            <div style="padding: 12px 14px; background: #ffffff;">
                                <h4 style="margin: 0 0 4px 0; font-size: 0.92rem; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $photo->title ?: 'Portfolio Asset' }}</h4>
                                <span style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;"><i class="bi bi-clock"></i> {{ $photo->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @empty
                        <div style="grid-column: 1/-1; text-align: center; padding: 50px 20px; background: #ffffff; border-radius: 14px; border: 1px dashed #cbd5e1;">
                            <i class="bi bi-camera" style="font-size: 2.5rem; color: #94a3b8; display: block; margin-bottom: 10px;"></i>
                            <p style="margin: 0; color: #64748b; font-weight: 600; font-size: 0.92rem;">No portfolio photos uploaded yet for {{ $talent->name }}.</p>
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
                                <i class="bi bi-camera-reels-fill" style="color: #ec4899;"></i> Official Video Showcase &amp; Reels
                            </h2>
                            <p style="margin: 0; color: #94a3b8; font-size: 0.84rem;">Official music videos, interviews, live performances, and video clips from {{ $talent->name }}.</p>
                        </div>
                        @php
                        $videos = $talent->media()->where('type', 'video')->latest()->get();
                        @endphp
                        <div style="background: rgba(236,72,153,0.2); border: 1px solid rgba(236,72,153,0.4); padding: 5px 14px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; color: #f472b6;">
                            {{ $videos->count() }} {{ Str::plural('Video', $videos->count()) }}
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
                                        <i class="bi bi-play-btn-fill" style="color: #ec4899;"></i> {{ $video->title ?: 'Portfolio Video Showcase' }}
                                    </h4>
                                    @if($video->content)
                                    <p style="margin: 0 0 8px 0; color: #475569; font-size: 0.84rem; line-height: 1.5; white-space: pre-line;">{{ $video->content }}</p>
                                    @endif
                                </div>
                                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.76rem; color: #94a3b8; font-weight: 600; border-top: 1px solid #f1f5f9; padding-top: 10px;">
                                    <span><i class="bi bi-clock-history" style="color: #ec4899;"></i> {{ $video->created_at->diffForHumans() }}</span>
                                    <span style="background: #f1f5f9; padding: 3px 10px; border-radius: 10px; color: #475569;"><i class="bi bi-film"></i> HD Video</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div style="grid-column: 1/-1; text-align: center; padding: 50px 20px; background: #ffffff; border-radius: 14px; border: 1px dashed #cbd5e1;">
                            <i class="bi bi-film" style="font-size: 2.5rem; color: #94a3b8; display: block; margin-bottom: 10px;"></i>
                            <p style="margin: 0; color: #64748b; font-weight: 600; font-size: 0.92rem;">No video showcases uploaded yet for {{ $talent->name }}.</p>
                        </div>
                        @endforelse
                    </div>

                </div>
            </div>

            <!-- News Section -->
            <div id="news-tab" class="profile-tab-section">
                <div class="pdetails" style="background: transparent; border-radius: 0; padding: 0; box-shadow: none; border: none;">
                    <!-- News Header Banner -->
                    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; padding: 18px 22px; border-radius: 14px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                        <div>
                            <h2 style="margin: 0 0 4px 0; font-size: 1.25rem; font-weight: 800; color: #ffffff; border: none; padding: 0; display: flex; align-items: center; gap: 10px;">
                                <i class="bi bi-newspaper" style="color: #6366f1;"></i> Official News &amp; Press Bulletins
                            </h2>
                            <p style="margin: 0; color: #94a3b8; font-size: 0.84rem;">Latest announcements, tour dates, press releases, and media updates from {{ $talent->name }}.</p>
                        </div>
                        @php
                        $newsItems = $talent->media()->where('type', 'news')->latest()->get();
                        @endphp
                        <div style="background: rgba(99,102,241,0.2); border: 1px solid rgba(99,102,241,0.4); padding: 5px 14px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; color: #a5b4fc;">
                            {{ $newsItems->count() }} {{ Str::plural('Article', $newsItems->count()) }}
                        </div>
                    </div>

                    <!-- News Articles Feed -->
                    <div style="display: flex; flex-direction: column; gap: 24px;">
                        @forelse($newsItems as $news)
                        <article class="news-article-card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,0.04); transition: transform 0.2s ease, box-shadow 0.2s ease;">
                            @if($news->file_path)
                            <div style="position: relative; width: 100%; max-height: 380px; overflow: hidden; background: #0f172a;">
                                <img src="{{ asset($news->file_path) }}" alt="{{ $news->title }}" style="width: 100%; height: 100%; object-fit: cover; max-height: 380px; display: block;">
                                <div style="position: absolute; top: 16px; left: 16px; background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.2); padding: 6px 14px; border-radius: 20px; font-weight: 700; font-size: 0.76rem; color: #38bdf8; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <i class="bi bi-lightning-charge-fill" style="color: #f59e0b;"></i> Official Bulletin
                                </div>
                            </div>
                            @endif

                            <div style="padding: 18px 20px;">
                                <!-- Meta bar -->
                                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 14px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 34px; height: 34px; border-radius: 50%; overflow: hidden; border: 2px solid #6366f1; flex-shrink: 0; background: #f1f5f9;">
                                            @if($talent->profile_image)
                                            <img src="{{ asset($talent->profile_image) }}" alt="{{ $talent->name }}" style="width:100%; height:100%; object-fit:cover;">
                                            @else
                                            <i class="bi bi-person-fill" style="color: #64748b; margin: 6px;"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <span style="font-weight: 700; font-size: 0.86rem; color: #0f172a;">{{ $talent->name }}</span>
                                            <span style="font-size: 0.76rem; color: #64748b; margin-left: 6px;">&bull; {{ $talent->category_label }}</span>
                                        </div>
                                    </div>

                                    <div style="display: flex; align-items: center; gap: 14px; font-size: 0.78rem; color: #64748b; font-weight: 600; flex-wrap: wrap;">
                                        <span><i class="bi bi-calendar3" style="color: #6366f1;"></i> {{ $news->created_at->format('M d, Y') }}</span>
                                        <span><i class="bi bi-clock-history" style="color: #6366f1;"></i> {{ $news->created_at->diffForHumans() }}</span>
                                        <span style="background: #f1f5f9; padding: 4px 10px; border-radius: 12px; color: #475569;"><i class="bi bi-book"></i> {{ max(1, (int)ceil(str_word_count(strip_tags($news->content)) / 200)) }} min read</span>
                                    </div>
                                </div>

                                <!-- Headline -->
                                <h3 style="margin: 0 0 12px 0; font-size: 1.25rem; font-weight: 800; color: #0f172a; line-height: 1.35;">
                                    {{ $news->title }}
                                </h3>

                                <!-- Body content -->
                                <div style="color: #334155; font-size: 0.94rem; line-height: 1.7; white-space: pre-line; margin-bottom: 20px;">
                                    {{ $news->content }}
                                </div>

                                <!-- Footer Bar -->
                                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 14px; border-top: 1px dashed #e2e8f0; flex-wrap: wrap; gap: 10px;">
                                    <div style="font-size: 0.78rem; font-weight: 600; color: #64748b; display: flex; align-items: center; gap: 6px;">
                                        <i class="bi bi-shield-check" style="color: #10b981; font-size: 0.95rem;"></i> Verified Official Release
                                    </div>
                                    <button type="button" onclick="navigator.clipboard.writeText(window.location.origin + window.location.pathname + '#news-tab'); alert('News link copied to clipboard!');" style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 6px 14px; font-size: 0.78rem; font-weight: 700; color: #475569; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s ease;">
                                        <i class="bi bi-share-fill" style="color: #6366f1;"></i> Share Update
                                    </button>
                                </div>
                            </div>
                        </article>
                        @empty
                        <div style="padding: 50px 20px; text-align: center; background: #f8fafc; border-radius: 14px; border: 1px dashed #cbd5e1;">
                            <i class="bi bi-newspaper" style="font-size: 2.8rem; color: #94a3b8; display: block; margin-bottom: 12px;"></i>
                            <h4 style="margin: 0 0 6px 0; font-size: 1.05rem; font-weight: 700; color: #334155;">No Press Releases Published Yet</h4>
                            <p style="margin: 0; color: #64748b; font-weight: 500; font-size: 0.88rem;">Official announcements and tour updates for {{ $talent->name }} will appear here.</p>
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
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    window.openPhotoViewer = function(url, title) {
        var modal = document.getElementById('photoViewerModal');
        var img = document.getElementById('viewerModalImg');
        var titleEl = document.getElementById('viewerModalTitle');
        if (modal && img) {
            img.src = url;
            if (titleEl) titleEl.innerText = title;
            modal.style.display = 'flex';
        }
    };

    window.closePhotoViewer = function() {
        var modal = document.getElementById('photoViewerModal');
        if (modal) {
            modal.style.display = 'none';
        }
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
                    likeBtn.textContent = 'Liked ❤️';
                }
                if (followBtn && data.is_following) {
                    followBtn.classList.add('following');
                    followBtn.textContent = 'Following';
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
                        btn.textContent = 'Liked ❤️';
                    } else {
                        btn.classList.remove('liked');
                        btn.textContent = 'Like 🤍';
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
                        btn.textContent = 'Following';
                    } else {
                        btn.classList.remove('following');
                        btn.textContent = 'Followers';
                    }
                    count.textContent = res.count;
                }
            },
            error: function() {
                btn.disabled = false;
            }
        });
    }
</script>
@endsection