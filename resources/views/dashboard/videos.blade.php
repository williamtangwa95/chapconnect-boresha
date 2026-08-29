@extends('layouts.app')

@section('title', 'ChapConnect - Manage Videos')

@section('content')
<main class="main admin-main-container" style="max-width: 100%; width: 100%; margin: 15px 0; padding: 0 30px;">
    <div class="dashboard-container">
        <!-- Main Content Area: Videos Manager -->
        <div class="pdetails" style="background: #ffffff; border-radius: 16px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
        <!-- Page Header -->
        <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; padding: 24px 28px; border-radius: 14px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <div>
                <h2 style="margin: 0 0 6px 0; font-size: 1.35rem; font-weight: 800; color: #ffffff; border: none; padding: 0; display: flex; align-items: center; gap: 10px;">
                    <i class="bi bi-film" style="color: #6366f1;"></i> Portfolio Video Showreels
                </h2>
                <p style="margin: 0; color: #94a3b8; font-size: 0.88rem;">Upload video files or embed YouTube links with custom titles and captions.</p>
            </div>
            <div style="background: rgba(99,102,241,0.2); border: 1px solid rgba(99,102,241,0.4); padding: 6px 16px; border-radius: 20px; font-weight: 700; font-size: 0.82rem; color: #a5b4fc;">
                Total Videos: {{ $videos->count() }}
            </div>
        </div>
        
        <!-- Upload forms wrapper -->
        <div style="max-width: 640px; margin: 0 auto 35px auto;">
            <!-- Upload Mode Switcher Tabs -->
            <div style="display: flex; gap: 10px; margin-bottom: 20px; background: #f1f5f9; padding: 6px; border-radius: 12px; border: 1px solid #e2e8f0; width: fit-content; max-width: 100%; flex-wrap: wrap;">
                <button type="button" id="tabUploadFile" class="btn-tab active-tab" style="padding: 9px 20px; border-radius: 8px; font-size: 0.88rem; font-weight: 700; cursor: pointer; background: #6366f1; color: white; border: none; box-shadow: 0 2px 8px rgba(99,102,241,0.3); transition: all 0.2s ease;">
                    <i class="bi bi-file-earmark-play-fill"></i> Upload File
                </button>
                <button type="button" id="tabUploadUrl" class="btn-tab" style="padding: 9px 20px; border-radius: 8px; font-size: 0.88rem; font-weight: 700; cursor: pointer; background: transparent; color: #64748b; border: none; transition: all 0.2s ease;">
                    <i class="bi bi-youtube"></i> Embed YouTube Link
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
                        <p style="margin: 0; font-size: 0.8rem; color: #64748b;">Upload MP4, MOV, AVI, WEBM, MKV, or 3GP (Max 50MB per video clip).</p>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 20px;">
                    <!-- Video Title -->
                    <div class="form-group">
                        <label for="video_title_file" style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Video Title (Optional)</label>
                        <input type="text" id="video_title_file" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. Official Music Video or Live Performance Showreel" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%;">
                    </div>

                    <!-- Video Caption -->
                    <div class="form-group">
                        <label for="video_caption_file" style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Video Description / Caption (Optional)</label>
                        <textarea id="video_caption_file" name="caption" class="form-control" rows="2" placeholder="Write a short description or notes about this video clip..." style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%; line-height: 1.5;">{{ old('caption') }}</textarea>
                    </div>

                    <!-- File Input -->
                    <div class="form-group">
                        <label for="video" style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Select Video File *</label>
                        <input type="file" id="video" name="video" class="form-control" accept="video/*" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.88rem; width: 100%;">
                        <div id="videoFileStatus" style="display: none; margin-top: 8px; font-size: 0.82rem; font-weight: 600;"></div>
                    </div>
                </div>
                
                <button type="submit" id="btnSubmitFileUpload" style="padding: 11px 26px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(99,102,241,0.35); cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-size: 0.88rem;">
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
                        <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: #0f172a;">Embed YouTube Link</h3>
                        <p style="margin: 0; font-size: 0.8rem; color: #64748b;">Paste any public YouTube video link (YouTube watch, shorts, or share link).</p>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 20px;">
                    <!-- Video Title -->
                    <div class="form-group">
                        <label for="video_title_url" style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Video Title (Optional)</label>
                        <input type="text" id="video_title_url" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. YouTube Live Performance or Concert Highlight" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%;">
                    </div>

                    <!-- Video Caption -->
                    <div class="form-group">
                        <label for="video_caption_url" style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Video Description / Caption (Optional)</label>
                        <textarea id="video_caption_url" name="caption" class="form-control" rows="2" placeholder="Write a short description or notes about this video link..." style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%; line-height: 1.5;">{{ old('caption') }}</textarea>
                    </div>

                    <!-- URL Input -->
                    <div class="form-group">
                        <label for="video_url" style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">YouTube Link URL *</label>
                        <input type="url" id="video_url" name="video_url" class="form-control" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=... or https://youtu.be/..." style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%;">
                        <div id="videoUrlStatus" style="display: none; margin-top: 8px; font-size: 0.82rem; font-weight: 600;"></div>
                    </div>
                </div>

                <button type="submit" id="btnSubmitUrlUpload" style="padding: 11px 26px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(236,72,153,0.35); cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-size: 0.88rem;">
                    <i class="bi bi-plus-circle-fill"></i> Add YouTube Video Link
                </button>
            </form>
        </div>

        <h3 style="font-size: 1.05rem; font-weight: 700; color: #0f172a; margin-bottom: 18px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; display: flex; align-items: center; justify-content: space-between;">
            <span><i class="bi bi-collection-play-fill" style="color: #6366f1; margin-right: 6px;"></i> Your Video Assets</span>
            <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">{{ $videos->count() }} items</span>
        </h3>
        
        <!-- Current Videos List -->
        <div class="videos-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 22px;">
            @forelse($videos as $video)
                <div class="video-item" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; flex-direction: column;">
                    <div style="width: 100%; background: #0f172a; position: relative;">
                        {!! \App\Helpers\VideoHelper::renderEmbed($video->file_path) !!}
                    </div>
                    
                    <div style="padding: 16px; background: #ffffff; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; gap: 10px;">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 4px;">
                                <h4 style="font-size: 0.95rem; font-weight: 700; color: #0f172a; margin: 0;">{{ $video->title ?: 'Portfolio Video' }}</h4>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <button type="button" onclick="openEditVideoModal({{ $video->id }}, '{{ addslashes($video->title ?? '') }}', '{{ addslashes($video->content ?? '') }}', '{{ str_starts_with($video->file_path, 'http') ? addslashes($video->file_path) : '' }}')" style="padding: 5px 12px; font-size: 0.76rem; font-weight: 700; background: #6366f1; color: #ffffff; border: none; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 8px rgba(99,102,241,0.3);">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <form action="{{ route('dashboard.videos.delete', $video->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this video?');" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="padding: 5px 12px; font-size: 0.76rem; font-weight: 700; background: #ef4444; color: #ffffff; border: none; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 8px rgba(239,68,68,0.3);">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
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

<!-- Edit Video Modal -->
<div id="editVideoModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15,23,42,0.65); backdrop-filter: blur(5px); z-index: 99999; justify-content: center; align-items: center; padding: 20px;">
    <div style="background: #ffffff; border-radius: 16px; width: 100%; max-width: 540px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); position: relative; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-pencil-square" style="color: #6366f1;"></i> Edit Video Details
            </h3>
            <button type="button" onclick="$('#editVideoModal').fadeOut(200);" style="background: none; border: none; font-size: 1.4rem; color: #64748b; cursor: pointer;">&times;</button>
        </div>

        <form id="editVideoForm" action="" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 20px;">
                <!-- Title -->
                <div class="form-group">
                    <label for="edit_video_title" style="display: block; font-weight: 700; font-size: 0.84rem; color: #334155; margin-bottom: 6px;">Video Title</label>
                    <input type="text" id="edit_video_title" name="title" class="form-control" placeholder="e.g. Official Music Video" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%;">
                </div>

                <!-- Caption -->
                <div class="form-group">
                    <label for="edit_video_caption" style="display: block; font-weight: 700; font-size: 0.84rem; color: #334155; margin-bottom: 6px;">Video Description / Caption</label>
                    <textarea id="edit_video_caption" name="caption" class="form-control" rows="3" placeholder="Write a short description..." style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%; line-height: 1.5;"></textarea>
                </div>

                <!-- Video URL Link -->
                <div class="form-group">
                    <label for="edit_video_url" style="display: block; font-weight: 700; font-size: 0.84rem; color: #334155; margin-bottom: 6px;">Update YouTube Video Link (Optional)</label>
                    <input type="url" id="edit_video_url" name="video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=... or https://youtu.be/..." style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%;">
                    <div id="editVideoUrlStatus" style="display: none; margin-top: 6px; font-size: 0.8rem; font-weight: 600;"></div>
                </div>

                <!-- Or Replace File -->
                <div class="form-group">
                    <label for="edit_video_file" style="display: block; font-weight: 700; font-size: 0.84rem; color: #334155; margin-bottom: 6px;">Or Replace Video File (Optional)</label>
                    <input type="file" id="edit_video_file" name="video" class="form-control" accept="video/*" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 8px 12px; font-size: 0.88rem; width: 100%;">
                    <p style="font-size: 0.76rem; color: #64748b; margin-top: 4px;">Leave empty to keep existing video file. Max 50MB.</p>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="$('#editVideoModal').fadeOut(200);" style="padding: 10px 20px; border-radius: 10px; font-weight: 700; background: #f1f5f9; color: #475569; border: none; cursor: pointer;">Cancel</button>
                <button type="submit" id="btnSubmitEditVideo" style="padding: 10px 24px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(99,102,241,0.35);">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openEditVideoModal(id, title, caption, videoUrl) {
        $('#editVideoForm').attr('action', '/dashboard/videos/' + id + '/update');
        $('#edit_video_title').val(title);
        $('#edit_video_caption').val(caption);
        $('#edit_video_url').val(videoUrl);
        $('#editVideoUrlStatus').hide();
        $('#editVideoModal').css('display', 'flex').hide().fadeIn(200);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const tabUploadFile = document.getElementById('tabUploadFile');
        const tabUploadUrl = document.getElementById('tabUploadUrl');
        const formFileUpload = document.getElementById('formFileUpload');
        const formUrlUpload = document.getElementById('formUrlUpload');
        const videoInput = document.getElementById('video');
        const statusEl = document.getElementById('videoFileStatus');
        const videoUrlInput = document.getElementById('video_url');
        const videoUrlStatusEl = document.getElementById('videoUrlStatus');
        const editVideoUrlInput = document.getElementById('edit_video_url');
        const editVideoUrlStatusEl = document.getElementById('editVideoUrlStatus');
        const btnSubmitFileUpload = document.getElementById('btnSubmitFileUpload');
        const btnSubmitUrlUpload = document.getElementById('btnSubmitUrlUpload');
        const btnSubmitEditVideo = document.getElementById('btnSubmitEditVideo');

        function isYouTubeUrl(urlStr) {
            if (!urlStr || !urlStr.trim()) return true; // empty allowed unless submitting empty
            try {
                const parsed = new URL(urlStr.trim());
                const host = parsed.hostname.toLowerCase();
                const allowed = ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'music.youtube.com', 'youtu.be', 'www.youtu.be'];
                return allowed.includes(host);
            } catch (e) {
                return false;
            }
        }

        function validateYouTubeInput(inputEl, statusEl, submitBtn) {
            if (!inputEl || !statusEl || !submitBtn) return;
            const val = inputEl.value.trim();
            if (!val) {
                statusEl.style.display = 'none';
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
                return;
            }

            if (isYouTubeUrl(val)) {
                statusEl.style.display = 'block';
                statusEl.style.color = '#10b981';
                statusEl.innerHTML = `<i class="bi bi-check-circle-fill"></i> Valid YouTube video link format.`;
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
            } else {
                statusEl.style.display = 'block';
                statusEl.style.color = '#ef4444';
                statusEl.innerHTML = `<i class="bi bi-exclamation-triangle-fill"></i> Please enter a valid YouTube link (e.g. https://www.youtube.com/watch?v=... or https://youtu.be/...). No other video platforms are allowed.`;
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.6';
                submitBtn.style.cursor = 'not-allowed';
            }
        }

        if (videoUrlInput) {
            videoUrlInput.addEventListener('input', function() {
                validateYouTubeInput(videoUrlInput, videoUrlStatusEl, btnSubmitUrlUpload);
            });
        }

        if (editVideoUrlInput) {
            editVideoUrlInput.addEventListener('input', function() {
                validateYouTubeInput(editVideoUrlInput, editVideoUrlStatusEl, btnSubmitEditVideo);
            });
        }

        function showTabFile() {
            tabUploadFile.style.background = '#6366f1';
            tabUploadFile.style.color = '#ffffff';
            tabUploadFile.style.boxShadow = '0 2px 8px rgba(99,102,241,0.3)';

            tabUploadUrl.style.background = 'transparent';
            tabUploadUrl.style.color = '#64748b';
            tabUploadUrl.style.boxShadow = 'none';

            formFileUpload.style.display = 'block';
            formUrlUpload.style.display = 'none';
        }

        function showTabUrl() {
            tabUploadUrl.style.background = '#ec4899';
            tabUploadUrl.style.color = '#ffffff';
            tabUploadUrl.style.boxShadow = '0 2px 8px rgba(236,72,153,0.3)';

            tabUploadFile.style.background = 'transparent';
            tabUploadFile.style.color = '#64748b';
            tabUploadFile.style.boxShadow = 'none';

            formUrlUpload.style.display = 'block';
            formFileUpload.style.display = 'none';
        }

        if (tabUploadFile && tabUploadUrl) {
            tabUploadFile.addEventListener('click', showTabFile);
            tabUploadUrl.addEventListener('click', showTabUrl);

            // Auto switch tab if URL form had errors or filled URL
            @if($errors->has('video_url') || old('video_url'))
                showTabUrl();
            @endif
        }

        // Client-side video file size check
        if (videoInput && statusEl && btnSubmitFileUpload) {
            videoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) {
                    statusEl.style.display = 'none';
                    btnSubmitFileUpload.disabled = false;
                    return;
                }

                const sizeMB = (file.size / (1024 * 1024)).toFixed(1);
                statusEl.style.display = 'block';

                if (file.size > 50 * 1024 * 1024) {
                    statusEl.style.color = '#ef4444';
                    statusEl.innerHTML = `<i class="bi bi-exclamation-triangle-fill"></i> Selected video file (${sizeMB} MB) exceeds the 50MB maximum limit. Please select a smaller video clip or embed a YouTube link.`;
                    btnSubmitFileUpload.disabled = true;
                    btnSubmitFileUpload.style.opacity = '0.6';
                    btnSubmitFileUpload.style.cursor = 'not-allowed';
                } else {
                    statusEl.style.color = '#10b981';
                    statusEl.innerHTML = `<i class="bi bi-check-circle-fill"></i> Selected video clip (${sizeMB} MB) is ready for upload.`;
                    btnSubmitFileUpload.disabled = false;
                    btnSubmitFileUpload.style.opacity = '1';
                    btnSubmitFileUpload.style.cursor = 'pointer';
                }
            });
        }

        // Handle upload button progress indication
        if (formFileUpload && btnSubmitFileUpload) {
            formFileUpload.addEventListener('submit', function() {
                btnSubmitFileUpload.disabled = true;
                btnSubmitFileUpload.innerHTML = `<i class="bi bi-hourglass-split"></i> Uploading Video... Please wait...`;
                btnSubmitFileUpload.style.opacity = '0.7';
            });
        }

        if (formUrlUpload && btnSubmitUrlUpload) {
            formUrlUpload.addEventListener('submit', function(e) {
                if (!isYouTubeUrl(videoUrlInput.value)) {
                    e.preventDefault();
                    validateYouTubeInput(videoUrlInput, videoUrlStatusEl, btnSubmitUrlUpload);
                    return false;
                }
                btnSubmitUrlUpload.disabled = true;
                btnSubmitUrlUpload.innerHTML = `<i class="bi bi-hourglass-split"></i> Saving Link...`;
                btnSubmitUrlUpload.style.opacity = '0.7';
            });
        }
    });
</script>
@endsection
