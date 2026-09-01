@extends('layouts.app')

@section('title', 'Customer Care & Operations Center - ChapConnect')

@section('content')
<main class="main admin-main-container" style="max-width: 100%; width: 100%; margin: 15px 0;">
    <div class="dashboard-container">

        <!-- Toast Alert Container for Success / Error Notifications -->
        <div class="toast-alert-container" id="toastContainer">
            @if(session('success'))
            <div class="toast-alert success">
                <div style="width: 28px; height: 28px; border-radius: 50%; background: rgba(16, 185, 129, 0.15); color: #10b981; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="bi bi-check-circle-fill" style="font-size: 1.1rem;"></i>
                </div>
                <div style="flex-grow: 1;">
                    <div style="font-weight: 700; font-size: 0.9rem; color: #0f172a;">Success Notification</div>
                    <div style="font-size: 0.83rem; color: #475569; margin-top: 2px;">{{ session('success') }}</div>
                </div>
                <button type="button" class="toast-close" onclick="$(this).parent().fadeOut();">&times;</button>
            </div>
            @endif

            @if(session('error'))
            <div class="toast-alert error">
                <div style="width: 28px; height: 28px; border-radius: 50%; background: rgba(239, 68, 68, 0.15); color: #ef4444; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.1rem;"></i>
                </div>
                <div style="flex-grow: 1;">
                    <div style="font-weight: 700; font-size: 0.9rem; color: #0f172a;">Action Alert</div>
                    <div style="font-size: 0.83rem; color: #475569; margin-top: 2px;">{{ session('error') }}</div>
                </div>
                <button type="button" class="toast-close" onclick="$(this).parent().fadeOut();">&times;</button>
            </div>
            @endif
        </div>

        <!-- Header Section -->
        <div class="dashboard-header" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; padding: 24px 28px; border-radius: 18px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                <div style="width: 54px; height: 54px; border-radius: 16px; background: rgba(99, 102, 241, 0.2); border: 1px solid rgba(99, 102, 241, 0.4); display: flex; align-items: center; justify-content: center; color: #818cf8; font-size: 1.6rem; flex-shrink: 0;">
                    <i class="bi bi-headset"></i>
                </div>
                <div>
                    <h1 style="margin: 0 0 4px 0; font-size: 1.4rem; font-weight: 800; color: #ffffff; display: flex; align-items: center; gap: 10px;">
                        Customer Care & Operations Center
                    </h1>
                    <p style="margin: 0; color: #94a3b8; font-size: 0.88rem;">Manage user support inquiries, technical tickets, and platform resolution requests.</p>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('admin.moderation') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 9px 18px; background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.35); border-radius: 30px; font-weight: 700; font-size: 0.85rem; text-decoration: none; transition: all 0.2s ease;">
                    <i class="bi bi-shield-exclamation" style="font-size: 1rem;"></i> Content Moderation & NSFW
                    @php
                    $flaggedCount = \App\Models\Media::where('moderation_status', 'flagged')->count();
                    @endphp
                    @if($flaggedCount > 0)
                    <span style="background: #ef4444; color: #fff; font-size: 0.72rem; font-weight: 800; padding: 1px 6px; border-radius: 10px;">{{ $flaggedCount }}</span>
                    @endif
                </a>
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}#customer-care" style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.2); border-radius: 30px; font-weight: 600; font-size: 0.85rem; text-decoration: none; transition: all 0.2s ease;">
                    <i class="bi bi-speedometer2"></i> Admin Dashboard & Assigned Tickets
                </a>
                @endif
                @if(auth()->user()->role === 'customer_care')
                <button type="button" onclick="$('#user-support-modal').fadeIn(200);" style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.2); border-radius: 30px; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: all 0.2s ease; outline: none; border: 1px solid rgba(255,255,255,0.2);">
                    <i class="bi bi-headset"></i> Need Help / Support
                </button>
                @endif
                <button type="button" onclick="$('#create-ticket-modal').fadeIn(200);" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; border: none; border-radius: 30px; font-weight: 700; font-size: 0.9rem; cursor: pointer; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35); transition: all 0.3s ease;">
                    <i class="bi bi-plus-circle-fill" style="font-size: 1.1rem;"></i> Log New Support Ticket
                </button>
            </div>
        </div>

        <!-- Tab: Support Issues & Tickets Roster -->
        <div id="tab-tickets" class="tab-content">
            <!-- Statistics Cards Grid (5-Column Grid for Tickets) -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; width: 100%; margin-bottom: 20px;">
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 18px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(99,102,241,0.12); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                        <i class="bi bi-ticket-detailed-fill"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a;">{{ $totalTickets }}</div>
                        <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Total Tickets</div>
                    </div>
                </div>
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 18px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(239,68,68,0.12); color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                        <i class="bi bi-exclamation-octagon-fill"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #ef4444;">{{ $openTickets }}</div>
                        <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Open Issues</div>
                    </div>
                </div>
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 18px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(245,158,11,0.12); color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #f59e0b;">{{ $inProgressTickets }}</div>
                        <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">In Progress</div>
                    </div>
                </div>
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 18px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16,185,129,0.12); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                        <i class="bi bi-check-all"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #10b981;">{{ $resolvedTickets }}</div>
                        <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Resolved / Closed</div>
                    </div>
                </div>
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 18px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(220,38,38,0.15); color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                        <i class="bi bi-bell-fill"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #dc2626;">{{ $urgentTickets }}</div>
                        <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Urgent Action Needed</div>
                    </div>
                </div>
            </div>

            <div class="admin-card" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color); box-sizing: border-box; width: 100%; min-width: 0;">

            <!-- Filter Controls Bar -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h2 style="margin: 0 0 4px 0; font-size: 1.2rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-life-preserver" style="color: var(--primary);"></i> Support Issues & Tickets Roster
                    </h2>
                    <p style="margin: 0; color: #64748b; font-size: 0.85rem;">Review, assign customer care staff, update priority, and post resolution notes.</p>
                </div>

                <!-- Filter Buttons -->
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <a href="{{ route('customer-care.dashboard') }}" class="social-btn" style="padding: 6px 14px; font-size: 0.8rem; border-radius: 20px; font-weight: 600; text-decoration: none; {{ empty($statusFilter) ? 'background: var(--primary); color: #fff; border: none;' : 'background: #f8fafc; color: #475569; border: 1px solid #cbd5e1;' }}">
                        All Tickets ({{ $totalTickets }})
                    </a>
                    <a href="{{ route('customer-care.dashboard', ['status' => 'open']) }}" class="social-btn" style="padding: 6px 14px; font-size: 0.8rem; border-radius: 20px; font-weight: 600; text-decoration: none; {{ $statusFilter === 'open' ? 'background: #ef4444; color: #fff; border: none;' : 'background: rgba(239,68,68,0.08); color: #ef4444; border: 1px solid rgba(239,68,68,0.2);' }}">
                        Open ({{ $openTickets }})
                    </a>
                    <a href="{{ route('customer-care.dashboard', ['status' => 'in_progress']) }}" class="social-btn" style="padding: 6px 14px; font-size: 0.8rem; border-radius: 20px; font-weight: 600; text-decoration: none; {{ $statusFilter === 'in_progress' ? 'background: #f59e0b; color: #fff; border: none;' : 'background: rgba(245,158,11,0.08); color: #f59e0b; border: 1px solid rgba(245,158,11,0.2);' }}">
                        In Progress ({{ $inProgressTickets }})
                    </a>
                    <a href="{{ route('customer-care.dashboard', ['status' => 'resolved']) }}" class="social-btn" style="padding: 6px 14px; font-size: 0.8rem; border-radius: 20px; font-weight: 600; text-decoration: none; {{ $statusFilter === 'resolved' ? 'background: #10b981; color: #fff; border: none;' : 'background: rgba(16,185,129,0.08); color: #10b981; border: 1px solid rgba(16,185,129,0.2);' }}">
                        Resolved ({{ $resolvedTickets }})
                    </a>
                </div>
            </div>

            <!-- Table Container -->
            <div class="admin-table-container">
                <table class="admin-table display nowrap" id="customer-care-tickets-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="width: 45px; text-align: center;">S/N</th>
                            <th style="width: 110px;">Ticket #</th>
                            <th>Reporter Info</th>
                            <th>Subject & Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Assigned Staff</th>
                            <th>Date Logged</th>
                            <th style="width: 130px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $t)
                        <tr>
                            <td style="font-weight: 700; color: #64748b; font-size: 0.85rem; text-align: center;">{{ $loop->iteration }}</td>
                            <td>
                                <span style="font-family: monospace; font-weight: 800; font-size: 0.82rem; color: #4338ca; background: rgba(99,102,241,0.1); padding: 4px 9px; border-radius: 6px; border: 1px solid rgba(99,102,241,0.2);">
                                    {{ $t->ticket_number }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #0f172a; font-size: 0.88rem;">{{ $t->reporter_name }}</div>
                                <div style="font-size: 0.78rem; color: #64748b;">{{ $t->reporter_email }}</div>
                                @if($t->reporter_phone)
                                <div style="font-size: 0.75rem; color: #10b981; font-weight: 600;"><i class="bi bi-whatsapp"></i> {{ $t->reporter_phone }}</div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #1e293b; font-size: 0.88rem; margin-bottom: 3px;">{{ $t->subject }}</div>
                                <span style="font-size: 0.75rem; color: #475569; background: #f1f5f9; padding: 2px 8px; border-radius: 4px; font-weight: 600;">
                                    {{ $t->category }}
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 0.72rem; font-weight: 800; padding: 3px 10px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.4px; {{ $t->priority_badge_class }}">
                                    {{ $t->priority }}
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 0.78rem; font-weight: 700; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px; {{ $t->status_badge_class }}">
                                    @if($t->status === 'open') <span style="width: 6px; height: 6px; border-radius: 50%; background: #ef4444; display: inline-block;"></span> Open
                                    @elseif($t->status === 'in_progress') <span style="width: 6px; height: 6px; border-radius: 50%; background: #f59e0b; display: inline-block;"></span> In Progress
                                    @elseif($t->status === 'resolved') <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981; display: inline-block;"></span> Resolved
                                    @else <span style="width: 6px; height: 6px; border-radius: 50%; background: #64748b; display: inline-block;"></span> Closed
                                    @endif
                                </span>
                            </td>
                            <td>
                                @if($t->assignedStaff)
                                <div style="display: flex; align-items: center; gap: 6px; font-size: 0.83rem; font-weight: 600; color: #1e293b;">
                                    <div style="width: 24px; height: 24px; border-radius: 50%; background: #6366f1; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800;">
                                        {{ strtoupper(substr($t->assignedStaff->name, 0, 1)) }}
                                    </div>
                                    <span>{{ $t->assignedStaff->name }}</span>
                                </div>
                                @else
                                <span style="font-size: 0.8rem; color: #94a3b8; font-style: italic;">Unassigned</span>
                                @endif
                            </td>
                            <td style="font-size: 0.82rem; color: #64748b;">
                                {{ $t->created_at->format('M d, Y') }}
                                <div style="font-size: 0.72rem; color: #94a3b8;">{{ $t->created_at->format('H:i') }}</div>
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <div style="display: flex; gap: 6px; justify-content: flex-end; align-items: center;">
                                    @if($t->status === 'resolved')
                                    <!-- View Ticket Modal Button (Resolved state) -->
                                    <button type="button" class="btn-update-ticket"
                                        data-id="{{ $t->id }}"
                                        data-ticket="{{ $t->ticket_number }}"
                                        data-name="{{ $t->reporter_name }}"
                                        data-email="{{ $t->reporter_email }}"
                                        data-phone="{{ $t->reporter_phone }}"
                                        data-subject="{{ $t->subject }}"
                                        data-category="{{ $t->category }}"
                                        data-priority="{{ $t->priority }}"
                                        data-status="{{ $t->status }}"
                                        data-assigned="{{ $t->assigned_to }}"
                                        data-description="{{ $t->description }}"
                                        data-notes="{{ $t->resolution_notes }}"
                                        data-view-only="true"
                                        style="padding: 6px 12px; font-size: 0.78rem; border: 1px solid #cbd5e1; color: var(--primary); border-radius: 8px; font-weight: 600; background: #fff; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="bi bi-eye-fill"></i> View
                                    </button>
                                    @else
                                    <!-- Update Ticket Modal Button -->
                                    <button type="button" class="btn-update-ticket"
                                        data-id="{{ $t->id }}"
                                        data-ticket="{{ $t->ticket_number }}"
                                        data-name="{{ $t->reporter_name }}"
                                        data-email="{{ $t->reporter_email }}"
                                        data-phone="{{ $t->reporter_phone }}"
                                        data-subject="{{ $t->subject }}"
                                        data-category="{{ $t->category }}"
                                        data-priority="{{ $t->priority }}"
                                        data-status="{{ $t->status }}"
                                        data-assigned="{{ $t->assigned_to }}"
                                        data-description="{{ $t->description }}"
                                        data-notes="{{ $t->resolution_notes }}"
                                        style="padding: 6px 12px; font-size: 0.78rem; border: 1px solid #cbd5e1; color: var(--primary); border-radius: 8px; font-weight: 600; background: #fff; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="bi bi-pencil-square"></i> Manage
                                    </button>
                                    @endif
 
                                    <!-- Delete Ticket -->
                                    <form action="{{ route('customer-care.tickets.delete', $t->id) }}" method="POST" onsubmit="return {{ $t->status === 'resolved' ? 'false' : 'confirm(\'Are you sure you want to delete support ticket ' . $t->ticket_number . '?\')' }};" style="margin: 0; display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" {{ $t->status === 'resolved' ? 'disabled' : '' }} style="padding: 6px 10px; font-size: 0.78rem; border-radius: 8px; font-weight: 600; {{ $t->status === 'resolved' ? 'border: 1px solid #cbd5e1; color: #94a3b8; background: #f1f5f9; cursor: not-allowed; opacity: 0.7;' : 'border: 1px solid rgba(239,68,68,0.3); color: #ef4444; background: rgba(239,68,68,0.05); cursor: pointer;' }}">
                                            <i class="bi bi-trash-fill"></i>
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
        </div>{{-- /tab-tickets --}}

        <!-- Tab: Blocked Accounts & Login Control -->
        <div id="tab-blocked" class="tab-content">
            <!-- Statistics Cards Grid (4-Column Grid for Blocked Accounts) -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; width: 100%; margin-bottom: 20px;">
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 18px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(15,23,42,0.1); color: #0f172a; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a;">{{ $blockedAccounts->count() }}</div>
                        <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Total Block Logged</div>
                    </div>
                </div>
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 18px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(239,68,68,0.12); color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                        <i class="bi bi-shield-slash-fill"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #ef4444;">{{ $blockedAccounts->where('status','blocked')->count() }}</div>
                        <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Currently Blocked</div>
                    </div>
                </div>
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 18px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16,185,129,0.12); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #10b981;">{{ $blockedAccounts->where('status','unblocked')->count() }}</div>
                        <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Unblocked / Released</div>
                    </div>
                </div>
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 18px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(245,158,11,0.12); color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #f59e0b;">{{ $blockedAccounts->sum('attempts_count') }}</div>
                        <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Failed Login Attempts</div>
                    </div>
                </div>
            </div>

            <div class="admin-card" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color); box-sizing: border-box; width: 100%; min-width: 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h2 style="margin: 0 0 4px 0; font-size: 1.2rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-shield-slash-fill" style="color: #dc2626;"></i> Blocked Accounts & Login Control
                    </h2>
                    <p style="margin: 0; color: #64748b; font-size: 0.85rem;">Review accounts blocked due to consecutive failed login attempts, audit customer complaints, and release blocks.</p>
                </div>
            </div>

            <!-- Table Container -->
            <div class="admin-table-container">
                <table class="admin-table display nowrap" id="blocked-accounts-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="width: 45px; text-align: center;">S/N</th>
                            <th>User Details</th>
                            <th>Block Reason / Trigger</th>
                            <th>Blocked At</th>
                            <th>Complaints / Audit Notes</th>
                            <th>Status</th>
                            <th style="width: 130px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($blockedAccounts as $b)
                        <tr>
                            <td style="font-weight: 700; color: #64748b; font-size: 0.85rem; text-align: center;">{{ $loop->iteration }}</td>
                            <td>
                                @if($b->user)
                                <div style="font-weight: 700; color: #0f172a; font-size: 0.88rem;">{{ $b->user->name }}</div>
                                <div style="font-size: 0.78rem; color: #64748b;">
                                    @if($b->user->email)
                                    <i class="bi bi-envelope"></i> {{ $b->user->email }}
                                    @endif
                                    @if($b->user->email && $b->user->phone) | @endif
                                    @if($b->user->phone)
                                    <i class="bi bi-telephone"></i> {{ $b->user->phone }}
                                    @endif
                                </div>
                                @else
                                <div style="font-weight: 700; color: #dc2626; font-size: 0.88rem; font-style: italic;">Unknown / Deleted User</div>
                                @endif
                            </td>
                            <td>
                                @if(!empty($b->reason))
                                <span style="font-weight: 700; color: #991b1b; background: #fee2e2; padding: 5px 12px; border-radius: 8px; font-size: 0.8rem; border: 1px solid #fecaca; display: inline-flex; align-items: center; gap: 6px; white-space: normal; line-height: 1.3;">
                                    <i class="bi bi-shield-x"></i> {{ $b->reason }}
                                </span>
                                @elseif($b->attempts_count > 0)
                                <span style="font-weight: 700; color: #991b1b; background: #fee2e2; padding: 5px 12px; border-radius: 8px; font-size: 0.8rem; border: 1px solid #fecaca; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="bi bi-exclamation-triangle-fill"></i> {{ $b->attempts_count }} failed attempts in {{ $b->time_interval }}
                                </span>
                                @else
                                <span style="font-weight: 700; color: #991b1b; background: #fee2e2; padding: 5px 12px; border-radius: 8px; font-size: 0.8rem; border: 1px solid #fecaca; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="bi bi-slash-circle-fill"></i> {{ $b->time_interval ?: 'Administrative Suspension' }}
                                </span>
                                @endif
                            </td>
                            <td style="font-size: 0.82rem; color: #64748b;">
                                {{ $b->created_at->format('M d, Y H:i') }}
                            </td>
                            <td style="max-width: 250px; font-size: 0.82rem; color: #334155; white-space: normal; word-break: break-word;">
                                @if($b->status === 'unblocked')
                                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 6px 10px;">
                                    <strong>Complaint:</strong> {{ $b->customer_complaint }}<br>
                                    <span style="font-size: 0.74rem; color: #64748b;">
                                        Req by: {{ $b->requested_by }} | Iss by: {{ $b->issued_by }}
                                    </span>
                                </div>
                                @elseif(!empty($b->issued_by))
                                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 6px 10px;">
                                    <span style="font-size: 0.76rem; color: #475569;">
                                        <strong>Issued by:</strong> {{ $b->issued_by }}
                                    </span>
                                </div>
                                @else
                                <span style="font-style: italic; color: #94a3b8;">Pending customer complaint & unblock</span>
                                @endif
                            </td>
                            <td>
                                @if($b->status === 'blocked')
                                <span style="font-size: 0.78rem; font-weight: 700; padding: 4px 10px; border-radius: 20px; background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); display: inline-flex; align-items: center; gap: 4px;">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #ef4444; display: inline-block;"></span> Blocked
                                </span>
                                @else
                                <span style="font-size: 0.78rem; font-weight: 700; padding: 4px 10px; border-radius: 20px; background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); display: inline-flex; align-items: center; gap: 4px;">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981; display: inline-block;"></span> Unblocked
                                </span>
                                @endif
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                @if($b->status === 'blocked')
                                <button type="button" class="btn-unblock-account"
                                    data-id="{{ $b->id }}"
                                    data-name="{{ $b->user ? $b->user->name : 'Unknown User' }}"
                                    style="padding: 6px 14px; font-size: 0.78rem; border: none; color: #ffffff; border-radius: 8px; font-weight: 700; background: linear-gradient(135deg, #10b981 0%, #059669 100%); cursor: pointer; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 5px rgba(16,185,129,0.2); transition: all 0.2s;">
                                    <i class="bi bi-unlock-fill"></i> Unblock Account
                                </button>
                                @else
                                <span style="font-size: 0.78rem; color: #94a3b8; font-style: italic; font-weight: 600;">Resolved</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>{{-- /admin-card --}}
        </div>{{-- /tab-blocked --}}

        <!-- Tab: Talents Directory & Q&A -->
        <div id="tab-talents" class="tab-content">
            <!-- Statistics Cards Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; width: 100%; margin-bottom: 20px;">
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 18px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(99,102,241,0.12); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a;">{{ $allUsers->count() }}</div>
                        <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Total Talents</div>
                    </div>
                </div>
            </div>

            <div class="admin-card" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color); box-sizing: border-box; width: 100%; min-width: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                    <div>
                        <h2 style="margin: 0 0 4px 0; font-size: 1.2rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-people-fill" style="color: var(--primary);"></i> Registered Talents Directory &amp; Support Recovery
                        </h2>
                        <p style="margin: 0; color: #64748b; font-size: 0.85rem;">Look up talent details and view their security recovery questions and answers to support password recovery calls.</p>
                    </div>
                </div>

                <!-- Table Container -->
                <div class="admin-table-container">
                    <table class="admin-table display nowrap" id="cc-talents-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 45px; text-align: center;">S/N</th>
                                <th>Name / Stage Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th style="width: 150px; text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allUsers as $u)
                            <tr>
                                <td style="font-weight: 700; color: #64748b; font-size: 0.85rem; text-align: center;">{{ $loop->iteration }}</td>
                                <td style="font-weight: 700; color: #0f172a; font-size: 0.88rem;">{{ $u->name }}</td>
                                <td>{{ $u->email ?? '—' }}</td>
                                <td style="font-weight: 600; color: #1e293b;">{{ $u->phone ?? '—' }}</td>
                                <td style="text-align: right; white-space: nowrap;">
                                    @php
                                        $decryptedAnswer = 'Not Configured';
                                        if ($u->security_answer) {
                                            try {
                                                $decryptedAnswer = \Illuminate\Support\Facades\Crypt::decryptString($u->security_answer);
                                            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                                                $decryptedAnswer = 'Legacy (Hashed Answer)';
                                            }
                                        }
                                    @endphp

                                    <button type="button" class="btn-see-qa"
                                        data-name="{{ $u->name }}"
                                        data-question="{{ $u->security_question ?? 'Not Configured' }}"
                                        data-answer="{{ $decryptedAnswer }}"
                                        style="padding: 6px 12px; font-size: 0.78rem; border: 1px solid #cbd5e1; color: #10b981; border-radius: 8px; font-weight: 600; background: #fff; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="bi bi-shield-lock-fill"></i> See Q&amp;A
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>{{-- /admin-card --}}
        </div>{{-- /tab-talents --}}

        <!-- Tab: Guest Contact Requests -->
        <div id="tab-requests" class="tab-content">
            <!-- Statistics Cards Grid (4-Column Grid for Guest Contact Requests) -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; width: 100%; margin-bottom: 20px;">
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 18px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(99,102,241,0.12); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a;">{{ $contactRequests->count() }}</div>
                        <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Total Contact Requests</div>
                    </div>
                </div>
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 18px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(245,158,11,0.12); color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                        <i class="bi bi-hourglass-top"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #f59e0b;">{{ $contactRequests->where('status','Pending')->count() }}</div>
                        <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Pending Review</div>
                    </div>
                </div>
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 18px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16,185,129,0.12); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #10b981;">{{ $contactRequests->where('status','Approved')->count() }}</div>
                        <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Approved Requests</div>
                    </div>
                </div>
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 18px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(14,165,233,0.12); color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #0284c7;">{{ $contactRequests->where('status','Completed')->count() }}</div>
                        <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Completed Connections</div>
                    </div>
                </div>
            </div>

            <div class="admin-card" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color); box-sizing: border-box; width: 100%; min-width: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
            <div>
                <h2 style="margin: 0 0 4px 0; font-size: 1.2rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-person-check-fill" style="color: #6366f1;"></i> Guest Contact Requests
                    <span style="font-size: 0.78rem; background: rgba(245,158,11,0.12); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); padding: 2px 9px; border-radius: 12px; font-weight: 800;">
                        {{ $contactRequests->where('status','Pending')->count() }} Pending
                    </span>
                </h2>
                <p style="margin: 0; color: #64748b; font-size: 0.85rem;">Visitors requesting to connect with talent profiles that have private contact information.</p>
            </div>
        </div>

        <div class="admin-table-container">
            <table class="admin-table display nowrap" id="cc-contact-requests-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Guest Name</th>
                        <th>Contact Type</th>
                        <th>Contact Value</th>
                        <th>Requested Talent</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th style="text-align: right; width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contactRequests as $req)
                    <tr>
                        <td style="font-size: 0.82rem; color: #475569; white-space: nowrap;">{{ date('d M Y', strtotime($req->created_at)) }}</td>
                        <td style="font-weight: 700; color: #0f172a;">{{ $req->requester_full_name }}</td>
                        <td>
                            @if($req->contact_type === 'whatsapp')
                                <i class="bi bi-whatsapp" style="color: #10b981;"></i> WhatsApp
                            @elseif($req->contact_type === 'phone')
                                <i class="bi bi-telephone-fill" style="color: #6366f1;"></i> Phone
                            @elseif($req->contact_type === 'email')
                                <i class="bi bi-envelope-fill" style="color: #ef4444;"></i> Email
                            @else
                                <i class="bi bi-geo-alt-fill" style="color: #f59e0b;"></i> Region
                            @endif
                        </td>
                        <td style="font-weight: 600; color: #1e293b;">{{ $req->contact_value }}</td>
                        <td style="font-weight: 700; color: #4f46e5;">{{ $req->targetUser ? $req->targetUser->name : 'Deleted' }}</td>
                        <td style="max-width: 180px; font-size: 0.8rem; color: #64748b; word-wrap: break-word; white-space: normal;">{{ $req->message ? Str::limit($req->message, 50) : '—' }}</td>
                        <td>
                            <span style="font-size: 0.72rem; font-weight: 800; padding: 3px 9px; border-radius: 20px; 
                                @if($req->status === 'Pending') background: rgba(245,158,11,0.1); color: #f59e0b;
                                @elseif($req->status === 'Approved') background: rgba(16,185,129,0.1); color: #10b981;
                                @elseif($req->status === 'Completed') background: rgba(99,102,241,0.1); color: #6366f1;
                                @else background: rgba(239,68,68,0.1); color: #ef4444; @endif">
                                {{ $req->status }}
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <button type="button" class="btn-cc-review-request"
                                data-id="{{ $req->id }}"
                                data-name="{{ $req->requester_full_name }}"
                                data-type="{{ $req->contact_type }}"
                                data-value="{{ $req->contact_value }}"
                                data-region="{{ $req->region }}"
                                data-target="{{ $req->targetUser ? $req->targetUser->name : 'N/A' }}"
                                data-message="{{ $req->message }}"
                                data-status="{{ $req->status }}"
                                data-staff-notes="{{ $req->staff_notes }}"
                                style="padding: 5px 10px; font-size: 0.75rem; border: none; color: #fff; border-radius: 7px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="bi bi-eye"></i> Review
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </div>{{-- /admin-card --}}
        </div>{{-- /tab-requests --}}

        <!-- Tab: Talent Payment Requests -->
        <div id="tab-payments" class="tab-content">
            <!-- Payout Statistics Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; width: 100%; margin-bottom: 20px;">
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 18px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(245,158,11,0.12); color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a;">{{ $paymentRequests->where('status', 'pending')->count() }}</div>
                        <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Pending Requests</div>
                    </div>
                </div>
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 18px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16,185,129,0.12); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a;">{{ $paymentRequests->where('status', 'paid')->count() }}</div>
                        <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Paid Talents</div>
                    </div>
                </div>
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 18px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(99,102,241,0.12); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a;">{{ number_format($paymentRequests->where('status', 'paid')->sum('amount'), 2) }}</div>
                        <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Total Paid (TZS)</div>
                    </div>
                </div>
            </div>

            <!-- Payment Requests Table -->
            <div class="admin-card" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                    <div>
                        <h2 style="margin: 0 0 4px 0; font-size: 1.15rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-wallet2" style="color: var(--primary);"></i> Talent Payment Requests &amp; Logs
                        </h2>
                        <p style="margin: 0; color: #64748b; font-size: 0.85rem;">View payment requests submitted by platform talents.</p>
                    </div>
                </div>

                <div class="admin-table-container">
                    <table class="admin-table display nowrap" id="cc-payments-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 40px; text-align: center;">S/N</th>
                                <th>Talent</th>
                                <th>Snapshot Stats (L/F/C/V)</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Request Date</th>
                                <th>Payment Details</th>
                                <th style="width: 150px; text-align: right;">Processing</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paymentRequests as $req)
                            <tr>
                                <td style="font-weight: 700; color: #64748b; font-size: 0.82rem; text-align: center;">{{ $loop->iteration }}</td>
                                <td style="font-weight: 700; color: #0f172a; font-size: 0.9rem;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        @if($req->user->profile_image)
                                        <img src="{{ asset($req->user->profile_image) }}" alt="{{ $req->user->name }}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border-color);">
                                        @else
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem;">
                                            {{ strtoupper(substr($req->user->name, 0, 1)) }}
                                        </div>
                                        @endif
                                        <div>
                                            <span style="display: block;">{{ $req->user->name }}</span>
                                            <span style="font-size: 0.75rem; color: #64748b; font-weight: 400;">{{ $req->user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-weight: 600; font-size: 0.82rem; color: #334155;">
                                    <span style="display: inline-block; background: #f1f5f9; padding: 2px 6px; border-radius: 4px; margin-right: 4px;" title="Likes">❤️ {{ $req->likes_count }}</span>
                                    <span style="display: inline-block; background: #f1f5f9; padding: 2px 6px; border-radius: 4px; margin-right: 4px;" title="Followers">👥 {{ $req->followers_count }}</span>
                                    <span style="display: inline-block; background: #f1f5f9; padding: 2px 6px; border-radius: 4px; margin-right: 4px;" title="Comments">💬 {{ $req->comments_count }}</span>
                                    <span style="display: inline-block; background: #f1f5f9; padding: 2px 6px; border-radius: 4px;" title="Views">👁️ {{ $req->views_count }}</span>
                                </td>
                                <td style="font-weight: 800; color: #0f172a; font-size: 0.88rem;">
                                    {{ number_format($req->amount, 2) }} TZS
                                </td>
                                <td>
                                    @if($req->status === 'pending')
                                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:0.72rem;font-weight:700;padding:3px 9px;border-radius:20px;background:rgba(245,158,11,0.12);color:#d97706;border:1px solid rgba(245,158,11,0.25);"><span style="width:5px;height:5px;border-radius:50%;background:#d97706;display:inline-block;"></span>Pending</span>
                                    @elseif($req->status === 'paid')
                                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:0.72rem;font-weight:700;padding:3px 9px;border-radius:20px;background:rgba(16,185,129,0.12);color:#10b981;border:1px solid rgba(16,185,129,0.25);"><span style="width:5px;height:5px;border-radius:50%;background:#10b981;display:inline-block;"></span>Paid</span>
                                    @else
                                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:0.72rem;font-weight:700;padding:3px 9px;border-radius:20px;background:rgba(239,68,68,0.12);color:#ef4444;border:1px solid rgba(239,68,68,0.25);"><span style="width:5px;height:5px;border-radius:50%;background:#ef4444;display:inline-block;"></span>Rejected</span>
                                    @endif
                                </td>
                                <td style="font-size: 0.8rem; color: #64748b;">
                                    {{ $req->created_at->format('M d, Y') }}
                                    <div style="font-size: 0.72rem; color: #94a3b8;">{{ $req->created_at->format('H:i') }}</div>
                                </td>
                                <td style="font-size: 0.8rem; color: #334155;">
                                    @if($req->status === 'paid')
                                        <strong>Method:</strong> {{ $req->payment_method }}<br>
                                        <strong>Ref:</strong> {{ $req->payment_reference ?? 'N/A' }}<br>
                                        <strong>By:</strong> {{ $req->payer ? $req->payer->name : 'Admin' }}
                                    @elseif($req->status === 'rejected')
                                        <span style="color:#ef4444;"><strong>Reason:</strong> {{ $req->admin_notes }}</span>
                                    @else
                                        <span style="color:#94a3b8; font-style:italic;">Awaiting Processing</span>
                                    @endif
                                </td>
                                <td style="text-align: right; white-space: nowrap;">
                                    @if($req->status === 'pending')
                                        @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('admin.dashboard') }}#payments" class="btn" style="padding: 6px 12px; font-size: 0.75rem; border: none; color: #fff; border-radius: 8px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); text-decoration: none; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 8px rgba(99,102,241,0.3);">
                                            <i class="bi bi-wallet2"></i> Process Payments
                                        </a>
                                        @else
                                        <span style="font-size: 0.78rem; font-weight: 700; color: #64748b; background: #f1f5f9; padding: 4px 10px; border-radius: 6px; border: 1px solid #cbd5e1; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="bi bi-lock-fill"></i> Admin Action Req.
                                        </span>
                                        @endif
                                    @else
                                    <span style="font-size: 0.8rem; color:#94a3b8; font-style:italic;">No Action Needed</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" style="text-align: center; color: #94a3b8; padding: 30px; font-style: italic;">No talent payment requests registered in the system.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>{{-- /dashboard-container --}}
</main>

<!-- View Security Q&A Modal Popup -->
<div id="see-qa-modal" class="admin-modal">
    <div class="admin-modal-content" style="border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-width: 480px; width: 90%; margin: auto;">
        <div class="admin-modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="margin: 0; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-shield-lock-fill" style="color: #10b981;"></i> Talent Security Q&amp;A Details
            </h3>
            <button type="button" class="admin-modal-close" onclick="$('#see-qa-modal').fadeOut(200);" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 18px;">
            <span style="font-size: 0.78rem; font-weight: 600; color: #64748b;">Talent Stage Name:</span>
            <div id="qa-modal-talent-name" style="font-weight: 800; color: #0f172a; font-size: 1.05rem; margin-top: 2px;"></div>
        </div>

        <div style="margin-bottom: 16px;">
            <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Security Question</label>
            <div id="qa-modal-question" style="background: #f1f5f9; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 12px 14px; font-weight: 700; font-size: 0.92rem; min-height: 20px;"></div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Decrypted Answer</label>
            <div id="qa-modal-answer" style="background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; border-radius: 10px; padding: 12px 14px; font-weight: 800; font-size: 1rem; min-height: 20px;"></div>
        </div>

        <div style="display: flex; justify-content: flex-end; border-top: 1px solid var(--border-color); padding-top: 15px;">
            <button type="button" onclick="$('#see-qa-modal').fadeOut(200);" style="padding: 10px 24px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(99,102,241,0.35); cursor: pointer;">
                Close Details
            </button>
        </div>
    </div>
</div>

<!-- CC Review Contact Request Modal -->
<div id="cc-review-contact-modal" class="admin-modal">
    <div class="admin-modal-content" style="border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-width: 520px; width: 90%; margin: auto;">
        <div class="admin-modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="margin: 0; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-person-check-fill" style="color: #6366f1;"></i> Review Contact Request
            </h3>
            <button type="button" onclick="$('#cc-review-contact-modal').fadeOut(200);" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        <form id="cc-review-form" action="" method="POST">
            @csrf
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; margin-bottom: 18px; font-size: 0.85rem; display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <div>
                    <span style="color: #64748b; font-weight: 600;">Guest Name:</span>
                    <div id="cc_rev_guest_name" style="font-weight: 800; color: #0f172a; margin-top: 2px;"></div>
                </div>
                <div>
                    <span style="color: #64748b; font-weight: 600;">Requested Talent:</span>
                    <div id="cc_rev_target_name" style="font-weight: 800; color: #4f46e5; margin-top: 2px;"></div>
                </div>
                <div style="margin-top: 6px;">
                    <span style="color: #64748b; font-weight: 600;">Contact Type:</span>
                    <div id="cc_rev_type" style="font-weight: 700; color: #0f172a; margin-top: 2px; text-transform: capitalize;"></div>
                </div>
                <div style="margin-top: 6px;">
                    <span style="color: #64748b; font-weight: 600;">Contact Value:</span>
                    <div id="cc_rev_value" style="font-weight: 700; color: #0f172a; margin-top: 2px;"></div>
                </div>
                <div id="cc_rev_region_wrap" style="margin-top: 6px; display: none; grid-column: 1 / -1;">
                    <span style="color: #64748b; font-weight: 600;"><i class="bi bi-geo-alt-fill" style="color:#f59e0b;"></i> Region:</span>
                    <div id="cc_rev_region" style="font-weight: 700; color: #0f172a; margin-top: 2px;"></div>
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Guest Message</label>
                <textarea id="cc_rev_message" readonly rows="2" class="form-control" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px 14px; font-size: 0.85rem; resize: none;"></textarea>
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Update Status</label>
                <select id="cc_rev_status" name="status" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Staff Internal Notes</label>
                <textarea id="cc_rev_staff_notes" name="staff_notes" rows="2" class="form-control" placeholder="Add internal support notes..." style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem;"></textarea>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--border-color); padding-top: 15px;">
                <button type="button" onclick="$('#cc-review-contact-modal').fadeOut(200);" style="padding: 10px 20px; border-radius: 10px; font-weight: 600; background: #e2e8f0; border: none; color: #475569; cursor: pointer;">Cancel</button>
                <button type="submit" style="padding: 10px 24px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; cursor: pointer;">Save Updates</button>
            </div>
        </form>
    </div>
</div>



<!-- ==========================================================================
     MODAL 1: Create New Support Ticket
   ========================================================================== -->
<div id="create-ticket-modal" class="admin-modal">
    <div class="admin-modal-content" style="border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-width: 580px; width: 90%; margin: auto;">
        <div class="admin-modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="margin: 0; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-plus-circle-fill" style="color: var(--primary);"></i> Log New Support Issue Ticket
            </h3>
            <button type="button" class="admin-modal-close" onclick="$('#create-ticket-modal').fadeOut(200);" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        <form action="{{ route('customer-care.tickets.store') }}" method="POST" onsubmit="if(window.broadcastNotificationAlert){ window.broadcastNotificationAlert(); }">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                <div class="form-group" style="grid-column: span 2;">
                    <label style="color: #475569; font-size: 0.84rem; font-weight: 600; display: block; margin-bottom: 6px;">Search/Select Existing User (Optional)</label>
                    <select id="search-user-select" class="form-control" style="width: 100%;">
                        <option value="">-- Select or Search User --</option>
                        @foreach($allUsers as $u)
                        @if($u->role === 'user')
                        <option value="{{ $u->id }}" data-name="{{ $u->name }}" data-email="{{ $u->email }}" data-phone="{{ $u->phone }}">
                            {{ $u->name }} ({{ $u->email ?: 'No Email' }} - {{ $u->phone ?: 'No Phone' }})
                        </option>
                        @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label style="color: #475569; font-size: 0.84rem; font-weight: 600; display: block; margin-bottom: 6px;">Reporter Full Name</label>
                    <input type="text" name="reporter_name" class="form-control" placeholder="e.g. Alex Kassim" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                </div>
                <div class="form-group">
                    <label style="color: #475569; font-size: 0.84rem; font-weight: 600; display: block; margin-bottom: 6px;">Reporter Email Address</label>
                    <input type="email" name="reporter_email" class="form-control" placeholder="user@example.com" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                <div class="form-group">
                    <label style="color: #475569; font-size: 0.84rem; font-weight: 600; display: block; margin-bottom: 6px;">Phone Number</label>
                    <input type="text" name="reporter_phone" class="form-control" placeholder="e.g. 0710383352" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                </div>
                <div class="form-group">
                    <label style="color: #475569; font-size: 0.84rem; font-weight: 600; display: block; margin-bottom: 6px;">Issue Category</label>
                    <select name="category" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        <option value="Account Access & Credentials">Account Access & Credentials</option>
                        <option value="Profile Verification">Profile Verification</option>
                        <option value="Media Uploads (Photos/Videos)">Media Uploads (Photos/Videos)</option>
                        <option value="Billing & Subscription">Billing & Subscription</option>
                        <option value="Report Abuse / Content Guidelines">Report Abuse / Content Guidelines</option>
                        <option value="General Inquiry" selected>General Inquiry</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="color: #475569; font-size: 0.84rem; font-weight: 600; display: block; margin-bottom: 6px;">Subject Title</label>
                <input type="text" name="subject" class="form-control" placeholder="Brief summary of the issue..." required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                <div class="form-group">
                    <label style="color: #475569; font-size: 0.84rem; font-weight: 600; display: block; margin-bottom: 6px;">Priority Level</label>
                    <select name="priority" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        <option value="low">Low Priority</option>
                        <option value="medium" selected>Medium Priority</option>
                        <option value="high">High Priority</option>
                        <option value="urgent">Urgent Priority (Critical)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label style="color: #475569; font-size: 0.84rem; font-weight: 600; display: block; margin-bottom: 6px;">Assign Customer Care Staff</label>
                    <select name="assigned_to" class="form-control" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        <option value="">Unassigned</option>
                        @foreach($staffMembers as $staff)
                        <option value="{{ $staff->id }}">{{ $staff->name }} ({{ ucfirst($staff->role) }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="color: #475569; font-size: 0.84rem; font-weight: 600; display: block; margin-bottom: 6px;">Issue Description & Details</label>
                <textarea name="description" rows="4" class="form-control" placeholder="Provide full details of the customer issue..." required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--border-color); padding-top: 15px;">
                <button type="button" onclick="$('#create-ticket-modal').fadeOut(200);" style="padding: 10px 20px; border-radius: 10px; font-weight: 600; background: #e2e8f0; border: none; color: #475569; cursor: pointer;">Cancel</button>
                <button type="submit" style="padding: 10px 24px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(99,102,241,0.35); cursor: pointer;">
                    Create Support Ticket
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==========================================================================
     MODAL 2: Manage & Resolve Ticket Modal
   ========================================================================== -->
<div id="update-ticket-modal" class="admin-modal">
    <div class="admin-modal-content" style="border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-width: 600px; width: 90%; margin: auto;">
        <div class="admin-modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="margin: 0; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-pencil-square" style="color: var(--primary);"></i> Manage Support Ticket <span id="modal_ticket_num" style="color: #6366f1;"></span>
            </h3>
            <button type="button" class="admin-modal-close" onclick="$('#update-ticket-modal').fadeOut(200);" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>

        <form id="update-ticket-form" method="POST" action="" onsubmit="if(window.broadcastNotificationAlert){ window.broadcastNotificationAlert(); }">
            @csrf

            <!-- Reporter Banner Info -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; margin-bottom: 18px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <div style="font-size: 0.78rem; text-transform: uppercase; color: #64748b; font-weight: 700;">Customer Reporter</div>
                        <div id="modal_reporter_name" style="font-weight: 800; font-size: 0.95rem; color: #0f172a;"></div>
                        <div id="modal_reporter_email" style="font-size: 0.83rem; color: #475569;"></div>
                    </div>
                    <div style="text-align: right;">
                        <span id="modal_category_badge" style="font-size: 0.78rem; color: #475569; background: #e2e8f0; padding: 3px 10px; border-radius: 12px; font-weight: 700;"></span>
                    </div>
                </div>
            </div>

            <!-- Issue Description Box -->
            <div class="form-group" style="margin-bottom: 18px;">
                <label style="color: #475569; font-size: 0.84rem; font-weight: 700; display: block; margin-bottom: 6px;">Issue Subject & Description</label>
                <div id="modal_subject" style="font-weight: 700; font-size: 0.92rem; color: #0f172a; margin-bottom: 6px;"></div>
                <div id="modal_description" style="background: #fff; border: 1px solid #cbd5e1; border-radius: 10px; padding: 12px 14px; font-size: 0.86rem; color: #334155; line-height: 1.5; max-height: 120px; overflow-y: auto;"></div>
            </div>

            <!-- Ticket Update Controls -->
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 18px;">
                <div class="form-group">
                    <label style="color: #475569; font-size: 0.82rem; font-weight: 700; display: block; margin-bottom: 6px;">Ticket Status</label>
                    <select name="status" id="modal_status" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.85rem;">
                        <option value="open">Open</option>
                        <option value="in_progress">In Progress</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label style="color: #475569; font-size: 0.82rem; font-weight: 700; display: block; margin-bottom: 6px;">Priority Level</label>
                    <select name="priority" id="modal_priority" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.85rem;">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>

                <div class="form-group">
                    <label style="color: #475569; font-size: 0.82rem; font-weight: 700; display: block; margin-bottom: 6px;">Assigned Staff</label>
                    <select name="assigned_to" id="modal_assigned_to" class="form-control" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 9px 12px; font-size: 0.85rem;">
                        <option value="">Unassigned</option>
                        @foreach($staffMembers as $staff)
                        <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Resolution Notes -->
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="color: #475569; font-size: 0.84rem; font-weight: 700; display: block; margin-bottom: 6px;">Resolution Notes & Admin Internal Remarks</label>
                <textarea name="resolution_notes" id="modal_resolution_notes" rows="3" class="form-control" placeholder="Enter resolution details, action steps taken, or response to the customer..." style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.86rem;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--border-color); padding-top: 15px;">
                <button type="button" onclick="$('#update-ticket-modal').fadeOut(200);" style="padding: 10px 20px; border-radius: 10px; font-weight: 600; background: #e2e8f0; border: none; color: #475569; cursor: pointer;">Cancel</button>
                <button type="submit" style="padding: 10px 24px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(99,102,241,0.35); cursor: pointer;">
                    Save Ticket Updates
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==========================================================================
     MODAL 3: Unblock User Account Modal
   ========================================================================== -->
<div id="unblock-account-modal" class="admin-modal">
    <div class="admin-modal-content" style="border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-width: 500px; width: 90%; margin: auto;">
        <div class="admin-modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="margin: 0; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-unlock-fill" style="color: #10b981;"></i> Unblock User Account: <span id="unblock_user_name" style="color: #10b981;"></span>
            </h3>
            <button type="button" class="admin-modal-close" onclick="$('#unblock-account-modal').fadeOut(200);" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>

        <form id="unblock-account-form" method="POST" action="">
            @csrf

            <!-- Customer Complaint -->
            <div class="form-group" style="margin-bottom: 16px;">
                <label style="color: #475569; font-size: 0.84rem; font-weight: 700; display: block; margin-bottom: 6px;">Customer Complaints Why Blocked</label>
                <textarea name="customer_complaint" rows="3" class="form-control" placeholder="Provide reason or complaint submitted by customer..." required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.86rem;"></textarea>
            </div>

            <!-- Requested By -->
            <div class="form-group" style="margin-bottom: 16px;">
                <label style="color: #475569; font-size: 0.84rem; font-weight: 700; display: block; margin-bottom: 6px;">Requested By</label>
                <input type="text" id="unblock_requested_by" name="requested_by" class="form-control" placeholder="e.g. Customer, Agent Name, etc." required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 12px; font-size: 0.86rem;">
            </div>

            <!-- Issued By -->
            <div class="form-group" style="margin-bottom: 16px;">
                <label style="color: #475569; font-size: 0.84rem; font-weight: 700; display: block; margin-bottom: 6px;">Issued By</label>
                <input type="text" name="issued_by" class="form-control" value="{{ auth()->user()->name }}" readonly style="background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 12px; font-size: 0.86rem; cursor: not-allowed;">
            </div>

            <!-- Status -->
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="color: #475569; font-size: 0.84rem; font-weight: 700; display: block; margin-bottom: 6px;">Status</label>
                <select name="status" class="form-control" required style="background: #e2e8f0; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 12px; font-size: 0.86rem;">
                    <option value="unblocked" selected>Unblocked</option>
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--border-color); padding-top: 15px;">
                <button type="button" onclick="$('#unblock-account-modal').fadeOut(200);" style="padding: 10px 20px; border-radius: 10px; font-weight: 600; background: #e2e8f0; border: none; color: #475569; cursor: pointer;">Cancel</button>
                <button type="submit" style="padding: 10px 24px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(16,185,129,0.3); cursor: pointer;">
                    Unblock Account
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables for Support Tickets Table
        if (typeof $.fn.DataTable !== "undefined" && !$.fn.DataTable.isDataTable('#customer-care-tickets-table')) {
            $('#customer-care-tickets-table').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search ticket #, subject, or reporter...",
                    emptyTable: "No support issues found matching the criteria.",
                    zeroRecords: "No matching support issues found.",
                },
                columnDefs: [{
                    orderable: false,
                    targets: [-1]
                }]
            });
        }

        // Open Update Ticket Modal & Populate Data
        $(document).on('click', '.btn-update-ticket', function() {
            const $btn = $(this);
            const id = $btn.data('id');
            const ticket = $btn.data('ticket');
            const name = $btn.data('name');
            const email = $btn.data('email');
            const phone = $btn.data('phone');
            const subject = $btn.data('subject');
            const category = $btn.data('category');
            const priority = $btn.data('priority');
            const status = $btn.data('status');
            const assigned = $btn.data('assigned');
            const description = $btn.data('description');
            const notes = $btn.data('notes');
            const isViewOnly = $btn.data('view-only') === true;

            $('#update-ticket-form').attr('action', '/customer-care/tickets/' + id + '/update');
            $('#modal_ticket_num').text('#' + ticket);
            $('#modal_reporter_name').text(name);
            $('#modal_reporter_email').text(email + (phone ? ' | ' + phone : ''));
            $('#modal_category_badge').text(category);
            $('#modal_subject').text(subject);
            $('#modal_description').text(description || 'No detailed description provided.');

            $('#modal_status').val(status);
            $('#modal_priority').val(priority);
            $('#modal_assigned_to').val(assigned || '');
            $('#modal_resolution_notes').val(notes || '');

            // Handle read-only mode for resolved tickets
            if (isViewOnly) {
                $('#update-ticket-form').find('select, textarea').prop('disabled', true);
                $('#update-ticket-form').find('button[type="submit"]').hide();
                $('#update-ticket-form').find('.admin-modal-header h3 i').removeClass('bi-pencil-square').addClass('bi-eye-fill').css('color', '#10b981');
                $('#update-ticket-form').find('.admin-modal-header h3').contents().each(function() {
                    if (this.nodeType === 3) {
                        this.nodeValue = " View Support Ticket ";
                    }
                });
            } else {
                $('#update-ticket-form').find('select, textarea').prop('disabled', false);
                $('#update-ticket-form').find('button[type="submit"]').show();
                $('#update-ticket-form').find('.admin-modal-header h3 i').removeClass('bi-eye-fill').addClass('bi-pencil-square').css('color', 'var(--primary)');
                $('#update-ticket-form').find('.admin-modal-header h3').contents().each(function() {
                    if (this.nodeType === 3) {
                        this.nodeValue = " Manage Support Ticket ";
                    }
                });
            }

            $('#update-ticket-modal').fadeIn(200);
        });

        // Initialize DataTables for Blocked Accounts Table
        if (typeof $.fn.DataTable !== "undefined" && !$.fn.DataTable.isDataTable('#blocked-accounts-table')) {
            $('#blocked-accounts-table').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search blocked accounts...",
                    emptyTable: "No blocked account entries found.",
                    zeroRecords: "No matching blocked account entries found.",
                },
                columnDefs: [{
                    orderable: false,
                    targets: [-1]
                }]
            });
        }

        // Initialize Select2 for User Search in Modal
        if (typeof $.fn.select2 !== "undefined") {
            $('#search-user-select').select2({
                placeholder: '-- Select or Search User --',
                allowClear: true,
                minimumInputLength: 3,
                width: '100%',
                dropdownParent: $('#create-ticket-modal')
            });

            // Listen to User Change Event to auto-fill inputs
            $('#search-user-select').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const name = selectedOption.data('name') || '';
                const email = selectedOption.data('email') || '';
                const phone = selectedOption.data('phone') || '';

                const $modal = $('#create-ticket-modal');
                $modal.find('input[name="reporter_name"]').val(name);
                $modal.find('input[name="reporter_email"]').val(email);
                $modal.find('input[name="reporter_phone"]').val(phone);
            });
        }

        // Initialize DataTables for Guest Contact Requests Table
        if (typeof $.fn.DataTable !== "undefined" && !$.fn.DataTable.isDataTable('#cc-contact-requests-table')) {
            $('#cc-contact-requests-table').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                responsive: true,
                order: [[0, 'desc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search guest name, contact value, or talent...",
                    emptyTable: "No guest contact requests found.",
                    zeroRecords: "No matching guest contact requests found.",
                },
                columnDefs: [{
                    orderable: false,
                    targets: [-1]
                }]
            });
        }

        // Review Contact Request Modal Click Handler
        $(document).on('click', '.btn-cc-review-request', function() {
            const id = $(this).data('id');
            const region = $(this).data('region');
            $('#cc_rev_guest_name').text($(this).data('name'));
            $('#cc_rev_target_name').text($(this).data('target'));
            $('#cc_rev_type').text($(this).data('type'));
            $('#cc_rev_value').text($(this).data('value'));
            if (region) {
                $('#cc_rev_region').text(region);
                $('#cc_rev_region_wrap').show();
            } else {
                $('#cc_rev_region_wrap').hide();
            }
            $('#cc_rev_message').val($(this).data('message') || 'No message provided.');
            $('#cc_rev_status').val($(this).data('status'));
            $('#cc_rev_staff_notes').val($(this).data('staff-notes') || '');
            $('#cc-review-form').attr('action', `/admin/contact-requests/${id}/action`);
            $('#cc-review-contact-modal').fadeIn(200);
        });

        // Open Unblock Account Modal & Populate Action URL
        $(document).on('click', '.btn-unblock-account', function() {
            const $btn = $(this);
            const id = $btn.data('id');
            const name = $btn.data('name');

            $('#unblock-account-form').attr('action', '/customer-care/unblock/' + id);
            $('#unblock_user_name').text(name);
            $('#unblock_requested_by').val(name);
            $('#unblock-account-modal').fadeIn(200);
        });

        // Initialize DataTables for cc-talents-table
        if (typeof $.fn.DataTable !== "undefined" && !$.fn.DataTable.isDataTable('#cc-talents-table')) {
            $('#cc-talents-table').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search talents...",
                    emptyTable: "No talents found.",
                    zeroRecords: "No matching talents found.",
                },
                columnDefs: [{
                    orderable: false,
                    targets: [-1]
                }]
            });
        }

        // See Q&A Click Handler
        $(document).on("click", ".btn-see-qa", function() {
            const name = $(this).attr("data-name");
            const question = $(this).attr("data-question");
            const answer = $(this).attr("data-answer");

            $("#qa-modal-talent-name").text(name);
            $("#qa-modal-question").text(question);
            $("#qa-modal-answer").text(answer);

            $("#see-qa-modal").fadeIn(200);
        });

        // -------------------------------------------------------
        // CC Page Tab Navigation (tickets | blocked | requests | talents | payments)
        // -------------------------------------------------------
        const ccTabs = ['tickets', 'blocked', 'requests', 'payments', 'talents'];

        function ccSwitchTab(tabId) {
            // Hide all tab panels
            ccTabs.forEach(function(t) {
                const el = document.getElementById('tab-' + t);
                if (el) el.classList.remove('active');
            });
            // Show active tab panel
            const active = document.getElementById('tab-' + tabId);
            if (active) active.classList.add('active');

            // Update sidebar active state
            document.querySelectorAll('.cc-tab-link').forEach(function(link) {
                if (link.getAttribute('data-cctab') === tabId) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });

            // Update URL hash without page jump
            if (window.history && window.history.pushState) {
                window.history.pushState(null, null, '#' + tabId);
            }

            // Recalculate DataTables layout
            setTimeout(function() {
                $.fn.dataTable && $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
            }, 50);
        }

        // Read initial tab from URL hash
        const ccValidTabs = ['tickets', 'blocked', 'requests', 'payments', 'talents'];
        let ccDefaultTab = 'tickets';
        if (window.location.hash) {
            const h = window.location.hash.substring(1);
            if (ccValidTabs.includes(h)) ccDefaultTab = h;
        }
        ccSwitchTab(ccDefaultTab);

        // Handle sidebar link clicks
        $(document).on('click', '.cc-tab-link', function(e) {
            e.preventDefault();
            const tabId = $(this).data('cctab');
            if (tabId) ccSwitchTab(tabId);
        });

    });
</script>
@endsection