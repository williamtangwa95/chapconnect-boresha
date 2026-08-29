<!-- ==========================================
     TAB: User Activity Logs Tab
     ========================================== -->
<div id="tab-activity-logs" class="tab-content" style="display: none;">
    
    <!-- Title & Intro -->
    <div style="margin-bottom: 25px;">
        <h1 style="font-size: 1.8rem; font-weight: 800; color: #0f172a; margin-bottom: 6px; display: flex; align-items: center; gap: 10px;">
            <i class="bi bi-journal-text" style="color: #6366f1;"></i> User Activity Logs
        </h1>
        <p style="color: #64748b; margin: 0; font-size: 0.95rem;">Track system modifications, configurations, and administrative actions.</p>
    </div>

    <!-- Filters & Action Bar -->
    <div style="background: #ffffff; border-radius: 16px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color); margin-bottom: 25px;">
        <form method="GET" action="{{ route('admin.dashboard') }}" id="activity-logs-filter-form" style="margin: 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
            <input type="hidden" name="tab" value="activity-logs">

            <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 15px;">
                <!-- User dropdown -->
                <div style="display: flex; align-items: center; gap: 8px;">
                    <label for="activity_user" style="font-size: 0.85rem; font-weight: 700; color: #475569; display: flex; align-items: center; gap: 5px;">
                        <i class="bi bi-person-fill" style="color: #6366f1;"></i> User:
                    </label>
                    <select name="activity_user" id="activity_user" style="background: #f8fafc; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 8px 14px; font-size: 0.86rem; font-weight: 600; cursor: pointer; outline: none;">
                        <option value="all" {{ ($selectedActivityUser ?? 'all') == 'all' ? 'selected' : '' }}>All Users</option>
                        @foreach($allUsersWithActivity as $u)
                            <option value="{{ $u->id }}" {{ ($selectedActivityUser ?? '') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ ucfirst($u->role) }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Timeframe Dropdown -->
                <div style="display: flex; align-items: center; gap: 8px;">
                    <label for="activity_timeframe" style="font-size: 0.85rem; font-weight: 700; color: #475569; display: flex; align-items: center; gap: 5px;">
                        <i class="bi bi-calendar3" style="color: #0284c7;"></i> Timeframe:
                    </label>
                    <select name="activity_timeframe" id="activity_timeframe" onchange="toggleActivityDatePickers()" style="background: #f8fafc; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 8px 14px; font-size: 0.86rem; font-weight: 600; cursor: pointer; outline: none;">
                        <option value="all_time" {{ ($activityTimeframe ?? 'all_time') == 'all_time' ? 'selected' : '' }}>All Time</option>
                        <option value="today" {{ ($activityTimeframe ?? '') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="last_7_days" {{ ($activityTimeframe ?? '') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="last_30_days" {{ ($activityTimeframe ?? '') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="custom" {{ ($activityTimeframe ?? '') == 'custom' ? 'selected' : '' }}>Custom Date Range</option>
                    </select>
                </div>

                <!-- Date pickers -->
                <div id="activity-custom-dates" style="display: {{ ($activityTimeframe ?? '') == 'custom' ? 'flex' : 'none' }}; align-items: center; gap: 8px;">
                    <input type="date" name="activity_start_date" value="{{ $activityStart ?? '' }}" style="background: #f8fafc; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 7px 12px; font-size: 0.86rem; font-weight: 600; outline: none;">
                    <span style="font-size: 0.85rem; color: #64748b; font-weight: bold;">to</span>
                    <input type="date" name="activity_end_date" value="{{ $activityEnd ?? '' }}" style="background: #f8fafc; color: #1e293b; border: 1px solid #cbd5e1; border-radius: 10px; padding: 7px 12px; font-size: 0.86rem; font-weight: 600; outline: none;">
                </div>

                <button type="submit" style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; background: #6366f1; color: #ffffff; border: none; border-radius: 10px; font-weight: 700; font-size: 0.86rem; cursor: pointer; transition: all 0.2s ease;">
                    <i class="bi bi-funnel-fill"></i> Apply Filter
                </button>
            </div>

            <!-- Export Buttons -->
            <div style="display: flex; align-items: center; gap: 10px;">
                <a href="{{ route('admin.activity-logs.export-excel', request()->all()) }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; background: #15803d; color: #ffffff; border: none; border-radius: 10px; font-weight: 700; font-size: 0.86rem; text-decoration: none; transition: all 0.2s ease; box-shadow: 0 4px 10px rgba(21,128,61,0.2);">
                    <i class="bi bi-file-earmark-excel-fill"></i> Export to Excel
                </a>
                <a href="{{ route('admin.activity-logs.download-pdf', request()->all()) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; background: #be123c; color: #ffffff; border: none; border-radius: 10px; font-weight: 700; font-size: 0.86rem; text-decoration: none; transition: all 0.2s ease; box-shadow: 0 4px 10px rgba(190,18,60,0.2);">
                    <i class="bi bi-file-earmark-pdf-fill"></i> Download PDF
                </a>
            </div>
        </form>
    </div>

    <!-- Timeline Log Table -->
    <div style="background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color);">
        <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 1.15rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
            <i class="bi bi-clock-history" style="color: #6366f1;"></i> System Activity Timeline (Last 1000 logs)
        </h3>

        <div class="table-responsive" style="overflow-x: auto; width: 100%;">
            <table class="admin-table display nowrap" id="admin-activity-logs-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 15%;">Time</th>
                        <th style="width: 20%;">User</th>
                        <th style="width: 12%;">Action</th>
                        <th style="width: 38%;">Activity Details</th>
                        <th style="width: 15%;">Origin Info</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activityLogs as $log)
                    <tr>
                        <!-- 1. Time -->
                        <td style="font-size: 0.88rem; color: #1e293b; vertical-align: top;">
                            <strong style="color: #0f172a;">{{ $log->created_at->format('M d, H:i:s') }}</strong>
                            <div style="font-size: 0.76rem; color: #64748b; margin-top: 2px;">
                                {{ $log->created_at->diffForHumans() }}
                            </div>
                        </td>

                        <!-- 2. User Avatar & Info -->
                        <td style="vertical-align: top;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                @php
                                    $userName = $log->user ? $log->user->name : 'System/Guest';
                                    $initials = strtoupper(substr($userName, 0, 1));
                                    $role = $log->user ? $log->user->role : 'guest';
                                    $bgColor = '#6366f1';
                                    if ($role === 'admin') $bgColor = '#1e3a8a';
                                    elseif ($role === 'customer_care') $bgColor = '#f59e0b';
                                @endphp
                                <div style="width: 34px; height: 34px; border-radius: 50%; background: {{ $bgColor }}; color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem; flex-shrink: 0; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <strong style="color: #1e293b; font-size: 0.88rem; display: block; line-height: 1.2;">{{ $userName }}</strong>
                                    <span style="font-size: 0.74rem; text-transform: uppercase; font-weight: 700; color: #64748b;">{{ $role }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- 3. Action Badge -->
                        <td style="vertical-align: top;">
                            @php
                                $badgeColor = 'background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe;'; // login default
                                $icon = 'bi-box-arrow-in-right';
                                
                                if ($log->action === 'LOGOUT') {
                                    $badgeColor = 'background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;';
                                    $icon = 'bi-box-arrow-left';
                                } elseif ($log->action === 'UPDATED') {
                                    $badgeColor = 'background: #fef3c7; color: #92400e; border: 1px solid #fde68a;';
                                    $icon = 'bi-pencil-square';
                                } elseif ($log->action === 'CREATED') {
                                    $badgeColor = 'background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;';
                                    $icon = 'bi-plus-circle';
                                } elseif ($log->action === 'DELETED') {
                                    $badgeColor = 'background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;';
                                    $icon = 'bi-trash-fill';
                                }
                            @endphp
                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 8px; font-weight: 700; font-size: 0.74rem; {{ $badgeColor }}">
                                <i class="bi {{ $icon }}"></i> {{ $log->action }}
                            </span>
                        </td>

                        <!-- 4. Details with visual table diff panel -->
                        <td style="vertical-align: top; white-space: normal; word-break: break-word;">
                            <div style="font-size: 0.88rem; color: #0f172a; font-weight: 600; margin-bottom: 6px;">
                                {{ $log->description }}
                            </div>

                            @if($log->properties && isset($log->properties['diff']))
                                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px; margin-top: 8px; max-width: 580px;">
                                    <table style="width: 100%; border-collapse: collapse; font-size: 0.8rem; text-align: left;">
                                        <thead>
                                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                                <th style="color: #475569; font-weight: 700; padding-bottom: 6px; width: 30%;">FIELD</th>
                                                <th style="color: #475569; font-weight: 700; padding-bottom: 6px; width: 35%;">OLD VALUE</th>
                                                <th style="color: #475569; font-weight: 700; padding-bottom: 6px; width: 35%;">NEW VALUE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($log->properties['diff'] as $field => $values)
                                            <tr>
                                                <td style="padding: 6px 0; font-family: monospace; font-weight: 600; color: #4f46e5;">{{ $field }}</td>
                                                <td style="padding: 6px 4px;">
                                                    <span style="background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 6px; font-weight: 600;">{{ $values['old'] ?? '[empty]' }}</span>
                                                </td>
                                                <td style="padding: 6px 4px;">
                                                    <span style="background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 6px; font-weight: 600;">{{ $values['new'] ?? '[empty]' }}</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </td>

                        <!-- 5. Origin Info -->
                        <td style="font-size: 0.8rem; color: #475569; vertical-align: top;">
                            <div style="font-weight: 600; color: #0f172a; display: flex; align-items: center; gap: 4px; margin-bottom: 3px;">
                                <i class="bi bi-laptop" style="color: #6366f1;"></i> {{ $log->ip_address }}
                            </div>
                            <div style="font-size: 0.72rem; color: #94a3b8; line-height: 1.3;" title="{{ $log->user_agent }}">
                                {{ Str::limit($log->user_agent, 80) }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #94a3b8; padding: 30px;">
                            <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                            No activity records match selected filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function toggleActivityDatePickers() {
        const select = document.getElementById('activity_timeframe');
        const container = document.getElementById('activity-custom-dates');
        if (select.value === 'custom') {
            container.style.display = 'flex';
        } else {
            container.style.display = 'none';
        }
    }
</script>
