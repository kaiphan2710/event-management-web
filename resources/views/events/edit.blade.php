@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Event</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('events.update', $event) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" value="{{ old('title', $event->title) }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="4" class="form-control" required>{{ old('description', $event->description) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" value="{{ old('date', $event->date->toDateString()) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Time</label>
                            <input type="time" name="time" value="{{ old('time', $event->time) }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" value="{{ old('location', $event->location) }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Capacity</label>
                        <input type="number" name="capacity" value="{{ old('capacity', $event->capacity) }}" min="1" class="form-control" required>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('events.show', $event) }}" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


