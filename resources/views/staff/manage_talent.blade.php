@extends('layouts.app')

@section('title', 'ChapConnect - Manage Talent: ' . $talent->name)

@section('content')
<main class="main" style="padding: 30px 20px; max-width: 1240px; margin: 0 auto;">
    
    <!-- Top Header & Breadcrumb -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 25px;">
        <div>
            <div style="font-size: 0.82rem; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                <i class="bi bi-shield-check" style="color: #6366f1;"></i> STAFF TALENT MANAGEMENT
            </div>
            <h1 style="font-size: 1.8rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 10px;">
                Managing Portfolio: {{ $talent->name }}
            </h1>
        </div>
        <div style="display: flex; gap: 10px;">
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}#talents" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 30px; color: #334155; font-weight: 700; text-decoration: none; font-size: 0.88rem; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <i class="bi bi-arrow-left"></i> Super Admin Panel
            </a>
            @else
            <a href="{{ route('customer-care.dashboard') }}#talents" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 30px; color: #334155; font-weight: 700; text-decoration: none; font-size: 0.88rem; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <i class="bi bi-arrow-left"></i> Customer Care Portal
            </a>
            @endif
            <a href="{{ route('profile', $talent->id) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background: linear-gradient(135deg, #6366f1 0%, #38bdf8 100%); color: #ffffff; border-radius: 30px; font-weight: 800; text-decoration: none; font-size: 0.88rem; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);">
                <i class="bi bi-person-badge-fill"></i> View Public Profile <i class="bi bi-box-arrow-up-right" style="font-size: 0.75rem;"></i>
            </a>
        </div>
    </div>

    <!-- Talent Summary Header Card -->
    <div style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border-radius: 20px; padding: 25px; color: #ffffff; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div style="width: 85px; height: 85px; border-radius: 50%; overflow: hidden; border: 3px solid rgba(255,255,255,0.2); box-shadow: 0 8px 20px rgba(0,0,0,0.4); flex-shrink: 0; background: #334155; display: flex; align-items: center; justify-content: center;">
                @if($talent->profile_image)
                <img src="{{ asset($talent->profile_image) }}" alt="{{ $talent->name }}" style="width: 100%; height: 100%; object-fit: cover; object-position: top center;">
                @else
                <i class="bi bi-person-circle" style="font-size: 3rem; color: #94a3b8;"></i>
                @endif
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                    <h2 style="margin: 0; font-size: 1.4rem; font-weight: 800; color: #ffffff;">{{ $talent->name }}</h2>
                    <span style="background: rgba(99, 102, 241, 0.3); border: 1px solid rgba(99, 102, 241, 0.5); color: #818cf8; padding: 2px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 800;">
                        {{ $talent->category_label }}
                    </span>
                </div>
                <div style="font-size: 0.88rem; color: #cbd5e1; display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                    <span><i class="bi bi-envelope-fill" style="color: #38bdf8;"></i> {{ $talent->email ?: 'No email' }}</span>
                    <span><i class="bi bi-telephone-fill" style="color: #34d399;"></i> {{ $talent->phone ?: 'No phone' }}</span>
                    <span><i class="bi bi-geo-alt-fill" style="color: #fbbf24;"></i> {{ $talent->country }}</span>
                </div>
            </div>
        </div>
        
        <!-- Media Quick Counts -->
        <div style="display: flex; gap: 15px;">
            <div style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); padding: 12px 20px; border-radius: 14px; text-align: center;">
                <div style="font-size: 1.3rem; font-weight: 900; color: #38bdf8;">{{ count($photos) }}</div>
                <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Photos</div>
            </div>
            <div style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); padding: 12px 20px; border-radius: 14px; text-align: center;">
                <div style="font-size: 1.3rem; font-weight: 900; color: #818cf8;">{{ count($videos) }}</div>
                <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Videos</div>
            </div>
            <div style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); padding: 12px 20px; border-radius: 14px; text-align: center;">
                <div style="font-size: 1.3rem; font-weight: 900; color: #34d399;">{{ count($newsItems) }}</div>
                <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 700; text-transform: uppercase;">News</div>
            </div>
        </div>
    </div>

    <!-- Management Tab Navigation Header -->
    <div style="display: flex; gap: 10px; border-bottom: 2px solid #e2e8f0; margin-bottom: 25px; overflow-x: auto; padding-bottom: 2px;">
        <button type="button" class="staff-tab-btn active" data-tab="profile" onclick="switchStaffTab('profile')" style="padding: 12px 22px; font-weight: 800; font-size: 0.92rem; border: none; background: none; color: #6366f1; border-bottom: 3px solid #6366f1; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
            <i class="bi bi-person-gear"></i> Profile Information
        </button>
        <button type="button" class="staff-tab-btn" data-tab="photos" onclick="switchStaffTab('photos')" style="padding: 12px 22px; font-weight: 700; font-size: 0.92rem; border: none; background: none; color: #64748b; border-bottom: 3px solid transparent; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
            <i class="bi bi-camera-fill"></i> Manage Photos ({{ count($photos) }})
        </button>
        <button type="button" class="staff-tab-btn" data-tab="videos" onclick="switchStaffTab('videos')" style="padding: 12px 22px; font-weight: 700; font-size: 0.92rem; border: none; background: none; color: #64748b; border-bottom: 3px solid transparent; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
            <i class="bi bi-camera-video-fill"></i> Manage Videos ({{ count($videos) }})
        </button>
        <button type="button" class="staff-tab-btn" data-tab="news" onclick="switchStaffTab('news')" style="padding: 12px 22px; font-weight: 700; font-size: 0.92rem; border: none; background: none; color: #64748b; border-bottom: 3px solid transparent; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
            <i class="bi bi-newspaper"></i> Manage News ({{ count($newsItems) }})
        </button>
    </div>

    <!-- TAB 1: Profile Information Form -->
    <div id="staff-tab-profile" class="staff-tab-content" style="display: block;">
        <div style="background: #ffffff; border-radius: 16px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #e2e8f0;">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-person-lines-fill" style="color: #6366f1;"></i> Edit Talent Profile & Social Links
            </h3>

            <form action="{{ route('staff.talent.update-profile', $talent->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 6px;">Full Name / Stage Name *</label>
                        <input type="text" name="name" value="{{ old('name', $talent->name) }}" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.9rem;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 6px;">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email', $talent->email) }}" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.9rem;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 6px;">Phone Number (Tanzania)</label>
                        <input type="text" name="phone" value="{{ old('phone', $talent->phone) }}" placeholder="06XXXXXXXX / +255..." style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.9rem;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 6px;">Talent Category *</label>
                        <select name="category" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.9rem; background: #fff;">
                            @foreach($categories as $slug => $label)
                            <option value="{{ $slug }}" {{ $talent->category === $slug ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 6px;">Country / Region</label>
                        <input type="text" name="country" value="{{ old('country', $talent->country) }}" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.9rem;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 6px;">New Password (Optional)</label>
                        <input type="password" name="password" placeholder="Leave empty to keep current password" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.9rem;">
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 6px;">Biography / Description</label>
                    <textarea name="description" rows="4" style="width: 100%; padding: 12px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.9rem; font-family: inherit; box-sizing: border-box;" placeholder="Enter talent biography, skill details, performance history...">{{ old('description', $talent->description) }}</textarea>
                </div>

                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 6px;">Profile Avatar Picture</label>
                    <input type="file" name="profile_image" accept="image/*" style="width: 100%; padding: 10px; border: 1px dashed #cbd5e1; border-radius: 10px; background: #f8fafc;">
                    <small style="color: #64748b; font-size: 0.78rem;">Upload JPEG, PNG, WEBP image file (max 10MB). Image is automatically compressed.</small>
                </div>

                <h4 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin: 25px 0 15px 0; border-top: 1px solid #e2e8f0; padding-top: 20px;">
                    Social Media Profiles
                </h4>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-bottom: 25px;">
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.82rem; margin-bottom: 4px;"><i class="bi bi-instagram" style="color: #e1306c;"></i> Instagram URL</label>
                        <input type="url" name="social_instagram" value="{{ old('social_instagram', $talent->social_instagram) }}" placeholder="https://instagram.com/username" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.85rem;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.82rem; margin-bottom: 4px;"><i class="bi bi-facebook" style="color: #1877f2;"></i> Facebook URL</label>
                        <input type="url" name="social_facebook" value="{{ old('social_facebook', $talent->social_facebook) }}" placeholder="https://facebook.com/username" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.85rem;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.82rem; margin-bottom: 4px;"><i class="bi bi-tiktok" style="color: #000000;"></i> TikTok URL</label>
                        <input type="url" name="social_tiktok" value="{{ old('social_tiktok', $talent->social_tiktok) }}" placeholder="https://tiktok.com/@username" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.85rem;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.82rem; margin-bottom: 4px;"><i class="bi bi-youtube" style="color: #ff0000;"></i> YouTube Channel URL</label>
                        <input type="url" name="social_youtube" value="{{ old('social_youtube', $talent->social_youtube) }}" placeholder="https://youtube.com/channel" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.85rem;">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" style="background: linear-gradient(135deg, #6366f1 0%, #38bdf8 100%); color: #ffffff; border: none; padding: 12px 30px; border-radius: 30px; font-weight: 800; font-size: 0.95rem; cursor: pointer; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);">
                        <i class="bi bi-check-lg"></i> Update Talent Profile
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 2: Manage Photos -->
    <div id="staff-tab-photos" class="staff-tab-content" style="display: none;">
        <!-- Upload Photos Card -->
        <div style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; margin-bottom: 30px;">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-cloud-upload-fill" style="color: #6366f1;"></i> Upload New Portfolio Photo(s)
            </h3>
            
            <form action="{{ route('staff.talent.photos.store', $talent->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-bottom: 15px;">
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 4px;">Photo Title / Label (Optional)</label>
                        <input type="text" name="title" placeholder="e.g. Stage Performance Photo" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 4px;">Select Image File(s) *</label>
                        <input type="file" name="photos[]" multiple accept="image/*" required style="width: 100%; padding: 8px; border: 1px dashed #cbd5e1; border-radius: 8px; background: #f8fafc; font-size: 0.85rem;">
                    </div>
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 4px;">Caption / Details (Optional)</label>
                    <input type="text" name="caption" placeholder="Short description for the photo..." style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem;">
                </div>
                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" style="background: #6366f1; color: #ffffff; border: none; padding: 10px 24px; border-radius: 25px; font-weight: 800; font-size: 0.88rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="bi bi-upload"></i> Upload Photos Now
                    </button>
                </div>
            </form>
        </div>

        <!-- Photos Gallery List -->
        <h4 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-bottom: 15px;">Existing Portfolio Photos ({{ count($photos) }})</h4>
        @if(count($photos) > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px;">
            @foreach($photos as $photo)
            <div style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.03); display: flex; flex-direction: column;">
                <div style="width: 100%; height: 180px; position: relative; background: #1e293b;">
                    <img src="{{ asset($photo->file_path) }}" alt="{{ $photo->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div style="padding: 12px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <strong style="display: block; font-size: 0.88rem; color: #0f172a; margin-bottom: 4px;">{{ $photo->title ?: 'Untitled Photo' }}</strong>
                        <p style="font-size: 0.78rem; color: #64748b; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $photo->content ?: 'No caption' }}</p>
                    </div>
                    <div style="margin-top: 10px; display: flex; justify-content: flex-end;">
                        <form action="{{ route('staff.talent.photos.delete', [$talent->id, $photo->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this photo?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 5px 12px; border-radius: 8px; font-size: 0.76rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align: center; padding: 40px; background: #ffffff; border-radius: 16px; border: 1px dashed #cbd5e1; color: #94a3b8;">
            <i class="bi bi-images" style="font-size: 2.5rem; display: block; margin-bottom: 8px;"></i>
            No portfolio photos uploaded for this talent yet.
        </div>
        @endif
    </div>

    <!-- TAB 3: Manage Videos -->
    <div id="staff-tab-videos" class="staff-tab-content" style="display: none;">
        <!-- Upload/Add Video Card -->
        <div style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; margin-bottom: 30px;">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-camera-video-fill" style="color: #6366f1;"></i> Add Video Link or Upload Video File
            </h3>

            <form action="{{ route('staff.talent.videos.store', $talent->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-bottom: 15px;">
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 4px;">Video Title</label>
                        <input type="text" name="title" placeholder="e.g. Official Music Video / Showreel" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 4px;">Option A: Video URL Link (TikTok, YouTube, Vimeo, IG, FB)</label>
                        <input type="url" name="video_url" placeholder="https://youtube.com/watch?v=... or TikTok link" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem;">
                    </div>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 4px;">Option B: Upload Direct MP4 Video File</label>
                    <input type="file" name="video" accept="video/mp4,video/quicktime,video/webm" style="width: 100%; padding: 8px; border: 1px dashed #cbd5e1; border-radius: 8px; background: #f8fafc; font-size: 0.85rem;">
                    <small style="color: #64748b; font-size: 0.78rem;">Upload MP4, MOV, WEBM clip (max 50MB).</small>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 4px;">Caption / Summary</label>
                    <input type="text" name="caption" placeholder="Short caption for video clip..." style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem;">
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" style="background: #6366f1; color: #ffffff; border: none; padding: 10px 24px; border-radius: 25px; font-weight: 800; font-size: 0.88rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="bi bi-plus-circle-fill"></i> Add Video Now
                    </button>
                </div>
            </form>
        </div>

        <!-- Videos List -->
        <h4 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-bottom: 15px;">Existing Portfolio Videos ({{ count($videos) }})</h4>
        @if(count($videos) > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
            @foreach($videos as $video)
            <div style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.03); display: flex; flex-direction: column;">
                <div style="width: 100%; height: 180px; background: #0f172a; display: flex; align-items: center; justify-content: center; position: relative;">
                    @if(str_starts_with($video->file_path, 'http'))
                    <a href="{{ $video->file_path }}" target="_blank" style="color: #ffffff; text-decoration: none; text-align: center; padding: 15px;">
                        <i class="bi bi-play-circle-fill" style="font-size: 3rem; color: #38bdf8; display: block; margin-bottom: 6px;"></i>
                        <span style="font-size: 0.8rem; font-weight: 700;">Open External Video Link</span>
                    </a>
                    @else
                    <video src="{{ asset($video->file_path) }}" controls style="width: 100%; height: 100%; object-fit: cover;"></video>
                    @endif
                </div>
                <div style="padding: 12px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <strong style="display: block; font-size: 0.9rem; color: #0f172a; margin-bottom: 4px;">{{ $video->title ?: 'Untitled Video' }}</strong>
                        <p style="font-size: 0.78rem; color: #64748b; margin: 0;">{{ $video->content ?: 'No details' }}</p>
                    </div>
                    <div style="margin-top: 12px; display: flex; justify-content: flex-end;">
                        <form action="{{ route('staff.talent.videos.delete', [$talent->id, $video->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this video?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 5px 12px; border-radius: 8px; font-size: 0.76rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align: center; padding: 40px; background: #ffffff; border-radius: 16px; border: 1px dashed #cbd5e1; color: #94a3b8;">
            <i class="bi bi-camera-video" style="font-size: 2.5rem; display: block; margin-bottom: 8px;"></i>
            No portfolio videos added for this talent yet.
        </div>
        @endif
    </div>

    <!-- TAB 4: Manage News -->
    <div id="staff-tab-news" class="staff-tab-content" style="display: none;">
        <!-- Publish News Card -->
        <div style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; margin-bottom: 30px;">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-newspaper" style="color: #6366f1;"></i> Publish News Bulletin / Update
            </h3>

            <form action="{{ route('staff.talent.news.store', $talent->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 4px;">Bulletin Title *</label>
                    <input type="text" name="title" required placeholder="e.g. Upcoming Album Launch / Live Tour Announcement" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 4px;">Bulletin Content / Full Text *</label>
                    <textarea name="content" rows="5" required placeholder="Write news announcement details..." style="width: 100%; padding: 12px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; font-family: inherit; box-sizing: border-box;"></textarea>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 4px;">Featured Poster / Image (Optional)</label>
                    <input type="file" name="image" accept="image/*" style="width: 100%; padding: 8px; border: 1px dashed #cbd5e1; border-radius: 8px; background: #f8fafc; font-size: 0.85rem;">
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" style="background: #6366f1; color: #ffffff; border: none; padding: 10px 24px; border-radius: 25px; font-weight: 800; font-size: 0.88rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="bi bi-send-fill"></i> Publish News Bulletin
                    </button>
                </div>
            </form>
        </div>

        <!-- News Bulletins List -->
        <h4 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-bottom: 15px;">Published News Bulletins ({{ count($newsItems) }})</h4>
        @if(count($newsItems) > 0)
        <div style="display: flex; flex-direction: column; gap: 16px;">
            @foreach($newsItems as $news)
            <div style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); display: flex; gap: 20px; align-items: flex-start; flex-wrap: wrap;">
                @if($news->file_path)
                <div style="width: 130px; height: 100px; border-radius: 10px; overflow: hidden; background: #1e293b; flex-shrink: 0;">
                    <img src="{{ asset($news->file_path) }}" alt="{{ $news->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                @endif
                <div style="flex: 1; min-width: 250px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px;">
                        <h4 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #0f172a;">{{ $news->title }}</h4>
                        <span style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">{{ $news->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <p style="font-size: 0.88rem; color: #475569; margin: 0 0 12px 0; line-height: 1.5; white-space: pre-line;">{{ $news->content }}</p>
                    <div style="display: flex; justify-content: flex-end;">
                        <form action="{{ route('staff.talent.news.delete', [$talent->id, $news->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this news article?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 5px 12px; border-radius: 8px; font-size: 0.76rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="bi bi-trash"></i> Delete Bulletin
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align: center; padding: 40px; background: #ffffff; border-radius: 16px; border: 1px dashed #cbd5e1; color: #94a3b8;">
            <i class="bi bi-newspaper" style="font-size: 2.5rem; display: block; margin-bottom: 8px;"></i>
            No news bulletins published for this talent yet.
        </div>
        @endif
    </div>

</main>

<script>
    function switchStaffTab(tabName) {
        document.querySelectorAll('.staff-tab-content').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.staff-tab-btn').forEach(btn => {
            btn.style.color = '#64748b';
            btn.style.borderBottomColor = 'transparent';
            btn.classList.remove('active');
        });

        const activeContent = document.getElementById('staff-tab-' + tabName);
        if (activeContent) activeContent.style.display = 'block';

        const activeBtn = document.querySelector(`.staff-tab-btn[data-tab="${tabName}"]`);
        if (activeBtn) {
            activeBtn.style.color = '#6366f1';
            activeBtn.style.borderBottomColor = '#6366f1';
            activeBtn.classList.add('active');
        }
    }
</script>
@endsection
