<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\EventWaitlist;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventWaitlistFactory extends Factory
{
    protected $model = EventWaitlist::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->attendee(),
            'event_id' => Event::factory(),
            'position' => 1,
            'joined_at' => now(),
            'status' => 'active',
        ];
    }
}


