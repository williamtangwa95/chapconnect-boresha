@extends('layouts.app')

@section('title', 'Chap Connect - Manage Videos')

@section('content')
<main class="profile-hero">
    <!-- Sidebar profile card -->
    <div class="profile-sidebar">
        <div class="pimage">
            @if(auth()->user()->profile_image)
                <img src="{{ auth()->user()->profile_image }}" alt="{{ auth()->user()->name }}">
            @else
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop" alt="{{ auth()->user()->name }}">
            @endif
        </div>
        <h2>{{ auth()->user()->name }}</h2>
        <h5>{{ auth()->user()->category_label }}</h5>
        
        <div class="profile-menu-vertical">
            <a href="{{ route('dashboard') }}">Overview Settings</a>
            <a href="{{ route('dashboard.photos') }}">Manage Photos</a>
            <a class="active" href="{{ route('dashboard.videos') }}">Manage Videos</a>
            <a href="{{ route('profile', auth()->user()->id) }}" target="_blank" style="background: rgba(99, 102, 241, 0.1); color: var(--accent); border-color: rgba(99, 102, 241, 0.2);">Preview Public Profile</a>
        </div>
    </div>

    <!-- Main Content Area: Videos Manager -->
    <div class="pdetails" style="padding: 30px;">
        <h2>Manage Portfolio Videos</h2>
        <p style="color: var(--text-muted); margin-bottom: 25px; font-size: 0.9rem;">Upload video showreels, performance clips, or highlights of your work.</p>
        
        <!-- Upload Form -->
        <form action="{{ route('dashboard.videos.store') }}" method="POST" enctype="multipart/form-data" style="margin-bottom: 40px; background: rgba(255,255,255,0.02); border: 1px dashed var(--border-color); padding: 20px; border-radius: 12px;">
            @csrf
            
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="video">Select Portfolio Video File</label>
                <input type="file" id="video" name="video" class="form-control" accept="video/*" required style="padding: 8px;">
                <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">Supported formats: MP4, MOV, AVI, WEBM. Max file size: 20MB.</p>
            </div>
            
            <button type="submit" class="btn-submit" style="padding: 10px 24px; font-size: 0.85rem;">Upload Video</button>
        </form>

        <h3 style="font-size: 1rem; margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Your Uploaded Videos ({{ $videos->count() }})</h3>
        
        <!-- Current Uploads List -->
        <div class="videos-grid">
            @forelse($videos as $video)
                <div class="video-item" style="position: relative;">
                    <div class="video-wrapper">
                        <video controls preload="metadata">
                            <source src="{{ $video->file_path }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    <div style="padding: 15px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h4 style="font-size: 0.9rem; color: var(--text-primary);">Video Asset</h4>
                            <p style="font-size: 0.75rem; color: var(--text-muted);">Uploaded {{ $video->created_at->diffForHumans() }}</p>
                        </div>
                        <form action="{{ route('dashboard.videos.delete', $video->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this video?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" style="padding: 4px 10px;">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="no-results" style="grid-column: 1/-1;">
                    You have not uploaded any videos to your portfolio yet.
                </div>
            @endforelse
        </div>
    </div>
</main>
@endsection
