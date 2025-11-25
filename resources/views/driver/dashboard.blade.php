<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Driver Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-2xl font-bold text-green-600 mb-2">🚗 Welcome, {{ Auth::user()->name }}!</h3>
                            <p class="text-gray-600 dark:text-gray-400">Ready to start accepting rides?</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Current Status</p>
                            <div class="flex items-center gap-4">
                                <span id="statusIndicator" class="px-3 py-1 rounded-full text-sm font-semibold 
                                    {{ Auth::user()->driver->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(Auth::user()->driver->status) }}
                                </span>
                                <button id="toggleStatus" 
                                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-colors">
                                    {{ Auth::user()->driver->status === 'available' ? 'Go Offline' : 'Go Online' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Real Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h4 class="text-lg font-semibold mb-2">💰 Total Earnings</h4>
                    <p class="text-2xl font-bold text-green-600">₱{{ number_format($totalEarnings, 2) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h4 class="text-lg font-semibold mb-2">📊 Completed Rides</h4>
                    <p class="text-2xl font-bold text-blue-600">{{ $completedRides }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h4 class="text-lg font-semibold mb-2">⭐ Rating</h4>
                    <p class="text-2xl font-bold text-yellow-600">{{ number_format(Auth::user()->driver->rating, 1) }}</p>
                </div>
            </div>

            <!-- Recent Rides -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold mb-4">🕒 Recent Rides</h4>
                    
                    @if($recentRides->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentRides as $ride)
                                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold">{{ $ride->pickup_address }} → {{ $ride->destination_address }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                {{ $ride->created_at->format('M j, Y g:i A') }} • 
                                                <span class="capitalize {{ $ride->status === 'completed' ? 'text-green-600' : 'text-blue-600' }}">
                                                    {{ str_replace('_', ' ', $ride->status) }}
                                                </span>
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                Passenger: {{ $ride->passenger->name }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-lg">₱{{ $ride->fare }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400 text-center py-4">No rides yet. Go online to start receiving ride requests!</p>
                    @endif
                </div>
            </div>

            <!-- Ride Requests (Show when online) -->
@if(Auth::user()->driver->status === 'available' && $pendingRides->count() > 0)
<div class="bg-yellow-50 dark:bg-yellow-900/20 border-2 border-yellow-400 dark:border-yellow-500 rounded-lg">
    <div class="p-6">
        <h4 class="text-lg font-semibold text-yellow-800 dark:text-yellow-400 mb-4">🚨 New Ride Requests</h4>
        
        <div class="space-y-4">
            @foreach($pendingRides as $ride)
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border-2 border-yellow-400 dark:border-yellow-500 shadow-md">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold">{{ $ride->pickup_address }} → {{ $ride->destination_address }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Passenger: {{ $ride->passenger->name }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $ride->created_at->format('M j, Y g:i A') }} • 
                                <span class="capitalize {{ $ride->status === 'requested' ? 'text-yellow-600 font-semibold' : 'text-blue-600' }}">
                                    {{ str_replace('_', ' ', $ride->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-lg text-green-600">₱{{ $ride->fare }}</p>
                            <div class="flex gap-2 mt-2">
    <form method="POST" action="{{ route('driver.accept-ride', $ride) }}" class="accept-form">
        @csrf
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-md transition-colors accept-btn">
            Accept
        </button>
    </form>
    <form method="POST" action="{{ route('driver.decline-ride', $ride) }}">
        @csrf
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-md transition-colors">
            Decline
        </button>
    </form>
</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

            <!-- Action Buttons -->
            <div class="text-center mt-6">
                <a href="{{ route('dashboard') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg">
                    Back to Main Dashboard
                </a>
            </div>
        </div>
    </div>

    <script>
        // Toggle driver status
        document.getElementById('toggleStatus').addEventListener('click', function() {
            const button = this;
            const originalText = button.textContent;
            
            // Show loading state
            button.textContent = 'Updating...';
            button.disabled = true;
            
            fetch('{{ route("driver.toggle-status") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload to show updated status
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                button.textContent = originalText;
                button.disabled = false;
            });
        });

        // Auto-refresh for new ride requests (every 10 seconds when online)
        @if(Auth::user()->driver->status === 'available')
        setInterval(() => {
            fetch('{{ route("driver.check-requests") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.has_new_requests) {
                        location.reload();
                    }
                });
        }, 10000);
        @endif
    </script>
</x-app-layout>