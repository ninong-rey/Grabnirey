<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DriverController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DriverDashboardController;
use App\Http\Controllers\RideController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Homepage
Route::get('/', function () {
    return view('welcome');
});

// Dashboard redirect based on user type
Route::get('/dashboard', function () {
    if (Auth::user()->user_type === 'driver') {
        return redirect()->route('driver.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth routes
require __DIR__.'/auth.php';

// User logout route (POST)
Route::middleware('auth')->post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout.perform');

// Driver routes
Route::middleware(['auth'])->group(function () {
    Route::get('/driver/setup', [DriverController::class, 'create'])->name('driver.setup');
    Route::post('/driver/setup', [DriverController::class, 'store'])->name('driver.store-vehicle');

    Route::get('/driver/dashboard', [DriverDashboardController::class, 'dashboard'])->name('driver.dashboard');
    Route::post('/driver/toggle-status', [DriverDashboardController::class, 'toggleStatus'])->name('driver.toggle-status');
    Route::post('/driver/rides/{ride}/accept', [DriverDashboardController::class, 'acceptRide'])->name('driver.accept-ride');
    Route::post('/driver/rides/{ride}/decline', [DriverDashboardController::class, 'declineRide'])->name('driver.decline-ride');
    Route::get('/driver/check-requests', [DriverDashboardController::class, 'checkRequests'])->name('driver.check-requests');
    Route::get('/driver/active-ride', [DriverDashboardController::class, 'activeRide'])->name('driver.active-ride');
    Route::post('/driver/rides/{ride}/start', [DriverDashboardController::class, 'startRide'])->name('driver.start-ride');
    Route::post('/driver/rides/{ride}/complete', [DriverDashboardController::class, 'completeRide'])->name('driver.complete-ride');
    // REMOVED the duplicate rides.show route
});

// Ride & Rating routes
Route::middleware(['auth'])->group(function () {
    Route::post('/rides/book', [RideController::class, 'store'])->name('rides.book');
    Route::get('/rides/{ride}', [RideController::class, 'show'])->name('rides.show');
    Route::get('/rides/{ride}/driver-position', [RideController::class, 'driverPosition'])->name('rides.driver-position');
    Route::get('/rides/{ride}/tracking', [RideController::class, 'tracking'])->name('rides.tracking');

    Route::get('/rides/{ride}/rate', [RatingController::class, 'create'])->name('ratings.create');
    Route::post('/rides/{ride}/rate', [RatingController::class, 'store'])->name('ratings.store');
    Route::get('/users/{user}/ratings', [RatingController::class, 'showUserRatings'])->name('users.ratings');
});

// Test ratings
Route::get('/test-ratings', function () {
    $ride = \App\Models\Ride::with(['passenger', 'driver.user', 'ratings.rater'])
                          ->where('status', 'completed')
                          ->first();
    if (!$ride) return "No completed rides found";
    return view('rides.show', compact('ride'));
});

// -----------------------------
// Admin routes
// -----------------------------
// In routes/web.php - Update your admin routes:

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'login'])->name('login');
    Route::post('/login', [AdminController::class, 'authenticate'])->name('authenticate');

    // These will now be protected by the controller middleware
    Route::middleware(['web'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
        Route::get('/rides', [AdminController::class, 'rides'])->name('rides');
        Route::get('/rides/{ride}/track', [AdminController::class, 'trackRide'])->name('rides.track');
        Route::get('/passengers', [AdminController::class, 'passengers'])->name('passengers');
        Route::get('/drivers', [AdminController::class, 'drivers'])->name('drivers');
    });
});
// Payment Routes
// Ride Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/rides/book', [RideController::class, 'book'])->name('rides.book');
    Route::post('/rides/book', [RideController::class, 'store'])->name('rides.store');
    Route::get('/rides/{ride}', [RideController::class, 'show'])->name('rides.show');
    Route::get('/rides/{ride}/tracking', [RideController::class, 'tracking'])->name('rides.tracking');
    Route::get('/rides/{ride}/position', [RideController::class, 'driverPosition'])->name('rides.position');
    Route::get('/rides/history', [RideController::class, 'history'])->name('rides.history'); // Add this line
    
    // Payment Routes
    Route::post('/payment/create', [PaymentController::class, 'createPayment'])->name('payment.create');
    Route::get('/payment/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/cancel', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');
    Route::post('/payment/cash/{ride}', [PaymentController::class, 'cashPayment'])->name('payment.cash');
});


