<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clean City - Service Report Invoice</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #2d3748;
            margin: 0;
            padding: 0;
        }
        
        /* Invoice Header */
        .invoice-header {
            background: linear-gradient(135deg, #16a085 0%, #27ae60 100%);
            color: white;
            padding: 25px 30px;
            margin-bottom: 25px;
            border-radius: 8px;
        }
        
        .header-row {
            width: 100%;
            display: table;
        }
        
        .header-left {
            display: table-cell;
            vertical-align: top;
            width: 60%;
        }
        
        .header-right {
            display: table-cell;
            vertical-align: top;
            width: 40%;
            text-align: right;
        }
        
        .company-name {
            font-size: 24pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .company-tagline {
            font-size: 12pt;
            opacity: 0.9;
            margin-bottom: 10px;
        }
        
        .company-contact {
            font-size: 10pt;
            opacity: 0.8;
        }
        
        .invoice-title {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .invoice-number {
            font-size: 14pt;
            font-weight: bold;
            background: rgba(255,255,255,0.2);
            padding: 8px 12px;
            border-radius: 6px;
            margin-bottom: 5px;
        }
        
        .invoice-date {
            font-size: 10pt;
            opacity: 0.8;
        }
        
        /* Client Information */
        .client-section {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .client-row {
            width: 100%;
            display: table;
        }
        
        .client-left {
            display: table-cell;
            vertical-align: top;
            width: 50%;
            padding-right: 20px;
        }
        
        .client-right {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }
        
        .client-title {
            font-size: 12pt;
            font-weight: bold;
            color: #16a085;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .client-info {
            font-size: 11pt;
            line-height: 1.6;
        }
        
        /* Service Details Table */
        .service-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .service-table th {
            background: linear-gradient(135deg, #16a085 0%, #27ae60 100%);
            color: white;
            padding: 15px;
            font-weight: bold;
            font-size: 11pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .service-table td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 11pt;
        }
        
        .service-table tr:last-child td {
            border-bottom: none;
        }
        
        .service-table tr:nth-child(even) {
            background: #f8fafc;
        }
        
        .field-label {
            font-weight: bold;
            color: #4a5568;
            width: 35%;
        }
        
        .field-value {
            color: #2d3748;
        }
        
        /* Status Badge */
        .status-container {
            text-align: center;
            margin: 20px 0;
        }
        
        .status-badge {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 12pt;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Timeline Section */
        .timeline-section {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .timeline-title {
            font-size: 14pt;
            font-weight: bold;
            color: #16a085;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .timeline-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .timeline-table th {
            background: #16a085;
            color: white;
            padding: 12px 15px;
            font-weight: bold;
            font-size: 10pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .timeline-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10pt;
        }
        
        .timeline-table tr:nth-child(even) {
            background: #f8fafc;
        }
        
        /* Footer */
        .invoice-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #16a085;
        }
        
        .footer-row {
            width: 100%;
            display: table;
        }
        
        .footer-left {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }
        
        .footer-right {
            display: table-cell;
            vertical-align: top;
            width: 50%;
            text-align: right;
        }
        
        .footer-section {
            font-size: 10pt;
            color: #4a5568;
            line-height: 1.6;
        }
        
        .footer-title {
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 5px;
        }
        
        .thank-you {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background: linear-gradient(135deg, #e6fffa 0%, #f0fff4 100%);
            border-radius: 8px;
            color: #16a085;
            font-weight: bold;
            font-size: 12pt;
        }
        
        .coordinates {
            font-family: 'Courier New', monospace;
            background: #edf2f7;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 9pt;
        }
    </style>
</head>
<body>
    <!-- System Header with Name & Motto -->
    <div style="text-align: center; margin-bottom: 20px; padding: 15px 0;">
        <div style="font-size: 32pt; font-weight: bold; color: #16a085; margin-bottom: 8px; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            CLEAN CITY
        </div>
        <div style="font-size: 14pt; color: #2d3748; font-weight: 600; letter-spacing: 2px; margin-bottom: 5px;">
            WASTE MANAGEMENT SYSTEM
        </div>
        <div style="font-size: 11pt; color: #16a085; font-style: italic; font-weight: 500;">
            "Keeping Our Community Clean, Green & Sustainable"
        </div>
        <div style="width: 100px; height: 3px; background: linear-gradient(90deg, #16a085 0%, #27ae60 100%); margin: 15px auto; border-radius: 2px;"></div>
    </div>

    <!-- Invoice Header -->
    <div class="invoice-header">
        <div class="header-row">
            <div class="header-left">
                <div class="company-name">Service Report Invoice</div>
                <div class="company-tagline">Professional Waste Collection Service</div>
                <div class="company-contact">
                    📧 contact@cleancity@gmail.com<br>
                    🌐 www.cleancity.com<br>
                    📍 Serving Sri Lanka with Excellence
                </div>
            </div>
            <div class="header-right">
                <div class="invoice-title">REPORT INVOICE</div>
                <div class="invoice-number"># {{ $report->reference_code }}</div>
                <div class="invoice-date">{{ now()->format('F j, Y') }}</div>
                <div style="font-size: 9pt; margin-top: 5px; opacity: 0.8;">
                    Generated at {{ now()->format('g:i A') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Client Information -->
    <div class="client-section">
        <div class="client-row">
            <div class="client-left">
                <div class="client-title">Service To:</div>
                <div class="client-info">
                    <strong>{{ $report->resident->name ?? 'Unknown Resident' }}</strong><br>
                    Email: {{ $report->resident->email ?? 'Not Available' }}<br>
                    Location: {{ $report->location ?? 'Not Specified' }}
                    @if($report->latitude && $report->longitude)
                    <br>GPS: <span class="coordinates">{{ number_format($report->latitude, 6) }}, {{ number_format($report->longitude, 6) }}</span>
                    @endif
                </div>
            </div>
            <div class="client-right">
                <div class="client-title">Report Details:</div>
                <div class="client-info">
                    <strong>Report ID:</strong> {{ $report->id }}<br>
                    <strong>Date Submitted:</strong> {{ optional($report->created_at)->format('M j, Y') }}<br>
                    <strong>Time:</strong> {{ optional($report->created_at)->format('g:i A') }}<br>
                    <strong>Service Type:</strong> Waste Collection
                </div>
            </div>
        </div>
    </div>

    <!-- Status Display -->
    <div class="status-container">
        @php 
            $status = strtolower($report->status);
            $statusColors = [
                'pending' => ['bg' => '#fff3cd', 'color' => '#856404'],
                'assigned' => ['bg' => '#cce5ff', 'color' => '#004085'],
                'enroute' => ['bg' => '#e2e3ff', 'color' => '#383d41'],
                'collected' => ['bg' => '#d4edda', 'color' => '#155724'],
                'closed' => ['bg' => '#e2e3e5', 'color' => '#383d41'],
                'cancelled' => ['bg' => '#f8d7da', 'color' => '#721c24']
            ];
            $colors = $statusColors[$status] ?? ['bg' => '#f8f9fa', 'color' => '#495057'];
        @endphp
        <span class="status-badge" style="background: {{ $colors['bg'] }}; color: {{ $colors['color'] }};">
            Current Status: {{ ucfirst($report->status) }}
        </span>
    </div>

    <!-- Service Details -->
    <table class="service-table">
        <thead>
            <tr>
                <th colspan="2">Service Information</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="field-label">Waste Type</td>
                <td class="field-value">{{ $report->waste_type ?? 'General Waste' }}</td>
            </tr>
            <tr>
                <td class="field-label">Collection Location</td>
                <td class="field-value">{{ $report->location ?? 'Not Specified' }}</td>
            </tr>
            <tr>
                <td class="field-label">Report Date</td>
                <td class="field-value">{{ optional($report->report_date)->format('F j, Y') ?? optional($report->created_at)->format('F j, Y') }}</td>
            </tr>
            @if($report->additional_details)
            <tr>
                <td class="field-label">Special Instructions</td>
                <td class="field-value">{{ $report->additional_details }}</td>
            </tr>
            @endif
            @if($report->collector)
            <tr>
                <td class="field-label">Assigned Collector</td>
                <td class="field-value">
                    {{ $report->collector->name }}
                    @if($report->collector->contact)
                    <br><small>Contact: {{ $report->collector->contact }}</small>
                    @endif
                </td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- Service Timeline -->
    <div class="timeline-section">
        <div class="timeline-title">Service Progress Timeline</div>
        <table class="timeline-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Service Stage</th>
                    <th style="width: 35%;">Date & Time</th>
                    <th style="width: 25%;">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Report Submitted</strong></td>
                    <td>{{ optional($report->created_at)->format('M j, Y g:i A') }}</td>
                    <td style="color: #16a085; font-weight: bold;">✓ Completed</td>
                </tr>
                @if($report->assigned_at)
                <tr>
                    <td><strong>Collector Assigned</strong></td>
                    <td>{{ $report->assigned_at->format('M j, Y g:i A') }}</td>
                    <td style="color: #16a085; font-weight: bold;">✓ Completed</td>
                </tr>
                @else
                <tr>
                    <td><strong>Collector Assignment</strong></td>
                    <td style="color: #718096;">Pending</td>
                    <td style="color: #e53e3e; font-weight: bold;">⏳ Pending</td>
                </tr>
                @endif
                
                @if($report->collected_at)
                <tr>
                    <td><strong>Waste Collection</strong></td>
                    <td>{{ $report->collected_at->format('M j, Y g:i A') }}</td>
                    <td style="color: #16a085; font-weight: bold;">✓ Completed</td>
                </tr>
                @elseif($report->assigned_at)
                <tr>
                    <td><strong>Waste Collection</strong></td>
                    <td style="color: #718096;">In Progress</td>
                    <td style="color: #d69e2e; font-weight: bold;">🚛 In Progress</td>
                </tr>
                @endif
                
                @if($report->closed_at)
                <tr>
                    <td><strong>Service Completed</strong></td>
                    <td>{{ $report->closed_at->format('M j, Y g:i A') }}</td>
                    <td style="color: #16a085; font-weight: bold;">✓ Completed</td>
                </tr>
                @endif
                
                @if(!empty($report->cancelled_at))
                <tr>
                    <td><strong>Service Cancelled</strong></td>
                    <td>{{ $report->cancelled_at->format('M j, Y g:i A') }}</td>
                    <td style="color: #e53e3e; font-weight: bold;">✗ Cancelled</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Customer Feedback (if available) -->
    @if($report->feedback)
    <table class="service-table" style="margin-bottom: 25px;">
        <thead>
            <tr>
                <th colspan="2" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);">Customer Feedback</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="field-label">Service Rating</td>
                <td class="field-value">
                    @for($i = 1; $i <= 5; $i++)
                        {{ $i <= ($report->feedback->rating ?? 0) ? '★' : '☆' }}
                    @endfor
                    ({{ $report->feedback->rating ?? 0 }}/5 Stars)
                </td>
            </tr>
            @if($report->feedback->comment)
            <tr>
                <td class="field-label">Comments</td>
                <td class="field-value" style="font-style: italic;">"{{ $report->feedback->comment }}"</td>
            </tr>
            @endif
            <tr>
                <td class="field-label">Feedback Date</td>
                <td class="field-value">{{ optional($report->feedback->created_at)->format('F j, Y g:i A') }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Thank You Message -->
    <div class="thank-you">
        Thank you for choosing Clean City Waste Management Services!<br>
        <small style="font-size: 10pt; opacity: 0.8;">Your trust in our services helps keep our community clean and green.</small>
    </div>

    <!-- Invoice Footer -->
    <div class="invoice-footer">
        <div class="footer-row">
            <div class="footer-left">
                <div class="footer-section">
                    <div class="footer-title">Clean City Waste Management</div>
                    Professional Waste Collection Services<br>
                    Email: contact@cleancity@gmail.com<br>
                    Committed to Environmental Excellence
                </div>
            </div>
            <div class="footer-right">
                <div class="footer-section">
                    <div class="footer-title">Document Information</div>
                    Generated: {{ now()->format('F j, Y \a\t g:i A') }}<br>
                    Report Reference: {{ $report->reference_code }}<br>
                    System ID: {{ $report->id }}
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 15px; padding-top: 10px; border-top: 1px solid #e2e8f0; font-size: 9pt; color: #718096;">
            This is an official service report generated by Clean City Waste Management System.<br>
            For any inquiries regarding this report, please contact us at contact@cleancity@gmail.com with reference number {{ $report->reference_code }}.
        </div>
    </div>
</body>
</html>