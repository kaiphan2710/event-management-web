<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Booking model representing a user's booking for an event
 * Links users to events they have successfully booked
 */
class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'event_id',
        'status',
        'booking_date',
        'notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'booking_date' => 'datetime',
    ];

    /**
     * Get the user who made this booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event that was booked
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Check if the booking is active (not cancelled)
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Cancel the booking
     *
     * @return void
     */
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }
}
