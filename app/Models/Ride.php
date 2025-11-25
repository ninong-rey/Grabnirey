<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    use HasFactory;

    protected $fillable = [
        'passenger_id',
        'driver_id',
        'pickup_address',
        'pickup_lat',
        'pickup_lng', 
        'destination_address',
        'destination_lat',
        'destination_lng',
        'fare',
        'status',
        'accepted_at',
        'started_at',
        'completed_at', // ← ADDED MISSING COMMA HERE
        'payment_status',
        'payment_method',
        'payment_id',
        'paid_at'
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'paid_at' => 'datetime', // Add this for payment timestamp
        'fare' => 'decimal:2',   // Add this for fare casting
    ];

    // Ride belongs to a Passenger (User)
    public function passenger()
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }

    // Ride belongs to a Driver (User)
    public function driverUser()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    // Ride has one Driver through driverUser
    public function driver()
    {
        return $this->hasOneThrough(
            Driver::class,
            User::class,
            'id',        // Foreign key on users table
            'user_id',   // Foreign key on drivers table
            'driver_id', // Local key on rides table
            'id'         // Local key on users table
        );
    }

    // Ride has many ratings
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    // Rating for driver only
    public function driverRating()
    {
        return $this->hasOne(Rating::class)->where('type', 'driver');
    }

    // Rating for passenger only
    public function passengerRating()
    {
        return $this->hasOne(Rating::class)->where('type', 'passenger');
    }

    // Helper to get driver's average rating
    public function driverAverageRating()
    {
        if ($this->driver) {
            $avg = $this->driver->ratings()->where('type', 'driver')->avg('score');
            return $avg ? round($avg, 1) : 5.0;
        }
        return 5.0;
    }
}