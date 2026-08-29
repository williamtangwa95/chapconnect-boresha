<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Activity Logs Report - {{ date('Y-m-d H:i:s') }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            margin: 30px;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            border-bottom: 2px solid #cbd5e1;
            padding-bottom: 15px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #0f172a;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #64748b;
            font-size: 12px;
        }
        .filter-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 25px;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th, td {
            text-align: left;
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }
        th {
            background-color: #f1f5f9;
            font-weight: 700;
            font-size: 11px;
            color: #475569;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-login { background: #dbeafe; color: #1e40af; }
        .badge-logout { background: #f1f5f9; color: #475569; }
        .badge-updated { background: #fef3c7; color: #92400e; }
        .badge-created { background: #dcfce7; color: #166534; }
        .badge-deleted { background: #fee2e2; color: #991b1b; }
        
        .diff-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-top: 5px;
            border: 1px solid #e2e8f0;
        }
        .diff-table th, .diff-table td {
            padding: 4px 6px;
            border: 1px solid #e2e8f0;
        }
        .diff-table th {
            background: #f8fafc;
        }
        .val-old { background: #fee2e2; color: #991b1b; }
        .val-new { background: #dcfce7; color: #166534; }

        .footer {
            font-size: 10px;
            color: #94a3b8;
            text-align: center;
            margin-top: 30px;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }
        @media print {
            body {
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; display: flex; justify-content: flex-end;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #6366f1; color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 10px rgba(99,102,241,0.3);">
            Print Report / Save as PDF
        </button>
    </div>

    <div class="header">
        <div>
            <h1>User Activity Logs Summary</h1>
            <p>Generated officially on {{ date('Y-m-d H:i:s') }}</p>
        </div>
        <div style="font-weight: bold; font-size: 16px; color: #6366f1;">
            ChapConnect
        </div>
    </div>

    <div class="filter-info">
        <strong>Report Parameters:</strong> &bull; User Filter: {{ $selectedActivityUser }} &bull; Timeframe: {{ ucfirst(str_replace('_', ' ', $activityTimeframe)) }} @if($activityStart && $activityEnd) ({{ $activityStart }} to {{ $activityEnd }}) @endif
    </div>

    <table>
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
            @forelse($logs as $log)
            <tr>
                <td>
                    {{ $log->created_at->format('Y-m-d H:i:s') }}<br>
                    <small style="color: #64748b;">{{ $log->created_at->diffForHumans() }}</small>
                </td>
                <td>
                    <strong>{{ $log->user ? $log->user->name : 'System/Guest' }}</strong><br>
                    <span style="font-size: 10px; color: #64748b;">{{ $log->user ? ucfirst($log->user->role) : 'Visitor' }}</span>
                </td>
                <td>
                    @php
                        $actionClass = 'badge-login';
                        if ($log->action === 'LOGOUT') $actionClass = 'badge-logout';
                        elseif ($log->action === 'UPDATED') $actionClass = 'badge-updated';
                        elseif ($log->action === 'CREATED') $actionClass = 'badge-created';
                        elseif ($log->action === 'DELETED') $actionClass = 'badge-deleted';
                    @endphp
                    <span class="badge {{ $actionClass }}">{{ $log->action }}</span>
                </td>
                <td>
                    <div><strong>{{ $log->description }}</strong></div>
                    
                    @if($log->properties && isset($log->properties['diff']))
                        <table class="diff-table">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Old Value</th>
                                    <th>New Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($log->properties['diff'] as $field => $values)
                                <tr>
                                    <td><code>{{ $field }}</code></td>
                                    <td class="val-old">{{ $values['old'] ?? '[empty]' }}</td>
                                    <td class="val-new">{{ $values['new'] ?? '[empty]' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </td>
                <td>
                    <code>{{ $log->ip_address }}</code><br>
                    <span style="font-size: 9px; color: #64748b; word-break: break-all;">{{ Str::limit($log->user_agent, 60) }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #94a3b8;">No activity logs found matching selected criteria.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        ChapConnect System Audit Logging Panel &bull; Page 1 of 1
    </div>

    <!-- Automatically open browser print interface -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 600);
        });
    </script>
</body>
</html>
