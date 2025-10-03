<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * WaitlistPolicy handles authorization for waitlist-related actions
 * Ensures users can only perform appropriate waitlist operations
 */
class WaitlistPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can join the waitlist for the event
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function joinWaitlist(User $user, Event $event): bool
    {
        // Only attendees can join waitlists
        if (!$user->isAttendee()) {
            return false;
        }

        // Cannot join waitlist for past events
        if ($event->date < now()->toDateString()) {
            return false;
        }

        // Cannot join waitlist if already booked
        if ($event->isBookedBy($user->id)) {
            return false;
        }

        // Cannot join waitlist if already on waitlist
        if ($event->isWaitlistedBy($user->id)) {
            return false;
        }

        // Can only join waitlist if event is full
        return $event->isFull();
    }

    /**
     * Determine whether the user can view waitlist for the event
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function view(User $user, Event $event): bool
    {
        // Only the event organiser can view the waitlist
        return $user->isOrganiser() && $event->organiser_id === $user->id;
    }
}
