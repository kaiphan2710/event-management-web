<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Events\BookingCancelled;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * BookingController handles attendee booking management
 * Manages user's bookings and provides booking history
 */
class BookingController extends Controller
{
    /**
     * Display a listing of the authenticated user's bookings
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        $bookings = $user->bookings()
            ->with('event.organiser')
            ->where('status', 'confirmed')
            ->orderBy('booking_date', 'desc')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Display a listing of the authenticated user's waitlist entries
     *
     * @return \Illuminate\View\View
     */
    public function waitlist()
    {
        $user = Auth::user();
        
        $waitlistEntries = $user->eventWaitlists()
            ->with('event.organiser')
            ->active()
            ->orderByPosition()
            ->get();

        return view('bookings.waitlist', compact('waitlistEntries'));
    }

    /**
     * Display the specified booking
     *
     * @param Booking $booking
     * @return \Illuminate\View\View
     */
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        
        $booking->load('event.organiser');
        
        return view('bookings.show', compact('booking'));
    }

    /**
     * Remove the specified booking from storage
     *
     * @param Booking $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);
        
        $event = $booking->event;
        $wasFull = $event->isFull();
        
        $booking->cancel();

        // Fire the BookingCancelled event to trigger automated notifications
        event(new BookingCancelled($booking, $wasFull));

        return redirect()->route('bookings.index')
            ->with('success', 'Booking cancelled successfully.');
    }

}
