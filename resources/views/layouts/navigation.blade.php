<!-- Settings Dropdown - COMPACT -->
<div class="hidden sm:flex sm:items-center sm:ms-6">
    <div class="relative" x-data="{ open: false }">
        <button 
            @click="open = !open"
            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150"
        >
            <div>{{ Auth::user()->name ?? 'Admin' }}</div>
            <div class="ms-1">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </div>
        </button>

        <!-- Compact dropdown -->
        <div 
            x-show="open"
            @click.away="open = false"
            x-cloak
            class="absolute right-0 z-50 mt-2 w-40 rounded-md bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg py-1"
            style="display: none;"
        >
            <!-- Profile Link -->
            <a 
                href="{{ route('profile.edit') }}" 
                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
            >
                {{ __('Profile') }}
            </a>

            <!-- Logout Form -->
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button 
                    type="submit"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                >
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</div>