<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    /**
     * Show the driver setup form
     */
    public function create()
    {
        // Check if user is already a driver
        if (Auth::user()->driver) {
            return redirect()->route('dashboard')->with('info', 'You already have a driver profile.');
        }

        return view('auth.driver-setup');
    }

    /**
     * Store driver vehicle information
     */
    public function store(Request $request)
    {
        $request->validate([
            'license_number' => ['required', 'string', 'max:255', 'unique:drivers'],
            'vehicle_type' => ['required', 'in:sedan,suv,motorcycle'],
            'vehicle_plate' => ['required', 'string', 'max:20', 'unique:drivers'],
        ]);

        // Create driver profile
        Driver::create([
            'user_id' => Auth::id(),
            'license_number' => $request->license_number,
            'vehicle_type' => $request->vehicle_type,
            'vehicle_plate' => $request->vehicle_plate,
            'status' => 'offline',
            'rating' => 5.0,
        ]);

        return redirect()->route('dashboard')->with('success', 'Driver profile completed! You can now go online to accept rides.');
    }
}