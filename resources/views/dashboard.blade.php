<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Book a Ride') }}
        </h2>
    </x-slot>

    <!-- Error and Success Messages -->
    @if ($errors->any())
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Pending Payments Section - Moved to top for importance -->
            @if(isset($pendingRides) && $pendingRides->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pending Payments</h3>
                        <span class="bg-red-500 text-white text-sm px-3 py-1 rounded-full">{{ $pendingRides->count() }}</span>
                    </div>
                    <div class="space-y-4">
                        @foreach($pendingRides as $ride)
                        <div class="flex justify-between items-center p-4 border border-red-200 dark:border-red-800 rounded-lg bg-red-50 dark:bg-red-900/20">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 dark:text-white">Ride #{{ $ride->id }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ $ride->pickup_address }} → {{ $ride->destination_address }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Booked: {{ $ride->created_at->format('M j, Y g:i A') }}
                                </p>
                            </div>
                            <div class="text-right ml-4">
                                <p class="text-lg font-bold text-green-600">₱{{ $ride->fare }}</p>
                                <a href="{{ route('rides.show', $ride->id) }}" 
                                   class="inline-block mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm transition-colors">
                                    Complete Payment
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Welcome Message -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center">
                        <div class="bg-blue-100 dark:bg-blue-900/20 p-3 rounded-full mr-4">
                            <span class="text-2xl">👋</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-1">Welcome back, {{ Auth::user()->name }}!</h3>
                            <p class="text-gray-600 dark:text-gray-400">Ready to book your next ride? Enter your pickup and destination below.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ride Booking Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('rides.book') }}" id="rideBookingForm">
                        @csrf
                        
                        <!-- Pickup Location -->
                        <div class="mb-6">
                            <label for="pickup_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                📍 Pickup Location
                            </label>
                            <input type="text" 
                                   id="pickup_address" 
                                   name="pickup_address" 
                                   required
                                   placeholder="Enter your pickup address..."
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors">
                        </div>

                        <!-- Destination Location -->
                        <div class="mb-6">
                            <label for="destination_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                🎯 Destination
                            </label>
                            <input type="text" 
                                   id="destination_address" 
                                   name="destination_address" 
                                   required
                                   placeholder="Where are you going?"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors">
                        </div>

                        <!-- Ride Type Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                🚗 Choose Your Ride
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="ride-type-option border-2 border-gray-200 dark:border-gray-600 rounded-lg p-4 cursor-pointer transition-all hover:border-blue-500 hover:shadow-md" data-type="sedan">
                                    <div class="text-center">
                                        <div class="text-3xl mb-2">🚗</div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white">Sedan</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Comfortable & affordable</p>
                                        <p class="text-lg font-bold text-green-600 mt-2">₱150+</p>
                                    </div>
                                </div>
                                <div class="ride-type-option border-2 border-gray-200 dark:border-gray-600 rounded-lg p-4 cursor-pointer transition-all hover:border-blue-500 hover:shadow-md" data-type="suv">
                                    <div class="text-center">
                                        <div class="text-3xl mb-2">🚙</div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white">SUV</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Extra space & comfort</p>
                                        <p class="text-lg font-bold text-green-600 mt-2">₱200+</p>
                                    </div>
                                </div>
                                <div class="ride-type-option border-2 border-gray-200 dark:border-gray-600 rounded-lg p-4 cursor-pointer transition-all hover:border-blue-500 hover:shadow-md" data-type="motorcycle">
                                    <div class="text-center">
                                        <div class="text-3xl mb-2">🏍️</div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white">Motorcycle</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Fast & economical</p>
                                        <p class="text-lg font-bold text-green-600 mt-2">₱80+</p>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="vehicle_type" name="vehicle_type" value="sedan">
                        </div>

                        <!-- Estimated Fare -->
                        <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Estimated Fare:</span>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Final fare may vary based on distance</p>
                                </div>
                                <span id="estimatedFare" class="text-3xl font-bold text-blue-600 dark:text-blue-400">₱150</span>
                            </div>
                        </div>

                        <!-- Book Ride Button -->
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-4 px-6 rounded-lg transition-all transform hover:scale-[1.02] text-lg shadow-lg">
                            🚗 Book Ride Now
                        </button>
                    </form>
                </div>
            </div>

            <!-- Recent Rides Section -->
            @if(Auth::user()->ridesAsPassenger->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Your Recent Rides</h3>
                        <span class="bg-gray-500 text-white text-sm px-3 py-1 rounded-full">{{ Auth::user()->ridesAsPassenger->count() }} total</span>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach(Auth::user()->ridesAsPassenger()->with(['driver.user'])->latest()->take(3)->get() as $ride)
                            <a href="{{ route('rides.show', $ride) }}" class="block border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all hover:shadow-md">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $ride->pickup_address }} → {{ $ride->destination_address }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            {{ $ride->created_at->format('M j, Y g:i A') }} • 
                                            <span class="capitalize font-medium 
                                                @if($ride->status === 'completed') text-green-600
                                                @elseif($ride->status === 'cancelled') text-red-600
                                                @else text-yellow-600 @endif">
                                                {{ str_replace('_', ' ', $ride->status) }}
                                            </span>
                                        </p>
                                        @if($ride->driver)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                Driver: {{ $ride->driver->user->name }} • {{ $ride->driver->vehicle_type }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-right ml-4">
                                        <p class="font-bold text-lg text-green-600">₱{{ $ride->fare }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 capitalize">
                                            @if($ride->payment_status === 'paid')
                                                <span class="text-green-600">✅ Paid</span>
                                            @else
                                                <span class="text-red-600">⏳ Pending</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    @if(Auth::user()->ridesAsPassenger->count() > 3)
                        <div class="text-center mt-4">
                            <a href="{{ route('rides.history') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                View All Rides →
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        // Ride type selection
        document.querySelectorAll('.ride-type-option').forEach(option => {
            option.addEventListener('click', function() {
                // Remove active class from all options
                document.querySelectorAll('.ride-type-option').forEach(opt => {
                    opt.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20', 'shadow-md');
                    opt.classList.add('border-gray-200', 'dark:border-gray-600');
                });
                
                // Add active class to clicked option
                this.classList.remove('border-gray-200', 'dark:border-gray-600');
                this.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20', 'shadow-md');
                
                // Update hidden input
                document.getElementById('vehicle_type').value = this.dataset.type;
                
                // Update estimated fare
                updateEstimatedFare(this.dataset.type);
            });
        });

        // Fare calculation based on vehicle type
        function updateEstimatedFare(vehicleType) {
            const fares = {
                'sedan': 150,
                'suv': 200,
                'motorcycle': 80
            };
            document.getElementById('estimatedFare').textContent = `₱${fares[vehicleType]}`;
        }

        // Form submission
        document.getElementById('rideBookingForm').addEventListener('submit', function(e) {
            const pickup = document.getElementById('pickup_address').value.trim();
            const destination = document.getElementById('destination_address').value.trim();
            
            if (!pickup || !destination) {
                e.preventDefault();
                alert('Please enter both pickup and destination locations.');
                return;
            }
            
            if (pickup === destination) {
                e.preventDefault();
                alert('Pickup and destination cannot be the same.');
                return;
            }
            
            // Show loading state
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.textContent;
            button.textContent = 'Finding drivers...';
            button.disabled = true;
            
            // Re-enable button after 5 seconds in case of error
            setTimeout(() => {
                button.textContent = originalText;
                button.disabled = false;
            }, 5000);
        });

        // Initialize first ride type as selected
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.ride-type-option').click();
            
            // Add emoji to input fields on focus
            const inputs = document.querySelectorAll('input[type="text"]');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-blue-200');
                });
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-blue-200');
                });
            });
        });
    </script>

    <style>
        .ride-type-option {
            transition: all 0.3s ease;
        }
        .ride-type-option:hover {
            transform: translateY(-3px);
        }
        .ride-type-option.active {
            transform: translateY(-2px);
        }
    </style>
</x-app-layout>