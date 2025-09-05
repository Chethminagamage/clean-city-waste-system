<?php

// Temporary debug route to test collector authentication
Route::get('/debug/collector-auth', function () {
    return response()->json([
        'collector_authenticated' => Auth::guard('collector')->check(),
        'collector_id' => Auth::guard('collector')->id(),
        'collector_user' => Auth::guard('collector')->user(),
        'web_authenticated' => Auth::guard('web')->check(),
        'web_id' => Auth::guard('web')->id(),
        'web_user' => Auth::guard('web')->user(),
        'session_data' => session()->all(),
        'guards' => [
            'web' => Auth::guard('web')->check(),
            'admin' => Auth::guard('admin')->check(),
            'collector' => Auth::guard('collector')->check(),
        ]
    ]);
});

// Test route for report details (with authentication)
Route::middleware(['auth:collector'])->get('/debug/test-report/{id}', function ($id) {
    $report = \App\Models\WasteReport::with('resident')
        ->where('collector_id', Auth::guard('collector')->id())
        ->where('id', $id)
        ->first();
        
    return response()->json([
        'authenticated' => Auth::guard('collector')->check(),
        'collector_id' => Auth::guard('collector')->id(),
        'report_found' => $report ? true : false,
        'report_data' => $report ? [
            'id' => $report->id,
            'waste_type' => $report->waste_type,
            'status' => $report->status
        ] : null
    ]);
});
