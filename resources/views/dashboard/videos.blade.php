@extends('layouts.app')

@section('title', 'ChapConnect - Manage Videos')

@section('styles')
<style>
    /* Responsive Spacing & Layout Overrides */
    .videos-main-container {
        max-width: 100%;
        width: 100%;
        margin: 15px 0;
        padding: 0 24px;
    }

    .videos-pdetails {
        background: #ffffff;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid var(--border-color, #e2e8f0);
    }

    .videos-page-header {
        background: #ffffff;
        color: #0f172a;
        padding: 16px 20px;
        border-radius: 16px;
        margin-bottom: 22px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
    }

    .videos-form-wrapper {
        max-width: 600px;
        margin: 0 auto 30px auto;
    }

    .videos-upload-card {
        background: #f8fafc;
        border: 2px dashed #cbd5e1;
        padding: 20px;
        border-radius: 16px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .videos-upload-card:focus-within,
    .videos-upload-card:hover {
        border-color: #6366f1;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.08);
    }

    .dropzone-label-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 16px 12px;
        background: #ffffff;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s ease;
        position: relative;
    }

    .dropzone-label-box:hover {
        background: #f1f5f9;
        border-color: #6366f1;
    }

    .videos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 18px;
    }

    .video-card {
        border-radius: 14px;
        overflow: hidden;
        position: relative;
        border: 1px solid #e2e8f0;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.04);
        background: #ffffff;
        display: flex;
        flex-direction: column;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .video-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.08);
    }

    .video-badge-opt {
        font-size: 0.72rem;
        font-weight: 600;
        color: #94a3b8;
        margin-left: 4px;
    }

    .tab-switcher-box {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
        background: #f1f5f9;
        padding: 5px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        width: fit-content;
        max-width: 100%;
    }

    /* Mobile Screen Enhancements (< 768px) */
    @media (max-width: 768px) {
        .videos-main-container {
            padding: 0 6px !important;
            margin: 8px 0 !important;
        }

        .videos-pdetails {
            padding: 14px 12px !important;
            border-radius: 14px !important;
        }

        .videos-page-header {
            padding: 14px 14px !important;
            border-radius: 12px !important;
            margin-bottom: 16px !important;
            gap: 8px !important;
        }

        .videos-page-title {
            font-size: 1.15rem !important;
        }

        .videos-count-badge {
            font-size: 0.76rem !important;
            padding: 4px 12px !important;
        }

        .videos-upload-card {
            padding: 14px 12px !important;
            border-radius: 12px !important;
            margin-bottom: 20px !important;
        }

        .videos-btn-submit {
            width: 100% !important;
            justify-content: center !important;
            padding: 12px 18px !important;
            font-size: 0.9rem !important;
        }

        .tab-switcher-box {
            width: 100% !important;
        }

        .tab-switcher-box button {
            flex: 1 !important;
            padding: 8px 10px !important;
            font-size: 0.8rem !important;
            justify-content: center !important;
        }

        .videos-grid {
            grid-template-columns: 1fr !important;
            gap: 14px !important;
        }

        .video-card-info {
            padding: 12px !important;
        }

        .video-card-title {
            font-size: 0.88rem !important;
        }

        .video-action-btn {
            padding: 4px 8px !important;
            font-size: 0.72rem !important;
        }
    }
</style>
@endsection

@section('content')
<main class="main admin-main-container videos-main-container">
    <div class="dashboard-container">
        <!-- Main Content Area: Videos Manager -->
        <div class="pdetails videos-pdetails">
            <!-- Page Header -->
            <div class="videos-page-header">
                <h2 class="videos-page-title" style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #0f172a; border: none; padding: 0; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-film" style="color: var(--primary);"></i> Videos
                </h2>
                <div class="videos-count-badge" style="background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.25); padding: 5px 14px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; color: #4f46e5;">
                    {{ $videos->count() }} Total
                </div>
            </div>

            <!-- Upload forms wrapper -->
            <div class="videos-form-wrapper">
                <!-- Upload Mode Switcher Tabs -->
                <div class="tab-switcher-box">
                    <button type="button" id="tabUploadFile" class="btn-tab active-tab" style="padding: 8px 18px; border-radius: 8px; font-size: 0.84rem; font-weight: 700; cursor: pointer; background: #6366f1; color: white; border: none; box-shadow: 0 2px 8px rgba(99,102,241,0.3); transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="bi bi-file-earmark-play-fill"></i> Upload File
                    </button>
                    <button type="button" id="tabUploadUrl" class="btn-tab" style="padding: 8px 18px; border-radius: 8px; font-size: 0.84rem; font-weight: 700; cursor: pointer; background: transparent; color: #64748b; border: none; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="bi bi-link-45deg"></i> Embed Link
                    </button>
                </div>

                <!-- Option A: File Upload Form -->
                <form id="formFileUpload" action="{{ route('dashboard.videos.store') }}" method="POST" enctype="multipart/form-data" class="videos-upload-card" style="margin-bottom: 25px;">
                    @csrf

                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(99,102,241,0.12); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0;">
                            <i class="bi bi-file-earmark-arrow-up-fill"></i>
                        </div>
                        <div style="flex-grow: 1;">
                            <h3 style="margin: 0; font-size: 0.98rem; font-weight: 800; color: #0f172a;">Upload Video File</h3>
                        </div>
                        <span style="font-size: 0.72rem; color: #64748b; font-weight: 600; background: #e2e8f0; padding: 2px 8px; border-radius: 10px;">Max 50MB</span>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 18px;">
                        <!-- Video Title -->
                        <div class="form-group">
                            <label for="video_title_file" style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">Title <span class="video-badge-opt">(Optional)</span></label>
                            <input type="text" id="video_title_file" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. Music Video or Showreel" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%;">
                        </div>

                        <!-- Video Caption -->
                        <div class="form-group">
                            <label for="video_caption_file" style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">Description <span class="video-badge-opt">(Optional)</span></label>
                            <textarea id="video_caption_file" name="caption" class="form-control" rows="2" placeholder="Add short notes about this clip..." style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%; line-height: 1.4;">{{ old('caption') }}</textarea>
                        </div>

                        <!-- File Input -->
                        <div class="form-group">
                            <label style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">Select Video File(s) *</label>

                            <div class="dropzone-label-box" onclick="document.getElementById('videos').click();">
                                <i class="bi bi-film" style="font-size: 1.6rem; color: #6366f1; margin-bottom: 4px;"></i>
                                <span style="font-size: 0.84rem; font-weight: 700; color: #1e293b;">Tap to choose video file(s)</span>
                                <span style="font-size: 0.72rem; color: #64748b; margin-top: 2px;">MP4, MOV, WEBM, MKV (Max 50MB)</span>
                            </div>

                            <input type="file" id="videos" name="videos[]" class="form-control" accept="video/*" multiple style="display: none;">
                            <div id="videoFileStatus" style="display: none; margin-top: 8px; font-size: 0.8rem; font-weight: 600;"></div>
                        </div>
                    </div>

                    <button type="submit" id="btnSubmitFileUpload" class="videos-btn-submit" style="padding: 10px 22px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 14px rgba(99,102,241,0.3); cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-size: 0.86rem; transition: opacity 0.2s ease;">
                        <i class="bi bi-upload"></i> Upload Video
                    </button>
                </form>

                <!-- Option B: URL Link Form -->
                <form id="formUrlUpload" action="{{ route('dashboard.videos.store') }}" method="POST" class="videos-upload-card" style="display: none; margin-bottom: 25px;">
                    @csrf

                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
                        <div id="socialIconBox" style="width: 36px; height: 36px; border-radius: 8px; background: rgba(236,72,153,0.12); color: #ec4899; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0; transition: background 0.3s, color 0.3s;">
                            <i id="socialIcon" class="bi bi-link-45deg"></i>
                        </div>
                        <div style="flex-grow: 1;">
                            <h3 style="margin: 0; font-size: 0.98rem; font-weight: 800; color: #0f172a;">Embed Social Link</h3>
                        </div>
                    </div>

                    <!-- Platform pills -->
                    <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 14px;">
                        <span style="display: inline-flex; align-items: center; gap: 4px; background: #fff3f3; color: #ff0000; border: 1px solid #ffcccc; border-radius: 16px; padding: 3px 10px; font-size: 0.74rem; font-weight: 700;"><i class="bi bi-youtube"></i> YouTube</span>
                        <span style="display: inline-flex; align-items: center; gap: 4px; background: #fff0f9; color: #c13584; border: 1px solid #f5c6e8; border-radius: 16px; padding: 3px 10px; font-size: 0.74rem; font-weight: 700;"><i class="bi bi-instagram"></i> Instagram</span>
                        <span style="display: inline-flex; align-items: center; gap: 4px; background: #f0f2ff; color: #1877F2; border: 1px solid #c3cdfb; border-radius: 16px; padding: 3px 10px; font-size: 0.74rem; font-weight: 700;"><i class="bi bi-facebook"></i> Facebook</span>
                        <span style="display: inline-flex; align-items: center; gap: 4px; background: #f0fffe; color: #010101; border: 1px solid #a0e9e5; border-radius: 16px; padding: 3px 10px; font-size: 0.74rem; font-weight: 700;"><i class="bi bi-tiktok"></i> TikTok</span>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 18px;">
                        <!-- Video Title -->
                        <div class="form-group">
                            <label for="video_title_url" style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">Title <span class="video-badge-opt">(Optional)</span></label>
                            <input type="text" id="video_title_url" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. YouTube Live Performance" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%;">
                        </div>

                        <!-- Video Caption -->
                        <div class="form-group">
                            <label for="video_caption_url" style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">Description <span class="video-badge-opt">(Optional)</span></label>
                            <textarea id="video_caption_url" name="caption" class="form-control" rows="2" placeholder="Add short notes about this link..." style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%; line-height: 1.4;">{{ old('caption') }}</textarea>
                        </div>

                        <!-- URL Input -->
                        <div class="form-group">
                            <label for="video_url" style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">Video Link URL *</label>
                            <input type="url" id="video_url" name="video_url" class="form-control" value="{{ old('video_url') }}" placeholder="Paste YouTube, Instagram, TikTok link..." style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%;">
                            <div id="videoUrlStatus" style="margin-top: 6px; font-size: 0.8rem; font-weight: 600; display: flex; align-items: center; gap: 6px; min-height: 22px;"></div>
                        </div>
                    </div>

                    <button type="submit" id="btnSubmitUrlUpload" class="videos-btn-submit" style="padding: 10px 22px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%); border: none; color: #fff; box-shadow: 0 4px 14px rgba(236,72,153,0.3); cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-size: 0.86rem; transition: opacity 0.2s ease;">
                        <i class="bi bi-plus-circle-fill"></i> Add Video Link
                    </button>
                </form>
            </div>

            <h3 style="font-size: 0.98rem; font-weight: 800; color: #0f172a; margin-bottom: 14px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px; display: flex; align-items: center; justify-content: space-between;">
                <span><i class="bi bi-collection-play-fill" style="color: #6366f1; margin-right: 6px;"></i> Your Videos</span>
                <span style="font-size: 0.78rem; font-weight: 600; color: #64748b;">{{ $videos->count() }} items</span>
            </h3>

            <!-- Current Videos List -->
            <div class="videos-grid">
                @forelse($videos as $video)
                <div class="video-card">
                    <div style="width: 100%; background: #0f172a; position: relative;">
                        {!! \App\Helpers\VideoHelper::renderEmbed($video->file_path) !!}
                    </div>

                    <div class="video-card-info" style="padding: 14px; background: #ffffff; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; gap: 8px;">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px; margin-bottom: 4px;">
                                <h4 class="video-card-title" style="font-size: 0.9rem; font-weight: 700; color: #0f172a; margin: 0; line-height: 1.3;">{{ $video->title ?: 'Portfolio Video' }}</h4>
                                <div style="display: flex; align-items: center; gap: 4px; flex-shrink: 0;">
                                    <button type="button" class="video-action-btn" onclick="openEditVideoModal({{ $video->id }}, '{{ addslashes($video->title ?? '') }}', '{{ addslashes($video->content ?? '') }}', '{{ str_starts_with($video->file_path, 'http') ? addslashes($video->file_path) : '' }}')" style="padding: 4px 10px; font-size: 0.74rem; font-weight: 700; background: #6366f1; color: #ffffff; border: none; border-radius: 7px; cursor: pointer; display: inline-flex; align-items: center; gap: 3px; box-shadow: 0 2px 6px rgba(99,102,241,0.25);" title="Edit Video">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <form action="{{ route('dashboard.videos.delete', $video->id) }}" method="POST" onsubmit="return confirm('Delete this video?');" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="video-action-btn" style="padding: 4px 10px; font-size: 0.74rem; font-weight: 700; background: #ef4444; color: #ffffff; border: none; border-radius: 7px; cursor: pointer; display: inline-flex; align-items: center; gap: 3px; box-shadow: 0 2px 6px rgba(239,68,68,0.25);" title="Delete Video">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @if($video->content)
                            <p style="font-size: 0.78rem; color: #475569; margin: 0 0 4px 0; line-height: 1.35;">{{ $video->content }}</p>
                            @endif
                        </div>
                        <span style="font-size: 0.72rem; color: #64748b; font-weight: 600;"><i class="bi bi-clock"></i> {{ $video->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div style="grid-column: 1/-1; padding: 40px 15px; text-align: center; background: #f8fafc; border-radius: 14px; border: 1px dashed #cbd5e1;">
                    <i class="bi bi-film" style="font-size: 2.2rem; color: #94a3b8; display: block; margin-bottom: 8px;"></i>
                    <p style="margin: 0; color: #64748b; font-weight: 600; font-size: 0.88rem;">No videos added yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</main>

<!-- Edit Video Modal -->
<div id="editVideoModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15,23,42,0.65); backdrop-filter: blur(5px); z-index: 99999; justify-content: center; align-items: center; padding: 15px;">
    <div style="background: #ffffff; border-radius: 16px; width: 100%; max-width: 480px; padding: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); position: relative; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
            <h3 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                <i class="bi bi-pencil-square" style="color: #6366f1;"></i> Edit Video
            </h3>
            <button type="button" onclick="$('#editVideoModal').fadeOut(200);" style="background: none; border: none; font-size: 1.3rem; color: #64748b; cursor: pointer;">&times;</button>
        </div>

        <form id="editVideoForm" action="" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 18px;">
                <!-- Title -->
                <div class="form-group">
                    <label for="edit_video_title" style="display: block; font-weight: 700; font-size: 0.8rem; color: #334155; margin-bottom: 4px;">Title</label>
                    <input type="text" id="edit_video_title" name="title" class="form-control" placeholder="e.g. Official Music Video" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%;">
                </div>

                <!-- Caption -->
                <div class="form-group">
                    <label for="edit_video_caption" style="display: block; font-weight: 700; font-size: 0.8rem; color: #334155; margin-bottom: 4px;">Description</label>
                    <textarea id="edit_video_caption" name="caption" class="form-control" rows="2" placeholder="Write short description..." style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%; line-height: 1.4;"></textarea>
                </div>

                <!-- Video URL Link -->
                <div class="form-group">
                    <label for="edit_video_url" style="display: block; font-weight: 700; font-size: 0.8rem; color: #334155; margin-bottom: 4px;">Update Video Link (Optional)</label>
                    <input type="url" id="edit_video_url" name="video_url" class="form-control" placeholder="Paste YouTube link..." style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%;">
                    <div id="editVideoUrlStatus" style="display: none; margin-top: 5px; font-size: 0.78rem; font-weight: 600;"></div>
                </div>

                <!-- Or Replace File -->
                <div class="form-group">
                    <label for="edit_video_file" style="display: block; font-weight: 700; font-size: 0.8rem; color: #334155; margin-bottom: 4px;">Or Replace Video File (Optional)</label>
                    <input type="file" id="edit_video_file" name="video" class="form-control" accept="video/*" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 7px 10px; font-size: 0.84rem; width: 100%;">
                    <p style="font-size: 0.72rem; color: #64748b; margin-top: 3px;">Max 50MB limit.</p>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" onclick="$('#editVideoModal').fadeOut(200);" style="padding: 8px 16px; border-radius: 8px; font-weight: 700; background: #f1f5f9; color: #475569; border: none; cursor: pointer; font-size: 0.84rem;">Cancel</button>
                <button type="submit" id="btnSubmitEditVideo" style="padding: 8px 20px; border-radius: 8px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; border: none; cursor: pointer; box-shadow: 0 3px 10px rgba(99,102,241,0.3); font-size: 0.84rem;">Save Changes</button>
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
        const videoInput = document.getElementById('videos') || document.getElementById('video');
        const statusEl = document.getElementById('videoFileStatus');
        const videoUrlInput = document.getElementById('video_url');
        const videoUrlStatusEl = document.getElementById('videoUrlStatus');
        const editVideoUrlInput = document.getElementById('edit_video_url');
        const editVideoUrlStatusEl = document.getElementById('editVideoUrlStatus');
        const btnSubmitFileUpload = document.getElementById('btnSubmitFileUpload');
        const btnSubmitUrlUpload = document.getElementById('btnSubmitUrlUpload');
        const btnSubmitEditVideo = document.getElementById('btnSubmitEditVideo');

        // Platform detection
        function detectPlatform(urlStr) {
            if (!urlStr || !urlStr.trim()) return null;
            try {
                const host = new URL(urlStr.trim()).hostname.toLowerCase();
                if (host.includes('youtube.com') || host.includes('youtu.be')) return 'youtube';
                if (host.includes('instagram.com')) return 'instagram';
                if (host.includes('facebook.com') || host.includes('fb.watch')) return 'facebook';
                if (host.includes('tiktok.com')) return 'tiktok';
                if (host.includes('vimeo.com')) return 'vimeo';
            } catch (e) {}
            return null;
        }

        const platformMeta = {
            youtube: {
                icon: 'bi-youtube',
                color: '#ff0000',
                bg: 'rgba(255,0,0,0.1)',
                label: 'YouTube',
                iconBoxBg: 'rgba(255,0,0,0.12)'
            },
            instagram: {
                icon: 'bi-instagram',
                color: '#c13584',
                bg: 'rgba(193,53,132,0.1)',
                label: 'Instagram',
                iconBoxBg: 'rgba(193,53,132,0.12)'
            },
            facebook: {
                icon: 'bi-facebook',
                color: '#1877F2',
                bg: 'rgba(24,119,242,0.1)',
                label: 'Facebook',
                iconBoxBg: 'rgba(24,119,242,0.12)'
            },
            tiktok: {
                icon: 'bi-tiktok',
                color: '#010101',
                bg: 'rgba(105,201,208,0.15)',
                label: 'TikTok',
                iconBoxBg: 'rgba(69,201,208,0.15)'
            },
            vimeo: {
                icon: 'bi-play-circle-fill',
                color: '#1ab7ea',
                bg: 'rgba(26,183,234,0.1)',
                label: 'Vimeo',
                iconBoxBg: 'rgba(26,183,234,0.12)'
            },
        };

        function isSocialUrl(urlStr) {
            return detectPlatform(urlStr) !== null;
        }

        function validateSocialInput(inputEl, statusEl, submitBtn) {
            if (!inputEl || !statusEl || !submitBtn) return;
            const val = inputEl.value.trim();
            const iconBox = document.getElementById('socialIconBox');
            const iconEl = document.getElementById('socialIcon');

            if (!val) {
                statusEl.innerHTML = '';
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
                if (iconBox && iconEl) {
                    iconBox.style.background = 'rgba(236,72,153,0.12)';
                    iconBox.style.color = '#ec4899';
                    iconEl.className = 'bi bi-link-45deg';
                }
                return;
            }

            const platform = detectPlatform(val);
            if (platform && platformMeta[platform]) {
                const meta = platformMeta[platform];
                statusEl.innerHTML = `<span style="display:inline-flex;align-items:center;gap:5px;background:${meta.bg};color:${meta.color};border-radius:20px;padding:3px 10px;font-size:0.78rem;"><i class="bi ${meta.icon}"></i> ${meta.label} detected</span>`;
                if (iconBox && iconEl) {
                    iconBox.style.background = meta.iconBoxBg;
                    iconBox.style.color = meta.color;
                    iconEl.className = 'bi ' + meta.icon;
                }
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
            } else {
                statusEl.innerHTML = `<span style="display:inline-flex;align-items:center;gap:5px;color:#ef4444;"><i class="bi bi-exclamation-triangle-fill"></i> Unsupported link.</span>`;
                if (iconBox && iconEl) {
                    iconBox.style.background = 'rgba(239,68,68,0.1)';
                    iconBox.style.color = '#ef4444';
                    iconEl.className = 'bi bi-exclamation-triangle-fill';
                }
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.6';
                submitBtn.style.cursor = 'not-allowed';
            }
        }

        if (videoUrlInput) {
            videoUrlInput.addEventListener('input', function() {
                validateSocialInput(videoUrlInput, videoUrlStatusEl, btnSubmitUrlUpload);
            });
            validateSocialInput(videoUrlInput, videoUrlStatusEl, btnSubmitUrlUpload);
        }

        if (editVideoUrlInput) {
            editVideoUrlInput.addEventListener('input', function() {
                validateSocialInput(editVideoUrlInput, editVideoUrlStatusEl, btnSubmitEditVideo);
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

            @if($errors -> has('video_url') || old('video_url'))
            showTabUrl();
            @endif
        }

        if (videoInput && statusEl && btnSubmitFileUpload) {
            videoInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                if (!files || files.length === 0) {
                    statusEl.style.display = 'none';
                    btnSubmitFileUpload.disabled = false;
                    return;
                }

                let totalSize = 0;
                let hasOversized = false;
                files.forEach(f => {
                    totalSize += f.size;
                    if (f.size > 50 * 1024 * 1024) {
                        hasOversized = true;
                    }
                });

                const totalSizeMB = (totalSize / (1024 * 1024)).toFixed(1);
                statusEl.style.display = 'block';

                if (hasOversized) {
                    statusEl.style.color = '#ef4444';
                    statusEl.innerHTML = `<i class="bi bi-exclamation-triangle-fill"></i> File exceeds 50MB limit.`;
                    btnSubmitFileUpload.disabled = true;
                    btnSubmitFileUpload.style.opacity = '0.6';
                    btnSubmitFileUpload.style.cursor = 'not-allowed';
                } else {
                    statusEl.style.color = '#10b981';
                    statusEl.innerHTML = `<i class="bi bi-check-circle-fill"></i> ${files.length} file(s) (${totalSizeMB} MB) ready.`;
                    btnSubmitFileUpload.disabled = false;
                    btnSubmitFileUpload.style.opacity = '1';
                    btnSubmitFileUpload.style.cursor = 'pointer';
                }
            });
        }

        if (formFileUpload && btnSubmitFileUpload) {
            formFileUpload.addEventListener('submit', function() {
                btnSubmitFileUpload.disabled = true;
                btnSubmitFileUpload.innerHTML = `<i class="bi bi-hourglass-split"></i> Uploading...`;
                btnSubmitFileUpload.style.opacity = '0.7';
            });
        }

        if (formUrlUpload && btnSubmitUrlUpload) {
            formUrlUpload.addEventListener('submit', function(e) {
                if (!isSocialUrl(videoUrlInput.value)) {
                    e.preventDefault();
                    validateSocialInput(videoUrlInput, videoUrlStatusEl, btnSubmitUrlUpload);
                    return false;
                }
                btnSubmitUrlUpload.disabled = true;
                btnSubmitUrlUpload.innerHTML = `<i class="bi bi-hourglass-split"></i> Saving...`;
                btnSubmitUrlUpload.style.opacity = '0.7';
            });
        }
    });
</script>
@endsection
