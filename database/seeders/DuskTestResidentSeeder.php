<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DuskTestResidentSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            [
                'email' => 'hesararajapaksha98@gmail.com',
            ],
            [
                'name' => 'Hesara Rajapaksha',
                'email' => 'hesararajapaksha98@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'resident',
                'email_verified_at' => now(),
                'status' => 'active',
                'contact' => '0712345678',
            ]
        );
    }
}
