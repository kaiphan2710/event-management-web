<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * BookingCancelled event is fired when a booking is cancelled
 * This triggers the waitlist notification system as required by the assignment
 */
class BookingCancelled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Booking $booking;
    public bool $wasFull;

    /**
     * Create a new event instance.
     *
     * @param Booking $booking The cancelled booking
     * @param bool $wasFull Whether the event was full before cancellation
     */
    public function __construct(Booking $booking, bool $wasFull)
    {
        $this->booking = $booking;
        $this->wasFull = $wasFull;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
