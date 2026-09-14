<!-- ==========================================
     TAB: Payment Methods Settings (Super Admin)
     ========================================== -->
<div id="tab-payment-methods" class="tab-content" style="display: none;">
    <div class="admin-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 25px;">
        <div>
            <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
                <i class="bi bi-credit-card-2-front-fill" style="color: #6366f1;"></i> Payment Channels & Methods Settings
            </h1>
            <p style="color: #64748b; margin: 0; font-size: 0.92rem;">Manage company payment accounts (CRDB, NMB, TigoPesa, HaloPesa, M-Pesa), Account Names, Lipa Numbers, and provider logos displayed during account publishing payment.</p>
        </div>
        <button type="button" onclick="$('#add-payment-method-modal').fadeIn(200);" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; border: none; border-radius: 30px; font-weight: 700; font-size: 0.9rem; cursor: pointer; box-shadow: 0 4px 15px rgba(99,102,241,0.35); transition: all 0.3s ease;">
            <i class="bi bi-plus-circle-fill"></i> Add Payment Channel
        </button>
    </div>

    <div class="admin-card" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
        <div class="admin-table-container">
            <table class="admin-table display nowrap" id="payment-methods-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">Logo</th>
                        <th>Company / Provider</th>
                        <th>Account Name / Lipa Name</th>
                        <th>Account / Lipa Number</th>
                        <th>Instructions</th>
                        <th>Status</th>
                        <th style="text-align: right; width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paymentMethods as $method)
                    <tr>
                        <td style="text-align: center;">
                            <img src="{{ $method->logo_url }}" alt="{{ $method->company }}" style="width: 38px; height: 38px; object-fit: contain; border-radius: 8px; border: 1px solid #cbd5e1; padding: 2px; background: #fff;" onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.png') }}';">
                        </td>
                        <td style="font-weight: 800; color: #0f172a; font-size: 0.92rem;">
                            {{ $method->company }}
                        </td>
                        <td style="font-weight: 700; color: #334155; font-size: 0.88rem;">
                            {{ $method->account_name }}
                        </td>
                        <td style="font-weight: 800; color: #6366f1; font-size: 0.95rem; font-family: monospace;">
                            {{ $method->account_number }}
                        </td>
                        <td style="font-size: 0.82rem; color: #64748b; max-width: 260px; white-space: normal;">
                            {{ $method->instructions ?: 'N/A' }}
                        </td>
                        <td>
                            @if($method->is_active)
                            <span style="font-size: 0.72rem; font-weight: 800; padding: 3px 10px; border-radius: 20px; background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.25);">
                                Active
                            </span>
                            @else
                            <span style="font-size: 0.72rem; font-weight: 800; padding: 3px 10px; border-radius: 20px; background: rgba(100,100,100,0.1); color: #64748b; border: 1px solid rgba(200,200,200,0.3);">
                                Disabled
                            </span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; justify-content: flex-end; gap: 6px; align-items: center;">
                                <button type="button" class="btn-edit-payment-method"
                                    data-id="{{ $method->id }}"
                                    data-company="{{ $method->company }}"
                                    data-account-name="{{ $method->account_name }}"
                                    data-account-number="{{ $method->account_number }}"
                                    data-instructions="{{ $method->instructions }}"
                                    data-is-active="{{ $method->is_active ? 1 : 0 }}"
                                    style="padding: 5px 12px; font-size: 0.78rem; border: 1px solid #cbd5e1; color: var(--primary); border-radius: 8px; font-weight: 600; background: #fff; cursor: pointer;">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                                <form action="{{ route('admin.payment-methods.delete', $method->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this payment channel?');" style="margin: 0; display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding: 5px 12px; font-size: 0.78rem; border: 1px solid #fca5a5; color: #ef4444; border-radius: 8px; font-weight: 600; background: #fff; cursor: pointer;">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 30px; color: #64748b;">
                            No payment channels configured yet. Click <strong>Add Payment Channel</strong> to create one.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add Payment Channel -->
<div id="add-payment-method-modal" class="admin-modal">
    <div class="admin-modal-content" style="border-radius: 16px; max-width: 520px; width: 90%; margin: auto;">
        <div class="admin-modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 14px; margin-bottom: 18px;">
            <h3 style="margin: 0; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-plus-circle-fill" style="color: #6366f1;"></i> Add Payment Channel
            </h3>
            <button type="button" class="admin-modal-close" onclick="$('#add-payment-method-modal').fadeOut(200);" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        <form action="{{ route('admin.payment-methods.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group" style="margin-bottom: 14px;">
                <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 5px;">Company / Bank Provider</label>
                <input type="text" name="company" placeholder="e.g. CRDB Bank, NMB Bank, TigoPesa, HaloPesa, M-Pesa" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 5px;">Account Name / Lipa Name</label>
                <input type="text" name="account_name" placeholder="e.g. CHAPCONNECT LIMITED / CHAPCONNECT LIPA" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 5px;">Account Number / Lipa Number</label>
                <input type="text" name="account_number" placeholder="e.g. 0150123456700 or Lipa Code 554433" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 5px;">Provider Logo / Icon (Optional)</label>
                <input type="file" name="logo" accept="image/*" style="width: 100%; padding: 8px; border: 1px dashed #cbd5e1; border-radius: 8px; background: #f8fafc; font-size: 0.84rem; box-sizing: border-box;">
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 5px;">Payment Instructions (Optional)</label>
                <textarea name="instructions" rows="2" placeholder="e.g. Piga *150*01# -> Lipa kwa TigoPesa kwenda Lipa Namba..." style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.86rem; box-sizing: border-box;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e2e8f0; padding-top: 14px;">
                <button type="button" onclick="$('#add-payment-method-modal').fadeOut(200);" style="padding: 10px 20px; border-radius: 8px; background: #e2e8f0; border: none; color: #475569; font-weight: 600; cursor: pointer;">Cancel</button>
                <button type="submit" style="padding: 10px 24px; border-radius: 8px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; font-weight: 700; cursor: pointer; box-shadow: 0 4px 14px rgba(99,102,241,0.35);">Save Payment Channel</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Payment Channel -->
<div id="edit-payment-method-modal" class="admin-modal">
    <div class="admin-modal-content" style="border-radius: 16px; max-width: 520px; width: 90%; margin: auto;">
        <div class="admin-modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 14px; margin-bottom: 18px;">
            <h3 style="margin: 0; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-pencil-square" style="color: #6366f1;"></i> Edit Payment Channel
            </h3>
            <button type="button" class="admin-modal-close" onclick="$('#edit-payment-method-modal').fadeOut(200);" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        <form id="edit-payment-method-form" method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div class="form-group" style="margin-bottom: 14px;">
                <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 5px;">Company / Bank Provider</label>
                <input type="text" id="edit_pm_company" name="company" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 5px;">Account Name / Lipa Name</label>
                <input type="text" id="edit_pm_account_name" name="account_name" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 5px;">Account Number / Lipa Number</label>
                <input type="text" id="edit_pm_account_number" name="account_number" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 5px;">Replace Provider Logo / Icon (Optional)</label>
                <input type="file" name="logo" accept="image/*" style="width: 100%; padding: 8px; border: 1px dashed #cbd5e1; border-radius: 8px; background: #f8fafc; font-size: 0.84rem; box-sizing: border-box;">
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 700; color: #334155; font-size: 0.85rem; margin-bottom: 5px;">Payment Instructions</label>
                <textarea id="edit_pm_instructions" name="instructions" rows="2" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.86rem; box-sizing: border-box;"></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #334155; font-size: 0.85rem; cursor: pointer;">
                    <input type="checkbox" id="edit_pm_is_active" name="is_active" value="1" style="width: auto; cursor: pointer;"> Channel Active & Available for Payments
                </label>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e2e8f0; padding-top: 14px;">
                <button type="button" onclick="$('#edit-payment-method-modal').fadeOut(200);" style="padding: 10px 20px; border-radius: 8px; background: #e2e8f0; border: none; color: #475569; font-weight: 600; cursor: pointer;">Cancel</button>
                <button type="submit" style="padding: 10px 24px; border-radius: 8px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; color: #fff; font-weight: 700; cursor: pointer; box-shadow: 0 4px 14px rgba(99,102,241,0.35);">Update Channel</button>
            </div>
        </form>
    </div>
</div>
