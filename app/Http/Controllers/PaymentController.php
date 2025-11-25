<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ride;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        $request->validate([
            'ride_id' => 'required|exists:rides,id'
        ]);

        $ride = Ride::where('id', $request->ride_id)
                    ->where('passenger_id', Auth::id())
                    ->firstOrFail();

        // Check if ride is already paid
        if ($ride->payment_status === 'paid') {
            return redirect()->route('rides.show', $ride->id)
                           ->with('error', 'This ride has already been paid.');
        }

        // For now, we'll simulate PayPal payment
        // In production, integrate with actual PayPal SDK
        return $this->simulatePayPalPayment($ride);
    }

    private function simulatePayPalPayment(Ride $ride)
    {
        // Simulate successful payment
        $ride->update([
            'payment_status' => 'paid',
            'payment_method' => 'paypal',
            'payment_id' => 'PP_' . uniqid(),
            'paid_at' => now()
        ]);

        return redirect()->route('rides.show', $ride->id)
                       ->with('success', 'Payment completed successfully via PayPal!');
    }

    public function paymentSuccess(Request $request)
    {
        // Handle actual PayPal success callback
        $rideId = session('current_ride_id');
        $ride = Ride::findOrFail($rideId);
        
        $ride->update([
            'payment_status' => 'paid',
            'payment_method' => 'paypal',
            'payment_id' => $request->paymentId,
            'paid_at' => now()
        ]);

        return redirect()->route('rides.show', $ride->id)
                       ->with('success', 'Payment completed successfully!');
    }

    public function paymentCancel()
    {
        return redirect()->route('rides.index')
                       ->with('error', 'Payment was cancelled.');
    }

    public function cashPayment($rideId)
    {
        $ride = Ride::where('id', $rideId)
                    ->where('passenger_id', Auth::id())
                    ->firstOrFail();

        // Check if ride is already paid
        if ($ride->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'This ride has already been paid.'
            ]);
        }

        $ride->update([
            'payment_status' => 'paid',
            'payment_method' => 'cash',
            'paid_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cash payment confirmed!'
        ]);
    }

    public function paymentHistory()
{
    // Use direct query instead of relationship
    $payments = \App\Models\Ride::where('passenger_id', Auth::id())
                    ->whereIn('payment_status', ['paid', 'pending'])
                    ->with(['driver.user'])
                    ->latest()
                    ->paginate(10);

    return view('payment.history', compact('payments'));
}
}