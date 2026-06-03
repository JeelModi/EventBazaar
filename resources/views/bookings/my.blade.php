@extends('layouts.app')

@section('content')
<h3 class="mb-4"><i class="bi bi-ticket"></i> My Bookings</h3>

@if($bookings->isEmpty())
    <div class="alert alert-info">
        You have no bookings yet.
        <a href="{{ route('events.index') }}">Browse Events</a>
    </div>
@else
<div class="table-responsive">
    <table class="table table-bordered table-hover bg-white">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Event</th>
                <th>Date</th>
                <th>Seats</th>
                <th>Total Price</th>
                <th>Confirmation Code</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $booking->event->title }}</td>
                <td>{{ $booking->event->event_date->format('d M Y') }}</td>
                <td>{{ $booking->seats }}</td>
                <td>₹{{ number_format($booking->seats * $booking->event->price, 2) }}</td>
                <td>
                    <span class="badge bg-info text-dark">
                        {{ $booking->confirmation_code }}
                    </span>
                </td>
                <td>
                    <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : 'danger' }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </td>
                <td>
                    {{-- Add $booking->event->status === 'active' check --}}
                    @if($booking->status === 'confirmed' && $booking->event->status === 'active')
                    <form method="POST"
                        action="{{ route('bookings.cancel', $booking->id) }}"
                        onsubmit="return confirm('Cancel this booking?')">
                        @csrf
                        <button class="btn btn-sm btn-danger">
                            <i class="bi bi-x-circle"></i> Cancel
                        </button>
                    </form>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection