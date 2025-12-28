<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="icon" type="image/png" href="{{ asset('asset/images/logo-iium.png') }}">
  <title>IIUM Smart Bus Tracker</title>

  <!-- Alpine.js -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

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

  <style>
    body { overflow-x: hidden; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
  </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display">

<div class="relative flex min-h-screen w-full flex-col overflow-x-hidden" x-data="{ selectedRoute: 'all' }">

  <!-- Header with dropdown menu -->
  <header class="sticky top-0 z-[2000] flex items-center justify-between
    bg-header-light p-4
    dark:bg-background-light dark:text-white">

    <button onclick="window.location.href='{{ url('/admin') }}'"
            class="flex size-12 shrink-0 items-center justify-center rounded-full hover:bg-primary/10 transition">
      <span class="material-symbols-outlined text-text-light dark:text-text-dark text-3xl">arrow_back</span>
    </button>

    <header class="flex items-center justify-center w-full p-4">
      <h1 class="text-lg sm:text-xl font-semibold whitespace-nowrap">Report</h1>
    </header>

  </header>

    <main class="flex-1 p-4 md:p-8 max-w-7xl mx-auto w-full">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-bold">Daily Report</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Activity for <span class="font-bold text-primary">{{ \Carbon\Carbon::parse($date)->format('D, d M Y') }}</span>
                </p>
            </div>

            <form action="{{ route('admin.report') }}" method="GET" class="flex items-center gap-2 bg-white dark:bg-gray-800 p-2 rounded-xl border border-border-light dark:border-gray-700 shadow-sm">
                <span class="material-symbols-outlined text-gray-400 pl-2">calendar_month</span>
                <input type="date" name="date" value="{{ $date }}"
                       class="border-none bg-transparent text-gray-700 dark:text-white focus:ring-0 cursor-pointer text-sm"
                       onchange="this.form.submit()">
            </form>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-8">

            <div class="flex flex-col gap-2 rounded-xl border border-border-light bg-white p-6 dark:bg-gray-800 shadow-sm hover:border-primary transition duration-300">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Boardings</p>
                <div class="flex items-center justify-between">
                    <p class="text-3xl font-bold text-primary">{{ $totalBoarding }}</p>
                    <span class="material-symbols-outlined text-primary text-3xl">directions_bus</span>
                </div>
            </div>

            <div class="flex flex-col gap-2 rounded-xl border border-border-light bg-white p-6 dark:bg-gray-800 shadow-sm hover:border-blue-400 transition duration-300">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Unique Students</p>
                <div class="flex items-center justify-between">
                    <p class="text-3xl font-bold text-blue-500">{{ $uniqueStudents }}</p>
                    <span class="material-symbols-outlined text-blue-500 text-3xl">person</span>
                </div>
            </div>

        </div>

        <div class="rounded-xl border border-border-light bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-white/5">
                <h3 class="font-bold text-lg">Activity Log</h3>
                <span class="text-xs font-medium px-2 py-1 rounded bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    {{ count($logs) }} Records
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-500 dark:text-gray-400 uppercase font-bold text-xs border-b border-gray-100 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-4">Time</th>
                            <th class="px-6 py-4">Student</th>
                            <th class="px-6 py-4">Action</th>
                            <th class="px-6 py-4">Bus ID</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                    {{ $log->created_at->format('h:i:s A') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $log->student_name ?? 'Unknown Student' }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $log->matric_no ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($log->action === 'BOARDING')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                            <span class="material-symbols-outlined text-[14px]">login</span>
                                            Boarding
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                                            <span class="material-symbols-outlined text-[14px]">logout</span>
                                            Exiting
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                    <div class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-base">directions_bus</span>
                                        #{{ $log->bus_id ?? '-' }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center text-gray-400 dark:text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-4 mb-3">
                                            <span class="material-symbols-outlined text-4xl">history_toggle_off</span>
                                        </div>
                                        <p class="text-base font-medium">No activity found</p>
                                        <p class="text-xs mt-1">No bus usage recorded for this date.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <footer class="mt-auto border-t border-border-light bg-footer-light p-5 text-center dark:bg-gray-900 dark:border-gray-800">
        <p class="text-sm text-gray-500 dark:text-gray-400">© 2025 IIUM Smart Bus Tracker</p>
    </footer>

</div>
</body>
</html>
