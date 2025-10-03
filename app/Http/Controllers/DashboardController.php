<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * DashboardController handles organiser dashboard functionality
 * Provides event reports with raw SQL queries as required
 */
class DashboardController extends Controller
{
    /**
     * Display the organiser dashboard with event reports
     * Uses raw SQL query to generate event statistics as required
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $organiser = Auth::user();
        
        // Raw SQL query to generate event report with booking statistics
        // This is the mandatory raw SQL requirement for the dashboard
        $eventsReport = DB::select("
            SELECT 
                e.id,
                e.title,
                e.date,
                e.time,
                e.location,
                e.capacity,
                COALESCE(booking_counts.current_bookings, 0) as current_bookings,
                (e.capacity - COALESCE(booking_counts.current_bookings, 0)) as remaining_spots,
                e.created_at
            FROM events e
            LEFT JOIN (
                SELECT 
                    event_id,
                    COUNT(*) as current_bookings
                FROM bookings 
                WHERE status = 'confirmed'
                GROUP BY event_id
            ) booking_counts ON e.id = booking_counts.event_id
            WHERE e.organiser_id = ?
            ORDER BY e.date ASC
        ", [$organiser->id]);

        // Get waitlist statistics for each event
        $waitlistStats = DB::select("
            SELECT 
                event_id,
                COUNT(*) as waitlist_count
            FROM event_waitlists 
            WHERE status = 'active'
            GROUP BY event_id
        ");

        // Create a lookup array for waitlist counts
        $waitlistLookup = [];
        foreach ($waitlistStats as $stat) {
            $waitlistLookup[$stat->event_id] = $stat->waitlist_count;
        }

        // Add waitlist counts to events report
        foreach ($eventsReport as $event) {
            $event->waitlist_count = $waitlistLookup[$event->id] ?? 0;
        }

        return view('dashboard.index', compact('eventsReport', 'organiser'));
    }

    /**
     * Display waitlist management for a specific event
     *
     * @param int $eventId
     * @return \Illuminate\View\View
     */
    public function waitlist($eventId)
    {
        $event = Auth::user()->events()->findOrFail($eventId);
        
        $waitlistEntries = $event->eventWaitlists()
            ->with('user')
            ->active()
            ->orderByPosition()
            ->get();

        return view('dashboard.waitlist', compact('event', 'waitlistEntries'));
    }

    /**
     * Get booking statistics for the organiser
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats()
    {
        $organiser = Auth::user();
        
        $stats = [
            'total_events' => $organiser->events()->count(),
            'upcoming_events' => $organiser->events()->upcoming()->count(),
            'total_bookings' => DB::table('bookings')
                ->join('events', 'bookings.event_id', '=', 'events.id')
                ->where('events.organiser_id', $organiser->id)
                ->where('bookings.status', 'confirmed')
                ->count(),
            'total_waitlist_entries' => DB::table('event_waitlists')
                ->join('events', 'event_waitlists.event_id', '=', 'events.id')
                ->where('events.organiser_id', $organiser->id)
                ->where('event_waitlists.status', 'active')
                ->count(),
        ];

        return response()->json($stats);
    }
}
