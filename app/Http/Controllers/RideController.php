<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RideController extends Controller
{
    /**
     * Show the ride booking form
     */
    public function book()
{
    // Use direct query instead of relationship
    $pendingRides = \App\Models\Ride::where('passenger_id', Auth::id())
                    ->where('payment_status', 'pending')
                    ->where('status', '!=', 'cancelled')
                    ->latest()
                    ->get();
            
    return view('rides.book', compact('pendingRides'));
}

    public function store(Request $request)
    {
        $request->validate([
            'pickup_address' => ['required', 'string', 'max:255'],
            'destination_address' => ['required', 'string', 'max:255'],
            'vehicle_type' => ['required', 'in:sedan,suv,motorcycle'],
        ]);

        // Calculate fare based on vehicle type and random distance
        $baseFares = [
            'sedan' => 150,
            'suv' => 200, 
            'motorcycle' => 80
        ];
            
        $fare = $baseFares[$request->vehicle_type] + rand(20, 100);

        // Find available driver
        $driver = Driver::where('vehicle_type', $request->vehicle_type)
                        ->where('status', 'available')
                        ->inRandomOrder()
                        ->first();

        if (!$driver) {
            return back()->with('error', 'No drivers available at the moment. Please try again.');
        }

        // Create the ride with payment status
        $ride = Ride::create([
            'passenger_id' => Auth::id(),
            'driver_id' => $driver->id,
            'pickup_address' => $request->pickup_address,
            'pickup_lat' => 14.5995 + (rand(-100, 100) / 1000), // Mock coordinates
            'pickup_lng' => 120.9842 + (rand(-100, 100) / 1000),
            'destination_address' => $request->destination_address,
            'destination_lat' => 14.5995 + (rand(-100, 100) / 1000),
            'destination_lng' => 120.9842 + (rand(-100, 100) / 1000),
            'fare' => $fare,
            'status' => 'accepted',
            'payment_status' => 'pending', // Add payment status
            'accepted_at' => now(),
        ]);

        // Update driver status
        $driver->update(['status' => 'busy']);

        return redirect()->route('rides.show', $ride)->with('success', 'Ride booked successfully! Driver is on the way.');
    }

    public function show(Ride $ride)
    {
        // Ensure the authenticated user owns this ride or is admin
        if ($ride->passenger_id !== Auth::id() && Auth::user()->user_type !== 'admin') {
            abort(403);
        }

        // Load relationships
        $ride->load([
            'passenger',
            'driver.user'
        ]);

        return view('rides.show', compact('ride'));
    }

    public function tracking(Ride $ride)
    {
        // Load necessary relationships
        $ride->load([
            'passenger',
            'driver.user',
            'ratings'
        ]);

        return view('rides.tracking', compact('ride'));
    }

    public function driverPosition(Ride $ride)
    {
        // Get the driver assigned to this ride
        $driver = $ride->driver;

        // Return latest GPS coordinates and speed
        return response()->json([
            'lat' => $driver->current_lat ?? 14.5995,
            'lng' => $driver->current_lng ?? 120.9842,
            'speed' => $driver->speed ?? 40
        ]);
    }

    /**
     * Show ride history for passenger
     */
    public function history()
{
    // Use direct query instead of relationship
    $rides = \App\Models\Ride::where('passenger_id', Auth::id())
                    ->with(['driver.user'])
                    ->latest()
                    ->paginate(10);

    return view('rides.history', compact('rides'));
}
}