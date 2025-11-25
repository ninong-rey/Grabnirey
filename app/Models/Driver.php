<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'license_number', 
        'vehicle_type',
        'vehicle_plate',
        'status',
        'current_lat',
        'current_lng',
        'rating'
    ];

    // In app/Models/Driver.php
public function user()
{
    return $this->belongsTo(User::class);
}

    public function rides()
    {
        return $this->hasMany(Ride::class, 'driver_id');
    }
}
