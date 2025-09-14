<?php

namespace Database\Factories;

use App\Models\Achievement;
use Illuminate\Database\Eloquent\Factories\Factory;

class AchievementFactory extends Factory
{
    protected $model = Achievement::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'slug' => $this->faker->unique()->slug,
            'description' => $this->faker->sentence(),
            'icon' => $this->faker->randomElement(['trophy', 'star', 'medal', 'award']),
            'category' => $this->faker->randomElement(['milestone', 'activity', 'bonus']),
            'points_reward' => $this->faker->numberBetween(10, 100),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
