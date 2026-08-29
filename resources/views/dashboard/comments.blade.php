@extends('layouts.app')

@section('title', 'ChapConnect - Manage Comments')

@section('content')
<main class="main admin-main-container" style="max-width: 100%; width: 100%; margin: 15px 0; padding: 0 30px;">
    <div class="dashboard-container">
        <!-- Main Content Area: Comments Manager -->
        <div class="pdetails" style="background: #ffffff; border-radius: 16px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
            
            <!-- Page Header Banner -->
            <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; padding: 24px 28px; border-radius: 14px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h2 style="margin: 0 0 6px 0; font-size: 1.35rem; font-weight: 800; color: #ffffff; border: none; padding: 0; display: flex; align-items: center; gap: 10px;">
                        <i class="bi bi-chat-left-text-fill" style="color: #6366f1;"></i> Comments &amp; Fan Feedback
                    </h2>
                    <p style="margin: 0; color: #94a3b8; font-size: 0.88rem;">Review comments received on your profile, reply directly to fans, or remove inappropriate messages.</p>
                </div>
                <div style="background: rgba(99,102,241,0.2); border: 1px solid rgba(99,102,241,0.4); padding: 6px 16px; border-radius: 20px; font-weight: 700; font-size: 0.82rem; color: #a5b4fc;">
                    Total Comments: {{ $totalComments }}
                </div>
            </div>

            <!-- Dashboard Comments Layout Grid -->
            <div style="display: grid; grid-template-columns: 280px 1fr; gap: 24px; align-items: start;">
                
                <!-- Left Sidebar: Comments Summary & Moderation Info -->
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    
                    <!-- Stats Card -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px;">
                        <h4 style="margin: 0 0 15px 0; font-size: 0.95rem; font-weight: 800; color: #0f172a; border-bottom: 1px solid #cbd5e1; padding-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-bar-chart-fill" style="color: #6366f1;"></i> Feedback Overview
                        </h4>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 0.84rem; color: #64748b; font-weight: 600;">Total Received</span>
                                <span style="font-size: 0.95rem; font-weight: 800; color: #0f172a;">{{ $totalComments }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 0.84rem; color: #64748b; font-weight: 600;">Direct Fan Reviews</span>
                                <span style="font-size: 0.95rem; font-weight: 800; color: #6366f1;">{{ $totalTopLevel }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 0.84rem; color: #64748b; font-weight: 600;">Replies Posted</span>
                                <span style="font-size: 0.95rem; font-weight: 800; color: #10b981;">{{ $totalReplies }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Guidelines & Moderation Rights Card -->
                    <div style="background: linear-gradient(135deg, rgba(99,102,241,0.06) 0%, rgba(79,70,229,0.1) 100%); border: 1px solid rgba(99,102,241,0.2); border-radius: 14px; padding: 20px;">
                        <h4 style="margin: 0 0 10px 0; font-size: 0.92rem; font-weight: 800; color: #4338ca; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-shield-check" style="font-size: 1.1rem;"></i> Moderation Control
                        </h4>
                        <p style="margin: 0 0 12px 0; font-size: 0.8rem; color: #475569; line-height: 1.5;">
                            As the profile owner, you have full authority to delete comments or replies that violate rules, contain hate speech, or abuse.
                        </p>
                        <a href="{{ route('profile', auth()->id()) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.8rem; font-weight: 700; color: #6366f1; text-decoration: none;">
                            View Public Profile Page <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                </div>

                <!-- Right Main Content: Received Comments Stream -->
                <div>
                    <h3 style="font-size: 1.05rem; font-weight: 700; color: #0f172a; margin-bottom: 18px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; display: flex; align-items: center; justify-content: space-between;">
                        <span><i class="bi bi-chat-dots-fill" style="color: #6366f1; margin-right: 6px;"></i> Comments Stream</span>
                        <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">{{ $comments->count() }} items</span>
                    </h3>

                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        @forelse($comments as $cmt)
                        <div style="background: #f8fafc; padding: 18px; border-radius: 14px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                            
                            <!-- Comment Header -->
                            <div style="display: flex; gap: 12px; align-items: flex-start;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: {{ $cmt->user_id == auth()->id() ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)' : 'linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)' }}; color: #ffffff; font-weight: 800; font-size: 0.95rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 8px rgba(99,102,241,0.25);">
                                    {{ strtoupper(substr($cmt->author_name, 0, 1)) }}
                                </div>
                                <div style="flex-grow: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; flex-wrap: wrap; gap: 8px;">
                                        <strong style="color: #0f172a; font-size: 0.92rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                                            {{ $cmt->author_name }}
                                            @if($cmt->user_id == auth()->id())
                                                <span style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; font-size: 0.68rem; font-weight: 700; padding: 2px 7px; border-radius: 10px; display: inline-flex; align-items: center; gap: 3px;">
                                                    <i class="bi bi-patch-check-fill"></i> Owner
                                                </span>
                                            @endif
                                        </strong>
                                        
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <span style="font-size: 0.76rem; color: #94a3b8; font-weight: 600;"><i class="bi bi-clock"></i> {{ $cmt->created_at->diffForHumans() }}</span>
                                            
                                            <!-- Reply Button -->
                                            <button type="button" onclick="toggleDashboardReplyBox({{ $cmt->id }})" style="padding: 4px 10px; border-radius: 6px; font-size: 0.76rem; font-weight: 700; background: rgba(99,102,241,0.1); color: #6366f1; border: 1px solid rgba(99,102,241,0.2); cursor: pointer; display: inline-flex; align-items: center; gap: 4px;" title="Reply to this comment">
                                                <i class="bi bi-reply-fill"></i> Reply
                                            </button>

                                            <!-- Delete Button -->
                                            <form action="{{ route('talent.comment.delete', $cmt->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this comment?');" style="margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="padding: 4px 10px; border-radius: 6px; font-size: 0.76rem; font-weight: 700; background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); cursor: pointer; display: inline-flex; align-items: center; gap: 4px;" title="Delete this comment">
                                                    <i class="bi bi-trash-fill"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <p style="margin: 0; color: #334155; font-size: 0.88rem; line-height: 1.5; white-space: pre-line;">{{ $cmt->comment }}</p>
                                </div>
                            </div>

                            <!-- Inline Reply Form -->
                            <div id="dash-reply-box-{{ $cmt->id }}" style="display: none; margin-top: 6px; background: #ffffff; padding: 14px; border-radius: 10px; border: 1px solid #cbd5e1; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                                <form action="{{ route('talent.comment', auth()->id()) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="parent_id" value="{{ $cmt->id }}">
                                    <div style="display: flex; gap: 10px; align-items: flex-start;">
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background: #6366f1; color: #fff; font-size: 0.82rem; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                        <div style="flex-grow: 1;">
                                            <textarea name="comment" rows="2" class="form-control" placeholder="Write an official response to this comment..." required style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 12px; font-size: 0.86rem; line-height: 1.4; background: #f8fafc; margin-bottom: 10px;"></textarea>
                                            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                                <button type="button" onclick="toggleDashboardReplyBox({{ $cmt->id }})" style="padding: 6px 14px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; background: #f1f5f9; color: #475569; border: none; cursor: pointer;">Cancel</button>
                                                <button type="submit" style="padding: 6px 18px; border-radius: 6px; font-size: 0.8rem; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; border: none; cursor: pointer; box-shadow: 0 2px 6px rgba(99,102,241,0.3); display: inline-flex; align-items: center; gap: 5px;">
                                                    <i class="bi bi-send-fill"></i> Post Reply
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Nested Replies Stream -->
                            @if($cmt->replies->count() > 0)
                            <div style="margin-top: 4px; margin-left: 20px; padding-left: 14px; border-left: 3px solid #6366f1; display: flex; flex-direction: column; gap: 10px;">
                                @foreach($cmt->replies as $reply)
                                <div style="background: #ffffff; padding: 12px 16px; border-radius: 10px; border: 1px solid #e2e8f0; display: flex; gap: 10px; align-items: flex-start;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ $reply->user_id == auth()->id() ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)' : '#475569' }}; color: #ffffff; font-weight: 800; font-size: 0.82rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        {{ strtoupper(substr($reply->author_name, 0, 1)) }}
                                    </div>
                                    <div style="flex-grow: 1;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; flex-wrap: wrap; gap: 6px;">
                                            <strong style="color: #0f172a; font-size: 0.86rem; font-weight: 700; display: inline-flex; align-items: center; gap: 5px;">
                                                {{ $reply->author_name }}
                                                @if($reply->user_id == auth()->id())
                                                    <span style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; font-size: 0.68rem; font-weight: 700; padding: 2px 7px; border-radius: 10px; display: inline-flex; align-items: center; gap: 3px;">
                                                        <i class="bi bi-patch-check-fill"></i> Owner
                                                    </span>
                                                @endif
                                            </strong>
                                            
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <span style="font-size: 0.74rem; color: #94a3b8; font-weight: 600;"><i class="bi bi-clock"></i> {{ $reply->created_at->diffForHumans() }}</span>
                                                <form action="{{ route('talent.comment.delete', $reply->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this reply?');" style="margin: 0;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="padding: 3px 8px; border-radius: 5px; font-size: 0.72rem; font-weight: 700; background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); cursor: pointer; display: inline-flex; align-items: center; gap: 3px;" title="Delete this reply">
                                                        <i class="bi bi-trash-fill"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <p style="margin: 0; color: #334155; font-size: 0.84rem; line-height: 1.45; white-space: pre-line;">{{ $reply->comment }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif

                        </div>
                        @empty
                        <div style="padding: 50px 20px; text-align: center; background: #f8fafc; border-radius: 14px; border: 1px dashed #cbd5e1;">
                            <i class="bi bi-chat-left-dots" style="font-size: 2.5rem; color: #94a3b8; display: block; margin-bottom: 10px;"></i>
                            <p style="margin: 0; color: #64748b; font-weight: 600; font-size: 0.92rem;">You have not received any comments on your profile yet.</p>
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
