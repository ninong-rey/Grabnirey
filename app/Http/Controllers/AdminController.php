<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ride;

class AdminController extends Controller
{
    // Show login page
    public function login(Request $request)
    {
        if ($request->session()->get('is_admin')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    // Handle login
    public function authenticate(Request $request)
{
    $request->validate([
        'password' => 'required|string',
    ]);

    $password = 'admin123'; // Consider moving this to .env

    if ($request->password === $password) {
        $request->session()->regenerate(); // Regenerate session ID
        session(['is_admin' => true]);
        return redirect()->route('admin.dashboard');
    }

    return back()->withErrors(['password' => 'Invalid password']);
}

    // Admin logout
    public function logout(Request $request)
    {
        $request->session()->forget('is_admin');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to main user login page instead of admin login
        return redirect()->route('login'); // This points to your main login route
    }

    // Admin dashboard
    public function dashboard()
    {
        $totalRides = Ride::count();
        $completedRides = Ride::where('status', 'completed')->count();
        $totalPassengers = User::where('user_type', 'passenger')->count();
        $totalDrivers = User::where('user_type', 'driver')->count();

        return view('admin.dashboard', compact(
            'totalRides', 'completedRides', 'totalPassengers', 'totalDrivers'
        ));
    }

    // Example resource pages
    public function drivers()
{
    $drivers = User::where('user_type', 'driver')
               ->with(['driver'])
               ->withCount(['ridesAsDriver'])
               ->latest()
               ->paginate(10);
    
    return view('admin.drivers', compact('drivers'));
}

    public function passengers()
{
    $passengers = User::where('user_type', 'passenger')
                     ->withCount(['ridesAsPassenger'])
                     ->latest()
                     ->paginate(10);
    
    return view('admin.passengers', compact('passengers'));
}

   public function rides()
{
    $rides = Ride::with(['passenger', 'driver.user']) // Add .user to access driver's user info
                ->latest()
                ->paginate(10);
    
    return view('admin.rides', compact('rides'));
}
public function trackRide($rideId)
    {
        $ride = Ride::with(['passenger', 'driver.user'])->findOrFail($rideId);
        return view('admin.tracking', compact('ride'));
    }
    public function payments()
{
    $payments = Ride::with(['passenger', 'driver.user'])
                   ->whereIn('payment_status', ['paid', 'pending'])
                   ->latest()
                   ->paginate(15);

    $stats = [
        'total_earnings' => Ride::where('payment_status', 'paid')->sum('fare'),
        'pending_payments' => Ride::where('payment_status', 'pending')->count(),
        'completed_payments' => Ride::where('payment_status', 'paid')->count(),
    ];

    return view('admin.payments', compact('payments', 'stats'));
}

public function paymentDetails($rideId)
{
    $payment = Ride::with(['passenger', 'driver.user'])
                  ->findOrFail($rideId);

    return view('admin.payment-details', compact('payment'));
}
}
