@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">

            {{-- Event Image --}}
            @if($event->image_path)
                <img src="{{ asset('storage/' . $event->image_path) }}"
                     class="card-img-top" style="height:300px; object-fit:cover">
            @endif

            <div class="card-body">

                {{-- Live indicator (Part 5) --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="mb-0">{{ $event->title }}</h3>
                    <span style="font-size:13px; color:#dc3545;">
                        <span style="display:inline-block; width:8px; height:8px;
                                     background:#dc3545; border-radius:50%;
                                     animation: pulse 1.5s infinite;">
                        </span>
                        &nbsp; Live Updates ON
                    </span>
                </div>

                <p class="text-muted">{{ $event->description }}</p>
                <hr>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><i class="bi bi-calendar"></i>
                            <strong>Date:</strong>
                            {{ $event->event_date->format('d M Y, h:i A') }}
                        </p>
                        <p><i class="bi bi-currency-rupee"></i>
                            <strong>Price:</strong> ₹{{ number_format($event->price, 2) }}
                        </p>
                    </div>

                    {{-- Part 4: Updated col with IDs for live updates --}}
                    <div class="col-md-6">
                        <p>
                            <i class="bi bi-people"></i>
                            <strong>Capacity:</strong> {{ $event->capacity }}
                        </p>
                        <p>
                            <i class="bi bi-check-circle"></i>
                            <strong>Available Seats:</strong>
                            <span id="seats-badge"
                                  class="badge bg-{{ $event->isFullyBooked() ? 'danger' : 'success' }}">
                                {{ $event->remainingSeats() }} seats left
                            </span>
                        </p>
                        <p>
                            <i class="bi bi-people-fill"></i>
                            <strong>Booked:</strong>
                            <span id="booked-count">{{ $event->booked_count }}</span>
                            / {{ $event->capacity }}
                        </p>
                    </div>
                </div>

                {{-- Alerts --}}
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- Booking Form with id="booking-form" (Part 4) --}}
                @if(!$event->isFullyBooked() && $event->status === 'active')
                <div id="booking-form" class="card bg-light p-3">
                    <h5><i class="bi bi-ticket"></i> Book This Event</h5>
                    <form method="POST" action="{{ route('events.book', $event->id) }}">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label class="form-label">Number of Seats</label>
                                <input type="number" name="seats"
                                       class="form-control" value="1" min="1"
                                       max="{{ min(10, $event->remainingSeats()) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Total Price</label>
                                <input type="text" class="form-control"
                                       id="total_price"
                                       value="₹{{ number_format($event->price, 2) }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-check-circle"></i> Confirm Booking
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                @elseif($event->isFullyBooked())
                    <div class="alert alert-danger">This event is fully booked.</div>
                @endif

                <a href="{{ route('events.index') }}" class="btn btn-secondary mt-3">
                    <i class="bi bi-arrow-left"></i> Back to Events
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // ── Price calculator ──────────────────────────────────────
    const pricePerSeat = parseFloat("{{ $event->price }}");
    document.querySelector('input[name="seats"]')?.addEventListener('input', function() {
        const total = this.value * pricePerSeat;
        document.getElementById('total_price').value = '₹' + total.toFixed(2);
    });

    // ── WebSocket-style Ajax Polling ──────────────────────────
    const eventId        = {{ $event->id }};
    const seatApiUrl     = `/events/${eventId}/seats`;
    let   lastSeatCount  = {{ $event->remainingSeats() }};
    let   pollInterval   = null;

    function startPolling() {
        pollInterval = setInterval(fetchSeatCount, 3000);
    }

    function fetchSeatCount() {
        fetch(seatApiUrl)
            .then(response => response.json())
            .then(data => updateSeatsDisplay(data))
            .catch(error => console.log('Polling error:', error));
    }

    function updateSeatsDisplay(data) {
        const seatsBadge  = document.getElementById('seats-badge');
        const bookedCount = document.getElementById('booked-count');
        const bookingForm = document.getElementById('booking-form');
        const seatsInput  = document.querySelector('input[name="seats"]');

        if (seatsBadge) {
            seatsBadge.textContent = data.remaining_seats + ' seats left';
            if (data.is_fully_booked) {
                seatsBadge.className = 'badge bg-danger';
            } else if (data.remaining_seats <= 10) {
                seatsBadge.className = 'badge bg-warning text-dark';
            } else {
                seatsBadge.className = 'badge bg-success';
            }
        }

        if (bookedCount) bookedCount.textContent = data.booked_count;
        if (seatsInput)  seatsInput.max = Math.min(10, data.remaining_seats);

        if (data.is_fully_booked && bookingForm) {
            bookingForm.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle"></i>
                    This event is now fully booked!
                </div>`;
            clearInterval(pollInterval);
        }

        if (data.remaining_seats !== lastSeatCount) {
            const diff = lastSeatCount - data.remaining_seats;
            if (diff > 0) {
                showToast(`🔴 ${diff} seat(s) just booked! Only ${data.remaining_seats} left.`);
            }
            lastSeatCount = data.remaining_seats;
        }
    }

    function showToast(message) {
        const existing = document.getElementById('live-toast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.id = 'live-toast';
        toast.style.cssText = `
            position: fixed;
            bottom: 25px;
            right: 25px;
            background: #0d6efd;
            color: white;
            padding: 14px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            z-index: 9999;
            font-size: 14px;
            max-width: 300px;
            animation: slideIn 0.3s ease;
        `;
        toast.innerHTML = `<strong>Live Update</strong><br>${message}`;
        document.body.appendChild(toast);
        setTimeout(() => { if (toast.parentNode) toast.remove(); }, 4000);
    }

    startPolling();

    window.addEventListener('beforeunload', function() {
        clearInterval(pollInterval);
    });
</script>

<style>
    @keyframes slideIn {
        from { transform: translateX(120px); opacity: 0; }
        to   { transform: translateX(0);     opacity: 1; }
    }
    @keyframes pulse {
        0%   { opacity: 1;   transform: scale(1);   }
        50%  { opacity: 0.4; transform: scale(1.3); }
        100% { opacity: 1;   transform: scale(1);   }
    }
</style>
@endsection