<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Live Ride Tracking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Ride Status Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-2xl font-bold text-green-600 mb-2" id="rideStatus">
                                @if($ride->status === 'accepted')
                                    🚗 Driver On The Way
                                @elseif($ride->status === 'started') 
                                    🚦 Ride In Progress
                                @else
                                    📍 Ride Completed
                                @endif
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400" id="statusMessage">
                                Tracking your ride in real-time...
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Estimated Arrival</p>
                            <p class="text-2xl font-bold text-blue-600" id="eta">5 min</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Map Container -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold mb-4">📍 Live Tracking Map</h4>
                            <div id="map" class="w-full h-96 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                <div class="text-center">
                                    <div class="text-4xl mb-4">🗺️</div>
                                    <p class="text-gray-600 dark:text-gray-400">Live map loading...</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">
                                        Driver location updates every 10 seconds
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ride Information -->
                <div class="space-y-6">
                    <!-- Driver Info -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold mb-4">👤 Driver Information</h4>
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xl">
                                    🚗
                                </div>
                                <div>
                                    <p class="font-semibold" id="driverName">{{ $ride->driver->user->name ?? 'Driver' }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $ride->driver->vehicle_type ?? 'Vehicle' }} • {{ $ride->driver->vehicle_plate ?? 'Plate' }}
                                    </p>
                                    <div class="flex items-center mt-1">
                                        <span class="text-yellow-500">⭐</span>
                                        <span class="text-sm ml-1">{{ $ride->driver->rating ?? '5.0' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Trip Details -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold mb-4">📋 Trip Details</h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">From</p>
                                    <p class="font-semibold" id="pickupAddress">{{ $ride->pickup_address }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">To</p>
                                    <p class="font-semibold" id="destinationAddress">{{ $ride->destination_address }}</p>
                                </div>
                                <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Fare</p>
                                    <p class="text-xl font-bold text-green-600">₱{{ $ride->fare }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Live Updates -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold mb-4">🔄 Live Updates</h4>
                            <div class="space-y-3" id="updatesContainer">
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                    <div>
                                        <p class="text-sm font-medium">Ride accepted</p>
                                        <p class="text-xs text-gray-500">{{ $ride->accepted_at?->format('g:i A') ?? 'Just now' }}</p>
                                    </div>
                                </div>
                                <!-- Updates will appear here dynamically -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 text-center">
                <a href="{{ route('rides.show', $ride) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg inline-block transition-colors">
                    View Ride Details
                </a>
            </div>
        </div>
    </div>

    <!-- JavaScript for Real-time Updates -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        // Initialize Pusher for real-time updates
        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
            encrypted: true
        });

        // Subscribe to ride channel
        const channel = pusher.subscribe('private-ride.{{ $ride->id }}');

        // Listen for status updates
        channel.bind('App\\Events\\RideStatusUpdated', function(data) {
            updateRideStatus(data);
        });

        // Listen for location updates
        channel.bind('App\\Events\\DriverLocationUpdated', function(data) {
            updateDriverLocation(data);
        });

        // Update ride status
        function updateRideStatus(data) {
            const statusElement = document.getElementById('rideStatus');
            const messageElement = document.getElementById('statusMessage');
            
            // Update status text based on ride status
            const statusMessages = {
                'accepted': '🚗 Driver On The Way',
                'started': '🚦 Ride In Progress', 
                'completed': '✅ Ride Completed'
            };

            if (statusElement) {
                statusElement.textContent = statusMessages[data.status] || data.status;
            }

            if (messageElement && data.message) {
                messageElement.textContent = data.message;
            }

            // Add to updates log
            addUpdate(data.message || `Status changed to ${data.status}`);
        }

        // Update driver location (simulated for now)
        function updateDriverLocation(data) {
            console.log('Driver location updated:', data);
            // In a real app, you would update the map here
            addUpdate(`Driver location updated`);
            
            // Simulate ETA calculation
            updateETA();
        }

        // Update ETA (simulated)
        function updateETA() {
            const etaElement = document.getElementById('eta');
            if (etaElement) {
                // Simulate ETA decreasing
                const currentETA = parseInt(etaElement.textContent) || 5;
                const newETA = Math.max(1, currentETA - 1);
                etaElement.textContent = newETA + ' min';
            }
        }

        // Add update to the updates container
        function addUpdate(message) {
            const updatesContainer = document.getElementById('updatesContainer');
            if (updatesContainer) {
                const updateElement = document.createElement('div');
                updateElement.className = 'flex items-start space-x-3';
                updateElement.innerHTML = `
                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                    <div>
                        <p class="text-sm font-medium">${message}</p>
                        <p class="text-xs text-gray-500">Just now</p>
                    </div>
                `;
                updatesContainer.prepend(updateElement);
            }
        }

        // Simulate real-time updates for demo
        setInterval(() => {
            // Simulate ETA updates
            updateETA();
        }, 30000); // Every 30 seconds

        // Initial simulation
        setTimeout(() => {
            addUpdate('Driver is 2 minutes away');
        }, 5000);

        setTimeout(() => {
            addUpdate('Driver has arrived at pickup location');
        }, 15000);
    </script>

    <style>
        #map {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .update-item {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</x-app-layout>