<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Area;
use App\Models\CollectionSchedule;
use Carbon\Carbon;
use Database\Seeders\WasteReportSeeder;
use Database\Seeders\CollectionScheduleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- Default Areas ---
        $area1 = Area::create([
            'name' => 'District 1',
            'description' => 'Downtown area',
        ]);
        
        $area2 = Area::create([
            'name' => 'District 2',
            'description' => 'Residential area',
        ]);
        
        // --- Default Users ---
        User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@cleancity.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Collector User',
            'email'    => 'collector@cleancity.com',
            'password' => Hash::make('password'),
            'role'     => 'collector',
            'area_id'  => $area1->id,
        ]);

        User::create([
            'name'     => 'Resident User',
            'email'    => 'resident@cleancity.com',
            'password' => Hash::make('password'),
            'role'     => 'resident',
            'area_id'  => $area1->id,
        ]);

        // --- Districts (25 Areas) ---
        $districts = [
            'Colombo', 'Gampaha', 'Kalutara',
            'Kandy', 'Matale', 'Nuwara Eliya',
            'Galle', 'Matara', 'Hambantota',
            'Jaffna', 'Kilinochchi', 'Mannar', 'Vavuniya', 'Mullaitivu',
            'Batticaloa', 'Ampara', 'Trincomalee',
            'Kurunegala', 'Puttalam',
            'Anuradhapura', 'Polonnaruwa',
            'Badulla', 'Monaragala',
            'Ratnapura', 'Kegalle'
        ];

        foreach ($districts as $name) {
            Area::create([
                'name' => $name,
                'code' => strtoupper(substr($name, 0, 3)), // "COL", "GAM", etc.
            ]);
        }

        // --- Collection Schedules (for each district) ---
        // Run the specialized collection schedule seeder
        $this->call(CollectionScheduleSeeder::class);
        
        // --- Seed waste reports ---
        $this->call(WasteReportSeeder::class);
    }
}