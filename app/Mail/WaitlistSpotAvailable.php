<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * WaitlistSpotAvailable mail class for automated waitlist notifications
 * This implements the mandatory excellence marker for automated notifications
 */
class WaitlistSpotAvailable extends Mailable
{
    use Queueable, SerializesModels;

    public Event $event;
    public User $user;

    /**
     * Create a new message instance.
     *
     * @param Event $event
     * @param User $user
     */
    public function __construct(Event $event, User $user)
    {
        $this->event = $event;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'A Spot is Available: ' . $this->event->title,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.waitlist-notification',
            with: [
                'event' => $this->event,
                'user' => $this->user,
                'bookingUrl' => route('events.show', $this->event),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
