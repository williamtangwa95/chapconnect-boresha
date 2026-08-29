<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Visitor Analytics PDF Report - {{ date('Y-m-d H:i:s') }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            margin: 30px;
            font-size: 13px;
            line-height: 1.5;
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
        .metrics-row {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }
        .metric-card {
            flex: 1;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            background: #ffffff;
        }
        .metric-card span {
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 700;
            color: #64748b;
            display: block;
            margin-bottom: 5px;
        }
        .metric-card strong {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
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
            <h1>Visitor Analytics Summary Report</h1>
            <p>Generated officially on {{ date('Y-m-d H:i:s') }}</p>
        </div>
        <div style="font-weight: bold; font-size: 16px; color: #6366f1;">
            ChapConnect
        </div>
    </div>

    <div class="filter-info">
        <strong>Report Parameters:</strong> &bull; Timeframe: {{ ucfirst(str_replace('_', ' ', $timeframe)) }} &bull; Location Filter: {{ $location }} &bull; User Filter: {{ $user }}
    </div>

    <div class="metrics-row">
        <div class="metric-card">
            <span>Total Page Views</span>
            <strong>{{ number_format($totalPageViews) }}</strong>
        </div>
        <div class="metric-card">
            <span>Unique Visitors</span>
            <strong>{{ number_format($uniqueVisitors) }}</strong>
        </div>
        <div class="metric-card">
            <span>Average Hits/Visitor</span>
            <strong>{{ $uniqueVisitors > 0 ? round($totalPageViews / $uniqueVisitors, 1) : 0 }}</strong>
        </div>
    </div>

    <h2>Detailed Visitor Request Logs (Last 1000 hits matching filters)</h2>
    <table>
        <thead>
            <tr>
                <th>Time</th>
                <th>IP Address</th>
                <th>Location</th>
                <th>Device &amp; Browser</th>
                <th>Request URL</th>
                <th>User Account</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                <td><strong>{{ $log->ip_address }}</strong></td>
                <td>{{ $log->location ?? 'Unknown, Unknown' }}</td>
                <td>{{ $log->device_type }} / {{ $log->browser }}</td>
                <td><code>{{ $log->url }}</code></td>
                <td>{{ $log->user ? $log->user->name : 'Guest Visitor' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; color: #94a3b8;">No visitor records found matching selected criteria.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        ChapConnect System Administrator Panel &bull; Confirmed &amp; Secured &bull; Page 1 of 1
    </div>

    <!-- Automatically open browser print/pdf interface upon rendering -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 600);
        });
    </script>
</body>
</html>
