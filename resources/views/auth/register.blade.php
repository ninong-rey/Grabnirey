<x-guest-layout>
    <!-- Website Name Header -->
    <div class="text-center mb-6">
        <h1 class="grab-ni-rey-title">GRAB NI REY</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Create your account to get started</p>
    </div>

    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- User Type Selection -->
        <div class="mt-4">
            <x-input-label for="user_type" :value="__('I want to')" />
            <div class="flex gap-4 mt-2">
                <label class="flex items-center">
                    <input type="radio" name="user_type" value="passenger" {{ old('user_type', 'passenger') == 'passenger' ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-600">Book Rides (Passenger)</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="user_type" value="driver" {{ old('user_type') == 'driver' ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-600">Drive & Earn (Driver)</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('user_type')" class="mt-2" />
        </div>

        <!-- Phone Number -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" required autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button id="registerBtn" class="ms-4 inline-flex items-center">
                <svg id="registerSpinner" class="hidden animate-spin h-4 w-4 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span id="registerText">{{ __('Register') }}</span>
            </x-primary-button>
        </div>
    </form>

    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        
        /* Perfect size title - just right */
        .grab-ni-rey-title {
            font-size: 2.5rem;
            font-weight: 900;
            letter-spacing: 0.05em;
            background: linear-gradient(135deg, #1f2937 0%, #4b5563 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .grab-ni-rey-title {
                background: linear-gradient(135deg, #f3f4f6 0%, #9ca3af 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
        }
        
        /* Mobile size */
        @media (max-width: 640px) {
            .grab-ni-rey-title {
                font-size: 1.75rem;
            }
        }
    </style>

    <script>
        // Show spinner for register button
        function showRegisterSpinner(show) {
            const spinner = document.getElementById('registerSpinner');
            const text = document.getElementById('registerText');
            const button = document.getElementById('registerBtn');
            
            if (show) {
                spinner.classList.remove('hidden');
                text.textContent = 'Registering...';
                button.disabled = true;
            } else {
                spinner.classList.add('hidden');
                text.textContent = 'Register';
                button.disabled = false;
            }
        }

        // Handle Register Form Submission
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            showRegisterSpinner(true);
            // Form will submit normally
        });
    </script>
</x-guest-layout>