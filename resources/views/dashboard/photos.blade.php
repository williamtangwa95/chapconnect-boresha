@extends('layouts.app')

@section('title', 'ChapConnect - Manage Photos')

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
    .photos-main-container {
        max-width: 100%;
        width: 100%;
        margin: 15px 0;
        padding: 0 24px;
    }

    .photos-pdetails {
        background: #ffffff;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid var(--border-color, #e2e8f0);
    }

    .photos-page-header {
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

    .photos-upload-card {
        max-width: 600px;
        margin: 0 auto 30px auto;
        background: #f8fafc;
        border: 2px dashed #cbd5e1;
        padding: 20px;
        border-radius: 16px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .photos-upload-card:focus-within,
    .photos-upload-card:hover {
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

    .photos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 16px;
    }

    .photo-card {
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

    .photo-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.08);
    }

    .photo-badge-opt {
        font-size: 0.72rem;
        font-weight: 600;
        color: #94a3b8;
        margin-left: 4px;
    }

    /* Mobile Screen Enhancements (< 768px) */
    @media (max-width: 768px) {
        .photos-main-container {
            padding: 0 6px !important;
            margin: 8px 0 !important;
        }

        .photos-pdetails {
            padding: 14px 12px !important;
            border-radius: 14px !important;
        }

        .photos-page-header {
            padding: 14px 14px !important;
            border-radius: 12px !important;
            margin-bottom: 16px !important;
            gap: 8px !important;
        }

        .photos-page-title {
            font-size: 1.15rem !important;
        }

        .photos-count-badge {
            font-size: 0.76rem !important;
            padding: 4px 12px !important;
        }

        .photos-upload-card {
            padding: 14px 12px !important;
            border-radius: 12px !important;
            margin-bottom: 22px !important;
        }

        .photos-btn-submit {
            width: 100% !important;
            justify-content: center !important;
            padding: 12px 18px !important;
            font-size: 0.9rem !important;
        }

        .photos-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px !important;
        }

        .photo-card-info {
            padding: 10px 12px !important;
        }

        .photo-card-title {
            font-size: 0.85rem !important;
        }

        .photo-card-caption {
            font-size: 0.76rem !important;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .photo-action-btn {
            padding: 5px 8px !important;
            font-size: 0.72rem !important;
        }

        .photo-action-btn-text {
            display: none !important;
        }
    }

    @media (max-width: 360px) {
        .photos-grid {
            grid-template-columns: 1fr !important;
        }
    }

    @keyframes progress-bar-stripes {
        0% { background-position: 1rem 0; }
        100% { background-position: 0 0; }
    }

    .progress-bar-green-animated {
        background-color: #10b981 !important;
        background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.25) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.25) 50%, rgba(255, 255, 255, 0.25) 75%, transparent 75%, transparent) !important;
        background-size: 1rem 1rem !important;
        animation: progress-bar-stripes 1s linear infinite !important;
        transition: width 0.2s ease !important;
        border-radius: 10px !important;
    }
</style>
@endsection

@section('content')
<main class="main admin-main-container photos-main-container">
    <div class="dashboard-container">
        <!-- Main Content Area: Photos Manager -->
        <div class="pdetails photos-pdetails">
            <!-- Page Header -->
            <div class="photos-page-header">
                <h2 class="photos-page-title" style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #0f172a; border: none; padding: 0; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-images" style="color: var(--primary);"></i> {{ __('Photos') }}
                </h2>
                <div class="photos-count-badge" style="background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.25); padding: 5px 14px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; color: #4f46e5;">
                    {{ $photos->count() }} {{ __('Uploaded') }}
                </div>
            </div>

            <!-- Upload Form -->
            <form id="formPhotoUpload" action="{{ route('dashboard.photos.store') }}" method="POST" enctype="multipart/form-data" class="photos-upload-card">
                @csrf

                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(99,102,241,0.12); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0;">
                        <i class="bi bi-cloud-arrow-up-fill"></i>
                    </div>
                    <div style="flex-grow: 1;">
                        <h3 style="margin: 0; font-size: 0.98rem; font-weight: 800; color: #0f172a;">{{ __('Upload Photos') }}</h3>
                    </div>
                    <span style="font-size: 0.72rem; color: #64748b; font-weight: 600; background: #e2e8f0; padding: 2px 8px; border-radius: 10px;">{{ __('Max 20MB') }}</span>
                </div>

                <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 18px;">
                    <!-- Photo Title -->
                    <div class="form-group">
                        <label for="title" style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">{{ __('Title') }} <span class="photo-badge-opt">{{ __('(Optional)') }}</span></label>
                        <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" placeholder="{{ __('e.g. Stage Performance') }}" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%;">
                    </div>

                    <!-- Photo Caption -->
                    <div class="form-group">
                        <label for="caption" style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">{{ __('Caption') }} <span class="photo-badge-opt">{{ __('(Optional)') }}</span></label>
                        <textarea id="caption" name="caption" class="form-control" rows="2" placeholder="{{ __('Add short description...') }}" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%; line-height: 1.4;">{{ old('caption') }}</textarea>
                    </div>

                    <!-- File Selection -->
                    <div class="form-group">
                        <label style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">{{ __('Select Photo(s) *') }}</label>

                        <div class="dropzone-label-box" onclick="document.getElementById('photos').click();">
                            <i class="bi bi-file-earmark-image" style="font-size: 1.6rem; color: #6366f1; margin-bottom: 4px;"></i>
                            <span style="font-size: 0.84rem; font-weight: 700; color: #1e293b;">{{ __('Tap to choose photo(s)') }}</span>
                            <span style="font-size: 0.72rem; color: #64748b; margin-top: 2px;">{{ __('JPEG, PNG, WEBP, GIF (Max 20MB)') }}</span>
                        </div>

                        <input type="file" id="photos" name="photos[]" class="form-control" accept="image/*" multiple required style="display: none;">

                        <div id="photoSizeAlert" style="display: none; margin-top: 6px; font-size: 0.8rem; font-weight: 600;"></div>

                        <!-- Live Multi-Image Preview Box -->
                        <div id="imagePreviewContainer" style="display: none; margin-top: 12px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 12px; padding: 12px; width: 100%;">
                            <div style="font-weight: 700; font-size: 0.8rem; color: #475569; margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between;">
                                <span><i class="bi bi-eye-fill" style="color: #6366f1;"></i> {{ __('Preview') }}</span>
                                <span id="selectedCountBadge" style="font-size: 0.72rem; background: rgba(99,102,241,0.1); color: #6366f1; padding: 2px 8px; border-radius: 10px; font-weight: 700;">0 {{ __('selected') }}</span>
                            </div>
                            <div id="multiPreviewGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 8px; max-height: 240px; overflow-y: auto; padding: 2px;"></div>
                        </div>
                    </div>
                </div>

                <button type="submit" id="btnSubmitPhoto" class="photos-btn-submit" style="padding: 10px 22px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 14px rgba(99,102,241,0.3); cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-size: 0.86rem; transition: opacity 0.2s ease;">
                    <i class="bi bi-upload"></i> {{ __('Upload Photos') }}
                </button>

                <!-- Inline Real-Time Upload Progress Box -->
                <div id="inlinePhotoUploadProgressBox" style="display: none; margin-top: 16px; background: #f0fdf4; border: 1.5px solid #10b981; border-radius: 14px; padding: 16px; box-shadow: 0 4px 14px rgba(16,185,129,0.15);">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <span id="inlinePhotoTitle" style="font-weight: 800; font-size: 0.88rem; color: #065f46; display: flex; align-items: center; gap: 6px;">
                            <i class="bi bi-arrow-repeat spin-icon" style="color: #10b981; font-size: 1.1rem;"></i>
                            <span>{{ __('Uploading & Compressing Photos...') }}</span>
                        </span>
                        <span id="inlinePhotoPercentBadge" style="font-weight: 800; font-size: 0.82rem; color: #047857; background: rgba(16,185,129,0.15); padding: 2px 10px; border-radius: 20px;">
                            0%
                        </span>
                    </div>

                    <!-- Dynamic Green Animated Progress Bar -->
                    <div style="background: #cbd5e1; border-radius: 10px; height: 16px; overflow: hidden; position: relative; margin-bottom: 8px; box-shadow: inset 0 1px 3px rgba(0,0,0,0.12);">
                        <div id="inlinePhotoProgressBar" class="progress-bar-green-animated" style="width: 0%; height: 100%;"></div>
                    </div>

                    <!-- Progress Counters -->
                    <div id="inlinePhotoProgressDetails" style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; font-weight: 700;">
                        <span style="color: #059669;"><i class="bi bi-arrow-up-circle-fill"></i> 0% Uploaded (0 MB / 0 MB)</span>
                        <span style="color: #d97706;"><i class="bi bi-clock-history"></i> 100% Remaining (0 MB left)</span>
                    </div>
                </div>
            </form>

            <h3 style="font-size: 0.98rem; font-weight: 800; color: #0f172a; margin-bottom: 14px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px; display: flex; align-items: center; justify-content: space-between;">
                <span><i class="bi bi-grid-3x3-gap-fill" style="color: #6366f1; margin-right: 6px;"></i> {{ __('Your Photos') }}</span>
                <span style="font-size: 0.78rem; font-weight: 600; color: #64748b;">{{ $photos->count() }} {{ __('items') }}</span>
            </h3>

            <!-- Current Uploads Grid -->
            <div class="photos-grid">
                @forelse($photos as $photo)
                <div class="photo-card">
                    <div style="width: 100%; aspect-ratio: 4/3; overflow: hidden; position: relative; background: #0f172a;">
                        <img src="{{ asset($photo->file_path) }}" alt="{{ $photo->title ?? 'Portfolio Photo' }}" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">

                        <div style="position: absolute; top: 6px; right: 6px; z-index: 2; display: flex; gap: 4px;">
                            <button type="button" class="photo-action-btn" onclick="openEditPhotoModal({{ $photo->id }}, '{{ addslashes($photo->title ?? '') }}', '{{ addslashes($photo->content ?? '') }}', '{{ asset($photo->file_path) }}')" style="padding: 5px 10px; border-radius: 7px; font-size: 0.74rem; font-weight: 700; background: rgba(99,102,241,0.9); color: #ffffff; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 3px; backdrop-filter: blur(4px); box-shadow: 0 2px 6px rgba(0,0,0,0.3);" title="Edit Photo">
                                <i class="bi bi-pencil-square"></i> <span class="photo-action-btn-text">{{ __('Edit') }}</span>
                            </button>
                            <form action="{{ route('dashboard.photos.delete', $photo->id) }}" method="POST" onsubmit="return confirm('Delete this photo?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="photo-action-btn" style="padding: 5px 10px; border-radius: 7px; font-size: 0.74rem; font-weight: 700; background: rgba(239,68,68,0.9); color: #ffffff; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 3px; backdrop-filter: blur(4px); box-shadow: 0 2px 6px rgba(0,0,0,0.3);" title="Delete Photo">
                                    <i class="bi bi-trash"></i> <span class="photo-action-btn-text">{{ __('Delete') }}</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="photo-card-info" style="padding: 10px 12px; background: #ffffff; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; gap: 8px;">
                        <div>
                            <h4 class="photo-card-title" style="margin: 0 0 3px 0; font-size: 0.88rem; font-weight: 700; color: #0f172a; line-height: 1.3;">{{ $photo->title ?: __('Untitled') }}</h4>
                            @if($photo->content)
                            <p class="photo-card-caption" style="margin: 0 0 6px 0; font-size: 0.78rem; color: #475569; line-height: 1.35;">{{ $photo->content }}</p>
                            @endif
                        </div>

                        <!-- Owner Engagement Metrics Bar -->
                        <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 6px; border-top: 1px solid #f1f5f9; font-size: 0.76rem; font-weight: 700;">
                            <span style="display: flex; align-items: center; gap: 4px; color: #ef4444;" title="Likes">
                                <i class="bi bi-heart-fill"></i> {{ $photo->likes_count ?? 0 }}
                            </span>
                            <span style="display: flex; align-items: center; gap: 4px; color: #0284c7; cursor: pointer;" onclick="openOwnerMediaCommentsModal({{ $photo->id }}, '{{ addslashes($photo->title ?: 'Photo Comments') }}')" title="View Comments">
                                <i class="bi bi-chat-dots-fill"></i> {{ $photo->comments_count ?? 0 }}
                            </span>
                            <span style="display: flex; align-items: center; gap: 4px; color: #6366f1;" title="Shares">
                                <i class="bi bi-share-fill"></i> {{ $photo->shares_count ?? 0 }}
                            </span>
                        </div>

                        <span style="font-size: 0.70rem; color: #94a3b8; font-weight: 600;"><i class="bi bi-clock"></i> {{ $photo->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div style="grid-column: 1/-1; padding: 40px 15px; text-align: center; background: #f8fafc; border-radius: 14px; border: 1px dashed #cbd5e1;">
                    <i class="bi bi-image" style="font-size: 2.2rem; color: #94a3b8; display: block; margin-bottom: 8px;"></i>
                    <p style="margin: 0; color: #64748b; font-weight: 600; font-size: 0.88rem;">{{ __('No photos uploaded yet.') }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</main>

<!-- Edit Photo Modal -->
<div id="editPhotoModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15,23,42,0.65); backdrop-filter: blur(5px); z-index: 99999; justify-content: center; align-items: center; padding: 15px;">
    <div style="background: #ffffff; border-radius: 16px; width: 100%; max-width: 480px; padding: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); position: relative; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
            <h3 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                <i class="bi bi-pencil-square" style="color: #6366f1;"></i> {{ __('Edit Photo') }}
            </h3>
            <button type="button" onclick="$('#editPhotoModal').fadeOut(200);" style="background: none; border: none; font-size: 1.3rem; color: #64748b; cursor: pointer;">&times;</button>
        </div>

        <form id="editPhotoForm" action="" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 18px;">
                <!-- Current Photo Preview -->
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.8rem; color: #334155; margin-bottom: 4px;">{{ __('Current Image') }}</label>
                    <div style="width: 100px; height: 100px; border-radius: 10px; overflow: hidden; border: 2px solid #6366f1; background: #f8fafc;">
                        <img id="editPhotoCurrentImg" src="" alt="Current Photo" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>

                <!-- Title -->
                <div class="form-group">
                    <label for="edit_photo_title" style="display: block; font-weight: 700; font-size: 0.8rem; color: #334155; margin-bottom: 4px;">{{ __('Title') }}</label>
                    <input type="text" id="edit_photo_title" name="title" class="form-control" placeholder="{{ __('e.g. Stage Performance') }}" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%;">
                </div>

                <!-- Caption -->
                <div class="form-group">
                    <label for="edit_photo_caption" style="display: block; font-weight: 700; font-size: 0.8rem; color: #334155; margin-bottom: 4px;">{{ __('Caption') }}</label>
                    <textarea id="edit_photo_caption" name="caption" class="form-control" rows="2" placeholder="{{ __('Write short description...') }}" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%; line-height: 1.4;"></textarea>
                </div>

                <!-- Replace Image File -->
                <div class="form-group">
                    <label for="edit_photo_file" style="display: block; font-weight: 700; font-size: 0.8rem; color: #334155; margin-bottom: 4px;">{{ __('Replace Image (Optional)') }}</label>
                    <input type="file" id="edit_photo_file" name="photo" class="form-control" accept="image/*" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 7px 10px; font-size: 0.84rem; width: 100%;">
                    <p style="font-size: 0.72rem; color: #64748b; margin-top: 3px;">{{ __('Max 20MB. Leave empty to keep current photo.') }}</p>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" onclick="$('#editPhotoModal').fadeOut(200);" style="padding: 8px 16px; border-radius: 8px; font-weight: 700; background: #f1f5f9; color: #475569; border: none; cursor: pointer; font-size: 0.84rem;">{{ __('Cancel') }}</button>
                <button type="submit" style="padding: 8px 20px; border-radius: 8px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; border: none; cursor: pointer; box-shadow: 0 3px 10px rgba(99,102,241,0.3); font-size: 0.84rem;">{{ __('Save Changes') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openEditPhotoModal(id, title, caption, imgUrl) {
        $('#editPhotoForm').attr('action', '/dashboard/photos/' + id + '/update');
        $('#edit_photo_title').val(title);
        $('#edit_photo_caption').val(caption);
        $('#editPhotoCurrentImg').attr('src', imgUrl);
        $('#editPhotoModal').css('display', 'flex').hide().fadeIn(200);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const photoInput = document.getElementById('photos');
        const previewContainer = document.getElementById('imagePreviewContainer');
        const multiPreviewGrid = document.getElementById('multiPreviewGrid');
        const selectedCountBadge = document.getElementById('selectedCountBadge');
        const photoSizeAlert = document.getElementById('photoSizeAlert');
        const btnSubmitPhoto = document.getElementById('btnSubmitPhoto');
        const formPhotoUpload = document.getElementById('formPhotoUpload');

        if (photoInput) {
            photoInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                if (files && files.length > 0) {
                    let hasOversized = false;
                    multiPreviewGrid.innerHTML = '';
                    selectedCountBadge.textContent = `${files.length} selected`;

                    files.forEach(file => {
                        if (file.size > 20 * 1024 * 1024) {
                            hasOversized = true;
                        }

                        const reader = new FileReader();
                        reader.onload = function(evt) {
                            const card = document.createElement('div');
                            card.style.cssText = 'position: relative; width: 80px; height: 80px; border-radius: 8px; overflow: hidden; border: 2px solid #6366f1; background: #f8fafc; flex-shrink: 0;';
                            card.innerHTML = `<img src="${evt.target.result}" style="width: 100%; height: 100%; object-fit: cover;" alt="Preview">`;
                            multiPreviewGrid.appendChild(card);
                        };
                        reader.readAsDataURL(file);
                    });

                    previewContainer.style.display = 'block';

                    if (hasOversized) {
                        photoSizeAlert.style.display = 'block';
                        photoSizeAlert.style.color = '#ef4444';
                        photoSizeAlert.innerHTML = `<i class="bi bi-exclamation-triangle-fill"></i> Image(s) exceed 20MB limit. Select smaller files.`;
                        btnSubmitPhoto.disabled = true;
                        btnSubmitPhoto.style.opacity = '0.6';
                    } else {
                        photoSizeAlert.style.display = 'none';
                        btnSubmitPhoto.disabled = false;
                        btnSubmitPhoto.style.opacity = '1';
                    }
                } else {
                    previewContainer.style.display = 'none';
                    multiPreviewGrid.innerHTML = '';
                    if (photoSizeAlert) photoSizeAlert.style.display = 'none';
                    if (btnSubmitPhoto) btnSubmitPhoto.disabled = false;
                }
            });
        }

        if (formPhotoUpload && btnSubmitPhoto) {
            formPhotoUpload.addEventListener('submit', function(e) {
                e.preventDefault();

                const fileInput = document.getElementById('photos');
                if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                    alert("Please select photo file(s) to upload.");
                    return;
                }

                const formData = new FormData(formPhotoUpload);
                const modal = document.getElementById('photoUploadLoaderModal');
                if (modal && modal.parentElement !== document.body) {
                    document.body.appendChild(modal);
                }

                const progressBar = document.getElementById('photoLoaderProgressBar');
                const progressPercent = document.getElementById('photoLoaderProgressPercent');
                const modalTitle = document.getElementById('photoLoaderModalTitle');
                const modalMsg = document.getElementById('photoLoaderModalMessage');

                const inlineBox = document.getElementById('inlinePhotoUploadProgressBox');
                const inlineProgressBar = document.getElementById('inlinePhotoProgressBar');
                const inlineProgressDetails = document.getElementById('inlinePhotoProgressDetails');
                const inlinePercentBadge = document.getElementById('inlinePhotoPercentBadge');
                const inlineTitle = document.getElementById('inlinePhotoTitle');

                modalTitle.textContent = "Uploading Photo(s)...";
                modalMsg.innerHTML = "Please wait while your image file(s) are being uploaded and compressed.<br>Do not refresh this page.";
                progressBar.style.width = "0%";
                progressPercent.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.88rem; font-weight: 800;">
                        <span style="color: #4f46e5;"><i class="bi bi-arrow-up-circle-fill"></i> 0% Uploaded</span>
                        <span style="color: #ec4899;"><i class="bi bi-clock-history"></i> 100% Remaining</span>
                    </div>
                `;
                modal.style.display = 'flex';

                if (inlineBox) {
                    inlineBox.style.display = 'block';
                    inlineProgressBar.style.width = '0%';
                    inlinePercentBadge.textContent = '0%';
                    inlinePercentBadge.style.background = 'rgba(99,102,241,0.12)';
                    inlinePercentBadge.style.color = '#4f46e5';
                    inlineProgressDetails.innerHTML = `
                        <span style="color: #4f46e5;"><i class="bi bi-arrow-up-circle-fill"></i> 0% Uploaded (0 MB / 0 MB)</span>
                        <span style="color: #ec4899;"><i class="bi bi-clock-history"></i> 100% Remaining (0 MB left)</span>
                    `;
                }

                btnSubmitPhoto.disabled = true;
                btnSubmitPhoto.style.opacity = '0.75';
                btnSubmitPhoto.style.cursor = 'not-allowed';
                btnSubmitPhoto.innerHTML = `<i class="bi bi-arrow-repeat spin-icon"></i> ${'{{ __("Uploading Photos... Please Wait") }}'}`;

                $.ajax({
                    url: formPhotoUpload.action,
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
                                const remainingPercent = Math.max(0, 100 - percentComplete);
                                const loadedMB = (evt.loaded / (1024 * 1024)).toFixed(1);
                                const totalMB = (evt.total / (1024 * 1024)).toFixed(1);
                                const remainingMB = Math.max(0, (evt.total - evt.loaded) / (1024 * 1024)).toFixed(1);

                                progressBar.style.width = percentComplete + '%';
                                progressPercent.innerHTML = `
                                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.88rem; font-weight: 800; color: #0f172a;">
                                        <span style="color: #4f46e5;"><i class="bi bi-arrow-up-circle-fill"></i> ${percentComplete}% Uploaded (${loadedMB} MB / ${totalMB} MB)</span>
                                        <span style="color: #ec4899;"><i class="bi bi-clock-history"></i> ${remainingPercent}% Remaining (${remainingMB} MB left)</span>
                                    </div>
                                `;

                                if (inlineBox) {
                                    inlineProgressBar.style.width = percentComplete + '%';
                                    inlinePercentBadge.textContent = percentComplete + '%';
                                    inlineProgressDetails.innerHTML = `
                                        <span style="color: #4f46e5;"><i class="bi bi-arrow-up-circle-fill"></i> ${percentComplete}% Uploaded (${loadedMB} MB / ${totalMB} MB)</span>
                                        <span style="color: #ec4899;"><i class="bi bi-clock-history"></i> ${remainingPercent}% Remaining (${remainingMB} MB left)</span>
                                    `;
                                }

                                if (percentComplete >= 100) {
                                    modalTitle.textContent = "Compressing & Processing Photo(s)...";
                                    modalMsg.innerHTML = "Upload complete! Optimizing images on server...<br>Please wait a moment.";
                                    if (inlineTitle) {
                                        inlineTitle.innerHTML = `<i class="bi bi-hourglass-split spin-icon" style="color: #6366f1;"></i> <span>Finalizing & Compressing Photo(s)...</span>`;
                                    }
                                }
                            }
                        }, false);
                        return xhr;
                    },
                    success: function(res) {
                        if (typeof res === 'string' || !res || res.success === false) {
                            modal.style.display = 'none';
                            if (inlineBox) inlineBox.style.display = 'none';
                            btnSubmitPhoto.disabled = false;
                            btnSubmitPhoto.style.opacity = '1';
                            btnSubmitPhoto.style.cursor = 'pointer';
                            btnSubmitPhoto.innerHTML = `<i class="bi bi-cloud-arrow-up-fill"></i> ${'{{ __("Upload Photos") }}'}`;
                            let msg = (res && res.message) ? res.message : "Server error or upload limit exceeded. Please select smaller images.";
                            alert("Upload Failed: " + msg);
                            return;
                        }
                        progressBar.style.width = "100%";
                        progressPercent.innerHTML = `
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.88rem; font-weight: 800;">
                                <span style="color: #10b981;"><i class="bi bi-check-circle-fill"></i> 100% Upload Complete!</span>
                                <span style="color: #10b981;"><i class="bi bi-check2-all"></i> 0% Remaining</span>
                            </div>
                        `;
                        if (inlineBox) {
                            inlineProgressBar.style.width = "100%";
                            inlinePercentBadge.textContent = "100%";
                            inlinePercentBadge.style.background = "rgba(16,185,129,0.15)";
                            inlinePercentBadge.style.color = "#10b981";
                            inlineProgressDetails.innerHTML = `
                                <span style="color: #10b981;"><i class="bi bi-check-circle-fill"></i> 100% Upload Complete!</span>
                                <span style="color: #10b981;"><i class="bi bi-check2-all"></i> 0% Remaining</span>
                            `;
                        }
                        modalTitle.textContent = "🎉 Upload Successful!";
                        modalMsg.textContent = res.message || "Your photo(s) have been uploaded successfully.";
                        btnSubmitPhoto.innerHTML = `<i class="bi bi-check-circle-fill"></i> ${'{{ __("Uploaded! Reloading...") }}'}`;
                        setTimeout(function() {
                            window.location.reload();
                        }, 500);
                    },
                    error: function(err) {
                        modal.style.display = 'none';
                        if (inlineBox) inlineBox.style.display = 'none';
                        btnSubmitPhoto.disabled = false;
                        btnSubmitPhoto.style.opacity = '1';
                        btnSubmitPhoto.style.cursor = 'pointer';
                        btnSubmitPhoto.innerHTML = `<i class="bi bi-cloud-arrow-up-fill"></i> ${'{{ __("Upload Photos") }}'}`;
                        let errMsg = "An error occurred while uploading your photo file(s).";
                        if (err.status === 413) {
                            errMsg = "The image payload exceeds web server limits (HTTP 413). Please select smaller files or ask server administrator to increase upload limits.";
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
    });

    function openOwnerMediaCommentsModal(id, title) {
        $('#ownerCommentsModalTitle').text(title || 'Post Comments');
        $('#ownerCommentsList').html('<div style="text-align: center; padding: 25px; color: #94a3b8;"><i class="bi bi-hourglass-split"></i> Loading comments...</div>');
        $('#ownerCommentsModal').css('display', 'flex').hide().fadeIn(200);

        $.ajax({
            url: '/media/' + id + '/comments',
            type: 'GET',
            success: function(res) {
                if (res.success) {
                    const list = $('#ownerCommentsList');
                    if (!res.comments || res.comments.length === 0) {
                        list.html('<div style="text-align: center; padding: 25px; color: #94a3b8;"><i class="bi bi-chat-left-text" style="font-size: 1.8rem; display: block; margin-bottom: 6px;"></i>No comments left on this photo yet.</div>');
                        return;
                    }
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
            data: { _token: '{{ csrf_token() }}' },
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
                <i class="bi bi-chat-dots-fill" style="color: #0284c7;"></i> <span id="ownerCommentsModalTitle">Photo Comments</span>
            </h3>
            <button type="button" onclick="$('#ownerCommentsModal').fadeOut(200);" style="background: none; border: none; font-size: 1.3rem; color: #64748b; cursor: pointer;">&times;</button>
        </div>

        <div id="ownerCommentsList" style="flex-grow: 1; overflow-y: auto; max-height: 400px; display: flex; flex-direction: column; gap: 10px;">
            <div style="text-align: center; padding: 25px; color: #94a3b8;"><i class="bi bi-hourglass-split"></i> Loading comments...</div>
        </div>
    </div>
</div>

<!-- Full-Screen Photo Upload Processing Loader Modal Overlay -->
<div id="photoUploadLoaderModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.88); backdrop-filter: blur(10px); z-index: 999999; justify-content: center; align-items: center; padding: 20px;">
    <div style="background: #ffffff; width: 100%; max-width: 460px; border-radius: 20px; padding: 32px 24px; text-align: center; box-shadow: 0 25px 60px rgba(0,0,0,0.5); border: 2px solid rgba(99,102,241,0.3); position: relative;">
        <!-- Animated Spinner & Photo Icon -->
        <div style="position: relative; width: 80px; height: 80px; margin: 0 auto 20px auto; display: flex; align-items: center; justify-content: center;">
            <div class="spinner-border text-primary" role="status" style="width: 80px; height: 80px; border-width: 5px; color: #6366f1 !important; border-top-color: #ec4899 !important;"></div>
            <i class="bi bi-images" style="position: absolute; font-size: 1.8rem; color: #6366f1;"></i>
        </div>

        <h3 id="photoLoaderModalTitle" style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #0f172a;">{{ __('Uploading Photo(s)...') }}</h3>
        <p id="photoLoaderModalMessage" style="margin: 10px 0 0 0; font-size: 0.88rem; color: #475569; line-height: 1.5; font-weight: 600;">
            {!! __('Please wait while your image file(s) are being uploaded and compressed.<br>Do not refresh this page.') !!}
        </p>

        <!-- Dynamic Real-time Green Animated Progress Bar -->
        <div id="photoLoaderProgressBarContainer" style="margin-top: 22px; background: #cbd5e1; border-radius: 12px; height: 16px; overflow: hidden; position: relative; box-shadow: inset 0 1px 3px rgba(0,0,0,0.12);">
            <div id="photoLoaderProgressBar" class="progress-bar-green-animated" style="width: 0%; height: 100%;"></div>
        </div>

        <div id="photoLoaderProgressPercent" style="margin-top: 10px;">
            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.88rem; font-weight: 800;">
                <span style="color: #4f46e5;"><i class="bi bi-arrow-up-circle-fill"></i> 0% Uploaded</span>
                <span style="color: #ec4899;"><i class="bi bi-clock-history"></i> 100% Remaining</span>
            </div>
        </div>

        <div style="margin-top: 18px; padding: 10px 14px; background: #f1f5f9; border-radius: 10px; border: 1px dashed #cbd5e1; font-size: 0.78rem; color: #64748b; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 6px;">
            <i class="bi bi-shield-lock-fill" style="color: #6366f1;"></i>
            <span>{{ __('Do not close or refresh this browser page') }}</span>
        </div>
    </div>
</div>
@endsection