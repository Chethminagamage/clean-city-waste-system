<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use App\Models\WasteReport;
use App\Models\Area;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds for testing environment.
     */
    public function run(): void
    {
        // Create basic areas
        $area1 = Area::create([
            'name' => 'Test District 1',
            'description' => 'Test area for unit testing',
        ]);

        $area2 = Area::create([
            'name' => 'Test District 2', 
            'description' => 'Test area for unit testing',
        ]);

        // Create test users
        $admin = User::factory()->admin()->create([
            'email' => 'admin@test.com',
            'name' => 'Test Admin',
        ]);

        $collector = User::factory()->collector()->create([
            'email' => 'collector@test.com',
            'name' => 'Test Collector',
            'area_id' => $area1->id,
        ]);

        $resident = User::factory()->create([
            'email' => 'resident@test.com',
            'name' => 'Test Resident',
            'area_id' => $area1->id,
        ]);

        // Create test admin
        Admin::factory()->create([
            'email' => 'admin@test.com',
            'name' => 'Test Admin',
        ]);

        // Create test waste reports
        WasteReport::factory()->count(5)->pending()->create([
            'resident_id' => $resident->id,
        ]);

        WasteReport::factory()->count(3)->assigned()->create([
            'resident_id' => $resident->id,
            'collector_id' => $collector->id,
        ]);

        WasteReport::factory()->count(2)->collected()->create([
            'resident_id' => $resident->id,
            'collector_id' => $collector->id,
        ]);
    }
}
