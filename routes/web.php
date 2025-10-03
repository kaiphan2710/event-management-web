<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\WaitlistController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', function () {
    return redirect()->route('events.index');
});

// Event routes (public access)
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])
    ->whereNumber('event')
    ->name('events.show');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Logout route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Authenticated routes
Route::middleware('auth')->group(function () {
    
    // Event management (organiser only)
    Route::middleware('can:create,App\Models\Event')->group(function () {
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    });

    // Event booking and waitlist actions (attendee only)
    Route::middleware('role:attendee')->group(function () {
        Route::post('/events/{event}/book', [EventController::class, 'book'])->name('events.book');
        Route::delete('/events/{event}/cancel-booking', [EventController::class, 'cancelBooking'])->name('events.cancel-booking');
        Route::post('/events/{event}/join-waitlist', [WaitlistController::class, 'join'])->name('events.join-waitlist');
    });

    // Booking management (attendee only)
    Route::middleware('role:attendee')->group(function () {
        Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/my-waitlist', [WaitlistController::class, 'myWaitlists'])->name('bookings.waitlist');
        Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
        // Leave waitlist should be allowed even if event is no longer full
        Route::delete('/events/{event}/leave-waitlist', [WaitlistController::class, 'leave'])->name('events.leave-waitlist');
        Route::post('/events/{event}/leave-waitlist', [WaitlistController::class, 'leave']);
    });

    // Dashboard (organiser only)
    Route::middleware('role:organiser')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/dashboard/events/{event}/waitlist', [WaitlistController::class, 'view'])->name('dashboard.waitlist');
        Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    });
});
