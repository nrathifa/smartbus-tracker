<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="icon" type="image/png" href="<?php echo e(asset('asset/images/logo-iium.png')); ?>">
  <title>IIUM Smart Bus Tracker</title>

  <!-- Alpine.js -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect"/>
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

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

<body class="bg-background-light dark:bg-background-dark font-display">
<div class="relative flex min-h-screen w-full flex-col overflow-x-hidden" x-data="{ selectedRoute: 'all' }">

<!-- Header with dropdown menu -->
  <header class="sticky top-0 z-[2000] flex items-center justify-between
    bg-header-light p-4 backdrop-blur-sm shadow-md
    dark:bg-background-light dark:text-white">

    <button onclick="window.location.href='<?php echo e(url('/admin')); ?>'"
            class="flex size-12 shrink-0 items-center justify-center rounded-full hover:bg-primary/10 transition">
      <span class="material-symbols-outlined text-text-light dark:text-text-dark text-3xl">arrow_back</span>
    </button>

    <header class="flex items-center justify-center w-full p-4">
      <h1 class="text-lg sm:text-xl font-semibold whitespace-nowrap">Schedule</h1>
    </header>

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
        <a href="<?php echo e(url('/admin')); ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Dashboard</a>
                <a href="<?php echo e(url('/admin/track')); ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Live Map & Routes</a>
                <a href="<?php echo e(url('/admin/schedule')); ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Schedule</a>
                <a href="<?php echo e(url('/businfo')); ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Bus Information</a>
                <form method="POST" action="<?php echo e(url('/user/adminportal')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">
                    Logout
                </button>
                </form>
            </div>
    </div>
</header>

    <main x-data="scheduleHighlight()" x-init="init()" class="flex-grow p-4">

    <h1 class="mb-6 text-xl font-bold leading-tight tracking-[-0.015em] text-gray-900 dark:text-white">
        Bus Operating Schedule
    </h1>

    <!-- Day Toggle Buttons -->
    <div class="mb-6 flex space-x-2">
      <button
        @click="day = 'monThurs'"
        :class="day === 'monThurs'
            ? 'bg-primary text-background-dark font-bold'
            : 'bg-white text-text-dark dark:bg-gray-700 dark:text-white'"
        class="flex-1 rounded-full py-2.5 text-center text-sm font-medium transition">
        Monday - Thursday
      </button>

      <button
        @click="day = 'friday'"
        :class="day === 'friday'
            ? 'bg-primary text-background-dark font-bold'
            : 'bg-white text-text-dark dark:bg-gray-700 dark:text-white'"
        class="flex-1 rounded-full py-2.5 text-center text-sm font-medium transition">
        Friday
      </button>
    </div>

    <!-- Monday - Thursday Schedule -->
    <div x-show="day === 'monThurs'" x-transition class="space-y-6">
      <div>
        <h3 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Morning</h3>
        <div class="space-y-3">
          <div :class="isNow('07:30','10:00') ? 'bg-primary/30 border-2 border-primary text-background-dark' : 'bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700'" class="flex items-start justify-between rounded-lg p-4 shadow-sm">
            <div>
              <p class="font-bold dark:text-white">07:30 AM - 10:00 AM</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">Peak Hours</p>
            </div>
            <div class="text-right">
              <p class="font-medium dark:text-white">Every 15 mins</p>
            </div>
          </div>

          <div :class="isNow('10:30','12:00') ? 'bg-primary/30 border-2 border-primary text-background-dark' : 'bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700'" class="flex items-start justify-between rounded-lg p-4 shadow-sm">
            <div>
              <p class="font-bold dark:text-white">10:30 AM - 12:00 PM</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">Off-Peak</p>
            </div>
            <div class="text-right">
              <p class="font-medium dark:text-white">Every 15-30 mins</p>
            </div>
          </div>
        </div>
      </div>

      <div>
        <h3 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Afternoon</h3>
        <div class="space-y-3">
          <div :class="isNow('12:00','14:00') ? 'bg-primary/30 border-2 border-primary text-background-dark' : 'bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700'" class="flex items-start justify-between rounded-lg p-4 shadow-sm">
            <div>
              <p class="font-bold dark:text-white">12:00 PM - 02:00 PM</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">Break Time</p>
            </div>
            <div class="text-right">
              <p class="font-medium dark:text-white">Every 15 mins</p>
            </div>
          </div>

          <div :class="isNow('14:00','15:15') ? 'bg-primary/30 border-2 border-primary text-background-dark' : 'bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700'" class="flex items-start justify-between rounded-lg p-4 shadow-sm">
            <div>
              <p class="font-bold dark:text-white">02:00 PM - 03:15 PM</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">Peak Hours</p>
            </div>
            <div class="text-right">
              <p class="font-medium dark:text-white">Every 15-30 mins</p>
            </div>
          </div>

          <div :class="isNow('16:00','18:00') ? 'bg-primary/30 border-2 border-primary text-background-dark' : 'bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700'" class="flex items-start justify-between rounded-lg p-4 shadow-sm">
            <div>
              <p class="font-bold dark:text-white">04:00 PM - 06:00 PM</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">Off-Peak</p>
            </div>
            <div class="text-right">
              <p class="font-medium dark:text-white">Every 15 mins</p>
            </div>
          </div>
        </div>
      </div>

      <div>
        <h3 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Evening</h3>
        <div class="space-y-3">
          <div :class="isNow('19:45','20:15') ? 'bg-primary/30 border-2 border-primary text-background-dark' : 'bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700'" class="flex items-start justify-between rounded-lg p-4 shadow-sm">
            <div>
              <p class="font-bold dark:text-white">07:45 PM - 08:15 PM</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">Night Service</p>
            </div>
            <div class="text-right">
              <p class="font-medium dark:text-white">Every 15 mins</p>
            </div>
          </div>

          <div :class="isNow('21:30','22:15') ? 'bg-primary/30 border-2 border-primary text-background-dark' : 'bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700'" class="flex items-start justify-between rounded-lg p-4 shadow-sm">
            <div>
              <p class="font-bold dark:text-white">09:30 PM - 10:15 PM</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">Night Service</p>
            </div>
            <div class="text-right">
              <p class="font-medium dark:text-white">Every 15-30 mins</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Friday Schedule -->
    <div x-show="day === 'friday'" x-transition class="space-y-6">
      <div>
        <h3 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Morning</h3>
        <div class="space-y-3">
          <div :class="isNow('07:45','12:15') ? 'bg-primary/30 border-2 border-primary text-background-dark' : 'bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700'" class="flex items-start justify-between rounded-lg p-4 shadow-sm">
            <div>
              <p class="font-bold dark:text-white">07:45 AM - 12:15 PM</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">Peak Hours</p>
            </div>
            <div class="text-right">
              <p class="font-medium dark:text-white">Every 30 mins</p>
            </div>
          </div>
        </div>
      </div>

      <div>
        <h3 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Afternoon (Jummah Break)</h3>
        <div class="space-y-3">
          <div :class="isNow('12:15','14:45') ? 'bg-primary/30 border-2 border-primary text-background-dark' : 'bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700'" class="flex items-start justify-between rounded-lg p-4 shadow-sm">
            <div>
              <p class="font-bold dark:text-white">12:15 PM - 02:45 PM</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">Jummah Prayer Break</p>
            </div>
            <div class="text-right">
              <p class="font-medium text-yellow-600 dark:text-yellow-400">No Service</p>
            </div>
          </div>

          <div :class="isNow('14:45','18:15') ? 'bg-primary/30 border-2 border-primary text-background-dark' : 'bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700'" class="flex items-start justify-between rounded-lg p-4 shadow-sm">
            <div>
              <p class="font-bold dark:text-white">02:45 PM - 06:15 PM</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">Off-Peak</p>
            </div>
            <div class="text-right">
              <p class="font-medium dark:text-white">Every 30 mins</p>
            </div>
          </div>
        </div>
      </div>
    </div>

  </main>
</div>

<script>
function scheduleHighlight() {
    return {
        day: 'monThurs',
        currentMinutes: null,
        init() {
            this.updateTime();
            setInterval(() => this.updateTime(), 60000);
        },
        updateTime() {
            const now = new Date();
            this.currentMinutes = now.getHours() * 60 + now.getMinutes();
        },
        isNow(start, end) {
            const [sh, sm] = start.split(':').map(Number);
            const [eh, em] = end.split(':').map(Number);
            const startMin = sh * 60 + sm;
            const endMin = eh * 60 + em;
            return this.currentMinutes >= startMin && this.currentMinutes <= endMin;
        }
    }
}
</script>
</body>
</html>
<?php /**PATH C:\Users\HP\smartbus-tracker\resources\views/admin/schedule.blade.php ENDPATH**/ ?>