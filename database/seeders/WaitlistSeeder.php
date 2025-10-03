<?php

namespace Database\Seeders;

use App\Models\EventWaitlist;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * WaitlistSeeder creates waitlist entries for full events
 * Adds users to waitlists for events that are at capacity
 */
class WaitlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attendees = User::where('user_type', 'attendee')->get();
        
        // Get events that are full (capacity reached)
        $fullEvents = Event::with('bookings')->get()->filter(function ($event) {
            return $event->getCurrentBookings() >= $event->capacity;
        });

        foreach ($fullEvents as $event) {
            // Add 10-20 people to each full event's waitlist
            $waitlistCount = rand(10, 20);
            
            // Get attendees who haven't booked this event
            $availableAttendees = $attendees->filter(function ($attendee) use ($event) {
                return !$event->bookings()->where('user_id', $attendee->id)
                    ->where('status', 'confirmed')->exists();
            });

            $waitlistAttendees = $availableAttendees->take($waitlistCount);
            
            foreach ($waitlistAttendees as $index => $attendee) {
                EventWaitlist::create([
                    'user_id' => $attendee->id,
                    'event_id' => $event->id,
                    'position' => $index + 1,
                    'joined_at' => now()->subDays(rand(1, 15)),
                    'status' => 'active',
                ]);
            }
        }

        // Add some people to waitlists for events that are nearly full
        $nearlyFullEvents = Event::with('bookings')->get()->filter(function ($event) {
            $currentBookings = $event->getCurrentBookings();
            return $currentBookings >= $event->capacity * 0.9 && $currentBookings < $event->capacity;
        });

        foreach ($nearlyFullEvents as $event) {
            // Add 3-8 people to nearly full events
            $waitlistCount = rand(3, 8);
            
            $availableAttendees = $attendees->filter(function ($attendee) use ($event) {
                return !$event->bookings()->where('user_id', $attendee->id)
                    ->where('status', 'confirmed')->exists();
            });

            $waitlistAttendees = $availableAttendees->take($waitlistCount);
            
            foreach ($waitlistAttendees as $index => $attendee) {
                EventWaitlist::create([
                    'user_id' => $attendee->id,
                    'event_id' => $event->id,
                    'position' => $index + 1,
                    'joined_at' => now()->subDays(rand(1, 10)),
                    'status' => 'active',
                ]);
            }
        }
    }
}
