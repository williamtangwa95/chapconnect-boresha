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
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}#customer-care" style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.2); border-radius: 30px; font-weight: 600; font-size: 0.85rem; text-decoration: none; transition: all 0.2s ease;">
                    <i class="bi bi-speedometer2"></i> Admin Dashboard & Assigned Tickets
                </a>
                @endif

                <button type="button" onclick="$('#create-ticket-modal').fadeIn(200);" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; border: none; border-radius: 30px; font-weight: 700; font-size: 0.9rem; cursor: pointer; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35); transition: all 0.3s ease;">
                    <i class="bi bi-plus-circle-fill" style="font-size: 1.1rem;"></i> Log New Support Ticket
                </button>
            </div>
        </div>

        <!-- Statistics Cards Grid (Horizontal 5-Column Grid) -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; width: 100%;">
            <!-- Card 1: Total Support Tickets -->
            <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 18px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(99,102,241,0.12); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                    <i class="bi bi-ticket-detailed-fill"></i>
                </div>
                <div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a;">{{ $totalTickets }}</div>
                    <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Total Tickets</div>
                </div>
            </div>

            <!-- Card 2: Open Issues -->
            <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 18px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(239,68,68,0.12); color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                    <i class="bi bi-exclamation-octagon-fill"></i>
                </div>
                <div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: #ef4444;">{{ $openTickets }}</div>
                    <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Open Issues</div>
                </div>
            </div>

            <!-- Card 3: In Progress -->
            <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 18px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(245,158,11,0.12); color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: #f59e0b;">{{ $inProgressTickets }}</div>
                    <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">In Progress</div>
                </div>
            </div>

            <!-- Card 4: Resolved & Closed -->
            <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 18px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16,185,129,0.12); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0;">
                    <i class="bi bi-check-all"></i>
                </div>
                <div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: #10b981;">{{ $resolvedTickets }}</div>
                    <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Resolved / Closed</div>
                </div>
            </div>

            <!-- Card 5: Urgent Priority -->
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

        <!-- Main Support Roster Card -->
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

                                    <!-- Delete Ticket -->
                                    <form action="{{ route('customer-care.tickets.delete', $t->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete support ticket {{ $t->ticket_number }}?');" style="margin: 0; display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="padding: 6px 10px; font-size: 0.78rem; border: 1px solid rgba(239,68,68,0.3); color: #ef4444; border-radius: 8px; font-weight: 600; background: rgba(239,68,68,0.05); cursor: pointer;">
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
    </div>
</main>

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
        <form action="{{ route('customer-care.tickets.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
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
                    <label style="color: #475569; font-size: 0.84rem; font-weight: 600; display: block; margin-bottom: 6px;">WhatsApp / Phone Number</label>
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

        <form id="update-ticket-form" method="POST" action="">
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
            const subject = $btn.data('subject');
            const category = $btn.data('category');
            const priority = $btn.data('priority');
            const status = $btn.data('status');
            const assigned = $btn.data('assigned');
            const description = $btn.data('description');
            const notes = $btn.data('notes');

            $('#update-ticket-form').attr('action', '/customer-care/tickets/' + id + '/update');
            $('#modal_ticket_num').text('#' + ticket);
            $('#modal_reporter_name').text(name);
            $('#modal_reporter_email').text(email);
            $('#modal_category_badge').text(category);
            $('#modal_subject').text(subject);
            $('#modal_description').text(description || 'No detailed description provided.');

            $('#modal_status').val(status);
            $('#modal_priority').val(priority);
            $('#modal_assigned_to').val(assigned || '');
            $('#modal_resolution_notes').val(notes || '');

            $('#update-ticket-modal').fadeIn(200);
        });
    });
</script>
@endsection
