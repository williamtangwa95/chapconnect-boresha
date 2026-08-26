@extends('layouts.app')

@section('title', 'ChapConnect - Manage Photos')

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
            <a class="active" href="{{ route('dashboard.photos') }}"><i class="bi bi-images"></i> Manage Photos</a>
            <a href="{{ route('dashboard.videos') }}"><i class="bi bi-film"></i> Manage Videos</a>
            <a href="{{ route('dashboard.news') }}"><i class="bi bi-newspaper"></i> Manage News</a>
            <a href="{{ route('profile', auth()->user()->id) }}" target="_blank" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); border: 1px solid rgba(99, 102, 241, 0.25);"><i class="bi bi-box-arrow-up-right"></i> Preview Public Profile</a>
        </div>
    </div>

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
        <form action="{{ route('dashboard.photos.store') }}" method="POST" enctype="multipart/form-data" style="margin-bottom: 35px; background: #f8fafc; border: 2px dashed #cbd5e1; padding: 25px; border-radius: 14px; transition: all 0.2s ease;">
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
                    <input type="text" id="title" name="title" class="form-control" placeholder="e.g. Live Performance at Serena Hotel or Studio Session 2026" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%;">
                </div>

                <!-- Photo Caption -->
                <div class="form-group">
                    <label for="caption" style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Photo Caption / Story (Optional)</label>
                    <textarea id="caption" name="caption" class="form-control" rows="2" placeholder="Write a short description or story behind this photo..." style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%; line-height: 1.5;"></textarea>
                </div>

                <!-- File Selection -->
                <div class="form-group">
                    <label for="photo" style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Select Portfolio Image File *</label>
                    <input type="file" id="photo" name="photo" class="form-control" accept="image/*" required style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.88rem; width: 100%;">
                    <p style="font-size: 0.76rem; color: #64748b; margin-top: 4px;">Formats: JPEG, PNG, JPG, GIF, WEBP. Max 15MB.</p>

                    <!-- Live Image Preview Box -->
                    <div id="imagePreviewContainer" style="display: none; margin-top: 15px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 12px; padding: 15px; width: fit-content;">
                        <div style="font-weight: 700; font-size: 0.82rem; color: #475569; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                            <i class="bi bi-eye-fill" style="color: #6366f1;"></i> Selected Image Live Preview
                        </div>
                        <div style="position: relative; width: 200px; height: 200px; border-radius: 10px; overflow: hidden; border: 2px solid #6366f1; box-shadow: 0 4px 15px rgba(99,102,241,0.25); background: #f8fafc;">
                            <img id="imagePreview" src="" alt="Photo Preview" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" style="padding: 11px 26px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(99,102,241,0.35); cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-size: 0.88rem;">
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
                        <img src="{{ asset($photo->file_path) }}" alt="{{ $photo->title ?? 'Portfolio Asset' }}" style="width: 100%; height: 100%; object-fit: cover;">
                        
                        <div style="position: absolute; top: 10px; right: 10px; z-index: 2;">
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
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const photoInput = document.getElementById('photo');
        const previewContainer = document.getElementById('imagePreviewContainer');
        const previewImage = document.getElementById('imagePreview');

        if (photoInput) {
            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(evt) {
                        previewImage.src = evt.target.result;
                        previewContainer.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.style.display = 'none';
                    previewImage.src = '';
                }
            });
        }
    });
</script>
@endsection
