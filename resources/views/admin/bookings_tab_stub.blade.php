<!-- ==========================================
     TAB: Talent Bookings (Super Admin)
     ========================================== -->
<div id="tab-bookings" class="tab-content" style="display: none;">
    <div class="admin-header" style="margin-bottom: 25px;">
        <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
            <i class="bi bi-calendar2-check-fill" style="color: #10b981;"></i> Platform Talent Bookings
        </h1>
        <p style="color: #64748b; margin: 0; font-size: 0.92rem;">Audit and manage all talent booking requests submitted by public clients across the platform.</p>
    </div>

    <!-- Stats Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 25px;">
        <!-- Total -->
        <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 20px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(99,102,241,0.12); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                <i class="bi bi-calendar2-range-fill"></i>
            </div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">{{ isset($allBookings) ? $allBookings->count() : 0 }}</div>
                <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Total Bookings</div>
            </div>
        </div>

        <!-- Pending -->
        <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 20px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(245,158,11,0.12); color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 800; color: #d97706;">{{ isset($allBookings) ? $allBookings->where('status', 'Pending')->count() : 0 }}</div>
                <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Pending Action</div>
            </div>
        </div>

        <!-- Confirmed -->
        <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 20px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16,185,129,0.12); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 800; color: #10b981;">{{ isset($allBookings) ? $allBookings->where('status', 'Confirmed')->count() : 0 }}</div>
                <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Confirmed</div>
            </div>
        </div>

        <!-- Completed -->
        <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 20px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(14,165,233,0.12); color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                <i class="bi bi-award-fill"></i>
            </div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 800; color: #0284c7;">{{ isset($allBookings) ? $allBookings->where('status', 'Completed')->count() : 0 }}</div>
                <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Completed</div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="admin-card" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
        <div class="admin-table-container">
            <table class="admin-table display nowrap" id="admin-bookings-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Date Placed</th>
                        <th>Talent</th>
                        <th>Client Name</th>
                        <th>Client Phone</th>
                        <th>Event Location</th>
                        <th>Booking Date</th>
                        <th>Status</th>
                        <th>Acted By</th>
                        <th style="text-align: right; width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($allBookings))
                    @foreach($allBookings as $bk)
                    <tr>
                        <td style="font-size: 0.82rem; color: #475569;">
                            {{ $bk->created_at ? $bk->created_at->format('d M Y, h:i A') : 'N/A' }}
                        </td>
                        <td>
                            @if($bk->talent)
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <img src="{{ $bk->talent->avatar_url }}" alt="{{ $bk->talent->name }}" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover;">
                                <a href="{{ route('profile', $bk->talent->id) }}" target="_blank" style="font-weight: 700; color: #4f46e5; text-decoration: none;">
                                    {{ $bk->talent->name }}
                                </a>
                            </div>
                            @else
                            <span style="color: #94a3b8; font-style: italic;">Deleted Talent</span>
                            @endif
                        </td>
                        <td style="font-weight: 700; color: #0f172a;">
                            {{ $bk->client_name }}
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <a href="tel:{{ $bk->client_phone }}" style="color: #0284c7; font-weight: 600; text-decoration: none;">
                                    {{ $bk->client_phone }}
                                </a>
                                @php
                                $bkDigits = preg_replace('/[^0-9]/', '', $bk->client_phone);
                                @endphp
                                <a href="https://wa.me/{{ $bkDigits }}" target="_blank" title="WhatsApp" style="color: #25d366; font-size: 0.95rem;">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                            </div>
                        </td>
                        <td style="font-weight: 600; color: #334155;">
                            {{ $bk->location }}
                        </td>
                        <td>
                            <span style="font-weight: 700; color: #0f172a; background: #f1f5f9; padding: 3px 8px; border-radius: 6px; font-size: 0.82rem;">
                                <i class="bi bi-calendar-event" style="color: #10b981;"></i> {{ \Carbon\Carbon::parse($bk->booking_date)->format('d M Y') }}
                            </span>
                        </td>
                        <td>
                            <span style="font-size: 0.72rem; font-weight: 800; padding: 3px 10px; border-radius: 20px;
                                @if($bk->status === 'Pending') background: rgba(245,158,11,0.12); color: #d97706;
                                @elseif($bk->status === 'Confirmed') background: rgba(16,185,129,0.12); color: #059669;
                                @elseif($bk->status === 'Completed') background: rgba(99,102,241,0.12); color: #4f46e5;
                                @else background: rgba(239,68,68,0.12); color: #dc2626; @endif">
                                {{ $bk->status }}
                            </span>
                        </td>
                        <td style="font-size: 0.8rem; color: #64748b;">
                            @if($bk->actionByUser)
                                {{ $bk->actionByUser->name }} ({{ $bk->actionByUser->role }})
                            @else
                                <span style="color: #cbd5e1;">-</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <button type="button" class="btn-manage-booking"
                                data-id="{{ $bk->id }}"
                                data-client="{{ $bk->client_name }}"
                                data-talent="{{ $bk->talent ? $bk->talent->name : 'N/A' }}"
                                data-phone="{{ $bk->client_phone }}"
                                data-location="{{ $bk->location }}"
                                data-date="{{ \Carbon\Carbon::parse($bk->booking_date)->format('d M Y') }}"
                                data-message="{{ $bk->message }}"
                                data-status="{{ $bk->status }}"
                                data-note="{{ $bk->action_note }}"
                                style="padding: 6px 12px; font-size: 0.75rem; border: none; color: #fff; border-radius: 8px; font-weight: 700; background: linear-gradient(135deg, #10b981 0%, #059669 100%); cursor: pointer; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 6px rgba(16,185,129,0.3);">
                                <i class="bi bi-pencil-square"></i> Act
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ==========================================================================
     MODAL: Super Admin Manage Booking
     ========================================================================== -->
<div id="review-admin-booking-modal" class="admin-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15,23,42,0.7); backdrop-filter: blur(5px); z-index: 99999; justify-content: center; align-items: center;">
    <div class="admin-modal-content" style="background: #ffffff; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-width: 520px; width: 90%; margin: auto; padding: 24px; position: relative;">
        <div class="admin-modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 18px;">
            <h3 style="margin: 0; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px; font-size: 1.15rem;">
                <i class="bi bi-calendar2-check-fill" style="color: #10b981;"></i> Act on Talent Booking
            </h3>
            <button type="button" onclick="$('#review-admin-booking-modal').fadeOut(200);" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>

        <form id="admin-booking-action-form" action="" method="POST">
            @csrf

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; margin-bottom: 16px; font-size: 0.85rem; display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <div>
                    <span style="color: #64748b; font-weight: 600; font-size: 0.75rem;">Client:</span>
                    <div id="adm_bk_client" style="font-weight: 800; color: #0f172a; margin-top: 2px;"></div>
                </div>
                <div>
                    <span style="color: #64748b; font-weight: 600; font-size: 0.75rem;">Talent:</span>
                    <div id="adm_bk_talent" style="font-weight: 800; color: #4f46e5; margin-top: 2px;"></div>
                </div>
                <div>
                    <span style="color: #64748b; font-weight: 600; font-size: 0.75rem;">Phone:</span>
                    <div id="adm_bk_phone" style="font-weight: 700; color: #0284c7; margin-top: 2px;"></div>
                </div>
                <div>
                    <span style="color: #64748b; font-weight: 600; font-size: 0.75rem;">Booking Date:</span>
                    <div id="adm_bk_date" style="font-weight: 700; color: #10b981; margin-top: 2px;"></div>
                </div>
                <div style="grid-column: span 2;">
                    <span style="color: #64748b; font-weight: 600; font-size: 0.75rem;">Location:</span>
                    <div id="adm_bk_location" style="font-weight: 700; color: #1e293b; margin-top: 2px;"></div>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="color: #475569; font-size: 0.82rem; font-weight: 700; display: block; margin-bottom: 5px;">Client Message</label>
                <textarea id="adm_bk_message" readonly rows="2" class="form-control" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; border-radius: 10px; padding: 8px 12px; font-size: 0.85rem; resize: none; cursor: default; width: 100%; box-sizing: border-box;"></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="color: #475569; font-size: 0.82rem; font-weight: 700; display: block; margin-bottom: 5px;">Update Booking Status <span style="color: #ef4444;">*</span></label>
                <select id="adm_bk_status" name="status" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; width: 100%; box-sizing: border-box; font-weight: 600;">
                    <option value="Pending">Pending (Awaiting Action)</option>
                    <option value="Confirmed">Confirmed (Accepted)</option>
                    <option value="Completed">Completed (Event Finished)</option>
                    <option value="Cancelled">Cancelled (Declined)</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="color: #475569; font-size: 0.82rem; font-weight: 700; display: block; margin-bottom: 5px;">Staff Action Note / Remarks</label>
                <textarea id="adm_bk_note" name="action_note" rows="2" class="form-control" placeholder="Add resolution note, conversation summary, or remarks..." style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem; width: 100%; box-sizing: border-box;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--border-color); padding-top: 15px;">
                <button type="button" onclick="$('#review-admin-booking-modal').fadeOut(200);" style="padding: 10px 20px; border-radius: 10px; font-weight: 600; background: #e2e8f0; border: none; color: #475569; cursor: pointer;">Cancel</button>
                <button type="submit" style="padding: 10px 24px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(16,185,129,0.3); cursor: pointer;">
                    Update Booking
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof $.fn.DataTable !== 'undefined') {
            if (!$.fn.DataTable.isDataTable('#admin-bookings-table')) {
                $('#admin-bookings-table').DataTable({
                    responsive: true,
                    order: [[0, 'desc']],
                    pageLength: 10,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search bookings..."
                    }
                });
            }
        }

        $(document).on('click', '.btn-manage-booking', function() {
            const id = $(this).data('id');
            const client = $(this).data('client');
            const talent = $(this).data('talent');
            const phone = $(this).data('phone');
            const location = $(this).data('location');
            const date = $(this).data('date');
            const message = $(this).data('message') || 'No specific message provided.';
            const status = $(this).data('status');
            const note = $(this).data('note') || '';

            $('#adm_bk_client').text(client);
            $('#adm_bk_talent').text(talent);
            $('#adm_bk_phone').text(phone);
            $('#adm_bk_location').text(location);
            $('#adm_bk_date').text(date);
            $('#adm_bk_message').val(message);
            $('#adm_bk_status').val(status);
            $('#adm_bk_note').val(note);

            const formAction = "{{ url('/bookings') }}/" + id + "/action";
            $('#admin-booking-action-form').attr('action', formAction);

            $('#review-admin-booking-modal').css('display', 'flex').hide().fadeIn(200);
        });
    });
</script>

