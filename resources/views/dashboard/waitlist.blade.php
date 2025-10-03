@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Waitlist - {{ $event->title }}</h5>
                <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-primary">Back to Event</a>
            </div>
            <div class="card-body">
                @if($waitlistEntries->count() === 0)
                    <div class="text-center text-muted">No one is on the waitlist yet.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-dark table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($waitlistEntries as $entry)
                                    <tr>
                                        <td>{{ $entry->position }}</td>
                                        <td>{{ $entry->user->name }}</td>
                                        <td>{{ $entry->user->email }}</td>
                                        <td>{{ $entry->joined_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection


