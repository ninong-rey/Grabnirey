<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Active Ride') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Ride Status Progress -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="text-center">
                        <div class="flex justify-center mb-6">
                            <div class="flex items-center">
                                <div class="flex flex-col items-center {{ $ride->status === 'accepted' ? 'text-blue-600' : 'text-gray-400' }}">
                                    <div class="w-10 h-10 rounded-full border-2 {{ $ride->status === 'accepted' ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300' }} flex items-center justify-center">
                                        1
                                    </div>
                                    <span class="mt-2 text-sm">Accepted</span>
                                </div>
                                <div class="h-1 w-20 bg-gray-300 mx-2"></div>
                                <div class="flex flex-col items-center {{ $ride->status === 'started' ? 'text-green-600' : 'text-gray-400' }}">
                                    <div class="w-10 h-10 rounded-full border-2 {{ $ride->status === 'started' ? 'border-green-600 bg-green-600 text-white' : 'border-gray-300' }} flex items-center justify-center">
                                        2
                                    </div>
                                    <span class="mt-2 text-sm">Started</span>
                                </div>
                                <div class="h-1 w-20 bg-gray-300 mx-2"></div>
                                <div class="flex flex-col items-center {{ $ride->status === 'completed' ? 'text-green-600' : 'text-gray-400' }}">
                                    <div class="w-10 h-10 rounded-full border-2 {{ $ride->status === 'completed' ? 'border-green-600 bg-green-600 text-white' : 'border-gray-300' }} flex items-center justify-center">
                                        3
                                    </div>
                                    <span class="mt-2 text-sm">Completed</span>
                                </div>
                            </div>
                        </div>

                        <h3 class="text-2xl font-bold text-green-600 mb-2">
                            @if($ride->status === 'accepted')
                                🚗 Ready to Start Ride
                            @elseif($ride->status === 'started') 
                                🚦 Ride in Progress
                            @else
                                ✅ Ride Completed
                            @endif
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Ride Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Trip Information -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
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
                                <p class="text-sm text-gray-500 dark:text-gray-400">Passenger</p>
                                <p class="font-semibold">{{ $ride->passenger->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $ride->passenger->email }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fare & Actions -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold mb-4">Fare & Actions</h4>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Fare</p>
                                <p class="text-3xl font-bold text-green-600">₱{{ $ride->fare }}</p>
                            </div>
                            
                            <!-- Action Buttons Based on Status -->
                            @if($ride->status === 'accepted')
                                <form method="POST" action="{{ route('driver.start-ride', $ride) }}">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                                        🚀 Start Ride
                                    </button>
                                </form>
                            @elseif($ride->status === 'started')
                                <form method="POST" action="{{ route('driver.complete-ride', $ride) }}">
                                    @csrf
                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                                        ✅ Complete Ride
                                    </button>
                                </form>
                            @elseif($ride->status === 'completed')
                                <div class="text-center">
                                    <p class="text-green-600 font-semibold mb-4">🎉 Ride Completed Successfully!</p>
                                    <a href="{{ route('driver.dashboard') }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                                        Back to Dashboard
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ride Timeline -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="text-lg font-semibold mb-4">Ride Timeline</h4>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <div>
                                <p class="font-semibold">Ride Requested</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $ride->created_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                        @if($ride->accepted_at)
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="font-semibold">Ride Accepted</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $ride->accepted_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                        @endif
                        @if($ride->started_at)
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                            <div>
                                <p class="font-semibold">Ride Started</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $ride->started_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                        @endif
                        @if($ride->completed_at)
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <div>
                                <p class="font-semibold">Ride Completed</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $ride->completed_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>