<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Splash Screen Styles */
        #splash-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2147483647;
            transition: opacity 0.5s ease;
        }

        .splash-logo {
            width: 200px;
            height: 200px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { 
                transform: scale(1); 
            }
            50% { 
                transform: scale(1.05); 
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            #splash-screen {
                background: #111827;
            }
        }

        /* Hide main content initially */
        #main-content {
            display: none;
        }

        /* Ensure smooth loading */
        .min-h-screen {
            opacity: 1;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Splash Screen -->
    <div id="splash-screen">
        <img src="{{ asset('images/logompc.png') }}" alt="Marine Polytechnic Logo" class="splash-logo">
    </div>

    <!-- Main Content -->
    <div id="main-content">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot ?? '' }}
            </main>
        </div>
    </div>

    <script>
        // Wait for the page to fully load
        window.addEventListener('load', function() {
            // Show splash immediately
            document.getElementById('splash-screen').style.display = 'flex';
            
            // After 3 seconds, fade out splash and show main content
            setTimeout(function() {
                const splash = document.getElementById('splash-screen');
                const mainContent = document.getElementById('main-content');
                
                if (splash && mainContent) {
                    // Add fade-out effect
                    splash.style.opacity = '0';
                    
                    // Show main content after fade starts
                    mainContent.style.display = 'block';
                    
                    // Remove splash from DOM after fade completes
                    setTimeout(function() {
                        if (splash.parentNode) {
                            splash.parentNode.removeChild(splash);
                        }
                    }, 500);
                }
            }, 1000); // 3 seconds delay
        });

        // Fallback: If something goes wrong, ensure main content shows after 5 seconds
        setTimeout(function() {
            const splash = document.getElementById('splash-screen');
            const mainContent = document.getElementById('main-content');
            
            if (splash && mainContent) {
                splash.style.display = 'none';
                mainContent.style.display = 'block';
            }
        }, 5000);
    </script>
</body>
</html>