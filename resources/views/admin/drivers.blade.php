<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Drivers Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $drivers->total() }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Total Drivers</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ \App\Models\User::where('user_type', 'passenger')->count() }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Total Passengers</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ \App\Models\Ride::count() }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Total Rides</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ \App\Models\Ride::where('status', 'completed')->count() }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Completed Rides</div>
                    </div>
                </div>
            </div>

            <!-- Debug Info -->
            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded mb-6">
                <h4 class="font-semibold mb-2">📊 Drivers Overview</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div><strong>Total Drivers:</strong> {{ $drivers->total() }}</div>
                    <div><strong>Available:</strong> {{ \App\Models\Driver::where('status', 'available')->count() }}</div>
                    <div><strong>Busy:</strong> {{ \App\Models\Driver::where('status', 'busy')->count() }}</div>
                    <div><strong>Offline:</strong> {{ \App\Models\Driver::where('status', 'offline')->count() }}</div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">All Drivers</h3>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Page {{ $drivers->currentPage() }} of {{ $drivers->lastPage() }}
                        </div>
                    </div>

                    @if($drivers->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Driver</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vehicle Info</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rides</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Registered</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($drivers as $driver)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-mono text-gray-900 dark:text-white">
                                                #{{ $driver->id }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="ml-4">
                                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                            {{ $driver->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $driver->email }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                @if($driver->driver)
                                                    <div class="text-sm text-gray-900 dark:text-white">
                                                        <div class="font-medium">{{ $driver->driver->vehicle_type ?? 'N/A' }}</div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $driver->driver->vehicle_plate ?? 'No plate' }}
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $driver->driver->license_number ?? 'No license' }}
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                                        No Driver Profile
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                @if($driver->driver)
                                                    @php
                                                        $statusConfig = [
                                                            'available' => ['color' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300', 'text' => 'Available'],
                                                            'busy' => ['color' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300', 'text' => 'On Ride'],
                                                            'offline' => ['color' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300', 'text' => 'Offline']
                                                        ];
                                                        $status = $driver->driver->status ?? 'offline';
                                                        $config = $statusConfig[$status] ?? $statusConfig['offline'];
                                                    @endphp
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $config['color'] }}">
                                                        {{ $config['text'] }}
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                                        No Profile
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                                    {{ $driver->rides_as_driver_count ?? 0 }} rides
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $driver->created_at->format('M j, Y') }}
                                                <div class="text-xs">{{ $driver->created_at->format('g:i A') }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $drivers->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 dark:text-gray-500 mb-2">
                                <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400">No drivers found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>