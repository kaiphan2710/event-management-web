<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'date' => now()->addDays($this->faker->numberBetween(1, 30))->toDateString(),
            'time' => $this->faker->time('H:i'),
            'location' => $this->faker->city(),
            'capacity' => $this->faker->numberBetween(10, 200),
            'organiser_id' => User::factory()->organiser(),
            'status' => 'active',
        ];
    }
}


