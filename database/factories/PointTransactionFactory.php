<?php

namespace Database\Factories;

use App\Models\PointTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PointTransactionFactory extends Factory
{
    protected $model = PointTransaction::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement(['earned', 'spent', 'bonus']),
            'points' => $this->faker->numberBetween(10, 100),
            'source' => $this->faker->randomElement([
                'report_submitted',
                'report_collected',
                'first_report',
                'urgent_report',
                'proper_segregation',
                'weekly_bonus',
                'achievement_unlocked'
            ]),
            'description' => $this->faker->sentence(),
            'metadata' => null,
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'updated_at' => now(),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function reportSubmitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'action_type' => 'report_submitted',
            'points' => 25,
            'description' => 'Points earned for submitting a waste report',
        ]);
    }

    public function reportCollected(): static
    {
        return $this->state(fn (array $attributes) => [
            'action_type' => 'report_collected',
            'points' => 50,
            'description' => 'Points earned for completed waste collection',
        ]);
    }

    public function firstReport(): static
    {
        return $this->state(fn (array $attributes) => [
            'action_type' => 'first_report',
            'points' => 100,
            'description' => 'Bonus points for submitting your first report',
        ]);
    }
}
