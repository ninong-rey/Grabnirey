<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create passengers
        User::create([
            'name' => 'John Passenger',
            'email' => 'passenger@example.com',
            'password' => Hash::make('password'),
            'user_type' => 'passenger',
            'phone' => '+1234567890',
        ]);

        User::create([
            'name' => 'Sarah Traveler', 
            'email' => 'sarah@example.com',
            'password' => Hash::make('password'),
            'user_type' => 'passenger',
            'phone' => '+1234567891',
        ]);

        // Create drivers
        User::create([
            'name' => 'Mike Driver',
            'email' => 'driver@example.com', 
            'password' => Hash::make('password'),
            'user_type' => 'driver',
            'phone' => '+1234567892',
        ]);

        User::create([
            'name' => 'Lisa Chauffeur',
            'email' => 'lisa@example.com',
            'password' => Hash::make('password'), 
            'user_type' => 'driver',
            'phone' => '+1234567893',
        ]);

        // Create more test users
        User::factory(10)->create([
            'user_type' => 'passenger'
        ]);

        User::factory(5)->create([
            'user_type' => 'driver' 
        ]);
    }
}