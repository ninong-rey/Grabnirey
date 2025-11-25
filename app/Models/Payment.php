<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ride_id',
        'amount',
        'payment_method',
        'status'
    ];

    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }
}
