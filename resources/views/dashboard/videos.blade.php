@extends('layouts.app')

@section('title', 'ChapConnect - Manage Videos')

@section('content')
<main class="profile-hero" style="max-width: 1200px; margin: 30px auto; padding: 0 20px; box-sizing: border-box;">
    <!-- Sidebar profile card -->
    <div class="profile-sidebar" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
        <div class="pimage" style="width: 90px; height: 90px; margin: 0 auto 15px auto; border-radius: 50%; overflow: hidden; border: 3px solid var(--primary); box-shadow: 0 4px 15px rgba(99,102,241,0.25);">
            @if(auth()->user()->profile_image)
                <img src="{{ asset(auth()->user()->profile_image) }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; object-fit: cover;">
            @endif
        </div>
        <h2 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; text-align: center; margin-bottom: 2px;">{{ auth()->user()->name }}</h2>
        <h5 style="font-size: 0.82rem; color: #64748b; text-align: center; margin-bottom: 20px; font-weight: 600;">{{ auth()->user()->category_label }}</h5>
        
        <div class="profile-menu-vertical">
            <a href="{{ route('dashboard') }}"><i class="bi bi-sliders"></i> Overview Settings</a>
            <a href="{{ route('dashboard.photos') }}"><i class="bi bi-images"></i> Manage Photos</a>
            <a class="active" href="{{ route('dashboard.videos') }}"><i class="bi bi-film"></i> Manage Videos</a>
            <a href="{{ route('dashboard.news') }}"><i class="bi bi-newspaper"></i> Manage News</a>
            <a href="{{ route('profile', auth()->user()->id) }}" target="_blank" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); border: 1px solid rgba(99, 102, 241, 0.25);"><i class="bi bi-box-arrow-up-right"></i> Preview Public Profile</a>
        </div>
    </div>

    <!-- Main Content Area: Videos Manager -->
    <div class="pdetails" style="background: #ffffff; border-radius: 16px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
        <!-- Page Header -->
        <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; padding: 24px 28px; border-radius: 14px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <div>
                <h2 style="margin: 0 0 6px 0; font-size: 1.35rem; font-weight: 800; color: #ffffff; border: none; padding: 0; display: flex; align-items: center; gap: 10px;">
                    <i class="bi bi-film" style="color: #6366f1;"></i> Portfolio Video Showreels
                </h2>
                <p style="margin: 0; color: #94a3b8; font-size: 0.88rem;">Upload video files or embed YouTube/Vimeo links with custom titles and captions.</p>
            </div>
            <div style="background: rgba(99,102,241,0.2); border: 1px solid rgba(99,102,241,0.4); padding: 6px 16px; border-radius: 20px; font-weight: 700; font-size: 0.82rem; color: #a5b4fc;">
                Total Videos: {{ $videos->count() }}
            </div>
        </div>
        
        <!-- Upload Mode Switcher Tabs -->
        <div style="display: flex; gap: 10px; margin-bottom: 20px; background: #f1f5f9; padding: 6px; border-radius: 12px; border: 1px solid #e2e8f0; width: fit-content; max-width: 100%; flex-wrap: wrap;">
            <button type="button" id="tabUploadFile" class="btn-tab active-tab" style="padding: 9px 20px; border-radius: 8px; font-size: 0.88rem; font-weight: 700; cursor: pointer; background: #6366f1; color: white; border: none; box-shadow: 0 2px 8px rgba(99,102,241,0.3); transition: all 0.2s ease;">
                <i class="bi bi-file-earmark-play-fill"></i> Upload File
            </button>
            <button type="button" id="tabUploadUrl" class="btn-tab" style="padding: 9px 20px; border-radius: 8px; font-size: 0.88rem; font-weight: 700; cursor: pointer; background: transparent; color: #64748b; border: none; transition: all 0.2s ease;">
                <i class="bi bi-youtube"></i> Embed YouTube / Video Link
            </button>
        </div>

        <!-- Option A: File Upload Form -->
        <form id="formFileUpload" action="{{ route('dashboard.videos.store') }}" method="POST" enctype="multipart/form-data" style="margin-bottom: 35px; background: #f8fafc; border: 2px dashed #cbd5e1; padding: 25px; border-radius: 14px; transition: all 0.2s ease;">
            @csrf
            
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
                <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(99,102,241,0.12); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                    <i class="bi bi-file-earmark-arrow-up-fill"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: #0f172a;">Upload Video File</h3>
                    <p style="margin: 0; font-size: 0.8rem; color: #64748b;">Upload MP4, MOV, AVI, WEBM, MKV, or 3GP (Max 50MB per video).</p>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 20px;">
                <!-- Video Title -->
                <div class="form-group">
                    <label for="video_title_file" style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Video Title (Optional)</label>
                    <input type="text" id="video_title_file" name="title" class="form-control" placeholder="e.g. Official Music Video or Live Performance Showreel" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%;">
                </div>

                <!-- Video Caption -->
                <div class="form-group">
                    <label for="video_caption_file" style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Video Description / Caption (Optional)</label>
                    <textarea id="video_caption_file" name="caption" class="form-control" rows="2" placeholder="Write a short description or notes about this video clip..." style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%; line-height: 1.5;"></textarea>
                </div>

                <!-- File Input -->
                <div class="form-group">
                    <label for="video" style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Select Video File *</label>
                    <input type="file" id="video" name="video" class="form-control" accept="video/*" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.88rem; width: 100%;">
                </div>
            </div>
            
            <button type="submit" style="padding: 11px 26px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(99,102,241,0.35); cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-size: 0.88rem;">
                <i class="bi bi-upload"></i> Upload Video &amp; Details
            </button>
        </form>

        <!-- Option B: URL Link Form -->
        <form id="formUrlUpload" action="{{ route('dashboard.videos.store') }}" method="POST" style="display: none; margin-bottom: 35px; background: #f8fafc; border: 2px dashed #cbd5e1; padding: 25px; border-radius: 14px; transition: all 0.2s ease;">
            @csrf
            
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
                <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(236,72,153,0.12); color: #ec4899; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                    <i class="bi bi-youtube"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: #0f172a;">Embed YouTube / Video Link</h3>
                    <p style="margin: 0; font-size: 0.8rem; color: #64748b;">Paste any public YouTube link, Vimeo video, or direct MP4 URL.</p>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 20px;">
                <!-- Video Title -->
                <div class="form-group">
                    <label for="video_title_url" style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Video Title (Optional)</label>
                    <input type="text" id="video_title_url" name="title" class="form-control" placeholder="e.g. YouTube Live Performance or Concert Highlight" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%;">
                </div>

                <!-- Video Caption -->
                <div class="form-group">
                    <label for="video_caption_url" style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Video Description / Caption (Optional)</label>
                    <textarea id="video_caption_url" name="caption" class="form-control" rows="2" placeholder="Write a short description or notes about this video link..." style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%; line-height: 1.5;"></textarea>
                </div>

                <!-- URL Input -->
                <div class="form-group">
                    <label for="video_url" style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">YouTube, Vimeo, or Video Link URL *</label>
                    <input type="url" id="video_url" name="video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=... or https://vimeo.com/..." style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%;">
                </div>
            </div>

            <button type="submit" style="padding: 11px 26px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(236,72,153,0.35); cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-size: 0.88rem;">
                <i class="bi bi-plus-circle-fill"></i> Add Video Link &amp; Details
            </button>
        </form>

        <h3 style="font-size: 1.05rem; font-weight: 700; color: #0f172a; margin-bottom: 18px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; display: flex; align-items: center; justify-content: space-between;">
            <span><i class="bi bi-collection-play-fill" style="color: #6366f1; margin-right: 6px;"></i> Your Video Assets</span>
            <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">{{ $videos->count() }} items</span>
        </h3>
        
        <!-- Current Videos List -->
        <div class="videos-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 22px;">
            @forelse($videos as $video)
                <div class="video-item" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; flex-direction: column;">
                    <div style="width: 100%; aspect-ratio: 16/9; background: #0f172a; overflow: hidden; position: relative;">
                        {!! \App\Helpers\VideoHelper::renderEmbed($video->file_path) !!}
                    </div>
                    
                    <div style="padding: 16px; background: #ffffff; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; gap: 10px;">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 4px;">
                                <h4 style="font-size: 0.95rem; font-weight: 700; color: #0f172a; margin: 0;">{{ $video->title ?: 'Portfolio Video' }}</h4>
                                <form action="{{ route('dashboard.videos.delete', $video->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this video?');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding: 5px 12px; font-size: 0.76rem; font-weight: 700; background: #ef4444; color: #ffffff; border: none; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 8px rgba(239,68,68,0.3);">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                            @if($video->content)
                                <p style="font-size: 0.82rem; color: #475569; margin: 0 0 6px 0; line-height: 1.4;">{{ $video->content }}</p>
                            @endif
                        </div>
                        <span style="font-size: 0.76rem; color: #64748b; font-weight: 600;"><i class="bi bi-clock"></i> {{ $video->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; padding: 50px 20px; text-align: center; background: #f8fafc; border-radius: 14px; border: 1px dashed #cbd5e1;">
                    <i class="bi bi-film" style="font-size: 2.5rem; color: #94a3b8; display: block; margin-bottom: 10px;"></i>
                    <p style="margin: 0; color: #64748b; font-weight: 600; font-size: 0.92rem;">You have not added any videos to your portfolio yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabUploadFile = document.getElementById('tabUploadFile');
        const tabUploadUrl = document.getElementById('tabUploadUrl');
        const formFileUpload = document.getElementById('formFileUpload');
        const formUrlUpload = document.getElementById('formUrlUpload');

        if (tabUploadFile && tabUploadUrl) {
            tabUploadFile.addEventListener('click', function() {
                tabUploadFile.style.background = '#6366f1';
                tabUploadFile.style.color = '#ffffff';
                tabUploadFile.style.boxShadow = '0 2px 8px rgba(99,102,241,0.3)';

                tabUploadUrl.style.background = 'transparent';
                tabUploadUrl.style.color = '#64748b';
                tabUploadUrl.style.boxShadow = 'none';

                formFileUpload.style.display = 'block';
                formUrlUpload.style.display = 'none';
            });

            tabUploadUrl.addEventListener('click', function() {
                tabUploadUrl.style.background = '#ec4899';
                tabUploadUrl.style.color = '#ffffff';
                tabUploadUrl.style.boxShadow = '0 2px 8px rgba(236,72,153,0.3)';

                tabUploadFile.style.background = 'transparent';
                tabUploadFile.style.color = '#64748b';
                tabUploadFile.style.boxShadow = 'none';

                formUrlUpload.style.display = 'block';
                formFileUpload.style.display = 'none';
            });
        }
    });
</script>
@endsection
