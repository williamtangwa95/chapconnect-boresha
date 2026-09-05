@extends('layouts.app')

@section('title', 'ChapConnect - Manage Talent: ' . $talent->name)

@section('content')
<main class="main-content-body" style="padding: 28px 24px; max-width: 1300px; margin: 0 auto; width: 100%; box-sizing: border-box;">
    
    <!-- Top Header & Actions -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 25px;">
        <div>
            <div style="font-size: 0.8rem; color: #6366f1; font-weight: 800; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 5px; display: flex; align-items: center; gap: 6px;">
                <i class="bi bi-shield-check" style="font-size: 1rem;"></i> STAFF TALENT MANAGEMENT
            </div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.5px;">
                Managing Portfolio: {{ $talent->name }}
            </h1>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}#talents" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 30px; color: #334155; font-weight: 700; text-decoration: none; font-size: 0.88rem; box-shadow: 0 2px 6px rgba(0,0,0,0.04); transition: all 0.2s ease;">
                <i class="bi bi-arrow-left"></i> Super Admin Panel
            </a>
            @else
            <a href="{{ route('customer-care.dashboard') }}#talents" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 30px; color: #334155; font-weight: 700; text-decoration: none; font-size: 0.88rem; box-shadow: 0 2px 6px rgba(0,0,0,0.04); transition: all 0.2s ease;">
                <i class="bi bi-arrow-left"></i> Customer Care Portal
            </a>
            @endif
            <a href="{{ route('profile', $talent->id) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; background: linear-gradient(135deg, #6366f1 0%, #38bdf8 100%); color: #ffffff; border-radius: 30px; font-weight: 800; text-decoration: none; font-size: 0.88rem; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35); transition: all 0.2s ease;">
                <i class="bi bi-person-badge-fill"></i> View Public Profile <i class="bi bi-box-arrow-up-right" style="font-size: 0.75rem;"></i>
            </a>
        </div>
    </div>

    <!-- Talent Summary Header Banner Card -->
    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-radius: 20px; padding: 26px 30px; color: #ffffff; margin-bottom: 28px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.2); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 24px; border: 1px solid rgba(255, 255, 255, 0.08);">
        <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
            <div style="width: 90px; height: 90px; border-radius: 50%; overflow: hidden; border: 3px solid rgba(255,255,255,0.2); box-shadow: 0 8px 24px rgba(0,0,0,0.4); flex-shrink: 0; background: #334155; display: flex; align-items: center; justify-content: center; position: relative;">
                @if($talent->profile_image)
                <img src="{{ asset($talent->profile_image) }}" alt="{{ $talent->name }}" style="width: 100%; height: 100%; object-fit: cover; object-position: top center;">
                @else
                <i class="bi bi-person-fill" style="font-size: 3.2rem; color: #94a3b8;"></i>
                @endif
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 6px; flex-wrap: wrap;">
                    <h2 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #ffffff; letter-spacing: -0.3px;">{{ $talent->name }}</h2>
                    <span style="background: rgba(99, 102, 241, 0.25); border: 1px solid rgba(129, 140, 248, 0.4); color: #a5b4fc; padding: 3px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">
                        {{ $talent->category_label }}
                    </span>
                </div>
                <div style="font-size: 0.88rem; color: #cbd5e1; display: flex; align-items: center; gap: 18px; flex-wrap: wrap;">
                    <span style="display: inline-flex; align-items: center; gap: 6px;"><i class="bi bi-envelope-fill" style="color: #38bdf8;"></i> {{ $talent->email ?: 'No email' }}</span>
                    <span style="display: inline-flex; align-items: center; gap: 6px;"><i class="bi bi-telephone-fill" style="color: #34d399;"></i> {{ $talent->phone ?: 'No phone' }}</span>
                    <span style="display: inline-flex; align-items: center; gap: 6px;"><i class="bi bi-geo-alt-fill" style="color: #fbbf24;"></i> {{ $talent->country }}</span>
                </div>
            </div>
        </div>
        
        <!-- Media Quick Counts -->
        <div style="display: flex; gap: 14px; flex-wrap: wrap;">
            <div style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12); padding: 12px 22px; border-radius: 16px; text-align: center; min-width: 90px;">
                <div style="font-size: 1.4rem; font-weight: 900; color: #38bdf8; line-height: 1.2;">{{ count($photos) }}</div>
                <div style="font-size: 0.74rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">Photos</div>
            </div>
            <div style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12); padding: 12px 22px; border-radius: 16px; text-align: center; min-width: 90px;">
                <div style="font-size: 1.4rem; font-weight: 900; color: #818cf8; line-height: 1.2;">{{ count($videos) }}</div>
                <div style="font-size: 0.74rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">Videos</div>
            </div>
            <div style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12); padding: 12px 22px; border-radius: 16px; text-align: center; min-width: 90px;">
                <div style="font-size: 1.4rem; font-weight: 900; color: #34d399; line-height: 1.2;">{{ count($newsItems) }}</div>
                <div style="font-size: 0.74rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">News</div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation Bar -->
    <div style="display: flex; gap: 8px; border-bottom: 2px solid #e2e8f0; margin-bottom: 25px; overflow-x: auto; padding-bottom: 2px; scrollbar-width: none;">
        <button type="button" class="staff-tab-btn active" data-tab="profile" onclick="switchStaffTab('profile')" style="padding: 12px 22px; font-weight: 800; font-size: 0.92rem; border: none; background: none; color: #6366f1; border-bottom: 3px solid #6366f1; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; white-space: nowrap; transition: all 0.2s ease;">
            <i class="bi bi-person-gear" style="font-size: 1.1rem;"></i> Profile Information
        </button>
        <button type="button" class="staff-tab-btn" data-tab="photos" onclick="switchStaffTab('photos')" style="padding: 12px 22px; font-weight: 700; font-size: 0.92rem; border: none; background: none; color: #64748b; border-bottom: 3px solid transparent; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; white-space: nowrap; transition: all 0.2s ease;">
            <i class="bi bi-camera-fill" style="font-size: 1.1rem;"></i> Manage Photos ({{ count($photos) }})
        </button>
        <button type="button" class="staff-tab-btn" data-tab="videos" onclick="switchStaffTab('videos')" style="padding: 12px 22px; font-weight: 700; font-size: 0.92rem; border: none; background: none; color: #64748b; border-bottom: 3px solid transparent; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; white-space: nowrap; transition: all 0.2s ease;">
            <i class="bi bi-camera-video-fill" style="font-size: 1.1rem;"></i> Manage Videos ({{ count($videos) }})
        </button>
        <button type="button" class="staff-tab-btn" data-tab="news" onclick="switchStaffTab('news')" style="padding: 12px 22px; font-weight: 700; font-size: 0.92rem; border: none; background: none; color: #64748b; border-bottom: 3px solid transparent; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; white-space: nowrap; transition: all 0.2s ease;">
            <i class="bi bi-newspaper" style="font-size: 1.1rem;"></i> Manage News ({{ count($newsItems) }})
        </button>
    </div>

    <!-- TAB 1: Profile Information Form -->
    <div id="staff-tab-profile" class="staff-tab-content" style="display: block;">
        <div style="background: #ffffff; border-radius: 18px; padding: 32px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #e2e8f0;">
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 24px;">
                <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 10px;">
                    <i class="bi bi-person-lines-fill" style="color: #6366f1; font-size: 1.25rem;"></i> Edit Talent Profile & Social Links
                </h3>
                <span style="font-size: 0.78rem; color: #64748b; font-weight: 600;">* Required fields</span>
            </div>

            <form action="{{ route('staff.talent.update-profile', $talent->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 22px;">
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 7px;">Full Name / Stage Name *</label>
                        <input type="text" name="name" value="{{ old('name', $talent->name) }}" required style="width: 100%; padding: 11px 15px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.92rem; color: #0f172a; box-sizing: border-box; background: #fff; transition: border-color 0.2s;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 7px;">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email', $talent->email) }}" required style="width: 100%; padding: 11px 15px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.92rem; color: #0f172a; box-sizing: border-box; background: #fff; transition: border-color 0.2s;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 7px;">Phone Number (Tanzania)</label>
                        <input type="text" name="phone" value="{{ old('phone', $talent->phone) }}" placeholder="06XXXXXXXX / +255..." style="width: 100%; padding: 11px 15px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.92rem; color: #0f172a; box-sizing: border-box; background: #fff; transition: border-color 0.2s;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 7px;">Talent Category *</label>
                        <select name="category" required style="width: 100%; padding: 11px 15px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.92rem; color: #0f172a; box-sizing: border-box; background: #fff; cursor: pointer;">
                            @foreach($categories as $slug => $label)
                            <option value="{{ $slug }}" {{ $talent->category === $slug ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 7px;">Country / Region</label>
                        <input type="text" name="country" value="{{ old('country', $talent->country) }}" style="width: 100%; padding: 11px 15px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.92rem; color: #0f172a; box-sizing: border-box; background: #fff;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 7px;">New Password (Optional)</label>
                        <input type="password" name="password" placeholder="Leave empty to keep current password" style="width: 100%; padding: 11px 15px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.92rem; color: #0f172a; box-sizing: border-box; background: #fff;">
                    </div>
                </div>

                <div style="margin-bottom: 22px;">
                    <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 7px;">Biography / Description</label>
                    <textarea name="description" rows="4" style="width: 100%; padding: 12px 15px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.92rem; color: #0f172a; font-family: inherit; box-sizing: border-box;" placeholder="Enter talent biography, skill details, performance history...">{{ old('description', $talent->description) }}</textarea>
                </div>

                <div style="margin-bottom: 28px; background: #f8fafc; padding: 18px 20px; border-radius: 12px; border: 1px dashed #cbd5e1;">
                    <label style="display: block; font-weight: 700; color: #334155; font-size: 0.88rem; margin-bottom: 6px;">Profile Avatar Picture</label>
                    <input type="file" name="profile_image" accept="image/*" style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; font-size: 0.85rem;">
                    <small style="color: #64748b; font-size: 0.78rem; display: block; margin-top: 6px;">
                        <i class="bi bi-info-circle-fill" style="color: #6366f1;"></i> Upload JPEG, PNG, WEBP image file (max 10MB). Automatically compressed on upload.
                    </small>
                </div>

                <h4 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 28px 0 16px 0; border-top: 1px solid #e2e8f0; padding-top: 22px; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-share-fill" style="color: #38bdf8;"></i> Social Media Links
                </h4>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; margin-bottom: 28px;">
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.84rem; margin-bottom: 5px;"><i class="bi bi-instagram" style="color: #e1306c;"></i> Instagram Profile URL</label>
                        <input type="url" name="social_instagram" value="{{ old('social_instagram', $talent->social_instagram) }}" placeholder="https://instagram.com/username" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.84rem; margin-bottom: 5px;"><i class="bi bi-facebook" style="color: #1877f2;"></i> Facebook Page URL</label>
                        <input type="url" name="social_facebook" value="{{ old('social_facebook', $talent->social_facebook) }}" placeholder="https://facebook.com/username" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.84rem; margin-bottom: 5px;"><i class="bi bi-tiktok" style="color: #000000;"></i> TikTok Profile URL</label>
                        <input type="url" name="social_tiktok" value="{{ old('social_tiktok', $talent->social_tiktok) }}" placeholder="https://tiktok.com/@username" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.84rem; margin-bottom: 5px;"><i class="bi bi-youtube" style="color: #ff0000;"></i> YouTube Channel URL</label>
                        <input type="url" name="social_youtube" value="{{ old('social_youtube', $talent->social_youtube) }}" placeholder="https://youtube.com/channel" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; padding-top: 15px; border-top: 1px solid #f1f5f9;">
                    <button type="submit" style="background: linear-gradient(135deg, #6366f1 0%, #38bdf8 100%); color: #ffffff; border: none; padding: 13px 32px; border-radius: 30px; font-weight: 800; font-size: 0.95rem; cursor: pointer; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4); transition: transform 0.2s ease;">
                        <i class="bi bi-check-lg"></i> Update Talent Profile
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TAB 2: Manage Photos -->
    <div id="staff-tab-photos" class="staff-tab-content" style="display: none;">
        <!-- Upload Photos Card -->
        <div style="background: #ffffff; border-radius: 18px; padding: 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; margin-bottom: 28px;">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-cloud-upload-fill" style="color: #6366f1; font-size: 1.2rem;"></i> Upload New Portfolio Photo(s)
            </h3>
            
            <form action="{{ route('staff.talent.photos.store', $talent->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 6px;">Photo Title / Label (Optional)</label>
                        <input type="text" name="title" placeholder="e.g. Stage Performance Photo" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 6px;">Select Image File(s) *</label>
                        <input type="file" name="photos[]" multiple accept="image/*" required style="width: 100%; padding: 8px; border: 1px dashed #cbd5e1; border-radius: 8px; background: #f8fafc; font-size: 0.85rem; box-sizing: border-box;">
                    </div>
                </div>
                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 6px;">Caption / Details (Optional)</label>
                    <input type="text" name="caption" placeholder="Short description for the photo..." style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
                </div>
                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" style="background: #6366f1; color: #ffffff; border: none; padding: 11px 26px; border-radius: 25px; font-weight: 800; font-size: 0.88rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);">
                        <i class="bi bi-upload"></i> Upload Photos Now
                    </button>
                </div>
            </form>
        </div>

        <!-- Photos Gallery List -->
        <h4 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
            <i class="bi bi-images" style="color: #6366f1;"></i> Portfolio Photos Gallery ({{ count($photos) }})
        </h4>
        @if(count($photos) > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px;">
            @foreach($photos as $photo)
            <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 14px rgba(0,0,0,0.03); display: flex; flex-direction: column; transition: transform 0.2s ease;">
                <div style="width: 100%; height: 200px; position: relative; background: #1e293b; cursor: pointer;" onclick="openPhotoViewer('{{ asset($photo->file_path) }}', '{{ addslashes($photo->title ?: 'Portfolio Photo') }}', '{{ addslashes($photo->content ?: '') }}')">
                    <img src="{{ asset($photo->file_path) }}" alt="{{ $photo->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                    <div style="position: absolute; top: 10px; right: 10px; background: rgba(15,23,42,0.75); backdrop-filter: blur(4px); padding: 4px 10px; border-radius: 12px; font-size: 0.72rem; font-weight: 700; color: #ffffff; display: flex; align-items: center; gap: 4px;">
                        <i class="bi bi-arrows-angle-expand"></i> View
                    </div>
                </div>
                <div style="padding: 14px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <strong style="display: block; font-size: 0.92rem; color: #0f172a; margin-bottom: 4px;">{{ $photo->title ?: 'Untitled Photo' }}</strong>
                        <p style="font-size: 0.8rem; color: #64748b; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $photo->content ?: 'No caption details' }}</p>
                    </div>
                    <div style="margin-top: 14px; display: flex; justify-content: flex-end;">
                        <form action="{{ route('staff.talent.photos.delete', [$talent->id, $photo->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this photo?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 6px 14px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align: center; padding: 50px 20px; background: #ffffff; border-radius: 18px; border: 1px dashed #cbd5e1; color: #94a3b8;">
            <i class="bi bi-images" style="font-size: 3rem; display: block; margin-bottom: 12px; color: #cbd5e1;"></i>
            <h4 style="margin: 0 0 6px 0; color: #475569; font-weight: 700;">No Portfolio Photos Uploaded</h4>
            <p style="margin: 0; font-size: 0.88rem;">Use the form above to upload images for this talent portfolio.</p>
        </div>
        @endif
    </div>

    <!-- TAB 3: Manage Videos -->
    <div id="staff-tab-videos" class="staff-tab-content" style="display: none;">
        <!-- Upload/Add Video Card -->
        <div style="background: #ffffff; border-radius: 18px; padding: 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; margin-bottom: 28px;">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-camera-video-fill" style="color: #6366f1; font-size: 1.2rem;"></i> Add Video Link or Upload Video File
            </h3>

            <form action="{{ route('staff.talent.videos.store', $talent->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 6px;">Video Title</label>
                        <input type="text" name="title" placeholder="e.g. Official Music Video / Showreel" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 6px;">Option A: External Video Link (YouTube, TikTok, IG, FB)</label>
                        <input type="url" name="video_url" placeholder="https://youtube.com/watch?v=... or TikTok link" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 6px;">Option B: Upload Direct MP4 Video File</label>
                    <input type="file" name="video" accept="video/mp4,video/quicktime,video/webm" style="width: 100%; padding: 8px; border: 1px dashed #cbd5e1; border-radius: 8px; background: #f8fafc; font-size: 0.85rem; box-sizing: border-box;">
                    <small style="color: #64748b; font-size: 0.78rem; display: block; margin-top: 4px;">Upload MP4, MOV, WEBM clip (max 50MB).</small>
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 6px;">Caption / Summary</label>
                    <input type="text" name="caption" placeholder="Short caption for video clip..." style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" style="background: #6366f1; color: #ffffff; border: none; padding: 11px 26px; border-radius: 25px; font-weight: 800; font-size: 0.88rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);">
                        <i class="bi bi-plus-circle-fill"></i> Add Video Now
                    </button>
                </div>
            </form>
        </div>

        <!-- Videos List -->
        <h4 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
            <i class="bi bi-play-btn-fill" style="color: #6366f1;"></i> Portfolio Videos List ({{ count($videos) }})
        </h4>
        @if(count($videos) > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(290px, 1fr)); gap: 20px;">
            @foreach($videos as $video)
            <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 14px rgba(0,0,0,0.03); display: flex; flex-direction: column;">
                <div style="width: 100%; background: #0f172a; position: relative; border-radius: 16px 16px 0 0; overflow: hidden;">
                    {!! \App\Helpers\VideoHelper::renderEmbed($video->file_path) !!}
                </div>
                <div style="padding: 14px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <strong style="display: block; font-size: 0.94rem; color: #0f172a; margin-bottom: 4px;">{{ $video->title ?: 'Untitled Video' }}</strong>
                        <p style="font-size: 0.8rem; color: #64748b; margin: 0;">{{ $video->content ?: 'No details provided' }}</p>
                    </div>
                    <div style="margin-top: 14px; display: flex; justify-content: flex-end;">
                        <form action="{{ route('staff.talent.videos.delete', [$talent->id, $video->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this video?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 6px 14px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align: center; padding: 50px 20px; background: #ffffff; border-radius: 18px; border: 1px dashed #cbd5e1; color: #94a3b8;">
            <i class="bi bi-camera-video" style="font-size: 3rem; display: block; margin-bottom: 12px; color: #cbd5e1;"></i>
            <h4 style="margin: 0 0 6px 0; color: #475569; font-weight: 700;">No Portfolio Videos Added</h4>
            <p style="margin: 0; font-size: 0.88rem;">Add video links or direct video files using the form above.</p>
        </div>
        @endif
    </div>

    <!-- TAB 4: Manage News -->
    <div id="staff-tab-news" class="staff-tab-content" style="display: none;">
        <!-- Publish News Card -->
        <div style="background: #ffffff; border-radius: 18px; padding: 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; margin-bottom: 28px;">
            <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-newspaper" style="color: #6366f1; font-size: 1.2rem;"></i> Publish News Bulletin / Update
            </h3>

            <form action="{{ route('staff.talent.news.store', $talent->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 6px;">Bulletin Title *</label>
                    <input type="text" name="title" required placeholder="e.g. Upcoming Album Launch / Live Tour Announcement" style="width: 100%; padding: 11px 15px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 6px;">Bulletin Content / Full Text *</label>
                    <textarea name="content" rows="5" required placeholder="Write news announcement details..." style="width: 100%; padding: 12px 15px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; font-family: inherit; box-sizing: border-box;"></textarea>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 6px;">Featured Poster / Image (Optional)</label>
                    <input type="file" name="image" accept="image/*" style="width: 100%; padding: 8px; border: 1px dashed #cbd5e1; border-radius: 8px; background: #f8fafc; font-size: 0.85rem; box-sizing: border-box;">
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" style="background: #6366f1; color: #ffffff; border: none; padding: 11px 26px; border-radius: 25px; font-weight: 800; font-size: 0.88rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);">
                        <i class="bi bi-send-fill"></i> Publish News Bulletin
                    </button>
                </div>
            </form>
        </div>

        <!-- News Bulletins List -->
        <h4 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
            <i class="bi bi-journal-text" style="color: #6366f1;"></i> Published News Bulletins ({{ count($newsItems) }})
        </h4>
        @if(count($newsItems) > 0)
        <div style="display: flex; flex-direction: column; gap: 18px;">
            @foreach($newsItems as $news)
            <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 22px; box-shadow: 0 4px 14px rgba(0,0,0,0.03); display: flex; gap: 20px; align-items: flex-start; flex-wrap: wrap;">
                @if($news->file_path)
                <div style="width: 140px; height: 110px; border-radius: 12px; overflow: hidden; background: #1e293b; flex-shrink: 0;">
                    <img src="{{ asset($news->file_path) }}" alt="{{ $news->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                @endif
                <div style="flex: 1; min-width: 250px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px; flex-wrap: wrap; gap: 10px;">
                        <h4 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #0f172a;">{{ $news->title }}</h4>
                        <span style="font-size: 0.76rem; color: #94a3b8; font-weight: 600;"><i class="bi bi-clock"></i> {{ $news->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <p style="font-size: 0.88rem; color: #475569; margin: 0 0 14px 0; line-height: 1.5; white-space: pre-line;">{{ $news->content }}</p>
                    <div style="display: flex; justify-content: flex-end;">
                        <form action="{{ route('staff.talent.news.delete', [$talent->id, $news->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this news article?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 6px 14px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                                <i class="bi bi-trash"></i> Delete Bulletin
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align: center; padding: 50px 20px; background: #ffffff; border-radius: 18px; border: 1px dashed #cbd5e1; color: #94a3b8;">
            <i class="bi bi-newspaper" style="font-size: 3rem; display: block; margin-bottom: 12px; color: #cbd5e1;"></i>
            <h4 style="margin: 0 0 6px 0; color: #475569; font-weight: 700;">No News Bulletins Published</h4>
            <p style="margin: 0; font-size: 0.88rem;">Publish news articles and tour announcements using the form above.</p>
        </div>
    <!-- Photo Lightbox Viewer Modal -->
    <div id="photoViewerModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15,23,42,0.92); backdrop-filter: blur(8px); z-index: 999999; justify-content: center; align-items: center; padding: 20px;" onclick="closePhotoViewer()">
        <div style="position: relative; max-width: 90vw; max-height: 90vh; text-align: center;" onclick="event.stopPropagation();">
            <button type="button" onclick="closePhotoViewer()" style="position: absolute; top: -45px; right: -10px; background: rgba(255,255,255,0.2); border: none; border-radius: 50%; width: 36px; height: 36px; font-size: 1.6rem; color: #ffffff; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">&times;</button>
            <img id="viewerModalImg" src="" alt="Full View" style="max-width: 100%; max-height: 80vh; border-radius: 12px; box-shadow: 0 20px 50px rgba(0,0,0,0.6); object-fit: contain; border: 2px solid rgba(255,255,255,0.2);">
            <h4 id="viewerModalTitle" style="margin: 15px 0 0 0; color: #ffffff; font-size: 1.1rem; font-weight: 700;"></h4>
            <p id="viewerModalCaption" style="margin: 6px 0 0 0; color: #cbd5e1; font-size: 0.9rem; font-weight: 500; line-height: 1.4; max-width: 600px; text-align: center; margin-left: auto; margin-right: auto;"></p>
        </div>
    </div>
</main>

<script>
    function openPhotoViewer(url, title, caption) {
        const modal = document.getElementById('photoViewerModal');
        const img = document.getElementById('viewerModalImg');
        const titleEl = document.getElementById('viewerModalTitle');
        const captionEl = document.getElementById('viewerModalCaption');
        if (modal && img) {
            img.src = url;
            if (titleEl) titleEl.innerText = title || '';
            if (captionEl) captionEl.innerText = caption || '';
            modal.style.display = 'flex';
        }
    }

    function closePhotoViewer() {
        const modal = document.getElementById('photoViewerModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }

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

        if (history.replaceState) {
            history.replaceState(null, null, '#' + tabName);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const hash = window.location.hash.replace('#', '');
        if (hash && document.getElementById('staff-tab-' + hash)) {
            switchStaffTab(hash);
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePhotoViewer();
        }
    });
</script>
@endsection
