<?php

namespace App\Providers;

use App\Events\BookingCancelled;
use App\Listeners\NotifyWaitlistedUsers;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * EventServiceProvider registers event-listener bindings
 * Handles the automated waitlist notification system
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        BookingCancelled::class => [
            NotifyWaitlistedUsers::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
