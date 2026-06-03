@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="bi bi-calendar-event"></i> All Events</h3>
    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Create New Event
    </a>
</div>

@if($events->isEmpty())
    <div class="alert alert-info">No events yet. Create your first event!</div>
@else
<div class="table-responsive">
    <table class="table table-bordered table-hover bg-white">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Title</th>
                <th>Date</th>
                <th>Capacity</th>
                <th>Booked</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    @if($event->image_path)
                        <img src="{{ asset('storage/' . $event->image_path) }}"
                             width="60" height="40" style="object-fit:cover; border-radius:4px">
                    @else
                        <span class="text-muted">No image</span>
                    @endif
                </td>
                <td>{{ $event->title }}</td>
                <td>{{ $event->event_date->format('d M Y, h:i A') }}</td>
                <td>{{ $event->capacity }}</td>
                <td>
                    <span class="badge bg-{{ $event->isFullyBooked() ? 'danger' : 'success' }}">
                        {{ $event->booked_count }} / {{ $event->capacity }}
                    </span>
                </td>
                <td>₹{{ number_format($event->price, 2) }}</td>
                <td>
                    <span class="badge bg-{{
                        $event->status === 'active' ? 'success' :
                        ($event->status === 'cancelled' ? 'danger' : 'secondary')
                    }}">
                        {{ ucfirst($event->status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.events.edit', $event->id) }}"
                       class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form method="POST"
                          action="{{ route('admin.events.destroy', $event->id) }}"
                          style="display:inline"
                          onsubmit="return confirm('Delete this event?')">
                        @csrf
                        <button class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection