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
        
        <div class="profile-menu-vertical">
            <a class="active" href="{{ route('dashboard') }}">Overview Settings</a>
            <a href="{{ route('dashboard.photos') }}">Manage Photos</a>
            <a href="{{ route('dashboard.videos') }}">Manage Videos</a>
            <a href="{{ route('profile', $user->id) }}" target="_blank" style="background: rgba(99, 102, 241, 0.1); color: var(--accent); border-color: rgba(99, 102, 241, 0.2);">Preview Public Profile</a>
        </div>
    </div>

    <!-- Main Details Form -->
    <div class="pdetails" style="padding: 30px;">
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
