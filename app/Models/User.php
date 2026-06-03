<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

// User class extends Authenticatable (OOP inheritance)
class User extends Authenticatable
{
    protected $table = 'users';

    // These fields can be mass-assigned (filled via forms)
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    // Hide password from arrays/JSON
    protected $hidden = ['password'];

    // Helper method: check if user is admin (OOP method)
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}