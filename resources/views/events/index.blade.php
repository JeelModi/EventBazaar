@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="bi bi-calendar3"></i> Upcoming Events</h3>
</div>

@if($events->isEmpty())
    <div class="alert alert-info">No upcoming events available.</div>
@else
<div class="row g-4">
    @foreach($events as $event)
    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            {{-- Event Image --}}
            @if($event->image_path)
                <img src="{{ asset('storage/' . $event->image_path) }}"
                     class="card-img-top" height="200" style="object-fit:cover">
            @else
                <div class="bg-secondary d-flex align-items-center justify-content-center"
                     style="height:200px">
                    <i class="bi bi-image text-white" style="font-size:3rem"></i>
                </div>
            @endif

            <div class="card-body">
                <h5 class="card-title">{{ $event->title }}</h5>
                <p class="card-text text-muted" style="font-size:14px">
                    {{ Str::limit($event->description, 100) }}
                </p>

                <div class="mb-2">
                    <i class="bi bi-calendar"></i>
                    <strong>{{ $event->event_date->format('d M Y, h:i A') }}</strong>
                </div>

                <div class="mb-2">
                    <i class="bi bi-people"></i>
                    <span class="badge bg-{{ $event->isFullyBooked() ? 'danger' : 'success' }}">
                        {{ $event->remainingSeats() }} seats left
                    </span>
                </div>

                <div class="mb-3">
                    <i class="bi bi-currency-rupee"></i>
                    <strong>₹{{ number_format($event->price, 2) }}</strong>
                </div>
            </div>

            <div class="card-footer bg-white">
                @if($event->isFullyBooked())
                    <button class="btn btn-secondary w-100" disabled>Fully Booked</button>
                @else
                    <a href="{{ route('events.show', $event->id) }}"
                       class="btn btn-primary w-100">
                        <i class="bi bi-ticket"></i> Book Now
                    </a>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection