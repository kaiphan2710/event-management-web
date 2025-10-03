<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use App\Models\EventWaitlist;
use App\Events\BookingCancelled;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * EventController handles all event-related operations
 * Manages event CRUD operations, booking functionality, and waitlist management
 */
class EventController extends Controller
{
    /**
     * Display a paginated listing of upcoming events for public viewing
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $events = Event::upcoming()
            ->with('organiser')
            ->orderBy('date', 'asc')
            ->paginate(10);

        return view('events.index', compact('events'));
    }

    /**
     * Display the specified event details
     *
     * @param Event $event
     * @return \Illuminate\View\View
     */
    public function show(Event $event)
    {
        $event->load('organiser');
        
        $user = Auth::user();
        $isBooked = $user ? $event->isBookedBy($user->id) : false;
        $isWaitlisted = $user ? $event->isWaitlistedBy($user->id) : false;
        
        return view('events.show', compact('event', 'isBooked', 'isWaitlisted'));
    }

    /**
     * Show the form for creating a new event (organiser only)
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', Event::class);
        
        return view('events.create');
    }

    /**
     * Store a newly created event in storage (organiser only)
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->authorize('create', Event::class);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
        ]);

        $validated['organiser_id'] = Auth::id();

        Event::create($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    /**
     * Show the form for editing the specified event (organiser only)
     *
     * @param Event $event
     * @return \Illuminate\View\View
     */
    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified event in storage (organiser only)
     *
     * @param Request $request
     * @param Event $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
        ]);

        $event->update($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified event from storage (organiser only)
     * Cannot delete events with active bookings
     *
     * @param Event $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        
        // Check if event has active bookings
        if ($event->bookings()->where('status', 'confirmed')->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete event with active bookings.');
        }

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }

    /**
     * Book an event for the authenticated user
     * Includes manual capacity validation as required
     *
     * @param Request $request
     * @param Event $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function book(Request $request, Event $event)
    {
        // Handle authorization via explicit checks + role middleware
        if (!Auth::check() || !Auth::user()->isAttendee()) {
            return redirect()->route('login');
        }

        // Manual capacity validation - explicit query and comparison
        $currentBookings = $event->bookings()->where('status', 'confirmed')->count();
        
        if ($currentBookings >= $event->capacity) {
            return redirect()->route('events.show', $event)->with('error', 'Event is full');
        }

        // Check if user already has a confirmed booking
        if ($event->isBookedBy(Auth::id())) {
            return redirect()->route('events.show', $event)->with('error', 'Already booked');
        }

        // Check if user has a cancelled booking and reactivate it
        $existingBooking = Booking::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();

        if ($existingBooking) {
            // Reactivate cancelled booking
            $existingBooking->update([
                'status' => 'confirmed',
                'booking_date' => now(),
            ]);
        } else {
            // Create new booking
            Booking::create([
                'user_id' => Auth::id(),
                'event_id' => $event->id,
                'status' => 'confirmed',
                'booking_date' => now(),
            ]);
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Successfully booked the event!');
    }

    /**
     * Cancel a booking for the authenticated user
     * Triggers waitlist notification if event was full
     *
     * @param Event $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelBooking(Event $event)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->back()
                ->with('error', 'You must be logged in to cancel a booking.');
        }

        $booking = Booking::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->where('status', 'confirmed')
            ->first();

        if (!$booking) {
            return redirect()->back()
                ->with('error', 'Booking not found.');
        }

        $wasFull = $event->isFull();
        
        $booking->cancel();

        // Fire the BookingCancelled event to trigger automated notifications
        event(new BookingCancelled($booking, $wasFull));

        return redirect()->route('events.show', $event)
            ->with('success', 'Booking cancelled successfully.');
    }

    /**
     * Join the waitlist for an event
     *
     * @param Request $request
     * @param Event $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function joinWaitlist(Request $request, Event $event)
    {
        $this->authorize('joinWaitlist', $event);
        
        if ($event->isWaitlistedBy(Auth::id())) {
            return redirect()->back()
                ->with('error', 'You are already on the waitlist for this event.');
        }

        // Get the next position in the waitlist
        $nextPosition = $event->eventWaitlists()->active()->max('position') + 1;

        EventWaitlist::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'position' => $nextPosition,
            'joined_at' => now(),
            'status' => 'active',
        ]);

        return redirect()->back()
            ->with('success', 'Successfully joined the waitlist!');
    }

    /**
     * Leave the waitlist for an event
     *
     * @param Event $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function leaveWaitlist(Event $event)
    {
        $waitlistEntry = EventWaitlist::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->where('status', 'active')
            ->first();

        if (!$waitlistEntry) {
            return redirect()->back()
                ->with('error', 'Waitlist entry not found.');
        }

        $position = $waitlistEntry->position;
        $waitlistEntry->remove();

        // Adjust positions of users behind this position
        $this->adjustWaitlistPositions($event, $position);

        return redirect()->route('events.show', $event)
            ->with('success', 'Successfully left the waitlist.');
    }


    /**
     * Adjust waitlist positions after someone leaves
     *
     * @param Event $event
     * @param int $removedPosition
     * @return void
     */
    private function adjustWaitlistPositions(Event $event, int $removedPosition): void
    {
        $event->eventWaitlists()
            ->active()
            ->where('position', '>', $removedPosition)
            ->decrement('position');
    }
}
