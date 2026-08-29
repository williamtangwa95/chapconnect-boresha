<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }} - ChapConnect</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --bg-light: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
            background-color: #f1f5f9;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }

        .invoice-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            max-width: 800px;
            width: 100%;
            padding: 50px;
            border: 1px solid var(--border-color);
            position: relative;
        }

        .print-btn-container {
            position: absolute;
            top: 25px;
            right: 25px;
        }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: 30px;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
            transition: all 0.2s ease-in-out;
            text-decoration: none;
        }

        .btn-print:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        /* Invoice Header */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid var(--bg-light);
            padding-bottom: 30px;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 800;
        }

        .brand-name {
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
        }

        .invoice-number {
            font-family: monospace;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Invoice Info Grid */
        .invoice-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        .info-col h3 {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin: 0 0 10px 0;
            font-weight: 700;
        }

        .info-col p {
            margin: 0 0 6px 0;
            font-size: 0.92rem;
            line-height: 1.5;
        }

        .info-col strong {
            color: var(--text-main);
            font-weight: 600;
        }

        /* Invoice Details Table */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .details-table th {
            background-color: var(--bg-light);
            text-align: left;
            padding: 12px 16px;
            font-size: 0.8rem;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--text-muted);
            border-bottom: 2px solid var(--border-color);
        }

        .details-table td {
            padding: 16px;
            font-size: 0.92rem;
            border-bottom: 1px solid var(--border-color);
        }

        .details-table tr:last-child td {
            border-bottom: none;
        }

        /* Limit Badges */
        .limit-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.76rem;
            font-weight: 700;
            background: #f1f5f9;
            color: #475569;
            padding: 3px 8px;
            border-radius: 6px;
            margin-right: 6px;
        }

        /* Summary Panel */
        .summary-panel {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 40px;
        }

        .summary-box {
            width: 300px;
            background-color: var(--bg-light);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid var(--border-color);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .summary-row:last-child {
            margin-bottom: 0;
            padding-top: 10px;
            border-top: 1px solid var(--border-color);
            font-weight: 800;
            font-size: 1.05rem;
        }

        /* Status Banner */
        .status-stamp {
            display: inline-block;
            font-weight: 800;
            font-size: 1.1rem;
            text-transform: uppercase;
            padding: 6px 16px;
            border-radius: 8px;
            border: 3px solid currentColor;
            transform: rotate(-5deg);
            margin-top: 15px;
        }

        .status-paid {
            color: #10b981;
        }

        .status-unpaid {
            color: #ef4444;
        }

        .status-partial {
            color: #f59e0b;
        }

        /* Payments History */
        .payments-section {
            border-top: 2px dashed var(--border-color);
            padding-top: 30px;
            margin-top: 30px;
        }

        .payments-section h2 {
            font-size: 1.05rem;
            font-weight: 700;
            margin: 0 0 15px 0;
            color: var(--text-main);
        }

        .payment-log-item {
            font-size: 0.85rem;
            padding: 10px 14px;
            border-radius: 8px;
            background: var(--bg-light);
            border: 1px solid var(--border-color);
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Print Media Styles */
        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
            }

            .invoice-card {
                box-shadow: none;
                border: none;
                padding: 0;
                max-width: 100%;
            }

            .print-btn-container {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="invoice-card">
        <!-- Print Trigger Action Button -->
        <div class="print-btn-container">
            <button onclick="window.print();" class="btn-print">
                <i class="bi bi-printer-fill"></i> Print Invoice
            </button>
        </div>

        <!-- Header -->
        <div class="invoice-header">
            <div class="brand">
                <div class="brand-logo">
                    <i class="bi bi-rocket-takeoff"></i>
                </div>
                <div class="brand-name">ChapConnect Portal</div>
            </div>
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <div class="invoice-number">{{ $invoice->invoice_number }}</div>
                
                <!-- Payment Status stamp -->
                <div class="status-stamp {{ $invoice->payment_status === 'Paid' ? 'status-paid' : ($invoice->payment_status === 'Unpaid' ? 'status-unpaid' : 'status-partial') }}">
                    {{ $invoice->payment_status }}
                </div>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="invoice-info-grid">
            <div class="info-col">
                <h3>Billed To (Talent)</h3>
                <p><strong>{{ $invoice->user ? $invoice->user->name : 'N/A' }}</strong></p>
                <p>{{ $invoice->user ? $invoice->user->email : 'N/A' }}</p>
                @if($invoice->user && $invoice->user->phone)
                <p>Tel: {{ $invoice->user->phone }}</p>
                @endif
                <p>Category: {{ $invoice->user ? $invoice->user->category_label : 'N/A' }}</p>
            </div>
            
            <div class="info-col" style="text-align: right;">
                <h3>Invoice Details</h3>
                <p><strong>Date Issued:</strong> {{ date('M d, Y', strtotime($invoice->created_at)) }}</p>
                <p><strong>Payment Due:</strong> {{ date('M d, Y', strtotime($invoice->due_date)) }}</p>
                <p><strong>Billing Cycle:</strong> {{ date('M d, Y', strtotime($invoice->start_date)) }} - {{ date('M d, Y', strtotime($invoice->end_date)) }}</p>
            </div>
        </div>

        <!-- Description Table -->
        <table class="details-table">
            <thead>
                <tr>
                    <th>Item Description / Subscription Package</th>
                    <th style="text-align: right; width: 150px;">Amount (TZS)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div style="font-weight: 700; font-size: 0.95rem; color: var(--text-main); margin-bottom: 6px;">
                            {{ $invoice->package_name }} Package Subscription
                        </div>
                        <div style="color: var(--text-muted); font-size: 0.84rem; line-height: 1.4; margin-bottom: 8px;">
                            Creative talent membership credentials and upload limits tier.
                        </div>
                        <!-- Snapshot limits representation -->
                        <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                            <span class="limit-badge"><i class="bi bi-image"></i> Images Limit: {{ $invoice->max_images }}</span>
                            <span class="limit-badge"><i class="bi bi-camera-video"></i> Videos Limit: {{ $invoice->max_videos }}</span>
                            <span class="limit-badge"><i class="bi bi-newspaper"></i> News Limit: {{ $invoice->max_news }}</span>
                            <span class="limit-badge"><i class="bi bi-telephone"></i> Phone Visibility: {{ $invoice->phone_visibility }}</span>
                        </div>
                    </td>
                    <td style="text-align: right; font-weight: 700; font-size: 1rem; vertical-align: top;">
                        TZS {{ number_format($invoice->amount) }}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Summary Panel -->
        <div class="summary-panel">
            <div class="summary-box">
                <div class="summary-row">
                    <span>Total Billed Amount:</span>
                    <span>TZS {{ number_format($invoice->amount) }}</span>
                </div>
                <div class="summary-row" style="color: #10b981;">
                    <span>Total Amount Paid:</span>
                    <span>TZS {{ number_format($invoice->amount_paid) }}</span>
                </div>
                <div class="summary-row" style="color: #ef4444;">
                    <span>Outstanding Balance:</span>
                    <span>TZS {{ number_format($invoice->amount - $invoice->amount_paid) }}</span>
                </div>
            </div>
        </div>

        <!-- Payments Log History Section -->
        @if($invoice->amount_paid > 0)
        <div class="payments-section">
            <h2>Logged Transaction History</h2>
            <div class="payment-log-item">
                <div>
                    <strong>Payment Recorded</strong><br>
                    <span style="color: var(--text-muted); font-size: 0.78rem;">
                        Via: {{ $invoice->payment_method ?? 'Not Specified' }} 
                        @if($invoice->payment_reference) | Ref: <code>{{ $invoice->payment_reference }}</code> @endif
                    </span>
                </div>
                <div style="text-align: right;">
                    <strong style="color: #10b981;">TZS {{ number_format($invoice->amount_paid) }}</strong><br>
                    <span style="color: var(--text-muted); font-size: 0.78rem;">
                        {{ $invoice->payment_date ? date('M d, Y', strtotime($invoice->payment_date)) : date('M d, Y', strtotime($invoice->updated_at)) }}
                    </span>
                </div>
            </div>
            @if($invoice->notes)
            <div style="font-size: 0.8rem; background: var(--bg-light); border: 1px solid var(--border-color); border-radius: 8px; padding: 10px 14px; color: var(--text-muted); margin-top: 8px; font-style: italic;">
                <strong>Administrative Notes:</strong> "{{ $invoice->notes }}"
            </div>
            @endif
        </div>
        @endif

        <!-- Footer terms -->
        <div style="border-top: 1px solid var(--border-color); margin-top: 40px; padding-top: 20px; text-align: center; color: var(--text-muted); font-size: 0.78rem;">
            Thank you for being part of ChapConnect. If you have any inquiries regarding this invoice, contact customer care.
        </div>
    </div>

</body>
</html>
