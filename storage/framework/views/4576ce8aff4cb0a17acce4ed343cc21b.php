<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link rel="icon" type="image/png" href="<?php echo e(asset('asset/images/logo-iium.png')); ?>">
<title>IIUM Smart Bus Tracker</title>

<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

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
            },
            fontFamily: {
                "display": ["Inter", "sans-serif"]
            }
        }
    }
}
</script>

<style>
.material-symbols-outlined { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
</style>
</head>

<body class="bg-background-light dark:bg-gray-900 font-display text-text-dark min-h-screen flex flex-col">

    <div class="relative flex min-h-screen w-full flex-col overflow-x-hidden max-w-md mx-auto sm:max-w-2xl p-4">

        <header class="flex items-center justify-between py-4">
            <button onclick="window.location.href='<?php echo e(url('/track')); ?>'"
                    class="flex size-12 items-center justify-center rounded-full hover:bg-white/50 transition text-text-dark dark:text-white">
                <span class="material-symbols-outlined text-3xl">arrow_back</span>
            </button>
            <h1 class="text-lg font-bold">Bus Profile</h1>
            <div class="size-12"></div> </header>

        <main class="flex-1 space-y-4">

            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col items-center text-center">

                    <div class="size-20 rounded-full bg-primary flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-black text-4xl">directions_bus</span>
                    </div>

                    <h2 class="text-3xl font-bold text-text-dark dark:text-white mb-1">
                        <?php echo e($bus->plate_number); ?>

                    </h2>

                    <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-background-light dark:bg-gray-700 text-sm font-medium text-gray-600 dark:text-gray-300 mb-4 mt-1">
                        <span class="material-symbols-outlined text-base">alt_route</span>
                        <?php echo e($bus->route ?? 'No Route Assigned'); ?>

                    </div>

                    <div class="flex items-center gap-2">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full <?php echo e($bus->status === 'active' ? 'bg-green-400' : 'bg-red-400'); ?> opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 <?php echo e($bus->status === 'active' ? 'bg-green-500' : 'bg-red-500'); ?>"></span>
                        </span>
                        <span class="text-sm font-bold uppercase tracking-wider <?php echo e($bus->status === 'active' ? 'text-green-600' : 'text-red-600'); ?>">
                            <?php echo e($bus->status ?? 'Offline'); ?>

                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">

                <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col justify-center items-center gap-2">
                    <span class="material-symbols-outlined text-gray-400 text-3xl">directions_car</span>
                    <div class="text-center">
                        <p class="text-xs text-gray-500 font-bold uppercase">Bus Model</p>
                        <p class="font-bold text-text-dark dark:text-white mt-1"><?php echo e($bus->model ?? 'Unknown'); ?></p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col justify-center items-center gap-2">
                    <span class="material-symbols-outlined text-gray-400 text-3xl">airline_seat_recline_normal</span>
                    <div class="text-center w-full">
                        <p class="text-xs text-gray-500 font-bold uppercase mb-1">Capacity</p>
                        <div class="flex items-end justify-center gap-1">
                            <span class="font-bold text-xl text-primary"><?php echo e($bus->current_passengers ?? 0); ?></span>
                            <span class="text-gray-400 text-sm mb-1">/ <?php echo e($bus->capacity ?? 0); ?></span>
                        </div>

                        <?php
                            $percentage = ($bus->capacity > 0) ? (($bus->current_passengers ?? 0) / $bus->capacity) * 100 : 0;
                        ?>
                        <div class="w-full h-1.5 bg-gray-100 rounded-full mt-2 overflow-hidden">
                            <div class="h-full bg-primary rounded-full" style="width: <?php echo e($percentage); ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Driver Information</h3>

                <div class="flex items-center gap-4">
                    <div class="size-12 rounded-full bg-background-light dark:bg-gray-700 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-gray-500 dark:text-gray-400 text-2xl">person</span>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-base font-bold text-text-dark dark:text-white truncate">
                            <?php echo e($bus->driver_name ?? 'Not Assigned'); ?>

                        </p>
                        <p class="text-sm text-gray-500 truncate">
                            <?php echo e($bus->driver_contact ?? 'No Contact Info'); ?>

                        </p>
                    </div>

                    <?php if(!empty($bus->driver_contact)): ?>
                    <a href="tel:<?php echo e($bus->driver_contact); ?>" class="size-10 rounded-full bg-primary text-black flex items-center justify-center hover:bg-opacity-80 transition-colors">
                        <span class="material-symbols-outlined text-xl">call</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>

        </main>
    </div>

</body>
</html>
<?php /**PATH C:\Users\HP\smartbus-tracker\resources\views/user/bus.blade.php ENDPATH**/ ?>