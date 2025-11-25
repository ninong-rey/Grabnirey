<?php

namespace App\Events;

use App\Models\Ride;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RideStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ride;
    public $message;

    public function __construct(Ride $ride, $message = null)
    {
        $this->ride = $ride;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('ride.' . $this->ride->id);
    }

    public function broadcastWith()
    {
        return [
            'ride_id' => $this->ride->id,
            'status' => $this->ride->status,
            'message' => $this->message,
            'updated_at' => $this->ride->updated_at->toISOString()
        ];
    }
}