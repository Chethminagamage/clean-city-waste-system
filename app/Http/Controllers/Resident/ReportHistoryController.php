<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\WasteReport;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\ReportStatusUpdated;

class ReportHistoryController extends Controller
{
    public function index() { return view('resident.reports.index'); }

    public function show(Request $req, $id)
    {
        $report = WasteReport::with([
            'collector' => function ($q) {
                // must include 'id' when selecting subset for relations
                $q->select('id','name','contact','latitude','longitude');
            },
        ])
        ->where('resident_id', $req->user()->id)
        ->findOrFail($id);

        return view('resident.reports.show', compact('report'));
    }

    // SMART FILTERS + SEARCH + Overdue badge derivation
    public function data(Request $req)
    {
        $residentId = $req->user()->id;

        $q      = $req->string('q')->toString();
        $status = $req->string('status')->toString();
        $type   = $req->string('type')->toString();
        $from   = $req->string('from')->toString();
        $to     = $req->string('to')->toString();

        $rows = WasteReport::query()
            ->with('feedback')
            ->where('resident_id', $residentId)
            ->when($status, fn($qq)=>$qq->where('status',$status))
            ->when($type,   fn($qq)=>$qq->where('waste_type',$type))
            ->when($from,   fn($qq)=>$qq->whereDate('created_at','>=',$from))
            ->when($to,     fn($qq)=>$qq->whereDate('created_at','<=',$to))
            ->when($q, function($qq) use ($q){
                $qq->where(function($w) use ($q){
                    $w->where('reference_code','like',"%$q%")
                      ->orWhere('description','like',"%$q%")
                      ->orWhere('location','like',"%$q%")
                      ->orWhere('waste_type','like',"%$q%");
                });
            })
            ->latest()
            ->paginate(10);

        // derived fields
        $rows->getCollection()->transform(function ($r) {
            $slaHours = (int) config('clean_city.sla_hours', 48);
            $r->sla_deadline = $r->assigned_at ? $r->assigned_at->copy()->addHours($slaHours) : null;
            $r->is_overdue   = $r->sla_deadline ? now()->greaterThan($r->sla_deadline) && $r->status !== 'closed' : false;
            
            // Check if report has feedback
            $r->has_feedback = $r->hasFeedback();
            return $r;
        });

        return response()->json($rows);
    }

    // ACTIONS
    public function duplicate(Request $req, WasteReport $report)
    {
        abort_unless($report->resident_id === $req->user()->id, 403);

        $copy = $report->replicate([
            'status','assigned_at','eta_at','collected_at','closed_at','qr_token',
            'verified_lat','verified_lng','geo_verified'
        ]);
        $copy->status = 'pending';
        $copy->created_at = now();
        $copy->updated_at = now();
        $copy->reference_code = null; // if you auto-generate on creating()
        $copy->save();

        return response()->json(['ok'=>true,'id'=>$copy->id]);
    }

    public function cancel(Request $req, WasteReport $report)
    {
        // Must own the report
        if ($report->resident_id !== $req->user()->id) {
            abort(403);
        }

        // Only allow when pending
        if (strtolower($report->status) !== 'pending') {
            return back()->with('error', 'Only pending reports can be cancelled.');
        }

        // Update status (use whatever value you prefer: "cancelled" or "closed")
        $report->update([
            'status'       => 'cancelled',
            'cancelled_at' => now(),
        ]);
        $report->resident->notify(new ReportStatusUpdated($report, 'cancelled'));

        return back()->with('success', 'Report cancelled.');
}

    // EXPORT
    public function exportCsv(Request $req): StreamedResponse
    {
        $residentId = $req->user()->id;
        $rows = WasteReport::where('resident_id', $residentId)
            ->orderByDesc('created_at')
            ->get(['reference_code','created_at','location','waste_type','status']);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="my_reports.csv"',
        ];

        return response()->stream(function () use ($rows) {
            $out = fopen('php://output','w');
            fputcsv($out, ['Reference','Date','Location','Type','Status']);
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->reference_code,
                    optional($r->created_at)->format('Y-m-d H:i'),
                    $r->location,
                    $r->waste_type,
                    ucfirst($r->status),
                ]);
            }
            fclose($out);
        }, 200, $headers);
    }

    public function pdf(Request $req, WasteReport $report)
    {
        // Ownership check
        abort_if($report->resident_id !== $req->user()->id, 403);

        // Optional: eager-load collector
        $report->load(['collector:id,name,contact']);

        // Optional static map image (Google Static Maps) if you have lat/lng
        $lat = $report->verified_lat ?? $report->latitude;
        $lng = $report->verified_lng ?? $report->longitude;
        $staticMapUrl = null;
        if ($lat && $lng && config('services.google.maps_key')) {
            $staticMapUrl = sprintf(
                'https://maps.googleapis.com/maps/api/staticmap?center=%s,%s&zoom=15&size=800x380&scale=2&maptype=roadmap&markers=color:green|%s,%s&key=%s',
                $lat, $lng, $lat, $lng, config('services.google.maps_key')
            );
        }

        $pdf = Pdf::loadView('resident.reports.pdf', [
            'report' => $report,
            'staticMapUrl' => $staticMapUrl,
        ])->setPaper('a4');

        // Download with a nice filename
        $name = 'CleanCity_Report_'.$report->reference_code.'.pdf';
        return $pdf->download($name);
    }
}