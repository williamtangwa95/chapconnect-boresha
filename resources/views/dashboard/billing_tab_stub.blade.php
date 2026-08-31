        <!-- Billing & Subscription Details Panel -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; margin-top: 25px;">
            
            <!-- Card 1: Active Subscription & Limits -->
            <div class="dashboard-panel" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
                <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 1.15rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-box-seam-fill" style="color: #f59e0b;"></i> My Package & Limits
                </h3>

                <div style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; margin-bottom: 18px;">
                    <div>
                        <div style="font-size: 0.76rem; text-transform: uppercase; color: #64748b; font-weight: 700;">Active Package</div>
                        <div style="font-weight: 800; font-size: 1.1rem; color: #0f172a; margin-top: 2px;">{{ $packageDetails['name'] }}</div>
                    </div>
                    <span style="font-size: 0.72rem; font-weight: 800; padding: 4px 12px; border-radius: 20px; text-transform: uppercase; {{ $packageDetails['package_type'] === 'Free' ? 'background: rgba(16,185,129,0.1); color: #10b981;' : 'background: rgba(99,102,241,0.1); color: #6366f1;' }}">
                        {{ $packageDetails['package_type'] }}
                    </span>
                </div>

                @php $isLifetime = ($packageDetails['duration_unit'] === 'lifetime'); @endphp

                @if($isLifetime)
                {{-- ===== LIFETIME PACKAGE DISPLAY ===== --}}
                <div style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%); border-radius: 14px; padding: 20px 22px; margin-bottom: 18px; border: 1px solid rgba(99,102,241,0.35); position: relative; overflow: hidden;">
                    <!-- shimmer bar -->
                    <div style="position: absolute; top: 0; left: -100%; width: 60%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent); animation: shimmer 2.5s infinite;"></div>
                    <style>@keyframes shimmer { 0%{left:-100%} 100%{left:150%} }</style>

                    <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                        <div style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center; font-size: 1.6rem; flex-shrink: 0; box-shadow: 0 4px 14px rgba(99,102,241,0.45);">
                            <i class="bi bi-infinity" style="color: #fff;"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.08em; color: #a5b4fc; font-weight: 700; margin-bottom: 3px;">Access Type</div>
                            <div style="font-size: 1.2rem; font-weight: 900; color: #ffffff; display: flex; align-items: center; gap: 8px;">
                                Lifetime Access
                                <span style="font-size: 0.7rem; font-weight: 700; background: linear-gradient(135deg, #f59e0b, #f97316); color: #fff; padding: 2px 10px; border-radius: 20px; letter-spacing: 0.04em;">FOREVER</span>
                            </div>
                            <div style="font-size: 0.78rem; color: #94a3b8; margin-top: 4px;">Your subscription never expires. Enjoy full access for life.</div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 18px;">
                        <div style="background: rgba(255,255,255,0.06); border-radius: 10px; padding: 10px 14px;">
                            <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 600; margin-bottom: 3px;">Subscription Price</div>
                            <div style="font-size: 0.95rem; font-weight: 800; color: #f8fafc;">TZS {{ number_format($packageDetails['price']) }}</div>
                        </div>
                        <div style="background: rgba(255,255,255,0.06); border-radius: 10px; padding: 10px 14px;">
                            <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 600; margin-bottom: 3px;">Phone Visibility</div>
                            <div style="font-size: 0.95rem; font-weight: 800; color: {{ $packageDetails['phone_visibility'] === 'Yes' ? '#34d399' : '#f87171' }};">
                                <i class="bi {{ $packageDetails['phone_visibility'] === 'Yes' ? 'bi-telephone-fill' : 'bi-eye-slash-fill' }}"></i>
                                {{ $packageDetails['phone_visibility'] === 'Yes' ? 'Visible' : 'Hidden' }}
                            </div>
                        </div>
                        <div style="background: rgba(255,255,255,0.06); border-radius: 10px; padding: 10px 14px; grid-column: 1 / -1;">
                            <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 600; margin-bottom: 3px;">Duration</div>
                            <div style="font-size: 0.95rem; font-weight: 800; color: #a5b4fc; display: flex; align-items: center; gap: 6px;">
                                <i class="bi bi-infinity"></i> Lifetime — no expiry date
                            </div>
                        </div>
                    </div>
                </div>

                @else
                {{-- ===== TIMED PACKAGE DISPLAY ===== --}}
                @php
                    $diff = strtotime($packageDetails['end_date']) - time();
                    $daysRemaining = max(0, ceil($diff / (60 * 60 * 24)));
                    $totalDays = $packageDetails['duration'];
                    $expiredSoon = $daysRemaining <= 5;
                @endphp
                <div style="display: flex; flex-direction: column; gap: 8px; font-size: 0.85rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #64748b;">Subscription Price:</span>
                        <strong style="color: #1e293b;">TZS {{ number_format($packageDetails['price']) }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #64748b;">Start Date:</span>
                        <span style="color: #1e293b; font-weight: 600;">{{ date('M d, Y', strtotime($packageDetails['start_date'])) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #64748b;">End Date:</span>
                        <span style="color: #1e293b; font-weight: 600;">{{ date('M d, Y', strtotime($packageDetails['end_date'])) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #64748b;">Duration Period:</span>
                        <span style="color: #1e293b; font-weight: 600;">{{ $packageDetails['duration'] }} {{ $packageDetails['duration_unit'] }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #64748b;">Remaining Days:</span>
                        <strong style="color: {{ $expiredSoon ? '#ef4444' : '#6366f1' }}; display: flex; align-items: center; gap: 4px;">
                            @if($expiredSoon)
                                <i class="bi bi-exclamation-triangle-fill" style="font-size: 0.8rem;"></i>
                            @endif
                            {{ $daysRemaining }} days remaining
                        </strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #64748b;">Phone Number Visibility:</span>
                        <strong style="color: {{ $packageDetails['phone_visibility'] === 'Yes' ? '#10b981' : '#ef4444' }};">{{ $packageDetails['phone_visibility'] === 'Yes' ? 'Visible' : 'Hidden' }}</strong>
                    </div>
                </div>
                @endif


                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <!-- Images Usage Progress -->
                    <div>
                        @php
                            $isImagesUnlimited = ($packageDetails['max_images'] == -1);
                            $imgPercent = (!$isImagesUnlimited && $packageDetails['max_images'] > 0) ? min(100, ($usage['images_used'] / $packageDetails['max_images']) * 100) : 0;
                        @endphp
                        <div style="display: flex; justify-content: space-between; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">
                            <span style="color: #475569;">Portfolio Images</span>
                            <span style="color: #0f172a;">{{ $usage['images_used'] }} / {{ $isImagesUnlimited ? '∞' : $packageDetails['max_images'] }}</span>
                        </div>
                        <div style="background: #f1f5f9; height: 8px; border-radius: 4px; overflow: hidden;">
                            <div style="width: {{ $imgPercent }}%; height: 100%; background: {{ $imgPercent >= 100 ? '#ef4444' : '#6366f1' }}; border-radius: 4px;"></div>
                        </div>
                        <span style="font-size: 0.74rem; color: #64748b; margin-top: 2px; display: block;">{{ $isImagesUnlimited ? 'Unlimited remaining' : max(0, $packageDetails['max_images'] - $usage['images_used']) . ' images remaining' }}</span>
                    </div>

                    <!-- Videos Usage Progress -->
                    <div>
                        @php
                            $isVideosUnlimited = ($packageDetails['max_videos'] == -1);
                            $vidPercent = (!$isVideosUnlimited && $packageDetails['max_videos'] > 0) ? min(100, ($usage['videos_used'] / $packageDetails['max_videos']) * 100) : 0;
                        @endphp
                        <div style="display: flex; justify-content: space-between; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">
                            <span style="color: #475569;">Portfolio Videos</span>
                            <span style="color: #0f172a;">{{ $usage['videos_used'] }} / {{ $isVideosUnlimited ? '∞' : $packageDetails['max_videos'] }}</span>
                        </div>
                        <div style="background: #f1f5f9; height: 8px; border-radius: 4px; overflow: hidden;">
                            <div style="width: {{ $vidPercent }}%; height: 100%; background: {{ $vidPercent >= 100 ? '#ef4444' : '#6366f1' }}; border-radius: 4px;"></div>
                        </div>
                        <span style="font-size: 0.74rem; color: #64748b; margin-top: 2px; display: block;">{{ $isVideosUnlimited ? 'Unlimited remaining' : max(0, $packageDetails['max_videos'] - $usage['videos_used']) . ' videos remaining' }}</span>
                    </div>

                    <!-- News Usage Progress -->
                    <div>
                        @php
                            $isNewsUnlimited = ($packageDetails['max_news'] == -1);
                            $newsPercent = (!$isNewsUnlimited && $packageDetails['max_news'] > 0) ? min(100, ($usage['news_used'] / $packageDetails['max_news']) * 100) : 0;
                        @endphp
                        <div style="display: flex; justify-content: space-between; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px;">
                            <span style="color: #475569;">News Updates / Articles</span>
                            <span style="color: #0f172a;">{{ $usage['news_used'] }} / {{ $isNewsUnlimited ? '∞' : $packageDetails['max_news'] }}</span>
                        </div>
                        <div style="background: #f1f5f9; height: 8px; border-radius: 4px; overflow: hidden;">
                            <div style="width: {{ $newsPercent }}%; height: 100%; background: {{ $newsPercent >= 100 ? '#ef4444' : '#6366f1' }}; border-radius: 4px;"></div>
                        </div>
                        <span style="font-size: 0.74rem; color: #64748b; margin-top: 2px; display: block;">{{ $isNewsUnlimited ? 'Unlimited remaining' : max(0, $packageDetails['max_news'] - $usage['news_used']) . ' articles remaining' }}</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Billing & Invoice History -->
            <div class="dashboard-panel" style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
                <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 1.15rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-receipt-cutoff" style="color: #ef4444;"></i> Billing & Invoices History
                </h3>

                <div class="admin-table-container">
                    <table class="admin-table display nowrap" id="my-invoices-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Package</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th style="text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myInvoices as $inv)
                            <tr>
                                <td>
                                    <span style="font-family: monospace; font-weight: 800; font-size: 0.8rem; color: #4338ca; background: rgba(99,102,241,0.08); padding: 3px 7px; border-radius: 4px; border: 1px solid rgba(99,102,241,0.15);">
                                        {{ $inv->invoice_number }}
                                    </span>
                                </td>
                                <td>{{ $inv->package_name }}</td>
                                <td style="font-weight: 700;">TZS {{ number_format($inv->amount) }}</td>
                                <td>
                                    <span style="font-size: 0.7rem; font-weight: 800; padding: 3px 8px; border-radius: 12px; {{ $inv->payment_status === 'Paid' ? 'background: rgba(16,185,129,0.1); color: #10b981;' : ($inv->payment_status === 'Unpaid' ? 'background: rgba(239,68,68,0.1); color: #ef4444;' : 'background: rgba(100,100,100,0.1); color: #64748b;') }}">
                                        {{ $inv->payment_status }}
                                    </span>
                                </td>
                                <td style="font-size: 0.8rem; color: #64748b;">{{ date('M d, Y', strtotime($inv->due_date)) }}</td>
                                <td style="text-align: right;">
                                    <a href="/dashboard/invoice/{{ $inv->id }}" target="_blank" style="padding: 4px 8px; font-size: 0.72rem; border: 1px solid #cbd5e1; color: #475569; border-radius: 6px; font-weight: 700; background: #fff; text-decoration: none; display: inline-flex; align-items: center; gap: 3px;">
                                        <i class="bi bi-printer"></i> Print
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: #94a3b8; padding: 30px 0; font-size: 0.85rem;">No invoices or billing history found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
