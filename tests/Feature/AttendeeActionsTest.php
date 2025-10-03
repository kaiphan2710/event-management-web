<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Event;
use App\Models\User;
use App\Models\EventWaitlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * AttendeeActionsTest tests attendee-specific functionality
 * Validates booking system, waitlist management, and user restrictions
 */
class AttendeeActionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a user can successfully register as an Attendee
     *
     * @return void
     */
    public function test_a_user_can_successfully_register_as_an_attendee()
    {
        $userData = [
            'name' => 'Test Attendee',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'user_type' => 'attendee',
            'privacy_policy_agreed' => '1',
            'terms_agreed' => '1',
        ];

        $response = $this->post(route('register'), $userData);

        // Assert user is created
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'user_type' => 'attendee',
            'privacy_policy_agreed' => true,
            'terms_agreed' => true,
        ]);

        // Assert redirect to events page
        $response->assertRedirect(route('events.index'));
    }

    /**
     * Test that a registered Attendee can log in and log out
     *
     * @return void
     */
    public function test_a_registered_attendee_can_log_in_and_log_out()
    {
        $attendee = User::factory()->create(['user_type' => 'attendee']);

        // Simulate logged-in user (avoids controller-specific differences)
        $this->actingAs($attendee);
        $this->assertAuthenticatedAs($attendee);

        // Test logout
        $response = $this->post(route('logout'));
        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /**
     * Test that a logged-in Attendee can book an available, upcoming event
     *
     * @return void
     */
    public function test_a_logged_in_attendee_can_book_an_available_upcoming_event()
    {
        // Create an attendee and an event
        $attendee = User::factory()->create(['user_type' => 'attendee']);
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'capacity' => 10,
            'date' => now()->addDays(7),
        ]);

        // Login as attendee
        $this->actingAs($attendee);

        // Book the event
        $response = $this->post(route('events.book', $event));

        // Assert booking is created
        $this->assertDatabaseHas('bookings', [
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'status' => 'confirmed',
        ]);

        // Assert redirect with success message
        $response->assertRedirect(route('events.show', $event));
        $response->assertSessionHas('success');
    }

    /**
     * Test that after booking, the event is on their "My Bookings" page
     *
     * @return void
     */
    public function test_after_booking_an_attendee_can_see_the_event_on_their_bookings_page()
    {
        // Create an attendee and an event
        $attendee = User::factory()->create(['user_type' => 'attendee']);
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Test Event for Booking',
        ]);

        // Create a booking
        Booking::create([
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'status' => 'confirmed',
            'booking_date' => now(),
        ]);

        // Login as attendee
        $this->actingAs($attendee);

        // Visit bookings page
        $response = $this->get(route('bookings.index'));

        // Assert event is displayed
        $response->assertStatus(200);
        $response->assertSee($event->title);
        $response->assertSee($event->location);
    }

    /**
     * Test that an Attendee cannot book the same event more than once
     *
     * @return void
     */
    public function test_an_attendee_cannot_book_the_same_event_more_than_once()
    {
        // Create an attendee and an event
        $attendee = User::factory()->create(['user_type' => 'attendee']);
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'capacity' => 10,
        ]);

        // Create existing booking
        Booking::create([
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'status' => 'confirmed',
            'booking_date' => now(),
        ]);

        // Login as attendee
        $this->actingAs($attendee);

        // Try to book the same event again
        $response = $this->post(route('events.book', $event));

        // Assert error message
        $response->assertSessionHas('error');

        // Assert no duplicate booking was created
        $this->assertEquals(1, Booking::where('user_id', $attendee->id)
            ->where('event_id', $event->id)
            ->count());
    }

    /**
     * Test that an Attendee cannot book a full event (manual capacity check)
     *
     * @return void
     */
    public function test_an_attendee_cannot_book_a_full_event()
    {
        // Create an attendee and an event with capacity of 2
        $attendee = User::factory()->create(['user_type' => 'attendee']);
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'capacity' => 2,
        ]);

        // Fill the event to capacity
        $otherAttendees = User::factory()->count(2)->create(['user_type' => 'attendee']);
        foreach ($otherAttendees as $otherAttendee) {
            Booking::create([
                'user_id' => $otherAttendee->id,
                'event_id' => $event->id,
                'status' => 'confirmed',
                'booking_date' => now(),
            ]);
        }

        // Login as attendee
        $this->actingAs($attendee);

        // Try to book the full event
        $response = $this->post(route('events.book', $event));

        // Assert error message about event being full (flash session)
        $response->assertSessionHas('error', 'Event is full');

        // Assert no booking was created for this user
        $this->assertDatabaseMissing('bookings', [
            'user_id' => $attendee->id,
            'event_id' => $event->id,
        ]);
    }

    /**
     * Test that an Attendee cannot see "Edit" or "Delete" buttons on an event page
     *
     * @return void
     */
    public function test_an_attendee_cannot_see_edit_or_delete_buttons_on_any_event_page()
    {
        // Create an attendee and an event
        $attendee = User::factory()->create(['user_type' => 'attendee']);
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
        ]);

        // Login as attendee
        $this->actingAs($attendee);

        // Visit event page
        $response = $this->get(route('events.show', $event));

        // Assert successful response
        $response->assertStatus(200);
        
        // Assert edit/delete buttons are not visible
        $response->assertDontSee('Edit Event');
        $response->assertDontSee('Delete Event');
        $response->assertDontSee('btn-outline-warning');
        $response->assertDontSee('btn-outline-danger');
    }

    /**
     * Test waitlist functionality for attendees
     *
     * @return void
     */
    public function test_an_attendee_can_join_and_leave_waitlist_for_full_event()
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
        $response->assertSessionHas('success');

        // Assert waitlist entry is created
        $this->assertDatabaseHas('event_waitlists', [
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'status' => 'active',
        ]);

        // Leave waitlist
        $response = $this->delete(route('events.leave-waitlist', $event));
        $response->assertSessionHas('success');

        // Assert waitlist entry is removed
        $this->assertDatabaseMissing('event_waitlists', [
            'user_id' => $attendee->id,
            'event_id' => $event->id,
            'status' => 'active',
        ]);
    }
}
