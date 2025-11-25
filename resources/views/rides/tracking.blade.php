<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Live Ride Tracking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Ride Status -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <div class="w-4 h-4 bg-green-500 rounded-full animate-ping absolute"></div>
                            <div class="w-4 h-4 bg-green-600 rounded-full relative"></div>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-green-600 mb-1" id="rideStatus">
                                @if($ride->status === 'accepted')
                                    🚗 Driver On The Way
                                @elseif($ride->status === 'started')
                                    🚦 Ride In Progress
                                @else
                                    📍 Ride Completed
                                @endif
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 flex items-center">
                                <span class="inline-block w-2 h-2 bg-red-500 rounded-full animate-pulse mr-2"></span>
                                LIVE TRACKING ACTIVE • Updates every 5 seconds
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Estimated Arrival</p>
                        <p class="text-3xl font-bold text-blue-600" id="eta">8 min</p>
                        <p class="text-xs text-gray-500" id="distance">2.3 km away</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Map -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div id="map" class="w-full h-96 rounded-t-lg"></div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-semibold text-gray-600">From:</span>
                                    <p class="truncate">{{ $ride->pickup_address }}</p>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-600">To:</span>
                                    <p class="truncate">{{ $ride->destination_address }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Driver Info -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold mb-4 flex items-center">
                                <span class="text-2xl mr-2">👤</span>
                                Driver Information
                            </h4>
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                    {{ substr($ride->driver->user->name ?? 'D', 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-lg">{{ $ride->driver->user->name ?? 'Driver' }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $ride->driver->vehicle_type ?? 'Vehicle' }} • {{ $ride->driver->vehicle_plate ?? 'ABC123' }}
                                    </p>
                                    <div class="flex items-center mt-1">
                                        <div class="flex text-yellow-400">
                                            ⭐⭐⭐⭐⭐
                                        </div>
                                        <span class="text-sm ml-2 font-semibold">{{ $ride->driver->rating ?? '5.0' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                                    <p class="text-blue-800 dark:text-blue-300 font-semibold">Contact</p>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $ride->driver->user->phone ?? '+63 912 345 6789' }}</p>
                                </div>
                                <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg">
                                    <p class="text-green-800 dark:text-green-300 font-semibold">Vehicle</p>
                                    <p class="text-gray-700 dark:text-gray-300 capitalize">{{ $ride->driver->vehicle_type ?? 'Standard' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Trip Stats -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold mb-4 flex items-center">
                                <span class="text-2xl mr-2">📊</span>
                                Trip Statistics
                            </h4>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Distance</span>
                                    <span class="font-semibold" id="liveDistance">2.3 km</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Duration</span>
                                    <span class="font-semibold" id="liveDuration">8 min</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Current Speed</span>
                                    <span class="font-semibold text-green-600" id="liveSpeed">45 km/h</span>
                                </div>
                                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 text-lg">Total Fare</span>
                                        <span class="text-2xl font-bold text-green-600">₱{{ $ride->fare }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Live Activity -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-lg font-semibold flex items-center">
                                    <span class="text-2xl mr-2">🔄</span>
                                    Live Activity
                                </h4>
                                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full animate-pulse">LIVE</span>
                            </div>
                            <div class="space-y-3 max-h-60 overflow-y-auto" id="updatesContainer">
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium">Driver accepted your ride request</p>
                                        <p class="text-xs text-gray-500">{{ $ride->accepted_at?->format('g:i A') ?? 'Just now' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Interactive Controls -->
            <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg p-6">
                <h4 class="text-lg font-semibold mb-4 flex items-center">
                    <span class="text-2xl mr-2">🎮</span>
                    Interactive Controls
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <button onclick="simulateDriverMoving()" class="bg-transparent border border-gray-300 hover:bg-gray-100 text-gray-800 font-bold py-3 px-4 rounded-lg flex items-center justify-center">
                        <span class="text-xl mr-2">🚗</span>Driver Moving
                    </button>
                    <button onclick="simulateTrafficUpdate()" class="bg-transparent border border-gray-300 hover:bg-gray-100 text-gray-800 font-bold py-3 px-4 rounded-lg flex items-center justify-center">
                        <span class="text-xl mr-2">🚦</span>Traffic Update
                    </button>
                    <button onclick="simulateDriverArrived()" class="bg-transparent border border-gray-300 hover:bg-gray-100 text-gray-800 font-bold py-3 px-4 rounded-lg flex items-center justify-center">
                        <span class="text-xl mr-2">🎯</span>Driver Arrived
                    </button>
                    <button onclick="simulateRideStart()" class="bg-transparent border border-gray-300 hover:bg-gray-100 text-gray-800 font-bold py-3 px-4 rounded-lg flex items-center justify-center">
                        <span class="text-xl mr-2">🚀</span>Start Ride
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet JS & CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        let driverPos = { lat: {{ $ride->pickup_lat }} + 0.01, lng: {{ $ride->pickup_lng }} + 0.01 };
        let pickupPos = { lat: {{ $ride->pickup_lat }}, lng: {{ $ride->pickup_lng }} };
        let destPos = { lat: {{ $ride->destination_lat }}, lng: {{ $ride->destination_lng }} };
        let currentETA = 8;
        let currentDistance = 2.3;
        let rideInProgress = false;

        const map = L.map('map').setView([driverPos.lat, driverPos.lng], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const pickupMarker = L.marker(pickupPos).addTo(map).bindPopup("Pickup Location");
        const destMarker = L.marker(destPos).addTo(map).bindPopup("Destination");

        const driverIcon = L.divIcon({
            className: 'driver-icon',
            html: `<div class="driver-marker"><span>🚗</span></div>`,
            iconSize: [40, 40],
            iconAnchor: [20, 20]
        });

        const driverMarker = L.marker(driverPos, {icon: driverIcon}).addTo(map).bindPopup("Driver");

        addMapControls();
        function addMapControls() {
            const controlDiv = L.control({ position: 'topright' });
            controlDiv.onAdd = function(map) {
                const div = L.DomUtil.create('div', 'leaflet-bar bg-white rounded-lg shadow-lg overflow-hidden');
                div.style.display = 'flex';
                div.style.flexDirection = 'column';
                const buttons = [
                    { label: '+', action: () => map.zoomIn() },
                    { label: '−', action: () => map.zoomOut() },
                    { label: 'Center', action: centerMap }
                ];
                buttons.forEach(btn => {
                    const b = L.DomUtil.create('a', 'leaflet-control-button', div);
                    b.innerHTML = btn.label;
                    b.href = '#';
                    b.style.cssText = 'text-align:center;font-weight:bold;padding:8px 12px;cursor:pointer;text-decoration:none;';
                    L.DomEvent.on(b, 'click', L.DomEvent.stopPropagation)
                              .on(b, 'click', L.DomEvent.preventDefault)
                              .on(b, 'click', btn.action);
                });
                return div;
            };
            controlDiv.addTo(map);
        }
        function centerMap() {
            const bounds = L.latLngBounds([driverPos, pickupPos, destPos]);
            map.fitBounds(bounds, {padding: [50, 50]});
        }

        function moveDriverSmoothly(targetPos, duration = 4000) {
            const start = {...driverPos};
            const deltaLat = targetPos.lat - start.lat;
            const deltaLng = targetPos.lng - start.lng;
            const startTime = performance.now();

            function animate(time) {
                const elapsed = time - startTime;
                const t = Math.min(1, elapsed / duration);
                driverPos.lat = start.lat + deltaLat * t;
                driverPos.lng = start.lng + deltaLng * t;
                driverMarker.setLatLng(driverPos);
                if (t < 1) requestAnimationFrame(animate);
            }
            requestAnimationFrame(animate);
        }

        function startLiveTracking() {
            setInterval(() => {
                if (rideInProgress) simulateProgressToDestination();
                else if (currentETA > 0) {
                    moveDriverSmoothly(pickupPos, 4000);
                    currentETA = Math.max(0, currentETA - 0.5);
                    currentDistance = Math.max(0.1, currentDistance - 0.1);
                    updateDisplay();
                    if (currentETA <= 1 && !rideInProgress) addUpdate('Driver is approaching your location');
                }
            }, 5000);
        }

        function simulateDriverMoving() {
            addUpdate('Driver is en route to your location');
            document.getElementById('rideStatus').textContent = '🚗 Driver On The Way';
        }

        function simulateTrafficUpdate() {
            const events = ['Light traffic','Moderate traffic','Clear road','Minor slowdown','Alternative route'];
            addUpdate('Traffic Update: '+events[Math.floor(Math.random()*events.length)]);
        }

        function simulateDriverArrived() {
            driverPos = {...pickupPos};
            driverMarker.setLatLng(driverPos);
            addUpdate('🎉 Driver has arrived at your location!');
            document.getElementById('rideStatus').textContent = '🎯 Driver Has Arrived';
            currentETA = 0;
            updateDisplay();
        }

        function simulateRideStart() {
            rideInProgress = true;
            addUpdate('🚀 Ride started! Heading to destination');
            document.getElementById('rideStatus').textContent = '🚦 Ride In Progress';
            currentETA = 12;
            currentDistance = getDistance(driverPos,destPos);
            updateDisplay();
        }

        function simulateProgressToDestination() {
            if(currentETA > 0){
                const progress = 0.08;
                driverPos.lat += (destPos.lat - driverPos.lat) * progress;
                driverPos.lng += (destPos.lng - driverPos.lng) * progress;
                driverMarker.setLatLng(driverPos);
                currentETA = Math.max(0, currentETA - 0.5);
                currentDistance = Math.max(0.1, currentDistance - 0.2);
                updateDisplay();
                if(currentETA <= 1) addUpdate('Almost at destination');
                if(currentETA <= 0) document.getElementById('rideStatus').textContent = '✅ Ride Completed';
            }
        }

        function updateDisplay() {
            document.getElementById('eta').textContent = Math.ceil(currentETA)+' min';
            document.getElementById('distance').textContent = currentDistance.toFixed(1)+' km away';
            document.getElementById('liveDistance').textContent = currentDistance.toFixed(1)+' km';
            document.getElementById('liveDuration').textContent = Math.ceil(currentETA)+' min';
            document.getElementById('liveSpeed').textContent = Math.floor(30+Math.random()*20)+' km/h';
        }

        function getDistance(p1,p2){
            const R = 6371;
            const dLat = (p2.lat-p1.lat)*Math.PI/180;
            const dLng = (p2.lng-p1.lng)*Math.PI/180;
            const a = Math.sin(dLat/2)**2 + Math.cos(p1.lat*Math.PI/180)*Math.cos(p2.lat*Math.PI/180)*Math.sin(dLng/2)**2;
            const c = 2*Math.atan2(Math.sqrt(a),Math.sqrt(1-a));
            return R*c;
        }

        function addUpdate(message){
            const container = document.getElementById('updatesContainer');
            const div = document.createElement('div');
            div.className='flex items-start space-x-3 update-item';
            div.innerHTML=`<div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                <div class="flex-1">
                    <p class="text-sm font-medium">${message}</p>
                    <p class="text-xs text-gray-500">${new Date().toLocaleTimeString()}</p>
                </div>`;
            container.prepend(div);
            while(container.children.length>10) container.removeChild(container.lastChild);
        }

        startLiveTracking();
    </script>

    <style>
        #map { box-shadow:0 10px 15px -3px rgba(0,0,0,0.1); }

        .update-item{animation:slideIn 0.5s ease-out;}
        @keyframes slideIn{from{opacity:0;transform:translateY(-20px);}to{opacity:1;transform:translateY(0);}}

        .driver-marker {
            width: 40px;
            height: 40px;
            background: #1E40AF;
            border: 2px solid #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
            color: white;
            font-size: 20px;
        }
        .driver-marker span { display:block; transform: translateY(-1px); }

        .leaflet-control-button:hover { background-color: #e5e7eb; }
    </style>
    
</x-app-layout>
