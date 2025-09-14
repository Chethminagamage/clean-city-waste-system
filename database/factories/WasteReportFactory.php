<?php

namespace Database\Factories;

use App\Models\WasteReport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WasteReportFactory extends Factory
{
    protected $model = WasteReport::class;

    public function definition(): array
    {
        return [
            'resident_id' => User::factory(),
            'collector_id' => null,
            'reference_code' => 'WR-' . strtoupper($this->faker->bothify('########')),
            'location' => $this->faker->address,
            'latitude' => $this->faker->latitude(-90, 90),
            'longitude' => $this->faker->longitude(-180, 180),
            'image_path' => $this->faker->optional()->imageUrl(),
            'report_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'status' => $this->faker->randomElement(['pending', 'assigned', 'enroute', 'collected', 'closed', 'cancelled']),
            'waste_type' => $this->faker->randomElement(['Household Waste', 'Recyclable', 'Organic', 'E-Waste', 'Hazardous']),
            'additional_details' => $this->faker->optional()->sentence(),
            'assigned_at' => null,
            'collected_at' => null,
            'closed_at' => null,
            'is_urgent' => $this->faker->boolean(20), // 20% chance of being urgent
            'urgent_reported_at' => null,
            'urgent_message' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'collector_id' => null,
            'assigned_at' => null,
        ]);
    }

    public function assigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'assigned',
            'collector_id' => User::factory(),
            'assigned_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    public function collected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'collected',
            'collector_id' => User::factory(),
            'assigned_at' => $this->faker->dateTimeBetween('-1 week', '-1 day'),
            'collected_at' => $this->faker->dateTimeBetween('-1 day', 'now'),
        ]);
    }
}
