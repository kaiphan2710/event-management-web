<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Event model representing events that can be created by organisers
 * and booked by attendees
 */
class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'date',
        'time',
        'location',
        'capacity',
        'organiser_id',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
    ];

    /**
     * Get the organiser who created this event
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organiser()
    {
        return $this->belongsTo(User::class, 'organiser_id');
    }

    /**
     * Get all bookings for this event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get all waitlist entries for this event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function eventWaitlists()
    {
        return $this->hasMany(EventWaitlist::class);
    }

    /**
     * Alias used by views/controllers: waitlistEntries
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function waitlistEntries()
    {
        return $this->hasMany(EventWaitlist::class);
    }

    /**
     * Check if the event is full
     *
     * @return bool
     */
    public function isFull(): bool
    {
        return $this->bookings()->where('status', 'confirmed')->count() >= $this->capacity;
    }

    /**
     * Get the number of remaining spots
     *
     * @return int
     */
    public function getRemainingSpots(): int
    {
        return max(0, $this->capacity - $this->bookings()->where('status', 'confirmed')->count());
    }

    /**
     * Get the current number of bookings
     *
     * @return int
     */
    public function getCurrentBookings(): int
    {
        return $this->bookings()->where('status', 'confirmed')->count();
    }

    /**
     * Check if the event is upcoming
     *
     * @return bool
     */
    public function isUpcoming(): bool
    {
        return $this->date >= Carbon::today();
    }

    /**
     * Scope to get only upcoming events
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', Carbon::today());
    }

    /**
     * Check if a user has booked this event
     *
     * @param int $userId
     * @return bool
     */
    public function isBookedBy(int $userId): bool
    {
        return $this->bookings()->where('user_id', $userId)->where('status', 'confirmed')->exists();
    }

    /**
     * Check if a user is on the waitlist for this event
     *
     * @param int $userId
     * @return bool
     */
    public function isWaitlistedBy(int $userId): bool
    {
        return $this->eventWaitlists()
            ->where('user_id', $userId)
            ->active()
            ->exists();
    }
}
