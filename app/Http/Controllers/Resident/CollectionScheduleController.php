<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\CollectionSchedule;
use App\Models\CollectionOverride;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Models\Area;

class CollectionScheduleController extends Controller
{
    public function index(Request $req)
    {
        $user  = $req->user();
        $areas = Area::orderBy('name')->get();

        // 1) Which district (area) to show?
        //    Prefer ?area_id=..., otherwise use user's saved district.
        $selectedAreaId = (int)($req->query('area_id') ?? ($user?->area_id ?? 0));
        $area = $selectedAreaId ? $areas->firstWhere('id', $selectedAreaId) : null;

        // 2) Date range (defaults: today → +28 days)
        $from = $req->query('from')
            ? Carbon::parse($req->query('from'))->startOfDay()
            : now()->startOfDay();

        $to = $req->query('to')
            ? Carbon::parse($req->query('to'))->endOfDay()
            : now()->addDays(28)->endOfDay();

        if ($from->gt($to)) {
            [$from, $to] = [$to, $from];
        }

        // If no area selected or saved, ask the resident to choose a district
        if (!$selectedAreaId) {
            return view('resident.schedule.index', [
                'occurrences'     => collect(),
                'schedules'       => collect(),
                'areas'           => $areas,
                'selectedAreaId'  => null,
                'area'            => null,
                // add these two:
                'from'            => $from,
                'to'              => $to,
                'message'         => 'Select your district to view the schedule.',
            ]);
        }

        // 3) Pull dated schedules for the selected district
        $rows = CollectionSchedule::query()
            ->where('area_id', $selectedAreaId)
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->orderBy('date')
            ->orderBy('window_from')
            ->get();

        // 4) Map to the structure your blade uses
        $occurrences = $rows->map(function ($r) {
            return [
                'date'       => Carbon::parse($r->date),
                'start_time' => $r->window_from,   // e.g. "08:00"
                'end_time'   => $r->window_to,     // e.g. "10:00"
                'waste_type' => $r->waste_type,
                'notes'      => $r->notes,
                'source'     => 'dated',           // for debugging/labels if needed
            ];
        });

        return view('resident.schedule.index', [
            'occurrences'     => $occurrences,
            'schedules'       => $occurrences,
            'areas'           => $areas,
            'selectedAreaId'  => $selectedAreaId,
            'area'            => $area,
            // add these two:
            'from'            => $from,
            'to'              => $to,
            'message'         => $occurrences->isEmpty()
                ? 'No schedule available for this date range.' : null,
        ]);
    }
}
