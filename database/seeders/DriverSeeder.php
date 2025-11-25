<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    public function run()
    {
        // Get driver users
        $driverUsers = User::where('user_type', 'driver')->get();

        foreach ($driverUsers as $user) {
            Driver::create([
                'user_id' => $user->id,
                'license_number' => 'DL' . rand(100000, 999999),
                'vehicle_type' => ['sedan', 'suv', 'motorcycle'][rand(0, 2)],
                'vehicle_plate' => 'ABC' . rand(100, 999),
                'status' => ['available', 'offline'][rand(0, 1)],
                'current_lat' => 14.5995 + (rand(-100, 100) / 1000), // Manila area coordinates
                'current_lng' => 120.9842 + (rand(-100, 100) / 1000),
                'rating' => rand(35, 50) / 10, // 3.5 to 5.0
            ]);
        }
    }
}