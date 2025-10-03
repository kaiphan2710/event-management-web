@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ $event->title }}</h4>
                    @auth
                        @if(auth()->user()->isOrganiser() && auth()->user()->id === $event->organiser_id)
                            <div>
                                <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                <form method="POST" action="{{ route('events.destroy', $event) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Are you sure you want to delete this event?')">
                                        <i class="fas fa-trash me-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
            
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-calendar text-primary me-2"></i>
                            <strong>Date:</strong>
                            <span class="ms-2">{{ $event->date->format('l, F d, Y') }}</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-clock text-primary me-2"></i>
                            <strong>Time:</strong>
                            <span class="ms-2">{{ $event->time }}</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                            <strong>Location:</strong>
                            <span class="ms-2">{{ $event->location }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-users text-primary me-2"></i>
                            <strong>Capacity:</strong>
                            <span class="ms-2">{{ $event->capacity }} people</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-ticket-alt text-primary me-2"></i>
                            <strong>Bookings:</strong>
                            <span class="ms-2">{{ $event->getCurrentBookings() }} confirmed</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-user-tie text-primary me-2"></i>
                            <strong>Organiser:</strong>
                            <span class="ms-2">{{ $event->organiser->name }}</span>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h5>Description</h5>
                    <p class="text-muted">{{ $event->description }}</p>
                </div>

                <div class="mb-4">
                    <h5>Availability</h5>
                    <div class="progress mb-2" style="height: 20px;">
                        <div class="progress-bar 
                            @if($event->getRemainingSpots() == 0) bg-danger
                            @elseif($event->getRemainingSpots() <= $event->capacity * 0.2) bg-warning
                            @else bg-success @endif" 
                            role="progressbar" 
                            style="width: {{ ($event->getCurrentBookings() / $event->capacity) * 100 }}%">
                            {{ $event->getCurrentBookings() }}/{{ $event->capacity }}
                        </div>
                    </div>
                    @if($event->getRemainingSpots() == 0)
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            This event is full. You can join the waitlist to be notified if a spot becomes available.
                        </div>
                    @else
                        <p class="text-muted">
                            <strong>{{ $event->getRemainingSpots() }}</strong> spots remaining
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                @auth
                    @if(auth()->user()->isAttendee())
                        @if($isBooked)
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                You have successfully booked this event!
                            </div>
                            <form method="POST" action="{{ route('events.cancel-booking', $event) }}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger w-100" 
                                        onclick="return confirm('Are you sure you want to cancel your booking?')">
                                    <i class="fas fa-times me-1"></i>Cancel Booking
                                </button>
                            </form>
                        @elseif($isWaitlisted)
                            <div class="alert alert-info">
                                <i class="fas fa-clock me-2"></i>
                                You are on the waitlist for this event.
                            </div>
                            <form method="POST" action="{{ route('events.leave-waitlist', $event) }}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-warning w-100" 
                                        onclick="return confirm('Are you sure you want to leave the waitlist?')">
                                    <i class="fas fa-sign-out-alt me-1"></i>Leave Waitlist
                                </button>
                            </form>
                        @else
                            @if($event->getRemainingSpots() > 0)
                                <form method="POST" action="{{ route('events.book', $event) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-ticket-alt me-1"></i>Book Now
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('events.join-waitlist', $event) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-clock me-1"></i>Join Waitlist
                                    </button>
                                </form>
                            @endif
                        @endif
                    @elseif(auth()->user()->isOrganiser() && auth()->user()->id === $event->organiser_id)
                        <a href="{{ route('events.edit', $event) }}" class="btn btn-warning w-100 mb-2">
                            <i class="fas fa-edit me-1"></i>Edit Event
                        </a>
                        <a href="{{ route('dashboard.waitlist', $event) }}" class="btn btn-info w-100">
                            <i class="fas fa-list me-1"></i>View Waitlist
                        </a>
                    @endif
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Please log in to book this event or join the waitlist.
                    </div>
                    <a href="{{ route('login') }}" class="btn btn-primary w-100">
                        <i class="fas fa-sign-in-alt me-1"></i>Login to Book
                    </a>
                @endauth
            </div>
        </div>

        @if($event->waitlistEntries()->active()->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Waitlist Status</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-2">
                        <i class="fas fa-users me-1"></i>
                        {{ $event->waitlistEntries()->active()->count() }} people on waitlist
                    </p>
                    @if(auth()->check() && auth()->user()->isWaitlistedBy($event->id))
                        @php
                            $position = $event->waitlistEntries()
                                ->where('user_id', auth()->id())
                                ->active()
                                ->first()
                                ->position ?? null;
                        @endphp
                        @if($position)
                            <div class="alert alert-info">
                                <i class="fas fa-clock me-2"></i>
                                You are #{{ $position }} on the waitlist
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
