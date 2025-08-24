<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\CollectionSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CollectionScheduleController extends Controller
{
    /**
     * Display a listing of the collection schedules.
     */
    public function index()
    {
        $schedules = CollectionSchedule::with('area')->latest()->paginate(10);
        return view('admin.schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new collection schedule.
     */
    public function create()
    {
        $areas = Area::all();
        $wasteTypes = [
            'organic' => 'Organic Waste',
            'recyclable' => 'Recyclable Waste',
            'hazardous' => 'Hazardous Waste',
            'general' => 'General Waste',
            'electronics' => 'Electronic Waste',
            'construction' => 'Construction Waste'
        ];
        return view('admin.schedules.create', compact('areas', 'wasteTypes'));
    }

    /**
     * Store a newly created collection schedule in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'waste_type' => 'required|string',
            'date' => 'required|date',
            'frequency' => 'required|string|in:weekly,bi-weekly,monthly',
            'notes' => 'nullable|string',
        ]);

        // Parse the date properly
        $validated['date'] = Carbon::parse($request->date)->format('Y-m-d');

        CollectionSchedule::create($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Collection schedule created successfully.');
    }

    /**
     * Display the specified collection schedule.
     */
    public function show(CollectionSchedule $schedule)
    {
        return view('admin.schedules.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified collection schedule.
     */
    public function edit(CollectionSchedule $schedule)
    {
        $areas = Area::all();
        $wasteTypes = [
            'organic' => 'Organic Waste',
            'recyclable' => 'Recyclable Waste',
            'hazardous' => 'Hazardous Waste',
            'general' => 'General Waste',
            'electronics' => 'Electronic Waste',
            'construction' => 'Construction Waste'
        ];
        return view('admin.schedules.edit', compact('schedule', 'areas', 'wasteTypes'));
    }

    /**
     * Update the specified collection schedule in storage.
     */
    public function update(Request $request, CollectionSchedule $schedule)
    {
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'waste_type' => 'required|string',
            'date' => 'required|date',
            'frequency' => 'required|string|in:weekly,bi-weekly,monthly',
            'notes' => 'nullable|string',
        ]);

        // Parse the date properly
        $validated['date'] = Carbon::parse($request->date)->format('Y-m-d');

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Collection schedule updated successfully.');
    }

    /**
     * Remove the specified collection schedule from storage.
     */
    public function destroy(CollectionSchedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Collection schedule deleted successfully.');
    }

    /**
     * Generate schedules based on area type.
     */
    public function generateSchedules(Request $request)
    {
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'type' => 'required|string|in:urban,suburban,rural',
        ]);
        
        $area = Area::findOrFail($validated['area_id']);
        $type = $validated['type'];
        
        // Clear existing schedules for this area
        CollectionSchedule::where('area_id', $area->id)->delete();
        
        // Start date for schedules (today)
        $startDate = Carbon::now();
        
        // Generate appropriate schedules based on area type
        switch ($type) {
            case 'urban':
                $this->generateUrbanSchedules($area, $startDate);
                break;
            case 'suburban':
                $this->generateSuburbanSchedules($area, $startDate);
                break;
            case 'rural':
                $this->generateRuralSchedules($area, $startDate);
                break;
        }
        
        return redirect()->route('admin.schedules.index')
            ->with('success', ucfirst($type) . ' schedules generated for ' . $area->name . '.');
    }
    
    /**
     * Generate urban area schedules (weekly collections).
     */
    private function generateUrbanSchedules($area, $startDate)
    {
        // Organic waste - twice weekly
        for ($i = 0; $i < 12; $i++) {
            $date = $startDate->copy()->addDays($i * 3);
            CollectionSchedule::create([
                'area_id' => $area->id,
                'waste_type' => 'organic',
                'date' => $date->format('Y-m-d'),
                'frequency' => 'weekly',
                'notes' => 'Regular organic waste collection'
            ]);
        }
        
        // Recyclable waste - weekly
        for ($i = 0; $i < 6; $i++) {
            $date = $startDate->copy()->addDays(2)->addWeeks($i);
            CollectionSchedule::create([
                'area_id' => $area->id,
                'waste_type' => 'recyclable',
                'date' => $date->format('Y-m-d'),
                'frequency' => 'weekly',
                'notes' => 'Regular recyclable waste collection'
            ]);
        }
        
        // General waste - weekly
        for ($i = 0; $i < 6; $i++) {
            $date = $startDate->copy()->addDays(5)->addWeeks($i);
            CollectionSchedule::create([
                'area_id' => $area->id,
                'waste_type' => 'general',
                'date' => $date->format('Y-m-d'),
                'frequency' => 'weekly',
                'notes' => 'Regular general waste collection'
            ]);
        }
        
        // Hazardous waste - monthly
        for ($i = 0; $i < 2; $i++) {
            $date = $startDate->copy()->addDays(7)->addMonths($i);
            CollectionSchedule::create([
                'area_id' => $area->id,
                'waste_type' => 'hazardous',
                'date' => $date->format('Y-m-d'),
                'frequency' => 'monthly',
                'notes' => 'Monthly hazardous waste collection'
            ]);
        }
    }
    
    /**
     * Generate suburban area schedules (bi-weekly collections).
     */
    private function generateSuburbanSchedules($area, $startDate)
    {
        // Organic waste - weekly
        for ($i = 0; $i < 6; $i++) {
            $date = $startDate->copy()->addDays(1)->addWeeks($i);
            CollectionSchedule::create([
                'area_id' => $area->id,
                'waste_type' => 'organic',
                'date' => $date->format('Y-m-d'),
                'frequency' => 'weekly',
                'notes' => 'Regular organic waste collection'
            ]);
        }
        
        // Recyclable waste - bi-weekly
        for ($i = 0; $i < 3; $i++) {
            $date = $startDate->copy()->addDays(3)->addWeeks($i * 2);
            CollectionSchedule::create([
                'area_id' => $area->id,
                'waste_type' => 'recyclable',
                'date' => $date->format('Y-m-d'),
                'frequency' => 'bi-weekly',
                'notes' => 'Bi-weekly recyclable waste collection'
            ]);
        }
        
        // General waste - bi-weekly
        for ($i = 0; $i < 3; $i++) {
            $date = $startDate->copy()->addDays(6)->addWeeks($i * 2);
            CollectionSchedule::create([
                'area_id' => $area->id,
                'waste_type' => 'general',
                'date' => $date->format('Y-m-d'),
                'frequency' => 'bi-weekly',
                'notes' => 'Bi-weekly general waste collection'
            ]);
        }
        
        // Hazardous waste - monthly
        for ($i = 0; $i < 2; $i++) {
            $date = $startDate->copy()->addDays(10)->addMonths($i);
            CollectionSchedule::create([
                'area_id' => $area->id,
                'waste_type' => 'hazardous',
                'date' => $date->format('Y-m-d'),
                'frequency' => 'monthly',
                'notes' => 'Monthly hazardous waste collection'
            ]);
        }
    }
    
    /**
     * Generate rural area schedules (monthly collections).
     */
    private function generateRuralSchedules($area, $startDate)
    {
        // Organic waste - bi-weekly
        for ($i = 0; $i < 3; $i++) {
            $date = $startDate->copy()->addDays(2)->addWeeks($i * 2);
            CollectionSchedule::create([
                'area_id' => $area->id,
                'waste_type' => 'organic',
                'date' => $date->format('Y-m-d'),
                'frequency' => 'bi-weekly',
                'notes' => 'Bi-weekly organic waste collection'
            ]);
        }
        
        // Recyclable waste - monthly
        for ($i = 0; $i < 2; $i++) {
            $date = $startDate->copy()->addDays(5)->addMonths($i);
            CollectionSchedule::create([
                'area_id' => $area->id,
                'waste_type' => 'recyclable',
                'date' => $date->format('Y-m-d'),
                'frequency' => 'monthly',
                'notes' => 'Monthly recyclable waste collection'
            ]);
        }
        
        // General waste - monthly
        for ($i = 0; $i < 2; $i++) {
            $date = $startDate->copy()->addDays(15)->addMonths($i);
            CollectionSchedule::create([
                'area_id' => $area->id,
                'waste_type' => 'general',
                'date' => $date->format('Y-m-d'),
                'frequency' => 'monthly',
                'notes' => 'Monthly general waste collection'
            ]);
        }
        
        // Hazardous waste - every two months
        $date = $startDate->copy()->addDays(20)->addMonths(1);
        CollectionSchedule::create([
            'area_id' => $area->id,
            'waste_type' => 'hazardous',
            'date' => $date->format('Y-m-d'),
            'frequency' => 'monthly',
            'notes' => 'Bi-monthly hazardous waste collection'
        ]);
    }
}
