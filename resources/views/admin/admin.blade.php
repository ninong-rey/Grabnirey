@extends('layouts.admin') {{-- assuming this is saved as resources/views/layouts/admin.blade.php --}}

@section('title', 'Rides')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Rides List</h2>
    <table class="min-w-full table-auto border border-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 border">ID</th>
                <th class="px-4 py-2 border">Driver</th>
                <th class="px-4 py-2 border">Passenger</th>
                <th class="px-4 py-2 border">Status</th>
                <th class="px-4 py-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rides as $ride)
            <tr class="hover:bg-gray-100">
                <td class="px-4 py-2 border">{{ $ride->id }}</td>
                <td class="px-4 py-2 border">{{ $ride->driver->name ?? 'N/A' }}</td>
                <td class="px-4 py-2 border">{{ $ride->passenger->name ?? 'N/A' }}</td>
                <td class="px-4 py-2 border">{{ $ride->status }}</td>
                <td class="px-4 py-2 border">
                    <a href="{{ route('admin.rides.track', $ride->id) }}" class="text-blue-600 hover:underline">Track</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
