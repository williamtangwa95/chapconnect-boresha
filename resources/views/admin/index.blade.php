@extends('layouts.app')

@section('title', 'Chap Connect - Super Admin Panel')

@section('content')
<main class="main admin-main-container" style="max-width: 100%; width: 100%; margin: 15px 0;">
    <div class="dashboard-container">

        <!-- ==========================================
         TAB 1: Dashboard Overview content
         ========================================== -->
        <div id="tab-dashboard" class="tab-content">
            <!-- Executive Header Banner -->
            <div class="dashboard-header" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; padding: 24px 28px; border-radius: 18px; margin-bottom: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; border: 1px solid rgba(255,255,255,0.08);">
                <div class="dashboard-welcome" style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap; max-width: 100%;">
                    <div class="dashboard-avatar" style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); display: flex; align-items: center; justify-content: center; font-size: 26px; color: #fff; box-shadow: 0 4px 15px rgba(99,102,241,0.4); flex-shrink: 0;">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <div class="dashboard-welcome-text" style="flex: 1; min-width: 220px;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px; flex-wrap: wrap;">
                            <h2 style="color: #ffffff; margin: 0; font-size: 1.4rem; font-weight: 800; word-break: break-word;">Welcome, {{ auth()->user()->name }}</h2>
                            <span style="font-size: 0.72rem; font-weight: 700; background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); padding: 3px 9px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #34d399; display: inline-block;"></span> System Operational
                            </span>
                        </div>
                        <p style="color: #94a3b8; margin: 0; font-size: 0.88rem; line-height: 1.4;">Executive Command Center • Real-time platform oversight, talent analytics, and directory control.</p>
                    </div>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('home') }}" target="_blank" class="vbtn" style="width: auto; padding: 9px 18px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #fff; border-radius: 30px; font-weight: 700; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
                        <i class="bi bi-globe"></i> View Live Site
                    </a>
                </div>
            </div>

            <!-- Stats Cards Grid (5 Cards) -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 25px;">
                <!-- Metric 1: Registered Talents -->
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 20px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px; transition: transform 0.2s ease;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(99,102,241,0.12); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stat-info" style="flex-grow: 1;">
                        <div class="stat-value" style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">{{ $totalUsers }}</div>
                        <div class="stat-label" style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Registered Talents</div>
                    </div>
                </div>

                <!-- Metric 2: Registered Staff -->
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 20px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px; transition: transform 0.2s ease;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(14,165,233,0.12); color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <div class="stat-info" style="flex-grow: 1;">
                        <div class="stat-value" style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">{{ count($staffUsers) }}</div>
                        <div class="stat-label" style="font-size: 0.8rem; color: #64748b; font-weight: 600;">System Staff</div>
                    </div>
                </div>

                <!-- Metric 3: Active Categories -->
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 20px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px; transition: transform 0.2s ease;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16,185,129,0.12); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                        <i class="bi bi-tags-fill"></i>
                    </div>
                    <div class="stat-info" style="flex-grow: 1;">
                        <div class="stat-value" style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">{{ count($categories) }}</div>
                        <div class="stat-label" style="font-size: 0.8rem; color: #64748b; font-weight: 600;">System Categories</div>
                    </div>
                </div>

                <!-- Metric 4: Portfolio Photos -->
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 20px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px; transition: transform 0.2s ease;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(245,158,11,0.12); color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                        <i class="bi bi-camera-fill"></i>
                    </div>
                    <div class="stat-info" style="flex-grow: 1;">
                        <div class="stat-value" style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">{{ $totalPhotos }}</div>
                        <div class="stat-label" style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Portfolio Photos</div>
                    </div>
                </div>

                <!-- Metric 5: Portfolio Videos -->
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 20px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px; transition: transform 0.2s ease;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(236,72,153,0.12); color: #ec4899; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                        <i class="bi bi-camera-video-fill"></i>
                    </div>
                    <div class="stat-info" style="flex-grow: 1;">
                        <div class="stat-value" style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">{{ $totalVideos }}</div>
                        <div class="stat-label" style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Portfolio Videos</div>
                    </div>
                </div>
            </div>

            <!-- Two Column Section Grid -->
            <div class="admin-two-col-grid">
                <!-- Left Column: Recent Talents Overview Table -->
                <div class="admin-card" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                        <div>
                            <h2 style="margin: 0 0 4px 0; font-size: 1.15rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                                <i class="bi bi-clock-history" style="color: var(--primary);"></i> Recently Onboarded Talents
                            </h2>
                            <p style="margin: 0; color: #64748b; font-size: 0.85rem;">Latest creative talent accounts added to the platform.</p>
                        </div>
                        <a href="#talents" class="tab-link" data-tab="talents" style="font-size: 0.83rem; font-weight: 700; color: var(--primary); text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                            View All Talents →
                        </a>
                    </div>

                    <div class="admin-table-container">
                        <table id="recent-talents-table" class="admin-table display nowrap" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th style="width: 40px; text-align: center;">S/N</th>
                                    <th>Talent & Profile</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Registered</th>
                                    <th style="width: 100px; text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users->take(5) as $u)
                                <tr>
                                    <td style="font-weight: 700; color: #64748b; font-size: 0.82rem; text-align: center;">{{ $loop->iteration }}</td>
                                    <td style="font-weight: 700; color: #0f172a; font-size: 0.9rem;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            @if($u->profile_image)
                                            <img src="{{ asset($u->profile_image) }}" alt="{{ $u->name }}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border-color);" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop';">
                                            @else
                                            <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem;">
                                                {{ strtoupper(substr($u->name, 0, 1)) }}
                                            </div>
                                            @endif
                                            <div>
                                                <span style="display: block;">{{ $u->name }}</span>
                                                <span style="font-size: 0.75rem; color: #64748b; font-weight: 400;">{{ $u->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="color: #2563eb; font-weight: 600; background: rgba(37, 99, 235, 0.08); padding: 3px 8px; border-radius: 6px; font-size: 0.78rem; border: 1px solid rgba(37, 99, 235, 0.15);">
                                            {{ $u->category_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($u->is_published)
                                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:0.72rem;font-weight:700;padding:3px 9px;border-radius:20px;background:rgba(16,185,129,0.12);color:#10b981;border:1px solid rgba(16,185,129,0.25);"><span style="width:5px;height:5px;border-radius:50%;background:#10b981;display:inline-block;"></span>Live</span>
                                        @else
                                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:0.72rem;font-weight:700;padding:3px 9px;border-radius:20px;background:rgba(100,100,100,0.1);color:#64748b;border:1px solid rgba(200,200,200,0.3);"><span style="width:5px;height:5px;border-radius:50%;background:#94a3b8;display:inline-block;"></span>Draft</span>
                                        @endif
                                    </td>
                                    <td style="font-size: 0.8rem; color: #64748b;">{{ $u->created_at->format('M d') }}</td>
                                    <td style="text-align: right;">
                                        <a href="{{ route('profile', $u->id) }}" target="_blank" class="social-btn" style="padding: 4px 10px; font-size: 0.75rem; border: 1px solid #cbd5e1; color: #334155; border-radius: 6px; font-weight: 600; background: #fff; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="bi bi-box-arrow-up-right"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; color: #64748b; padding: 30px 0;">No registered users found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Right Column: Executive Shortcuts & Diagnostics -->
                <div style="display: flex; flex-direction: column; gap: 20px; min-width: 0; width: 100%;">
                    <!-- Quick Control Actions -->
                    <div class="admin-card" style="background: #ffffff; border-radius: 16px; padding: 22px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color); box-sizing: border-box; width: 100%;">
                        <h3 style="margin: 0 0 15px 0; font-size: 1.05rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                            <i class="bi bi-lightning-charge-fill" style="color: #f59e0b;"></i> Management Shortcuts
                        </h3>
                        <div style="display: flex; flex-direction: column; gap: 10px; width: 100%;">
                            <button type="button" onclick="$('#add-talent-modal').fadeIn(200);" style="width: 100%; box-sizing: border-box; text-align: left; padding: 10px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.88rem; font-weight: 600; color: #1e293b; cursor: pointer; display: flex; align-items: center; justify-content: space-between; gap: 10px; transition: all 0.2s ease;">
                                <span style="display: flex; align-items: center; gap: 8px; word-break: break-word;"><i class="bi bi-person-plus-fill" style="color: var(--primary); flex-shrink: 0;"></i> Register New Talent</span>
                                <i class="bi bi-chevron-right" style="font-size: 0.8rem; color: #94a3b8; flex-shrink: 0;"></i>
                            </button>
                            <button type="button" onclick="$('#add-category-modal').fadeIn(200);" style="width: 100%; box-sizing: border-box; text-align: left; padding: 10px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.88rem; font-weight: 600; color: #1e293b; cursor: pointer; display: flex; align-items: center; justify-content: space-between; gap: 10px; transition: all 0.2s ease;">
                                <span style="display: flex; align-items: center; gap: 8px; word-break: break-word;"><i class="bi bi-tag-fill" style="color: #10b981; flex-shrink: 0;"></i> Register New Category</span>
                                <i class="bi bi-chevron-right" style="font-size: 0.8rem; color: #94a3b8; flex-shrink: 0;"></i>
                            </button>
                            <a href="#settings" class="tab-link" data-tab="settings" style="width: 100%; box-sizing: border-box; text-align: left; padding: 10px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.88rem; font-weight: 600; color: #1e293b; text-decoration: none; display: flex; align-items: center; justify-content: space-between; gap: 10px; transition: all 0.2s ease;">
                                <span style="display: flex; align-items: center; gap: 8px; word-break: break-word;"><i class="bi bi-person-gear" style="color: #0284c7; flex-shrink: 0;"></i> Staff Account Profile</span>
                                <i class="bi bi-chevron-right" style="font-size: 0.8rem; color: #94a3b8; flex-shrink: 0;"></i>
                            </a>
                            <a href="{{ route('customer-care.dashboard') }}" style="width: 100%; box-sizing: border-box; text-align: left; padding: 10px 14px; background: rgba(99, 102, 241, 0.08); border: 1px solid rgba(99, 102, 241, 0.2); border-radius: 10px; font-size: 0.88rem; font-weight: 700; color: #4338ca; text-decoration: none; display: flex; align-items: center; justify-content: space-between; gap: 10px; transition: all 0.2s ease;">
                                <span style="display: flex; align-items: center; gap: 8px; word-break: break-word;"><i class="bi bi-headset" style="color: #6366f1; flex-shrink: 0;"></i> Customer Care Support Portal</span>
                                <i class="bi bi-arrow-right-short" style="font-size: 1.1rem; color: #6366f1; flex-shrink: 0;"></i>
                            </a>
                            <a href="{{ route('home') }}" target="_blank" style="width: 100%; box-sizing: border-box; text-align: left; padding: 10px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.88rem; font-weight: 600; color: #1e293b; text-decoration: none; display: flex; align-items: center; justify-content: space-between; gap: 10px; transition: all 0.2s ease;">
                                <span style="display: flex; align-items: center; gap: 8px; word-break: break-word;"><i class="bi bi-grid-fill" style="color: #8b5cf6; flex-shrink: 0;"></i> Browse Talent Directory</span>
                                <i class="bi bi-box-arrow-up-right" style="font-size: 0.8rem; color: #94a3b8; flex-shrink: 0;"></i>
                            </a>
                        </div>
                    </div>

                    <!-- System Status Info Card -->
                    <div class="admin-card" style="background: #ffffff; border-radius: 16px; padding: 22px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color); box-sizing: border-box; width: 100%;">
                        <h3 style="margin: 0 0 15px 0; font-size: 1.05rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                            <i class="bi bi-shield-check" style="color: #10b981;"></i> System Diagnostics
                        </h3>
                        <div style="display: flex; flex-direction: column; gap: 10px; font-size: 0.84rem; width: 100%;">
                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 6px; padding-bottom: 8px; border-bottom: 1px solid #f1f5f9;">
                                <span style="color: #64748b;">Database Engine</span>
                                <span style="font-weight: 700; color: #0f172a;">MySQL (Connected)</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 6px; padding-bottom: 8px; border-bottom: 1px solid #f1f5f9;">
                                <span style="color: #64748b;">Media Processing</span>
                                <span style="font-weight: 700; color: #10b981;">WebP Auto-Compress</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 6px;">
                                <span style="color: #64748b;">Staff Authority</span>
                                <span style="font-weight: 700; color: var(--primary);">Super Administrator</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==========================================================================
             Assigned Support Tickets Roster for Current Staff Member
           ========================================================================== -->
        <!-- <div class="admin-card" id="assigned-tickets-section" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color); margin-top: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h2 style="margin: 0 0 4px 0; font-size: 1.2rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-person-workspace" style="color: #6366f1;"></i> Support Tickets Assigned To Me
                        <span style="font-size: 0.78rem; background: rgba(99, 102, 241, 0.12); color: #6366f1; border: 1px solid rgba(99, 102, 241, 0.3); padding: 2px 9px; border-radius: 12px; font-weight: 800;">
                            {{ count($assignedTickets) }}
                        </span>
                    </h2>
                    <p style="margin: 0; color: #64748b; font-size: 0.85rem;">Review assigned issues, approve/cancel/resolve status, and add staff recommendations for addressed issues.</p>
                </div>
                <a href="{{ route('customer-care.dashboard') }}" style="font-size: 0.85rem; font-weight: 700; color: #6366f1; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                    <i class="bi bi-headset"></i> Open Operations Portal <i class="bi bi-arrow-right-short" style="font-size: 1.1rem;"></i>
                </a>
            </div>

            <div class="admin-table-container">
                <table class="admin-table display nowrap" id="assigned-staff-tickets-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="width: 110px;">Ticket #</th>
                            <th>Reporter</th>
                            <th>Subject & Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Staff Recommendation</th>
                            <th style="width: 130px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignedTickets as $ticket)
                        <tr>
                            <td>
                                <span style="font-family: monospace; font-weight: 800; font-size: 0.82rem; color: #4338ca; background: rgba(99,102,241,0.1); padding: 4px 9px; border-radius: 6px; border: 1px solid rgba(99,102,241,0.2);">
                                    {{ $ticket->ticket_number }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #0f172a; font-size: 0.88rem;">{{ $ticket->reporter_name }}</div>
                                <div style="font-size: 0.78rem; color: #64748b;">{{ $ticket->reporter_email }}</div>
                                @if($ticket->reporter_phone)
                                <div style="font-size: 0.75rem; color: #10b981; font-weight: 600;"><i class="bi bi-whatsapp"></i> {{ $ticket->reporter_phone }}</div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #1e293b; font-size: 0.88rem; margin-bottom: 3px;">{{ $ticket->subject }}</div>
                                <span style="font-size: 0.75rem; color: #475569; background: #f1f5f9; padding: 2px 8px; border-radius: 4px; font-weight: 600;">
                                    {{ $ticket->category }}
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 0.72rem; font-weight: 800; padding: 3px 10px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.4px; {{ $ticket->priority_badge_class }}">
                                    {{ $ticket->priority }}
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 0.78rem; font-weight: 700; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px; {{ $ticket->status_badge_class }}">
                                    @if(in_array($ticket->status, ['open', 'pending']))
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #ef4444; display: inline-block;"></span> Pending
                                    @elseif(in_array($ticket->status, ['in_progress', 'approved']))
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #f59e0b; display: inline-block;"></span> Approved / In Progress
                                    @elseif($ticket->status === 'resolved')
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981; display: inline-block;"></span> Resolved
                                    @else
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #64748b; display: inline-block;"></span> Cancelled / Closed
                                    @endif
                                </span>
                            </td>
                            <td style="max-width: 200px;">
                                @if($ticket->recommendations)
                                <div style="font-size: 0.8rem; color: #334155; line-height: 1.3; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 6px 10px;">
                                    <i class="bi bi-chat-quote-fill" style="color: #6366f1;"></i> {{ Str::limit($ticket->recommendations, 60) }}
                                </div>
                                @else
                                <span style="font-size: 0.78rem; color: #94a3b8; font-style: italic;">No recommendation</span>
                                @endif
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <button type="button" class="btn-staff-ticket-action"
                                    data-id="{{ $ticket->id }}"
                                    data-ticket="{{ $ticket->ticket_number }}"
                                    data-name="{{ $ticket->reporter_name }}"
                                    data-email="{{ $ticket->reporter_email }}"
                                    data-subject="{{ $ticket->subject }}"
                                    data-category="{{ $ticket->category }}"
                                    data-status="{{ $ticket->status }}"
                                    data-description="{{ $ticket->description }}"
                                    data-notes="{{ $ticket->resolution_notes }}"
                                    data-recommendations="{{ $ticket->recommendations }}"
                                    style="padding: 6px 14px; font-size: 0.78rem; border: 1px solid #6366f1; color: #fff; border-radius: 8px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); cursor: pointer; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 8px rgba(99,102,241,0.3);">
                                    <i class="bi bi-pencil-square"></i> Action & Recommend
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div> -->

        <!-- ==========================================
         TAB 2: Registered Talents content
         ========================================== -->
        <div id="tab-talents" class="tab-content">
            <div class="admin-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 25px;">
                <div>
                    <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
                        <i class="bi bi-people-fill" style="color: var(--primary);"></i> Registered Talents Directory
                    </h1>
                    <p style="color: #64748b; margin: 0; font-size: 0.92rem;">Review, edit profiles, publish/unpublish, or manage registered creative talent accounts.</p>
                </div>
                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <div style="background: rgba(99, 102, 241, 0.1); color: var(--primary); padding: 8px 18px; border-radius: 20px; font-weight: 700; font-size: 0.88rem; border: 1px solid rgba(99, 102, 241, 0.2);">
                        <i class="bi bi-person-check-fill"></i> Total Talents: {{ count($users) }}
                    </div>
                    <button type="button" id="btn-open-add-talent" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; border: none; border-radius: 30px; font-weight: 700; font-size: 0.9rem; cursor: pointer; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35); transition: all 0.3s ease;">
                        <i class="bi bi-person-plus-fill" style="font-size: 1.1rem;"></i> Register New Talent
                    </button>
                </div>
            </div>

            <div class="admin-card" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <h2 style="margin: 0 0 4px 0; font-size: 1.2rem; font-weight: 700; color: #0f172a;">Active Talents Roster</h2>
                        <p style="margin: 0; color: #64748b; font-size: 0.85rem;">Manage registered talent profiles, change publishing status, or reset credentials.</p>
                    </div>
                </div>

                <!-- Wrap table inside a bulk delete form -->
                <form id="bulk-delete-form" action="{{ route('admin.users.bulk-delete') }}" method="POST" style="margin: 0;">
                    @csrf
                    @method('DELETE')

                    <!-- Users Table -->
                    <div class="admin-table-container">
                        <table class="admin-table" id="admin-talents-table">
                            <thead>
                                <tr>
                                    <th style="width: 40px; text-align: center;">
                                        <input type="checkbox" id="select-all-talents" style="width: auto; cursor: pointer;">
                                    </th>
                                    <th style="width: 45px; text-align: center;">S/N</th>
                                    <th>Name & Profile</th>
                                    <th>Email Address</th>
                                    <th>Category</th>
                                    <th>Country</th>
                                    <th>Status</th>
                                    <th>Registered Date</th>
                                    <th style="width: 280px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $u)
                                <tr>
                                    <td style="text-align: center;">
                                        <input type="checkbox" name="ids[]" value="{{ $u->id }}" class="talent-checkbox" style="width: auto; cursor: pointer;">
                                    </td>
                                    <td style="font-weight: 700; color: #64748b; font-size: 0.85rem; text-align: center;">{{ $loop->iteration }}</td>
                                    <td style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            @if($u->profile_image)
                                            <img src="{{ $u->profile_image }}" alt="{{ $u->name }}" style="width: 34px; height: 34px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border-color);" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop';">
                                            @else
                                            <div style="width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem;">
                                                {{ strtoupper(substr($u->name, 0, 1)) }}
                                            </div>
                                            @endif
                                            <span>{{ $u->name }}</span>
                                        </div>
                                    </td>
                                    <td style="color: #64748b; font-size: 0.88rem;">{{ $u->email }}</td>
                                    <td>
                                        <span style="color: #2563eb; font-weight: 600; background: rgba(37, 99, 235, 0.08); padding: 4px 10px; border-radius: 6px; font-size: 0.82rem; border: 1px solid rgba(37, 99, 235, 0.2);">
                                            {{ $u->category_label }}
                                        </span>
                                    </td>
                                    <td style="font-size: 0.88rem; color: #475569;">{{ $u->country }}</td>
                                    <td>
                                        @if($u->is_published)
                                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:0.75rem;font-weight:700;padding:4px 11px;border-radius:20px;background:rgba(16,185,129,0.12);color:#10b981;border:1px solid rgba(16,185,129,0.25);"><span style="width:6px;height:6px;border-radius:50%;background:#10b981;display:inline-block;"></span>Live</span>
                                        @else
                                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:0.75rem;font-weight:700;padding:4px 11px;border-radius:20px;background:rgba(100,100,100,0.1);color:#64748b;border:1px solid rgba(200,200,200,0.3);"><span style="width:6px;height:6px;border-radius:50%;background:#94a3b8;display:inline-block;"></span>Draft</span>
                                        @endif
                                    </td>
                                    <td style="font-size: 0.85rem; color: #64748b;">{{ $u->created_at->format('M d, Y') }}</td>
                                    <td style="white-space: nowrap; text-align: right;">
                                        <div style="display: inline-flex; gap: 4px; align-items: center; justify-content: flex-end; white-space: nowrap;">
                                            <!-- View Profile -->
                                            <a href="{{ route('profile', $u->id) }}" target="_blank" style="padding: 4px 9px; font-size: 0.73rem; border: 1px solid #cbd5e1; color: #334155; border-radius: 6px; font-weight: 600; background: #fff; text-decoration: none; display: inline-flex; align-items: center; gap: 3px; line-height: 1.2;">
                                                <i class="bi bi-box-arrow-up-right" style="font-size: 0.72rem;"></i> View
                                            </a>

                                            <!-- Edit User (Triggers Edit Modal) -->
                                            <button type="button" class="btn-edit-user"
                                                data-id="{{ $u->id }}"
                                                data-name="{{ $u->name }}"
                                                data-email="{{ $u->email }}"
                                                data-phone="{{ $u->phone }}"
                                                data-category="{{ $u->category }}"
                                                data-is-staff="0"
                                                style="padding: 4px 9px; font-size: 0.73rem; border: 1px solid #cbd5e1; color: #0284c7; border-radius: 6px; font-weight: 600; background: #fff; cursor: pointer; display: inline-flex; align-items: center; gap: 3px; line-height: 1.2;">
                                                <i class="bi bi-pencil-square" style="font-size: 0.72rem;"></i> Edit
                                            </button>

                                            <!-- Toggle Publish / Unpublish -->
                                            <form action="{{ route('admin.user.toggle-publish', $u->id) }}" method="POST" style="margin:0;display:inline;">
                                                @csrf
                                                <button type="submit" style="padding: 4px 9px; font-size: 0.73rem; border: 1px solid {{ $u->is_published ? 'rgba(239,68,68,0.4)' : 'rgba(16,185,129,0.4)' }}; color: {{ $u->is_published ? '#ef4444' : '#10b981' }}; border-radius: 6px; font-weight: 600; background: {{ $u->is_published ? 'rgba(239,68,68,0.08)' : 'rgba(16,185,129,0.08)' }}; cursor: pointer; display: inline-flex; align-items: center; gap: 3px; line-height: 1.2;">
                                                    <i class="bi {{ $u->is_published ? 'bi-lock' : 'bi-globe' }}" style="font-size: 0.72rem;"></i> {{ $u->is_published ? 'Unpublish' : 'Publish' }}
                                                </button>
                                            </form>

                                            <!-- Reset Password -->
                                            <button type="button" class="btn-reset-password"
                                                data-id="{{ $u->id }}"
                                                data-name="{{ $u->name }}"
                                                style="padding: 4px 9px; font-size: 0.73rem; border: 1px solid #cbd5e1; color: #d97706; border-radius: 6px; font-weight: 600; background: #fff; cursor: pointer; display: inline-flex; align-items: center; gap: 3px; line-height: 1.2;">
                                                <i class="bi bi-key-fill" style="font-size: 0.72rem;"></i> Reset Pass
                                            </button>

                                            <!-- Delete User -->
                                            <button type="button" class="btn-delete btn-single-delete"
                                                data-id="{{ $u->id }}"
                                                data-name="{{ $u->name }}"
                                                style="padding: 4px 9px; border-radius: 6px; font-size: 0.73rem; cursor: pointer; display: inline-flex; align-items: center; gap: 3px; line-height: 1.2;">
                                                <i class="bi bi-trash" style="font-size: 0.72rem;"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" style="text-align: center; color: #64748b; padding: 40px 0;">No registered users found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Floating Bulk Action Panel -->
                    <div id="bulk-action-bar" class="bulk-action-bar">
                        <div class="bulk-action-info"><span id="selected-count">0</span> talents selected</div>
                        <div class="bulk-action-buttons">
                            <button type="button" class="bulk-btn-cancel" id="bulk-cancel-btn">Cancel</button>
                            <button type="button" id="bulk-publish-btn" class="bulk-btn-publish">🌐 Publish Selected</button>
                            <button type="button" id="bulk-unpublish-btn" class="bulk-btn-unpublish">🔒 Unpublish Selected</button>
                            <button type="button" id="bulk-delete-btn" class="bulk-btn-delete">🗑️ Delete Selected</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- ==========================================
         TAB 3: Manage Categories content
         ========================================== -->
        <div id="tab-categories" class="tab-content">
            <div class="admin-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 25px;">
                <div>
                    <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
                        <i class="bi bi-tags-fill" style="color: var(--primary);"></i> Manage Talent Categories
                    </h1>
                    <p style="color: #64748b; margin: 0; font-size: 0.92rem;">Define and organize creative skill categories for user registration and feed discovery.</p>
                </div>
                <div>
                    <button type="button" id="btn-open-add-category" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; border: none; border-radius: 30px; font-weight: 700; font-size: 0.9rem; cursor: pointer; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35); transition: all 0.3s ease;">
                        <i class="bi bi-plus-circle-fill" style="font-size: 1.1rem;"></i> Register New Category
                    </button>
                </div>
            </div>

            <div class="admin-card" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <h2 style="margin: 0 0 4px 0; font-size: 1.2rem; font-weight: 700; color: #0f172a;">Categories Directory</h2>
                        <p style="margin: 0; color: #64748b; font-size: 0.85rem;">Active system categories used across talent registrations.</p>
                    </div>
                    <div style="background: rgba(99, 102, 241, 0.1); color: var(--primary); padding: 6px 16px; border-radius: 20px; font-weight: 700; font-size: 0.83rem; border: 1px solid rgba(99, 102, 241, 0.2);">
                        <i class="bi bi-tag-fill"></i> Total Categories: {{ count($categories) }}
                    </div>
                </div>

                <div class="admin-table-container">
                    <table class="admin-table" id="admin-categories-table">
                        <thead>
                            <tr>
                                <th style="width: 50px; text-align: center;">S/N</th>
                                <th>Category Name</th>
                                <th>Slug Identifier</th>
                                <th style="text-align: right; white-space: nowrap;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $cat)
                            <tr>
                                <td style="font-weight: 700; color: #64748b; font-size: 0.85rem; text-align: center;">{{ $loop->iteration }}</td>
                                <td style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(99, 102, 241, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 800;">
                                            {{ strtoupper(substr($cat->name, 0, 1)) }}
                                        </div>
                                        <span>{{ $cat->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-family: monospace; font-size: 0.82rem; background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 6px; border: 1px solid #e2e8f0;">
                                        {{ $cat->slug }}
                                    </span>
                                </td>
                                <td style="white-space: nowrap; text-align: right;">
                                    <div style="display: inline-flex; gap: 4px; align-items: center; justify-content: flex-end; white-space: nowrap;">
                                        <!-- View Category Feed -->
                                        <a href="{{ route('category', $cat->slug) }}" target="_blank" style="padding: 4px 10px; font-size: 0.73rem; border: 1px solid #cbd5e1; color: #334155; border-radius: 6px; font-weight: 600; background: #fff; text-decoration: none; display: inline-flex; align-items: center; gap: 3px; line-height: 1.2;">
                                            <i class="bi bi-box-arrow-up-right" style="font-size: 0.72rem;"></i> View
                                        </a>

                                        <!-- Edit Category Button -->
                                        <button type="button" class="btn-edit-category"
                                            data-id="{{ $cat->id }}"
                                            data-name="{{ $cat->name }}"
                                            style="padding: 4px 10px; font-size: 0.73rem; border: 1px solid #cbd5e1; color: #0284c7; border-radius: 6px; font-weight: 600; background: #fff; cursor: pointer; display: inline-flex; align-items: center; gap: 3px; line-height: 1.2;">
                                            <i class="bi bi-pencil-square" style="font-size: 0.72rem;"></i> Edit
                                        </button>

                                        <!-- Delete Category Form -->
                                        <form action="{{ route('admin.categories.delete', $cat->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this category?');" style="margin: 0; display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete" style="padding: 4px 10px; border-radius: 6px; font-size: 0.73rem; cursor: pointer; display: inline-flex; align-items: center; gap: 3px; line-height: 1.2;">
                                                <i class="bi bi-trash" style="font-size: 0.72rem;"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ==========================================
         TAB 4: User Settings (Staff Profile) content
         ========================================== -->
        <div id="tab-settings" class="tab-content">
            <!-- Staff Member Welcome Header -->
            <!-- <div class="dashboard-header" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; padding: 25px 30px; border-radius: 16px; margin-bottom: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; ga            <div class="admin-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 25px;">
                <div>
                    <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
                        <i class="bi bi-person-gear" style="color: var(--primary);"></i> Staff Account Settings & Roles
                    </h1>
                    <p style="color: #64748b; margin: 0; font-size: 0.92rem;">Update your profile details and register staff members (Administrators & Customer Care personnel).</p>
                </div>
                <div>
                    <button type="button" onclick="$('#add-staff-modal').fadeIn(200);" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; border: none; border-radius: 30px; font-weight: 700; font-size: 0.9rem; cursor: pointer; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35); transition: all 0.3s ease;">
                        <i class="bi bi-person-plus-fill" style="font-size: 1.1rem;"></i> Register New Staff Account
                    </button>
                </div>
            </div>

            <!-- Staff Profile Form -->
            <div class="dashboard-panel" style="background: #ffffff; border-radius: 16px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
                <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 1.2rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-person-gear" style="color: var(--primary);"></i> My Staff Profile & Security Credentials
                </h3>

                <form action="{{ route('dashboard.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-bottom: 20px;">
                        <div class="form-group">
                            <label for="admin_setting_name" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Full Name</label>
                            <input type="text" id="admin_setting_name" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        </div>

                        <div class="form-group">
                            <label for="admin_setting_email" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Account Email Address</label>
                            <input type="email" id="admin_setting_email" class="form-control" value="{{ auth()->user()->email }}" disabled style="background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-bottom: 20px;">
                        <div class="form-group">
                            <label for="admin_setting_phone" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Phone Number (WhatsApp)</label>
                            <input type="text" id="admin_setting_phone" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        </div>

                        <div class="form-group">
                            <label for="admin_setting_country" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Country Location</label>
                            <input type="text" id="admin_setting_country" name="country" class="form-control" value="{{ old('country', auth()->user()->country ?? 'East Africa Tanzania') }}" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 25px;">
                        <label for="admin_setting_profile_image" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Update Profile Avatar Photo</label>
                        <input type="file" id="admin_setting_profile_image" name="profile_image" class="form-control" accept="image/*" style="padding: 8px; border: 1px solid #cbd5e1; border-radius: 10px;">
                    </div>

                    <h4 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin-top: 30px; margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-key-fill" style="color: #d97706;"></i> Update Security Password Credentials (Optional)
                    </h4>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 25px;">
                        <div class="form-group">
                            <label for="admin_setting_current_password" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Current Password</label>
                            <div style="position: relative;">
                                <input type="password" id="admin_setting_current_password" name="current_password" class="form-control" placeholder="Enter current password" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 42px 10px 14px;">
                                <button type="button" class="toggle-password-btn" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; font-size: 1.1rem; padding: 0; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="admin_setting_password" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">New Password</label>
                            <div style="position: relative;">
                                <input type="password" id="admin_setting_password" name="password" class="form-control" placeholder="Leave blank to keep current password" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 42px 10px 14px;">
                                <button type="button" class="toggle-password-btn" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; font-size: 1.1rem; padding: 0; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="admin_setting_password_confirmation" style="color: #475569; font-weight: 600; margin-bottom: 6px; display: block;">Confirm New Password</label>
                            <div style="position: relative;">
                                <input type="password" id="admin_setting_password_confirmation" name="password_confirmation" class="form-control" placeholder="Re-type new password" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 42px 10px 14px;">
                                <button type="button" class="toggle-password-btn" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; font-size: 1.1rem; padding: 0; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" style="padding: 12px 28px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(99,102,241,0.35); cursor: pointer;">
                            Save Staff Profile Updates
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- ==========================================
         TAB 5: Registered Staff content
         ========================================== -->
        <div id="tab-staff" class="tab-content">
            <div class="admin-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 25px;">
                <div>
                    <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
                        <i class="bi bi-people-fill" style="color: var(--primary);"></i> System Registered Staff Members
                    </h1>
                    <p style="color: #64748b; margin: 0; font-size: 0.92rem;">Active administrators and customer care personnel managing ChapConnect operations.</p>
                </div>
                <div>
                    <button type="button" onclick="$('#add-staff-modal').fadeIn(200);" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; border: none; border-radius: 30px; font-weight: 700; font-size: 0.9rem; cursor: pointer; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35); transition: all 0.3s ease;">
                        <i class="bi bi-person-plus-fill" style="font-size: 1.1rem;"></i> Register New Staff Account
                    </button>
                </div>
            </div>

            <!-- Staff Roster Directory Panel -->
            <div class="admin-card" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
                <div class="admin-table-container">
                    <table class="admin-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 50px; text-align: center;">S/N</th>
                                <th>Staff Member</th>
                                <th>Email Address</th>
                                <th>Assigned Role</th>
                                <th>Phone (WhatsApp)</th>
                                <th>Location</th>
                                <th>Registered</th>
                                <th style="text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staffUsers as $s)
                            <tr>
                                <td style="font-weight: 700; color: #64748b; font-size: 0.85rem; text-align: center;">{{ $loop->iteration }}</td>
                                <td style="font-weight: 700; color: #0f172a; font-size: 0.9rem;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem;">
                                            {{ strtoupper(substr($s->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span style="display: block;">{{ $s->name }}</span>
                                            @if($s->id === auth()->id())
                                            <span style="font-size: 0.72rem; color: #6366f1; font-weight: 700;">(Current You)</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td style="color: #64748b; font-size: 0.88rem;">{{ $s->email }}</td>
                                <td>
                                    @if($s->role === 'admin')
                                    <span style="font-size: 0.78rem; font-weight: 700; padding: 5px 12px; border-radius: 20px; background: rgba(99, 102, 241, 0.12); color: #6366f1; border: 1px solid rgba(99, 102, 241, 0.25); display: inline-flex; align-items: center; gap: 6px;">
                                        <i class="bi bi-shield-lock-fill"></i> Administrator
                                    </span>
                                    @else
                                    <span style="font-size: 0.78rem; font-weight: 700; padding: 5px 12px; border-radius: 20px; background: rgba(16, 185, 129, 0.12); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.25); display: inline-flex; align-items: center; gap: 6px;">
                                        <i class="bi bi-headset"></i> Customer Care
                                    </span>
                                    @endif
                                </td>
                                <td style="font-size: 0.88rem; color: #475569;">{{ $s->phone ?? 'N/A' }}</td>
                                <td style="font-size: 0.88rem; color: #475569;">{{ $s->country ?? 'Tanzania' }}</td>
                                <td style="font-size: 0.85rem; color: #64748b;">{{ $s->created_at->format('M d, Y') }}</td>
                                <td style="white-space: nowrap; text-align: right;">
                                    <div style="display: inline-flex; gap: 4px; align-items: center; justify-content: flex-end; white-space: nowrap;">
                                        <!-- Edit Staff Profile -->
                                        <button type="button" class="btn-edit-user"
                                            data-id="{{ $s->id }}"
                                            data-name="{{ $s->name }}"
                                            data-email="{{ $s->email }}"
                                            data-phone="{{ $s->phone }}"
                                            data-role="{{ $s->role ?? 'admin' }}"
                                            data-is-staff="1"
                                            style="padding: 4px 9px; font-size: 0.73rem; border: 1px solid #cbd5e1; color: var(--primary); border-radius: 6px; font-weight: 600; background: #fff; cursor: pointer; display: inline-flex; align-items: center; gap: 3px; line-height: 1.2;">
                                            <i class="bi bi-pencil-square" style="font-size: 0.72rem;"></i> Edit
                                        </button>

                                        <!-- Reset Staff Password -->
                                        <button type="button" class="btn-reset-password"
                                            data-id="{{ $s->id }}"
                                            data-name="{{ $s->name }}"
                                            style="padding: 4px 9px; font-size: 0.73rem; border: 1px solid #cbd5e1; color: #d97706; border-radius: 6px; font-weight: 600; background: #fff; cursor: pointer; display: inline-flex; align-items: center; gap: 3px; line-height: 1.2;">
                                            <i class="bi bi-key-fill" style="font-size: 0.72rem;"></i> Reset Pass
                                        </button>

                                        @if($s->id !== auth()->id())
                                        <!-- Delete Staff Member -->
                                        <button type="button" class="btn-delete btn-single-delete"
                                            data-id="{{ $s->id }}"
                                            data-name="{{ $s->name }}"
                                            style="padding: 4px 9px; border-radius: 6px; font-size: 0.73rem; cursor: pointer; display: inline-flex; align-items: center; gap: 3px; line-height: 1.2;">
                                            <i class="bi bi-trash" style="font-size: 0.72rem;"></i> Delete
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align: center; color: #64748b; padding: 40px 0;">No registered staff accounts found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ==========================================
         TAB 6: Dedicated System Settings content
         ========================================== -->
        <div id="tab-system-settings" class="tab-content">
            <div class="admin-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 25px;">
                <div>
                    <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
                        <i class="bi bi-gear-wide-connected" style="color: #6366f1;"></i> Dedicated System & Platform Settings
                    </h1>
                    <p style="color: #64748b; margin: 0; font-size: 0.92rem;">Manage global system configurations, audio notification chimes, platform contacts, and server maintenance.</p>
                </div>
                <form action="{{ route('admin.settings.clear-cache') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; border-radius: 30px; font-weight: 700; font-size: 0.88rem; cursor: pointer; transition: all 0.2s ease;">
                        <i class="bi bi-arrow-repeat" style="color: #6366f1; font-size: 1rem;"></i> Clear System Cache
                    </button>
                </form>
            </div>

            <!-- System Settings Form Grid -->
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">

                    <!-- Card 1: In-App Audio & Notification Settings -->
                    <div class="admin-card" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
                        <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 1.1rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-volume-up-fill" style="color: #6366f1;"></i> In-App Audio & Sound Alerts
                        </h3>

                        <div class="form-group" style="margin-bottom: 18px;">
                            <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Upload Custom Sound Chime (.mp3, .wav, .ogg)</label>
                            <input type="file" name="notification_sound" accept="audio/*" class="form-control" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.88rem;">
                        </div>

                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; background: #f8fafc; padding: 12px 14px; border-radius: 10px; border: 1px solid #e2e8f0;">
                            <button type="button" onclick="document.getElementById('chapconnect-notification-audio').play();" style="padding: 8px 16px; border-radius: 8px; font-weight: 700; background: #6366f1; border: none; color: #fff; cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 0.82rem;">
                                <i class="bi bi-play-fill" style="font-size: 1.1rem;"></i> Test Sound Chime
                            </button>
                            <span style="font-size: 0.8rem; color: #64748b;">Click to preview current sound chime</span>
                        </div>

                        <div style="display: flex; align-items: center; gap: 10px;">
                            <input type="checkbox" id="notification_sound_enabled" name="notification_sound_enabled" value="1" {{ ($systemSettings['notification_sound_enabled'] ?? '1') == '1' ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #6366f1; cursor: pointer;">
                            <label for="notification_sound_enabled" style="color: #1e293b; font-weight: 600; font-size: 0.88rem; cursor: pointer;">Enable In-App Sound Chimes on New Alerts</label>
                        </div>
                    </div>

                    <!-- Card 2: General Platform & Contact Info -->
                    <div class="admin-card" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
                        <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 1.1rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-globe" style="color: #0284c7;"></i> Platform & Support Contact Settings
                        </h3>

                        <div class="form-group" style="margin-bottom: 16px;">
                            <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Application Title</label>
                            <input type="text" name="site_title" value="{{ $systemSettings['site_title'] ?? 'ChapConnect' }}" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; width: 100%; box-sizing: border-box;">
                        </div>

                        <div class="form-group" style="margin-bottom: 16px;">
                            <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Support WhatsApp Number</label>
                            <input type="text" name="whatsapp_number" value="{{ $systemSettings['whatsapp_number'] ?? '0710383352' }}" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; width: 100%; box-sizing: border-box;">
                        </div>

                        <div class="form-group" style="margin-bottom: 16px;">
                            <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Support Contact Email</label>
                            <input type="email" name="support_email" value="{{ $systemSettings['support_email'] ?? 'support@chapconnect.com' }}" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; width: 100%; box-sizing: border-box;">
                        </div>
                    </div>

                    <!-- Card 3: Directory Rules & Auto Approval -->
                    <div class="admin-card" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
                        <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 1.1rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-shield-check" style="color: #10b981;"></i> Directory Publishing Rules
                        </h3>

                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                            <input type="checkbox" id="auto_publish_talents" name="auto_publish_talents" value="1" {{ ($systemSettings['auto_publish_talents'] ?? '1') == '1' ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #10b981; cursor: pointer;">
                            <label for="auto_publish_talents" style="color: #1e293b; font-weight: 600; font-size: 0.88rem; cursor: pointer;">Auto-publish newly registered talent profiles</label>
                        </div>

                        <div style="font-size: 0.84rem; color: #64748b; background: #f8fafc; padding: 14px; border-radius: 10px; border: 1px solid #e2e8f0; line-height: 1.4;">
                            <strong style="color: #0f172a;">Media Upload Policies:</strong><br>
                            • Photos auto-compressed to WebP format.<br>
                            • Videos supported up to 50MB per file.<br>
                            • Maximum 10 photos &amp; 5 videos per talent.
                        </div>
                    </div>

                    <!-- Card 4: First-Time Splash Loader & Typewriter Settings -->
                    <div class="admin-card" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
                        <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 1.1rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-fonts" style="color: #ec4899;"></i> First-Time Splash Loader &amp; Typewriter
                        </h3>

                        <div class="form-group" style="margin-bottom: 16px;">
                            <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Welcome Text to Type</label>
                            <input type="text" name="welcome_text" value="{{ $systemSettings['welcome_text'] ?? 'Karibu sana ChapConnect...' }}" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; width: 100%; box-sizing: border-box;">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                            <div class="form-group">
                                <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Typing Speed (ms / letter)</label>
                                <input type="number" name="welcome_typing_speed" value="{{ $systemSettings['welcome_typing_speed'] ?? '55' }}" min="10" max="500" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; width: 100%; box-sizing: border-box;">
                            </div>
                            <div class="form-group">
                                <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Start Delay Timeout (ms)</label>
                                <input type="number" name="welcome_delay" value="{{ $systemSettings['welcome_delay'] ?? '300' }}" min="0" max="3000" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; width: 100%; box-sizing: border-box;">
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 12px;">
                            <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Background Welcome Audio Sound (.mp3, .wav, .ogg)</label>
                            <input type="file" name="welcome_sound_file" accept="audio/*" class="form-control" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.88rem;">
                            
                            <div style="margin-top: 10px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                                @if(!empty($systemSettings['welcome_sound']))
                                <div style="font-size: 0.82rem; color: #10b981; display: flex; align-items: center; gap: 6px;">
                                    <i class="bi bi-music-note-beamed"></i> Active Sound: <strong>{{ basename($systemSettings['welcome_sound']) }}</strong>
                                </div>
                                @endif

                                <button type="button" onclick="event.preventDefault(); document.getElementById('resetWelcomeSoundForm').submit();" style="background: #f1f5f9; border: 1px solid #cbd5e1; color: #475569; border-radius: 8px; padding: 6px 12px; font-size: 0.78rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                                    <i class="bi bi-arrow-counterclockwise" style="color: #6366f1;"></i> Reset to Default Female Sound
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <div style="margin-top: 25px; display: flex; justify-content: flex-end;">
                    <button type="submit" style="padding: 12px 30px; border-radius: 12px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 20px rgba(99,102,241,0.4); cursor: pointer; font-size: 0.95rem;">
                        <i class="bi bi-save-fill" style="margin-right: 6px;"></i> Save All System Settings
                    </button>
                </div>
            </form>

            <!-- Reset Welcome Sound Form (Outside Main Settings Form to prevent HTML form nesting) -->
            <form id="resetWelcomeSoundForm" action="{{ route('admin.settings.reset-welcome-sound') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>

        <!-- ==========================================
         TAB 7: Dedicated Customer Care Portal Tab content
         ========================================== -->
        <div id="tab-customer-care" class="tab-content">
            <!-- Header Section -->
            <div class="dashboard-header" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; padding: 24px 28px; border-radius: 18px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border: 1px solid rgba(255,255,255,0.08); margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                    <div style="width: 54px; height: 54px; border-radius: 16px; background: rgba(99, 102, 241, 0.2); border: 1px solid rgba(99, 102, 241, 0.4); display: flex; align-items: center; justify-content: center; color: #818cf8; font-size: 1.6rem; flex-shrink: 0;">
                        <i class="bi bi-headset"></i>
                    </div>
                    <div>
                        <h1 style="margin: 0 0 4px 0; font-size: 1.4rem; font-weight: 800; color: #ffffff; display: flex; align-items: center; gap: 10px;">
                            Customer Care & Support Operations
                        </h1>
                        <p style="margin: 0; color: #94a3b8; font-size: 0.88rem;">Manage support tickets assigned to you, respond to issues, and post staff recommendations.</p>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <a href="{{ route('customer-care.dashboard') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; border: none; border-radius: 30px; font-weight: 700; font-size: 0.9rem; text-decoration: none; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35); transition: all 0.3s ease;">
                        <i class="bi bi-box-arrow-up-right" style="font-size: 1.0rem;"></i> Open Full Customer Care Portal
                    </a>
                </div>
            </div>

            <!-- Support Tickets Roster Panel -->
            <div class="admin-card" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                    <div>
                        <h2 style="margin: 0 0 4px 0; font-size: 1.2rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-person-workspace" style="color: #6366f1;"></i> Support Tickets Assigned To Me
                            <span style="font-size: 0.78rem; background: rgba(99, 102, 241, 0.12); color: #6366f1; border: 1px solid rgba(99, 102, 241, 0.3); padding: 2px 9px; border-radius: 12px; font-weight: 800;">
                                {{ count($assignedTickets) }}
                            </span>
                        </h2>
                        <p style="margin: 0; color: #64748b; font-size: 0.85rem;">Action tickets, approve/cancel/resolve status, and post technical recommendations.</p>
                    </div>
                </div>

                <div class="admin-table-container">
                    <table class="admin-table display nowrap" id="tab-care-tickets-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 45px; text-align: center;">S/N</th>
                                <th style="width: 110px;">Ticket #</th>
                                <th>Reporter</th>
                                <th>Subject & Category</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Staff Recommendation</th>
                                <th style="width: 130px; text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignedTickets as $ticket)
                            <tr>
                                <td style="font-weight: 700; color: #64748b; font-size: 0.85rem; text-align: center;">{{ $loop->iteration }}</td>
                                <td>
                                    <span style="font-family: monospace; font-weight: 800; font-size: 0.82rem; color: #4338ca; background: rgba(99,102,241,0.1); padding: 4px 9px; border-radius: 6px; border: 1px solid rgba(99,102,241,0.2);">
                                        {{ $ticket->ticket_number }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: #0f172a; font-size: 0.88rem;">{{ $ticket->reporter_name }}</div>
                                    <div style="font-size: 0.78rem; color: #64748b;">{{ $ticket->reporter_email }}</div>
                                    @if($ticket->reporter_phone)
                                    <div style="font-size: 0.75rem; color: #10b981; font-weight: 600;"><i class="bi bi-whatsapp"></i> {{ $ticket->reporter_phone }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: #1e293b; font-size: 0.88rem; margin-bottom: 3px;">{{ $ticket->subject }}</div>
                                    <span style="font-size: 0.75rem; color: #475569; background: #f1f5f9; padding: 2px 8px; border-radius: 4px; font-weight: 600;">
                                        {{ $ticket->category }}
                                    </span>
                                </td>
                                <td>
                                    <span style="font-size: 0.72rem; font-weight: 800; padding: 3px 10px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.4px; {{ $ticket->priority_badge_class }}">
                                        {{ $ticket->priority }}
                                    </span>
                                </td>
                                <td>
                                    <span style="font-size: 0.78rem; font-weight: 700; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px; {{ $ticket->status_badge_class }}">
                                        @if(in_array($ticket->status, ['open', 'pending']))
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #ef4444; display: inline-block;"></span> Pending
                                        @elseif(in_array($ticket->status, ['in_progress', 'approved']))
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #f59e0b; display: inline-block;"></span> Approved / In Progress
                                        @elseif($ticket->status === 'resolved')
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981; display: inline-block;"></span> Resolved
                                        @else
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #64748b; display: inline-block;"></span> Cancelled / Closed
                                        @endif
                                    </span>
                                </td>
                                <td style="max-width: 200px;">
                                    @if($ticket->recommendations)
                                    <div style="font-size: 0.8rem; color: #334155; line-height: 1.3; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 6px 10px;">
                                        <i class="bi bi-chat-quote-fill" style="color: #6366f1;"></i> {{ Str::limit($ticket->recommendations, 60) }}
                                    </div>
                                    @else
                                    <span style="font-size: 0.78rem; color: #94a3b8; font-style: italic;">No recommendation</span>
                                    @endif
                                </td>
                                <td style="text-align: right; white-space: nowrap;">
                                    <button type="button" class="btn-staff-ticket-action"
                                        data-id="{{ $ticket->id }}"
                                        data-ticket="{{ $ticket->ticket_number }}"
                                        data-name="{{ $ticket->reporter_name }}"
                                        data-email="{{ $ticket->reporter_email }}"
                                        data-subject="{{ $ticket->subject }}"
                                        data-category="{{ $ticket->category }}"
                                        data-status="{{ $ticket->status }}"
                                        data-description="{{ $ticket->description }}"
                                        data-notes="{{ $ticket->resolution_notes }}"
                                        data-recommendations="{{ $ticket->recommendations }}"
                                        style="padding: 6px 14px; font-size: 0.78rem; border: 1px solid #6366f1; color: #fff; border-radius: 8px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); cursor: pointer; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 8px rgba(99,102,241,0.3);">
                                        <i class="bi bi-pencil-square"></i> Action & Recommend
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- ==========================================
     MODALS SECTION
     ========================================== -->

<!-- Register New Staff Account Modal Popup -->
<div id="add-staff-modal" class="admin-modal">
    <div class="admin-modal-content" style="border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-width: 520px; width: 90%; margin: auto;">
        <div class="admin-modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="margin: 0; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-shield-lock-fill" style="color: var(--primary);"></i> Register New Staff Account
            </h3>
            <button type="button" class="admin-modal-close" onclick="$('#add-staff-modal').fadeOut(200);" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        <form action="{{ route('admin.staff.store') }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="modal_staff_name" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Staff Full Name</label>
                <input type="text" id="modal_staff_name" name="name" class="form-control" placeholder="e.g. Sarah Jenkins" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="modal_staff_email" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Email Address</label>
                <input type="email" id="modal_staff_email" name="email" class="form-control" placeholder="staff@chapconnect.com" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="modal_staff_role" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Assigned Staff Role</label>
                <select name="role" id="modal_staff_role" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    <option value="admin" selected>Administrator (Full Access & Control)</option>
                    <option value="customer_care">Customer Care (Support & Operations)</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="modal_staff_password" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Initial Login Password</label>
                <div style="position: relative;">
                    <input type="password" id="modal_staff_password" name="password" class="form-control" placeholder="Minimum 6 characters" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 42px 10px 14px;">
                    <button type="button" class="toggle-password-btn" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; font-size: 1.1rem; padding: 0; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                <div class="form-group">
                    <label for="modal_staff_phone" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Phone (WhatsApp)</label>
                    <input type="text" id="modal_staff_phone" name="phone" class="form-control" placeholder="e.g. 0710383352" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                </div>
                <div class="form-group">
                    <label for="modal_staff_country" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Country Location</label>
                    <input type="text" id="modal_staff_country" name="country" class="form-control" value="Tanzania" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--border-color); padding-top: 15px;">
                <button type="button" onclick="$('#add-staff-modal').fadeOut(200);" style="padding: 10px 20px; border-radius: 10px; font-weight: 600; background: #e2e8f0; border: none; color: #475569; cursor: pointer;">Cancel</button>
                <button type="submit" style="padding: 10px 24px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(99,102,241,0.35); cursor: pointer;">
                    Register Staff Account
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Register New Talent Modal Popup -->
<div id="add-talent-modal" class="admin-modal">
    <div class="admin-modal-content" style="border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-width: 520px; width: 90%; margin: auto;">
        <div class="admin-modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="margin: 0; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-person-plus-fill" style="color: var(--primary);"></i> Register New Talent Account
            </h3>
            <button type="button" class="admin-modal-close" id="close-add-talent-modal" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        <form action="{{ route('admin.user.store') }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="modal_user_name" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Stage / Full Name</label>
                <input type="text" id="modal_user_name" name="name" class="form-control" placeholder="e.g. Diamond Platnumz" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="modal_user_email" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Email Address</label>
                <input type="email" id="modal_user_email" name="email" class="form-control" placeholder="talent@example.com" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="modal_user_password" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Password</label>
                <div style="position: relative;">
                    <input type="password" id="modal_user_password" name="password" class="form-control" placeholder="Minimum 6 characters" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 42px 10px 14px;">
                    <button type="button" class="toggle-password-btn" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; font-size: 1.1rem; padding: 0; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="modal_user_category" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Primary Category</label>
                <select name="category" id="modal_user_category" class="form-control select2-category" style="width: 100%;">
                    <option value="" disabled selected>Select Category</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label for="modal_user_phone" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Phone (WhatsApp)</label>
                    <input type="text" id="modal_user_phone" name="phone" class="form-control" placeholder="0710383352" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="modal_user_country" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Country</label>
                    <input type="text" id="modal_user_country" name="country" class="form-control" value="Tanzania" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px;">
                <button type="button" id="cancel-add-talent-modal" style="padding: 10px 18px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; font-weight: 600; cursor: pointer;">Cancel</button>
                <button type="submit" class="btn-submit" style="padding: 10px 24px; border-radius: 10px; font-weight: 600; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 12px rgba(99,102,241,0.3);">Create Talent Account</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User / Staff Modal Popup -->
<div id="edit-user-modal" class="admin-modal">
    <div class="admin-modal-content" style="border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-width: 520px; width: 90%; margin: auto;">
        <div class="admin-modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="margin: 0; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-pencil-square" style="color: var(--primary);"></i> <span id="edit-user-modal-title">Edit Talent Profile</span>
            </h3>
            <button type="button" class="admin-modal-close" id="close-edit-modal" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        <form id="edit-user-form" method="POST" action="">
            @csrf

            <div class="form-group" style="margin-bottom: 15px;">
                <label id="edit_name_label" for="edit_name" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Stage / Full Name</label>
                <input type="text" id="edit_name" name="name" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="edit_email" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Email Address</label>
                <input type="email" id="edit_email" name="email" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
            </div>

            <!-- Primary Category Dropdown (For Talents) -->
            <div id="group_edit_category" class="form-group" style="margin-bottom: 15px;">
                <label for="edit_category" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Primary Category</label>
                <select name="category" id="edit_category" class="form-control select2-category" style="width: 100%;">
                    <option value="" disabled selected>Select Category</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Assigned Staff Role Dropdown (For Staff Members) -->
            <div id="group_edit_role" class="form-group" style="margin-bottom: 15px; display: none;">
                <label for="edit_role" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Assigned Staff Role</label>
                <select name="role" id="edit_role" class="form-control" style="width: 100%; background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    <option value="admin">Administrator (Full Access & Control)</option>
                    <option value="customer_care">Customer Care (Support & Operations)</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="edit_phone" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Phone Number (WhatsApp)</label>
                <input type="text" id="edit_phone" name="phone" class="form-control" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px;">
                <button type="button" id="cancel-edit-modal" style="padding: 10px 18px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; font-weight: 600; cursor: pointer;">Cancel</button>
                <button type="submit" class="btn-submit" style="padding: 10px 24px; border-radius: 10px; font-weight: 600; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 12px rgba(99,102,241,0.3);">Save Profile Updates</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal Popup -->
<div id="edit-category-modal" class="admin-modal">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h3>Edit Category Name</h3>
            <button type="button" class="admin-modal-close" id="close-category-modal">&times;</button>
        </div>
        <form id="edit-category-form" method="POST" action="">
            @csrf
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="edit_cat_name" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Category Name</label>
                <input type="text" id="edit_cat_name" name="name" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1;">
                <small style="color: #64748b; font-size: 0.75rem; display: block; margin-top: 4px;">Slug will be updated and existing talent links will be preserved.</small>
            </div>
            <button type="submit" class="btn-submit" style="width: 100%; padding: 12px 20px; font-weight: 600;">Save Category Name</button>
        </form>
    </div>
</div>

<!-- Register New Category Modal Popup -->
<div id="add-category-modal" class="admin-modal">
    <div class="admin-modal-content" style="border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-width: 480px; width: 90%; margin: auto;">
        <div class="admin-modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="margin: 0; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-folder-plus" style="color: var(--primary);"></i> Register New Category
            </h3>
            <button type="button" class="admin-modal-close" id="close-add-category-modal" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="modal_cat_name" style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Category Name</label>
                <input type="text" id="modal_cat_name" name="name" class="form-control" placeholder="e.g. Model, Actor, Producer, Photographer..." required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                <small style="color: #64748b; font-size: 0.78rem; display: block; margin-top: 6px;">Slug URL key will be generated automatically (e.g. <code>model</code>).</small>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px;">
                <button type="button" id="cancel-add-category-modal" style="padding: 10px 18px; border-radius: 10px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; font-weight: 600; cursor: pointer;">Cancel</button>
                <button type="submit" class="btn-submit" style="padding: 10px 24px; border-radius: 10px; font-weight: 600; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 12px rgba(99,102,241,0.3);">Register Category</button>
            </div>
        </form>
    </div>
</div>

<!-- Standalone Hidden Forms for Single Row Actions -->
<form id="single-delete-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<form id="reset-password-form" action="" method="POST" style="display: none;">
    @csrf
</form>

<!-- Bulk Action forms (ids[] populated dynamically by JS) -->
<form id="bulk-publish-form" action="{{ route('admin.users.bulk-publish') }}" method="POST" style="display: none;">
    @csrf
    <div id="bulk-publish-ids"></div>
</form>

<form id="bulk-unpublish-form" action="{{ route('admin.users.bulk-unpublish') }}" method="POST" style="display: none;">
    @csrf
    <div id="bulk-unpublish-ids"></div>
</form>

<form id="bulk-delete-action-form" action="{{ route('admin.users.bulk-delete') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
    <div id="bulk-delete-ids"></div>
</form>
</main>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Helper to force DataTables to recalculate column widths
        function recalcDataTables() {
            if (typeof $.fn.DataTable !== "undefined") {
                try {
                    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust().responsive.recalc();
                } catch (e) {}
            }
        }

        // -------------------------------------------------------------
        // DataTables Plugin Initialization (Responsive Child Row Collapse)
        // -------------------------------------------------------------
        if (typeof $.fn.DataTable !== "undefined") {
            if (!$.fn.DataTable.isDataTable('#recent-talents-table')) {
                $('#recent-talents-table').DataTable({
                    paging: false,
                    info: false,
                    searching: false,
                    responsive: true,
                    columnDefs: [{
                        orderable: false,
                        targets: [-1]
                    }]
                });
            }

            if (!$.fn.DataTable.isDataTable('#admin-talents-table')) {
                $('#admin-talents-table').DataTable({
                    pageLength: 10,
                    lengthMenu: [10, 15, 25, 50, 100],
                    responsive: true,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Instant Search Talents...",
                    },
                    columnDefs: [{
                        orderable: false,
                        targets: [0, 1, 8]
                    }]
                });
            }

            $('.admin-table').each(function() {
                if (this.id !== 'admin-talents-table' && this.id !== 'recent-talents-table' && !$.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable({
                        pageLength: 10,
                        lengthMenu: [10, 15, 25, 50, 100],
                        responsive: true,
                        language: {
                            search: "_INPUT_",
                            searchPlaceholder: "Search records...",
                        }
                    });
                }
            });
        }

        // -------------------------------------------------------------
        // 1. SPA Tab Navigation Switching
        // -------------------------------------------------------------
        const tabLinks = document.querySelectorAll(".tab-link");
        const tabContents = document.querySelectorAll(".tab-content");

        function switchTab(tabId) {
            tabContents.forEach(content => {
                content.classList.remove("active");
            });

            const activeContent = document.getElementById("tab-" + tabId);
            if (activeContent) {
                activeContent.classList.add("active");
            }

            tabLinks.forEach(link => {
                if (link.getAttribute("data-tab") === tabId) {
                    link.classList.add("active");
                } else {
                    link.classList.remove("active");
                }
            });

            if (window.history && window.history.pushState) {
                window.history.pushState(null, null, "#" + tabId);
            } else {
                window.location.hash = tabId;
            }

            // Recalculate DataTables responsive child columns upon tab switch
            recalcDataTables();
            setTimeout(recalcDataTables, 50);
            setTimeout(recalcDataTables, 150);
        }

        tabLinks.forEach(link => {
            link.addEventListener("click", (e) => {
                const tabId = link.getAttribute("data-tab");
                if (tabId) {
                    e.preventDefault();
                    switchTab(tabId);
                }
            });
        });

        let defaultTab = "dashboard";
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('search') || urlParams.has('page')) {
            defaultTab = "talents";
        } else if (window.location.hash) {
            const hash = window.location.hash.substring(1);
            if (["dashboard", "talents", "categories", "settings", "staff", "system-settings", "customer-care"].includes(hash)) {
                defaultTab = hash;
            }
        }
        
        // Execute initial tab switch AFTER DataTables initialization
        switchTab(defaultTab);

        // Auto-close mobile navigation menu when a link is clicked
        $(document).on('click', '.nav-mobile-link', function() {
            const navMenu = document.getElementById("navIconMenu");
            const toggleBtn = document.getElementById("navToggleBtn");
            if (navMenu) navMenu.classList.remove("open");
            if (toggleBtn) {
                const icon = toggleBtn.querySelector("i");
                if (icon) icon.className = "bi bi-list";
            }
        });

        // -------------------------------------------------------------
        // 2. DataTables Checkboxes & Floating Bulk Action Panel
        // -------------------------------------------------------------
        function updateBulkPanelState() {
            const checkedCheckboxes = $('#admin-talents-table tbody input.talent-checkbox:checked');
            const checkedCount = checkedCheckboxes.length;
            const totalCheckboxes = $('#admin-talents-table tbody input.talent-checkbox').length;

            if ($('#selected-count').length) {
                $('#selected-count').text(checkedCount);
            }

            if ($('#bulk-action-bar').length) {
                if (checkedCount > 0) {
                    $('#bulk-action-bar').addClass('active');
                } else {
                    $('#bulk-action-bar').removeClass('active');
                }
            }

            if ($('#select-all-talents').length) {
                $('#select-all-talents').prop('checked', totalCheckboxes > 0 && checkedCount === totalCheckboxes);
            }
        }

        // Delegated checkbox change handlers
        $(document).on('change', '#admin-talents-table tbody input.talent-checkbox', function() {
            updateBulkPanelState();
        });

        $(document).on('change', '#select-all-talents', function() {
            const isChecked = $(this).is(':checked');
            $('#admin-talents-table tbody input.talent-checkbox').prop('checked', isChecked);
            updateBulkPanelState();
        });

        // Trigger panel update on DataTables redraw
        if (typeof talentsDataTable !== 'undefined' && talentsDataTable) {
            talentsDataTable.on('draw', function() {
                updateBulkPanelState();
            });
        }

        // Cancel Bulk Selection
        $(document).on('click', '#bulk-cancel-btn', function() {
            $('#admin-talents-table input.talent-checkbox, #select-all-talents').prop('checked', false);
            updateBulkPanelState();
        });

        // Toggle Password Visibility Eye Handler
        $(document).on('click', '.toggle-password-btn', function() {
            const $btn = $(this);
            const $input = $btn.siblings('input');
            const $icon = $btn.find('i');

            if ($input.attr('type') === 'password') {
                $input.attr('type', 'text');
                $icon.removeClass('bi-eye-slash').addClass('bi-eye');
            } else {
                $input.attr('type', 'password');
                $icon.removeClass('bi-eye').addClass('bi-eye-slash');
            }
        });

        // Helper: collect checked IDs and submit target form
        function bulkSubmitWithIds(formId, idsContainerId, confirmMsg) {
            const checked = $('#admin-talents-table tbody input.talent-checkbox:checked');
            if (checked.length === 0) {
                alert('Please select at least one talent user account.');
                return;
            }
            if (!confirm(confirmMsg)) return;

            const $form = $('#' + formId);
            const $container = $('#' + idsContainerId);
            if (!$form.length || !$container.length) return;

            $container.empty();
            checked.each(function() {
                $container.append($('<input>', {
                    type: 'hidden',
                    name: 'ids[]',
                    value: $(this).val()
                }));
            });
            $form.submit();
        }

        // Bulk Action Button Clicks
        $(document).on('click', '#bulk-publish-btn', function() {
            bulkSubmitWithIds(
                'bulk-publish-form',
                'bulk-publish-ids',
                'Publish all selected talent profiles? They will become live on the public website.'
            );
        });

        $(document).on('click', '#bulk-unpublish-btn', function() {
            bulkSubmitWithIds(
                'bulk-unpublish-form',
                'bulk-unpublish-ids',
                'Unpublish all selected talent profiles? They will be hidden from the public website.'
            );
        });

        $(document).on('click', '#bulk-delete-btn', function() {
            bulkSubmitWithIds(
                'bulk-delete-action-form',
                'bulk-delete-ids',
                'WARNING: Are you sure you want to permanently delete all selected talent accounts and their uploaded media?'
            );
        });

        // Initialize Custom Searchable Select Dropdowns in Admin
        document.querySelectorAll(".searchable-select-container").forEach(container => {
            const input = container.querySelector(".select-search-input");
            const hidden = container.querySelector(".select-hidden-value");
            const optionsPanel = container.querySelector(".select-dropdown-options");
            const optionItems = container.querySelectorAll(".select-option-item");

            if (input && optionsPanel) {
                input.addEventListener("focus", () => {
                    optionsPanel.classList.add("active");
                    optionItems.forEach(item => item.style.display = "block");
                });

                document.addEventListener("click", (e) => {
                    if (!container.contains(e.target)) {
                        optionsPanel.classList.remove("active");
                        const selected = container.querySelector(".select-option-item.selected");
                        if (selected && hidden) {
                            input.value = selected.getAttribute("data-name") || "";
                            hidden.value = selected.getAttribute("data-slug") || "";
                        } else if (hidden) {
                            input.value = "";
                            hidden.value = "";
                        }
                    }
                });

                input.addEventListener("input", () => {
                    const query = input.value.toLowerCase().trim();
                    optionItems.forEach(item => {
                        const name = (item.getAttribute("data-name") || "").toLowerCase();
                        if (name.includes(query)) {
                            item.style.display = "block";
                        } else {
                            item.style.display = "none";
                        }
                    });
                });

                optionItems.forEach(item => {
                    item.addEventListener("click", () => {
                        input.value = item.getAttribute("data-name") || "";
                        if (hidden) hidden.value = item.getAttribute("data-slug") || "";
                        optionItems.forEach(opt => opt.classList.remove("selected"));
                        item.classList.add("selected");
                        optionsPanel.classList.remove("active");
                    });
                });
            }
        });

        // -------------------------------------------------------------
        // 3. Edit User Modal Toggling
        // -------------------------------------------------------------
        const editModal = document.getElementById("edit-user-modal");
        const closeEditModalBtn = document.getElementById("close-edit-modal");
        const editUserForm = document.getElementById("edit-user-form");

        const editNameInput = document.getElementById("edit_name");
        const editEmailInput = document.getElementById("edit_email");
        const editPhoneInput = document.getElementById("edit_phone");
        const editCategorySelect = document.getElementById("edit_category");
        const cancelEditModalBtn = document.getElementById("cancel-edit-modal");

        // Initialize Select2 dropdowns inside modals
        if (typeof $.fn.select2 !== "undefined") {
            $('#edit_category').select2({
                dropdownParent: $('#edit-user-modal'),
                width: '100%',
                placeholder: 'Search & select category...'
            });

            $('#modal_user_category').select2({
                dropdownParent: $('#add-talent-modal'),
                width: '100%',
                placeholder: 'Search & select category...'
            });
        }

        $('#add-talent-modal form').on('submit', function(e) {
            const catVal = $('#modal_user_category').val();
            if (!catVal) {
                e.preventDefault();
                alert('Please select a Primary Category for the talent account.');
                return false;
            }
        });

        $(document).on("click", ".btn-edit-user", function() {
            const $btn = $(this);
            const id = $btn.attr("data-id");
            const name = $btn.attr("data-name");
            const email = $btn.attr("data-email");
            const phone = $btn.attr("data-phone");
            const category = $btn.attr("data-category");
            const role = $btn.attr("data-role");
            const isStaff = $btn.attr("data-is-staff") === "1";

            if (editUserForm) editUserForm.setAttribute("action", `/admin/user/${id}/update`);
            if (editNameInput) editNameInput.value = name || "";
            if (editEmailInput) editEmailInput.value = email || "";
            if (editPhoneInput) editPhoneInput.value = phone || "";

            if (isStaff) {
                // Staff Edit Mode
                $('#edit-user-modal-title').text('Edit Staff Information');
                $('#edit_name_label').text('Staff Full Name');

                $('#group_edit_category').hide();
                $('#edit_category').prop('disabled', true);

                $('#group_edit_role').show();
                $('#edit_role').prop('disabled', false).val(role || 'admin');
            } else {
                // Talent Edit Mode
                $('#edit-user-modal-title').text('Edit Talent Profile');
                $('#edit_name_label').text('Stage / Full Name');

                $('#group_edit_role').hide();
                $('#edit_role').prop('disabled', true);

                $('#group_edit_category').show();
                $('#edit_category').prop('disabled', false);
                if (editCategorySelect) {
                    $(editCategorySelect).val(category || "").trigger("change");
                }
            }

            if (editModal) editModal.classList.add("active");
        });

        if (closeEditModalBtn && editModal) {
            closeEditModalBtn.addEventListener("click", () => {
                editModal.classList.remove("active");
            });
        }

        if (cancelEditModalBtn && editModal) {
            cancelEditModalBtn.addEventListener("click", () => {
                editModal.classList.remove("active");
            });
        }

        window.addEventListener("click", (e) => {
            if (editModal && e.target === editModal) {
                editModal.classList.remove("active");
            }
        });

        // -------------------------------------------------------------
        // Register New Talent Modal Toggling
        // -------------------------------------------------------------
        const addTalentModal = document.getElementById("add-talent-modal");
        const openAddTalentBtn = document.getElementById("btn-open-add-talent");
        const closeAddTalentBtn = document.getElementById("close-add-talent-modal");
        const cancelAddTalentBtn = document.getElementById("cancel-add-talent-modal");

        if (openAddTalentBtn && addTalentModal) {
            openAddTalentBtn.addEventListener("click", () => {
                addTalentModal.classList.add("active");
            });
        }

        if (closeAddTalentBtn && addTalentModal) {
            closeAddTalentBtn.addEventListener("click", () => {
                addTalentModal.classList.remove("active");
            });
        }

        if (cancelAddTalentBtn && addTalentModal) {
            cancelAddTalentBtn.addEventListener("click", () => {
                addTalentModal.classList.remove("active");
            });
        }

        window.addEventListener("click", (e) => {
            if (addTalentModal && e.target === addTalentModal) {
                addTalentModal.classList.remove("active");
            }
        });

        // -------------------------------------------------------------
        // 4. Edit Category Modal Toggling
        // -------------------------------------------------------------
        const editCatModal = document.getElementById("edit-category-modal");
        const closeCatModalBtn = document.getElementById("close-category-modal");
        const editCatForm = document.getElementById("edit-category-form");
        const editCatNameInput = document.getElementById("edit_cat_name");

        document.querySelectorAll(".btn-edit-category").forEach(btn => {
            btn.addEventListener("click", () => {
                const id = btn.getAttribute("data-id");
                const name = btn.getAttribute("data-name");

                if (editCatForm) editCatForm.setAttribute("action", `/admin/categories/${id}/update`);
                if (editCatNameInput) editCatNameInput.value = name || "";
                if (editCatModal) editCatModal.classList.add("active");
            });
        });

        if (closeCatModalBtn && editCatModal) {
            closeCatModalBtn.addEventListener("click", () => {
                editCatModal.classList.remove("active");
            });
        }

        window.addEventListener("click", (e) => {
            if (editCatModal && e.target === editCatModal) {
                editCatModal.classList.remove("active");
            }
        });

        // -------------------------------------------------------------
        // Register New Category Modal Toggling
        // -------------------------------------------------------------
        const addCatModal = document.getElementById("add-category-modal");
        const openAddCatBtn = document.getElementById("btn-open-add-category");
        const closeAddCatBtn = document.getElementById("close-add-category-modal");
        const cancelAddCatBtn = document.getElementById("cancel-add-category-modal");

        if (openAddCatBtn && addCatModal) {
            openAddCatBtn.addEventListener("click", () => {
                addCatModal.classList.add("active");
            });
        }

        if (closeAddCatBtn && addCatModal) {
            closeAddCatBtn.addEventListener("click", () => {
                addCatModal.classList.remove("active");
            });
        }

        if (cancelAddCatBtn && addCatModal) {
            cancelAddCatBtn.addEventListener("click", () => {
                addCatModal.classList.remove("active");
            });
        }

        window.addEventListener("click", (e) => {
            if (addCatModal && e.target === addCatModal) {
                addCatModal.classList.remove("active");
            }
        });

        // -------------------------------------------------------------
        // 5. Single Row Action Form Triggers
        // -------------------------------------------------------------
        document.querySelectorAll(".btn-single-delete").forEach(btn => {
            btn.addEventListener("click", () => {
                const id = btn.getAttribute("data-id");
                const name = btn.getAttribute("data-name");

                if (confirm(`WARNING: Are you sure you want to permanently delete user account '${name}' and all their photos/videos?`)) {
                    const deleteForm = document.getElementById("single-delete-form");
                    if (deleteForm) {
                        deleteForm.setAttribute("action", `/admin/user/${id}`);
                        deleteForm.submit();
                    }
                }
            });
        });

        document.querySelectorAll(".btn-reset-password").forEach(btn => {
            btn.addEventListener("click", () => {
                const id = btn.getAttribute("data-id");
                const name = btn.getAttribute("data-name");

                if (confirm(`Are you sure you want to reset the password for talent '${name}' to 'password123'?`)) {
                    const resetForm = document.getElementById("reset-password-form");
                    if (resetForm) {
                        resetForm.setAttribute("action", `/admin/user/${id}/reset-password`);
                        resetForm.submit();
                    }
                }
            });
        });

        // -------------------------------------------------------------
        // Staff Ticket Action Modal Event Handlers
        // -------------------------------------------------------------
        $(document).on('click', '.btn-staff-ticket-action', function() {
            const $btn = $(this);
            const id = $btn.data('id');
            const ticket = $btn.data('ticket');
            const subject = $btn.data('subject');
            const category = $btn.data('category');
            const status = $btn.data('status');
            const description = $btn.data('description');
            const notes = $btn.data('notes');
            const recommendations = $btn.data('recommendations');

            $('#staff-ticket-action-form').attr('action', '/admin/tickets/' + id + '/staff-action');
            $('#staff_modal_ticket_num').text('#' + ticket);
            $('#staff_modal_subject').text(subject);
            $('#staff_modal_category').text('Category: ' + category);
            $('#staff_modal_description').text(description || 'No detailed description provided.');

            // Map status
            let currentStatus = status;
            if (currentStatus === 'open') currentStatus = 'pending';
            if (currentStatus === 'in_progress') currentStatus = 'approved';

            $('#staff_modal_status').val(currentStatus);
            $('#staff_modal_recommendations').val(recommendations || '');
            $('#staff_modal_notes').val(notes || '');

            $('#staff-ticket-action-modal').fadeIn(200);
        });
    });
</script>

<!-- ==========================================================================
     MODAL: Staff Ticket Action & Recommendations
   ========================================================================== -->
<div id="staff-ticket-action-modal" class="admin-modal">
    <div class="admin-modal-content" style="border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-width: 580px; width: 90%; margin: auto;">
        <div class="admin-modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="margin: 0; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-person-workspace" style="color: #6366f1;"></i> Process Assigned Ticket <span id="staff_modal_ticket_num" style="color: #6366f1;"></span>
            </h3>
            <button type="button" class="admin-modal-close" onclick="$('#staff-ticket-action-modal').fadeOut(200);" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        <form id="staff-ticket-action-form" method="POST" action="">
            @csrf
            <!-- Ticket Summary Box -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; margin-bottom: 18px;">
                <div style="font-size: 0.78rem; text-transform: uppercase; color: #64748b; font-weight: 700;">Issue Subject & Category</div>
                <div id="staff_modal_subject" style="font-weight: 800; font-size: 0.95rem; color: #0f172a; margin-top: 2px;"></div>
                <div id="staff_modal_category" style="font-size: 0.78rem; color: #475569; margin-top: 2px;"></div>
                <div id="staff_modal_description" style="margin-top: 8px; font-size: 0.84rem; color: #334155; background: #fff; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; max-height: 100px; overflow-y: auto;"></div>
            </div>

            <!-- Status Action Dropdown -->
            <div class="form-group" style="margin-bottom: 18px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 700; display: block; margin-bottom: 6px;">Update Ticket Action / Status</label>
                <select name="status" id="staff_modal_status" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    <option value="pending">Pending (Awaiting Further Review)</option>
                    <option value="approved">Approved (Issue Accepted & In Progress)</option>
                    <option value="resolved">Resolved (Issue Fully Addressed & Solved)</option>
                    <option value="cancelled">Cancelled (Issue Rejected or Invalid)</option>
                </select>
            </div>

            <!-- Recommendation Field -->
            <div class="form-group" style="margin-bottom: 18px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 700; display: block; margin-bottom: 6px;">Staff Recommendation & Technical Advice</label>
                <textarea name="recommendations" id="staff_modal_recommendations" rows="3" class="form-control" placeholder="Provide your technical recommendation, feedback, or resolution steps for this issue..." style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.86rem;"></textarea>
            </div>

            <!-- Resolution / Staff Notes -->
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 700; display: block; margin-bottom: 6px;">Internal Resolution Notes</label>
                <textarea name="resolution_notes" id="staff_modal_notes" rows="2" class="form-control" placeholder="Additional notes for Customer Care staff..." style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.86rem;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--border-color); padding-top: 15px;">
                <button type="button" onclick="$('#staff-ticket-action-modal').fadeOut(200);" style="padding: 10px 20px; border-radius: 10px; font-weight: 600; background: #e2e8f0; border: none; color: #475569; cursor: pointer;">Cancel</button>
                <button type="submit" style="padding: 10px 24px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(99,102,241,0.35); cursor: pointer;">
                    Save Action & Recommendations
                </button>
            </div>
        </form>
    </div>
</div>
@endsection