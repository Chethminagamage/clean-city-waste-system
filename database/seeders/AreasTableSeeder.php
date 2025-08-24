<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;

class AreasTableSeeder extends Seeder
{
    public function run(): void
    {
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

        foreach ($districts as $i => $name) {
            Area::create([
                'name' => $name,
                'code' => strtoupper(substr($name, 0, 3)), // e.g. "COL", "GAM"
            ]);
        }
    }
}