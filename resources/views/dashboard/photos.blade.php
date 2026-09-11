@extends('layouts.app')

@section('title', 'ChapConnect - Manage Photos')

@section('styles')
<style>
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
                    <i class="bi bi-images" style="color: var(--primary);"></i> Photos
                </h2>
                <div class="photos-count-badge" style="background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.25); padding: 5px 14px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; color: #4f46e5;">
                    {{ $photos->count() }} Uploaded
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
                        <h3 style="margin: 0; font-size: 0.98rem; font-weight: 800; color: #0f172a;">Upload Photos</h3>
                    </div>
                    <span style="font-size: 0.72rem; color: #64748b; font-weight: 600; background: #e2e8f0; padding: 2px 8px; border-radius: 10px;">Max 15MB</span>
                </div>

                <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 18px;">
                    <!-- Photo Title -->
                    <div class="form-group">
                        <label for="title" style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">Title <span class="photo-badge-opt">(Optional)</span></label>
                        <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. Stage Performance" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%;">
                    </div>

                    <!-- Photo Caption -->
                    <div class="form-group">
                        <label for="caption" style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">Caption <span class="photo-badge-opt">(Optional)</span></label>
                        <textarea id="caption" name="caption" class="form-control" rows="2" placeholder="Add short description..." style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%; line-height: 1.4;">{{ old('caption') }}</textarea>
                    </div>

                    <!-- File Selection -->
                    <div class="form-group">
                        <label style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">Select Photo(s) *</label>

                        <div class="dropzone-label-box" onclick="document.getElementById('photos').click();">
                            <i class="bi bi-file-earmark-image" style="font-size: 1.6rem; color: #6366f1; margin-bottom: 4px;"></i>
                            <span style="font-size: 0.84rem; font-weight: 700; color: #1e293b;">Tap to choose photo(s)</span>
                            <span style="font-size: 0.72rem; color: #64748b; margin-top: 2px;">JPEG, PNG, WEBP, GIF (Max 15MB)</span>
                        </div>

                        <input type="file" id="photos" name="photos[]" class="form-control" accept="image/*" multiple required style="display: none;">

                        <div id="photoSizeAlert" style="display: none; margin-top: 6px; font-size: 0.8rem; font-weight: 600;"></div>

                        <!-- Live Multi-Image Preview Box -->
                        <div id="imagePreviewContainer" style="display: none; margin-top: 12px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 12px; padding: 12px; width: 100%;">
                            <div style="font-weight: 700; font-size: 0.8rem; color: #475569; margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between;">
                                <span><i class="bi bi-eye-fill" style="color: #6366f1;"></i> Preview</span>
                                <span id="selectedCountBadge" style="font-size: 0.72rem; background: rgba(99,102,241,0.1); color: #6366f1; padding: 2px 8px; border-radius: 10px; font-weight: 700;">0 selected</span>
                            </div>
                            <div id="multiPreviewGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 8px; max-height: 240px; overflow-y: auto; padding: 2px;"></div>
                        </div>
                    </div>
                </div>

                <button type="submit" id="btnSubmitPhoto" class="photos-btn-submit" style="padding: 10px 22px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 14px rgba(99,102,241,0.3); cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-size: 0.86rem; transition: opacity 0.2s ease;">
                    <i class="bi bi-upload"></i> Upload Photos
                </button>
            </form>

            <h3 style="font-size: 0.98rem; font-weight: 800; color: #0f172a; margin-bottom: 14px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px; display: flex; align-items: center; justify-content: space-between;">
                <span><i class="bi bi-grid-3x3-gap-fill" style="color: #6366f1; margin-right: 6px;"></i> Your Photos</span>
                <span style="font-size: 0.78rem; font-weight: 600; color: #64748b;">{{ $photos->count() }} items</span>
            </h3>

            <!-- Current Uploads Grid -->
            <div class="photos-grid">
                @forelse($photos as $photo)
                <div class="photo-card">
                    <div style="width: 100%; aspect-ratio: 4/3; overflow: hidden; position: relative; background: #0f172a;">
                        <img src="{{ asset($photo->file_path) }}" alt="{{ $photo->title ?? 'Portfolio Photo' }}" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">

                        <div style="position: absolute; top: 6px; right: 6px; z-index: 2; display: flex; gap: 4px;">
                            <button type="button" class="photo-action-btn" onclick="openEditPhotoModal({{ $photo->id }}, '{{ addslashes($photo->title ?? '') }}', '{{ addslashes($photo->content ?? '') }}', '{{ asset($photo->file_path) }}')" style="padding: 5px 10px; border-radius: 7px; font-size: 0.74rem; font-weight: 700; background: rgba(99,102,241,0.9); color: #ffffff; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 3px; backdrop-filter: blur(4px); box-shadow: 0 2px 6px rgba(0,0,0,0.3);" title="Edit Photo">
                                <i class="bi bi-pencil-square"></i> <span class="photo-action-btn-text">Edit</span>
                            </button>
                            <form action="{{ route('dashboard.photos.delete', $photo->id) }}" method="POST" onsubmit="return confirm('Delete this photo?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="photo-action-btn" style="padding: 5px 10px; border-radius: 7px; font-size: 0.74rem; font-weight: 700; background: rgba(239,68,68,0.9); color: #ffffff; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 3px; backdrop-filter: blur(4px); box-shadow: 0 2px 6px rgba(0,0,0,0.3);" title="Delete Photo">
                                    <i class="bi bi-trash"></i> <span class="photo-action-btn-text">Delete</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="photo-card-info" style="padding: 10px 12px; background: #ffffff; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <h4 class="photo-card-title" style="margin: 0 0 3px 0; font-size: 0.88rem; font-weight: 700; color: #0f172a; line-height: 1.3;">{{ $photo->title ?: 'Untitled' }}</h4>
                            @if($photo->content)
                            <p class="photo-card-caption" style="margin: 0 0 6px 0; font-size: 0.78rem; color: #475569; line-height: 1.35;">{{ $photo->content }}</p>
                            @endif
                        </div>
                        <span style="font-size: 0.70rem; color: #94a3b8; font-weight: 600;"><i class="bi bi-clock"></i> {{ $photo->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div style="grid-column: 1/-1; padding: 40px 15px; text-align: center; background: #f8fafc; border-radius: 14px; border: 1px dashed #cbd5e1;">
                    <i class="bi bi-image" style="font-size: 2.2rem; color: #94a3b8; display: block; margin-bottom: 8px;"></i>
                    <p style="margin: 0; color: #64748b; font-weight: 600; font-size: 0.88rem;">No photos uploaded yet.</p>
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
                <i class="bi bi-pencil-square" style="color: #6366f1;"></i> Edit Photo
            </h3>
            <button type="button" onclick="$('#editPhotoModal').fadeOut(200);" style="background: none; border: none; font-size: 1.3rem; color: #64748b; cursor: pointer;">&times;</button>
        </div>

        <form id="editPhotoForm" action="" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 18px;">
                <!-- Current Photo Preview -->
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.8rem; color: #334155; margin-bottom: 4px;">Current Image</label>
                    <div style="width: 100px; height: 100px; border-radius: 10px; overflow: hidden; border: 2px solid #6366f1; background: #f8fafc;">
                        <img id="editPhotoCurrentImg" src="" alt="Current Photo" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>

                <!-- Title -->
                <div class="form-group">
                    <label for="edit_photo_title" style="display: block; font-weight: 700; font-size: 0.8rem; color: #334155; margin-bottom: 4px;">Title</label>
                    <input type="text" id="edit_photo_title" name="title" class="form-control" placeholder="e.g. Stage Performance" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%;">
                </div>

                <!-- Caption -->
                <div class="form-group">
                    <label for="edit_photo_caption" style="display: block; font-weight: 700; font-size: 0.8rem; color: #334155; margin-bottom: 4px;">Caption</label>
                    <textarea id="edit_photo_caption" name="caption" class="form-control" rows="2" placeholder="Write short description..." style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%; line-height: 1.4;"></textarea>
                </div>

                <!-- Replace Image File -->
                <div class="form-group">
                    <label for="edit_photo_file" style="display: block; font-weight: 700; font-size: 0.8rem; color: #334155; margin-bottom: 4px;">Replace Image (Optional)</label>
                    <input type="file" id="edit_photo_file" name="photo" class="form-control" accept="image/*" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 7px 10px; font-size: 0.84rem; width: 100%;">
                    <p style="font-size: 0.72rem; color: #64748b; margin-top: 3px;">Max 15MB. Leave empty to keep current photo.</p>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" onclick="$('#editPhotoModal').fadeOut(200);" style="padding: 8px 16px; border-radius: 8px; font-weight: 700; background: #f1f5f9; color: #475569; border: none; cursor: pointer; font-size: 0.84rem;">Cancel</button>
                <button type="submit" style="padding: 8px 20px; border-radius: 8px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; border: none; cursor: pointer; box-shadow: 0 3px 10px rgba(99,102,241,0.3); font-size: 0.84rem;">Save Changes</button>
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
                        if (file.size > 15 * 1024 * 1024) {
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
                        photoSizeAlert.innerHTML = `<i class="bi bi-exclamation-triangle-fill"></i> Image(s) exceed 15MB limit. Select smaller files.`;
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
            formPhotoUpload.addEventListener('submit', function() {
                if (!btnSubmitPhoto.disabled) {
                    btnSubmitPhoto.disabled = true;
                    btnSubmitPhoto.innerHTML = `<i class="bi bi-hourglass-split"></i> Uploading...`;
                    btnSubmitPhoto.style.opacity = '0.7';
                }
            });
        }
    });
</script>
@endsection