<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\CollectionSchedule;
use Carbon\Carbon;

class CollectionSchedulesTableSeeder extends Seeder
{
    public function run(): void
    {
        $wasteTypes = ['Organic', 'Recyclables', 'E-Waste', 'General'];

        foreach (Area::all() as $area) {
            // Add 3–4 schedules for each district
            foreach ($wasteTypes as $i => $type) {
                CollectionSchedule::create([
                    'area_id'    => $area->id,
                    'date'       => Carbon::now()->addDays($i),   // spread out across next few days
                    'window_from'=> sprintf('%02d:00', 8 + ($i*2)), // 08:00, 10:00, 12:00, 14:00
                    'window_to'  => sprintf('%02d:00', 10 + ($i*2)),
                    'waste_type' => $type,
                    'notes'      => $type . ' collection in ' . $area->name,
                ]);
            }
        }
    }
}