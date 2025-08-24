<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Report Update</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{margin:0;background:#f6f7fb;font-family:ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto,Arial}
    .container{max-width:640px;margin:24px auto;background:#fff;border-radius:12px;overflow:hidden;
      box-shadow:0 6px 24px rgba(0,0,0,.06)}
    .brand{font-weight:700;font-size:20px;color:#16a34a}
    .header{padding:20px 24px;border-bottom:1px solid #eef2f7;display:flex;justify-content:space-between;align-items:center}
    .content{padding:24px}
    .btn{display:inline-block;background:#16a34a;color:#fff;text-decoration:none;border-radius:8px;
      padding:10px 16px;font-weight:600}
    .panel{background:#f9fafb;border:1px solid #eef2f7;border-radius:8px;padding:14px 16px;margin:14px 0}
    .muted{color:#64748b;font-size:13px}
    .footer{padding:20px 24px;color:#94a3b8;font-size:12px;text-align:center}
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="brand">Clean City</div>
    </div>

    <div class="content">
      <h2 style="margin:0 0 8px">Report Update: {{ $report->reference_code }}</h2>
      <p class="muted" style="margin:0 0 16px">Hello {{ $user->name }}, your waste report has been updated.</p>

      <div class="panel">
        <div><strong>Status:</strong> {{ ucfirst($report->status) }}</div>
        @if(!empty($reason))
          <div><strong>Action:</strong> {{ ucfirst($reason) }}</div>
        @endif
        <div><strong>Type:</strong> {{ $report->waste_type ?? '—' }}</div>
        <div><strong>Location:</strong> {{ $report->location ?? '—' }}</div>
      </div>

      @if(optional($report->collector)->name)
        <p class="muted" style="margin:0 0 16px">
          <strong>Assigned collector:</strong> {{ $report->collector->name }}
          @if(!empty($report->collector->contact)) ({{ $report->collector->contact }}) @endif
        </p>
      @endif

      <p style="margin:20px 0">
        <a class="btn" href="{{ $url }}">View Report</a>
      </p>

      <p class="muted">Thanks for helping keep the city clean!</p>
    </div>

    <div class="footer">
      © {{ date('Y') }} Clean City • If the button doesn’t work, open this link: <br>
      <a href="{{ $url }}">{{ $url }}</a>
    </div>
  </div>
</body>
</html>