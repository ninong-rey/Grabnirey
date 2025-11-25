<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if(session('error'))
        <div class="mb-4 text-red-600 text-sm">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Login & Register Buttons -->
        <div class="flex justify-between items-center mt-6">
            <x-primary-button class="text-sm px-3 py-1.5">
                {{ __('Log in') }}
            </x-primary-button>
            
            <a href="{{ url('/register') }}" 
               class="inline-flex items-center px-3 py-1.5 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150 text-sm">
                {{ __('Register') }}
            </a>
        </div>

        <!-- Forgot Password Link -->
        <div class="text-center mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-xs text-gray-600 hover:text-gray-900"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>
    </form>

    <!-- Admin Login Button -->
    <div class="mt-4 text-center">
        <button onclick="openAdminModal()"
            class="px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-150 text-sm">
            🔐 Admin Login
        </button>
    </div>

    <!-- Admin Password Modal -->
    <div id="adminModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-4 rounded-lg w-72 relative">
            <h2 class="text-md font-bold mb-3">Admin Password</h2>
            <input type="password" id="adminPassword" placeholder="Enter admin password"
                class="w-full p-2 border rounded mb-2 text-sm" />
            <p id="adminError" class="text-red-600 text-xs hidden mb-2">Invalid password</p>
            <div class="flex justify-end space-x-2">
                <button onclick="closeAdminModal()" class="px-3 py-1.5 bg-gray-300 rounded hover:bg-gray-400 transition duration-150 text-sm">Cancel</button>
                <button onclick="verifyAdminPassword()"
                    class="px-3 py-1.5 bg-red-600 text-white rounded hover:bg-red-700 transition duration-150 text-sm">Login</button>
            </div>
        </div>
    </div>

    <script>
        const BUILT_IN_ADMIN_PASSWORD = "admin123";

        function openAdminModal() {
            document.getElementById('adminModal').classList.remove('hidden');
            document.getElementById('adminPassword').focus();
        }

        function closeAdminModal() {
            document.getElementById('adminModal').classList.add('hidden');
            document.getElementById('adminPassword').value = "";
            document.getElementById('adminError').classList.add('hidden');
        }

        function verifyAdminPassword() {
            const pass = document.getElementById('adminPassword').value;
            if(pass === BUILT_IN_ADMIN_PASSWORD){
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('admin.authenticate') }}";
                const token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = "{{ csrf_token() }}";
                const passwordInput = document.createElement('input');
                passwordInput.type = 'hidden';
                passwordInput.name = 'password';
                passwordInput.value = pass;
                form.appendChild(token);
                form.appendChild(passwordInput);
                document.body.appendChild(form);
                form.submit();
            } else {
                document.getElementById('adminError').classList.remove('hidden');
            }
        }

        document.getElementById('adminModal').addEventListener('click', function(e) {
            if (e.target.id === 'adminModal') {
                closeAdminModal();
            }
        });

        document.getElementById('adminPassword').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                verifyAdminPassword();
            }
        });
    </script>
</x-guest-layout>