<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Event;
use App\Models\EventWaitlist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event as EventFacade;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * WaitlistTest tests the waitlist system functionality
 * Covers all core functional criteria and excellence markers
 */
class WaitlistTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an attendee can join waitlist for full event
     *
     * @return void
     */
    public function test_attendee_can_join_waitlist_for_full_event()
    {
        // Create an attendee and a full event
        $attendee = User::factory()->create(['user_type' => 'attendee']);
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'capacity' => 1,
        ]);

        // Fill the event
        $otherAttendee = User::factory()->create(['user_type' => 'attendee']);
        Booking::create([
            'user_id' => $otherAttendee->id,
            'event_id' => $event->id,
            'status' => 'confirmed',
            'booking_date' => now(),
        ]);

        // Login as attendee
        $this->actingAs($attendee);

        // Join waitlist
        $response = $this->post(route('events.join-waitlist', $event));

        // Assert waitlist entry is created
        $this->assertDatabaseHas('event_waitlists', [
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'status' => 'active',
        ]);

        $response->assertSessionHas('success');
    }

    /**
     * Test that an attendee cannot join waitlist for available event
     *
     * @return void
     */
    public function test_attendee_cannot_join_waitlist_for_available_event()
    {
        // Create an attendee and an available event
        $attendee = User::factory()->create(['user_type' => 'attendee']);
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'capacity' => 10,
        ]);

        // Login as attendee
        $this->actingAs($attendee);

        // Try to join waitlist for available event
        $response = $this->post(route('events.join-waitlist', $event));

        // Assert no waitlist entry is created
        $this->assertDatabaseMissing('event_waitlists', [
            'user_id' => $attendee->id,
            'event_id' => $event->id,
        ]);

        $response->assertSessionHas('error');
    }

    /**
     * Test that an attendee cannot join waitlist twice for same event
     *
     * @return void
     */
    public function test_attendee_cannot_join_waitlist_twice_for_same_event()
    {
        // Create an attendee and a full event
        $attendee = User::factory()->create(['user_type' => 'attendee']);
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'capacity' => 1,
        ]);

        // Fill the event
        $otherAttendee = User::factory()->create(['user_type' => 'attendee']);
        Booking::create([
            'user_id' => $otherAttendee->id,
            'event_id' => $event->id,
            'status' => 'confirmed',
            'booking_date' => now(),
        ]);

        // Login as attendee
        $this->actingAs($attendee);

        // Join waitlist first time
        $this->post(route('events.join-waitlist', $event));

        // Try to join waitlist again
        $response = $this->post(route('events.join-waitlist', $event));

        // Assert error message (flash session)
        $response->assertSessionHas('error', 'You are already on the waitlist for this event.');

        // Assert only one waitlist entry exists
        $this->assertEquals(1, EventWaitlist::where('user_id', $attendee->id)
            ->where('event_id', $event->id)
            ->count());
    }

    /**
     * Test that an attendee can leave waitlist
     *
     * @return void
     */
    public function test_attendee_can_leave_waitlist()
    {
        // Create an attendee and a full event
        $attendee = User::factory()->create(['user_type' => 'attendee']);
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'capacity' => 1,
        ]);

        // Create waitlist entry
        EventWaitlist::create([
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'position' => 1,
            'joined_at' => now(),
            'status' => 'active',
        ]);

        // Login as attendee
        $this->actingAs($attendee);

        // Leave waitlist
        $response = $this->delete(route('events.leave-waitlist', $event));

        // Assert waitlist entry is removed
        $this->assertDatabaseMissing('event_waitlists', [
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'status' => 'active',
        ]);

        $response->assertSessionHas('success');
    }

    /**
     * Test that an attendee can view their waitlists
     *
     * @return void
     */
    public function test_attendee_can_view_their_waitlists()
    {
        // Create an attendee and events
        $attendee = User::factory()->create(['user_type' => 'attendee']);
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        
        $event1 = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Event 1',
        ]);
        
        $event2 = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Event 2',
        ]);

        // Create waitlist entries
        EventWaitlist::create([
            'user_id' => $attendee->id,
            'event_id' => $event1->id,
            'position' => 1,
            'joined_at' => now(),
            'status' => 'active',
        ]);

        EventWaitlist::create([
            'user_id' => $attendee->id,
            'event_id' => $event2->id,
            'position' => 2,
            'joined_at' => now(),
            'status' => 'active',
        ]);

        // Login as attendee
        $this->actingAs($attendee);

        // Visit waitlist page
        $response = $this->get(route('bookings.waitlist'));

        // Assert events are displayed
        $response->assertStatus(200);
        $response->assertSee('Event 1');
        $response->assertSee('Event 2');
    }

    /**
     * Test that an organiser can view waitlist for their events
     *
     * @return void
     */
    public function test_organiser_can_view_waitlist_for_their_events()
    {
        // Create organiser and attendee
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        $attendee = User::factory()->create(['user_type' => 'attendee']);
        
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Test Event',
        ]);

        // Create waitlist entry
        EventWaitlist::create([
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'position' => 1,
            'joined_at' => now(),
            'status' => 'active',
        ]);

        // Login as organiser
        $this->actingAs($organiser);

        // Visit waitlist page
        $response = $this->get(route('dashboard.waitlist', $event));

        // Assert waitlist is displayed
        $response->assertStatus(200);
        $response->assertSee($attendee->name);
    }

    /**
     * Test that email is sent to first waitlisted user when booking cancelled
     * This tests the mandatory excellence marker for automated notifications
     *
     * @return void
     */
    public function test_email_sent_to_first_waitlisted_user_when_booking_cancelled()
    {
        // Fake mail to test email sending
        Mail::fake();

        // Create users and event
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        $attendee1 = User::factory()->create(['user_type' => 'attendee']);
        $attendee2 = User::factory()->create(['user_type' => 'attendee']);
        
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'capacity' => 1,
        ]);

        // Create a booking to fill the event
        $booking = Booking::create([
            'user_id' => $attendee1->id,
            'event_id' => $event->id,
            'status' => 'confirmed',
            'booking_date' => now(),
        ]);

        // Add someone to waitlist
        EventWaitlist::create([
            'user_id' => $attendee2->id,
            'event_id' => $event->id,
            'position' => 1,
            'joined_at' => now(),
            'status' => 'active',
        ]);

        // Login as attendee who booked
        $this->actingAs($attendee1);

        // Cancel the booking
        $response = $this->delete(route('events.cancel-booking', $event));

        // Assert email was sent to waitlisted user
        Mail::assertSent(\App\Mail\WaitlistSpotAvailable::class, function ($mail) use ($attendee2) {
            return $mail->user->id === $attendee2->id;
        });

        $response->assertSessionHas('success');
    }

    /**
     * Test that user cannot join waitlist if already booked
     *
     * @return void
     */
    public function test_user_cannot_join_waitlist_if_already_booked()
    {
        // Create users and event
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        $attendee = User::factory()->create(['user_type' => 'attendee']);
        
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'capacity' => 10,
        ]);

        // Create a booking for the attendee
        Booking::create([
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'status' => 'confirmed',
            'booking_date' => now(),
        ]);

        // Fill the event to capacity
        for ($i = 0; $i < 9; $i++) {
            $otherAttendee = User::factory()->create(['user_type' => 'attendee']);
            Booking::create([
                'user_id' => $otherAttendee->id,
                'event_id' => $event->id,
                'status' => 'confirmed',
                'booking_date' => now(),
            ]);
        }

        // Login as attendee who already booked
        $this->actingAs($attendee);

        // Try to join waitlist
        $response = $this->post(route('events.join-waitlist', $event));

        // Assert error message
        $response->assertSessionHas('error');

        // Assert no waitlist entry was created
        $this->assertDatabaseMissing('event_waitlists', [
            'user_id' => $attendee->id,
            'event_id' => $event->id,
        ]);
    }
}
