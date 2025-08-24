<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\CollectionSchedule;
use Carbon\Carbon;

class CollectionScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing schedules
        CollectionSchedule::truncate();
        
        // Define district types
        $urbanDistricts = ['Colombo', 'Gampaha', 'Kandy', 'Galle', 'Jaffna'];
        $suburbanDistricts = ['Kalutara', 'Kurunegala', 'Ratnapura', 'Matara', 'Kegalle', 'Badulla', 'Matale'];
        $ruralDistricts = ['Ampara', 'Anuradhapura', 'Batticaloa', 'Hambantota', 'Kilinochchi', 'Mannar', 
                          'Monaragala', 'Mullaitivu', 'Nuwara Eliya', 'Polonnaruwa', 'Puttalam', 'Trincomalee', 'Vavuniya'];
        
        // Get all areas
        $areas = Area::all();
        
        foreach ($areas as $area) {
            $districtName = $area->name;
            $startDate = Carbon::now()->startOfWeek();
            
            // Set district type
            $districtType = 'rural'; // default
            if (in_array($districtName, $urbanDistricts)) {
                $districtType = 'urban';
            } elseif (in_array($districtName, $suburbanDistricts)) {
                $districtType = 'suburban';
            }
            
            // Create schedules based on district type
            $this->createSchedulesByDistrictType($area, $districtType, $startDate);
        }
    }
    
    /**
     * Create collection schedules based on district type
     */
    private function createSchedulesByDistrictType($area, $districtType, $startDate)
    {
        switch ($districtType) {
            case 'urban':
                $this->createUrbanSchedules($area, $startDate);
                break;
            case 'suburban':
                $this->createSuburbanSchedules($area, $startDate);
                break;
            case 'rural':
                $this->createRuralSchedules($area, $startDate);
                break;
        }
    }
    
    /**
     * Create schedules for urban districts (frequent collections)
     */
    private function createUrbanSchedules($area, $startDate)
    {
        // Urban areas have more frequent collections for all waste types
        
        // Organic waste - 3 times per week (Mon, Wed, Fri)
        $this->createScheduleForWeekday($area, $startDate, 1, 'Organic', '07:00', '09:00', 
            'Food waste, garden trimmings, compostable items. Use green bins.');
        
        $this->createScheduleForWeekday($area, $startDate, 3, 'Organic', '07:00', '09:00', 
            'Food waste, garden trimmings, compostable items. Use green bins.');
            
        $this->createScheduleForWeekday($area, $startDate, 5, 'Organic', '07:00', '09:00', 
            'Food waste, garden trimmings, compostable items. Use green bins.');
        
        // Recyclables - twice per week (Tue, Thu)
        $this->createScheduleForWeekday($area, $startDate, 2, 'Recyclables', '10:00', '12:00', 
            'Paper, cardboard, clean plastics, metal cans. Place in blue bins.');
            
        $this->createScheduleForWeekday($area, $startDate, 4, 'Recyclables', '10:00', '12:00', 
            'Paper, cardboard, clean plastics, metal cans. Place in blue bins.');
        
        // E-Waste - once per week (Saturday)
        $this->createScheduleForWeekday($area, $startDate, 6, 'E-Waste', '09:00', '11:00', 
            'Electronics, batteries, bulbs. Special handling required. Place in red containers.');
        
        // General waste - twice per week (Mon, Thu)
        $this->createScheduleForWeekday($area, $startDate, 1, 'General', '13:00', '15:00', 
            'Non-recyclable, non-compostable items. Use black bins.');
            
        $this->createScheduleForWeekday($area, $startDate, 4, 'General', '13:00', '15:00', 
            'Non-recyclable, non-compostable items. Use black bins.');
            
        // Hazardous waste - once per month
        $this->createMonthlySchedule($area, $startDate, 3, 6, 'Hazardous', '11:00', '13:00', 
            'Chemicals, paint, medical waste. Special handling required. Contact municipal office for details.');
    }
    
    /**
     * Create schedules for suburban districts (moderate frequency)
     */
    private function createSuburbanSchedules($area, $startDate)
    {
        // Organic waste - twice per week (Mon, Thu)
        $this->createScheduleForWeekday($area, $startDate, 1, 'Organic', '08:00', '10:00', 
            'Food waste, garden trimmings, compostable items. Use green bins.');
            
        $this->createScheduleForWeekday($area, $startDate, 4, 'Organic', '08:00', '10:00', 
            'Food waste, garden trimmings, compostable items. Use green bins.');
        
        // Recyclables - once per week (Wed)
        $this->createScheduleForWeekday($area, $startDate, 3, 'Recyclables', '10:00', '12:00', 
            'Paper, cardboard, clean plastics, metal cans. Place in blue bins.');
        
        // E-Waste - twice per month
        $this->createBiWeeklySchedule($area, $startDate, 6, 'E-Waste', '09:00', '11:00', 
            'Electronics, batteries, bulbs. Special handling required. Place in red containers.');
        
        // General waste - once per week (Sat)
        $this->createScheduleForWeekday($area, $startDate, 6, 'General', '13:00', '15:00', 
            'Non-recyclable, non-compostable items. Use black bins.');
            
        // Hazardous waste - once per month
        $this->createMonthlySchedule($area, $startDate, 2, 6, 'Hazardous', '11:00', '13:00', 
            'Chemicals, paint, medical waste. Special handling required. Contact municipal office for details.');
    }
    
    /**
     * Create schedules for rural districts (less frequent collections)
     */
    private function createRuralSchedules($area, $startDate)
    {
        // Organic waste - once per week (Mon)
        $this->createScheduleForWeekday($area, $startDate, 1, 'Organic', '08:00', '11:00', 
            'Food waste, garden trimmings, compostable items. Community compost available.');
        
        // Recyclables - once per week (Wed)
        $this->createScheduleForWeekday($area, $startDate, 3, 'Recyclables', '09:00', '12:00', 
            'Paper, cardboard, clean plastics, metal cans. Community recycling center at town hall.');
        
        // General waste - once per week (Fri)
        $this->createScheduleForWeekday($area, $startDate, 5, 'General', '09:00', '12:00', 
            'Non-recyclable, non-compostable items. Place at designated collection points.');
            
        // E-Waste - once per month
        $this->createMonthlySchedule($area, $startDate, 1, 6, 'E-Waste', '10:00', '13:00', 
            'Electronics, batteries, bulbs. Monthly collection at main village center.');
            
        // Mobile collection unit - twice per month
        $this->createBiWeeklySchedule($area, $startDate, 2, 'Mobile Unit', '08:00', '16:00', 
            'Mobile waste collection unit visits remote areas. Bring all waste types.');
    }
    
    /**
     * Create weekly schedule for specified weekday (1=Mon, 7=Sun)
     */
    private function createScheduleForWeekday($area, $startDate, $weekday, $wasteType, $timeFrom, $timeTo, $notes)
    {
        // Create collections for 3 months
        for ($week = 0; $week < 12; $week++) {
            $date = $startDate->copy()->addWeeks($week)->next($weekday - 1); // Carbon weekday is 0-based
            
            CollectionSchedule::create([
                'area_id'     => $area->id,
                'date'        => $date,
                'window_from' => $timeFrom,
                'window_to'   => $timeTo,
                'waste_type'  => $wasteType,
                'notes'       => $notes . ' | ' . $area->name . ' District', 
            ]);
        }
    }
    
    /**
     * Create bi-weekly schedule (every two weeks)
     */
    private function createBiWeeklySchedule($area, $startDate, $weekday, $wasteType, $timeFrom, $timeTo, $notes)
    {
        // Create collections for 3 months (6 bi-weekly collections)
        for ($week = 0; $week < 12; $week += 2) {
            $date = $startDate->copy()->addWeeks($week)->next($weekday - 1); // Carbon weekday is 0-based
            
            CollectionSchedule::create([
                'area_id'     => $area->id,
                'date'        => $date,
                'window_from' => $timeFrom,
                'window_to'   => $timeTo,
                'waste_type'  => $wasteType,
                'notes'       => $notes . ' | ' . $area->name . ' District', 
            ]);
        }
    }
    
    /**
     * Create monthly schedule
     */
    private function createMonthlySchedule($area, $startDate, $weekNumber, $weekday, $wasteType, $timeFrom, $timeTo, $notes)
    {
        // Create collections for 3 months
        for ($month = 0; $month < 3; $month++) {
            $date = $startDate->copy()->addMonths($month)->nthOfMonth($weekNumber, $weekday - 1); // Carbon weekday is 0-based
            
            CollectionSchedule::create([
                'area_id'     => $area->id,
                'date'        => $date,
                'window_from' => $timeFrom,
                'window_to'   => $timeTo,
                'waste_type'  => $wasteType,
                'notes'       => $notes . ' | ' . $area->name . ' District', 
            ]);
        }
    }
}
