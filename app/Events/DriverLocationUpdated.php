<?php

namespace App\Events;

use App\Models\Ride;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DriverLocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ride;
    public $latitude;
    public $longitude;

    public function __construct(Ride $ride, $latitude, $longitude)
    {
        $this->ride = $ride;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('ride.' . $this->ride->id);
    }

    public function broadcastWith()
    {
        return [
            'ride_id' => $this->ride->id,
            'driver_lat' => $this->latitude,
            'driver_lng' => $this->longitude,
            'updated_at' => now()->toISOString()
        ];
    }
}