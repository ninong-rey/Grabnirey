@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<h1 class="text-2xl font-bold mb-6">Dashboard</h1>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center">
        <h5 class="text-gray-500 dark:text-gray-300 uppercase text-xs font-medium tracking-wider mb-2">Total Rides</h5>
        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalRides }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center">
        <h5 class="text-gray-500 dark:text-gray-300 uppercase text-xs font-medium tracking-wider mb-2">Completed Rides</h5>
        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $completedRides }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center">
        <h5 class="text-gray-500 dark:text-gray-300 uppercase text-xs font-medium tracking-wider mb-2">Total Drivers</h5>
        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalDrivers }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center">
        <h5 class="text-gray-500 dark:text-gray-300 uppercase text-xs font-medium tracking-wider mb-2">Total Passengers</h5>
        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalPassengers }}</p>
    </div>
</div>
@endsection
