@extends('layouts.app')

@section('title', $talent->name . ' - Videos')

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
            <a href="{{ route('profile.photos', $talent->id) }}">Photos Gallery</a>
            <a class="active" href="{{ route('profile.videos', $talent->id) }}">Videos Showcase</a>
            <a href="{{ route('home') }}" style="margin-top: 15px; background: rgba(255, 255, 255, 0.05); border-color: var(--border-color);">Back to Directory</a>
        </div>
    </div>

    <!-- Videos Grid -->
    <div class="pdetails" style="padding: 30px;">
        <h2>{{ $talent->name }}'s Videos</h2>
        <p style="color: var(--text-muted); margin-bottom: 25px; font-size: 0.9rem;">Watch uploaded videos and showreel files.</p>
        
        <div class="videos-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
            @forelse($videos as $index => $video)
                <div class="video-item" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; overflow: hidden;">
                    {!! \App\Helpers\VideoHelper::renderEmbed($video->file_path) !!}
                    
                    <div style="padding: 15px;">
                        <h4 style="font-size: 1rem; margin-bottom: 4px; color: var(--text-primary);">Video Showcase #{{ $index + 1 }}</h4>
                        <p style="font-size: 0.8rem; color: var(--text-muted);">Added {{ $video->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <div class="no-results" style="grid-column: 1/-1;">
                    No videos uploaded to this portfolio yet.
                </div>
            @endforelse
        </div>
    </div>
</main>
@endsection
