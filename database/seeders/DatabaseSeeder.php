<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    User::create([
        'name' => 'Admin User',
        'email' => 'admin@cleancity.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
    ]);

    User::create([
        'name' => 'Collector User',
        'email' => 'collector@cleancity.com',
        'password' => Hash::make('password'),
        'role' => 'collector',
    ]);

    User::create([
        'name' => 'Resident User',
        'email' => 'resident@cleancity.com',
        'password' => Hash::make('password'),
        'role' => 'resident',
    ]);
}
}
