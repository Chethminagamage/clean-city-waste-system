<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\WasteReport;
use Carbon\Carbon;

class WasteReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get collector and resident
        $collector = User::where('email', 'collector@cleancity.com')->first();
        $resident = User::where('email', 'resident@cleancity.com')->first();
        
        if (!$collector || !$resident) {
            return;
        }
        
        // Create a pending report
        WasteReport::create([
            'resident_id' => $resident->id,
            'location' => '123 Main Street',
            'latitude' => 10.123456,
            'longitude' => 20.123456,
            'waste_type' => 'Household Waste',
            'additional_details' => 'Large trash bags',
            'status' => 'pending',
            'reference_code' => 'WR-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
        ]);
        
        // Create an assigned report
        WasteReport::create([
            'resident_id' => $resident->id,
            'collector_id' => $collector->id,
            'location' => '456 Elm Street',
            'latitude' => 10.234567,
            'longitude' => 20.234567,
            'waste_type' => 'Recyclables',
            'additional_details' => 'Paper and plastic',
            'status' => 'assigned',
            'assigned_at' => now(),
            'reference_code' => 'WR-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
        ]);
        
        // Create an in_progress report
        WasteReport::create([
            'resident_id' => $resident->id,
            'collector_id' => $collector->id,
            'location' => '789 Oak Avenue',
            'latitude' => 10.345678,
            'longitude' => 20.345678,
            'waste_type' => 'Hazardous Waste',
            'additional_details' => 'Old batteries and electronics',
            'status' => 'in_progress',
            'assigned_at' => now()->subHour(),
            'reference_code' => 'WR-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
        ]);
        
        // Create a collected report
        WasteReport::create([
            'resident_id' => $resident->id,
            'collector_id' => $collector->id,
            'location' => '101 Pine Road',
            'latitude' => 10.456789,
            'longitude' => 20.456789,
            'waste_type' => 'Green Waste',
            'additional_details' => 'Garden trimmings',
            'status' => 'collected',
            'assigned_at' => now()->subDays(1),
            'collected_at' => now()->subHours(2),
            'reference_code' => 'WR-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
        ]);
    }
}
