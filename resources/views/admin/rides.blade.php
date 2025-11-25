<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Rides Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $rides->total() }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Total Rides</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ \App\Models\Ride::where('payment_status', 'paid')->count() }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Paid Rides</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ \App\Models\Ride::where('payment_status', 'pending')->count() }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Pending Payments</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ \App\Models\Ride::where('status', 'completed')->count() }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Completed</div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">All Rides</h3>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Page {{ $rides->currentPage() }} of {{ $rides->lastPage() }}
                        </div>
                    </div>

                    @if($rides->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Passenger</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Driver</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Route</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fare</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Payment</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($rides as $ride)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-mono text-gray-900 dark:text-white">
                                                #{{ $ride->id }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ $ride->passenger->name ?? 'N/A' }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $ride->passenger->email ?? '' }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                @if($ride->driver && $ride->driver->user)
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $ride->driver->user->name }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $ride->driver->vehicle_type ?? '' }}
                                                    </div>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                                        No Driver
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-white">
                                                    <div class="font-medium truncate max-w-xs">{{ $ride->pickup_address }}</div>
                                                    <div class="text-xs text-gray-500">→</div>
                                                    <div class="font-medium truncate max-w-xs">{{ $ride->destination_address }}</div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                                ₱{{ $ride->fare }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                @php
                                                    $statusConfig = [
                                                        'pending' => ['color' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300', 'text' => 'Pending'],
                                                        'accepted' => ['color' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300', 'text' => 'Accepted'],
                                                        'started' => ['color' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300', 'text' => 'Started'],
                                                        'completed' => ['color' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300', 'text' => 'Completed'],
                                                        'cancelled' => ['color' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300', 'text' => 'Cancelled']
                                                    ];
                                                    $config = $statusConfig[$ride->status] ?? ['color' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300', 'text' => ucfirst($ride->status)];
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $config['color'] }}">
                                                    {{ $config['text'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                @php
                                                    $paymentColors = [
                                                        'paid' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                                        'failed' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                                    ];
                                                    $paymentColor = $paymentColors[$ride->payment_status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $paymentColor }} capitalize">
                                                    {{ $ride->payment_status }}
                                                    @if($ride->payment_method)
                                                        <br><span class="text-xs">({{ $ride->payment_method }})</span>
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                @if($ride->driver && $ride->status !== 'completed' && $ride->status !== 'cancelled')
                                                    <a href="{{ route('admin.rides.track', $ride->id) }}" 
                                                       class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md transition duration-150">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        Track
                                                    </a>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 bg-gray-300 text-gray-600 text-xs font-semibold rounded-md cursor-not-allowed">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        Track
                                                    </span>
                                                @endif
                                                
                                                <a href="{{ route('rides.show', $ride->id) }}" 
                                                   class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-md transition duration-150">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $rides->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">No rides found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>