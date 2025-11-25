<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - @yield('title')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Splash Screen -->
    <div id="splash" class="fixed inset-0 bg-white flex justify-center items-center z-50">
        <img src="{{ asset('images/logompc.png') }}" alt="Logo" class="w-40 h-40 animate-bounce">
    </div>

    <!-- Main Content -->
    <div id="mainContent" class="hidden flex-1 flex flex-col">

        <!-- Navbar -->
        @include('components.admin-nav')

        <main class="flex-1 p-6">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white text-center py-4 shadow">
            &copy; {{ date('Y') }} Admin Panel
        </footer>
    </div>

    <script>
        window.addEventListener('load', () => {
            setTimeout(() => {
                const splash = document.getElementById('splash');
                splash.style.opacity = '0';
                splash.style.transition = 'opacity 0.5s ease';
                setTimeout(() => splash.style.display = 'none', 500);
                document.getElementById('mainContent').classList.remove('hidden');
            }, 3000); // 3 seconds delay
        });
    </script>

</body>
</html>
