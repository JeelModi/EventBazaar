<?php
// app/Models/Event.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';

    protected $fillable = [
        'admin_id',
        'title',
        'description',
        'image_path',
        'event_date',
        'capacity',
        'booked_count',
        'price',
        'status',
    ];

    // Cast event_date as datetime automatically
    protected $casts = [
        'event_date' => 'datetime',
    ];

    // OOP method: check if event is fully booked
    public function isFullyBooked(): bool
    {
        return $this->booked_count >= $this->capacity;
    }

    // OOP method: get remaining seats
    public function remainingSeats(): int
    {
        return $this->capacity - $this->booked_count;
    }

    // Relationship: event belongs to an admin (User)
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Relationship: event has many bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}