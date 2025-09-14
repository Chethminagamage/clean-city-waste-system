<?php

namespace Database\Factories;

use App\Models\Feedback;
use App\Models\User;
use App\Models\WasteReport;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    protected $model = Feedback::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'waste_report_id' => WasteReport::factory(),
            'feedback_type' => 'report',
            'rating' => $this->faker->numberBetween(1, 5),
            'message' => $this->faker->paragraph(),
            'admin_response' => null,
            'admin_responded_by' => null,
            'admin_responded_at' => null,
            'status' => 'pending',
            'resolved_by' => null,
            'resolved_at' => null,
            'response_rating' => null,
            'response_rated_at' => null,
        ];
    }

    public function highRating(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $this->faker->numberBetween(4, 5),
        ]);
    }

    public function lowRating(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $this->faker->numberBetween(1, 2),
        ]);
    }

    public function withResponse(): static
    {
        return $this->state(fn (array $attributes) => [
            'admin_response' => $this->faker->paragraph(),
            'admin_responded_by' => Admin::factory(),
            'admin_responded_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'status' => 'responded',
        ]);
    }

    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'admin_response' => $this->faker->paragraph(),
            'admin_responded_by' => Admin::factory(),
            'admin_responded_at' => $this->faker->dateTimeBetween('-1 week', '-1 day'),
            'status' => 'resolved',
            'resolved_by' => Admin::factory(),
            'resolved_at' => $this->faker->dateTimeBetween('-1 day', 'now'),
        ]);
    }

    public function general(): static
    {
        return $this->state(fn (array $attributes) => [
            'waste_report_id' => null,
            'feedback_type' => 'service',
        ]);
    }
}
