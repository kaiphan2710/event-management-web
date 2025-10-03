<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password123'),
            'user_type' => $this->faker->randomElement(['attendee', 'organiser']),
            'phone' => $this->faker->optional()->e164PhoneNumber(),
            'address' => $this->faker->optional()->address(),
            'privacy_policy_agreed' => true,
            'terms_agreed' => true,
            'agreed_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    public function organiser(): static
    {
        return $this->state(fn () => ['user_type' => 'organiser']);
    }

    public function attendee(): static
    {
        return $this->state(fn () => ['user_type' => 'attendee']);
    }
}


