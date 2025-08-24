<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;

class AreasSeeder extends Seeder
{
    public function run(): void
    {
        $districts = [
            ['name' => 'Colombo',          'code' => 'CO'],
            ['name' => 'Gampaha',          'code' => 'GA'],
            ['name' => 'Kalutara',         'code' => 'KA'],
            ['name' => 'Kandy',            'code' => 'KD'],
            ['name' => 'Matale',           'code' => 'MT'],
            ['name' => 'Nuwara Eliya',     'code' => 'NE'],
            ['name' => 'Galle',            'code' => 'GL'],
            ['name' => 'Matara',           'code' => 'MR'],
            ['name' => 'Hambantota',       'code' => 'HB'],
            ['name' => 'Jaffna',           'code' => 'JF'],
            ['name' => 'Kilinochchi',      'code' => 'KL'],
            ['name' => 'Mannar',           'code' => 'MN'],
            ['name' => 'Vavuniya',         'code' => 'VV'],
            ['name' => 'Mullaitivu',       'code' => 'ML'],
            ['name' => 'Batticaloa',       'code' => 'BC'],
            ['name' => 'Ampara',           'code' => 'AP'],
            ['name' => 'Trincomalee',      'code' => 'TC'],
            ['name' => 'Kurunegala',       'code' => 'KU'],
            ['name' => 'Puttalam',         'code' => 'PT'],
            ['name' => 'Anuradhapura',     'code' => 'AN'],
            ['name' => 'Polonnaruwa',      'code' => 'PL'],
            ['name' => 'Badulla',          'code' => 'BD'],
            ['name' => 'Monaragala',       'code' => 'MO'],
            ['name' => 'Ratnapura',        'code' => 'RP'],
            ['name' => 'Kegalle',          'code' => 'KG'],
        ];

        foreach ($districts as $d) {
            Area::firstOrCreate(['name' => $d['name']], ['code' => $d['code']]);
        }
    }
}