@extends('layouts.app')

@section('title', 'ChapConnect - Manage Videos')

@section('styles')
<style>
    @keyframes spinIcon {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .spin-icon {
        animation: spinIcon 0.9s linear infinite;
        display: inline-block;
    }

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
                    <i class="bi bi-film" style="color: var(--primary);"></i> {{ __('Videos') }}
                </h2>
                <div class="videos-count-badge" style="background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.25); padding: 5px 14px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; color: #4f46e5;">
                    {{ $videos->count() }} {{ __('Total') }}
                </div>
            </div>

            <!-- Flash Message Alerts -->
            @if(session('success'))
            <div class="video-flash-alert alert-success" style="background: #ecfdf5; border: 1.5px solid #10b981; border-radius: 14px; padding: 16px 20px; margin-bottom: 22px; display: flex; align-items: center; justify-content: space-between; color: #065f46; box-shadow: 0 4px 14px rgba(16,185,129,0.15);">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 42px; height: 42px; border-radius: 12px; background: #10b981; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0; box-shadow: 0 3px 8px rgba(16,185,129,0.3);">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-weight: 800; font-size: 1rem; color: #064e3b;">Success!</h4>
                        <p style="margin: 3px 0 0 0; font-size: 0.86rem; font-weight: 600; color: #047857;">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; font-size: 1.4rem; color: #047857; cursor: pointer; line-height: 1; padding: 0 5px;">&times;</button>
            </div>
            @endif

            @if(session('warning'))
            <div class="video-flash-alert alert-warning" style="background: #fffbe6; border: 1.5px solid #f59e0b; border-radius: 14px; padding: 16px 20px; margin-bottom: 22px; display: flex; align-items: center; justify-content: space-between; color: #92400e; box-shadow: 0 4px 14px rgba(245,158,11,0.15);">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 42px; height: 42px; border-radius: 12px; background: #f59e0b; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0; box-shadow: 0 3px 8px rgba(245,158,11,0.3);">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-weight: 800; font-size: 1rem; color: #78350f;">Notice</h4>
                        <p style="margin: 3px 0 0 0; font-size: 0.86rem; font-weight: 600; color: #92400e;">{{ session('warning') }}</p>
                    </div>
                </div>
                <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; font-size: 1.4rem; color: #92400e; cursor: pointer; line-height: 1; padding: 0 5px;">&times;</button>
            </div>
            @endif

            @if($errors->any())
            <div class="video-flash-alert alert-danger" style="background: #fef2f2; border: 1.5px solid #ef4444; border-radius: 14px; padding: 16px 20px; margin-bottom: 22px; color: #991b1b; box-shadow: 0 4px 14px rgba(239,68,68,0.15);">
                <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 8px;">
                    <div style="width: 42px; height: 42px; border-radius: 12px; background: #ef4444; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0; box-shadow: 0 3px 8px rgba(239,68,68,0.3);">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-weight: 800; font-size: 1rem; color: #7f1d1d;">Upload Error</h4>
                        <p style="margin: 2px 0 0 0; font-size: 0.84rem; font-weight: 600; color: #b91c1c;">Please review the errors below:</p>
                    </div>
                </div>
                <ul style="margin: 6px 0 0 56px; padding: 0; font-size: 0.84rem; color: #991b1b; font-weight: 600;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Upload forms wrapper -->
            <div class="videos-form-wrapper">
                <!-- Quick Jump / Info Banner -->
                <div class="tab-switcher-box" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid #cbd5e1; border-radius: 14px; padding: 10px 14px; margin-bottom: 22px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 0.84rem; font-weight: 700; color: #1e293b;">
                        <i class="bi bi-info-circle-fill" style="color: #ec4899; font-size: 1.1rem;"></i>
                        <span>{{ __('Choose your video submission method below:') }}</span>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <a href="#formUrlUpload" class="btn-tab" style="padding: 6px 14px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; text-decoration: none; background: #fce7f3; color: #be185d; border: 1px solid #fbcfe8; display: inline-flex; align-items: center; gap: 5px;">
                            <i class="bi bi-link-45deg"></i> {{ __('Paste Link') }}
                        </a>
                        <a href="#formFileUpload" class="btn-tab" style="padding: 6px 14px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; text-decoration: none; background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; display: inline-flex; align-items: center; gap: 5px;">
                            <i class="bi bi-file-earmark-arrow-up-fill"></i> {{ __('Upload File') }}
                        </a>
                    </div>
                </div>

                <!-- Option A: Social Video Link Form (DISPLAYED FIRST & ALWAYS VISIBLE) -->
                <form id="formUrlUpload" action="{{ route('dashboard.videos.store') }}" method="POST" class="videos-upload-card" style="margin-bottom: 25px; border: 2px solid #f472b6; background: #fff5f8; border-radius: 16px; padding: 20px; box-shadow: 0 4px 15px rgba(236,72,153,0.08);">
                    @csrf

                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 14px; border-bottom: 1px solid #fbcfe8; padding-bottom: 12px; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div id="socialIconBox" style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #ec4899 0%, #d946ef 100%); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; box-shadow: 0 3px 8px rgba(236,72,153,0.3);">
                                <i id="socialIcon" class="bi bi-link-45deg"></i>
                            </div>
                            <div>
                                <h3 style="margin: 0; font-size: 1.02rem; font-weight: 800; color: #831843;">{{ __('Option 1: Add Video via Link') }}</h3>
                                <p style="margin: 2px 0 0 0; font-size: 0.78rem; color: #9d174d; font-weight: 600;">{{ __('Paste link from YouTube, TikTok, Instagram, Facebook, or Vimeo') }}</p>
                            </div>
                        </div>
                        <span style="font-size: 0.74rem; font-weight: 800; background: #fbcfe8; color: #be185d; padding: 4px 12px; border-radius: 20px; border: 1px solid #f472b6;">{{ __('⚡ FAST & EASY (Recommended)') }}</span>
                    </div>

                    <!-- Platform pills -->
                    <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px; background: #ffffff; padding: 10px 12px; border-radius: 10px; border: 1px solid #fbcfe8;">
                        <span style="font-size: 0.76rem; font-weight: 700; color: #831843; display: flex; align-items: center; gap: 4px; margin-right: 4px;">{{ __('Supported Platforms:') }}</span>
                        <span style="display: inline-flex; align-items: center; gap: 4px; background: #fff3f3; color: #ff0000; border: 1px solid #ffcccc; border-radius: 16px; padding: 4px 12px; font-size: 0.76rem; font-weight: 700;"><i class="bi bi-youtube" style="font-size: 0.9rem;"></i> YouTube</span>
                        <span style="display: inline-flex; align-items: center; gap: 4px; background: #fff0f9; color: #c13584; border: 1px solid #f5c6e8; border-radius: 16px; padding: 4px 12px; font-size: 0.76rem; font-weight: 700;"><i class="bi bi-instagram" style="font-size: 0.9rem;"></i> Instagram</span>
                        <span style="display: inline-flex; align-items: center; gap: 4px; background: #f0fffe; color: #010101; border: 1px solid #a0e9e5; border-radius: 16px; padding: 4px 12px; font-size: 0.76rem; font-weight: 700;"><i class="bi bi-tiktok" style="font-size: 0.9rem;"></i> TikTok</span>
                        <span style="display: inline-flex; align-items: center; gap: 4px; background: #f0f2ff; color: #1877F2; border: 1px solid #c3cdfb; border-radius: 16px; padding: 4px 12px; font-size: 0.76rem; font-weight: 700;"><i class="bi bi-facebook" style="font-size: 0.9rem;"></i> Facebook</span>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 18px;">
                        <!-- URL Input -->
                        <div class="form-group">
                            <label for="video_url" style="display: block; font-weight: 800; font-size: 0.86rem; color: #831843; margin-bottom: 6px;">{{ __('Video Link URL *') }}</label>
                            <input type="url" id="video_url" name="video_url" class="form-control" value="{{ old('video_url') }}" placeholder="{{ __('Paste YouTube, TikTok, or Instagram video link here... (e.g. https://www.youtube.com/watch?v=...)') }}" style="background: #ffffff; color: #1e293b; border: 2px solid #f472b6; border-radius: 10px; padding: 11px 14px; font-size: 0.9rem; width: 100%; font-weight: 600;">
                            <div id="videoUrlStatus" style="margin-top: 6px; font-size: 0.8rem; font-weight: 600; display: flex; align-items: center; gap: 6px; min-height: 22px;"></div>
                        </div>

                        <!-- Video Title -->
                        <div class="form-group">
                            <label for="video_title_url" style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">{{ __('Title') }} <span class="video-badge-opt">{{ __('(Optional)') }}</span></label>
                            <input type="text" id="video_title_url" name="title" class="form-control" value="{{ old('title') }}" placeholder="{{ __('e.g. Official Music Video / Performance') }}" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%;">
                        </div>

                        <!-- Video Caption -->
                        <div class="form-group">
                            <label for="video_caption_url" style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">{{ __('Description') }} <span class="video-badge-opt">{{ __('(Optional)') }}</span></label>
                            <textarea id="video_caption_url" name="caption" class="form-control" rows="2" placeholder="{{ __('Add short notes about this link...') }}" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%; line-height: 1.4;">{{ old('caption') }}</textarea>
                        </div>
                    </div>

                    <button type="submit" id="btnSubmitUrlUpload" class="videos-btn-submit" style="padding: 11px 24px; border-radius: 10px; font-weight: 800; background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%); border: none; color: #fff; box-shadow: 0 4px 14px rgba(236,72,153,0.35); cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-size: 0.9rem; transition: opacity 0.2s ease;">
                        <i class="bi bi-plus-circle-fill" style="font-size: 1.05rem;"></i> {{ __('Save & Add Video Link') }}
                    </button>
                </form>

                <!-- Option B: File Upload Form (ALSO ALWAYS VISIBLE) -->
                <form id="formFileUpload" action="{{ route('dashboard.videos.store') }}" method="POST" enctype="multipart/form-data" class="videos-upload-card" style="margin-bottom: 25px; border: 1px solid #cbd5e1; background: #ffffff; border-radius: 16px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                    @csrf

                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 16px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                                <i class="bi bi-file-earmark-arrow-up-fill"></i>
                            </div>
                            <div>
                                <h3 style="margin: 0; font-size: 1.02rem; font-weight: 800; color: #0f172a;">{{ __('Option 2: Upload Video File from Device') }}</h3>
                                <p style="margin: 2px 0 0 0; font-size: 0.78rem; color: #64748b;">{{ __('Upload MP4, MOV, WEBM, or MKV files from your device') }}</p>
                            </div>
                        </div>
                        <span style="font-size: 0.74rem; color: #475569; font-weight: 700; background: #f1f5f9; border: 1px solid #cbd5e1; padding: 4px 12px; border-radius: 20px;">{{ __('Max File Size: 100MB') }}</span>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 18px;">
                        <!-- File Input -->
                        <div class="form-group">
                            <label style="display: block; font-weight: 700; font-size: 0.84rem; color: #334155; margin-bottom: 6px;">{{ __('Select Video File(s) *') }}</label>

                            <div class="dropzone-label-box" onclick="document.getElementById('videos').click();">
                                <i class="bi bi-film" style="font-size: 1.8rem; color: #6366f1; margin-bottom: 4px;"></i>
                                <span style="font-size: 0.86rem; font-weight: 800; color: #1e293b;">{{ __('Click to browse and choose video file(s)') }}</span>
                                <span style="font-size: 0.74rem; color: #64748b; margin-top: 2px;">{{ __('Supported: MP4, MOV, WEBM, MKV (Max 100MB)') }}</span>
                            </div>

                            <input type="file" id="videos" name="videos[]" class="form-control" accept="video/*" multiple style="display: none;">
                            <div id="videoFileStatus" style="display: none; margin-top: 8px; font-size: 0.8rem; font-weight: 600;"></div>
                        </div>

                        <!-- Video Title -->
                        <div class="form-group">
                            <label for="video_title_file" style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">{{ __('Title') }} <span class="video-badge-opt">{{ __('(Optional)') }}</span></label>
                            <input type="text" id="video_title_file" name="title" class="form-control" value="{{ old('title') }}" placeholder="{{ __('e.g. Music Video or Showreel') }}" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%;">
                        </div>

                        <!-- Video Caption -->
                        <div class="form-group">
                            <label for="video_caption_file" style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">{{ __('Description') }} <span class="video-badge-opt">{{ __('(Optional)') }}</span></label>
                            <textarea id="video_caption_file" name="caption" class="form-control" rows="2" placeholder="{{ __('Add short notes about this clip...') }}" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%; line-height: 1.4;">{{ old('caption') }}</textarea>
                        </div>
                    </div>

                    <button type="submit" id="btnSubmitFileUpload" class="videos-btn-submit" style="padding: 10px 22px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 14px rgba(99,102,241,0.3); cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-size: 0.86rem; transition: opacity 0.2s ease;">
                        <i class="bi bi-upload"></i> {{ __('Upload Video File') }}
                    </button>
                </form>
            </div>

            <h3 style="font-size: 0.98rem; font-weight: 800; color: #0f172a; margin-bottom: 14px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px; display: flex; align-items: center; justify-content: space-between;">
                <span><i class="bi bi-collection-play-fill" style="color: #6366f1; margin-right: 6px;"></i> {{ __('Your Videos') }}</span>
                <span style="font-size: 0.78rem; font-weight: 600; color: #64748b;">{{ $videos->count() }} {{ __('items') }}</span>
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

                        <!-- Owner Engagement Metrics Bar -->
                        <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 6px; border-top: 1px solid #f1f5f9; font-size: 0.76rem; font-weight: 700;">
                            <span style="display: flex; align-items: center; gap: 4px; color: #ef4444;" title="Likes">
                                <i class="bi bi-heart-fill"></i> {{ $video->likes_count ?? 0 }}
                            </span>
                            <span style="display: flex; align-items: center; gap: 4px; color: #0284c7; cursor: pointer;" onclick="openOwnerMediaCommentsModal({{ $video->id }}, '{{ addslashes($video->title ?: 'Video Comments') }}')" title="View Comments">
                                <i class="bi bi-chat-dots-fill"></i> {{ $video->comments_count ?? 0 }}
                            </span>
                            <span style="display: flex; align-items: center; gap: 4px; color: #6366f1;" title="Shares">
                                <i class="bi bi-share-fill"></i> {{ $video->shares_count ?? 0 }}
                            </span>
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
                    <p style="font-size: 0.72rem; color: #64748b; margin-top: 3px;">{{ __('Max 100MB limit.') }}</p>
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

        // Both Option 1 (Video Link) and Option 2 (File Upload) cards are visible continuously

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
                    if (f.size > 100 * 1024 * 1024) {
                        hasOversized = true;
                    }
                });

                const totalSizeMB = (totalSize / (1024 * 1024)).toFixed(1);
                statusEl.style.display = 'block';

                if (hasOversized) {
                    statusEl.style.color = '#ef4444';
                    statusEl.innerHTML = `<i class="bi bi-exclamation-triangle-fill"></i> ${'File exceeds 100MB limit.'}`;
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
            formFileUpload.addEventListener('submit', function(e) {
                const fileInput = document.getElementById('videos');
                if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                    return; // Let standard form submit or browser validation handle empty file
                }

                e.preventDefault();
                const formData = new FormData(formFileUpload);
                const modal = document.getElementById('videoUploadLoaderModal');
                const progressBar = document.getElementById('loaderProgressBar');
                const progressPercent = document.getElementById('loaderProgressPercent');
                const modalTitle = document.getElementById('loaderModalTitle');
                const modalMsg = document.getElementById('loaderModalMessage');

                modalTitle.textContent = "Uploading & Processing Video...";
                modalMsg.innerHTML = "Please wait while your video file is being uploaded.<br>Large files may take a few moments. Do not refresh this page.";
                progressBar.style.width = "0%";
                progressPercent.textContent = "0% (0 MB / 0 MB)";
                modal.style.display = 'flex';

                btnSubmitFileUpload.disabled = true;
                btnSubmitFileUpload.style.opacity = '0.75';
                btnSubmitFileUpload.style.cursor = 'not-allowed';
                btnSubmitFileUpload.innerHTML = `<i class="bi bi-arrow-repeat spin-icon"></i> ${'{{ __("Uploading Video... Please Wait") }}'}`;

                $.ajax({
                    url: formFileUpload.action,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    xhr: function() {
                        const xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener('progress', function(evt) {
                            if (evt.lengthComputable) {
                                const percentComplete = Math.round((evt.loaded / evt.total) * 100);
                                const loadedMB = (evt.loaded / (1024 * 1024)).toFixed(1);
                                const totalMB = (evt.total / (1024 * 1024)).toFixed(1);

                                progressBar.style.width = percentComplete + '%';
                                progressPercent.textContent = `${percentComplete}% (${loadedMB} MB / ${totalMB} MB)`;

                                if (percentComplete >= 100) {
                                    modalTitle.textContent = "Processing Video File...";
                                    modalMsg.innerHTML = "Upload complete! Finalizing video details on server...<br>Please wait a moment.";
                                }
                            }
                        }, false);
                        return xhr;
                    },
                    success: function(res) {
                        if (typeof res === 'string' || !res || res.success === false) {
                            modal.style.display = 'none';
                            btnSubmitFileUpload.disabled = false;
                            btnSubmitFileUpload.style.opacity = '1';
                            btnSubmitFileUpload.style.cursor = 'pointer';
                            btnSubmitFileUpload.innerHTML = `<i class="bi bi-upload"></i> ${'{{ __("Upload Video File") }}'}`;
                            let msg = (res && res.message) ? res.message : "Server error or upload limit exceeded. Please select a smaller clip.";
                            alert("Upload Failed: " + msg);
                            return;
                        }
                        progressBar.style.width = "100%";
                        progressPercent.textContent = "100% - Complete!";
                        modalTitle.textContent = "🎉 Upload Successful!";
                        modalMsg.textContent = res.message || "Your video file has been uploaded successfully.";
                        btnSubmitFileUpload.innerHTML = `<i class="bi bi-check-circle-fill"></i> ${'{{ __("Uploaded! Reloading...") }}'}`;
                        setTimeout(function() {
                            window.location.reload();
                        }, 500);
                    },
                    error: function(err) {
                        modal.style.display = 'none';
                        btnSubmitFileUpload.disabled = false;
                        btnSubmitFileUpload.style.opacity = '1';
                        btnSubmitFileUpload.style.cursor = 'pointer';
                        btnSubmitFileUpload.innerHTML = `<i class="bi bi-upload"></i> ${'{{ __("Upload Video File") }}'}`;
                        let errMsg = "An error occurred while uploading your video file.";
                        if (err.status === 413) {
                            errMsg = "The video file exceeds web server limits (HTTP 413). Please select a smaller clip or ask your server administrator to increase Nginx client_max_body_size / PHP post_max_size.";
                        } else if (err.responseJSON && err.responseJSON.message) {
                            errMsg = err.responseJSON.message;
                        } else if (err.responseJSON && err.responseJSON.errors) {
                            errMsg = Object.values(err.responseJSON.errors).flat().join(' ');
                        }
                        alert("Upload Failed: " + errMsg);
                    }
                });
            });
        }

        if (formUrlUpload && btnSubmitUrlUpload) {
            formUrlUpload.addEventListener('submit', function(e) {
                if (!isSocialUrl(videoUrlInput.value)) {
                    e.preventDefault();
                    validateSocialInput(videoUrlInput, videoUrlStatusEl, btnSubmitUrlUpload);
                    return false;
                }
                const modal = document.getElementById('videoUploadLoaderModal');
                const modalTitle = document.getElementById('loaderModalTitle');
                const modalMsg = document.getElementById('loaderModalMessage');
                const progressBar = document.getElementById('loaderProgressBar');
                const progressPercent = document.getElementById('loaderProgressPercent');

                modalTitle.textContent = "Adding Video Link...";
                modalMsg.textContent = "Validating and adding video link to your portfolio...";
                progressBar.style.width = "100%";
                progressPercent.textContent = "Processing...";
                modal.style.display = 'flex';
                btnSubmitUrlUpload.disabled = true;
                btnSubmitUrlUpload.style.opacity = '0.75';
                btnSubmitUrlUpload.innerHTML = `<i class="bi bi-arrow-repeat spin-icon"></i> ${'{{ __("Processing Link...") }}'}`;
            });
        }
    });

    function openOwnerMediaCommentsModal(id, title) {
        $('#ownerCommentsModalTitle').text(title || 'Video Comments');
        $('#ownerCommentsList').html('<div style="text-align: center; padding: 25px; color: #94a3b8;"><i class="bi bi-hourglass-split"></i> Loading comments...</div>');
        $('#ownerCommentsModal').css('display', 'flex').hide().fadeIn(200);

        $.ajax({
            url: '/media/' + id + '/comments',
            type: 'GET',
            success: function(res) {
                if (res.success) {
                    const list = $('#ownerCommentsList');
                    if (!res.comments || res.comments.length === 0) {
                        list.html('<div style="text-align: center; padding: 25px; color: #94a3b8;"><i class="bi bi-chat-left-text" style="font-size: 1.8rem; display: block; margin-bottom: 6px;"></i>No comments left on this video yet.</div>');
                        return;
                    }
                    let html = '';
                    res.comments.forEach(c => {
                        const avatar = c.user_avatar || "{{ asset('images/default-avatar.png') }}";
                        html += `
                            <div style="background: #f8fafc; border-radius: 10px; padding: 10px 12px; border: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                                <div style="flex-grow: 1;">
                                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 3px;">
                                        <img src="${avatar}" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover;">
                                        <span style="font-weight: 700; font-size: 0.82rem; color: #0f172a;">${c.author_name}</span>
                                        <span style="font-size: 0.70rem; color: #94a3b8;">${c.created_at_human}</span>
                                    </div>
                                    <p style="margin: 0; font-size: 0.82rem; color: #334155;">${c.comment}</p>
                                </div>
                                <button type="button" onclick="deleteOwnerComment(${c.id}, ${id})" style="background: rgba(239,68,68,0.1); color: #ef4444; border: none; padding: 3px 8px; border-radius: 6px; font-size: 0.72rem; cursor: pointer;" title="Delete Comment">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        `;
                    });
                    list.html(html);
                }
            }
        });
    }

    function deleteOwnerComment(commentId, mediaId) {
        if (!confirm('Delete this comment?')) return;
        $.ajax({
            url: '/media/comment/' + commentId,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                if (res.success) {
                    openOwnerMediaCommentsModal(mediaId, $('#ownerCommentsModalTitle').text());
                }
            }
        });
    }
</script>

<!-- Owner Media Comments Modal -->
<div id="ownerCommentsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15,23,42,0.65); backdrop-filter: blur(5px); z-index: 99999; justify-content: center; align-items: center; padding: 15px;">
    <div style="background: #ffffff; border-radius: 16px; width: 100%; max-width: 480px; padding: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.25); position: relative; max-height: 85vh; display: flex; flex-direction: column;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
            <h3 style="margin: 0; font-size: 1.02rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                <i class="bi bi-chat-dots-fill" style="color: #0284c7;"></i> <span id="ownerCommentsModalTitle">Video Comments</span>
            </h3>
            <button type="button" onclick="$('#ownerCommentsModal').fadeOut(200);" style="background: none; border: none; font-size: 1.3rem; color: #64748b; cursor: pointer;">&times;</button>
        </div>

        <div id="ownerCommentsList" style="flex-grow: 1; overflow-y: auto; max-height: 400px; display: flex; flex-direction: column; gap: 10px;">
            <div style="text-align: center; padding: 25px; color: #94a3b8;"><i class="bi bi-hourglass-split"></i> Loading comments...</div>
        </div>
    </div>
<!-- Full-Screen Video Upload Processing Loader Modal Overlay -->
<div id="videoUploadLoaderModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.88); backdrop-filter: blur(10px); z-index: 999999; justify-content: center; align-items: center; padding: 20px;">
    <div style="background: #ffffff; width: 100%; max-width: 460px; border-radius: 20px; padding: 32px 24px; text-align: center; box-shadow: 0 25px 60px rgba(0,0,0,0.5); border: 2px solid rgba(99,102,241,0.3); position: relative;">
        <!-- Animated Spinner & Video Reel Icon -->
        <div style="position: relative; width: 80px; height: 80px; margin: 0 auto 20px auto; display: flex; align-items: center; justify-content: center;">
            <div class="spinner-border text-primary" role="status" style="width: 80px; height: 80px; border-width: 5px; color: #6366f1 !important; border-top-color: #ec4899 !important;"></div>
            <i class="bi bi-film" style="position: absolute; font-size: 1.8rem; color: #6366f1;"></i>
        </div>

        <h3 id="loaderModalTitle" style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #0f172a;">{{ __('Uploading Video File...') }}</h3>
        <p id="loaderModalMessage" style="margin: 10px 0 0 0; font-size: 0.88rem; color: #475569; line-height: 1.5; font-weight: 600;">
            {!! __('Please wait while your video is uploading and processing.<br>Large files may take a few moments. Do not refresh this page.') !!}
        </p>

        <!-- Dynamic Real-time Progress Bar -->
        <div id="loaderProgressBarContainer" style="margin-top: 22px; background: #e2e8f0; border-radius: 12px; height: 12px; overflow: hidden; position: relative;">
            <div id="loaderProgressBar" style="width: 0%; height: 100%; background: linear-gradient(90deg, #6366f1 0%, #ec4899 100%); transition: width 0.2s ease; border-radius: 12px;"></div>
        </div>

        <div id="loaderProgressPercent" style="margin-top: 8px; font-size: 0.86rem; font-weight: 800; color: #4f46e5;">
            0% (0 MB / 0 MB)
        </div>

        <div style="margin-top: 18px; padding: 10px 14px; background: #f1f5f9; border-radius: 10px; border: 1px dashed #cbd5e1; font-size: 0.78rem; color: #64748b; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 6px;">
            <i class="bi bi-shield-lock-fill" style="color: #6366f1;"></i>
            <span>{{ __('Do not close or refresh this browser page') }}</span>
        </div>
    </div>
</div>
@endsection