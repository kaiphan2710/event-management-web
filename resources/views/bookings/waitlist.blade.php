@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-clock me-2"></i>My Waitlist
            </h1>
            <a href="{{ route('events.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-search me-1"></i>Browse Events
            </a>
        </div>

        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Here are the events you're currently waitlisted for. You'll be automatically notified when a spot becomes available.
        </div>

        @if($waitlistEntries->count() > 0)
            <div class="row">
                @foreach($waitlistEntries as $entry)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title">{{ $entry->event->title }}</h5>
                                    <span class="badge bg-warning">Position #{{ $entry->position }}</span>
                                </div>
                                
                                <p class="card-text text-muted">{{ Str::limit($entry->event->description, 100) }}</p>
                                
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-calendar me-2"></i>
                                        <small class="text-muted">{{ $entry->event->date->format('M d, Y') }}</small>
                                    </div>
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-clock me-2"></i>
                                        <small class="text-muted">{{ $entry->event->time }}</small>
                                    </div>
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        <small class="text-muted">{{ $entry->event->location }}</small>
                                    </div>
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-user-tie me-2"></i>
                                        <small class="text-muted">Organiser: {{ $entry->event->organiser->name }}</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-hourglass-half me-2"></i>
                                        <small class="text-muted">Joined: {{ $entry->joined_at->format('M d, Y g:i A') }}</small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar bg-danger" role="progressbar" 
                                             style="width: 100%">
                                            Event Full
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        {{ $entry->event->getCurrentBookings() }}/{{ $entry->event->capacity }} booked
                                    </small>
                                </div>

                                @if($entry->position == 1)
                                    <div class="alert alert-success alert-sm">
                                        <i class="fas fa-star me-1"></i>
                                        <strong>You're next in line!</strong> You'll be the first to be notified when a spot opens.
                                    </div>
                                @elseif($entry->position <= 3)
                                    <div class="alert alert-warning alert-sm">
                                        <i class="fas fa-clock me-1"></i>
                                        <strong>Great position!</strong> You're in the top 3 on the waitlist.
                                    </div>
                                @else
                                    <div class="alert alert-info alert-sm">
                                        <i class="fas fa-list me-1"></i>
                                        You're position {{ $entry->position }} on the waitlist.
                                    </div>
                                @endif
                            </div>
                            
                            <div class="card-footer bg-transparent border-top">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('events.show', $entry->event) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>View Event
                                    </a>
                                    <form method="POST" action="{{ route('events.leave-waitlist', $entry->event) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Are you sure you want to leave the waitlist for this event?')">
                                            <i class="fas fa-sign-out-alt me-1"></i>Leave Waitlist
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No Waitlist Entries</h4>
                <p class="text-muted">You're not currently on any waitlists. Join waitlists for full events to be notified when spots become available.</p>
                <a href="{{ route('events.index') }}" class="btn btn-primary">
                    <i class="fas fa-search me-1"></i>Browse Events
                </a>
            </div>
        @endif

        <!-- Waitlist Information -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>How the Waitlist Works
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Automatic Notifications</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Email notifications when spots open</li>
                            <li><i class="fas fa-check text-success me-2"></i>First-come-first-served priority</li>
                            <li><i class="fas fa-check text-success me-2"></i>Limited time to claim available spots</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Waitlist Management</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Leave waitlist anytime</li>
                            <li><i class="fas fa-check text-success me-2"></i>Position updates automatically</li>
                            <li><i class="fas fa-check text-success me-2"></i>No booking limits on waitlists</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
