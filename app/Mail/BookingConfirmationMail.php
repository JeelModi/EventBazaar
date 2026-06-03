<?php
// app/Mail/BookingConfirmationMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;
use App\Models\Event;
use App\Models\User;

class BookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    // Public properties are auto-available in the view
    public $booking;
    public $event;
    public $user;

    // Constructor receives booking data (OOP)
    public function __construct(Booking $booking, Event $event, User $user)
    {
        $this->booking = $booking;
        $this->event   = $event;
        $this->user    = $user;
    }

    // Email subject line
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Confirmed - ' . $this->event->title,
        );
    }

    // Which view to use for email body
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-confirmation',
        );
    }
}