## Event Management System — Report (Rubric Aligned)

### 1) Executive Summary
This Laravel 10 application enables organisers to create/manage events and attendees to book or join a waitlist when full. It implements secure auth, role-based access, robust booking/waitlist logic, a dark-mode UI with Bootstrap 5, and automated email notifications for the first person in the waitlist when a booking is cancelled.

Key outcomes:
- All core and advanced features implemented (booking, cancellation, waitlist join/leave/view, organiser dashboard with stats, email notification).
- 20/20 feature tests passing (64 assertions). Assertions cover DB state, session flash messages, authorisation, and mail dispatch.

### 2) Technical Architecture Overview
- Framework: Laravel 10 (PHP 8.4)
- DB: SQLite for development/testing
- Layers:
  - Models: `User`, `Event`, `Booking`, `EventWaitlist`
  - Controllers: `EventController`, `WaitlistController`, `BookingController`, `DashboardController`, `Auth\*`
  - Policies: `EventPolicy`, `BookingPolicy`
  - Events/Listeners: `BookingCancelled` → `NotifyWaitlistedUsers`
  - Mail: `WaitlistSpotAvailable`
  - Views: Blade (Bootstrap 5, dark-mode overrides)
- Routing: Web routes with `auth`, `role`, and fine-grained flow checks inside controllers for consistent UX and flash messaging

### 3) Entity-Relationship Diagram (ERD)
Use the Mermaid diagram below. To export as PNG in VS Code/Cursor, install a Mermaid extension or use `https://mermaid.live`:

```mermaid
erDiagram
  USERS ||--o{ EVENTS : "organises"
  USERS ||--o{ BOOKINGS : "makes"
  USERS ||--o{ EVENT_WAITLISTS : "joins"

  EVENTS ||--o{ BOOKINGS : "has"
  EVENTS ||--o{ EVENT_WAITLISTS : "has"

  USERS {
    int id PK
    string name
    string email UQ
    string password
    string user_type  // organiser|attendee
  }

  EVENTS {
    int id PK
    string title
    text description
    date date
    string time
    string location
    int capacity
    int organiser_id FK -> USERS.id
  }

  BOOKINGS {
    int id PK
    int user_id FK -> USERS.id
    int event_id FK -> EVENTS.id
    string status  // confirmed|cancelled
    datetime booking_date
    UNIQUE "user_id,event_id"
  }

  EVENT_WAITLISTS {
    int id PK
    int user_id FK -> USERS.id
    int event_id FK -> EVENTS.id
    int position
    string status  // active|removed|promoted
    datetime joined_at
    UNIQUE "user_id,event_id"
  }
```

Notable design decisions:
- `bookings.status` allows cancellation without deleting history.
- `event_waitlists.position` and `status` allow deterministic promotion and reactivation.

### 4) Advanced Feature (Mandatory Excellence)
Automated email notification for the first person on the waitlist when a booking is cancelled from a previously full event.
- Flow: User cancels booking → `BookingCancelled($booking, $wasFull)` event → `NotifyWaitlistedUsers` listener checks if a spot is available → sends `WaitlistSpotAvailable` to the first `active` waitlist user and marks them `promoted` (optional tracking).
- Rationale: event-driven decoupling; easy to queue. In tests we send synchronously for determinism, enabling `Mail::assertSent`.

### 5) Feature Analysis (by module)
- Booking
  - Prevent double-booking (checks confirmed only).
  - Reactivate old cancelled booking instead of creating duplicates to satisfy unique constraint.
  - Capacity checks count confirmed bookings only.
- Waitlist
  - Join only when event is full; cannot join twice; leave adjusts positions behind.
  - Cannot join waitlist if already booked.
- Dashboard
  - Raw SQL used to compute per-event booking counts and remaining spots, plus waitlist counts.
- Auth/Authorisation
  - Role middleware with explicit flow guards for clearer UX and consistent flash messages.

### 6) Testing Strategy & Evidence
- PHPUnit Feature tests (20): Guests, Attendees, Organisers, Waitlist, Email notification.
- Assertions: `assertDatabaseHas/Missing`, `assertSessionHas`, `assertRedirect`, `assertForbidden`, `Mail::fake` + `Mail::assertSent`.
- Result evidence: 20/20 tests pass, 64 assertions.

### 7) UI/UX
- Dark mode variables for readability; Bootstrap 5 pagination customised for contrast and spacing.
- Clear flash messages after each action; state-sensitive action buttons on the event page (Book/Cancel/Join/Leave).

### 8) Reflection (Process, Challenges, Solutions)
- Setup issues: Missing core Laravel files and config → Reconstructed minimal `app/` kernels, config, and `public/index.php` to satisfy Composer and Artisan.
- DB issues: SQLite “file is not a database” → recreate database file with proper permissions.
- Routing/middleware: `auth`/`guest`/`can` aliases; 403 on cancel booking fixed by introducing dedicated policy method and then moving to explicit flow checks with role middleware for better UX.
- Unique constraints: Booking reactivation to avoid duplicates; Waitlist reactivation with position recalculation.
- UI: Dark mode contrast and pagination layout refined per feedback.
- Testing: Migrated tests to assert flash messages and DB state; made mail listener synchronous under test to pass `assertSent`.
- Key learnings: Keep UX-first flow checks near controllers for clear messaging; prefer event-driven architecture for extensibility; tests should assert business state, not just HTTP codes.

### 9) How to Export ERD as PNG
1. Copy the Mermaid block above to `https://mermaid.live`.
2. Click “Export” → PNG.
3. Add the PNG to your submission (e.g., `docs/erd.png`).

### 10) Future Enhancements
- Queue email notifications in production; add promotion auto-book hold window; organiser category management and client-side filtering.

---
Evidence to include in submission:
- Screenshot PHPUnit OK (20/20, 64 assertions)
- ERD PNG
- 3–5 screenshots: Events list, Event detail (booked/full/waitlist states), Dashboard


