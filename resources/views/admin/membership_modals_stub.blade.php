<!-- ==========================================================================
     MODAL: Create New Package
   ========================================================================== -->
<div id="add-package-modal" class="admin-modal">
    <div class="admin-modal-content" style="border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-width: 520px; width: 90%; margin: auto;">
        <div class="admin-modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="margin: 0; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-box-seam-fill" style="color: #f59e0b;"></i> Create New Package
            </h3>
            <button type="button" class="admin-modal-close" onclick="$('#add-package-modal').fadeOut(200);" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        <form action="/admin/packages" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 15px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Package Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. VIP" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Description</label>
                <textarea name="description" rows="2" class="form-control" placeholder="Enter package details..." style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem;"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div class="form-group">
                    <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Package Type</label>
                    <select name="package_type" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        <option value="Free">Free</option>
                        <option value="To Pay">To Pay</option>
                    </select>
                </div>
                <div class="form-group">
                    <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Price (TZS)</label>
                    <input type="number" name="price" class="form-control" value="0" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div class="form-group">
                    <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Duration</label>
                    <input type="number" name="duration" class="form-control" value="30" min="-1" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    <span style="font-size: 0.72rem; color: #64748b; margin-top: 3px; display: block;">Enter -1 for Lifetime.</span>
                </div>
                <div class="form-group">
                    <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Duration Unit</label>
                    <select name="duration_unit" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        <option value="days">Days</option>
                        <option value="months">Months</option>
                        <option value="years">Years</option>
                        <option value="lifetime">Lifetime (No End)</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div class="form-group">
                    <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Phone Visibility</label>
                    <select name="phone_visibility" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        <option value="No">No (Hidden)</option>
                        <option value="Yes">Yes (Visible)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Max Images</label>
                    <input type="number" name="max_images" class="form-control" value="5" min="-1" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    <span style="font-size: 0.72rem; color: #64748b; margin-top: 3px; display: block;">Enter -1 for Unlimited.</span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                <div class="form-group">
                    <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Max Videos</label>
                    <input type="number" name="max_videos" class="form-control" value="2" min="-1" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    <span style="font-size: 0.72rem; color: #64748b; margin-top: 3px; display: block;">Enter -1 for Unlimited.</span>
                </div>
                <div class="form-group">
                    <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Max News Articles</label>
                    <input type="number" name="max_news" class="form-control" value="3" min="-1" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    <span style="font-size: 0.72rem; color: #64748b; margin-top: 3px; display: block;">Enter -1 for Unlimited.</span>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Status</label>
                <select name="status" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--border-color); padding-top: 15px;">
                <button type="button" onclick="$('#add-package-modal').fadeOut(200);" style="padding: 10px 20px; border-radius: 10px; font-weight: 600; background: #e2e8f0; border: none; color: #475569; cursor: pointer;">Cancel</button>
                <button type="submit" style="padding: 10px 24px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(245,158,11,0.35); cursor: pointer;">
                    Create Package
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==========================================================================
     MODAL: Edit Package
   ========================================================================== -->
<div id="edit-package-modal" class="admin-modal">
    <div class="admin-modal-content" style="border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-width: 520px; width: 90%; margin: auto;">
        <div class="admin-modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="margin: 0; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-pencil-square" style="color: var(--primary);"></i> Edit Package Config
            </h3>
            <button type="button" class="admin-modal-close" onclick="$('#edit-package-modal').fadeOut(200);" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        <form id="edit-package-form" action="" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 15px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Package Name</label>
                <input type="text" id="edit_pkg_name" name="name" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Description</label>
                <textarea id="edit_pkg_description" name="description" rows="2" class="form-control" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem;"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div class="form-group">
                    <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Package Type</label>
                    <select id="edit_pkg_package_type" name="package_type" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        <option value="Free">Free</option>
                        <option value="To Pay">To Pay</option>
                    </select>
                </div>
                <div class="form-group">
                    <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Price (TZS)</label>
                    <input type="number" id="edit_pkg_price" name="price" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div class="form-group">
                    <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Duration</label>
                    <input type="number" id="edit_pkg_duration" name="duration" class="form-control" min="-1" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    <span style="font-size: 0.72rem; color: #64748b; margin-top: 3px; display: block;">Enter -1 for Lifetime.</span>
                </div>
                <div class="form-group">
                    <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Duration Unit</label>
                    <select id="edit_pkg_duration_unit" name="duration_unit" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        <option value="days">Days</option>
                        <option value="months">Months</option>
                        <option value="years">Years</option>
                        <option value="lifetime">Lifetime (No End)</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div class="form-group">
                    <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Phone Visibility</label>
                    <select id="edit_pkg_phone_visibility" name="phone_visibility" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                        <option value="No">No (Hidden)</option>
                        <option value="Yes">Yes (Visible)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Max Images</label>
                    <input type="number" id="edit_pkg_max_images" name="max_images" class="form-control" min="-1" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    <span style="font-size: 0.72rem; color: #64748b; margin-top: 3px; display: block;">Enter -1 for Unlimited.</span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                <div class="form-group">
                    <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Max Videos</label>
                    <input type="number" id="edit_pkg_max_videos" name="max_videos" class="form-control" min="-1" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    <span style="font-size: 0.72rem; color: #64748b; margin-top: 3px; display: block;">Enter -1 for Unlimited.</span>
                </div>
                <div class="form-group">
                    <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Max News Articles</label>
                    <input type="number" id="edit_pkg_max_news" name="max_news" class="form-control" min="-1" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    <span style="font-size: 0.72rem; color: #64748b; margin-top: 3px; display: block;">Enter -1 for Unlimited.</span>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Status</label>
                <select id="edit_pkg_status" name="status" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--border-color); padding-top: 15px;">
                <button type="button" onclick="$('#edit-package-modal').fadeOut(200);" style="padding: 10px 20px; border-radius: 10px; font-weight: 600; background: #e2e8f0; border: none; color: #475569; cursor: pointer;">Cancel</button>
                <button type="submit" style="padding: 10px 24px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(99,102,241,0.35); cursor: pointer;">
                    Save Package Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==========================================================================
     MODAL: Record Invoice Payment
   ========================================================================== -->
<div id="record-payment-modal" class="admin-modal">
    <div class="admin-modal-content" style="border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-width: 500px; width: 90%; margin: auto;">
        <div class="admin-modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="margin: 0; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-wallet2" style="color: #10b981;"></i> Record Invoice Payment
            </h3>
            <button type="button" class="admin-modal-close" onclick="$('#record-payment-modal').fadeOut(200);" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        <form id="record-payment-form" action="" method="POST">
            @csrf
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; margin-bottom: 18px;">
                <div style="font-size: 0.78rem; text-transform: uppercase; color: #64748b; font-weight: 700;">Invoice Reference</div>
                <div id="pay_invoice_number" style="font-weight: 800; font-size: 0.95rem; color: #0f172a; margin-top: 2px;"></div>
                <div id="pay_user_name" style="font-size: 0.83rem; color: #475569;"></div>
                <div style="font-size: 0.83rem; color: #ef4444; font-weight: 700; margin-top: 4px;">Outstanding Balance: TZS <span id="pay_outstanding_balance"></span></div>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Payment Amount (TZS)</label>
                <input type="number" id="pay_amount_paid" name="amount_paid" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Payment Method</label>
                <select name="payment_method" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    <option value="Mobile Money (M-Pesa/Tigo Pesa/Airtel Money)">Mobile Money</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Cash Payment">Cash Payment</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Transaction / Reference Number</label>
                <input type="text" name="payment_reference" class="form-control" placeholder="e.g. PP260829.1122" style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Administrative Notes</label>
                <textarea name="notes" rows="2" class="form-control" placeholder="Add optional transaction remarks..." style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px; font-size: 0.88rem;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--border-color); padding-top: 15px;">
                <button type="button" onclick="$('#record-payment-modal').fadeOut(200);" style="padding: 10px 20px; border-radius: 10px; font-weight: 600; background: #e2e8f0; border: none; color: #475569; cursor: pointer;">Cancel</button>
                <button type="submit" style="padding: 10px 24px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(16,185,129,0.3); cursor: pointer;">
                    Log Payment
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==========================================================================
     MODAL: Manage & Assign User Package
   ========================================================================== -->
<div id="manage-user-package-modal" class="admin-modal">
    <div class="admin-modal-content" style="border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-width: 520px; width: 90%; margin: auto;">
        <div class="admin-modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="margin: 0; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-box-seam-fill" style="color: #f59e0b;"></i> Manage Package: <span id="assign_user_name" style="color: #6366f1;"></span>
            </h3>
            <button type="button" class="admin-modal-close" onclick="$('#manage-user-package-modal').fadeOut(200);" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        <form id="assign-package-form" action="" method="POST">
            @csrf
            
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; margin-bottom: 18px; font-size: 0.88rem;">
                <div style="font-weight: 700; color: #475569;">Active Package details:</div>
                <div style="display: flex; justify-content: space-between; margin-top: 4px;">
                    <span style="color: #64748b;">Package Name:</span>
                    <strong id="assign_current_pkg_name" style="color: #0f172a;"></strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 2px;">
                    <span style="color: #64748b;">Start Date:</span>
                    <span id="assign_current_start" style="color: #0f172a; font-weight: 600;"></span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 2px;">
                    <span style="color: #64748b;">End Date:</span>
                    <span id="assign_current_end" style="color: #0f172a; font-weight: 600;"></span>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Select New Package to Assign</label>
                <select name="package_id" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    <option value="">-- Choose Active Package --</option>
                    @foreach($packages->where('status', 'Active') as $p)
                    <option value="{{ $p->id }}">
                        {{ $p->name }} (TZS {{ number_format($p->price) }} / {{ $p->duration }} {{ $p->duration_unit }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Duration (Months)</label>
                <select name="months" class="form-control" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
                    @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $m === 1 ? 'selected' : '' }}>{{ $m }} {{ $m === 1 ? 'Month' : 'Months' }}</option>
                    @endfor
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="color: #475569; font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 6px;">Subscription Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ now()->toDateString() }}" required style="background: #fff; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 14px;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--border-color); padding-top: 15px;">
                <button type="button" onclick="$('#manage-user-package-modal').fadeOut(200);" style="padding: 10px 20px; border-radius: 10px; font-weight: 600; background: #e2e8f0; border: none; color: #475569; cursor: pointer;">Cancel</button>
                <button type="submit" style="padding: 10px 24px; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none; color: #fff; box-shadow: 0 4px 15px rgba(245,158,11,0.35); cursor: pointer;">
                    Assign Package
                </button>
            </div>
        </form>
    </div>
</div>
