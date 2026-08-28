@extends('layouts.app')

@section('title', 'ChapConnect - Manage News & Updates')

@section('content')
<main class="main admin-main-container" style="max-width: 100%; width: 100%; margin: 15px 0; padding: 0 30px;">
    <div class="dashboard-container">
        <!-- Main Content Area: News & Updates Manager -->
        <div class="pdetails" style="background: #ffffff; border-radius: 16px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
        <!-- Page Header -->
        <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; padding: 24px 28px; border-radius: 14px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <div>
                <h2 style="margin: 0 0 6px 0; font-size: 1.35rem; font-weight: 800; color: #ffffff; border: none; padding: 0; display: flex; align-items: center; gap: 10px;">
                    <i class="bi bi-newspaper" style="color: #6366f1;"></i> News & Announcements Manager
                </h2>
                <p style="margin: 0; color: #94a3b8; font-size: 0.88rem;">Publish tour announcements, album launches, or press releases.</p>
            </div>
            <div style="background: rgba(99,102,241,0.2); border: 1px solid rgba(99,102,241,0.4); padding: 6px 16px; border-radius: 20px; font-weight: 700; font-size: 0.82rem; color: #a5b4fc;">
                Published: {{ $newsItems->count() }} Articles
            </div>
        </div>
        
        <!-- Create News Form -->
        <form action="{{ route('dashboard.news.store') }}" method="POST" enctype="multipart/form-data" style="margin-bottom: 35px; background: #f8fafc; border: 1px solid #e2e8f0; padding: 25px; border-radius: 14px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
            @csrf
            
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
                <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(99,102,241,0.12); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: #0f172a;">Publish New Article</h3>
                    <p style="margin: 0; font-size: 0.8rem; color: #64748b;">Share your latest updates with profile visitors and fans.</p>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label for="title" style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">News Headline / Article Title *</label>
                <input type="text" id="title" name="title" class="form-control" placeholder="e.g. New Single Release or Live Performance Announcement" required style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.9rem; width: 100%;">
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label for="content" style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Article Details / Description *</label>
                <textarea id="content" name="content" class="form-control" rows="5" placeholder="Write your full announcement or news story details..." required style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 12px 14px; font-size: 0.9rem; width: 100%; line-height: 1.6;"></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 22px;">
                <label for="image" style="display: block; font-weight: 700; font-size: 0.85rem; color: #334155; margin-bottom: 6px;">Optional Banner / Cover Image</label>
                <input type="file" id="image" name="image" class="form-control" accept="image/*" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 8px 12px; font-size: 0.88rem; width: 100%;">
                <p style="font-size: 0.76rem; color: #64748b; margin-top: 4px;">Supported Formats: JPEG, PNG, WEBP. Max size: 10MB.</p>

                <!-- Live Image Preview Box -->
                <div id="newsImagePreviewContainer" style="display: none; margin-top: 15px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 12px; padding: 15px; width: fit-content;">
                    <div style="font-weight: 700; font-size: 0.82rem; color: #475569; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                        <i class="bi bi-eye-fill" style="color: #6366f1;"></i> Selected Banner Live Preview
                    </div>
                    <div style="position: relative; width: 280px; max-height: 160px; border-radius: 10px; overflow: hidden; border: 2px solid #6366f1; box-shadow: 0 4px 15px rgba(99,102,241,0.25); background: #f8fafc;">
                        <img id="newsImagePreview" src="" alt="Banner Preview" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>
            </div>
            
            <button type="submit" style="padding: 11px 28px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(99,102,241,0.35); cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-size: 0.88rem;">
                <i class="bi bi-send-fill"></i> Publish News Article
            </button>
        </form>

        <h3 style="font-size: 1.05rem; font-weight: 700; color: #0f172a; margin-bottom: 18px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; display: flex; align-items: center; justify-content: space-between;">
            <span><i class="bi bi-journal-richtext" style="color: #6366f1; margin-right: 6px;"></i> Published Articles</span>
            <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">{{ $newsItems->count() }} items</span>
        </h3>
        
        <!-- Current News Feed -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            @forelse($newsItems as $news)
                <div style="background: #ffffff; border-radius: 14px; padding: 22px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 14px; position: relative;">
                    @if($news->file_path)
                        <div style="max-height: 280px; overflow: hidden; border-radius: 10px; border: 1px solid #f1f5f9;">
                            <img src="{{ asset($news->file_path) }}" alt="{{ $news->title }}" style="width: 100%; height: 100%; max-height: 280px; object-fit: cover;">
                        </div>
                    @endif
                    
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 15px; flex-wrap: wrap;">
                        <div>
                            <h4 style="margin: 0 0 6px 0; color: #0f172a; font-size: 1.15rem; font-weight: 800;">{{ $news->title }}</h4>
                            <span style="font-size: 0.78rem; color: #64748b; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="bi bi-clock-history" style="color: #6366f1;"></i> Published {{ $news->created_at->format('M d, Y - h:i A') }} ({{ $news->created_at->diffForHumans() }})
                            </span>
                        </div>

                        <div style="display: flex; align-items: center; gap: 6px;">
                            <button type="button" onclick="openEditNewsModal({{ $news->id }}, '{{ addslashes($news->title ?? '') }}', '{{ addslashes($news->content ?? '') }}', '{{ $news->file_path ? asset($news->file_path) : '' }}')" style="padding: 6px 14px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; background: #6366f1; color: #ffffff; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 8px rgba(99,102,241,0.4);">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                            <form action="{{ route('dashboard.news.delete', $news->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this news article?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="padding: 6px 14px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; background: #ef4444; color: #ffffff; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 8px rgba(239,68,68,0.4);">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    <p style="color: #334155; font-size: 0.92rem; line-height: 1.65; margin: 0; white-space: pre-line;">{{ $news->content }}</p>
                </div>
            @empty
                <div style="padding: 50px 20px; text-align: center; background: #f8fafc; border-radius: 14px; border: 1px dashed #cbd5e1;">
                    <i class="bi bi-newspaper" style="font-size: 2.5rem; color: #94a3b8; display: block; margin-bottom: 10px;"></i>
                    <p style="margin: 0; color: #64748b; font-weight: 600; font-size: 0.92rem;">You have not published any news articles or updates yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</main>

<!-- Edit News Modal -->
<div id="editNewsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15,23,42,0.65); backdrop-filter: blur(5px); z-index: 99999; justify-content: center; align-items: center; padding: 20px;">
    <div style="background: #ffffff; border-radius: 16px; width: 100%; max-width: 600px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); position: relative; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-pencil-square" style="color: #6366f1;"></i> Edit Article Details
            </h3>
            <button type="button" onclick="$('#editNewsModal').fadeOut(200);" style="background: none; border: none; font-size: 1.4rem; color: #64748b; cursor: pointer;">&times;</button>
        </div>

        <form id="editNewsForm" action="" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 20px;">
                <!-- Article Title -->
                <div class="form-group">
                    <label for="edit_news_title" style="display: block; font-weight: 700; font-size: 0.84rem; color: #334155; margin-bottom: 6px;">News Headline / Article Title *</label>
                    <input type="text" id="edit_news_title" name="title" class="form-control" required style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%;">
                </div>

                <!-- Article Content -->
                <div class="form-group">
                    <label for="edit_news_content" style="display: block; font-weight: 700; font-size: 0.84rem; color: #334155; margin-bottom: 6px;">Article Details / Description *</label>
                    <textarea id="edit_news_content" name="content" class="form-control" rows="5" required style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%; line-height: 1.6;"></textarea>
                </div>

                <!-- Banner Image Preview & Replace -->
                <div class="form-group">
                    <label for="edit_news_image" style="display: block; font-weight: 700; font-size: 0.84rem; color: #334155; margin-bottom: 6px;">Replace Cover Image (Optional)</label>
                    <div id="editNewsCurrentImgContainer" style="display: none; margin-bottom: 10px;">
                        <div style="font-weight: 600; font-size: 0.76rem; color: #64748b; margin-bottom: 4px;">Current Banner:</div>
                        <img id="editNewsCurrentImg" src="" alt="Current Banner" style="max-width: 100%; max-height: 140px; border-radius: 8px; border: 1px solid #cbd5e1; object-fit: cover;">
                    </div>
                    <input type="file" id="edit_news_image" name="image" class="form-control" accept="image/*" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 8px 12px; font-size: 0.88rem; width: 100%;">
                    <p style="font-size: 0.76rem; color: #64748b; margin-top: 4px;">Leave empty to keep existing banner. Max 10MB.</p>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="$('#editNewsModal').fadeOut(200);" style="padding: 10px 20px; border-radius: 10px; font-weight: 700; background: #f1f5f9; color: #475569; border: none; cursor: pointer;">Cancel</button>
                <button type="submit" style="padding: 10px 24px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(99,102,241,0.35);">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openEditNewsModal(id, title, content, imgUrl) {
        $('#editNewsForm').attr('action', '/dashboard/news/' + id + '/update');
        $('#edit_news_title').val(title);
        $('#edit_news_content').val(content);
        if (imgUrl) {
            $('#editNewsCurrentImg').attr('src', imgUrl);
            $('#editNewsCurrentImgContainer').show();
        } else {
            $('#editNewsCurrentImgContainer').hide();
        }
        $('#editNewsModal').css('display', 'flex').hide().fadeIn(200);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const newsImageInput = document.getElementById('image');
        const newsPreviewContainer = document.getElementById('newsImagePreviewContainer');
        const newsPreviewImage = document.getElementById('newsImagePreview');

        if (newsImageInput) {
            newsImageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(evt) {
                        newsPreviewImage.src = evt.target.result;
                        newsPreviewContainer.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    newsPreviewContainer.style.display = 'none';
                    newsPreviewImage.src = '';
                }
            });
        }
    });
</script>
