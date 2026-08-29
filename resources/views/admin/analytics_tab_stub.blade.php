<div id="tab-analytics" class="tab-content">
    <div style="margin-bottom: 25px;">
        <h2 style="margin: 0 0 6px 0; font-size: 1.6rem; font-weight: 800; color: #0f172a; border: none; padding: 0;">Visitor Analytics</h2>
        <p style="margin: 0; color: #64748b; font-size: 0.9rem;">Track visitor sessions, location demographics, and devices.</p>
    </div>

    <!-- Analytics Filter Form & Toolbar -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 18px 24px; margin-bottom: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
        <form action="{{ route('admin.dashboard') }}" method="GET" style="margin: 0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <!-- Hidden tab query parameter to ensure user stays on the analytics tab after filtering -->
            <input type="hidden" name="tab" value="analytics">
            
            <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                <!-- Location Filter -->
                <div style="display: flex; align-items: center; gap: 8px;">
                    <label for="location_filter" style="font-size: 0.84rem; font-weight: 700; color: #475569; white-space: nowrap;"><i class="bi bi-geo-alt-fill" style="color: #6366f1;"></i> Location:</label>
                    <select name="location_filter" id="location_filter" style="padding: 7px 14px; border: 1.5px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem; color: #1e293b; outline: none; background: #fff;">
                        <option value="all" {{ $selectedLocation === 'all' || !$selectedLocation ? 'selected' : '' }}>All Locations</option>
                        @foreach($allLocations as $loc)
                            <option value="{{ $loc->location }}" {{ $selectedLocation === $loc->location ? 'selected' : '' }}>{{ $loc->location }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- User Filter -->
                <div style="display: flex; align-items: center; gap: 8px;">
                    <label for="user_filter" style="font-size: 0.84rem; font-weight: 700; color: #475569; white-space: nowrap;"><i class="bi bi-person-fill" style="color: #6366f1;"></i> User:</label>
                    <select name="user_filter" id="user_filter" style="padding: 7px 14px; border: 1.5px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem; color: #1e293b; outline: none; background: #fff; max-width: 200px;">
                        <option value="all" {{ $selectedUser === 'all' || !$selectedUser ? 'selected' : '' }}>All Accounts</option>
                        @foreach($allUsersWithActivity as $usr)
                            <option value="{{ $usr->id }}" {{ $selectedUser == $usr->id ? 'selected' : '' }}>{{ $usr->name }} ({{ ucfirst($usr->role) }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Timeframe Filter -->
                <div style="display: flex; align-items: center; gap: 8px;">
                    <label for="timeframe" style="font-size: 0.84rem; font-weight: 700; color: #475569; white-space: nowrap;"><i class="bi bi-funnel-fill" style="color: #6366f1;"></i> Timeframe:</label>
                    <select name="timeframe" id="timeframe" style="padding: 7px 14px; border: 1.5px solid #cbd5e1; border-radius: 10px; font-size: 0.85rem; color: #1e293b; outline: none; background: #fff;">
                        <option value="all_time" {{ $selectedTimeframe === 'all_time' ? 'selected' : '' }}>All Time</option>
                        <option value="today" {{ $selectedTimeframe === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="last_7_days" {{ $selectedTimeframe === 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="last_30_days" {{ $selectedTimeframe === 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                    </select>
                </div>

                <!-- Apply Button -->
                <button type="submit" style="padding: 8px 18px; border-radius: 10px; background: #4f46e5; color: #ffffff; border: none; font-weight: 700; font-size: 0.85rem; cursor: pointer; box-shadow: 0 4px 10px rgba(79,70,229,0.3); display: inline-flex; align-items: center; gap: 6px;">
                    <i class="bi bi-filter"></i> Apply Filter
                </button>
            </div>

            <!-- Export Buttons -->
            <div style="display: flex; align-items: center; gap: 10px;">
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
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 25px;">
        <!-- Today's Visits -->
        <div style="background: #ffffff; border-radius: 14px; padding: 20px; border-left: 5px solid #f59e0b; box-shadow: 0 4px 15px rgba(0,0,0,0.02); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <span style="font-size: 0.86rem; color: #64748b; font-weight: 700; display: block; margin-bottom: 6px;">Today's Visits</span>
                <strong style="font-size: 1.8rem; font-weight: 800; color: #0f172a;">{{ number_format($todaysVisits) }}</strong>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(245,158,11,0.1); color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                <i class="bi bi-calendar-event"></i>
            </div>
        </div>

        <!-- Total Page Views -->
        <div style="background: #ffffff; border-radius: 14px; padding: 20px; border-left: 5px solid #3b82f6; box-shadow: 0 4px 15px rgba(0,0,0,0.02); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <span style="font-size: 0.86rem; color: #64748b; font-weight: 700; display: block; margin-bottom: 6px;">Total Page Views</span>
                <strong style="font-size: 1.8rem; font-weight: 800; color: #0f172a;">{{ number_format($totalPageViews) }}</strong>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(59,130,246,0.1); color: #3b82f6; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                <i class="bi bi-eye-fill"></i>
            </div>
        </div>

        <!-- Unique Visitors -->
        <div style="background: #ffffff; border-radius: 14px; padding: 20px; border-left: 5px solid #10b981; box-shadow: 0 4px 15px rgba(0,0,0,0.02); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <span style="font-size: 0.86rem; color: #64748b; font-weight: 700; display: block; margin-bottom: 6px;">Unique Visitors</span>
                <strong style="font-size: 1.8rem; font-weight: 800; color: #0f172a;">{{ number_format($uniqueVisitors) }}</strong>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16,185,129,0.1); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>

        <!-- Top Device -->
        <div style="background: #ffffff; border-radius: 14px; padding: 20px; border-left: 5px solid #ef4444; box-shadow: 0 4px 15px rgba(0,0,0,0.02); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <span style="font-size: 0.86rem; color: #64748b; font-weight: 700; display: block; margin-bottom: 6px;">Top Device</span>
                <strong style="font-size: 1.4rem; font-weight: 800; color: #0f172a;">{{ $topDevice }}</strong>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(239,68,68,0.1); color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                <i class="bi bi-laptop"></i>
            </div>
        </div>

        <!-- Top Country -->
        <div style="background: #ffffff; border-radius: 14px; padding: 20px; border-left: 5px solid #6366f1; box-shadow: 0 4px 15px rgba(0,0,0,0.02); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <span style="font-size: 0.86rem; color: #64748b; font-weight: 700; display: block; margin-bottom: 6px;">Top Country</span>
                <strong style="font-size: 1.3rem; font-weight: 800; color: #0f172a; white-space: nowrap;">{{ $topCountry }}</strong>
            </div>
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(99,102,241,0.1); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                <i class="bi bi-globe2"></i>
            </div>
        </div>
    </div>

    <!-- Location Demographics & Device/Browser Stats Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; align-items: start;">
        <!-- Left Panel: Top Visitor Locations -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 22px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); min-height: 380px;">
            <h3 style="margin: 0 0 18px 0; font-size: 1.05rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-geo-alt-fill" style="color: #6366f1;"></i> Top Visitor Locations
            </h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @forelse($topLocations as $loc)
                    @php
                        $percentage = $totalPageViews > 0 ? round(($loc->count / $totalPageViews) * 100, 1) : 0;
                    @endphp
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; font-size: 0.85rem; font-weight: 700; color: #1e293b;">
                            <span>{{ $loc->location ?? 'Unknown' }}</span>
                            <span style="color: #64748b;">{{ $loc->count }} hits ({{ $percentage }}%)</span>
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
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 22px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); min-height: 380px;">
            <h3 style="margin: 0 0 18px 0; font-size: 1.05rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-display" style="color: #6366f1;"></i> Device &amp; Browser Stats
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Device Column -->
                <div>
                    <h4 style="margin: 0 0 12px 0; font-size: 0.82rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Devices</h4>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @forelse($deviceStats as $dev)
                            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; font-size: 0.86rem;">
                                <span style="font-weight: 600; color: #334155; display: inline-flex; align-items: center; gap: 8px;">
                                    @if($dev->device_type === 'Desktop') <i class="bi bi-laptop" style="color: #6366f1;"></i>
                                    @elseif($dev->device_type === 'Mobile') <i class="bi bi-phone" style="color: #10b981;"></i>
                                    @else <i class="bi bi-tablet" style="color: #f59e0b;"></i> @endif
                                    {{ $dev->device_type }}
                                </span>
                                <span style="font-weight: 800; color: #0f172a;">{{ $dev->count }}</span>
                            </div>
                        @empty
                            <div style="color: #94a3b8; font-size: 0.8rem; text-align: center;">No device data.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Browser Column -->
                <div>
                    <h4 style="margin: 0 0 12px 0; font-size: 0.82rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Top Browsers</h4>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @forelse($browserStats as $br)
                            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; font-size: 0.86rem;">
                                <span style="font-weight: 600; color: #334155; display: inline-flex; align-items: center; gap: 8px;">
                                    @if($br->browser === 'Chrome') <i class="bi bi-browser-chrome" style="color: #ef4444;"></i>
                                    @elseif($br->browser === 'Safari') <i class="bi bi-browser-safari" style="color: #3b82f6;"></i>
                                    @elseif($br->browser === 'Firefox') <i class="bi bi-browser-firefox" style="color: #f97316;"></i>
                                    @elseif($br->browser === 'Edge') <i class="bi bi-browser-edge" style="color: #0284c7;"></i>
                                    @else <i class="bi bi-globe" style="color: #64748b;"></i> @endif
                                    {{ $br->browser }}
                                </span>
                                <span style="font-weight: 800; color: #0f172a;">{{ $br->count }}</span>
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
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 22px; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
        <h3 style="margin: 0 0 18px 0; font-size: 1.05rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
            <i class="bi bi-card-list" style="color: #6366f1;"></i> Visitor Request Log (Last 1000 hits)
        </h3>
        
        <div class="table-responsive">
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
                        <td style="font-family: monospace; font-size: 0.82rem; color: #0f172a;">{{ $log->url }}</td>
                        <td style="font-weight: 600; color: #4f46e5;">
                            @if($log->user)
                                <span style="display: inline-flex; align-items: center; gap: 4px;">
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
