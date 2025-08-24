@php
  $ref    = $report->reference_code ?? ('#'.$report->id);
  $status = ucfirst((string) $report->status);
  $reason = $reason ? ucfirst($reason) : 'Updated';

  // Define status color classes
  $statusClass = 'status-pending';
  if (strtolower($status) == 'assigned') {
    $statusClass = 'status-assigned';
  } elseif (strtolower($status) == 'collected') {
    $statusClass = 'status-collected';
  } elseif (strtolower($status) == 'closed') {
    $statusClass = 'status-closed';
  }
@endphp

@component('mail::message')
<div style="text-align:center; margin-bottom:30px;">
    <h1 style="font-size:24px; color:#22c55e; margin:0;">Report Update</h1>
</div>

Hello {{ $name }},

Your waste report has been updated.

<div style="background-color:#f0fdf4; border-left:4px solid #22c55e; padding:12px; margin-bottom:20px;">
    <div style="display:flex; justify-content:space-between;">
        <p style="margin:0;"><strong>Reference:</strong> {{ $ref }}</p>
        <span style="display:inline-block; padding:4px 8px; border-radius:12px; font-size:13px; font-weight:bold; background-color:#dcfce7; color:#166534;">{{ $status }}</span>
    </div>
</div>

<div style="background-color:#f0fdf4; padding:10px 15px; margin-top:20px; border-radius:5px; color:#15803d; font-weight:600;">
    Report Details
</div>

<table style="width:100%; border-collapse:collapse; margin-top:15px;">
    <tr>
        <td style="padding:8px 0;"><strong>Action:</strong></td>
        <td style="padding:8px 0;">{{ $reason }}</td>
    </tr>
    @isset($report->waste_type)
    <tr>
        <td style="padding:8px 0;"><strong>Waste Type:</strong></td>
        <td style="padding:8px 0;">{{ $report->waste_type }}</td>
    </tr>
    @endisset
    @isset($report->location)
    <tr>
        <td style="padding:8px 0;"><strong>Location:</strong></td>
        <td style="padding:8px 0;">{{ $report->location }}</td>
    </tr>
    @endisset
</table>

@component('mail::button', ['url' => $url, 'color' => 'primary'])
View Report
@endcomponent

<div style="margin-top:30px;">
    Thanks for helping keep the city clean,<br>
    <strong style="color:#22c55e;">Clean City</strong>
</div>
@endcomponent