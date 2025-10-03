@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-calendar-alt me-2"></i>Upcoming Events
            </h1>
            @auth
                @if(auth()->user()->isOrganiser())
                    <a href="{{ route('events.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create Event
                    </a>
                @endif
            @endauth
        </div>

        @if($events->count() > 0)
            <div class="row">
                @foreach($events as $event)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $event->title }}</h5>
                                <p class="card-text text-muted">{{ Str::limit($event->description, 100) }}</p>
                                
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-calendar me-2"></i>
                                        <small class="text-muted">{{ $event->date->format('M d, Y') }}</small>
                                    </div>
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-clock me-2"></i>
                                        <small class="text-muted">{{ $event->time }}</small>
                                    </div>
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        <small class="text-muted">{{ $event->location }}</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-users me-2"></i>
                                        <small class="text-muted">
                                            {{ $event->getCurrentBookings() }}/{{ $event->capacity }} booked
                                        </small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar 
                                            @if($event->getRemainingSpots() == 0) bg-danger
                                            @elseif($event->getRemainingSpots() <= $event->capacity * 0.2) bg-warning
                                            @else bg-success @endif" 
                                            role="progressbar" 
                                            style="width: {{ ($event->getCurrentBookings() / $event->capacity) * 100 }}%">
                                        </div>
                                    </div>
                                    @if($event->getRemainingSpots() == 0)
                                        <small class="text-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Event Full
                                        </small>
                                    @else
                                        <small class="text-muted">{{ $event->getRemainingSpots() }} spots remaining</small>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card-footer bg-transparent border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-user-tie me-1"></i>{{ $event->organiser->name }}
                                    </small>
                                    <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-primary">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small">
                    Showing {{ $events->firstItem() }} to {{ $events->lastItem() }} of {{ $events->total() }} results
                </div>
                <nav>
                    {{ $events->onEachSide(1)->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No Upcoming Events</h4>
                <p class="text-muted">There are currently no upcoming events available.</p>
                @auth
                    @if(auth()->user()->isOrganiser())
                        <a href="{{ route('events.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Create the First Event
                        </a>
                    @endif
                @endauth
            </div>
        @endif
    </div>
</div>
@endsection
