<?php
// app/Http/Controllers/BookingController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\BookingConfirmationMail;

class BookingController extends Controller
{
    // Show all active events to users (GET)
    public function index()
    {
        $events = Event::where('status', 'active')
                       ->orderBy('event_date', 'asc')
                       ->get();
        return view('events.index', compact('events'));
    }

    // Show single event detail + booking form (GET)
    public function show($id)
    {
        $event = Event::findOrFail($id);
        return view('events.show', compact('event'));
    }

    // Handle booking form submission (POST)
    public function book(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // Validate seats
        $request->validate([
            'seats' => 'required|integer|min:1|max:10',
        ]);

        // Check if event is active
        if ($event->status !== 'active') {
            return back()->with('error', 'This event is not available for booking.');
        }

        // Check if enough seats available
        if ($request->seats > $event->remainingSeats()) {
            return back()->with('error', 'Not enough seats available. Only ' . $event->remainingSeats() . ' seats left.');
        }

        // Check if user already booked this event
        $alreadyBooked = Booking::where('user_id', Session::get('user_id'))
                                ->where('event_id', $id)
                                ->where('status', 'confirmed')
                                ->exists();

        if ($alreadyBooked) {
            return back()->with('sorry', 'You have already booked this event.');
        }

        // Create booking using OOP
        $booking = Booking::create([
            'user_id'           => Session::get('user_id'),
            'event_id'          => $id,
            'seats'             => $request->seats,
            'status'            => 'confirmed',
            'confirmation_code' => Booking::generateCode(), // OOP static method
        ]);

        // Update event booked count
        $event->increment('booked_count', $request->seats);

        // Send confirmation email
        $user = User::find(Session::get('user_id'));
        try {
            Mail::to($user->email)
                ->send(new BookingConfirmationMail($booking, $event, $user));
        } catch (\Exception $e) {
            dd('Email Error: ' . $e->getMessage()); // show error on screen
        }

        return redirect()->route('bookings.my')
                         ->with('success', 'Booking confirmed! Code: ' . $booking->confirmation_code);
    }

    // Show user's bookings (GET)
    public function myBookings()
    {
        $bookings = Booking::where('user_id', Session::get('user_id'))
                           ->with('event') // eager load event data
                           ->orderBy('created_at', 'desc')
                           ->get();
        return view('bookings.my', compact('bookings'));
    }

    // Cancel a booking (POST)
    public function cancel($id)
    {
        $booking = Booking::where('id', $id)
                          ->where('user_id', Session::get('user_id'))
                          ->firstOrFail();

        // Only cancel confirmed bookings
        if ($booking->status === 'confirmed') {
            $booking->update(['status' => 'cancelled']);

            // Give back the seats to the event
            $booking->event->decrement('booked_count', $booking->seats);
        }

        return redirect()->route('bookings.my')
                         ->with('success', 'Booking cancelled successfully.');
    }

    // Send confirmation email (private OOP method)
    // Send confirmation email using Laravel Mail (OOP)
    private function sendConfirmationEmail($user, $event, $booking)
    {
        try {
            Mail::to($user->email)
                ->send(new BookingConfirmationMail($booking, $event, $user));
        } catch (\Exception $e) {
            // Don't fail the booking if email fails
            // Just log the error silently
            Log::error('Email failed: ' . $e->getMessage());
        }
    }

    // Return live seat count as JSON (for Ajax polling)
    public function getSeatCount($id)
    {
        $event = Event::findOrFail($id);

        // Return JSON response
        return response()->json([
            'event_id'        => $event->id,
            'booked_count'    => $event->booked_count,
            'remaining_seats' => $event->remainingSeats(),
            'capacity'        => $event->capacity,
            'is_fully_booked' => $event->isFullyBooked(),
            'status'          => $event->status,
        ]);
    }
}