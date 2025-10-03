@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-ticket-alt me-2"></i>My Bookings
            </h1>
            <a href="{{ route('events.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-search me-1"></i>Browse Events
            </a>
        </div>

        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Here are all the events you have successfully booked. You can view details or cancel bookings from here.
        </div>

        @if($bookings->count() > 0)
            <div class="row">
                @foreach($bookings as $booking)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $booking->event->title }}</h5>
                                <p class="card-text text-muted">{{ Str::limit($booking->event->description, 100) }}</p>
                                
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-calendar me-2"></i>
                                        <small class="text-muted">{{ $booking->event->date->format('M d, Y') }}</small>
                                    </div>
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-clock me-2"></i>
                                        <small class="text-muted">{{ $booking->event->time }}</small>
                                    </div>
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        <small class="text-muted">{{ $booking->event->location }}</small>
                                    </div>
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-user-tie me-2"></i>
                                        <small class="text-muted">Organiser: {{ $booking->event->organiser->name }}</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar-check me-2"></i>
                                        <small class="text-muted">Booked: {{ $booking->booking_date->format('M d, Y g:i A') }}</small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    @if($booking->event->date < now()->toDateString())
                                        <span class="badge bg-secondary">Past Event</span>
                                    @elseif($booking->event->date == now()->toDateString())
                                        <span class="badge bg-warning">Today</span>
                                    @else
                                        <span class="badge bg-success">Upcoming</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card-footer bg-transparent border-top">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('events.show', $booking->event) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>View Event
                                    </a>
                                    @if($booking->event->date >= now()->toDateString())
                                        <form method="POST" action="{{ route('bookings.destroy', $booking) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                <i class="fas fa-times me-1"></i>Cancel
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No Bookings Yet</h4>
                <p class="text-muted">You haven't booked any events yet. Start exploring available events!</p>
                <a href="{{ route('events.index') }}" class="btn btn-primary">
                    <i class="fas fa-search me-1"></i>Browse Events
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
