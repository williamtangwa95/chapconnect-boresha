        <!-- ==========================================
         TAB: Invoices & Billing
         ========================================== -->
        <div id="tab-invoices" class="tab-content">
            <div class="admin-header" style="margin-bottom: 25px;">
                <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
                    <i class="bi bi-receipt-cutoff" style="color: #ef4444;"></i> Invoices & Billing Operations
                </h1>
                <p style="color: #64748b; margin: 0; font-size: 0.92rem;">Review generated bills, record payments, and track outstanding talent balances.</p>
            </div>

            <!-- Financial Summary Cards -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 25px;">
                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 20px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(99,102,241,0.12); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="stat-info" style="flex-grow: 1;">
                        <div class="stat-value" style="font-size: 1.3rem; font-weight: 800; color: #0f172a;">TZS {{ number_format($billingStats['total_billed']) }}</div>
                        <div class="stat-label" style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Total Billed Amount</div>
                    </div>
                </div>

                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 20px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16,185,129,0.12); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div class="stat-info" style="flex-grow: 1;">
                        <div class="stat-value" style="font-size: 1.3rem; font-weight: 800; color: #0f172a;">TZS {{ number_format($billingStats['total_paid']) }}</div>
                        <div class="stat-label" style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Total Received Payments</div>
                    </div>
                </div>

                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 20px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(239,68,68,0.12); color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                        <i class="bi bi-exclamation-circle-fill"></i>
                    </div>
                    <div class="stat-info" style="flex-grow: 1;">
                        <div class="stat-value" style="font-size: 1.3rem; font-weight: 800; color: #ef4444;">TZS {{ number_format($billingStats['total_outstanding']) }}</div>
                        <div class="stat-label" style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Total Outstanding Balance</div>
                    </div>
                </div>

                <div class="stat-card" style="background: #ffffff; border-radius: 16px; padding: 20px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 14px;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(245,158,11,0.12); color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <div class="stat-info" style="flex-grow: 1;">
                        <div class="stat-value" style="font-size: 1.3rem; font-weight: 800; color: #0f172a;">{{ $billingStats['active_subs'] }} Active</div>
                        <div class="stat-label" style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Subscribed Talents</div>
                    </div>
                </div>
            </div>

            <div class="admin-card" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
                <div class="admin-table-container">
                    <table class="admin-table display nowrap" id="invoices-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Talent User</th>
                                <th>Package</th>
                                <th>Billed</th>
                                <th>Paid</th>
                                <th>Outstanding</th>
                                <th>Billing Period</th>
                                <th>Payment Status</th>
                                <th style="text-align: right; width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $inv)
                            <tr>
                                <td>
                                    <span style="font-family: monospace; font-weight: 800; font-size: 0.82rem; color: #4338ca; background: rgba(99,102,241,0.1); padding: 4px 9px; border-radius: 6px; border: 1px solid rgba(99,102,241,0.2);">
                                        {{ $inv->invoice_number }}
                                    </span>
                                </td>
                                <td style="font-weight: 700; color: #0f172a;">{{ $inv->user ? $inv->user->name : 'N/A' }}</td>
                                <td>{{ $inv->package_name }}</td>
                                <td style="font-weight: 700;">TZS {{ number_format($inv->amount) }}</td>
                                <td style="font-weight: 700; color: #10b981;">TZS {{ number_format($inv->amount_paid) }}</td>
                                <td style="font-weight: 700; color: #ef4444;">TZS {{ number_format($inv->amount - $inv->amount_paid) }}</td>
                                <td style="font-size: 0.8rem; color: #475569;">
                                    {{ date('M d, Y', strtotime($inv->start_date)) }} - {{ date('M d, Y', strtotime($inv->end_date)) }}
                                </td>
                                <td>
                                    <span style="font-size: 0.72rem; font-weight: 800; padding: 3px 10px; border-radius: 20px; {{ $inv->payment_status === 'Paid' ? 'background: rgba(16,185,129,0.1); color: #10b981;' : ($inv->payment_status === 'Unpaid' ? 'background: rgba(239,68,68,0.1); color: #ef4444;' : 'background: rgba(100,100,100,0.1); color: #64748b;') }}">
                                        {{ $inv->payment_status }}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    @if($inv->payment_status !== 'Paid')
                                    <button type="button" class="btn-record-payment"
                                        data-id="{{ $inv->id }}"
                                        data-number="{{ $inv->invoice_number }}"
                                        data-name="{{ $inv->user ? $inv->user->name : '' }}"
                                        data-outstanding="{{ $inv->amount - $inv->amount_paid }}"
                                        style="padding: 6px 10px; font-size: 0.75rem; border: none; color: #fff; border-radius: 8px; font-weight: 700; background: linear-gradient(135deg, #10b981 0%, #059669 100%); cursor: pointer;">
                                        <i class="bi bi-wallet2"></i> Pay
                                    </button>
                                    @endif
                                    <a href="/dashboard/invoice/{{ $inv->id }}" target="_blank" style="padding: 6px 10px; font-size: 0.75rem; border: 1px solid #cbd5e1; color: #475569; border-radius: 8px; font-weight: 700; background: #fff; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="bi bi-printer"></i> Print
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
