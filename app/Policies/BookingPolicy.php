<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * BookingPolicy handles authorization for booking-related actions
 * Ensures users can only access their own bookings
 */
class BookingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the booking
     *
     * @param User $user
     * @param Booking $booking
     * @return bool
     */
    public function view(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id || $user->isOrganiser();
    }

    /**
     * Determine whether the user can update the booking
     *
     * @param User $user
     * @param Booking $booking
     * @return bool
     */
    public function update(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id;
    }

    /**
     * Determine whether the user can delete the booking
     *
     * @param User $user
     * @param Booking $booking
     * @return bool
     */
    public function delete(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id;
    }
}
