<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * User model representing both Attendees and Organisers in the system
 * Handles authentication and user role management
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'phone',
        'address',
        'privacy_policy_agreed',
        'terms_agreed',
        'agreed_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'privacy_policy_agreed' => 'boolean',
        'terms_agreed' => 'boolean',
        'agreed_at' => 'datetime',
    ];

    /**
     * Check if user is an organiser
     *
     * @return bool
     */
    public function isOrganiser(): bool
    {
        return $this->user_type === 'organiser';
    }

    /**
     * Check if user is an attendee
     *
     * @return bool
     */
    public function isAttendee(): bool
    {
        return $this->user_type === 'attendee';
    }

    /**
     * Get all events created by this organiser
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'organiser_id');
    }

    /**
     * Get all bookings made by this attendee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get all waitlist entries for this attendee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function eventWaitlists()
    {
        return $this->hasMany(EventWaitlist::class);
    }

    /**
     * Check if the user is waitlisted for the given event
     */
    public function isWaitlistedBy(int $eventId): bool
    {
        return $this->eventWaitlists()
            ->where('event_id', $eventId)
            ->active()
            ->exists();
    }
}
