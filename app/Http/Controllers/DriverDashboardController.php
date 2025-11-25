<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DriverDashboardController extends Controller
{
   public function dashboard()
{
    // Simple check to ensure user is a driver
    if (Auth::user()->user_type !== 'driver') {
        return redirect()->route('dashboard')->with('error', 'Access denied. Driver account required.');
    }

    // Get driver profile
    $driver = Auth::user()->driver;
    
    if (!$driver) {
        return redirect()->route('driver.setup')->with('error', 'Please complete your driver profile first.');
    }

    // Calculate REAL earnings
    $todayEarnings = Ride::where('driver_id', Auth::id())
                        ->where('status', 'completed')
                        ->whereDate('completed_at', today())
                        ->sum('fare');

    $weekEarnings = Ride::where('driver_id', Auth::id())
                       ->where('status', 'completed')
                       ->where('completed_at', '>=', Carbon::now()->startOfWeek())
                       ->sum('fare');

    $totalEarnings = Ride::where('driver_id', Auth::id())
                        ->where('status', 'completed')
                        ->sum('fare');

    // Ride statistics
    $completedRides = Ride::where('driver_id', Auth::id())
                         ->where('status', 'completed')
                         ->count();

    // Recent rides
    $recentRides = Ride::where('driver_id', Auth::id())
                      ->with(['passenger'])
                      ->orderBy('created_at', 'desc')
                      ->take(5)
                      ->get();

    // Pending ride requests (for demo - show requested rides that match vehicle type)
    $pendingRides = Ride::where('status', 'requested')
                       ->orWhere('status', 'accepted')
                       ->with(['passenger'])
                       ->orderBy('created_at', 'desc')
                       ->take(3)
                       ->get();

    return view('driver.dashboard', compact(
        'todayEarnings',
        'weekEarnings', 
        'totalEarnings',
        'completedRides',
        'recentRides',
        'pendingRides'
    ));
}

    public function toggleStatus(Request $request)
{
    $driver = Auth::user()->driver;
    
    $newStatus = $driver->status === 'available' ? 'offline' : 'available';
    
    $driver->update([
        'status' => $newStatus
    ]);

    return response()->json([
        'success' => true,
        'new_status' => $newStatus
    ]);
}

    public function acceptRide(Ride $ride)
{
    // Check if ride is available and driver is online
    if (Auth::user()->driver->status !== 'available') {
        return back()->with('error', 'You must be online to accept rides.');
    }

    // Check if ride is already taken
    if ($ride->status !== 'requested' && $ride->status !== 'accepted') {
        return back()->with('error', 'This ride is no longer available.');
    }

    // Accept the ride
    $ride->update([
        'status' => 'accepted',
        'driver_id' => Auth::id(),
        'accepted_at' => now()
    ]);

    // Update driver status to busy
    Auth::user()->driver->update(['status' => 'busy']);

    return redirect()->route('driver.dashboard')->with('success', 'Ride accepted! Navigate to pickup location.');
}

public function declineRide(Ride $ride)
{
    // Only allow declining requested rides
    if ($ride->status === 'requested') {
        $ride->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);
    }

    return back()->with('info', 'Ride declined.');
}
public function checkRequests()
    {
        $hasNewRequests = Ride::where('status', 'requested')
                             ->where(function($query) {
                                 $query->where('driver_id', Auth::id())
                                       ->orWhereNull('driver_id');
                             })
                             ->exists();

        return response()->json(['has_new_requests' => $hasNewRequests]);
    }
    public function activeRide()
{
    // Get the driver's current active ride
    $ride = Ride::where('driver_id', Auth::id())
                ->whereIn('status', ['accepted', 'started'])
                ->with(['passenger'])
                ->first();

    if (!$ride) {
        return redirect()->route('driver.dashboard')->with('error', 'No active ride found.');
    }

    return view('driver.active-ride', compact('ride'));
}

public function startRide(Ride $ride)
{
    // Verify the driver owns this ride and it's in accepted status
    if ($ride->driver_id !== Auth::id() || $ride->status !== 'accepted') {
        return back()->with('error', 'Cannot start this ride.');
    }

    $ride->update([
        'status' => 'started',
        'started_at' => now()
    ]);

    return redirect()->route('driver.active-ride')->with('success', 'Ride started! Safe driving!');
}

public function completeRide(Ride $ride)
{
    // Verify the driver owns this ride and it's in started status
    if ($ride->driver_id !== Auth::id() || $ride->status !== 'started') {
        return back()->with('error', 'Cannot complete this ride.');
    }

    // Calculate actual fare (could include additional charges)
    $actualFare = $ride->fare; // In real app, you might calculate based on distance/time

    $ride->update([
        'status' => 'completed',
        'completed_at' => now(),
        'actual_fare' => $actualFare,
        'payment_status' => 'paid'
    ]);

    // Update driver status back to available
    Auth::user()->driver->update(['status' => 'available']);

    return redirect()->route('driver.active-ride')->with('success', 'Ride completed! Payment received: ₱' . $actualFare);
}
}
