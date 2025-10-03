<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * EventPolicy handles authorization for event-related actions
 * Ensures users can only perform actions they are permitted to
 */
class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create events
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->isOrganiser();
    }

    /**
     * Determine whether the user can update the event
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function update(User $user, Event $event): bool
    {
        return $user->isOrganiser() && $event->organiser_id === $user->id;
    }

    /**
     * Determine whether the user can delete the event
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function delete(User $user, Event $event): bool
    {
        return $user->isOrganiser() && $event->organiser_id === $user->id;
    }

    /**
     * Determine whether the user can book the event
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function book(User $user, Event $event): bool
    {
        // Only attendees can book events
        if (!$user->isAttendee()) {
            return false;
        }

        // Cannot book past events
        if ($event->date < now()->toDateString()) {
            return false;
        }

        // Cannot book if already booked
        if ($event->isBookedBy($user->id)) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can cancel their booking for the event
     *
     * @param User $user
     * @param Event $event
     * @return bool
     */
    public function cancelBooking(User $user, Event $event): bool
    {
        // Only attendees can cancel bookings
        if (!$user->isAttendee()) {
            return false;
        }

        // Can only cancel if they have a confirmed booking
        return $event->isBookedBy($user->id);
    }

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
     * Determine whether the user can view the event
     *
     * @param User|null $user
     * @param Event $event
     * @return bool
     */
    public function view(?User $user, Event $event): bool
    {
        // Anyone can view events
        return true;
    }
}
