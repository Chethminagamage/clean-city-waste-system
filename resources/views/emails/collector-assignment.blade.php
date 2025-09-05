<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Waste Collection Assignment - Clean City</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4fef7;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 128, 0, 0.1);
            border: 1px solid #d2e7d9;
        }

        h1 {
            color: #15803d;
            margin-bottom: 16px;
        }

        p {
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .btn {
            display: inline-block;
            background-color: #22c55e;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin: 20px 0;
        }

        .btn:hover {
            background-color: #16a34a;
        }

        .footer {
            margin-top: 40px;
            font-size: 13px;
            color: #777;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }

        .brand {
            font-size: 22px;
            font-weight: bold;
            color: #15803d;
            margin-bottom: 10px;
        }

        .highlight {
            color: #16a34a;
            font-weight: 600;
        }

        .report-details {
            background-color: #f0fdf4;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #22c55e;
            margin: 20px 0;
        }

        .report-details ul {
            margin: 0;
            padding-left: 0;
            list-style: none;
        }

        .report-details li {
            margin-bottom: 8px;
            padding: 4px 0;
        }

        .urgent {
            color: #dc2626;
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .detail-label {
            font-weight: bold;
            color: #16a34a;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="center">
            <div class="brand">🌿 Clean City</div>
        </div>

        <h1>🗑️ New Waste Collection Assignment</h1>

        <p>Hello <strong>{{ $collector->name }}</strong>!</p>

        <p>You have been assigned a new waste collection report. Please review the details below and take action as soon as possible.</p>

        <div class="report-details">
            <h3 style="color: #15803d; margin-top: 0;">📋 Report Details</h3>
            <ul>
                <li><span class="detail-label">Reference Code:</span> {{ $report->reference_code ?? 'Report #' . $report->id }}</li>
                <li><span class="detail-label">Waste Type:</span> {{ ucfirst($report->waste_type) }}</li>
                <li><span class="detail-label">Location:</span> {{ $report->location }}</li>
                @if($report->urgency && $report->urgency !== 'normal')
                    <li><span class="detail-label">Urgency:</span> <span class="urgent">{{ ucfirst($report->urgency) }}</span></li>
                @else
                    <li><span class="detail-label">Urgency:</span> Normal</li>
                @endif
                @if($report->resident)
                    <li><span class="detail-label">Reported By:</span> {{ $report->resident->name }}</li>
                    @if($report->resident->contact)
                        <li><span class="detail-label">Contact:</span> {{ $report->resident->contact }}</li>
                    @endif
                @endif
                <li><span class="detail-label">Reported On:</span> {{ $report->created_at->format('M d, Y \a\t h:i A') }}</li>
            </ul>
        </div>

        <div class="center">
            <a href="{{ route('collector.report.show', $report->id) }}" class="btn">
                📱 View Report Details
            </a>
        </div>

        <p>Please proceed with the collection as per your schedule. If you have any questions or concerns, contact the administration team immediately.</p>

        <p style="margin-top: 30px;">
            Best regards,<br>
            <strong>Clean City Team</strong>
        </p>

        <div class="footer">
            &copy; {{ date('Y') }} Clean City. All rights reserved.<br>
            Contact us: <span class="highlight">contact@cleancity.gmail.com</span> | +94 81 456 7890
        </div>
    </div>
</body>
</html>
