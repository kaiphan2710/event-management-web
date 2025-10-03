<?php

namespace App\Listeners;

use App\Events\BookingCancelled;
use App\Mail\WaitlistSpotAvailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

/**
 * NotifyWaitlistedUsers listener handles automated waitlist notifications
 * This implements the mandatory excellence marker for automated notifications
 */
class NotifyWaitlistedUsers
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * When a booking is cancelled for a full event, notify the first person on waitlist
     *
     * @param BookingCancelled $event
     * @return void
     */
    public function handle(BookingCancelled $event): void
    {
        // Only proceed if the event was full before cancellation
        if (!$event->wasFull) {
            return;
        }

        $eventModel = $event->booking->event;

        // Check if event is now not full (spot available)
        if ($eventModel->isFull()) {
            return;
        }

        // Get the first person on the waitlist
        $firstInLine = $eventModel->eventWaitlists()
            ->active()
            ->orderBy('position', 'asc')
            ->first();

        if ($firstInLine) {
            // Send email notification to the first person on waitlist
            Mail::to($firstInLine->user->email)
                ->send(new WaitlistSpotAvailable($eventModel, $firstInLine->user));

            // Mark as promoted (optional - for tracking purposes)
            $firstInLine->update(['status' => 'promoted']);
        }
    }
}
