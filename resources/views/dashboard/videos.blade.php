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
        <p style="color: var(--text-muted); margin-bottom: 25px; font-size: 0.9rem;">Upload video showreels, performance clips, or paste YouTube/Vimeo video links.</p>
        
        <!-- Upload Mode Switcher -->
        <div style="display: flex; gap: 10px; margin-bottom: 20px;">
            <button type="button" id="tabUploadFile" class="btn-tab active-tab" style="padding: 8px 16px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; background: var(--accent); color: white; border: none;">
                📁 Upload File
            </button>
            <button type="button" id="tabUploadUrl" class="btn-tab" style="padding: 8px 16px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; background: rgba(255,255,255,0.05); color: var(--text-muted); border: 1px solid var(--border-color);">
                🔗 Embed YouTube / Video Link
            </button>
        </div>

        <!-- Option A: File Upload Form -->
        <form id="formFileUpload" action="{{ route('dashboard.videos.store') }}" method="POST" enctype="multipart/form-data" style="margin-bottom: 40px; background: rgba(255,255,255,0.02); border: 1px dashed var(--border-color); padding: 22px; border-radius: 12px;">
            @csrf
            
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="video">Select Portfolio Video File</label>
                <input type="file" id="video" name="video" class="form-control" accept="video/*" style="padding: 8px;">
                <p style="font-size: 0.78rem; color: var(--text-muted); margin-top: 6px;">Supported formats: MP4, MOV, AVI, WEBM, MKV, 3GP. Max file size: 50MB.</p>
            </div>
            
            <button type="submit" class="btn-submit" style="padding: 10px 24px; font-size: 0.85rem;">Upload Video File</button>
        </form>

        <!-- Option B: URL Link Form -->
        <form id="formUrlUpload" action="{{ route('dashboard.videos.store') }}" method="POST" style="display: none; margin-bottom: 40px; background: rgba(255,255,255,0.02); border: 1px dashed var(--border-color); padding: 22px; border-radius: 12px;">
            @csrf
            
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="video_url">YouTube, Vimeo, or Video Link URL</label>
                <input type="url" id="video_url" name="video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=... or https://vimeo.com/..." style="padding: 10px;">
                <p style="font-size: 0.78rem; color: var(--text-muted); margin-top: 6px;">Paste any public YouTube link, Vimeo video, or direct MP4 URL.</p>
            </div>
            
            <button type="submit" class="btn-submit" style="padding: 10px 24px; font-size: 0.85rem; background: linear-gradient(135deg, #ec4899, #8b5cf6);">Add Video Link</button>
        </form>

        <h3 style="font-size: 1rem; margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Your Portfolio Videos ({{ $videos->count() }})</h3>
        
        <!-- Current Videos List -->
        <div class="videos-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
            @forelse($videos as $video)
                <div class="video-item" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; overflow: hidden;">
                    {!! \App\Helpers\VideoHelper::renderEmbed($video->file_path) !!}
                    
                    <div style="padding: 15px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h4 style="font-size: 0.9rem; color: var(--text-primary);">Video Asset</h4>
                            <p style="font-size: 0.75rem; color: var(--text-muted);">Added {{ $video->created_at->diffForHumans() }}</p>
                        </div>
                        <form action="{{ route('dashboard.videos.delete', $video->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this video?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" style="padding: 6px 12px; font-size: 0.8rem; background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 6px; cursor: pointer;">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="no-results" style="grid-column: 1/-1;">
                    You have not added any videos to your portfolio yet.
                </div>
            @endforelse
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const tabFile = document.getElementById("tabUploadFile");
        const tabUrl = document.getElementById("tabUploadUrl");
        const formFile = document.getElementById("formFileUpload");
        const formUrl = document.getElementById("formUrlUpload");

        tabFile.addEventListener("click", () => {
            tabFile.style.background = "var(--accent)";
            tabFile.style.color = "white";
            tabUrl.style.background = "rgba(255,255,255,0.05)";
            tabUrl.style.color = "var(--text-muted)";
            formFile.style.display = "block";
            formUrl.style.display = "none";
        });

        tabUrl.addEventListener("click", () => {
            tabUrl.style.background = "var(--accent)";
            tabUrl.style.color = "white";
            tabFile.style.background = "rgba(255,255,255,0.05)";
            tabFile.style.color = "var(--text-muted)";
            formUrl.style.display = "block";
            formFile.style.display = "none";
        });
    });
</script>
@endsection
