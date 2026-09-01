@extends('layouts.app')

@section('title', $talent->name . ' - Photos')

@section('content')
<main class="profile-hero">
    <!-- Sidebar profile card -->
    <div class="profile-sidebar">
        <div class="pimage">
            @if($talent->profile_image)
                <img src="{{ $talent->profile_image }}" alt="{{ $talent->name }}">
            @else
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop" alt="{{ $talent->name }}">
            @endif
        </div>
        <h2>{{ $talent->name }}</h2>
        <h5>{{ $talent->category_label }}</h5>
        
        <div class="profile-menu-vertical">
            <a href="{{ route('profile', $talent->id) }}">Overview</a>
            <a class="active" href="{{ route('profile.photos', $talent->id) }}">Photos Gallery</a>
            <a href="{{ route('profile.videos', $talent->id) }}">Videos Showcase</a>
            <a href="{{ route('home') }}" style="margin-top: 15px; background: rgba(255, 255, 255, 0.05); border-color: var(--border-color);">Back to Directory</a>
        </div>
    </div>

    <!-- Photos Grid -->
    <div class="pdetails" style="padding: 30px;">
        <h2>{{ $talent->name }}'s Photos</h2>
        <p style="color: var(--text-muted); margin-bottom: 20px; font-size: 0.9rem;">Browse visual work and portfolio uploads.</p>
        
        <div class="photos-grid">
            @forelse($photos as $photo)
                <div class="photo-item" style="position: relative; overflow: hidden; border-radius: 12px; group">
                    <img src="{{ $photo->file_path }}" alt="{{ $talent->name }} Portfolio Photo" style="width: 100%; height: 100%; object-fit: cover;">
                    <button type="button" onclick="openReportModal({{ $photo->id }})" title="{{ __('Report Inappropriate Content') }}" style="position: absolute; top: 8px; right: 8px; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); color: #f87171; border: 1px solid rgba(248, 113, 113, 0.4); border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 0.75rem; transition: all 0.2s ease;">
                        <i class="bi bi-flag"></i>
                    </button>
                </div>
            @empty
                <div class="no-results" style="grid-column: 1/-1;">
                    {{ __('No photos uploaded to this portfolio yet.') }}
                </div>
            @endforelse
        </div>
    </div>
</main>
@endsection
