<?php

namespace Database\Factories;

use App\Models\Reward;
use Illuminate\Database\Eloquent\Factories\Factory;

class RewardFactory extends Factory
{
    protected $model = Reward::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'cost_points' => $this->faker->numberBetween(100, 1000),
            'type' => $this->faker->randomElement(['voucher', 'gift', 'discount', 'other']),
            'reward_data' => ['code' => $this->faker->uuid],
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
