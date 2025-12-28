<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="icon" type="image/png" href="{{ asset('asset/images/logo-iium.png') }}">
    <title>IIUM Smart Bus Tracker</title>

    <!--Alpine.js-->
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
                        "primary": "#72D0C8",            /* Teal buttons */
                        "background-light": "#E5E7E8",   /* Soft silver background */
                        "header-light": "#E5E7E8",       /* Light gray header */
                        "footer-light": "#E5E7E8",       /* Light gray footer */
                        "text-dark": "#1A1A1A",          /* Dark neutral text */
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                },
            },
        }
    </script>

</head>
<body class="font-display bg-background-light dark:bg-background-dark">
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
       x-transition
       class="absolute right-0 mt-2 w-48 rounded-lg border border-gray-300 dark:border-gray-700 bg-header-light dark:bg-background-dark shadow-lg"
       style="position: absolute; z-index: 9999;">
    <a href="/" class="block px-4 py-2 text-sm hover:bg-gray-200 dark:hover:bg-gray-700">Home</a>
    <a href="/track" class="block px-4 py-2 text-sm hover:bg-gray-200 dark:hover:bg-gray-700">Live Map & Routes</a>
    <a href="/schedule" class="block px-4 py-2 text-sm hover:bg-gray-200 dark:hover:bg-gray-700">Schedule</a>
    <a href="/adminportal" class="block px-4 py-2 text-sm hover:bg-gray-200 dark:hover:bg-gray-700">Admin Portal</a>
  </div>
</div>
</header>
    <main class="flex-1">
        <!-- Hero Section -->
        <section class="p-4 pt-10 sm:p-6 sm:pt-12 md:p-8 md:pt-16">
            <div class="mx-auto max-w-4xl text-center">
                <h1 class="font-display text-3xl font-bold leading-tight tracking-tighter text-slate-800 dark:text-white sm:text-5xl md:text-6xl">
                    Welcome to IIUM Smart Bus Tracker
                </h1>
                <p class="mt-4 text-base text-slate-600 dark:text-slate-300 sm:text-lg">
                    Find your bus easily with real-time tracking and up-to-date schedules.
                </p>
            </div>
        </section>

        <!-- Card-based Sections -->
        <div class="grid grid-cols-1 gap-4 p-4 sm:p-6 md:grid-cols-2 md:p-8 lg:max-w-6xl lg:mx-auto lg:gap-8">
            <!-- Track a Bus Card -->
            <div class="flex flex-col gap-4 rounded-xl border border-white/10 bg-white p-6 dark:bg-white/5 sm:p-8">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-primary/20 text-primary">
                        <span class="material-symbols-outlined text-3xl">map</span>
                    </div>
                    <div>
                        <h3 class="font-display text-xl font-bold text-slate-800 dark:text-white">Find Your Bus Now</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Follow your bus in real-time on the map.</p>
                    </div>
                </div>
                <button
                    class="flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-background-dark text-base font-bold leading-normal tracking-wide"
                    onclick="window.location.href='track'">
                    <span class="truncate">View Live Map</span>
                </button>
            </div>

            <!-- Bus Schedule Card -->
            <div class="flex flex-col gap-4 rounded-xl border border-white/10 bg-white p-6 dark:bg-white/5 sm:p-8">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-primary/20 text-primary">
                        <span class="material-symbols-outlined text-3xl">schedule</span>
                    </div>
                    <div>
                        <h3 class="font-display text-xl font-bold text-slate-800 dark:text-white">Schedule</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">View official timetables and plan your trips.</p>
                    </div>
                </div>
                <button
                    class="flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-background-dark text-base font-bold leading-normal tracking-wide"
                    onclick="window.location.href='schedule'">
                    <span class="truncate">Schedule</span>
                </button>
            </div>

            <!-- Admin Login Card -->
            <div class="flex flex-col gap-4 rounded-xl border border-white/10 bg-white p-6 dark:bg-white/5 sm:p-8 md:col-span-2">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-slate-500/20 text-slate-500 dark:text-slate-300">
                        <span class="material-symbols-outlined text-3xl">admin_panel_settings</span>
                    </div>
                    <div>
                        <h3 class="font-display text-xl font-bold text-slate-800 dark:text-white">For Administrators</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Access the portal to manage routes and schedules.</p>
                    </div>
                </div>
                <button
                class="flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-background-dark text-base font-bold leading-normal tracking-wide"
                onclick="window.location.href='adminportal'">
                    <span class="truncate">Admin Portal</span>
                </button>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-10 border-t border-neutral-300 bg-footer-light p-5 text-center">
        <div class="mx-auto max-w-4xl">
            <p class="text-sm text-slate-500 dark:text-slate-400">© 2025 IIUM Smart Bus Tracker</p>
        </div>
    </footer>
</div>
</body>
</html>
