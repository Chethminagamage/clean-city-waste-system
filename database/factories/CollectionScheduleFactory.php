<?php

namespace Database\Factories;

use App\Models\CollectionSchedule;
use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

class CollectionScheduleFactory extends Factory
{
    protected $model = CollectionSchedule::class;

    public function definition(): array
    {
        return [
            'area_id' => Area::factory(),
            'date' => $this->faker->dateTimeBetween('now', '+30 days'),
            'waste_type' => $this->faker->randomElement(['organic', 'plastic', 'paper', 'glass', 'general']),
            'frequency' => $this->faker->randomElement(['weekly', 'bi-weekly', 'monthly']),
            'window_from' => $this->faker->time('08:00:00', '12:00:00'),
            'window_to' => $this->faker->time('13:00:00', '18:00:00'),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
