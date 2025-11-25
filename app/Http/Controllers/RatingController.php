<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Ride;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function create(Ride $ride)
    {
        // Verify the user can rate this ride
        if ($ride->passenger_id !== Auth::id() && $ride->driver_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if ride is completed
        if ($ride->status !== 'completed') {
            return back()->with('error', 'You can only rate completed rides.');
        }

        // Check if user already rated this ride
        $existingRating = Rating::where('ride_id', $ride->id)
                               ->where('rater_id', Auth::id())
                               ->first();

        if ($existingRating) {
            return back()->with('info', 'You have already rated this ride.');
        }

        return view('ratings.create', compact('ride'));
    }

    public function store(Request $request, Ride $ride)
    {
        // Validate the rating
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:500'],
            'type' => ['required', 'in:driver,passenger']
        ]);

        // Verify the user can rate this ride
        if ($ride->passenger_id !== Auth::id() && $ride->driver_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if ride is completed
        if ($ride->status !== 'completed') {
            return back()->with('error', 'You can only rate completed rides.');
        }

        // Check if user already rated this ride
        $existingRating = Rating::where('ride_id', $ride->id)
                               ->where('rater_id', Auth::id())
                               ->first();

        if ($existingRating) {
            return back()->with('info', 'You have already rated this ride.');
        }

        // Determine who is being rated
        $ratedId = $request->type === 'driver' ? $ride->driver_id : $ride->passenger_id;

        // Create the rating
        $rating = Rating::create([
            'ride_id' => $ride->id,
            'rater_id' => Auth::id(),
            'rated_id' => $ratedId,
            'type' => $request->type,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        // Update the driver's average rating if rating is for driver
        if ($request->type === 'driver' && $ride->driver) {
            $this->updateDriverAverageRating($ride->driver);
        }

        return redirect()->route('rides.show', $ride)
                       ->with('success', 'Thank you for your rating! ⭐');
    }

    private function updateDriverAverageRating($driver)
    {
        $averageRating = Rating::where('rated_id', $driver->user_id)
                              ->where('type', 'driver')
                              ->avg('rating');

        $driver->update([
            'rating' => $averageRating ?? 5.0
        ]);
    }

    public function showUserRatings($userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        $ratings = $user->receivedRatings()->with(['rater', 'ride'])->latest()->get();

        return view('ratings.user-ratings', compact('user', 'ratings'));
    }
}