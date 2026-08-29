<!-- ==========================================
     TAB: Contact Requests
     ========================================== -->
<div id="tab-requests" class="tab-content">
    <div class="admin-header" style="margin-bottom: 25px;">
        <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
            <i class="bi bi-person-check-fill" style="color: #6366f1;"></i> Guest Contact Requests
        </h1>
        <p style="color: #64748b; margin: 0; font-size: 0.92rem;">Review connection requests from visitors asking to contact private talent profiles.</p>
    </div>

    <div class="admin-card" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
        <div class="admin-table-container">
            <table class="admin-table display nowrap" id="contact-requests-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Requester Type</th>
                        <th>Guest Name</th>
                        <th>Contact Type</th>
                        <th>Contact Value</th>
                        <th>Requested Talent</th>
                        <th>Status</th>
                        <th style="text-align: right; width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contactRequests as $req)
                    <tr>
                        <td style="font-size: 0.82rem; color: #475569;">
                            {{ date('d M Y, h:i A', strtotime($req->created_at)) }}
                        </td>
                        <td>
                            <span style="font-size: 0.72rem; font-weight: 800; background: rgba(99,102,241,0.1); color: #6366f1; padding: 3px 8px; border-radius: 6px; text-transform: uppercase;">
                                Guest
                            </span>
                        </td>
                        <td style="font-weight: 700; color: #0f172a;">{{ $req->requester_full_name }}</td>
                        <td>
                            <span style="text-transform: capitalize;">
                                @if($req->contact_type === 'whatsapp')
                                    <i class="bi bi-whatsapp" style="color: #10b981;"></i> WhatsApp
                                @elseif($req->contact_type === 'phone')
                                    <i class="bi bi-telephone-fill" style="color: #6366f1;"></i> Phone
                                @elseif($req->contact_type === 'email')
                                    <i class="bi bi-envelope-fill" style="color: #ef4444;"></i> Email
                                @else
                                    <i class="bi bi-geo-alt-fill" style="color: #f59e0b;"></i> Region
                                @endif
                            </span>
                        </td>
                        <td style="font-weight: 600; color: #1e293b;">
                            {{ $req->contact_value }}
                        </td>
                        <td style="font-weight: 700; color: #4f46e5;">
                            {{ $req->targetUser ? $req->targetUser->name : 'Deleted Talent' }}
                        </td>
                        <td>
                            <span style="font-size: 0.72rem; font-weight: 800; padding: 3px 10px; border-radius: 20px; 
                                @if($req->status === 'Pending') background: rgba(245,158,11,0.1); color: #f59e0b;
                                @elseif($req->status === 'Approved') background: rgba(16,185,129,0.1); color: #10b981;
                                @elseif($req->status === 'Completed') background: rgba(99,102,241,0.1); color: #6366f1;
                                @else background: rgba(239,68,68,0.1); color: #ef4444; @endif">
                                {{ $req->status }}
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <button type="button" class="btn-review-request"
                                data-id="{{ $req->id }}"
                                data-name="{{ $req->requester_full_name }}"
                                data-type="{{ $req->contact_type }}"
                                data-value="{{ $req->contact_value }}"
                                data-region="{{ $req->region }}"
                                data-target="{{ $req->targetUser ? $req->targetUser->name : 'N/A' }}"
                                data-message="{{ $req->message }}"
                                data-status="{{ $req->status }}"
                                data-staff-notes="{{ $req->staff_notes }}"
                                data-admin-notes="{{ $req->admin_notes }}"
                                style="padding: 6px 12px; font-size: 0.75rem; border: none; color: #fff; border-radius: 8px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="bi bi-eye"></i> Review
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ==========================================================================
     MODAL: Review Contact Request
     ========================================================================== -->
<div id="review-contact-request-modal" class="admin-modal">
    <div class="admin-modal-content" style="border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-width: 540px; width: 90%; margin: auto;">
        <div class="admin-modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="margin: 0; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-person-check-fill" style="color: #6366f1;"></i> Review Connection Request
            </h3>
            <button type="button" class="admin-modal-close" onclick="$('#review-contact-request-modal').fadeOut(200);" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        <form id="review-contact-request-form" action="" method="POST">
            @csrf
            
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; margin-bottom: 18px; font-size: 0.85rem; display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <div>
                    <span style="color: #64748b; font-weight: 600;">Guest Name:</span>
                    <div id="rev_guest_name" style="font-weight: 800; color: #0f172a; margin-top: 2px;"></div>
                </div>
                <div>
                    <span style="color: #64748b; font-weight: 600;">Requested Talent:</span>
                    <div id="rev_target_name" style="font-weight: 800; color: #4f46e5; margin-top: 2px;"></div>
                </div>
                <div style="margin-top: 6px;">
                    <span style="color: #64748b; font-weight: 600;">Contact Type:</span>
                    <div id="rev_contact_type" style="font-weight: 700; color: #0f172a; margin-top: 2px; text-transform: capitalize;"></div>
                </div>
                <div style="margin-top: 6px;">
                    <span style="color: #64748b; font-weight: 600;">Contact Value:</span>
                    <div id="rev_contact_value" style="font-weight: 700; color: #0f172a; margin-top: 2px;"></div>
                </div>
                <div id="rev_region_wrap" style="margin-top: 6px; display: none;">
                    <span style="color: #64748b; font-weight: 600;"><i class="bi bi-geo-alt-fill" style="color:#f59e0b;"></i> Region:</span>
                    <div id="rev_region" style="font-weight: 700; color: #0f172a; margin-top: 2px;"></div>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Optional Message from Guest</label>
                <textarea id="rev_message" readonly rows="2" class="form-control" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.85rem; resize: none; cursor: default;"></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Request Status</label>
                <select id="rev_status" name="status" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Staff / Support Internal Notes</label>
                <textarea id="rev_staff_notes" name="staff_notes" rows="2" class="form-control" placeholder="Add details visible to both admin and customer care..." style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem;"></textarea>
            </div>

            @if(auth()->user() && auth()->user()->role === 'admin')
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Super Admin Only Notes <span style="font-size: 0.72rem; color: #6366f1;">(Hidden from Staff)</span></label>
                <textarea id="rev_admin_notes" name="admin_notes" rows="2" class="form-control" placeholder="Add confidential admin observations..." style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem;"></textarea>
            </div>
            @endif

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--border-color); padding-top: 15px;">
                <button type="button" onclick="$('#review-contact-request-modal').fadeOut(200);" style="padding: 10px 20px; border-radius: 10px; font-weight: 600; background: #e2e8f0; border: none; color: #475569; cursor: pointer;">Cancel</button>
                <button type="submit" style="padding: 10px 24px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(99,102,241,0.3); cursor: pointer;">
                    Save Request Updates
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize DataTable
        if (typeof $.fn.DataTable !== 'undefined') {
            if (!$.fn.DataTable.isDataTable('#contact-requests-table')) {
                $('#contact-requests-table').DataTable({
                    responsive: true,
                    order: [[0, 'desc']],
                    pageLength: 10,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search requests..."
                    }
                });
            }
        }

        // Setup modal review triggers
        $(document).on('click', '.btn-review-request', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const type = $(this).data('type');
            const value = $(this).data('value');
            const target = $(this).data('target');
            const region = $(this).data('region');
            const message = $(this).data('message');
            const status = $(this).data('status');
            const staffNotes = $(this).data('staff-notes');
            const adminNotes = $(this).data('admin-notes');

            // Set content details
            $('#rev_guest_name').text(name);
            $('#rev_target_name').text(target);
            $('#rev_contact_type').text(type);
            $('#rev_contact_value').text(value);
            if (region) {
                $('#rev_region').text(region);
                $('#rev_region_wrap').show();
            } else {
                $('#rev_region_wrap').hide();
            }
            $('#rev_message').val(message ? message : 'No message provided.');
            $('#rev_status').val(status);
            $('#rev_staff_notes').val(staffNotes ? staffNotes : '');
            
            if ($('#rev_admin_notes').length) {
                $('#rev_admin_notes').val(adminNotes ? adminNotes : '');
            }

            // Set Form action endpoint
            $('#review-contact-request-form').attr('action', `/admin/contact-requests/${id}/action`);

            // Open modal
            $('#review-contact-request-modal').fadeIn(200);
        });
    });
</script>
