@extends('layouts.app')

@section('title', __('ChapConnect - Manage News & Updates'))

@section('styles')
<style>
    /* Responsive Spacing & Layout Overrides */
    .news-main-container {
        max-width: 100%;
        width: 100%;
        margin: 15px 0;
        padding: 0 24px;
    }

    .news-pdetails {
        background: #ffffff;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid var(--border-color, #e2e8f0);
    }

    .news-page-header {
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

    .news-upload-card {
        max-width: 600px;
        margin: 0 auto 30px auto;
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .news-upload-card:focus-within,
    .news-upload-card:hover {
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

    .news-badge-opt {
        font-size: 0.72rem;
        font-weight: 600;
        color: #94a3b8;
        margin-left: 4px;
    }

    .news-card-item {
        background: #ffffff;
        border-radius: 14px;
        padding: 18px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.04);
        border: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        gap: 12px;
        position: relative;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .news-card-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.08);
    }

    /* Mobile Screen Enhancements (< 768px) */
    @media (max-width: 768px) {
        .news-main-container {
            padding: 0 6px !important;
            margin: 8px 0 !important;
        }

        .news-pdetails {
            padding: 14px 12px !important;
            border-radius: 14px !important;
        }

        .news-page-header {
            padding: 14px 14px !important;
            border-radius: 12px !important;
            margin-bottom: 16px !important;
            gap: 8px !important;
        }

        .news-page-title {
            font-size: 1.15rem !important;
        }

        .news-count-badge {
            font-size: 0.76rem !important;
            padding: 4px 12px !important;
        }

        .news-upload-card {
            padding: 14px 12px !important;
            border-radius: 12px !important;
            margin-bottom: 20px !important;
        }

        .news-btn-submit {
            width: 100% !important;
            justify-content: center !important;
            padding: 12px 18px !important;
            font-size: 0.9rem !important;
        }

        .news-card-item {
            padding: 14px 12px !important;
            border-radius: 12px !important;
            gap: 10px !important;
        }

        .news-card-title {
            font-size: 1.0rem !important;
        }

        .news-action-btn {
            padding: 4px 8px !important;
            font-size: 0.72rem !important;
        }
    }
</style>
@endsection

@section('content')
<main class="main admin-main-container news-main-container">
    <div class="dashboard-container">
        <!-- Main Content Area: News & Updates Manager -->
        <div class="pdetails news-pdetails">
            <!-- Page Header -->
            <div class="news-page-header">
                <h2 class="news-page-title" style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #0f172a; border: none; padding: 0; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-newspaper" style="color: var(--primary);"></i> {{ __('News & Updates') }}
                </h2>
                <div class="news-count-badge" style="background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.25); padding: 5px 14px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; color: #4f46e5;">
                    {{ $newsItems->count() }} {{ __('Published') }}
                </div>
            </div>

            <!-- Create News Form -->
            <form action="{{ route('dashboard.news.store') }}" method="POST" enctype="multipart/form-data" class="news-upload-card">
                @csrf

                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(99,102,241,0.12); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0;">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <div style="flex-grow: 1;">
                        <h3 style="margin: 0; font-size: 0.98rem; font-weight: 800; color: #0f172a;">{{ __('Publish Article') }}</h3>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 18px;">
                    <div class="form-group">
                        <label for="title" style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">{{ __('Headline *') }}</label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="{{ __('e.g. New Single Release') }}" required style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%;">
                    </div>

                    <div class="form-group">
                        <label for="content" style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">{{ __('Details / Story *') }}</label>
                        <textarea id="content" name="content" class="form-control" rows="4" placeholder="{{ __('Write full story or announcement details...') }}" required style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%; line-height: 1.5;"></textarea>
                    </div>

                    <div class="form-group">
                        <label style="display: block; font-weight: 700; font-size: 0.82rem; color: #334155; margin-bottom: 5px;">{{ __('Cover Image') }} <span class="news-badge-opt">({{ __('Optional') }})</span></label>

                        <div class="dropzone-label-box" onclick="document.getElementById('image').click();">
                            <i class="bi bi-image" style="font-size: 1.6rem; color: #6366f1; margin-bottom: 4px;"></i>
                            <span style="font-size: 0.84rem; font-weight: 700; color: #1e293b;">{{ __('Tap to choose cover image') }}</span>
                            <span style="font-size: 0.72rem; color: #64748b; margin-top: 2px;">{{ __('JPEG, PNG, WEBP (Max 10MB)') }}</span>
                        </div>

                        <input type="file" id="image" name="image" class="form-control" accept="image/*" style="display: none;">

                        <!-- Live Image Preview Box -->
                        <div id="newsImagePreviewContainer" style="display: none; margin-top: 12px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 12px; padding: 12px; width: fit-content;">
                            <div style="font-weight: 700; font-size: 0.78rem; color: #475569; margin-bottom: 6px; display: flex; align-items: center; gap: 4px;">
                                <i class="bi bi-eye-fill" style="color: #6366f1;"></i> {{ __('Preview') }}
                            </div>
                            <div style="position: relative; width: 220px; max-height: 130px; border-radius: 10px; overflow: hidden; border: 2px solid #6366f1; box-shadow: 0 3px 12px rgba(99,102,241,0.2); background: #f8fafc;">
                                <img id="newsImagePreview" src="" alt="Banner Preview" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="news-btn-submit" style="padding: 10px 22px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 14px rgba(99,102,241,0.3); cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-size: 0.86rem; transition: opacity 0.2s ease;">
                    <i class="bi bi-send-fill"></i> {{ __('Publish Article') }}
                </button>
            </form>

            <h3 style="font-size: 0.98rem; font-weight: 800; color: #0f172a; margin-bottom: 14px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px; display: flex; align-items: center; justify-content: space-between;">
                <span><i class="bi bi-journal-richtext" style="color: #6366f1; margin-right: 6px;"></i> {{ __('Published Articles') }}</span>
                <span style="font-size: 0.78rem; font-weight: 600; color: #64748b;">{{ $newsItems->count() }} {{ __('items') }}</span>
            </h3>

            <!-- Current News Feed -->
            <div style="display: flex; flex-direction: column; gap: 16px;">
                @forelse($newsItems as $news)
                <div class="news-card-item">
                    @if($news->file_path)
                    <div style="max-height: 240px; overflow: hidden; border-radius: 10px; border: 1px solid #f1f5f9;">
                        <img src="{{ asset($news->file_path) }}" alt="{{ $news->title }}" style="width: 100%; height: 100%; max-height: 240px; object-fit: cover;">
                    </div>
                    @endif

                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; flex-wrap: wrap;">
                        <div style="flex-grow: 1;">
                            <h4 class="news-card-title" style="margin: 0 0 4px 0; color: #0f172a; font-size: 1.05rem; font-weight: 800; line-height: 1.3;">{{ $news->title }}</h4>
                            <span style="font-size: 0.74rem; color: #64748b; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="bi bi-clock-history" style="color: #6366f1;"></i> {{ $news->created_at->format('M d, Y') }} ({{ $news->created_at->diffForHumans() }})
                            </span>
                        </div>

                        <div style="display: flex; align-items: center; gap: 4px; flex-shrink: 0;">
                            <button type="button" class="news-action-btn" onclick="openEditNewsModal({{ $news->id }}, '{{ addslashes($news->title ?? '') }}', '{{ addslashes($news->content ?? '') }}', '{{ $news->file_path ? asset($news->file_path) : '' }}')" style="padding: 4px 10px; border-radius: 7px; font-size: 0.74rem; font-weight: 700; background: #6366f1; color: #ffffff; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 3px; box-shadow: 0 2px 6px rgba(99,102,241,0.25);" title="{{ __('Edit Article') }}">
                                <i class="bi bi-pencil-square"></i> {{ __('Edit') }}
                            </button>
                            <form action="{{ route('dashboard.news.delete', $news->id) }}" method="POST" onsubmit="return confirm('{{ __('Delete this news article?') }}');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="news-action-btn" style="padding: 4px 10px; border-radius: 7px; font-size: 0.74rem; font-weight: 700; background: #ef4444; color: #ffffff; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 3px; box-shadow: 0 2px 6px rgba(239,68,68,0.25);" title="{{ __('Delete Article') }}">
                                    <i class="bi bi-trash"></i> {{ __('Delete') }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <p style="color: #334155; font-size: 0.88rem; line-height: 1.55; margin: 0; white-space: pre-line;">{{ $news->content }}</p>
                </div>
                @empty
                <div style="padding: 40px 15px; text-align: center; background: #f8fafc; border-radius: 14px; border: 1px dashed #cbd5e1;">
                    <i class="bi bi-newspaper" style="font-size: 2.2rem; color: #94a3b8; display: block; margin-bottom: 8px;"></i>
                    <p style="margin: 0; color: #64748b; font-weight: 600; font-size: 0.88rem;">{{ __('No articles published yet.') }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</main>

<!-- Edit News Modal -->
<div id="editNewsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15,23,42,0.65); backdrop-filter: blur(5px); z-index: 99999; justify-content: center; align-items: center; padding: 15px;">
    <div style="background: #ffffff; border-radius: 16px; width: 100%; max-width: 480px; padding: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); position: relative; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
            <h3 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                <i class="bi bi-pencil-square" style="color: #6366f1;"></i> {{ __('Edit Article') }}
            </h3>
            <button type="button" onclick="$('#editNewsModal').fadeOut(200);" style="background: none; border: none; font-size: 1.3rem; color: #64748b; cursor: pointer;">&times;</button>
        </div>

        <form id="editNewsForm" action="" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 18px;">
                <!-- Article Title -->
                <div class="form-group">
                    <label for="edit_news_title" style="display: block; font-weight: 700; font-size: 0.8rem; color: #334155; margin-bottom: 4px;">{{ __('Headline *') }}</label>
                    <input type="text" id="edit_news_title" name="title" class="form-control" required style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%;">
                </div>

                <!-- Article Content -->
                <div class="form-group">
                    <label for="edit_news_content" style="display: block; font-weight: 700; font-size: 0.8rem; color: #334155; margin-bottom: 4px;">{{ __('Details / Story *') }}</label>
                    <textarea id="edit_news_content" name="content" class="form-control" rows="4" required style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.86rem; width: 100%; line-height: 1.5;"></textarea>
                </div>

                <!-- Banner Image Preview & Replace -->
                <div class="form-group">
                    <label for="edit_news_image" style="display: block; font-weight: 700; font-size: 0.8rem; color: #334155; margin-bottom: 4px;">{{ __('Replace Cover Image (Optional)') }}</label>
                    <div id="editNewsCurrentImgContainer" style="display: none; margin-bottom: 8px;">
                        <div style="font-weight: 600; font-size: 0.72rem; color: #64748b; margin-bottom: 3px;">{{ __('Current Banner:') }}</div>
                        <img id="editNewsCurrentImg" src="" alt="Current Banner" style="max-width: 100%; max-height: 120px; border-radius: 8px; border: 1px solid #cbd5e1; object-fit: cover;">
                    </div>
                    <input type="file" id="edit_news_image" name="image" class="form-control" accept="image/*" style="background: #ffffff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 7px 10px; font-size: 0.84rem; width: 100%;">
                    <p style="font-size: 0.72rem; color: #64748b; margin-top: 3px;">{{ __('Max 10MB limit.') }}</p>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" onclick="$('#editNewsModal').fadeOut(200);" style="padding: 8px 16px; border-radius: 8px; font-weight: 700; background: #f1f5f9; color: #475569; border: none; cursor: pointer; font-size: 0.84rem;">{{ __('Cancel') }}</button>
                <button type="submit" style="padding: 8px 20px; border-radius: 8px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; border: none; cursor: pointer; box-shadow: 0 3px 10px rgba(99,102,241,0.3); font-size: 0.84rem;">{{ __('Save Changes') }}</button>
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
@endsection