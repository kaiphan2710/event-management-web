<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * EventWaitlist model representing a user's position on an event's waitlist
 * Manages the waitlist system when events are full
 */
class EventWaitlist extends Model
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
        'position',
        'joined_at',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'joined_at' => 'datetime',
    ];

    /**
     * Get the user who is on the waitlist
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event for this waitlist entry
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Check if the waitlist entry is active
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the user is first in line on the waitlist
     *
     * @return bool
     */
    public function isFirstInLine(): bool
    {
        return $this->position === 1 && $this->isActive();
    }

    /**
     * Move the user up in the waitlist position
     *
     * @return void
     */
    public function moveUp(): void
    {
        if ($this->position > 1) {
            $this->update(['position' => $this->position - 1]);
        }
    }

    /**
     * Remove the user from the waitlist
     *
     * @return void
     */
    public function remove(): void
    {
        $this->update(['status' => 'removed']);
    }

    /**
     * Scope to get active waitlist entries
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to order by position
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByPosition($query)
    {
        return $query->orderBy('position', 'asc');
    }
}
