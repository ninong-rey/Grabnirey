<x-guest-layout>
    <!-- Website Name Header -->
    <div class="text-center mb-6">
        <h1 class="grab-ni-rey-title">GRAB NI REY</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Welcome back! Please login to your account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if(session('error'))
        <div class="mb-4 text-red-600 text-sm">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" id="loginForm">
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
            <x-primary-button id="loginBtn" class="text-sm px-3 py-1.5 inline-flex items-center">
                <svg id="loginSpinner" class="hidden animate-spin h-4 w-4 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span id="loginText">{{ __('Log in') }}</span>
            </x-primary-button>
            
            <a href="#" id="registerLink" 
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
        <button onclick="openAdminModal()" id="adminMainBtn"
            class="px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-150 text-sm inline-flex items-center justify-center">
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
                <button onclick="verifyAdminPassword()" id="adminLoginBtn"
                    class="px-3 py-1.5 bg-red-600 text-white rounded hover:bg-red-700 transition duration-150 text-sm inline-flex items-center justify-center">
                    Login
                </button>
            </div>
        </div>
    </div>

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
        const BUILT_IN_ADMIN_PASSWORD = "admin123";

        // Show spinner for login button
        function showLoginSpinner(show) {
            const spinner = document.getElementById('loginSpinner');
            const text = document.getElementById('loginText');
            const button = document.getElementById('loginBtn');
            
            if (show) {
                spinner.classList.remove('hidden');
                text.textContent = 'Logging in...';
                button.disabled = true;
            } else {
                spinner.classList.add('hidden');
                text.textContent = 'Log in';
                button.disabled = false;
            }
        }

        // Handle Login Form Submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            showLoginSpinner(true);
            // Form will submit normally
        });

        // Handle Register Link Click
        document.getElementById('registerLink').addEventListener('click', function(e) {
            e.preventDefault();
            
            // Show spinner on register button
            const registerBtn = this;
            const originalHtml = registerBtn.innerHTML;
            registerBtn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Registering...';
            registerBtn.style.opacity = '0.7';
            registerBtn.style.cursor = 'not-allowed';
            
            // Redirect to register page after short delay
            setTimeout(function() {
                window.location.href = "{{ url('/register') }}";
            }, 500);
        });

        // Show spinner for admin login button in modal
        function showAdminSpinner(show) {
            const button = document.getElementById('adminLoginBtn');
            if (show) {
                button.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Logging in...';
                button.disabled = true;
            } else {
                button.innerHTML = 'Login';
                button.disabled = false;
            }
        }

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
                showAdminSpinner(true);
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