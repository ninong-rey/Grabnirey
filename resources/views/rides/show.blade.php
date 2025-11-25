<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ride Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Ride Status -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                <div class="text-6xl mb-4">🚗</div>
                <h3 class="text-2xl font-bold text-green-600 dark:text-green-400 mb-2">
                    @if($ride->status === 'accepted') Ride Confirmed! 
                    @elseif($ride->status === 'started') Ride In Progress!
                    @else Ride Completed!
                    @endif
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    @if($ride->status === 'accepted') Your driver is on the way
                    @elseif($ride->status === 'started') You are on your way
                    @else Thank you for riding with us!
                    @endif
                </p>
            </div>

            <!-- Live Tracking Map -->
            @if(in_array($ride->status, ['accepted','started']))
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div id="liveMap" class="w-full h-96 rounded-t-lg"></div>
                <div class="p-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 grid grid-cols-2 text-sm">
                    <div>
                        <span class="font-semibold text-gray-600">From:</span>
                        <p class="truncate">{{ $ride->pickup_address }}</p>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-600">To:</span>
                        <p class="truncate">{{ $ride->destination_address }}</p>
                    </div>
                </div>
                <div class="p-4 flex justify-between items-center text-sm bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                    <div>
                        <p class="font-semibold text-gray-600">Driver:</p>
                        @if($ride->driverUser && $ride->driverUser->driver)
                            <p class="font-semibold">{{ $ride->driverUser->name }}</p>
                            <p class="text-gray-600 dark:text-gray-400">
                                {{ $ride->driverUser->driver->vehicle_type }} • {{ $ride->driverUser->driver->vehicle_plate }}
                            </p>
                        @else
                            <p class="font-semibold text-yellow-600">Driver assigned soon</p>
                        @endif
                    </div>
                    <div>
                        <p class="font-semibold text-gray-600">Estimated Fare</p>
                        <p class="font-bold text-xl text-blue-600">₱{{ $ride->fare }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Ride Info Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Trip Details -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-lg font-semibold mb-4">Trip Details</h4>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">From</p>
                            <p class="font-semibold">{{ $ride->pickup_address }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">To</p>
                            <p class="font-semibold">{{ $ride->destination_address }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Driver</p>
                            @if($ride->driverUser && $ride->driverUser->driver)
                                <p class="font-semibold">{{ $ride->driverUser->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $ride->driverUser->driver->vehicle_type }} • {{ $ride->driverUser->driver->vehicle_plate }}
                                </p>
                            @else
                                <p class="font-semibold text-yellow-600">Driver assigned soon</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Ride Status & Fare -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-lg font-semibold mb-4">Ride Information</h4>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                            <p class="font-semibold capitalize text-green-600">{{ str_replace('_', ' ', $ride->status) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Fare</p>
                            <p class="text-2xl font-bold text-blue-600">₱{{ $ride->fare }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Booked At</p>
                            <p class="font-semibold">{{ $ride->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Section - Moved outside the grid -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment</h3>
                
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-600 dark:text-gray-400">Total Fare:</span>
                    <span class="text-2xl font-bold text-green-600">₱{{ $ride->fare }}</span>
                </div>

                @if($ride->payment_status === 'pending')
                    <div class="space-y-3">
                        <form action="{{ route('payment.create') }}" method="POST">
                            @csrf
                            <input type="hidden" name="ride_id" value="{{ $ride->id }}">
                            
                            <!-- PayPal Button -->
                            <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg flex items-center justify-center transition duration-200">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M7.5 14.5c-1.58 0-2.83.76-3.5 1.5v-9c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2v1.5c-1.5 0-2.5 1.2-2.5 2.5s1 2.5 2.5 2.5v1.5c0 1.1-.9 2-2 2h-9z"/>
                                </svg>
                                Pay with PayPal - ₱{{ $ride->fare }}
                            </button>
                        </form>
                        
                        <!-- Cash Payment -->
                        <button type="button" 
                                onclick="confirmCashPayment()"
                                class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg transition duration-200">
                            💵 Pay with Cash - ₱{{ $ride->fare }}
                        </button>
                    </div>
                @elseif($ride->payment_status === 'paid')
                    <div class="bg-green-100 dark:bg-green-900 border border-green-400 text-green-800 dark:text-green-200 px-4 py-3 rounded">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Payment Completed ({{ ucfirst($ride->payment_method) }})
                        </div>
                        @if($ride->paid_at)
                            <p class="text-sm mt-1">Paid on: {{ $ride->paid_at->format('M j, Y g:i A') }}</p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Ride Action Card -->
            <div class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-2xl p-6 max-w-md mx-auto text-center space-y-6">
                @if(in_array($ride->status, ['accepted', 'started']))
                    <div class="flex flex-col items-center space-y-2">
                        <div class="bg-blue-100 dark:bg-blue-900/20 p-4 rounded-full">
                            <span class="text-4xl">🗺️</span>
                        </div>
                        <h4 class="text-xl font-bold text-gray-800 dark:text-gray-200">Live Ride Tracking</h4>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Track your driver in real-time on the map</p>
                    </div>

                    <a href="{{ route('rides.tracking', $ride) }}" 
                       class="w-full inline-flex justify-center items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg text-lg">
                        <span class="mr-2 text-2xl">🚗</span>Track Your Ride
                    </a>
                @endif

                <a href="{{ route('dashboard') }}"
                   class="w-full inline-flex justify-center items-center bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold py-3 px-6 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md text-lg">
                    <span class="mr-2 text-xl">📘</span>Book Another Ride
                </a>
            </div>

            <!-- Rating Section -->
            @if($ride->status === 'completed')
                @include('rides.partials.rating', ['ride' => $ride])
            @endif
        </div>
    </div>

    <!-- Leaflet JS & CSS for Live Tracking -->
    @if(in_array($ride->status, ['accepted','started']))
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            let driverPos = { lat: {{ $ride->pickup_lat ?? 14.5995 }} + 0.01, lng: {{ $ride->pickup_lng ?? 120.9842 }} + 0.01 };
            let pickupPos = { lat: {{ $ride->pickup_lat ?? 14.5995 }}, lng: {{ $ride->pickup_lng ?? 120.9842 }} };
            let destPos = { lat: {{ $ride->destination_lat ?? 14.5547 }}, lng: {{ $ride->destination_lng ?? 121.0244 }} };
            let currentETA = 8;
            let currentDistance = 2.3;
            let rideInProgress = {{ $ride->status === 'started' ? 'true' : 'false' }};

            const map = L.map('liveMap').setView([driverPos.lat, driverPos.lng], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            const pickupMarker = L.marker(pickupPos).addTo(map).bindPopup("Pickup Location");
            const destMarker = L.marker(destPos).addTo(map).bindPopup("Destination");

            const driverIcon = L.divIcon({className:'driver-icon', html:`<div style="font-size:24px;">🚗</div>`});
            const driverMarker = L.marker(driverPos, {icon: driverIcon}).addTo(map).bindPopup("Driver");

            function moveDriverSmoothly(targetPos, duration = 4000) {
                const start = {...driverPos};
                const deltaLat = targetPos.lat - start.lat;
                const deltaLng = targetPos.lng - start.lng;
                const startTime = performance.now();

                function animate(time){
                    const elapsed = time - startTime;
                    const t = Math.min(1, elapsed/duration);
                    driverPos.lat = start.lat + deltaLat * t;
                    driverPos.lng = start.lng + deltaLng * t;
                    driverMarker.setLatLng(driverPos);
                    if(t<1) requestAnimationFrame(animate);
                }

                requestAnimationFrame(animate);
            }

            function startLiveTracking() {
                setInterval(()=>{
                    if(rideInProgress){
                        moveDriverSmoothly(destPos, 4000);
                        currentETA = Math.max(0, currentETA - 0.5);
                        currentDistance = Math.max(0.1, currentDistance - 0.2);
                    } else if(currentETA>0){
                        moveDriverSmoothly(pickupPos, 4000);
                        currentETA = Math.max(0, currentETA - 0.5);
                        currentDistance = Math.max(0.1, currentDistance - 0.1);
                    }
                }, 5000);
            }

            startLiveTracking();
        </script>
    @endif

    <script>
    function confirmCashPayment() {
        if (confirm('Confirm cash payment? The driver will collect ₱{{ $ride->fare }} when you complete the ride.')) {
            fetch('{{ route("payment.cash", $ride->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    }
    </script>
</x-app-layout>