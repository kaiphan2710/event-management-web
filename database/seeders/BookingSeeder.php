<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * BookingSeeder creates realistic booking data for testing
 * Creates bookings with various attendees across different events
 */
class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attendees = User::where('user_type', 'attendee')->get();
        $events = Event::all();

        // Create bookings for various events
        $bookings = [
            // Tech Innovation Summit - 150 bookings (75% full)
            [
                'event_id' => $events->where('title', 'Tech Innovation Summit 2024')->first()->id,
                'attendee_ids' => $attendees->take(150)->pluck('id')->toArray(),
            ],
            
            // Digital Marketing Workshop - 45 bookings (90% full)
            [
                'event_id' => $events->where('title', 'Digital Marketing Workshop')->first()->id,
                'attendee_ids' => $attendees->take(45)->pluck('id')->toArray(),
            ],
            
            // Startup Pitch Competition - 95 bookings (95% full)
            [
                'event_id' => $events->where('title', 'Startup Pitch Competition')->first()->id,
                'attendee_ids' => $attendees->take(95)->pluck('id')->toArray(),
            ],
            
            // AI & Machine Learning Conference - 120 bookings (80% full)
            [
                'event_id' => $events->where('title', 'AI & Machine Learning Conference')->first()->id,
                'attendee_ids' => $attendees->take(120)->pluck('id')->toArray(),
            ],
            
            // Cybersecurity Awareness Seminar - 80 bookings (100% full)
            [
                'event_id' => $events->where('title', 'Cybersecurity Awareness Seminar')->first()->id,
                'attendee_ids' => $attendees->take(80)->pluck('id')->toArray(),
            ],
            
            // Data Analytics Bootcamp - 25 bookings (100% full)
            [
                'event_id' => $events->where('title', 'Data Analytics Bootcamp')->first()->id,
                'attendee_ids' => $attendees->take(25)->pluck('id')->toArray(),
            ],
            
            // Mobile App Development Workshop - 30 bookings (100% full)
            [
                'event_id' => $events->where('title', 'Mobile App Development Workshop')->first()->id,
                'attendee_ids' => $attendees->take(30)->pluck('id')->toArray(),
            ],
            
            // Melbourne Food & Wine Festival - 200 bookings (67% full)
            [
                'event_id' => $events->where('title', 'Melbourne Food & Wine Festival')->first()->id,
                'attendee_ids' => $attendees->take(200)->pluck('id')->toArray(),
            ],
            
            // Photography Masterclass - 35 bookings (87% full)
            [
                'event_id' => $events->where('title', 'Photography Masterclass')->first()->id,
                'attendee_ids' => $attendees->take(35)->pluck('id')->toArray(),
            ],
            
            // Sustainable Living Expo - 90 bookings (75% full)
            [
                'event_id' => $events->where('title', 'Sustainable Living Expo')->first()->id,
                'attendee_ids' => $attendees->take(90)->pluck('id')->toArray(),
            ],
            
            // Fitness & Wellness Retreat - 45 bookings (75% full)
            [
                'event_id' => $events->where('title', 'Fitness & Wellness Retreat')->first()->id,
                'attendee_ids' => $attendees->take(45)->pluck('id')->toArray(),
            ],
            
            // Music Production Workshop - 20 bookings (100% full)
            [
                'event_id' => $events->where('title', 'Music Production Workshop')->first()->id,
                'attendee_ids' => $attendees->take(20)->pluck('id')->toArray(),
            ],
            
            // Art & Design Exhibition Opening - 150 bookings (75% full)
            [
                'event_id' => $events->where('title', 'Art & Design Exhibition Opening')->first()->id,
                'attendee_ids' => $attendees->take(150)->pluck('id')->toArray(),
            ],
            
            // Entrepreneurship Networking Event - 60 bookings (80% full)
            [
                'event_id' => $events->where('title', 'Entrepreneurship Networking Event')->first()->id,
                'attendee_ids' => $attendees->take(60)->pluck('id')->toArray(),
            ],
            
            // Language Learning Meetup - 30 bookings (60% full)
            [
                'event_id' => $events->where('title', 'Language Learning Meetup')->first()->id,
                'attendee_ids' => $attendees->take(30)->pluck('id')->toArray(),
            ],
        ];

        foreach ($bookings as $bookingData) {
            $event = Event::find($bookingData['event_id']);
            
            foreach ($bookingData['attendee_ids'] as $index => $attendeeId) {
                // Ensure we don't exceed event capacity
                if ($index >= $event->capacity) {
                    break;
                }
                
                Booking::create([
                    'user_id' => $attendeeId,
                    'event_id' => $event->id,
                    'status' => 'confirmed',
                    'booking_date' => now()->subDays(rand(1, 30)),
                ]);
            }
        }

        // Create some cancelled bookings for testing
        $cancelledBookings = [
            [
                'user_id' => $attendees->get(0)->id,
                'event_id' => $events->where('title', 'Tech Innovation Summit 2024')->first()->id,
                'status' => 'cancelled',
                'booking_date' => now()->subDays(15),
            ],
            [
                'user_id' => $attendees->get(1)->id,
                'event_id' => $events->where('title', 'Digital Marketing Workshop')->first()->id,
                'status' => 'cancelled',
                'booking_date' => now()->subDays(10),
            ],
        ];

        foreach ($cancelledBookings as $booking) {
            Booking::create($booking);
        }
    }
}
