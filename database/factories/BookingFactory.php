<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->attendee(),
            'event_id' => Event::factory(),
            'status' => 'confirmed',
            'booking_date' => now(),
        ];
    }
}


