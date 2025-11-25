<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'ride_id',
        'rater_id',
        'rated_type',
        'rating',
        'comment'
    ];

    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }

    public function rater()
    {
        return $this->belongsTo(User::class, 'rater_id');
    }
}
