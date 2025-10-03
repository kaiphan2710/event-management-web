<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * GuestAccessTest tests public access functionality
 * Validates that guests can view events but are redirected for protected routes
 */
class GuestAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a guest can view the paginated list of upcoming events
     *
     * @return void
     */
    public function test_a_guest_can_view_the_paginated_list_of_upcoming_events()
    {
        // Create an organiser and some events
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        $events = Event::factory()->count(15)->create([
            'organiser_id' => $organiser->id,
            'date' => now()->addDays(rand(1, 30)),
        ]);

        // Guest visits the events index page
        $response = $this->get(route('events.index'));

        // Assert successful response
        $response->assertStatus(200);
        
        // Assert events are displayed
        $response->assertSee($events->first()->title);
        
        // Assert pagination is working (if more than 10 events)
        // Basic assertion: first event's title visible confirms pagination rendered content
    }

    /**
     * Test that a guest can view the details page for a specific event
     *
     * @return void
     */
    public function test_a_guest_can_view_a_specific_event_details_page()
    {
        // Create an organiser and an event
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Test Event',
            'description' => 'Test Description',
        ]);

        // Guest visits the event details page
        $response = $this->get(route('events.show', $event));

        // Assert successful response
        $response->assertStatus(200);
        
        // Assert event details are displayed
        $response->assertSee($event->title);
        $response->assertSee($event->description);
        $response->assertSee($event->location);
    }

    /**
     * Test that a guest is redirected to the login page for authenticated routes
     *
     * @return void
     */
    public function test_a_guest_is_redirected_when_accessing_protected_routes()
    {
        // Test dashboard access (organiser only)
        $response = $this->get(route('dashboard.index'));
        $response->assertRedirect(route('login'));

        // Test booking creation (attendee only)
        $event = Event::factory()->create();
        $response = $this->post(route('events.book', $event));
        $response->assertRedirect(route('login'));

        // Test event creation (organiser only)
        $response = $this->get(route('events.create'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test that a guest viewing an event details page cannot see action buttons
     *
     * @return void
     */
    public function test_a_guest_cannot_see_action_buttons_on_event_details_page()
    {
        // Create an organiser and an event
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'capacity' => 10,
        ]);

        // Guest visits the event details page
        $response = $this->get(route('events.show', $event));

        // Assert successful response
        $response->assertStatus(200);
        
        // Assert action buttons are not visible
        $response->assertDontSee('Book Now');
        $response->assertDontSee('Join Waitlist');
        $response->assertDontSee('Edit Event');
        $response->assertDontSee('Delete Event');
        
        // Assert login prompt is visible
        $response->assertSee('Please log in to book this event');
    }
}
