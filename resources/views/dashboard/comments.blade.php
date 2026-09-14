@extends('layouts.app')

@section('title', __('ChapConnect - Manage Comments'))

@section('styles')
<style>
    /* Responsive Spacing & Layout Overrides */
    .comments-main-container {
        max-width: 100%;
        width: 100%;
        margin: 15px 0;
        padding: 0 24px;
    }

    .comments-pdetails {
        background: #ffffff;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid var(--border-color, #e2e8f0);
    }

    .comments-page-header {
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

    .comments-layout-grid {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 22px;
        align-items: start;
    }

    .comments-sidebar-stack {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .comment-card-box {
        background: #f8fafc;
        padding: 16px;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        gap: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .comment-card-box:hover {
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
    }

    .nested-replies-container {
        margin-top: 4px;
        margin-left: 18px;
        padding-left: 12px;
        border-left: 3px solid #6366f1;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* Mobile Screen Enhancements (< 992px) */
    @media (max-width: 992px) {
        .comments-layout-grid {
            grid-template-columns: 1fr !important;
            gap: 18px !important;
        }

        .comments-sidebar-stack {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 14px;
        }
    }

    @media (max-width: 768px) {
        .comments-main-container {
            padding: 0 6px !important;
            margin: 8px 0 !important;
        }

        .comments-pdetails {
            padding: 14px 12px !important;
            border-radius: 14px !important;
        }

        .comments-page-header {
            padding: 14px 14px !important;
            border-radius: 12px !important;
            margin-bottom: 16px !important;
            gap: 8px !important;
        }

        .comments-page-title {
            font-size: 1.15rem !important;
        }

        .comments-count-badge {
            font-size: 0.76rem !important;
            padding: 4px 12px !important;
        }

        .comments-sidebar-stack {
            grid-template-columns: 1fr !important;
            gap: 12px !important;
        }

        .comment-card-box {
            padding: 12px 10px !important;
            border-radius: 12px !important;
        }

        .nested-replies-container {
            margin-left: 4px !important;
            padding-left: 8px !important;
            border-left-width: 2px !important;
        }

        .comment-action-btn {
            padding: 3px 8px !important;
            font-size: 0.72rem !important;
        }
    }
</style>
@endsection

@section('content')
<main class="main admin-main-container comments-main-container">
    <div class="dashboard-container">
        <!-- Main Content Area: Comments Manager -->
        <div class="pdetails comments-pdetails">

            <!-- Page Header Banner -->
            <div class="comments-page-header">
                <div>
                    <h2 class="comments-page-title" style="margin: 0 0 4px 0; font-size: 1.25rem; font-weight: 800; color: #0f172a; border: none; padding: 0; display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-chat-left-text-fill" style="color: var(--primary);"></i> {{ __('Comments & Feedback') }}
                    </h2>
                    <p style="margin: 0; color: #64748b; font-size: 0.82rem;">{{ __('Manage profile comments, reply to fans, or moderate messages.') }}</p>
                </div>
                <div class="comments-count-badge" style="background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.25); padding: 5px 14px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; color: #4f46e5;">
                    {{ __('Total Comments:') }} {{ $totalComments }}
                </div>
            </div>

            <!-- Dashboard Comments Layout Grid -->
            <div class="comments-layout-grid">

                <!-- Left Sidebar: Comments Summary & Moderation Info -->
                <div class="comments-sidebar-stack">

                    <!-- Stats Card -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px;">
                        <h4 style="margin: 0 0 12px 0; font-size: 0.9rem; font-weight: 800; color: #0f172a; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                            <i class="bi bi-bar-chart-fill" style="color: #6366f1;"></i> {{ __('Overview') }}
                        </h4>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 0.82rem; color: #64748b; font-weight: 600;">{{ __('Total Received') }}</span>
                                <span style="font-size: 0.9rem; font-weight: 800; color: #0f172a;">{{ $totalComments }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 0.82rem; color: #64748b; font-weight: 600;">{{ __('Direct Reviews') }}</span>
                                <span style="font-size: 0.9rem; font-weight: 800; color: #6366f1;">{{ $totalTopLevel }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 0.82rem; color: #64748b; font-weight: 600;">{{ __('Replies Posted') }}</span>
                                <span style="font-size: 0.9rem; font-weight: 800; color: #10b981;">{{ $totalReplies }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Guidelines & Moderation Rights Card -->
                    <div style="background: linear-gradient(135deg, rgba(99,102,241,0.06) 0%, rgba(79,70,229,0.1) 100%); border: 1px solid rgba(99,102,241,0.2); border-radius: 14px; padding: 16px;">
                        <h4 style="margin: 0 0 8px 0; font-size: 0.88rem; font-weight: 800; color: #4338ca; display: flex; align-items: center; gap: 6px;">
                            <i class="bi bi-shield-check" style="font-size: 1.05rem;"></i> {{ __('Moderation Control') }}
                        </h4>
                        <p style="margin: 0 0 10px 0; font-size: 0.78rem; color: #475569; line-height: 1.45;">
                            {{ __('Delete comments or replies that violate rules or contain inappropriate content.') }}
                        </p>
                        <a href="{{ route('profile', auth()->id()) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 5px; font-size: 0.78rem; font-weight: 700; color: #6366f1; text-decoration: none;">
                            {{ __('View Public Profile') }} <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                </div>

                <!-- Right Main Content: Received Comments Stream -->
                <div style="min-width: 0;">
                    <h3 style="font-size: 0.98rem; font-weight: 800; color: #0f172a; margin-bottom: 14px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px; display: flex; align-items: center; justify-content: space-between;">
                        <span><i class="bi bi-chat-dots-fill" style="color: #6366f1; margin-right: 6px;"></i> {{ __('Comments Stream') }}</span>
                        <span style="font-size: 0.78rem; font-weight: 600; color: #64748b;">{{ $comments->count() }} {{ __('items') }}</span>
                    </h3>

                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        @forelse($comments as $cmt)
                        <div class="comment-card-box">

                            <!-- Comment Header -->
                            <div style="display: flex; gap: 10px; align-items: flex-start;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: {{ $cmt->user_id == auth()->id() ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)' : 'linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)' }}; color: #ffffff; font-weight: 800; font-size: 0.88rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 6px rgba(99,102,241,0.25);">
                                    {{ strtoupper(substr($cmt->author_name, 0, 1)) }}
                                </div>
                                <div style="flex-grow: 1; min-width: 0;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; flex-wrap: wrap; gap: 6px;">
                                        <strong style="color: #0f172a; font-size: 0.88rem; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; flex-wrap: wrap;">
                                            <span>{{ $cmt->author_name }}</span>
                                            @if($cmt->user_id == auth()->id())
                                            <span style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; font-size: 0.65rem; font-weight: 700; padding: 2px 6px; border-radius: 8px; display: inline-flex; align-items: center; gap: 2px;">
                                                <i class="bi bi-patch-check-fill"></i> {{ __('Owner') }}
                                            </span>
                                            @endif
                                        </strong>

                                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                            <span style="font-size: 0.72rem; color: #94a3b8; font-weight: 600;"><i class="bi bi-clock"></i> {{ $cmt->created_at->diffForHumans() }}</span>

                                            <!-- Reply Button -->
                                            <button type="button" class="comment-action-btn" onclick="toggleDashboardReplyBox({{ $cmt->id }})" style="padding: 4px 9px; border-radius: 6px; font-size: 0.74rem; font-weight: 700; background: rgba(99,102,241,0.1); color: #6366f1; border: 1px solid rgba(99,102,241,0.2); cursor: pointer; display: inline-flex; align-items: center; gap: 3px;" title="{{ __('Reply to this comment') }}">
                                                <i class="bi bi-reply-fill"></i> {{ __('Reply') }}
                                            </button>

                                            <!-- Delete Button -->
                                            <form action="{{ route('talent.comment.delete', $cmt->id) }}" method="POST" onsubmit="return confirm('{{ __('Delete this comment?') }}');" style="margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="comment-action-btn" style="padding: 4px 9px; border-radius: 6px; font-size: 0.74rem; font-weight: 700; background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); cursor: pointer; display: inline-flex; align-items: center; gap: 3px;" title="{{ __('Delete this comment') }}">
                                                    <i class="bi bi-trash-fill"></i> {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <p style="margin: 0; color: #334155; font-size: 0.86rem; line-height: 1.45; white-space: pre-line; word-break: break-word;">{{ $cmt->comment }}</p>
                                </div>
                            </div>

                            <!-- Inline Reply Form -->
                            <div id="dash-reply-box-{{ $cmt->id }}" style="display: none; margin-top: 6px; background: #ffffff; padding: 12px; border-radius: 10px; border: 1px solid #cbd5e1; box-shadow: 0 4px 12px rgba(0,0,0,0.04);">
                                <form action="{{ route('talent.comment', auth()->id()) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="parent_id" value="{{ $cmt->id }}">
                                    <div style="display: flex; gap: 8px; align-items: flex-start;">
                                        <div style="width: 30px; height: 30px; border-radius: 50%; background: #6366f1; color: #fff; font-size: 0.78rem; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                        <div style="flex-grow: 1;">
                                            <textarea name="comment" rows="2" class="form-control" placeholder="{{ __('Write response...') }}" required style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 10px; font-size: 0.84rem; line-height: 1.4; background: #f8fafc; margin-bottom: 8px;"></textarea>
                                            <div style="display: flex; justify-content: flex-end; gap: 6px;">
                                                <button type="button" onclick="toggleDashboardReplyBox({{ $cmt->id }})" style="padding: 5px 12px; border-radius: 6px; font-size: 0.78rem; font-weight: 600; background: #f1f5f9; color: #475569; border: none; cursor: pointer;">{{ __('Cancel') }}</button>
                                                <button type="submit" style="padding: 5px 16px; border-radius: 6px; font-size: 0.78rem; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; border: none; cursor: pointer; box-shadow: 0 2px 6px rgba(99,102,241,0.3); display: inline-flex; align-items: center; gap: 4px;">
                                                    <i class="bi bi-send-fill"></i> {{ __('Post Reply') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Nested Replies Stream -->
                            @if($cmt->replies->count() > 0)
                            <div class="nested-replies-container">
                                @foreach($cmt->replies as $reply)
                                <div style="background: #ffffff; padding: 10px 12px; border-radius: 10px; border: 1px solid #e2e8f0; display: flex; gap: 8px; align-items: flex-start;">
                                    <div style="width: 28px; height: 28px; border-radius: 50%; background: {{ $reply->user_id == auth()->id() ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)' : '#475569' }}; color: #ffffff; font-weight: 800; font-size: 0.78rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        {{ strtoupper(substr($reply->author_name, 0, 1)) }}
                                    </div>
                                    <div style="flex-grow: 1; min-width: 0;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3px; flex-wrap: wrap; gap: 4px;">
                                            <strong style="color: #0f172a; font-size: 0.82rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; flex-wrap: wrap;">
                                                <span>{{ $reply->author_name }}</span>
                                                @if($reply->user_id == auth()->id())
                                                <span style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; font-size: 0.65rem; font-weight: 700; padding: 2px 6px; border-radius: 8px; display: inline-flex; align-items: center; gap: 2px;">
                                                    <i class="bi bi-patch-check-fill"></i> {{ __('Owner') }}
                                                </span>
                                                @endif
                                            </strong>

                                            <div style="display: flex; align-items: center; gap: 6px;">
                                                <span style="font-size: 0.70rem; color: #94a3b8; font-weight: 600;"><i class="bi bi-clock"></i> {{ $reply->created_at->diffForHumans() }}</span>
                                                <form action="{{ route('talent.comment.delete', $reply->id) }}" method="POST" onsubmit="return confirm('{{ __('Delete this reply?') }}');" style="margin: 0;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="padding: 2px 6px; border-radius: 5px; font-size: 0.70rem; font-weight: 700; background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); cursor: pointer; display: inline-flex; align-items: center; gap: 2px;" title="{{ __('Delete this reply') }}">
                                                        <i class="bi bi-trash-fill"></i> {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <p style="margin: 0; color: #334155; font-size: 0.82rem; line-height: 1.4; white-space: pre-line; word-break: break-word;">{{ $reply->comment }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif

                        </div>
                        @empty
                        <div style="padding: 40px 15px; text-align: center; background: #f8fafc; border-radius: 14px; border: 1px dashed #cbd5e1;">
                            <i class="bi bi-chat-left-dots" style="font-size: 2.2rem; color: #94a3b8; display: block; margin-bottom: 8px;"></i>
                            <p style="margin: 0; color: #64748b; font-weight: 600; font-size: 0.88rem;">{{ __('No comments received yet.') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    function toggleDashboardReplyBox(commentId) {
        const box = document.getElementById('dash-reply-box-' + commentId);
        if (box) {
            if (box.style.display === 'none' || !box.style.display) {
                box.style.display = 'block';
            } else {
                box.style.display = 'none';
            }
        }
    }
</script>
@endsection

