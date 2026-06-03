<?php
// app/Models/Booking.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'user_id',
        'event_id',
        'seats',
        'status',
        'confirmation_code',
    ];

    // Relationship: booking belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: booking belongs to an event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // OOP method: generate unique confirmation code
    public static function generateCode(): string
    {
        return strtoupper('BK-' . uniqid());
    }
}