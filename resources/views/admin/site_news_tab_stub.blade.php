<!-- ==========================================================================
     WEBSITE NEWS MANAGEMENT TAB CONTENT (Admin & Customer Care Dashboards)
     ========================================================================== -->
@php
    $siteNewsList = $siteNewsList ?? \App\Models\SiteNews::with('user')->withCount(['likes', 'shares'])->latest()->get();
    $siteNewsInterval = $siteNewsInterval ?? (int) \App\Models\SystemSetting::get('site_news_interval', 5);
@endphp

<div id="tab-site-news" class="tab-content" style="display: none;">
    <!-- Top Header Banner -->
    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; padding: 22px 26px; border-radius: 18px; margin-bottom: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.12); border: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 52px; height: 52px; border-radius: 16px; background: rgba(245, 158, 11, 0.18); border: 1px solid rgba(245, 158, 11, 0.35); display: flex; align-items: center; justify-content: center; color: #f59e0b; font-size: 1.6rem; flex-shrink: 0;">
                <i class="bi bi-megaphone-fill"></i>
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <h2 style="margin: 0; font-size: 1.3rem; font-weight: 800; color: #ffffff;">{{ __('Website News Feed') }}</h2>
                    <span style="background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.35); font-size: 0.72rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 5px;">
                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #fbbf24;"></span> {{ __('Official Site Feed') }}
                    </span>
                </div>
                <p style="margin: 4px 0 0 0; color: #94a3b8; font-size: 0.86rem;">
                    {{ __('Publish official announcements, photos, and videos displayed periodically in the Post View sidebar feed.') }}
                </p>
            </div>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <a href="{{ route('home') }}" target="_blank" class="vbtn" style="background: rgba(255,255,255,0.12); color: #fff; border: 1px solid rgba(255,255,255,0.25); border-radius: 30px; font-weight: 700; font-size: 0.84rem; padding: 9px 18px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
                <i class="bi bi-box-arrow-up-right"></i> {{ __('View Live Feed') }}
            </a>
            <button type="button" onclick="$('#create-site-news-modal').fadeIn(200);" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #ffffff; border: none; border-radius: 30px; font-weight: 700; font-size: 0.86rem; padding: 10px 22px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(245, 158, 11, 0.35); transition: all 0.2s ease;">
                <i class="bi bi-plus-circle-fill" style="font-size: 1rem;"></i> {{ __('Publish News Post') }}
            </button>
        </div>
    </div>

    <!-- Admin Display Interval Setting Banner -->
    @if(auth()->user()->role === 'admin')
    <div style="background: #ffffff; border-radius: 16px; padding: 18px 24px; margin-bottom: 22px; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 14px;">
            <div style="width: 42px; height: 42px; border-radius: 12px; background: rgba(99, 102, 241, 0.12); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                <i class="bi bi-sliders"></i>
            </div>
            <div>
                <h4 style="margin: 0 0 3px 0; font-size: 0.95rem; font-weight: 800; color: #0f172a;">{{ __('Feed Display Interval') }}</h4>
                <p style="margin: 0; font-size: 0.82rem; color: #64748b;">
                    {{ __('Controls how often a news card is blended into the Post View sidebar feed.') }}
                    <strong style="color: #4f46e5;">(Currently 1 news per every {{ $siteNewsInterval }} talent posts)</strong>
                </p>
            </div>
        </div>

        <form action="{{ route('admin.settings.site-news-interval') }}" method="POST" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            @csrf
            <div style="display: flex; align-items: center; gap: 6px;">
                <label for="site_news_interval_input" style="font-size: 0.82rem; font-weight: 700; color: #475569;">{{ __('Every') }}</label>
                <input type="number" id="site_news_interval_input" name="site_news_interval" value="{{ $siteNewsInterval }}" min="1" max="50" required style="width: 70px; padding: 7px 10px; border-radius: 8px; border: 1px solid #cbd5e1; font-weight: 700; font-size: 0.88rem; text-align: center; color: #0f172a;">
                <span style="font-size: 0.82rem; font-weight: 700; color: #475569;">{{ __('posts') }}</span>
            </div>
            <button type="submit" style="padding: 7px 18px; border-radius: 20px; background: #4f46e5; color: #ffffff; border: none; font-weight: 700; font-size: 0.82rem; cursor: pointer; transition: all 0.2s ease;">
                {{ __('Update Interval') }}
            </button>
        </form>
    </div>
    @endif

    <!-- Statistics Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 25px;">
        <!-- Stat 1: Total Published News -->
        <div style="background: #ffffff; border-radius: 16px; padding: 18px 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
            <div style="width: 46px; height: 46px; border-radius: 12px; background: rgba(245, 158, 11, 0.12); color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; flex-shrink: 0;">
                <i class="bi bi-broadcast"></i>
            </div>
            <div>
                <div style="font-size: 1.45rem; font-weight: 800; color: #0f172a;">{{ $siteNewsList->where('is_published', true)->count() }}</div>
                <div style="font-size: 0.78rem; color: #64748b; font-weight: 600;">{{ __('Active News Posts') }}</div>
            </div>
        </div>

        <!-- Stat 2: Total Views -->
        <div style="background: #ffffff; border-radius: 16px; padding: 18px 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
            <div style="width: 46px; height: 46px; border-radius: 12px; background: rgba(2, 132, 199, 0.12); color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; flex-shrink: 0;">
                <i class="bi bi-eye-fill"></i>
            </div>
            <div>
                <div style="font-size: 1.45rem; font-weight: 800; color: #0f172a;">{{ number_format($siteNewsList->sum('views_count')) }}</div>
                <div style="font-size: 0.78rem; color: #64748b; font-weight: 600;">{{ __('Total Impressions') }}</div>
            </div>
        </div>

        <!-- Stat 3: Total Likes -->
        <div style="background: #ffffff; border-radius: 16px; padding: 18px 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
            <div style="width: 46px; height: 46px; border-radius: 12px; background: rgba(239, 68, 68, 0.12); color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; flex-shrink: 0;">
                <i class="bi bi-heart-fill"></i>
            </div>
            <div>
                <div style="font-size: 1.45rem; font-weight: 800; color: #0f172a;">{{ number_format($siteNewsList->sum('likes_count')) }}</div>
                <div style="font-size: 0.78rem; color: #64748b; font-weight: 600;">{{ __('Total Likes') }}</div>
            </div>
        </div>

        <!-- Stat 4: Total Shares -->
        <div style="background: #ffffff; border-radius: 16px; padding: 18px 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
            <div style="width: 46px; height: 46px; border-radius: 12px; background: rgba(99, 102, 241, 0.12); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; flex-shrink: 0;">
                <i class="bi bi-share-fill"></i>
            </div>
            <div>
                <div style="font-size: 1.45rem; font-weight: 800; color: #0f172a;">{{ number_format($siteNewsList->sum('shares_count')) }}</div>
                <div style="font-size: 0.78rem; color: #64748b; font-weight: 600;">{{ __('Total Shares') }}</div>
            </div>
        </div>
    </div>

    <!-- News Posts Table Card -->
    <div style="background: #ffffff; border-radius: 16px; padding: 22px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.04);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 12px;">
            <div>
                <h3 style="margin: 0 0 4px 0; font-size: 1.1rem; font-weight: 800; color: #0f172a;">
                    {{ __('Published News Roster') }} ({{ count($siteNewsList) }})
                </h3>
                <p style="margin: 0; color: #64748b; font-size: 0.84rem;">
                    {{ __('Manage active publications, review reader engagement, or update news content.') }}
                </p>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="table" style="width: 100%; border-collapse: separate; border-spacing: 0; font-size: 0.86rem;">
                <thead>
                    <tr style="background: #f8fafc; color: #475569; font-weight: 700; text-transform: uppercase; font-size: 0.74rem; letter-spacing: 0.5px;">
                        <th style="padding: 12px 14px; border-bottom: 2px solid #e2e8f0; border-top-left-radius: 10px;">{{ __('Type') }}</th>
                        <th style="padding: 12px 14px; border-bottom: 2px solid #e2e8f0;">{{ __('Media') }}</th>
                        <th style="padding: 12px 14px; border-bottom: 2px solid #e2e8f0;">{{ __('Title & Content') }}</th>
                        <th style="padding: 12px 14px; border-bottom: 2px solid #e2e8f0;">{{ __('Author') }}</th>
                        <th style="padding: 12px 14px; border-bottom: 2px solid #e2e8f0; text-align: center;">{{ __('Stats') }}</th>
                        <th style="padding: 12px 14px; border-bottom: 2px solid #e2e8f0; text-align: center;">{{ __('Priority') }}</th>
                        <th style="padding: 12px 14px; border-bottom: 2px solid #e2e8f0; text-align: center;">{{ __('Status') }}</th>
                        <th style="padding: 12px 14px; border-bottom: 2px solid #e2e8f0; text-align: right; border-top-right-radius: 10px;">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siteNewsList as $news)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s ease;">
                        <!-- Type Badge -->
                        <td style="padding: 14px; vertical-align: middle;">
                            @if($news->type === 'text')
                                <span style="background: #e0e7ff; color: #4338ca; padding: 4px 9px; border-radius: 6px; font-weight: 700; font-size: 0.72rem; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="bi bi-file-text"></i> {{ __('Text') }}
                                </span>
                            @elseif($news->type === 'photo')
                                <span style="background: #fef3c7; color: #b45309; padding: 4px 9px; border-radius: 6px; font-weight: 700; font-size: 0.72rem; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="bi bi-image"></i> {{ __('Photo') }}
                                </span>
                            @elseif($news->type === 'video')
                                <span style="background: #fee2e2; color: #b91c1c; padding: 4px 9px; border-radius: 6px; font-weight: 700; font-size: 0.72rem; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="bi bi-play-btn"></i> {{ __('Video') }}
                                </span>
                            @elseif($news->type === 'text_photo')
                                <span style="background: #ecfdf5; color: #047857; padding: 4px 9px; border-radius: 6px; font-weight: 700; font-size: 0.72rem; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="bi bi-card-image"></i> {{ __('Text + Photo') }}
                                </span>
                            @elseif($news->type === 'text_video')
                                <span style="background: #fdf2f8; color: #be185d; padding: 4px 9px; border-radius: 6px; font-weight: 700; font-size: 0.72rem; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="bi bi-file-earmark-play"></i> {{ __('Text + Video') }}
                                </span>
                            @endif
                        </td>

                        <!-- Media Preview Thumbnail -->
                        <td style="padding: 14px; vertical-align: middle;">
                            @if(in_array($news->type, ['photo', 'text_photo']) && $news->image_path)
                                <a href="{{ asset($news->image_path) }}" target="_blank">
                                    <img src="{{ asset($news->image_path) }}" alt="Preview" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1;">
                                </a>
                            @elseif(in_array($news->type, ['video', 'text_video']))
                                <div style="width: 50px; height: 50px; border-radius: 8px; background: #0f172a; color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                    <i class="bi bi-camera-video-fill"></i>
                                </div>
                            @else
                                <span style="color: #94a3b8; font-size: 0.75rem; font-style: italic;">{{ __('No media') }}</span>
                            @endif
                        </td>

                        <!-- Title & Content -->
                        <td style="padding: 14px; vertical-align: middle; max-width: 280px;">
                            <div style="font-weight: 800; color: #0f172a; font-size: 0.88rem; margin-bottom: 2px;">
                                {{ $news->title ?: __('(Untitled News Post)') }}
                            </div>
                            @if($news->body)
                                <div style="color: #64748b; font-size: 0.78rem; line-height: 1.4; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 260px;">
                                    {{ Str::limit($news->body, 85) }}
                                </div>
                            @endif
                            <div style="font-size: 0.7rem; color: #94a3b8; margin-top: 3px;">
                                {{ $news->created_at->format('M d, Y H:i') }}
                                @if($news->expires_at)
                                    &bull; <span style="color: #d97706;">Expires {{ $news->expires_at->format('M d, Y') }}</span>
                                @endif
                            </div>
                        </td>

                        <!-- Author -->
                        <td style="padding: 14px; vertical-align: middle; white-space: nowrap;">
                            <span style="font-weight: 700; color: #334155; font-size: 0.82rem; display: inline-flex; align-items: center; gap: 5px;">
                                <i class="bi bi-person-circle" style="color: #64748b;"></i> {{ $news->author_name }}
                            </span>
                        </td>

                        <!-- Stats (Views, Likes, Shares) -->
                        <td style="padding: 14px; vertical-align: middle; text-align: center; white-space: nowrap;">
                            <div style="display: inline-flex; align-items: center; gap: 10px; font-size: 0.78rem; font-weight: 700; color: #64748b;">
                                <span title="Views" style="display: inline-flex; align-items: center; gap: 3px;"><i class="bi bi-eye"></i> {{ $news->views_count }}</span>
                                <span title="Likes" style="display: inline-flex; align-items: center; gap: 3px; color: #ef4444;"><i class="bi bi-heart-fill"></i> {{ $news->likes_count }}</span>
                                <span title="Shares" style="display: inline-flex; align-items: center; gap: 3px; color: #6366f1;"><i class="bi bi-share-fill"></i> {{ $news->shares_count }}</span>
                            </div>
                        </td>

                        <!-- Priority -->
                        <td style="padding: 14px; vertical-align: middle; text-align: center;">
                            <span style="font-weight: 800; font-size: 0.82rem; color: {{ $news->priority > 0 ? '#b45309' : '#64748b' }};">
                                {{ $news->priority }}
                            </span>
                        </td>

                        <!-- Status Toggle Button -->
                        <td style="padding: 14px; vertical-align: middle; text-align: center;">
                            <form action="{{ route('staff.site-news.toggle', $news->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @if($news->is_published)
                                    <button type="submit" style="background: rgba(16, 185, 129, 0.15); color: #059669; border: 1px solid rgba(16, 185, 129, 0.35); padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 0.74rem; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;" title="{{ __('Click to Unpublish') }}">
                                        <i class="bi bi-check-circle-fill"></i> {{ __('Active') }}
                                    </button>
                                @else
                                    <button type="submit" style="background: rgba(100, 116, 139, 0.15); color: #64748b; border: 1px solid rgba(100, 116, 139, 0.3); padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 0.74rem; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;" title="{{ __('Click to Publish') }}">
                                        <i class="bi bi-pause-circle"></i> {{ __('Inactive') }}
                                    </button>
                                @endif
                            </form>
                        </td>

                        <!-- Actions -->
                        <td style="padding: 14px; vertical-align: middle; text-align: right; white-space: nowrap;">
                            <button type="button" onclick="$('#edit-site-news-modal-{{ $news->id }}').fadeIn(200);" style="background: rgba(99, 102, 241, 0.1); color: #4f46e5; border: 1px solid rgba(99, 102, 241, 0.25); border-radius: 8px; padding: 6px 10px; font-weight: 700; font-size: 0.78rem; cursor: pointer; margin-right: 6px;">
                                <i class="bi bi-pencil-square"></i> {{ __('Edit') }}
                            </button>
                            <form action="{{ route('staff.site-news.delete', $news->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to permanently delete this news post?') }}');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: rgba(239, 68, 68, 0.1); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.25); border-radius: 8px; padding: 6px 10px; font-weight: 700; font-size: 0.78rem; cursor: pointer;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 45px 15px; color: #94a3b8;">
                            <i class="bi bi-megaphone" style="font-size: 2.5rem; color: #cbd5e1; display: block; margin-bottom: 8px;"></i>
                            <p style="font-size: 0.95rem; font-weight: 600; margin: 0 0 10px 0;">{{ __('No website news posts published yet.') }}</p>
                            <button type="button" onclick="$('#create-site-news-modal').fadeIn(200);" style="padding: 8px 20px; border-radius: 20px; background: #f59e0b; color: #fff; border: none; font-weight: 700; font-size: 0.82rem; cursor: pointer;">
                                + {{ __('Create First News Post') }}
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ==========================================================================
     CREATE SITE NEWS MODAL
     ========================================================================== -->
<div id="create-site-news-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15,23,42,0.65); backdrop-filter: blur(5px); z-index: 99999; justify-content: center; align-items: center; padding: 15px; overflow-y: auto;">
    <div style="background: #ffffff; border-radius: 20px; width: 100%; max-width: 650px; padding: 26px; box-shadow: 0 25px 50px rgba(0,0,0,0.25); position: relative; margin: 20px auto; max-height: 92vh; display: flex; flex-direction: column;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(245, 158, 11, 0.15); color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="bi bi-broadcast"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">{{ __('Create Site News Post') }}</h3>
                    <p style="margin: 0; font-size: 0.78rem; color: #64748b;">{{ __('Will be displayed automatically in Post View sidebar') }}</p>
                </div>
            </div>
            <button type="button" onclick="$('#create-site-news-modal').fadeOut(200);" style="background: none; border: none; font-size: 1.5rem; color: #64748b; cursor: pointer;">&times;</button>
        </div>

        <form action="{{ route('staff.site-news.store') }}" method="POST" enctype="multipart/form-data" id="createSiteNewsForm" style="overflow-y: auto; padding-right: 6px; flex-grow: 1;">
            @csrf

            <!-- Format Selector (5 types) -->
            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 8px;">
                    {{ __('Choose Post Format') }} <span style="color: #ef4444;">*</span>
                </label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(105px, 1fr)); gap: 8px;">
                    <label class="news-type-card" style="border: 2px solid #f59e0b; background: #fffbeb; border-radius: 12px; padding: 10px 8px; text-align: center; cursor: pointer; transition: all 0.2s;">
                        <input type="radio" name="type" value="text" checked onchange="updateNewsFormFields('create', this.value)" style="display: none;">
                        <i class="bi bi-file-text-fill" style="font-size: 1.3rem; color: #d97706; display: block; margin-bottom: 4px;"></i>
                        <span style="font-size: 0.75rem; font-weight: 700; color: #0f172a; display: block;">{{ __('Text Only') }}</span>
                    </label>

                    <label class="news-type-card" style="border: 2px solid #e2e8f0; background: #f8fafc; border-radius: 12px; padding: 10px 8px; text-align: center; cursor: pointer; transition: all 0.2s;">
                        <input type="radio" name="type" value="photo" onchange="updateNewsFormFields('create', this.value)" style="display: none;">
                        <i class="bi bi-image-fill" style="font-size: 1.3rem; color: #0284c7; display: block; margin-bottom: 4px;"></i>
                        <span style="font-size: 0.75rem; font-weight: 700; color: #0f172a; display: block;">{{ __('Photo Only') }}</span>
                    </label>

                    <label class="news-type-card" style="border: 2px solid #e2e8f0; background: #f8fafc; border-radius: 12px; padding: 10px 8px; text-align: center; cursor: pointer; transition: all 0.2s;">
                        <input type="radio" name="type" value="video" onchange="updateNewsFormFields('create', this.value)" style="display: none;">
                        <i class="bi bi-play-circle-fill" style="font-size: 1.3rem; color: #ef4444; display: block; margin-bottom: 4px;"></i>
                        <span style="font-size: 0.75rem; font-weight: 700; color: #0f172a; display: block;">{{ __('Video Only') }}</span>
                    </label>

                    <label class="news-type-card" style="border: 2px solid #e2e8f0; background: #f8fafc; border-radius: 12px; padding: 10px 8px; text-align: center; cursor: pointer; transition: all 0.2s;">
                        <input type="radio" name="type" value="text_photo" onchange="updateNewsFormFields('create', this.value)" style="display: none;">
                        <i class="bi bi-card-image" style="font-size: 1.3rem; color: #059669; display: block; margin-bottom: 4px;"></i>
                        <span style="font-size: 0.75rem; font-weight: 700; color: #0f172a; display: block;">{{ __('Text + Photo') }}</span>
                    </label>

                    <label class="news-type-card" style="border: 2px solid #e2e8f0; background: #f8fafc; border-radius: 12px; padding: 10px 8px; text-align: center; cursor: pointer; transition: all 0.2s;">
                        <input type="radio" name="type" value="text_video" onchange="updateNewsFormFields('create', this.value)" style="display: none;">
                        <i class="bi bi-file-earmark-play-fill" style="font-size: 1.3rem; color: #db2777; display: block; margin-bottom: 4px;"></i>
                        <span style="font-size: 0.75rem; font-weight: 700; color: #0f172a; display: block;">{{ __('Text + Video') }}</span>
                    </label>
                </div>
            </div>

            <!-- Title Field -->
            <div id="create_title_group" style="margin-bottom: 14px;">
                <label for="create_title" style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">
                    {{ __('Post Title / Headline') }} <span id="create_title_req" style="color: #ef4444;">*</span>
                </label>
                <input type="text" id="create_title" name="title" required placeholder="{{ __('e.g., Exciting Announcement: New Features Live!') }}" style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.88rem; color: #0f172a; outline: none;">
            </div>

            <!-- Body Field -->
            <div id="create_body_group" style="margin-bottom: 14px;">
                <label for="create_body" style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">
                    {{ __('Post Content / Message Body') }} <span id="create_body_req" style="color: #ef4444;">*</span>
                </label>
                <textarea id="create_body" name="body" rows="4" required placeholder="{{ __('Write your announcement or news text in your preferred language...') }}" style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.88rem; color: #0f172a; outline: none; line-height: 1.45;"></textarea>
            </div>

            <!-- Photo Upload Field (Shown for photo, text_photo) -->
            <div id="create_photo_group" style="margin-bottom: 14px; display: none;">
                <label for="create_image" style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">
                    {{ __('Upload Photo') }} <span style="color: #ef4444;">*</span>
                </label>
                <input type="file" id="create_image" name="image" accept="image/jpeg,image/png,image/webp,image/gif" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px dashed #cbd5e1; background: #f8fafc; font-size: 0.82rem;">
                <small style="color: #94a3b8; font-size: 0.74rem;">{{ __('Supports JPG, PNG, WEBP up to 10MB. Images are automatically optimized.') }}</small>
            </div>

            <!-- Video Field (Shown for video, text_video) -->
            <div id="create_video_group" style="margin-bottom: 14px; display: none;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">
                    {{ __('Video Source') }} <span style="color: #ef4444;">*</span>
                </label>
                
                <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                    <label style="font-size: 0.82rem; font-weight: 700; color: #475569; display: flex; align-items: center; gap: 5px; cursor: pointer;">
                        <input type="radio" name="video_source_type" value="file" checked onchange="toggleCreateVideoSource('file')">
                        {{ __('Upload Video File') }}
                    </label>
                    <label style="font-size: 0.82rem; font-weight: 700; color: #475569; display: flex; align-items: center; gap: 5px; cursor: pointer;">
                        <input type="radio" name="video_source_type" value="link" onchange="toggleCreateVideoSource('link')">
                        {{ __('External Video URL') }}
                    </label>
                </div>

                <!-- Video File Input -->
                <div id="create_video_file_input">
                    <input type="file" name="video_file" accept="video/mp4,video/webm,video/quicktime" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px dashed #cbd5e1; background: #f8fafc; font-size: 0.82rem;">
                    <small style="color: #94a3b8; font-size: 0.74rem;">{{ __('Supports MP4, WEBM, MOV up to 100MB. Autoplays when active in sidebar.') }}</small>
                </div>

                <!-- Video URL Input -->
                <div id="create_video_link_input" style="display: none;">
                    <input type="url" name="video_url" placeholder="https://www.youtube.com/watch?v=... or TikTok / Vimeo link" style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.88rem;">
                    <small style="color: #94a3b8; font-size: 0.74rem;">{{ __('Paste standard YouTube, Vimeo, TikTok, Instagram, or direct video link.') }}</small>
                </div>
            </div>

            <!-- Priority & Expiry Date Row -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 18px;">
                <div>
                    <label for="create_priority" style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">
                        {{ __('Display Priority') }}
                    </label>
                    <input type="number" id="create_priority" name="priority" value="0" min="0" max="999" placeholder="0" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; outline: none;">
                    <small style="color: #94a3b8; font-size: 0.72rem;">{{ __('Higher numbers show up earlier in feed.') }}</small>
                </div>
                <div>
                    <label for="create_expires_at" style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">
                        {{ __('Expiration (Optional)') }}
                    </label>
                    <input type="datetime-local" id="create_expires_at" name="expires_at" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; outline: none;">
                    <small style="color: #94a3b8; font-size: 0.72rem;">{{ __('Leave empty for permanent / indefinite display.') }}</small>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                <button type="button" onclick="$('#create-site-news-modal').fadeOut(200);" style="padding: 10px 20px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; font-weight: 700; font-size: 0.86rem; cursor: pointer;">
                    {{ __('Cancel') }}
                </button>
                <button type="submit" style="padding: 10px 24px; border-radius: 10px; border: none; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #ffffff; font-weight: 800; font-size: 0.88rem; cursor: pointer; box-shadow: 0 4px 14px rgba(245, 158, 11, 0.35);">
                    <i class="bi bi-broadcast"></i> {{ __('Publish News Post') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==========================================================================
     EDIT SITE NEWS MODALS (Rendered for each news item)
     ========================================================================== -->
@foreach($siteNewsList as $news)
<div id="edit-site-news-modal-{{ $news->id }}" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15,23,42,0.65); backdrop-filter: blur(5px); z-index: 99999; justify-content: center; align-items: center; padding: 15px; overflow-y: auto;">
    <div style="background: #ffffff; border-radius: 20px; width: 100%; max-width: 650px; padding: 26px; box-shadow: 0 25px 50px rgba(0,0,0,0.25); position: relative; margin: 20px auto; max-height: 92vh; display: flex; flex-direction: column;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(99, 102, 241, 0.15); color: #4f46e5; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">{{ __('Edit News Post') }} #{{ $news->id }}</h3>
                    <p style="margin: 0; font-size: 0.78rem; color: #64748b;">{{ __('Format:') }} {{ strtoupper(str_replace('_', ' + ', $news->type)) }}</p>
                </div>
            </div>
            <button type="button" onclick="$('#edit-site-news-modal-{{ $news->id }}').fadeOut(200);" style="background: none; border: none; font-size: 1.5rem; color: #64748b; cursor: pointer;">&times;</button>
        </div>

        <form action="{{ route('staff.site-news.update', $news->id) }}" method="POST" enctype="multipart/form-data" style="overflow-y: auto; padding-right: 6px; flex-grow: 1;">
            @csrf

            <!-- Title Field -->
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">
                    {{ __('Post Title / Headline') }}
                </label>
                <input type="text" name="title" value="{{ $news->title }}" placeholder="{{ __('Headline...') }}" style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.88rem; color: #0f172a; outline: none;">
            </div>

            <!-- Body Field -->
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">
                    {{ __('Post Content / Message Body') }}
                </label>
                <textarea name="body" rows="4" placeholder="{{ __('Content...') }}" style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.88rem; color: #0f172a; outline: none; line-height: 1.45;">{{ $news->body }}</textarea>
            </div>

            <!-- Photo Upload Field if photo type -->
            @if(in_array($news->type, ['photo', 'text_photo']))
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">
                    {{ __('Replace Photo (Optional)') }}
                </label>
                @if($news->image_path)
                    <div style="margin-bottom: 8px;">
                        <img src="{{ asset($news->image_path) }}" alt="Current photo" style="max-height: 80px; border-radius: 8px; border: 1px solid #cbd5e1;">
                    </div>
                @endif
                <input type="file" name="image" accept="image/*" style="width: 100%; padding: 8px 12px; border-radius: 10px; border: 1px dashed #cbd5e1; background: #f8fafc; font-size: 0.82rem;">
            </div>
            @endif

            <!-- Video Field if video type -->
            @if(in_array($news->type, ['video', 'text_video']))
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">
                    {{ __('Update Video Source (Optional)') }}
                </label>
                <div style="margin-bottom: 8px;">
                    <input type="url" name="video_url" value="{{ $news->video_url }}" placeholder="External Video URL (YouTube, Vimeo, etc.)" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.85rem; margin-bottom: 8px;">
                    <span style="font-size: 0.74rem; color: #64748b; display: block; margin-bottom: 6px;">{{ __('Or replace video file:') }}</span>
                    <input type="file" name="video_file" accept="video/mp4,video/webm,video/quicktime" style="width: 100%; padding: 8px 12px; border-radius: 10px; border: 1px dashed #cbd5e1; background: #f8fafc; font-size: 0.82rem;">
                </div>
            </div>
            @endif

            <!-- Priority & Expiry -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 18px;">
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">
                        {{ __('Display Priority') }}
                    </label>
                    <input type="number" name="priority" value="{{ $news->priority }}" min="0" max="999" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; outline: none;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">
                        {{ __('Expiration (Optional)') }}
                    </label>
                    <input type="datetime-local" name="expires_at" value="{{ $news->expires_at ? $news->expires_at->format('Y-m-d\TH:i') : '' }}" style="width: 100%; padding: 9px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.86rem; outline: none;">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                <button type="button" onclick="$('#edit-site-news-modal-{{ $news->id }}').fadeOut(200);" style="padding: 10px 20px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; font-weight: 700; font-size: 0.86rem; cursor: pointer;">
                    {{ __('Cancel') }}
                </button>
                <button type="submit" style="padding: 10px 24px; border-radius: 10px; border: none; background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); color: #ffffff; font-weight: 800; font-size: 0.88rem; cursor: pointer; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);">
                    {{ __('Save Changes') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endforeach

<script>
    function updateNewsFormFields(prefix, type) {
        // Update visual radio cards
        document.querySelectorAll('.news-type-card').forEach(card => {
            const input = card.querySelector('input');
            if (input && input.value === type) {
                card.style.borderColor = '#f59e0b';
                card.style.background = '#fffbeb';
            } else {
                card.style.borderColor = '#e2e8f0';
                card.style.background = '#f8fafc';
            }
        });

        const photoGroup = document.getElementById(prefix + '_photo_group');
        const videoGroup = document.getElementById(prefix + '_video_group');
        const titleReq = document.getElementById(prefix + '_title_req');
        const bodyReq = document.getElementById(prefix + '_body_req');
        const titleInput = document.getElementById(prefix + '_title');
        const bodyInput = document.getElementById(prefix + '_body');

        if (type === 'text') {
            if (photoGroup) photoGroup.style.display = 'none';
            if (videoGroup) videoGroup.style.display = 'none';
            if (titleReq) titleReq.style.display = 'inline';
            if (bodyReq) bodyReq.style.display = 'inline';
            if (titleInput) titleInput.required = true;
            if (bodyInput) bodyInput.required = true;
        } else if (type === 'photo') {
            if (photoGroup) photoGroup.style.display = 'block';
            if (videoGroup) videoGroup.style.display = 'none';
            if (titleReq) titleReq.style.display = 'none';
            if (bodyReq) bodyReq.style.display = 'none';
            if (titleInput) titleInput.required = false;
            if (bodyInput) bodyInput.required = false;
        } else if (type === 'video') {
            if (photoGroup) photoGroup.style.display = 'none';
            if (videoGroup) videoGroup.style.display = 'block';
            if (titleReq) titleReq.style.display = 'none';
            if (bodyReq) bodyReq.style.display = 'none';
            if (titleInput) titleInput.required = false;
            if (bodyInput) bodyInput.required = false;
        } else if (type === 'text_photo') {
            if (photoGroup) photoGroup.style.display = 'block';
            if (videoGroup) videoGroup.style.display = 'none';
            if (titleReq) titleReq.style.display = 'inline';
            if (bodyReq) bodyReq.style.display = 'inline';
            if (titleInput) titleInput.required = true;
            if (bodyInput) bodyInput.required = true;
        } else if (type === 'text_video') {
            if (photoGroup) photoGroup.style.display = 'none';
            if (videoGroup) videoGroup.style.display = 'block';
            if (titleReq) titleReq.style.display = 'inline';
            if (bodyReq) bodyReq.style.display = 'inline';
            if (titleInput) titleInput.required = true;
            if (bodyInput) bodyInput.required = true;
        }
    }

    function toggleCreateVideoSource(source) {
        const fileInput = document.getElementById('create_video_file_input');
        const linkInput = document.getElementById('create_video_link_input');
        if (source === 'file') {
            if (fileInput) fileInput.style.display = 'block';
            if (linkInput) linkInput.style.display = 'none';
        } else {
            if (fileInput) fileInput.style.display = 'none';
            if (linkInput) linkInput.style.display = 'block';
        }
    }
</script>
