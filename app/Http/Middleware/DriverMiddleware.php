<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->user_type !== 'driver') {
            return redirect()->route('dashboard')->with('error', 'Access denied. Driver account required.');
        }

        if (!Auth::user()->driver) {
            return redirect()->route('driver.setup')->with('error', 'Please complete your driver profile first.');
        }

        return $next($request);
    }
}