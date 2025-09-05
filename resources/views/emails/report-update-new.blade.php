<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Update - Clean City</title>
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

        .status-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #bbf7d0;
            margin: 20px 0;
            position: relative;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-assigned {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-collected {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-closed {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-pending {
            background-color: #fde68a;
            color: #92400e;
        }

        .report-details {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #22c55e;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: bold;
            color: #374151;
        }

        .detail-value {
            color: #6b7280;
        }

        .center {
            text-align: center;
        }

        .icon {
            width: 40px;
            height: 40px;
            background-color: #22c55e;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }

        .urgent {
            color: #dc2626;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="center">
            <div class="brand">🌿 Clean City</div>
            <div class="icon">
                📋
            </div>
        </div>

        <h1 class="center">Report Update</h1>

        <p>Hello <strong>{{ $user->name }}</strong>,</p>

        <p>Your waste report has been updated. Here are the latest details:</p>

        <div class="status-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="margin: 0; color: #15803d;">{{ $report->reference_code ?? 'Report #' . $report->id }}</h3>
                @php
                    $status = ucfirst((string) $report->status);
                    $statusClass = 'status-pending';
                    if (strtolower($status) == 'assigned') {
                        $statusClass = 'status-assigned';
                    } elseif (strtolower($status) == 'collected') {
                        $statusClass = 'status-collected';
                    } elseif (strtolower($status) == 'closed') {
                        $statusClass = 'status-closed';
                    }
                @endphp
                <span class="status-badge {{ $statusClass }}">{{ $status }}</span>
            </div>
            
            @if($reason)
                <p style="margin: 0; font-size: 16px; color: #059669;">
                    <strong>Action:</strong> {{ ucfirst($reason) }}
                </p>
            @endif
        </div>

        <div class="report-details">
            <h3 style="color: #15803d; margin-top: 0; margin-bottom: 15px;">📍 Report Details</h3>
            
            @if($report->waste_type)
            <div class="detail-row">
                <span class="detail-label">Waste Type:</span>
                <span class="detail-value">{{ ucfirst($report->waste_type) }}</span>
            </div>
            @endif
            
            @if($report->location)
            <div class="detail-row">
                <span class="detail-label">Location:</span>
                <span class="detail-value">{{ $report->location }}</span>
            </div>
            @endif
            
            @if($report->urgency && $report->urgency !== 'normal')
            <div class="detail-row">
                <span class="detail-label">Urgency:</span>
                <span class="detail-value urgent">{{ ucfirst($report->urgency) }}</span>
            </div>
            @endif
            
            @if($report->assigned_collector)
            <div class="detail-row">
                <span class="detail-label">Assigned Collector:</span>
                <span class="detail-value">{{ $report->assigned_collector->name }}</span>
            </div>
            @endif
            
            <div class="detail-row">
                <span class="detail-label">Reported On:</span>
                <span class="detail-value">{{ $report->created_at->format('M d, Y \a\t h:i A') }}</span>
            </div>
            
            @if($report->updated_at)
            <div class="detail-row">
                <span class="detail-label">Last Updated:</span>
                <span class="detail-value">{{ $report->updated_at->format('M d, Y \a\t h:i A') }}</span>
            </div>
            @endif
        </div>

        <div class="center">
            <a href="{{ route('resident.reports.show', $report->id) }}" class="btn">
                👁️ View Full Report
            </a>
        </div>

        @if($report->status === 'collected')
            <p style="background-color: #d1fae5; padding: 15px; border-radius: 8px; border-left: 4px solid #10b981;">
                🎉 <strong>Great news!</strong> Your waste has been successfully collected. Thank you for contributing to a cleaner city!
            </p>
        @elseif($report->status === 'assigned')
            <p style="background-color: #fef3c7; padding: 15px; border-radius: 8px; border-left: 4px solid #f59e0b;">
                ⏰ Your report has been assigned to a collector. Collection will begin soon.
            </p>
        @endif

        <p style="margin-top: 30px;">
            Thanks for helping keep the city clean,<br>
            <strong class="highlight">Clean City Team</strong>
        </p>

        <div class="footer">
            &copy; {{ date('Y') }} Clean City. All rights reserved.<br>
            Contact us: <span class="highlight">contact@cleancity.gmail.com</span> | +94 81 456 7890
        </div>
    </div>
</body>
</html>
