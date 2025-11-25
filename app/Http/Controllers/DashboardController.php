<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverDashboardController extends Controller
{
    /**
     * Show driver dashboard with statistics
     */
    public function dashboard()
    {
        $driver = Auth::user()->driver;
        
        if (!$driver) {
            return redirect()->route('driver.setup')->with('error', 'Please complete your driver profile first.');
        }

        // Calculate total earnings from COMPLETED and PAID rides
        $totalEarnings = Ride::where('driver_id', $driver->id)
                            ->where('status', 'completed')
                            ->where('payment_status', 'paid')
                            ->sum('fare');

        // Count completed rides
        $completedRides = Ride::where('driver_id', $driver->id)
                             ->where('status', 'completed')
                             ->count();

        // Get recent rides (last 5)
        $recentRides = Ride::where('driver_id', $driver->id)
                          ->with(['passenger'])
                          ->latest()
                          ->take(5)
                          ->get();

        // Get pending ride requests
        $pendingRides = Ride::where('driver_id', $driver->id)
                           ->where('status', 'pending')
                           ->with(['passenger'])
                           ->get();

        return view('driver.dashboard', compact(
            'totalEarnings',
            'completedRides', 
            'recentRides',
            'pendingRides'
        ));
    }

    /**
     * Toggle driver online/offline status
     */
    public function toggleStatus(Request $request)
    {
        $driver = Auth::user()->driver;
        
        $driver->update([
            'status' => $driver->status === 'available' ? 'offline' : 'available'
        ]);

        return response()->json([
            'success' => true,
            'new_status' => $driver->status
        ]);
    }

    /**
     * Accept a ride request
     */
    public function acceptRide(Ride $ride)
    {
        // Verify the ride is assigned to this driver and is pending
        if ($ride->driver_id !== Auth::user()->driver->id || $ride->status !== 'pending') {
            return back()->with('error', 'Invalid ride request.');
        }

        $ride->update([
            'status' => 'accepted',
            'accepted_at' => now()
        ]);

        return back()->with('success', 'Ride accepted successfully!');
    }

    /**
     * Decline a ride request
     */
    public function declineRide(Ride $ride)
    {
        // Verify the ride is assigned to this driver
        if ($ride->driver_id !== Auth::user()->driver->id) {
            return back()->with('error', 'Invalid ride request.');
        }

        $ride->update([
            'status' => 'declined'
        ]);

        return back()->with('info', 'Ride declined.');
    }

    /**
     * Check for new ride requests
     */
    public function checkRequests()
    {
        $pendingRidesCount = Ride::where('driver_id', Auth::user()->driver->id)
                                ->where('status', 'pending')
                                ->count();

        return response()->json([
            'has_new_requests' => $pendingRidesCount > 0
        ]);
    }

    /**
     * Show active ride
     */
    public function activeRide()
    {
        $activeRide = Ride::where('driver_id', Auth::user()->driver->id)
                         ->whereIn('status', ['accepted', 'started'])
                         ->with(['passenger'])
                         ->first();

        return view('driver.active-ride', compact('activeRide'));
    }

    /**
     * Start a ride
     */
    public function startRide(Ride $ride)
    {
        if ($ride->driver_id !== Auth::user()->driver->id) {
            return back()->with('error', 'Invalid ride.');
        }

        $ride->update([
            'status' => 'started',
            'started_at' => now()
        ]);

        return back()->with('success', 'Ride started!');
    }

    /**
     * Complete a ride
     */
    public function completeRide(Ride $ride)
    {
        if ($ride->driver_id !== Auth::user()->driver->id) {
            return back()->with('error', 'Invalid ride.');
        }

        $ride->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        // Make driver available again
        Auth::user()->driver->update(['status' => 'available']);

        return back()->with('success', 'Ride completed successfully!');
    }
}