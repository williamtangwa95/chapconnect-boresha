@extends('layouts.app')

@section('title', __('ChapConnect Hub Content Management'))

@section('styles')
<style>
    main.main,
    .main {
        display: block !important;
        width: 100% !important;
        max-width: 1300px !important;
        margin: 0 auto !important;
        padding: 20px 16px 80px 16px !important;
        box-sizing: border-box !important;
    }
</style>
@endsection

@section('content')
<main class="main">

    <!-- Top Executive Banner -->
    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-radius: 20px; padding: 26px 28px; color: #ffffff; margin-bottom: 28px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 1px solid rgba(255,255,255,0.08);">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(99,102,241,0.25); border: 1px solid rgba(165,180,252,0.3); padding: 4px 12px; border-radius: 20px; font-size: 0.74rem; font-weight: 800; color: #c7d2fe; margin-bottom: 8px;">
                <i class="bi bi-shield-check"></i> {{ __('Staff & Admin Hub Portal') }}
            </div>
            <h1 style="font-size: 1.5rem; font-weight: 900; margin: 0 0 4px 0; color: #ffffff;">
                {{ __('Manage News, Tutorials, Announcements & Testimonies') }}
            </h1>
            <p style="font-size: 0.85rem; color: #94a3b8; margin: 0;">
                {{ __('Publish audios, videos, photo showcases, and learning materials to educate and inform ChapConnect users.') }}
            </p>
        </div>

        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="{{ route('hub') }}" target="_blank" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #ffffff; padding: 9px 18px; border-radius: 10px; font-weight: 700; font-size: 0.84rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="bi bi-eye"></i> {{ __('View Live Public Hub') }}
            </a>
            <button type="button" onclick="$('#createPostCard').slideToggle(200);" style="background: #6366f1; color: #ffffff; border: none; padding: 9px 20px; border-radius: 10px; font-weight: 800; font-size: 0.84rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(99,102,241,0.3);">
                <i class="bi bi-plus-circle-fill"></i> {{ __('Create New Hub Post') }}
            </button>
        </div>
    </div>

    <!-- Stats Quick Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 28px;">
        <div style="background: #ffffff; border-radius: 14px; padding: 18px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
            <div style="font-size: 0.76rem; font-weight: 700; color: #64748b; margin-bottom: 4px;">{{ __('Total Posts') }}</div>
            <div style="font-size: 1.6rem; font-weight: 900; color: #0f172a;">{{ $stats['total'] }}</div>
        </div>
        <div style="background: #ffffff; border-radius: 14px; padding: 18px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
            <div style="font-size: 0.76rem; font-weight: 700; color: #4338ca; margin-bottom: 4px;"><i class="bi bi-mortarboard-fill"></i> {{ __('Tutorials') }}</div>
            <div style="font-size: 1.6rem; font-weight: 900; color: #4338ca;">{{ $stats['tutorials'] }}</div>
        </div>
        <div style="background: #ffffff; border-radius: 14px; padding: 18px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
            <div style="font-size: 0.76rem; font-weight: 700; color: #15803d; margin-bottom: 4px;"><i class="bi bi-newspaper"></i> {{ __('News') }}</div>
            <div style="font-size: 1.6rem; font-weight: 900; color: #15803d;">{{ $stats['news'] }}</div>
        </div>
        <div style="background: #ffffff; border-radius: 14px; padding: 18px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
            <div style="font-size: 0.76rem; font-weight: 700; color: #b45309; margin-bottom: 4px;"><i class="bi bi-megaphone-fill"></i> {{ __('Announcements') }}</div>
            <div style="font-size: 1.6rem; font-weight: 900; color: #b45309;">{{ $stats['announcements'] }}</div>
        </div>
        <div style="background: #ffffff; border-radius: 14px; padding: 18px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
            <div style="font-size: 0.76rem; font-weight: 700; color: #be185d; margin-bottom: 4px;"><i class="bi bi-trophy-fill"></i> {{ __('Testimonies') }}</div>
            <div style="font-size: 1.6rem; font-weight: 900; color: #be185d;">{{ $stats['testimonies'] }}</div>
        </div>
    </div>

    @if(session('success'))
    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 20px; border-radius: 12px; font-weight: 700; font-size: 0.88rem; margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 14px 20px; border-radius: 12px; font-size: 0.86rem; margin-bottom: 24px;">
        <strong style="display: block; margin-bottom: 6px;">{{ __('Please resolve the following errors:') }}</strong>
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- ====================================================
         CREATION FORM (COLLAPSIBLE)
         ==================================================== -->
    <div id="createPostCard" style="background: #ffffff; border-radius: 18px; border: 1px solid #e2e8f0; padding: 28px; box-shadow: 0 6px 24px rgba(0,0,0,0.04); margin-bottom: 32px; {{ $errors->any() ? '' : 'display: none;' }}">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 14px;">
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-pencil-square" style="color: #6366f1;"></i> {{ __('Publish New Post to ChapConnect Hub') }}
            </h3>
            <button type="button" onclick="$('#createPostCard').slideUp(200);" style="background: none; border: none; font-size: 1.2rem; color: #94a3b8; cursor: pointer;">✕</button>
        </div>

        <form action="{{ route('staff.hub.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; margin-bottom: 18px;">
                <!-- Post Title -->
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">{{ __('Post Title') }} *</label>
                    <input type="text" name="title" required value="{{ old('title') }}" placeholder="{{ __('e.g. How to get booked faster on ChapConnect') }}" style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
                </div>

                <!-- Category -->
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">{{ __('Category') }} *</label>
                    <select name="category" required style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
                        <option value="tutorial" {{ old('category') === 'tutorial' ? 'selected' : '' }}>🎓 {{ __('Tutorials & Learning Guides') }}</option>
                        <option value="news" {{ old('category') === 'news' ? 'selected' : '' }}>📰 {{ __('Platform News & Bulletins') }}</option>
                        <option value="announcement" {{ old('category') === 'announcement' ? 'selected' : '' }}>📢 {{ __('Official Announcements') }}</option>
                        <option value="testimony" {{ old('category') === 'testimony' ? 'selected' : '' }}>🏆 {{ __('Success Testimonies & Spotlights') }}</option>
                    </select>
                </div>

                <!-- Primary Media Type -->
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">{{ __('Media Format') }} *</label>
                    <select name="media_type" required style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
                        <option value="article" {{ old('media_type') === 'article' ? 'selected' : '' }}>📝 {{ __('Written Guide / Article') }}</option>
                        <option value="audio" {{ old('media_type') === 'audio' ? 'selected' : '' }}>🎵 {{ __('Audio Guide / Podcast (MP3/WAV)') }}</option>
                        <option value="video" {{ old('media_type') === 'video' ? 'selected' : '' }}>🎬 {{ __('Video (Upload MP4 or External Link)') }}</option>
                        <option value="photo" {{ old('media_type') === 'photo' ? 'selected' : '' }}>📸 {{ __('Photo Showcase / Poster') }}</option>
                    </select>
                </div>
            </div>

            <!-- Media Upload Attachments Grid -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; margin-bottom: 18px;">
                <h4 style="font-size: 0.92rem; font-weight: 800; color: #0f172a; margin: 0 0 14px 0;">
                    <i class="bi bi-paperclip" style="color: #6366f1;"></i> {{ __('Media Attachments & Links (Optional/Recommended)') }}
                </h4>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                    <!-- Featured Image / Poster -->
                    <div>
                        <label style="display: block; font-weight: 700; font-size: 0.8rem; color: #334155; margin-bottom: 4px;">{{ __('Cover / Featured Image') }}</label>
                        <input type="file" name="featured_image" accept="image/*" style="width: 100%; padding: 6px; border: 1px dashed #cbd5e1; border-radius: 6px; background: #ffffff; font-size: 0.8rem;">
                        <small style="color: #64748b; font-size: 0.72rem; display: block; margin-top: 3px;">JPEG, PNG, WEBP (Max 50MB)</small>
                    </div>

                    <!-- Audio File -->
                    <div>
                        <label style="display: block; font-weight: 700; font-size: 0.8rem; color: #334155; margin-bottom: 4px;">{{ __('Audio File (Voice Guide / Podcast)') }}</label>
                        <input type="file" name="audio_file" accept="audio/*" style="width: 100%; padding: 6px; border: 1px dashed #cbd5e1; border-radius: 6px; background: #ffffff; font-size: 0.8rem;">
                        <small style="color: #64748b; font-size: 0.72rem; display: block; margin-top: 3px;">MP3, WAV, M4A, OGG, AAC (Max 100MB)</small>
                    </div>

                    <!-- Direct Video File -->
                    <div>
                        <label style="display: block; font-weight: 700; font-size: 0.8rem; color: #334155; margin-bottom: 4px;">{{ __('Or Direct MP4 Video File') }}</label>
                        <input type="file" name="video_file" accept="video/mp4,video/quicktime,video/webm" style="width: 100%; padding: 6px; border: 1px dashed #cbd5e1; border-radius: 6px; background: #ffffff; font-size: 0.8rem;">
                        <small style="color: #64748b; font-size: 0.72rem; display: block; margin-top: 3px;">MP4, WEBM, MOV (Max 100MB)</small>
                    </div>

                    <!-- External Video Link -->
                    <div>
                        <label style="display: block; font-weight: 700; font-size: 0.8rem; color: #334155; margin-bottom: 4px;">{{ __('Or External Video Link (YouTube / TikTok)') }}</label>
                        <input type="url" name="video_url" value="{{ old('video_url') }}" placeholder="https://youtube.com/watch?v=..." style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.8rem; box-sizing: border-box;">
                    </div>
                </div>
            </div>

            <!-- Short Summary -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">{{ __('Short Excerpt / Summary') }} ({{ __('Optional') }})</label>
                <input type="text" name="summary" value="{{ old('summary') }}" placeholder="{{ __('Brief 1-2 sentence overview shown in preview cards...') }}" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
            </div>

            <!-- Detailed Content Body -->
            <div style="margin-bottom: 18px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">{{ __('Full Guide / Article Content') }} *</label>
                <textarea name="content" rows="7" placeholder="{{ __('Write full explanation, tutorial steps, announcement text, or testimony details here...') }}" style="width: 100%; padding: 12px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; font-family: inherit; line-height: 1.5; box-sizing: border-box;">{{ old('content') }}</textarea>
            </div>

            <!-- Toggles: Pin and Publish -->
            <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 22px; flex-wrap: wrap;">
                <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.86rem; font-weight: 700; color: #334155; cursor: pointer;">
                    <input type="checkbox" name="is_pinned" value="1" {{ old('is_pinned') ? 'checked' : '' }} style="width: 16px; height: 16px;">
                    <i class="bi bi-pin-angle-fill" style="color: #ef4444;"></i> {{ __('Pin as Featured Story at top of Hub') }}
                </label>

                <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.86rem; font-weight: 700; color: #334155; cursor: pointer;">
                    <input type="checkbox" name="is_published" value="1" checked style="width: 16px; height: 16px;">
                    <i class="bi bi-globe" style="color: #16a34a;"></i> {{ __('Publish immediately on Live Hub') }}
                </label>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="$('#createPostCard').slideUp(200);" style="background: #e2e8f0; color: #475569; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 700; font-size: 0.86rem; cursor: pointer;">
                    {{ __('Cancel') }}
                </button>
                <button type="submit" style="background: #6366f1; color: #ffffff; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 800; font-size: 0.88rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="bi bi-send-fill"></i> {{ __('Publish Post') }}
                </button>
            </div>
        </form>
    </div>

    <!-- ====================================================
         POSTS MANAGEMENT TABLE / LIST
         ==================================================== -->
    <div style="background: #ffffff; border-radius: 18px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
        <div style="padding: 20px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #0f172a;">
                {{ __('All Published & Draft Posts') }} ({{ $posts->total() }})
            </h3>

            <!-- Category Filter Tabs -->
            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                <a href="{{ route('staff.hub.index') }}" style="padding: 6px 12px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; text-decoration: none; {{ $category === 'all' ? 'background: #0f172a; color: #fff;' : 'background: #f1f5f9; color: #475569;' }}">{{ __('All') }}</a>
                <a href="{{ route('staff.hub.index', ['category' => 'tutorial']) }}" style="padding: 6px 12px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; text-decoration: none; {{ $category === 'tutorial' ? 'background: #4338ca; color: #fff;' : 'background: #f1f5f9; color: #475569;' }}">{{ __('Tutorials') }}</a>
                <a href="{{ route('staff.hub.index', ['category' => 'news']) }}" style="padding: 6px 12px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; text-decoration: none; {{ $category === 'news' ? 'background: #15803d; color: #fff;' : 'background: #f1f5f9; color: #475569;' }}">{{ __('News') }}</a>
                <a href="{{ route('staff.hub.index', ['category' => 'announcement']) }}" style="padding: 6px 12px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; text-decoration: none; {{ $category === 'announcement' ? 'background: #b45309; color: #fff;' : 'background: #f1f5f9; color: #475569;' }}">{{ __('Announcements') }}</a>
                <a href="{{ route('staff.hub.index', ['category' => 'testimony']) }}" style="padding: 6px 12px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; text-decoration: none; {{ $category === 'testimony' ? 'background: #be185d; color: #fff;' : 'background: #f1f5f9; color: #475569;' }}">{{ __('Testimonies') }}</a>
            </div>
        </div>

        @if($posts->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.86rem;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 0.78rem; font-weight: 800; text-transform: uppercase;">
                        <th style="padding: 12px 18px;">{{ __('Post') }}</th>
                        <th style="padding: 12px 14px;">{{ __('Category') }}</th>
                        <th style="padding: 12px 14px;">{{ __('Media') }}</th>
                        <th style="padding: 12px 14px;">{{ __('Views') }}</th>
                        <th style="padding: 12px 14px;">{{ __('Status') }}</th>
                        <th style="padding: 12px 14px;">{{ __('Date') }}</th>
                        <th style="padding: 12px 18px; text-align: right;">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $p)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px 18px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                @if($p->featured_image)
                                <img src="{{ asset($p->featured_image) }}" alt="" style="width: 44px; height: 44px; border-radius: 8px; object-fit: cover;">
                                @else
                                <div style="width: 44px; height: 44px; border-radius: 8px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; color: #64748b;">
                                    <i class="bi {{ $p->category_details['icon'] }}"></i>
                                </div>
                                @endif
                                <div>
                                    <strong style="color: #0f172a; display: block; font-size: 0.9rem;">
                                        @if($p->is_pinned)
                                        <i class="bi bi-pin-angle-fill" style="color: #ef4444;" title="Pinned"></i>
                                        @endif
                                        {{ $p->title }}
                                    </strong>
                                    <small style="color: #94a3b8;">{{ $p->author ? $p->author->name : 'Staff' }}</small>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 14px 14px;">
                            <span style="background: {{ $p->category_details['bg'] }}; color: {{ $p->category_details['color'] }}; border: 1px solid {{ $p->category_details['border'] }}; font-size: 0.74rem; font-weight: 800; padding: 2px 8px; border-radius: 8px;">
                                {{ $p->category_details['short_label'] }}
                            </span>
                        </td>
                        <td style="padding: 14px 14px;">
                            <span style="font-size: 0.78rem; font-weight: 700; color: #475569; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="bi {{ $p->media_type_details['icon'] }}" style="color: {{ $p->media_type_details['color'] }};"></i> {{ $p->media_type_details['label'] }}
                            </span>
                        </td>
                        <td style="padding: 14px 14px; font-weight: 700; color: #334155;">
                            {{ $p->views_count }}
                        </td>
                        <td style="padding: 14px 14px;">
                            @if($p->is_published)
                            <span style="background: #dcfce7; color: #15803d; font-size: 0.74rem; font-weight: 800; padding: 2px 8px; border-radius: 8px;">{{ __('Published') }}</span>
                            @else
                            <span style="background: #f1f5f9; color: #64748b; font-size: 0.74rem; font-weight: 800; padding: 2px 8px; border-radius: 8px;">{{ __('Draft') }}</span>
                            @endif
                        </td>
                        <td style="padding: 14px 14px; color: #94a3b8; font-size: 0.8rem;">
                            {{ $p->created_at->format('d M Y') }}
                        </td>
                        <td style="padding: 14px 18px; text-align: right;">
                            <div style="display: inline-flex; gap: 6px; align-items: center;">
                                <a href="{{ route('hub.show', $p->slug) }}" target="_blank" style="background: #f1f5f9; color: #475569; padding: 5px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 700; text-decoration: none;" title="Preview">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>

                                <form action="{{ route('staff.hub.toggle-pin', $p->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" style="background: {{ $p->is_pinned ? '#fee2e2' : '#f1f5f9' }}; color: {{ $p->is_pinned ? '#b91c1c' : '#475569' }}; border: none; padding: 5px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 700; cursor: pointer;" title="Pin to top">
                                        <i class="bi bi-pin-angle"></i>
                                    </button>
                                </form>

                                <form action="{{ route('staff.hub.toggle-publish', $p->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" style="background: {{ $p->is_published ? '#fef3c7' : '#dcfce7' }}; color: {{ $p->is_published ? '#b45309' : '#15803d' }}; border: none; padding: 5px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 700; cursor: pointer;" title="Toggle Publish">
                                        <i class="bi {{ $p->is_published ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                    </button>
                                </form>

                                <form action="{{ route('staff.hub.destroy', $p->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to permanently delete this post?') }}')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: #fee2e2; color: #b91c1c; border: none; padding: 5px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 700; cursor: pointer;" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="padding: 16px 24px; border-top: 1px solid #e2e8f0; display: flex; justify-content: center;">
            {{ $posts->links() }}
        </div>
        @else
        <div style="padding: 50px 20px; text-align: center; color: #94a3b8;">
            <i class="bi bi-journal-plus" style="font-size: 3rem; color: #cbd5e1; display: block; margin-bottom: 10px;"></i>
            <h4 style="color: #475569; font-weight: 800; margin: 0 0 6px 0;">{{ __('No Hub Posts Created Yet') }}</h4>
            <p style="font-size: 0.88rem; margin: 0 0 16px 0;">{{ __('Click the button above to publish the first tutorial, announcement, or news post.') }}</p>
        </div>
        @endif
    </div>

</main>
@endsection
