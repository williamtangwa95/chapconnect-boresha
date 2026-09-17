        <!-- ==========================================
         TAB: Invoices & Billing
         ========================================== -->
        @php
            $userInvoiceCounts = $invoices->groupBy('user_id')->map->count();
            $usersWithMultiple = $userInvoiceCounts->filter(fn($c) => $c > 1)->count();
        @endphp

        <div id="tab-invoices" class="tab-content">
            <div class="admin-header" style="margin-bottom: 25px; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 15px;">
                <div>
                    <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
                        <i class="bi bi-receipt-cutoff" style="color: #ef4444;"></i> Invoices & Billing Operations
                    </h1>
                    <p style="color: #64748b; margin: 0; font-size: 0.92rem;">Review generated bills, record payments, manage duplicate user bills, and track balances.</p>
                </div>
                @if($usersWithMultiple > 0)
                <div>
                    <button type="button" class="btn-clean-all-duplicates"
                        style="padding: 9px 16px; font-size: 0.85rem; border: none; color: #fff; border-radius: 10px; font-weight: 700; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); cursor: pointer; box-shadow: 0 4px 12px rgba(245,158,11,0.25); display: inline-flex; align-items: center; gap: 8px;">
                        <i class="bi bi-magic"></i> Clean Duplicate Invoices ({{ $usersWithMultiple }} Talents)
                    </button>
                </div>
                @endif
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

            <!-- Bulk Actions Bar for Invoices -->
            <div id="invoices-bulk-bar" style="display: none; background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 20px; margin-bottom: 20px; align-items: center; justify-content: space-between; box-shadow: 0 4px 12px rgba(0,0,0,0.04);">
                <div style="display: flex; align-items: center; gap: 10px; font-weight: 700; color: #1e293b; font-size: 0.9rem;">
                    <span style="background: rgba(99,102,241,0.12); color: #4338ca; padding: 4px 10px; border-radius: 20px; font-size: 0.82rem;" id="invoices-selected-count">0 Selected</span>
                    <span>Manage selected invoices:</span>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="button" id="btn-bulk-delete-invoices" style="padding: 7px 14px; font-size: 0.8rem; border: none; color: #fff; border-radius: 8px; font-weight: 700; background: #ef4444; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="bi bi-trash-fill"></i> Delete Selected Invoices
                    </button>
                </div>
            </div>

            <div class="admin-card" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
                <div class="admin-table-container">
                    <table class="admin-table display nowrap" id="invoices-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 30px; text-align: center;">
                                    <input type="checkbox" id="select-all-invoices" style="width: 16px; height: 16px; cursor: pointer;">
                                </th>
                                <th>Invoice #</th>
                                <th>Talent User</th>
                                <th>Package</th>
                                <th>Billed</th>
                                <th>Paid</th>
                                <th>Outstanding</th>
                                <th>Billing Period</th>
                                <th>Payment Status</th>
                                <th style="text-align: right; width: 220px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $inv)
                            @php
                                $userCount = $inv->user_id ? ($userInvoiceCounts[$inv->user_id] ?? 1) : 1;
                                $isPaid = $inv->payment_status === 'Paid';
                            @endphp
                            <tr>
                                <td style="text-align: center;">
                                    @if($isPaid)
                                    <span title="Paid invoices are protected from deletion" style="color: #cbd5e1; font-size: 0.9rem;"><i class="bi bi-shield-lock-fill"></i></span>
                                    @else
                                    <input type="checkbox" class="invoice-checkbox" value="{{ $inv->id }}" data-user-id="{{ $inv->user_id }}" style="width: 16px; height: 16px; cursor: pointer;">
                                    @endif
                                </td>
                                <td>
                                    <span style="font-family: monospace; font-weight: 800; font-size: 0.82rem; color: #4338ca; background: rgba(99,102,241,0.1); padding: 4px 9px; border-radius: 6px; border: 1px solid rgba(99,102,241,0.2);">
                                        {{ $inv->invoice_number }}
                                    </span>
                                </td>
                                <td style="font-weight: 700; color: #0f172a;">
                                    <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                        <span>{{ $inv->user ? $inv->user->name : 'N/A' }}</span>
                                        @if($userCount > 1)
                                        <span style="font-size: 0.68rem; font-weight: 800; background: #fef3c7; color: #92400e; padding: 2px 7px; border-radius: 12px; border: 1px solid #fde68a; display: inline-flex; align-items: center; gap: 3px;" title="This talent has {{ $userCount }} invoices">
                                            <i class="bi bi-stack"></i> {{ $userCount }} Invoices
                                        </span>
                                        @endif
                                    </div>
                                    @if($userCount > 1)
                                    <div style="margin-top: 4px;">
                                        <button type="button" class="btn-keep-one-talent"
                                            data-user-id="{{ $inv->user_id }}"
                                            data-user-name="{{ $inv->user ? $inv->user->name : '' }}"
                                            style="padding: 2px 7px; font-size: 0.68rem; border: 1px solid #f59e0b; color: #b45309; border-radius: 6px; font-weight: 700; background: #fffbebfb; cursor: pointer; display: inline-flex; align-items: center; gap: 3px;"
                                            title="Delete extra unpaid invoices for this talent user, leaving 1 invoice">
                                            <i class="bi bi-trash3"></i> Keep Only 1 Invoice
                                        </button>
                                    </div>
                                    @endif
                                    @if($inv->notes)
                                    <div style="font-size: 0.74rem; color: #0284c7; font-weight: 600; margin-top: 3px; max-width: 250px; white-space: normal;">
                                        <i class="bi bi-credit-card-2-front-fill" style="color: #6366f1;"></i> {{ $inv->notes }}
                                    </div>
                                    @endif
                                </td>
                                @php
                                    $isLifetimeInv = $inv->duration == -1 || $inv->duration_unit === 'lifetime' || str_contains($inv->end_date, '2099');
                                    $cleanPackageName = $isLifetimeInv ? preg_replace('/\s*\(\d+\s*Months?\)/i', '', $inv->package_name) : $inv->package_name;
                                @endphp
                                <td>{{ $cleanPackageName }}</td>
                                <td style="font-weight: 700;">TZS {{ number_format($inv->amount) }}</td>
                                <td style="font-weight: 700; color: #10b981;">TZS {{ number_format($inv->amount_paid) }}</td>
                                <td style="font-weight: 700; color: #ef4444;">TZS {{ number_format($inv->amount - $inv->amount_paid) }}</td>
                                <td style="font-size: 0.8rem; color: #475569;">
                                    @if($isLifetimeInv)
                                        <span style="font-weight: 700; color: #6366f1;"><i class="bi bi-infinity"></i> Lifetime</span>
                                    @else
                                        {{ date('M d, Y', strtotime($inv->start_date)) }} - {{ date('M d, Y', strtotime($inv->end_date)) }}
                                    @endif
                                </td>
                                <td>
                                    <span style="font-size: 0.72rem; font-weight: 800; padding: 3px 10px; border-radius: 20px; {{ $inv->payment_status === 'Paid' ? 'background: rgba(16,185,129,0.1); color: #10b981;' : ($inv->payment_status === 'Unpaid' ? 'background: rgba(239,68,68,0.1); color: #ef4444;' : 'background: rgba(100,100,100,0.1); color: #64748b;') }}">
                                        {{ $inv->payment_status }}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 5px;">
                                        @if(!$isPaid)
                                        <button type="button" class="btn-record-payment"
                                            data-id="{{ $inv->id }}"
                                            data-number="{{ $inv->invoice_number }}"
                                            data-name="{{ $inv->user ? $inv->user->name : '' }}"
                                            data-outstanding="{{ $inv->amount - $inv->amount_paid }}"
                                            style="padding: 6px 9px; font-size: 0.75rem; border: none; color: #fff; border-radius: 8px; font-weight: 700; background: linear-gradient(135deg, #10b981 0%, #059669 100%); cursor: pointer;">
                                            <i class="bi bi-wallet2"></i> Pay
                                        </button>
                                        @endif
                                        <a href="/dashboard/invoice/{{ $inv->id }}" target="_blank" style="padding: 6px 9px; font-size: 0.75rem; border: 1px solid #cbd5e1; color: #475569; border-radius: 8px; font-weight: 700; background: #fff; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="bi bi-printer"></i> Print
                                        </a>
                                        @if(!$isPaid)
                                        <button type="button" class="btn-delete-invoice"
                                            data-id="{{ $inv->id }}"
                                            data-number="{{ $inv->invoice_number }}"
                                            style="padding: 6px 9px; font-size: 0.75rem; border: 1px solid #fca5a5; color: #dc2626; border-radius: 8px; font-weight: 700; background: #fef2f2; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;"
                                            title="Delete Invoice">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                        @else
                                        <span style="font-size: 0.73rem; font-weight: 700; color: #10b981; background: rgba(16,185,129,0.08); padding: 4px 8px; border-radius: 6px; border: 1px solid rgba(16,185,129,0.2); display: inline-flex; align-items: center; gap: 4px;" title="Paid invoices cannot be deleted to preserve payment audit trail">
                                            <i class="bi bi-shield-check"></i> Paid (Protected)
                                        </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" style="text-align: center; color: #94a3b8; padding: 30px; font-style: italic;">No package invoices registered.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Hidden Invoice Operation Forms -->
        <form id="single-delete-invoice-form" action="" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        <form id="bulk-delete-invoices-form" action="{{ route('admin.invoices.bulk-delete') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="invoice_ids" id="bulk-delete-invoice-ids">
        </form>

        <form id="keep-one-invoice-form" action="{{ route('admin.invoices.keep-one') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="user_id" id="keep-one-user-id" value="">
        </form>

