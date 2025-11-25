<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen bg-gray-100">

    <!-- Sidebar -->
    <aside class="bg-gray-800 text-white w-64 flex-shrink-0">
        <div class="p-6 font-bold text-2xl border-b border-gray-700">
            Admin Panel
        </div>
        <ul class="mt-6">
            <li class="mb-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-6 py-3 hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                    Dashboard
                </a>
            </li>
            <li class="mb-2">
                <a href="{{ route('admin.rides') }}" class="block px-6 py-3 hover:bg-gray-700 {{ request()->routeIs('admin.rides') ? 'bg-gray-700' : '' }}">
                    Rides
                </a>
            </li>
            <li class="mb-2">
                <a href="{{ route('admin.drivers') }}" class="block px-6 py-3 hover:bg-gray-700 {{ request()->routeIs('admin.drivers') ? 'bg-gray-700' : '' }}">
                    Drivers
                </a>
            </li>
            <li class="mb-2">
                <a href="{{ route('admin.passengers') }}" class="block px-6 py-3 hover:bg-gray-700 {{ request()->routeIs('admin.passengers') ? 'bg-gray-700' : '' }}">
                    Passengers
                </a>
            </li>

<li class="mt-6">
    <form action="{{ route('admin.logout') }}" method="POST">
        @csrf
        <button type="submit" class="w-full text-left px-6 py-3 hover:bg-red-600">
            Logout
        </button>
    </form>
</li>


        </ul>
    </aside>

    <!-- Main content -->
    <main class="flex-1 p-6 overflow-y-auto">
        <h1 class="text-3xl font-bold mb-6">@yield('title')</h1>
        @yield('content')
    </main>

</body>
</html>
