@extends('layouts.app')

@section('title', 'ChapConnect - Content Moderation & NSFW Review Queue')

@section('content')
<div class="admin-main-container" style="width: 100%; max-width: 1400px; margin: 0 auto; padding: 24px 28px; box-sizing: border-box;">
    
    <!-- Flash Messages / Toast Alerts -->
    <div style="margin-bottom: 20px;">
        @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #065f46; padding: 14px 20px; border-radius: 14px; font-weight: 600; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.08);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="bi bi-check-circle-fill" style="color: #10b981; font-size: 1.2rem;"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="$(this).parent().fadeOut();" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #065f46;">&times;</button>
        </div>
        @endif

        @if(session('error'))
        <div style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); color: #991b1b; padding: 14px 20px; border-radius: 14px; font-weight: 600; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.08);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="bi bi-exclamation-triangle-fill" style="color: #ef4444; font-size: 1.2rem;"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="$(this).parent().fadeOut();" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #991b1b;">&times;</button>
        </div>
        @endif
    </div>

    <!-- Hero Header Section -->
    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; padding: 26px 30px; border-radius: 20px; margin-bottom: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.12); border: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
            <div style="width: 56px; height: 56px; border-radius: 16px; background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #f87171; display: flex; align-items: center; justify-content: center; font-size: 1.7rem; flex-shrink: 0; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.25);">
                <i class="bi bi-shield-shaded"></i>
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <h1 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #ffffff;">
                        {{ __('Automated Content Moderation Queue') }}
                    </h1>
                    @if($counts['flagged'] > 0)
                    <span style="background: #ef4444; color: #fff; font-size: 0.75rem; font-weight: 800; padding: 3px 10px; border-radius: 20px; letter-spacing: 0.5px; animation: pulse 2s infinite;">
                        {{ $counts['flagged'] }} PENDING ACTION
                    </span>
                    @endif
                </div>
                <p style="margin: 5px 0 0 0; color: #94a3b8; font-size: 0.88rem;">
                    {{ __('Review, approve, or permanently remove media flagged by AI heuristics or reported by the community.') }}
                </p>
            </div>
        </div>

        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <a href="{{ auth()->user()->role === 'customer_care' ? route('customer-care.dashboard') : route('admin.dashboard') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #fff; border-radius: 30px; font-weight: 700; font-size: 0.85rem; text-decoration: none; transition: all 0.2s ease;">
                <i class="bi bi-arrow-left"></i> {{ auth()->user()->role === 'customer_care' ? __('Back to Customer Care') : __('Back to Admin Dashboard') }}
            </a>
        </div>
    </div>

    <!-- Statistics Overview Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <!-- Flagged Card -->
        <a href="{{ route('admin.moderation', ['status' => 'flagged']) }}" style="text-decoration: none; color: inherit;">
            <div style="background: #ffffff; border-radius: 16px; padding: 18px 22px; border: 2px solid {{ $currentStatus === 'flagged' ? '#ef4444' : '#e2e8f0' }}; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 16px; transition: transform 0.2s ease, box-shadow 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="width: 48px; height: 48px; border-radius: 14px; background: rgba(239, 68, 68, 0.12); color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                    <i class="bi bi-exclamation-octagon-fill"></i>
                </div>
                <div>
                    <div style="font-size: 1.6rem; font-weight: 800; color: #ef4444;">{{ $counts['flagged'] }}</div>
                    <div style="font-size: 0.82rem; color: #64748b; font-weight: 700;">{{ __('Flagged by AI / Filters') }}</div>
                </div>
            </div>
        </a>

        <!-- All Items Card -->
        <a href="{{ route('admin.moderation', ['status' => 'all']) }}" style="text-decoration: none; color: inherit;">
            <div style="background: #ffffff; border-radius: 16px; padding: 18px 22px; border: 2px solid {{ $currentStatus === 'all' ? '#6366f1' : '#e2e8f0' }}; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 16px; transition: transform 0.2s ease, box-shadow 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="width: 48px; height: 48px; border-radius: 14px; background: rgba(99, 102, 241, 0.12); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                    <i class="bi bi-collection-fill"></i>
                </div>
                <div>
                    <div style="font-size: 1.6rem; font-weight: 800; color: #0f172a;">{{ $counts['flagged'] + $counts['rejected'] }}</div>
                    <div style="font-size: 0.82rem; color: #64748b; font-weight: 700;">{{ __('All Moderation Items') }}</div>
                </div>
            </div>
        </a>

        <!-- Rejected History Card -->
        <a href="{{ route('admin.moderation', ['status' => 'rejected']) }}" style="text-decoration: none; color: inherit;">
            <div style="background: #ffffff; border-radius: 16px; padding: 18px 22px; border: 2px solid {{ $currentStatus === 'rejected' ? '#475569' : '#e2e8f0' }}; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 16px; transition: transform 0.2s ease, box-shadow 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="width: 48px; height: 48px; border-radius: 14px; background: rgba(71, 85, 105, 0.12); color: #475569; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                    <i class="bi bi-slash-circle-fill"></i>
                </div>
                <div>
                    <div style="font-size: 1.6rem; font-weight: 800; color: #475569;">{{ $counts['rejected'] }}</div>
                    <div style="font-size: 0.82rem; color: #64748b; font-weight: 700;">{{ __('Rejected & Removed History') }}</div>
                </div>
            </div>
        </a>
    </div>

    <!-- Filter Pills Navigation Bar -->
    <div style="background: #ffffff; padding: 12px 18px; border-radius: 16px; border: 1px solid #e2e8f0; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('admin.moderation', ['status' => 'flagged']) }}" style="text-decoration: none; padding: 8px 18px; border-radius: 25px; font-size: 0.84rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; {{ $currentStatus === 'flagged' ? 'background: #ef4444; color: #ffffff; box-shadow: 0 4px 12px rgba(239,68,68,0.3);' : 'background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">
                <i class="bi bi-shield-exclamation"></i> {{ __('Flagged Pending Action') }} ({{ $counts['flagged'] }})
            </a>
            <a href="{{ route('admin.moderation', ['status' => 'all']) }}" style="text-decoration: none; padding: 8px 18px; border-radius: 25px; font-size: 0.84rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; {{ $currentStatus === 'all' ? 'background: #6366f1; color: #ffffff; box-shadow: 0 4px 12px rgba(99,102,241,0.3);' : 'background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">
                <i class="bi bi-collection"></i> {{ __('All Flagged & Reported') }}
            </a>
            <a href="{{ route('admin.moderation', ['status' => 'rejected']) }}" style="text-decoration: none; padding: 8px 18px; border-radius: 25px; font-size: 0.84rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; {{ $currentStatus === 'rejected' ? 'background: #475569; color: #ffffff; box-shadow: 0 4px 12px rgba(71,85,105,0.3);' : 'background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;' }}">
                <i class="bi bi-trash3-fill"></i> {{ __('Rejected History') }} ({{ $counts['rejected'] }})
            </a>
        </div>

        <div style="font-size: 0.82rem; color: #94a3b8; font-weight: 600;">
            <i class="bi bi-info-circle"></i> {{ __('Auto-hides explicit content from public feeds until reviewed') }}
        </div>
    </div>

    <!-- Moderation Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 24px;">
        @forelse($mediaItems as $item)
        <div style="background: #ffffff; border: 1.5px solid {{ $item->moderation_status === 'flagged' ? '#fecaca' : '#e2e8f0' }}; border-radius: 20px; overflow: hidden; box-shadow: 0 6px 20px rgba(0,0,0,0.05); display: flex; flex-direction: column; transition: all 0.25s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 6px 20px rgba(0,0,0,0.05)';">
            
            <!-- Media Preview Banner -->
            <div style="position: relative; width: 100%; height: 260px; background: #0f172a; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                @if($item->type === 'photo')
                <img id="media-img-{{ $item->id }}" src="{{ asset($item->file_path) }}" alt="{{ $item->title ?? 'Flagged Photo' }}" style="width: 100%; height: 100%; object-fit: contain; background: #020617; filter: {{ $item->moderation_status === 'flagged' ? 'blur(6px)' : 'none' }}; transition: filter 0.3s ease;">
                
                <!-- Unblur Toggle Button -->
                <button type="button" onclick="toggleBlur({{ $item->id }})" style="position: absolute; bottom: 12px; right: 12px; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(8px); color: #ffffff; border: 1px solid rgba(255,255,255,0.25); padding: 6px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
                    <i class="bi bi-eye-fill"></i> <span id="blur-btn-text-{{ $item->id }}">{{ __('Reveal Preview') }}</span>
                </button>
                @elseif($item->type === 'video')
                <div style="color: #fff; text-align: center; padding: 25px;">
                    <i class="bi bi-play-circle-fill" style="font-size: 3.5rem; color: #ef4444;"></i>
                    <p style="margin: 10px 0 0 0; font-size: 0.82rem; color: #cbd5e1; word-break: break-all; max-width: 90%;">
                        {{ $item->file_path }}
                    </p>
                </div>
                @endif

                <!-- Floating Status Badge -->
                <span style="position: absolute; top: 14px; left: 14px; background: {{ $item->moderation_status === 'flagged' ? 'rgba(239, 68, 68, 0.95)' : 'rgba(71, 85, 105, 0.95)' }}; color: #fff; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 800; display: inline-flex; align-items: center; gap: 5px; box-shadow: 0 3px 10px rgba(0,0,0,0.3); letter-spacing: 0.5px;">
                    <i class="bi {{ $item->moderation_status === 'flagged' ? 'bi-shield-exclamation' : 'bi-slash-circle' }}"></i> {{ strtoupper($item->moderation_status) }}
                </span>

                <!-- Floating Community Report Badge -->
                @if($item->report_count > 0)
                <span style="position: absolute; top: 14px; right: 14px; background: rgba(245, 158, 11, 0.95); color: #fff; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 800; display: inline-flex; align-items: center; gap: 5px; box-shadow: 0 3px 10px rgba(0,0,0,0.3);">
                    <i class="bi bi-flag-fill"></i> {{ $item->report_count }} {{ __('Reports') }}
                </span>
                @endif
            </div>

            <!-- Card Information Content -->
            <div style="padding: 20px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <!-- Top Metadata -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <span style="background: #f1f5f9; color: #475569; padding: 3px 10px; border-radius: 6px; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">
                            {{ $item->type }}
                        </span>
                        <span style="font-size: 0.78rem; color: #94a3b8; font-weight: 600;">
                            <i class="bi bi-clock"></i> {{ $item->created_at->diffForHumans() }}
                        </span>
                    </div>

                    <!-- Media Title & Caption -->
                    <h3 style="margin: 0 0 8px 0; font-size: 1.05rem; font-weight: 800; color: #0f172a; line-height: 1.4;">
                        {{ $item->title ?: __('Untitled Media Upload') }}
                    </h3>

                    @if($item->content)
                    <p style="margin: 0 0 12px 0; font-size: 0.84rem; color: #64748b; line-height: 1.4;">
                        "{{ Str::limit($item->content, 90) }}"
                    </p>
                    @endif

                    <!-- Talent Uploader Box -->
                    @if($item->user)
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px 14px; margin-bottom: 14px; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 10px; min-width: 0;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: #e0e7ff; color: #4338ca; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem; flex-shrink: 0;">
                                {{ substr($item->user->name, 0, 1) }}
                            </div>
                            <div style="min-width: 0;">
                                <div style="font-weight: 700; font-size: 0.88rem; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $item->user->name }}
                                </div>
                                <div style="font-size: 0.75rem; color: #64748b;">
                                    {{ $item->user->category_label ?? 'Talent' }} • {{ $item->user->phone ?? $item->user->email }}
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('profile', $item->user->id) }}" target="_blank" style="color: var(--primary); font-size: 0.78rem; font-weight: 700; text-decoration: none; flex-shrink: 0; display: flex; align-items: center; gap: 3px;">
                            {{ __('Profile') }} <i class="bi bi-box-arrow-up-right" style="font-size: 0.7rem;"></i>
                        </a>
                    </div>
                    @endif

                    <!-- Automated Flag Reason Box -->
                    @if($item->moderation_reason)
                    <div style="background: #fff1f2; border: 1px solid #fecdd3; border-radius: 10px; padding: 10px 14px; margin-bottom: 12px;">
                        <div style="display: flex; align-items: flex-start; gap: 8px;">
                            <i class="bi bi-exclamation-circle-fill" style="color: #e11d48; font-size: 1rem; margin-top: 1px; flex-shrink: 0;"></i>
                            <div style="font-size: 0.8rem; color: #9f1239; line-height: 1.4;">
                                <strong>{{ __('AI Detection') }}:</strong> {{ $item->moderation_reason }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Community Reports Accordion if any -->
                    @if($item->reports->isNotEmpty())
                    <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 10px 14px; margin-bottom: 14px;">
                        <div style="font-size: 0.78rem; font-weight: 800; color: #92400e; display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                            <i class="bi bi-flag-fill"></i> {{ __('Community Reports Submitted') }} ({{ $item->reports->count() }}):
                        </div>
                        <ul style="margin: 0 0 0 16px; padding: 0; font-size: 0.76rem; color: #78350f;">
                            @foreach($item->reports->take(3) as $rep)
                            <li style="margin-bottom: 3px;">
                                <strong>{{ ucfirst(str_replace('_', ' ', $rep->reason)) }}:</strong> {{ $rep->details ?: __('User flagged this content as inappropriate') }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>

                <!-- Action Controls Toolbar -->
                <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 15px; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                    <div style="display: flex; gap: 10px;">
                        <!-- Approve & Make Live Button -->
                        <form action="{{ route('admin.moderation.approve', $item->id) }}" method="POST" style="flex: 1;">
                            @csrf
                            <button type="submit" style="width: 100%; padding: 10px 14px; background: #10b981; color: #ffffff; border: none; border-radius: 10px; font-weight: 700; font-size: 0.84rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25); transition: all 0.2s ease;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                                <i class="bi bi-check2-circle" style="font-size: 1rem;"></i> {{ __('Approve (Make Live)') }}
                            </button>
                        </form>

                        <!-- Reject & Delete Button -->
                        <form action="{{ route('admin.moderation.reject', $item->id) }}" method="POST" style="flex: 1;" onsubmit="return confirm('⚠️ Are you sure you want to permanently delete this prohibited media?');">
                            @csrf
                            <button type="submit" style="width: 100%; padding: 10px 14px; background: #ef4444; color: #ffffff; border: none; border-radius: 10px; font-weight: 700; font-size: 0.84rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; box-shadow: 0 2px 8px rgba(239, 68, 68, 0.25); transition: all 0.2s ease;" onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'">
                                <i class="bi bi-trash3-fill" style="font-size: 0.95rem;"></i> {{ __('Delete Media') }}
                            </button>
                        </form>
                    </div>

                    <!-- Ban User Account Button -->
                    @if($item->user)
                    <form action="{{ route('admin.moderation.ban', $item->id) }}" method="POST" style="width: 100%;" onsubmit="return confirm('🚨 DANGER: This will immediately SUSPEND & BAN user {{ $item->user->name }} and permanently DELETE all their violating content. Proceed?');">
                        @csrf
                        <button type="submit" style="width: 100%; padding: 9px 14px; background: #0f172a; color: #f87171; border: 1px solid #334155; border-radius: 10px; font-weight: 700; font-size: 0.82rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; transition: all 0.2s ease;" onmouseover="this.style.background='#1e293b'; this.style.borderColor='#ef4444';" onmouseout="this.style.background='#0f172a'; this.style.borderColor='#334155';">
                            <i class="bi bi-slash-circle-fill"></i> {{ __('Delete & Ban User Account') }}
                        </button>
                    </form>
                    @endif
                </div>

            </div>
        </div>
        @empty
        <!-- Clean Empty State -->
        <div style="grid-column: 1 / -1; background: #ffffff; border-radius: 20px; border: 1.5px dashed #cbd5e1; padding: 60px 20px; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(16, 185, 129, 0.12); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 18px auto;">
                <i class="bi bi-shield-check"></i>
            </div>
            <h2 style="margin: 0 0 8px 0; color: #0f172a; font-weight: 800; font-size: 1.35rem;">
                {{ __('Moderation Queue is Completely Clean!') }}
            </h2>
            <p style="margin: 0 auto; max-width: 500px; color: #64748b; font-size: 0.9rem; line-height: 1.5;">
                {{ __('No pending explicit photos, videos, or user reports need review at this time. All uploaded content is complying with platform standards.') }}
            </p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div style="margin-top: 30px; display: flex; justify-content: center;">
        {{ $mediaItems->links() }}
    </div>
</div>

<script>
    function toggleBlur(id) {
        const img = document.getElementById('media-img-' + id);
        const btnText = document.getElementById('blur-btn-text-' + id);
        if (!img) return;

        if (img.style.filter === 'none') {
            img.style.filter = 'blur(6px)';
            if (btnText) btnText.innerText = '{{ __("Reveal Preview") }}';
        } else {
            img.style.filter = 'none';
            if (btnText) btnText.innerText = '{{ __("Hide / Blur") }}';
        }
    }
</script>
@endsection
