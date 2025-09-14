<?php

namespace Database\Factories;

use App\Models\UserGamification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserGamificationFactory extends Factory
{
    protected $model = UserGamification::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'total_points' => $this->faker->numberBetween(0, 1000),
            'level' => $this->faker->numberBetween(1, 5),
            'reports_count' => $this->faker->numberBetween(0, 50),
            'achievements_count' => $this->faker->numberBetween(0, 10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
