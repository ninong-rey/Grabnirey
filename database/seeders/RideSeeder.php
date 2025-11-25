<?php

namespace Database\Seeders;

use App\Models\Ride;
use App\Models\User;
use Illuminate\Database\Seeder;

class RideSeeder extends Seeder
{
    public function run()
    {
        $passengers = User::where('user_type', 'passenger')->get();
        $drivers = User::where('user_type', 'driver')->get();

        $locations = [
            ['SM Mall of Asia', 14.5351, 120.9820],
            ['Bonifacio Global City', 14.5547, 121.0509],
            ['Makati CBD', 14.5542, 121.0244],
            ['Quezon City', 14.6760, 121.0437],
            ['Manila Airport', 14.5086, 121.0195],
        ];

        foreach ($passengers as $passenger) {
            $driver = $drivers->random();
            
            $pickup = $locations[rand(0, 4)];
            $destination = $locations[rand(0, 4)];
            
            // Make sure pickup and destination are different
            while ($destination === $pickup) {
                $destination = $locations[rand(0, 4)];
            }

            Ride::create([
                'passenger_id' => $passenger->id,
                'driver_id' => $driver->id,
                'pickup_address' => $pickup[0],
                'pickup_lat' => $pickup[1],
                'pickup_lng' => $pickup[2],
                'destination_address' => $destination[0],
                'destination_lat' => $destination[1], 
                'destination_lng' => $destination[2],
                'fare' => rand(150, 500),
                'status' => ['completed', 'in_progress', 'accepted'][rand(0, 2)],
                'accepted_at' => now()->subMinutes(rand(5, 30)),
                'started_at' => now()->subMinutes(rand(2, 20)),
                'completed_at' => now()->subMinutes(rand(1, 10)),
            ]);
        }
    }
}