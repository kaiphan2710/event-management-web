<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\Booking;
use App\Policies\EventPolicy;
use App\Policies\BookingPolicy;
use App\Policies\WaitlistPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

/**
 * AuthServiceProvider registers authorization policies and gates
 * Handles user permissions and access control
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Event::class => EventPolicy::class,
        Booking::class => BookingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define role-based gates
        Gate::define('role', function ($user, $role) {
            return $user->user_type === $role;
        });
    }
}
