<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="h-9 w-auto" />
                    <span class="ml-2 font-bold text-gray-800 dark:text-gray-200">Admin Panel</span>
                </a>
            </div>

            <!-- Links & Profile -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                <a href="{{ route('admin.rides') }}" class="text-gray-700 hover:text-gray-900">Rides</a>

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center px-3 py-2 rounded-md text-gray-500 hover:text-gray-700">
                        {{ Auth::user()?->name ?? 'User' }}
                        <i class="fas fa-chevron-down ml-1"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white shadow-md rounded-md py-2 z-50">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                        <form method="POST" action="{{ route('logout.perform') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">Logout</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</nav>
