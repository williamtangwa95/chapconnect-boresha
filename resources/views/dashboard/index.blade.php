@extends('layouts.app')

@section('title', 'Chap Connect - Dashboard Settings')

@section('content')
<main class="profile-hero">
    <!-- Sidebar profile card -->
    <div class="profile-sidebar">
        <div class="pimage">
            @if($user->profile_image)
                <img src="{{ $user->profile_image }}" alt="{{ $user->name }}">
            @else
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop" alt="{{ $user->name }}">
            @endif
        </div>
        <h2>{{ $user->name }}</h2>
        <h5>{{ $user->category_label }}</h5>
        
        <!-- Publish Status Badge -->
        @if($user->is_published)
            <div style="margin: 8px 0; padding: 5px 12px; border-radius: 20px; background: rgba(16,185,129,0.15); color: #10b981; font-size: 0.78rem; font-weight: 700; display: inline-flex; align-items: center; gap: 5px;">
                <span style="width:7px;height:7px;background:#10b981;border-radius:50%;display:inline-block;"></span> LIVE &amp; PUBLIC
            </div>
        @else
            <div style="margin: 8px 0; padding: 5px 12px; border-radius: 20px; background: rgba(239,68,68,0.12); color: #ef4444; font-size: 0.78rem; font-weight: 700; display: inline-flex; align-items: center; gap: 5px;">
                <span style="width:7px;height:7px;background:#ef4444;border-radius:50%;display:inline-block;"></span> HIDDEN (Draft)
            </div>
        @endif

        <div class="profile-menu-vertical">
            <a class="active" href="{{ route('dashboard') }}">Overview Settings</a>
            <a href="{{ route('dashboard.photos') }}">Manage Photos</a>
            <a href="{{ route('dashboard.videos') }}">Manage Videos</a>
            <a href="{{ route('profile', $user->id) }}" target="_blank" style="background: rgba(99, 102, 241, 0.1); color: var(--accent); border-color: rgba(99, 102, 241, 0.2);">Preview Public Profile</a>
        </div>
    </div>

    <!-- Main Details Form -->
    <div class="pdetails" style="padding: 30px;">

        <!-- Flash Messages -->
        @if(session('success'))
            <div style="background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.3); color: #10b981; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 0.9rem;">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.3); color: #ef4444; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 0.9rem;">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        <!-- Profile Completion & Publish Panel -->
        <div style="background: rgba(255,255,255,0.03); border: 1px solid var(--border-color); border-radius: 14px; padding: 22px 24px; margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <div>
                    <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px;">Profile Completion</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: @if($completion >= 60) #10b981 @else var(--accent) @endif;">{{ $completion }}%</div>
                </div>

                @if($user->is_published)
                    <form action="{{ route('dashboard.unpublish') }}" method="POST">
                        @csrf
                        <button type="submit" style="padding: 10px 22px; background: rgba(239,68,68,0.12); color: #ef4444; border: 1px solid rgba(239,68,68,0.3); border-radius: 8px; font-weight: 700; font-size: 0.88rem; cursor: pointer; transition: all 0.2s;">
                            🔒 Unpublish Profile
                        </button>
                    </form>
                @else
                    <form action="{{ route('dashboard.publish') }}" method="POST">
                        @csrf
                        <button type="submit" 
                            @if($completion < 60) disabled title="Complete at least 60% of your profile to publish" @endif
                            style="padding: 10px 22px; background: @if($completion >= 60) linear-gradient(135deg, #10b981, #059669) @else rgba(100,100,100,0.2) @endif; color: @if($completion >= 60) #fff @else #888 @endif; border: none; border-radius: 8px; font-weight: 700; font-size: 0.88rem; cursor: @if($completion >= 60) pointer @else not-allowed @endif; transition: all 0.2s;">
                            🌐 Publish Profile
                        </button>
                    </form>
                @endif
            </div>

            <!-- Progress Bar -->
            <div style="background: rgba(255,255,255,0.06); border-radius: 999px; height: 8px; overflow: hidden;">
                <div style="height: 100%; width: {{ $completion }}%; border-radius: 999px; background: @if($completion >= 60) linear-gradient(90deg, #10b981, #059669) @else linear-gradient(90deg, var(--accent), var(--accent-pink)) @endif; transition: width 0.5s ease;"></div>
            </div>

            <!-- Checklist hints -->
            <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 14px;">
                @php
                    $checks = [
                        'Name set'              => !empty($user->name),
                        'Bio written'           => !empty($user->description),
                        'Profile photo'         => !empty($user->profile_image),
                        'Phone added'           => !empty($user->phone),
                        'Country added'         => !empty($user->country),
                        'Photo uploaded'        => $user->media()->where('type','photo')->exists(),
                        'Social link added'     => !empty($user->social_instagram) || !empty($user->social_facebook) || !empty($user->social_tiktok) || !empty($user->social_youtube),
                    ];
                @endphp
                @foreach($checks as $label => $done)
                    <span style="font-size: 0.72rem; font-weight: 600; padding: 3px 10px; border-radius: 20px;
                        background: {{ $done ? 'rgba(16,185,129,0.12)' : 'rgba(255,255,255,0.05)' }};
                        color: {{ $done ? '#10b981' : '#888' }};
                        border: 1px solid {{ $done ? 'rgba(16,185,129,0.25)' : 'rgba(255,255,255,0.08)' }};">
                        {{ $done ? '✓' : '○' }} {{ $label }}
                    </span>
                @endforeach
            </div>

            @if($completion < 60)
                <p style="font-size: 0.78rem; color: #888; margin-top: 10px; margin-bottom: 0;">Complete at least <strong style="color: var(--accent);">4 out of 7</strong> profile sections to unlock publishing.</p>
            @endif
        </div>

        <h2>Profile Control Panel</h2>
        <p style="color: var(--text-muted); margin-bottom: 25px; font-size: 0.9rem;">Update your biography description, profile photo, and public details.</p>
        
        <form action="{{ route('dashboard.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="name">Stage Name / Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                
                <div class="form-group">
                    <label for="category_read">Registered Category</label>
                    <input type="text" id="category_read" class="form-control" value="{{ $user->category_label }}" disabled style="opacity: 0.7;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                </div>
                
                <div class="form-group">
                    <label for="country">Country Location</label>
                    <input type="text" id="country" name="country" class="form-control" value="{{ old('country', $user->country) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label for="profile_image">Change Profile Photo</label>
                <input type="file" id="profile_image" name="profile_image" class="form-control" accept="image/*" style="padding: 8px;">
                <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">Recommended: Square image, maximum 2MB size.</p>
            </div>

            <div class="form-group">
                <label for="description">Short Bio Description</label>
                <textarea id="description" name="description" class="form-control" placeholder="Tell the world about yourself and your works...">{{ old('description', $user->description) }}</textarea>
            </div>

            <h3 style="font-size: 0.9rem; color: var(--accent); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; margin-bottom: 20px; margin-top: 30px;">Social Media Links</h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="social_instagram">Instagram Link</label>
                    <input type="url" id="social_instagram" name="social_instagram" class="form-control" value="{{ old('social_instagram', $user->social_instagram) }}" placeholder="https://instagram.com/username">
                </div>
                
                <div class="form-group">
                    <label for="social_facebook">Facebook Link</label>
                    <input type="url" id="social_facebook" name="social_facebook" class="form-control" value="{{ old('social_facebook', $user->social_facebook) }}" placeholder="https://facebook.com/page">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="social_tiktok">TikTok Link</label>
                    <input type="url" id="social_tiktok" name="social_tiktok" class="form-control" value="{{ old('social_tiktok', $user->social_tiktok) }}" placeholder="https://tiktok.com/@username">
                </div>
                
                <div class="form-group">
                    <label for="social_youtube">YouTube Link</label>
                    <input type="url" id="social_youtube" name="social_youtube" class="form-control" value="{{ old('social_youtube', $user->social_youtube) }}" placeholder="https://youtube.com/channel">
                </div>
            </div>
            
            <button type="submit" class="btn-submit" style="margin-top: 10px;">Save Settings</button>
        </form>
    </div>
</main>
@endsection
