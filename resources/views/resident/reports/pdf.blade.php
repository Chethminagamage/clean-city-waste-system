<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Report {{ $report->reference_code }}</title>
  <style>
    @page { margin: 24px; }
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color:#111; font-size: 12px; }
    .h1{ font-size:20px; font-weight:700; margin:0 0 8px }
    .muted{ color:#6b7280 }
    .box{ border:1px solid #e5e7eb; border-radius:8px; padding:12px; margin-bottom:12px }
    .row{ display:flex; justify-content:space-between; align-items:flex-start }
    .chip{ display:inline-block; padding:2px 8px; border-radius:999px; font-size:11px; font-weight:600 }
    .mt8{ margin-top:8px } .mt12{ margin-top:12px } .w100{ width:100% }
    .table{ width:100%; border-collapse:collapse; }
    .table td{ padding:6px 0; vertical-align:top; }
    .label{ color:#6b7280; width:80px }
    .map{ margin-top:8px; border:1px solid #e5e7eb; border-radius:8px; }
  </style>
</head>
<body>
  <div class="row" style="margin-bottom:10px">
    <div>
      <div class="h1">Clean City — Waste Report</div>
      <div class="muted">Reference: {{ $report->reference_code }}</div>
    </div>
    <div class="muted">Generated: {{ now()->format('Y-m-d H:i') }}</div>
  </div>

  <div class="box">
    <div class="row">
      <div>
        <div class="muted">Status</div>
        @php $status = strtolower($report->status); @endphp
        <span class="chip"
          style="background:
            {{ $status==='pending'?'#fef3c7':($status==='assigned'?'#dbeafe':($status==='enroute'?'#e0e7ff':($status==='collected'?'#d1fae5':($status==='closed'?'#e5e7eb':($status==='cancelled'?'#fee2e2':'#f3f4f6'))))) }};
                 color:
            {{ $status==='pending'?'#92400e':($status==='assigned'?'#1e40af':($status==='enroute'?'#3730a3':($status==='collected'?'#065f46':($status==='closed'?'#374151':($status==='cancelled'?'#991b1b':'#374151'))))) }};">
          {{ ucfirst($report->status) }}
        </span>
      </div>
      <div class="muted">Created: {{ optional($report->created_at)->format('Y-m-d H:i') }}</div>
    </div>

    <table class="table mt8">
      <tr><td class="label">Type</td><td>{{ $report->waste_type ?? '—' }}</td></tr>
      <tr><td class="label">Location</td><td>{{ $report->location ?? '—' }}</td></tr>
      @if($report->additional_details)
        <tr><td class="label">Details</td><td>{{ $report->additional_details }}</td></tr>
      @endif
    </table>
  </div>

  <div class="box">
    <div class="muted" style="margin-bottom:6px">Assigned Collector</div>
    @if($report->collector)
      <div>{{ $report->collector->name }}</div>
      <div class="muted">{{ $report->collector->contact ?? 'No contact' }}</div>
      @if($report->assigned_at)
        <div class="muted">Assigned: {{ $report->assigned_at->format('Y-m-d H:i') }}</div>
      @endif
    @else
      <div class="muted">No collector assigned.</div>
    @endif
  </div>

  @if($staticMapUrl)
    <div class="box">
      <div class="muted" style="margin-bottom:6px">Location Map</div>
      <img src="{{ $staticMapUrl }}" alt="Map" class="w100 map">
    </div>
  @endif

  <div class="box">
    <div class="muted" style="margin-bottom:6px">Timeline</div>
    <table class="table">
      <tr><td class="label">Submitted</td><td>{{ optional($report->created_at)->format('Y-m-d H:i') }}</td></tr>
      @if($report->assigned_at) <tr><td class="label">Assigned</td><td>{{ $report->assigned_at->format('Y-m-d H:i') }}</td></tr> @endif
      @if($report->collected_at)<tr><td class="label">Collected</td><td>{{ $report->collected_at->format('Y-m-d H:i') }}</td></tr> @endif
      @if($report->closed_at)   <tr><td class="label">Closed</td><td>{{ $report->closed_at->format('Y-m-d H:i') }}</td></tr> @endif
      @if(!empty($report->cancelled_at)) <tr><td class="label">Cancelled</td><td>{{ $report->cancelled_at->format('Y-m-d H:i') }}</td></tr> @endif
    </table>
  </div>
</body>
</html>