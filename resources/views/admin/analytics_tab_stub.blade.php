<div id="tab-analytics" class="tab-content" style="box-sizing: border-box; width: 100%; max-width: 100%;">
    <style>
        /* Global box-sizing enforcement for analytics tab elements */
        #tab-analytics,
        #tab-analytics * {
            box-sizing: border-box;
        }

        /* Analytics Filter Toolbar Styles */
        .analytics-filter-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 18px 24px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
            width: 100%;
        }
        .analytics-filter-form {
            margin: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            width: 100%;
        }
        .analytics-filter-inputs {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        .analytics-filter-field {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .analytics-filter-field label {
            font-size: 0.84rem;
            font-weight: 700;
            color: #475569;
            white-space: nowrap;
            margin-bottom: 0;
        }
        .analytics-filter-field select {
            padding: 7px 14px;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            font-size: 0.85rem;
            color: #1e293b;
            outline: none;
            background: #fff;
            max-width: 200px;
            width: 100%;
        }
        .analytics-export-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* Summary Metrics Cards Grid */
        .analytics-metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
            width: 100%;
        }
        .analytics-metric-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 18px 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            width: 100%;
            overflow: hidden;
        }

        /* Layout Grid for Panels */
        .analytics-two-col-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
            align-items: start;
            width: 100%;
        }
        .analytics-panel-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            min-height: 380px;
            width: 100%;
            overflow: hidden;
        }
        .analytics-sub-two-col {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 20px;
            width: 100%;
        }

        /* DataTables Child Row & Details Fixes */
        .analytics-table-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            overflow: hidden;
            width: 100%;
        }
        .analytics-table-wrapper {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        #admin-visitor-logs-table_wrapper {
            width: 100% !important;
            overflow-x: auto;
        }
        table.dataTable > tbody > tr.child {
            background-color: #f8fafc !important;
        }
        table.dataTable > tbody > tr.child td.child {
            padding: 12px 14px !important;
            background: #f8fafc !important;
            border-top: 1px solid #e2e8f0 !important;
        }
        table.dataTable > tbody > tr.child ul.dtr-details {
            display: flex !important;
            flex-direction: column !important;
            gap: 8px !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            list-style: none !important;
            box-sizing: border-box !important;
        }
        table.dataTable > tbody > tr.child ul.dtr-details > li {
            display: flex !important;
            flex-direction: row !important;
            justify-content: space-between !important;
            align-items: center !important;
            flex-wrap: wrap !important;
            gap: 8px !important;
            padding: 10px 14px !important;
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 10px !important;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03) !important;
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        table.dataTable > tbody > tr.child span.dtr-title {
            font-weight: 700 !important;
            color: #475569 !important;
            font-size: 0.82rem !important;
            margin-right: 8px !important;
            white-space: nowrap !important;
            display: inline-block !important;
        }
        table.dataTable > tbody > tr.child span.dtr-data {
            font-size: 0.83rem !important;
            color: #0f172a !important;
            word-break: break-all !important;
            font-weight: 600 !important;
            text-align: right !important;
            flex: 1 1 auto !important;
        }

        /* Responsive Breakpoints */
        @media (max-width: 991px) {
            .analytics-filter-inputs {
                width: 100%;
            }
            .analytics-export-buttons {
                width: 100%;
                justify-content: flex-start;
            }
        }
        @media (max-width: 768px) {
            .analytics-filter-card {
                padding: 16px;
            }
            .analytics-filter-inputs {
                flex-direction: column;
                align-items: stretch;
                width: 100%;
                gap: 12px;
            }
            .analytics-filter-field {
                flex-direction: column;
                align-items: stretch;
                gap: 5px;
                width: 100%;
            }
            .analytics-filter-field select {
                max-width: 100% !important;
                width: 100% !important;
            }
            .analytics-btn-apply {
                width: 100%;
                justify-content: center;
            }
            .analytics-export-buttons {
                flex-direction: column;
                width: 100%;
                gap: 10px;
            }
            .analytics-export-buttons a {
                width: 100%;
                justify-content: center;
            }
            .analytics-two-col-grid {
                grid-template-columns: 1fr !important;
            }
            .analytics-panel-card {
                padding: 16px;
                min-height: auto;
            }
            .analytics-table-card {
                padding: 16px;
            }
        }
        @media (max-width: 576px) {
            .analytics-metrics-grid {
                grid-template-columns: 1fr !important;
                gap: 12px;
            }
            .analytics-sub-two-col {
                grid-template-columns: 1fr !important;
                gap: 16px;
            }
            table.dataTable > tbody > tr.child ul.dtr-details > li {
                flex-direction: column !important;
                align-items: flex-start !important;
            }
            table.dataTable > tbody > tr.child span.dtr-data {
                text-align: left !important;
                width: 100% !important;
            }
        }
    </style>

    <div style="margin-bottom: 25px;">
        <h2 style="margin: 0 0 6px 0; font-size: 1.6rem; font-weight: 800; color: #0f172a; border: none; padding: 0;">Visitor Analytics</h2>
        <p style="margin: 0; color: #64748b; font-size: 0.9rem;">Track visitor sessions, location demographics, and devices.</p>
    </div>

    <!-- Analytics Filter Form & Toolbar -->
    <div class="analytics-filter-card">
        <form action="{{ route('admin.dashboard') }}" method="GET" class="analytics-filter-form">
            <!-- Hidden tab query parameter to ensure user stays on the analytics tab after filtering -->
            <input type="hidden" name="tab" value="analytics">
            
            <div class="analytics-filter-inputs">
                <!-- Location Filter -->
                <div class="analytics-filter-field">
                    <label for="location_filter"><i class="bi bi-geo-alt-fill" style="color: #6366f1;"></i> Location:</label>
                    <select name="location_filter" id="location_filter">
                        <option value="all" {{ $selectedLocation === 'all' || !$selectedLocation ? 'selected' : '' }}>All Locations</option>
                        @foreach($allLocations as $loc)
                            <option value="{{ $loc->location }}" {{ $selectedLocation === $loc->location ? 'selected' : '' }}>{{ $loc->location }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- User Filter -->
                <div class="analytics-filter-field">
                    <label for="user_filter"><i class="bi bi-person-fill" style="color: #6366f1;"></i> User:</label>
                    <select name="user_filter" id="user_filter">
                        <option value="all" {{ $selectedUser === 'all' || !$selectedUser ? 'selected' : '' }}>All Accounts</option>
                        @foreach($allUsersWithActivity as $usr)
                            <option value="{{ $usr->id }}" {{ $selectedUser == $usr->id ? 'selected' : '' }}>{{ $usr->name }} ({{ ucfirst($usr->role) }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Timeframe Filter -->
                <div class="analytics-filter-field">
                    <label for="timeframe"><i class="bi bi-funnel-fill" style="color: #6366f1;"></i> Timeframe:</label>
                    <select name="timeframe" id="timeframe">
                        <option value="all_time" {{ $selectedTimeframe === 'all_time' ? 'selected' : '' }}>All Time</option>
                        <option value="today" {{ $selectedTimeframe === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="last_7_days" {{ $selectedTimeframe === 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="last_30_days" {{ $selectedTimeframe === 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                    </select>
                </div>

                <!-- Apply Button -->
                <button type="submit" class="analytics-btn-apply" style="padding: 8px 18px; border-radius: 10px; background: #4f46e5; color: #ffffff; border: none; font-weight: 700; font-size: 0.85rem; cursor: pointer; box-shadow: 0 4px 10px rgba(79,70,229,0.3); display: inline-flex; align-items: center; gap: 6px;">
                    <i class="bi bi-filter"></i> Apply Filter
                </button>
            </div>

            <!-- Export Buttons -->
            <div class="analytics-export-buttons">
                <a href="{{ route('admin.analytics.export-excel', ['timeframe' => $selectedTimeframe, 'location_filter' => $selectedLocation, 'user_filter' => $selectedUser]) }}" 
                   style="padding: 8px 18px; border-radius: 10px; background: #15803d; color: #ffffff; border: none; font-weight: 700; font-size: 0.85rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 10px rgba(21,128,61,0.25);">
                    <i class="bi bi-file-earmark-excel-fill"></i> Export to Excel
                </a>
                <a href="{{ route('admin.analytics.download-pdf', ['timeframe' => $selectedTimeframe, 'location_filter' => $selectedLocation, 'user_filter' => $selectedUser]) }}" 
                   target="_blank"
                   style="padding: 8px 18px; border-radius: 10px; background: #b91c1c; color: #ffffff; border: none; font-weight: 700; font-size: 0.85rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 10px rgba(185,28,28,0.25);">
                    <i class="bi bi-file-earmark-pdf-fill"></i> Download PDF
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Metrics Cards Grid -->
    <div class="analytics-metrics-grid">
        <!-- Today's Visits -->
        <div class="analytics-metric-card" style="border-left: 5px solid #f59e0b;">
            <div>
                <span style="font-size: 0.86rem; color: #64748b; font-weight: 700; display: block; margin-bottom: 6px;">Today's Visits</span>
                <strong style="font-size: 1.8rem; font-weight: 800; color: #0f172a;">{{ number_format($todaysVisits) }}</strong>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(245,158,11,0.1); color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                <i class="bi bi-calendar-event"></i>
            </div>
        </div>

        <!-- Total Page Views -->
        <div class="analytics-metric-card" style="border-left: 5px solid #3b82f6;">
            <div>
                <span style="font-size: 0.86rem; color: #64748b; font-weight: 700; display: block; margin-bottom: 6px;">Total Page Views</span>
                <strong style="font-size: 1.8rem; font-weight: 800; color: #0f172a;">{{ number_format($totalPageViews) }}</strong>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(59,130,246,0.1); color: #3b82f6; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                <i class="bi bi-eye-fill"></i>
            </div>
        </div>

        <!-- Unique Visitors -->
        <div class="analytics-metric-card" style="border-left: 5px solid #10b981;">
            <div>
                <span style="font-size: 0.86rem; color: #64748b; font-weight: 700; display: block; margin-bottom: 6px;">Unique Visitors</span>
                <strong style="font-size: 1.8rem; font-weight: 800; color: #0f172a;">{{ number_format($uniqueVisitors) }}</strong>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16,185,129,0.1); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>

        <!-- Top Device -->
        <div class="analytics-metric-card" style="border-left: 5px solid #ef4444;">
            <div>
                <span style="font-size: 0.86rem; color: #64748b; font-weight: 700; display: block; margin-bottom: 6px;">Top Device</span>
                <strong style="font-size: 1.4rem; font-weight: 800; color: #0f172a;">{{ $topDevice }}</strong>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(239,68,68,0.1); color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                <i class="bi bi-laptop"></i>
            </div>
        </div>

        <!-- Top Country -->
        <div class="analytics-metric-card" style="border-left: 5px solid #6366f1;">
            <div>
                <span style="font-size: 0.86rem; color: #64748b; font-weight: 700; display: block; margin-bottom: 6px;">Top Country</span>
                <strong style="font-size: 1.3rem; font-weight: 800; color: #0f172a; word-break: break-word;">{{ $topCountry }}</strong>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(99,102,241,0.1); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                <i class="bi bi-globe2"></i>
            </div>
        </div>
    </div>

    <!-- Location Demographics & Device/Browser Stats Grid -->
    <div class="analytics-two-col-grid">
        <!-- Left Panel: Top Visitor Locations -->
        <div class="analytics-panel-card">
            <h3 style="margin: 0 0 18px 0; font-size: 1.05rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-geo-alt-fill" style="color: #6366f1;"></i> Top Visitor Locations
            </h3>
            <div style="display: flex; flex-direction: column; gap: 15px; width: 100%;">
                @forelse($topLocations as $loc)
                    @php
                        $percentage = $totalPageViews > 0 ? round(($loc->count / $totalPageViews) * 100, 1) : 0;
                    @endphp
                    <div style="width: 100%;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 6px; margin-bottom: 6px; font-size: 0.85rem; font-weight: 700; color: #1e293b; width: 100%;">
                            <span style="word-break: break-word; flex: 1 1 140px; min-width: 0; padding-right: 8px;">{{ $loc->location ?? 'Unknown' }}</span>
                            <span style="color: #64748b; font-size: 0.8rem; white-space: nowrap; flex-shrink: 0; margin-left: auto;">{{ $loc->count }} hits ({{ $percentage }}%)</span>
                        </div>
                        <div style="width: 100%; height: 8px; background: #e2e8f0; border-radius: 10px; overflow: hidden;">
                            <div style="width: {{ $percentage }}%; height: 100%; background: linear-gradient(90deg, #6366f1 0%, #4f46e5 100%); border-radius: 10px;"></div>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; color: #94a3b8; padding-top: 50px;">
                        <i class="bi bi-geo-fill" style="font-size: 2.2rem; display: block; margin-bottom: 10px;"></i>
                        No location data captured.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Panel: Device & Browser Stats -->
        <div class="analytics-panel-card">
            <h3 style="margin: 0 0 18px 0; font-size: 1.05rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-display" style="color: #6366f1;"></i> Device &amp; Browser Stats
            </h3>
            
            <div class="analytics-sub-two-col">
                <!-- Device Column -->
                <div style="width: 100%;">
                    <h4 style="margin: 0 0 12px 0; font-size: 0.82rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Devices</h4>
                    <div style="display: flex; flex-direction: column; gap: 12px; width: 100%;">
                        @forelse($deviceStats as $dev)
                            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; font-size: 0.86rem; gap: 10px; width: 100%;">
                                <span style="font-weight: 600; color: #334155; display: inline-flex; align-items: center; gap: 8px; word-break: break-word; flex: 1; min-width: 0;">
                                    @if($dev->device_type === 'Desktop') <i class="bi bi-laptop" style="color: #6366f1;"></i>
                                    @elseif($dev->device_type === 'Mobile') <i class="bi bi-phone" style="color: #10b981;"></i>
                                    @else <i class="bi bi-tablet" style="color: #f59e0b;"></i> @endif
                                    {{ $dev->device_type }}
                                </span>
                                <span style="font-weight: 800; color: #0f172a; flex-shrink: 0;">{{ $dev->count }}</span>
                            </div>
                        @empty
                            <div style="color: #94a3b8; font-size: 0.8rem; text-align: center;">No device data.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Browser Column -->
                <div style="width: 100%;">
                    <h4 style="margin: 0 0 12px 0; font-size: 0.82rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Top Browsers</h4>
                    <div style="display: flex; flex-direction: column; gap: 12px; width: 100%;">
                        @forelse($browserStats as $br)
                            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; font-size: 0.86rem; gap: 10px; width: 100%;">
                                <span style="font-weight: 600; color: #334155; display: inline-flex; align-items: center; gap: 8px; word-break: break-word; flex: 1; min-width: 0;">
                                    @if($br->browser === 'Chrome') <i class="bi bi-browser-chrome" style="color: #ef4444;"></i>
                                    @elseif($br->browser === 'Safari') <i class="bi bi-browser-safari" style="color: #3b82f6;"></i>
                                    @elseif($br->browser === 'Firefox') <i class="bi bi-browser-firefox" style="color: #f97316;"></i>
                                    @elseif($br->browser === 'Edge') <i class="bi bi-browser-edge" style="color: #0284c7;"></i>
                                    @else <i class="bi bi-globe" style="color: #64748b;"></i> @endif
                                    {{ $br->browser }}
                                </span>
                                <span style="font-weight: 800; color: #0f172a; flex-shrink: 0;">{{ $br->count }}</span>
                            </div>
                        @empty
                            <div style="color: #94a3b8; font-size: 0.8rem; text-align: center;">No browser data.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Request Log table -->
    <div class="analytics-table-card">
        <h3 style="margin: 0 0 18px 0; font-size: 1.05rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
            <i class="bi bi-card-list" style="color: #6366f1;"></i> Visitor Request Log (Last 1000 hits)
        </h3>
        
        <div class="analytics-table-wrapper">
            <table class="admin-table display nowrap" id="admin-visitor-logs-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>IP Address</th>
                        <th>Location</th>
                        <th>Device / Browser</th>
                        <th>Request</th>
                        <th>User Account</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requestLogs as $log)
                    <tr>
                        <td style="font-size: 0.82rem; color: #475569; white-space: nowrap;">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        <td style="font-weight: 700; color: #1e293b;">{{ $log->ip_address }}</td>
                        <td style="color: #334155;">{{ $log->location ?? 'Unknown' }}</td>
                        <td style="font-size: 0.82rem; color: #475569;">{{ $log->device_type }} / {{ $log->browser }}</td>
                        <td style="font-family: monospace; font-size: 0.82rem; color: #0f172a; word-break: break-all; max-width: 250px;">{{ $log->url }}</td>
                        <td style="font-weight: 600; color: #4f46e5;">
                            @if($log->user)
                                <span style="display: inline-flex; align-items: center; gap: 4px; word-break: break-word;">
                                    <i class="bi bi-person-fill"></i> {{ $log->user->name }}
                                </span>
                            @else
                                <span style="color: #64748b;">Guest Visitor</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
