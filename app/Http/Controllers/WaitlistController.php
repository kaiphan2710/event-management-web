<?php

namespace App\Http\Controllers;

use App\Models\EventWaitlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use App\Events\BookingCancelled;

/**
 * WaitlistController handles waitlist functionality
 * Manages joining, leaving, and viewing waitlists as required by the assignment
 */
class WaitlistController extends Controller
{
    /**
     * Join the waitlist for an event
     *
     * @param Request $request
     * @param \App\Models\Event $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function join(Request $request, \App\Models\Event $event)
    {
        // Explicit checks instead of policy gate to allow flashing messages
        if (!Auth::check() || !Auth::user()->isAttendee()) {
            return redirect()->route('login');
        }

        if (!$event->isFull()) {
            return redirect()->route('events.show', $event)->with('error', 'Event not full');
        }

        // Cannot join waitlist if already booked
        if ($event->isBookedBy(Auth::id())) {
            return redirect()->route('events.show', $event)->with('error', 'You are already booked for this event.');
        }

        if ($event->isWaitlistedBy(Auth::id())) {
            return redirect()->route('events.show', $event)->with('error', 'You are already on the waitlist for this event.');
        }

        // If a removed entry exists (unique constraint), reactivate it instead of creating new
        $existing = EventWaitlist::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();

        // Get the next position in the waitlist
        $maxPosition = $event->eventWaitlists()->active()->max('position');
        $nextPosition = ($maxPosition ?? 0) + 1;

        if ($existing) {
            if ($existing->status === 'active') {
                return redirect()->route('events.show', $event)->with('error', 'You are already on the waitlist for this event.');
            }

            $existing->update([
                'status' => 'active',
                'position' => $nextPosition,
                'joined_at' => now(),
            ]);
        } else {
            EventWaitlist::create([
                'user_id' => Auth::id(),
                'event_id' => $event->id,
                'position' => $nextPosition,
                'joined_at' => now(),
                'status' => 'active',
            ]);
        }

        return redirect()->back()
            ->with('success', 'Successfully joined the waitlist!');
    }

    /**
     * Leave the waitlist for an event
     *
     * @param \App\Models\Event $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function leave(\App\Models\Event $event)
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

        return redirect()->back()
            ->with('success', 'Successfully left the waitlist.');
    }

    /**
     * View waitlist entries for an event (organiser only)
     *
     * @param \App\Models\Event $event
     * @return \Illuminate\View\View
     */
    public function view(\App\Models\Event $event)
    {
        $this->authorize('view', $event);
        
        $waitlistEntries = $event->eventWaitlists()
            ->with('user')
            ->active()
            ->orderByPosition()
            ->get();

        return view('dashboard.waitlist', compact('event', 'waitlistEntries'));
    }

    /**
     * View user's waitlist entries
     *
     * @return \Illuminate\View\View
     */
    public function myWaitlists()
    {
        $waitlistEntries = Auth::user()->eventWaitlists()
            ->with('event.organiser')
            ->active()
            ->orderByPosition()
            ->get();

        return view('bookings.waitlist', compact('waitlistEntries'));
    }

    /**
     * Adjust waitlist positions after someone leaves
     *
     * @param \App\Models\Event $event
     * @param int $removedPosition
     * @return void
     */
    private function adjustWaitlistPositions(\App\Models\Event $event, int $removedPosition): void
    {
        $event->eventWaitlists()
            ->active()
            ->where('position', '>', $removedPosition)
            ->decrement('position');
    }
}
