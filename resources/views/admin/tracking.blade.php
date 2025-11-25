@extends('admin.layout')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Ride Tracking</h2>

    <div class="bg-white shadow rounded-lg p-4">
        <h3 class="text-lg font-bold mb-2">Ride Details</h3>
        <p><strong>Passenger:</strong> {{ $ride->passenger->name ?? 'N/A' }}</p>
        <p><strong>Driver:</strong> {{ $ride->driver->user->name ?? 'N/A' }}</p>
        <p><strong>Pickup:</strong> {{ $ride->pickup_address }}</p>
        <p><strong>Destination:</strong> {{ $ride->destination_address }}</p>
        <p><strong>Status:</strong> {{ $ride->status }}</p>
        <p><strong>Fare:</strong> {{ $ride->fare }}</p>
    </div>

    <div class="mt-6">
        <h3 class="text-lg font-bold mb-2">Map Tracking</h3>
        <div id="map" class="w-full h-96 rounded-lg"></div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.rides') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            ← Back to Rides
        </a>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let pickupLat = {{ $ride->pickup_lat ?? 14.5995 }};
    let pickupLng = {{ $ride->pickup_lng ?? 120.9842 }};
    var map = L.map('map').setView([pickupLat, pickupLng], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
    L.marker([pickupLat, pickupLng]).addTo(map).bindPopup("Pickup Location").openPopup();
});
</script>
@endsection