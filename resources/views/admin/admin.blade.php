<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="icon" type="image/png" href="{{ asset('asset/images/logo-iium.png') }}">
    <title>IIUM Smart Bus Tracker</title>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#72D0C8",
                        "background-light": "#E5E7E8",
                        "header-light": "#E5E7E8",
                        "footer-light": "#E5E7E8",
                        "text-dark": "#1A1A1A",
                        "border-light": "#D1D5DB"
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                        lg: "0.75rem",
                        xl: "1rem",
                        full: "9999px"
                    }
                },
            },
        }
    </script>
</head>
<body class="font-display bg-background-light backdrop-blur-sm dark:border-gray-700 text-text-dark dark:text-white">
<div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">
    <!-- Top App Bar -->
<header class="sticky top-0 z-[2000] flex items-center justify-between
    bg-header-light p-4 backdrop-blur-sm shadow-md
    dark:bg-background-dark dark:text-white">

    <div class="flex items-center justify-center w-full p-4 gap-3">
        <img src="{{ asset('asset/images/logo-iium.png') }}" alt="IIUM Logo" class="h-8 w-8 object-contain">
        <h1 class="text-lg sm:text-xl font-semibold whitespace-nowrap">IIUM Smart Bus Tracker</h1>
    </div>

    <!-- Menu Button (in header) -->
<div x-data="{ open: false }" class="relative">
  <button @click="open = !open"
          class="flex h-10 w-10 items-center justify-center rounded-full text-text-dark dark:text-white">
    <span class="material-symbols-outlined">menu</span>
  </button>

      <!-- Dropdown -->
      <div x-show="open" @click.away="open = false"
          x-transition:enter="transition ease-out duration-200"
          x-transition:enter-start="opacity-0 scale-95"
          x-transition:enter-end="opacity-100 scale-100"
          x-transition:leave="transition ease-in duration-150"
          x-transition:leave-start="opacity-100 scale-100"
          x-transition:leave-end="opacity-0 scale-95"
          class="absolute right-0 mt-2 w-48 rounded-lg border border-gray-300 dark:border-gray-700 bg-header-light dark:bg-background-dark shadow-lg z-50">
        <a href="{{ url('/admin') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Dashboard</a>
                <a href="{{ url('/admin/track') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Live Map & Routes</a>
                <a href="{{ url('/admin/schedule') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Schedule</a>
                <a href="{{ url('/businfo') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Bus Information</a>
                <a href="{{ url('/admin/report') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Report</a>
                <form method="POST" action="{{ url('/user/adminportal') }}">
                @csrf
                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">
                    Logout
                </button>
                </form>
            </div>
    </div>
    </header>

    <main class="flex-1 p-4 md:p-8">
        <h2 class="text-2xl font-bold mb-8">Admin Dashboard</h2>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-8">
            <div class="flex flex-col gap-2 rounded-xl border border-border-light bg-white p-6 dark:bg-gray-800">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Buses Active</p>
                <p class="text-3xl font-bold text-primary">{{ $busCount ?? 0 }}</p>
            </div>

        </div>

        <!-- Management Cards -->
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
    <!-- Live Tracking -->
    <div class="flex flex-col gap-4 rounded-xl border border-border-light bg-white p-6 dark:bg-gray-800">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/20 text-primary">
                <span class="material-symbols-outlined text-3xl">map</span>
            </div>
            <h3 class="font-bold text-lg">Live Bus Tracking</h3>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-300">View real-time location of all active buses.</p>
        <button onclick="window.location.href='/admin/track'"
                class="mt-auto w-full rounded-lg bg-primary text-white h-12 font-bold text-base hover:bg-primary/80 transition">
            View Live Map
        </button>
    </div>

    <!-- Manage Schedules -->
    <div class="flex flex-col gap-4 rounded-xl border border-border-light bg-white p-6 dark:bg-gray-800">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/20 text-primary">
                <span class="material-symbols-outlined text-3xl">schedule</span>
            </div>
            <h3 class="font-bold text-lg">Schedules</h3>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-300">View schedules and timings.</p>
        <button onclick="window.location.href='/admin/schedule'"
                class="mt-auto w-full rounded-lg bg-primary text-white h-12 font-bold text-base hover:bg-primary/80 transition">
            View Bus Schedules
        </button>
    </div>

    <!-- Bus & Driver Info -->
    <div class="flex flex-col gap-4 rounded-xl border border-border-light bg-white p-6 dark:bg-gray-800">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/20 text-primary">
                <span class="material-symbols-outlined text-3xl">directions_bus</span>
            </div>
            <h3 class="font-bold text-lg">Bus & Driver Info</h3>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-300">Update bus details and driver assignments.</p>
        <button onclick="window.location.href='/admin/businfo'"
                class="mt-auto w-full rounded-lg bg-primary text-white h-12 font-bold text-base hover:bg-primary/80 transition">
            Update Info
        </button>
    </div>

    <!-- Report -->
    <div class="flex flex-col gap-4 rounded-xl border border-border-light bg-white p-6 dark:bg-gray-800">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/20 text-primary">
                <span class="material-symbols-outlined text-3xl">report</span>
            </div>
            <h3 class="font-bold text-lg">Daily Report</h3>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-300">View daily report on the bus.</p>
        <button onclick="window.location.href='/admin/report'"
                class="mt-auto w-full rounded-lg bg-primary text-white h-12 font-bold text-base hover:bg-primary/80 transition">
            View Report
        </button>
    </div>
</div>
    </main>

    <!-- Footer -->
    <footer class="mt-10 border-t border-border-light bg-footer-light p-5 text-center dark:bg-gray-900">
        <p class="text-sm text-gray-500 dark:text-gray-400">© 2025 IIUM Smart Bus Tracker</p>
    </footer>

</div>
</body>
</html>
