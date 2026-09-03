@extends('layouts.app')

@section('title', 'ChapConnect - Manage Photos')

@section('content')
<main class="main admin-main-container" style="max-width: 100%; width: 100%; margin: 15px 0; padding: 0 30px;">
    <div class="dashboard-container">
        <!-- Main Content Area: Photos Manager -->
        <div class="pdetails" style="background: #ffffff; border-radius: 16px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
        <!-- Page Header -->
        <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; padding: 24px 28px; border-radius: 14px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <div>
                <h2 style="margin: 0 0 6px 0; font-size: 1.35rem; font-weight: 800; color: #ffffff; border: none; padding: 0; display: flex; align-items: center; gap: 10px;">
                    <i class="bi bi-images" style="color: #6366f1;"></i> Portfolio Photos Manager
                </h2>
                <p style="margin: 0; color: #94a3b8; font-size: 0.88rem;">Upload photos with instant live preview, custom titles, and captions.</p>
            </div>
            <div style="background: rgba(99,102,241,0.2); border: 1px solid rgba(99,102,241,0.4); padding: 6px 16px; border-radius: 20px; font-weight: 700; font-size: 0.82rem; color: #a5b4fc;">
                Uploaded: {{ $photos->count() }} Photos
            </div>
        </div>
        
        <!-- Upload Form -->
        <form id="formPhotoUpload" action="{{ route('dashboard.photos.store') }}" method="POST" enctype="multipart/form-data" style="max-width: 640px; margin: 0 auto 35px auto; background: #f8fafc; border: 2px dashed #cbd5e1; padding: 25px; border-radius: 14px; transition: all 0.2s ease;">
            @csrf
            
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
                <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(99,102,241,0.12); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                    <i class="bi bi-cloud-arrow-up-fill"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: #0f172a;">Upload New Image</h3>
                    <p style="margin: 0; font-size: 0.8rem; color: #64748b;">Add title, caption description, and image file (Max 15MB, auto-web compressed).</p>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 20px;">
                <!-- Photo Title -->
                <div class="form-group">
                    <label for="title" style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Photo Title / Label (Optional)</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. Live Performance at Serena Hotel or Studio Session 2026" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%;">
                </div>

                <!-- Photo Caption -->
                <div class="form-group">
                    <label for="caption" style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Photo Caption / Story (Optional)</label>
                    <textarea id="caption" name="caption" class="form-control" rows="2" placeholder="Write a short description or story behind this photo..." style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%; line-height: 1.5;">{{ old('caption') }}</textarea>
                </div>

                <!-- File Selection -->
                <div class="form-group">
                    <label for="photos" style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Select Portfolio Image File(s) * (Multiple allowed)</label>
                    <input type="file" id="photos" name="photos[]" class="form-control" accept="image/*" multiple required style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.88rem; width: 100%;">
                    <p style="font-size: 0.76rem; color: #64748b; margin-top: 4px;">Formats: JPEG, PNG, JPG, GIF, WEBP, HEIC. Max 15MB per file. You can select multiple images.</p>
                    <div id="photoSizeAlert" style="display: none; margin-top: 6px; font-size: 0.82rem; font-weight: 600;"></div>

                    <!-- Live Multi-Image Preview Box -->
                    <div id="imagePreviewContainer" style="display: none; margin-top: 15px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 12px; padding: 15px; width: 100%;">
                        <div style="font-weight: 700; font-size: 0.82rem; color: #475569; margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between;">
                            <span><i class="bi bi-eye-fill" style="color: #6366f1;"></i> Selected Image(s) Live Preview</span>
                            <span id="selectedCountBadge" style="font-size: 0.75rem; background: rgba(99,102,241,0.1); color: #6366f1; padding: 2px 8px; border-radius: 10px; font-weight: 700;">0 selected</span>
                        </div>
                        <div id="multiPreviewGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; max-height: 320px; overflow-y: auto; padding: 5px;"></div>
                    </div>
                </div>
            </div>

            <button type="submit" id="btnSubmitPhoto" style="padding: 11px 26px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(99,102,241,0.35); cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-size: 0.88rem;">
                <i class="bi bi-upload"></i> Upload Image &amp; Details
            </button>
        </form>

        <h3 style="font-size: 1.05rem; font-weight: 700; color: #0f172a; margin-bottom: 18px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; display: flex; align-items: center; justify-content: space-between;">
            <span><i class="bi bi-grid-3x3-gap-fill" style="color: #6366f1; margin-right: 6px;"></i> Your Gallery Photos</span>
            <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">{{ $photos->count() }} items</span>
        </h3>
        
        <!-- Current Uploads Grid -->
        <div class="photos-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px;">
            @forelse($photos as $photo)
                <div class="photo-item" style="border-radius: 14px; overflow: hidden; position: relative; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.05); background: #ffffff; display: flex; flex-direction: column;">
                    <div style="width: 100%; aspect-ratio: 4/3; overflow: hidden; position: relative; background: #0f172a;">
                        <img src="{{ asset($photo->file_path) }}" alt="{{ $photo->title ?? 'Portfolio Asset' }}" style="width: 100%; height: 100%; object-fit: cover; object-position: top center;">
                        
                        <div style="position: absolute; top: 10px; right: 10px; z-index: 2; display: flex; gap: 6px;">
                            <button type="button" onclick="openEditPhotoModal({{ $photo->id }}, '{{ addslashes($photo->title ?? '') }}', '{{ addslashes($photo->content ?? '') }}', '{{ asset($photo->file_path) }}')" style="padding: 6px 12px; border-radius: 8px; font-size: 0.76rem; font-weight: 700; background: rgba(99,102,241,0.9); color: #ffffff; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; backdrop-filter: blur(4px); box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                            <form action="{{ route('dashboard.photos.delete', $photo->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this photo?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="padding: 6px 12px; border-radius: 8px; font-size: 0.76rem; font-weight: 700; background: rgba(239,68,68,0.9); color: #ffffff; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; backdrop-filter: blur(4px); box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    <div style="padding: 14px 16px; background: #ffffff; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <h4 style="margin: 0 0 4px 0; font-size: 0.95rem; font-weight: 700; color: #0f172a;">{{ $photo->title ?: 'Untitled Photo' }}</h4>
                            @if($photo->content)
                                <p style="margin: 0 0 8px 0; font-size: 0.8rem; color: #475569; line-height: 1.4;">{{ $photo->content }}</p>
                            @endif
                        </div>
                        <span style="font-size: 0.74rem; color: #94a3b8; font-weight: 600;"><i class="bi bi-clock"></i> {{ $photo->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; padding: 50px 20px; text-align: center; background: #f8fafc; border-radius: 14px; border: 1px dashed #cbd5e1;">
                    <i class="bi bi-image" style="font-size: 2.5rem; color: #94a3b8; display: block; margin-bottom: 10px;"></i>
                    <p style="margin: 0; color: #64748b; font-weight: 600; font-size: 0.92rem;">You have not uploaded any photos to your portfolio yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</main>

<!-- Edit Photo Modal -->
<div id="editPhotoModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15,23,42,0.65); backdrop-filter: blur(5px); z-index: 99999; justify-content: center; align-items: center; padding: 20px;">
    <div style="background: #ffffff; border-radius: 16px; width: 100%; max-width: 520px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); position: relative; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-pencil-square" style="color: #6366f1;"></i> Edit Photo Details
            </h3>
            <button type="button" onclick="$('#editPhotoModal').fadeOut(200);" style="background: none; border: none; font-size: 1.4rem; color: #64748b; cursor: pointer;">&times;</button>
        </div>

        <form id="editPhotoForm" action="" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 20px;">
                <!-- Current Photo Preview -->
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.84rem; color: #334155; margin-bottom: 6px;">Current Image</label>
                    <div style="width: 140px; height: 140px; border-radius: 10px; overflow: hidden; border: 2px solid #6366f1; background: #f8fafc;">
                        <img id="editPhotoCurrentImg" src="" alt="Current Photo" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>

                <!-- Title -->
                <div class="form-group">
                    <label for="edit_photo_title" style="display: block; font-weight: 700; font-size: 0.84rem; color: #334155; margin-bottom: 6px;">Photo Title / Label</label>
                    <input type="text" id="edit_photo_title" name="title" class="form-control" placeholder="e.g. Live Performance at Serena Hotel" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%;">
                </div>

                <!-- Caption -->
                <div class="form-group">
                    <label for="edit_photo_caption" style="display: block; font-weight: 700; font-size: 0.84rem; color: #334155; margin-bottom: 6px;">Photo Caption / Story</label>
                    <textarea id="edit_photo_caption" name="caption" class="form-control" rows="3" placeholder="Write a short description..." style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%; line-height: 1.5;"></textarea>
                </div>

                <!-- Replace Image File -->
                <div class="form-group">
                    <label for="edit_photo_file" style="display: block; font-weight: 700; font-size: 0.84rem; color: #334155; margin-bottom: 6px;">Replace Image File (Optional)</label>
                    <input type="file" id="edit_photo_file" name="photo" class="form-control" accept="image/*" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 8px 12px; font-size: 0.88rem; width: 100%;">
                    <p style="font-size: 0.76rem; color: #64748b; margin-top: 4px;">Leave empty to keep the existing photo. Max 15MB.</p>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="$('#editPhotoModal').fadeOut(200);" style="padding: 10px 20px; border-radius: 10px; font-weight: 700; background: #f1f5f9; color: #475569; border: none; cursor: pointer;">Cancel</button>
                <button type="submit" style="padding: 10px 24px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(99,102,241,0.35);">Save Changes</button>
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
        const photoInput = document.getElementById('photos') || document.getElementById('photo');
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
                            card.style.cssText = 'position: relative; width: 100px; height: 100px; border-radius: 8px; overflow: hidden; border: 2px solid #6366f1; background: #f8fafc; flex-shrink: 0;';
                            card.innerHTML = `<img src="${evt.target.result}" style="width: 100%; height: 100%; object-fit: cover;" alt="Preview">`;
                            multiPreviewGrid.appendChild(card);
                        };
                        reader.readAsDataURL(file);
                    });

                    previewContainer.style.display = 'block';

                    if (hasOversized) {
                        photoSizeAlert.style.display = 'block';
                        photoSizeAlert.style.color = '#ef4444';
                        photoSizeAlert.innerHTML = `<i class="bi bi-exclamation-triangle-fill"></i> One or more selected images exceed the 15MB maximum size. Please choose smaller photos.`;
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
                    btnSubmitPhoto.innerHTML = `<i class="bi bi-hourglass-split"></i> Uploading &amp; Optimizing Image(s)...`;
                    btnSubmitPhoto.style.opacity = '0.7';
                }
            });
        }
    });
</script>
