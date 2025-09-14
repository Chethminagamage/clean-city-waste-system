<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminFactory extends Factory
{
    protected $model = Admin::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'phone' => $this->faker->optional()->phoneNumber(),
            'position' => $this->faker->optional()->jobTitle(),
            'department' => $this->faker->optional()->randomElement(['Operations', 'Management', 'IT', 'Support']),
            'profile_photo' => null,
            'bio' => $this->faker->optional()->paragraph(),
            'notification_preferences' => [
                'email_reports' => true,
                'email_feedback' => true,
                'sms_urgent' => false,
            ],
            'timezone' => 'Asia/Colombo',
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'last_login_at' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
            'last_login_ip' => $this->faker->optional()->ipv4(),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function withTwoFactor(): static
    {
        return $this->state(fn (array $attributes) => [
            'two_factor_enabled' => true,
            'two_factor_secret' => Str::random(32),
        ]);
    }
}
