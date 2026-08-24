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
                <div class="photo-item">
                    <img src="{{ $photo->file_path }}" alt="{{ $talent->name }} Portfolio Photo">
                </div>
            @empty
                <div class="no-results" style="grid-column: 1/-1;">
                    No photos uploaded to this portfolio yet.
                </div>
            @endforelse
        </div>
    </div>
</main>
@endsection
