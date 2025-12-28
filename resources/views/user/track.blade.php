<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="icon" type="image/png" href="{{ asset('asset/images/logo-iium.png') }}">
  <title>IIUM Smart Bus Tracker</title>

  <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>

  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

  <link href="https://fonts.googleapis.com" rel="preconnect"/>
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            primary: "#72D0C8",
            "background-light": "#E5E7E8",
            "header-light": "#E5E7E8",
            "footer-light": "#E5E7E8",
            "text-dark": "#1A1A1A",
          },
          fontFamily: {
            display: ["Inter", "sans-serif"]
          },
        },
      },
    }
  </script>
  <style>
    /* 1. Define the shaking/rumbling animation */
    @keyframes busRumble {
        0%   { transform: translate(0, 0) rotate(0deg); }
        25%  { transform: translate(1px, 1px) rotate(1deg); }
        50%  { transform: translate(-1px, -1px) rotate(-1deg); }
        75%  { transform: translate(1px, -1px) rotate(1deg); }
        100% { transform: translate(0, 0) rotate(0deg); }
    }

    /* 2. Apply it to the bus image */
    .moving-bus-img {
        width: 100%;
        height: 100%;
        animation: busRumble 0.3s infinite linear; /* Fast vibration */
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3)); /* Optional shadow */
    }

    /* 3. Remove default white square from Leaflet divIcon */
    .transparent-marker {
        background: transparent;
        border: none;
    }
</style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-text-dark">
<div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">

  <header class="sticky top-0 z-[2000] flex items-center justify-between
    bg-header-light p-4 backdrop-blur-sm shadow-md
    dark:bg-background-dark dark:text-white">

    <button onclick="window.location.href='{{ url('/') }}'"
            class="flex size-12 shrink-0 items-center justify-center rounded-full hover:bg-primary/10 transition">
      <span class="material-symbols-outlined text-text-light dark:text-text-dark text-3xl">arrow_back</span>
    </button>

    <div class="flex items-center justify-center w-full p-4">
      <h1 class="text-lg sm:text-xl font-semibold whitespace-nowrap">Live Map & Routes</h1>
    </div>

    <div x-data="{ open: false }" class="relative">
      <button @click="open = !open"
              class="flex h-10 w-10 items-center justify-center rounded-full text-text-dark dark:text-white">
        <span class="material-symbols-outlined">menu</span>
      </button>

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


  <main class="flex flex-col flex-1 p-4 sm:p-6 md:p-8" x-data="{ selectedRoute: 'all' }">

    <div id="liveMap" class="w-full aspect-[4/3] z-0 rounded-xl overflow-hidden shadow-lg mb-6"></div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        /* ---- 1. INITIALIZE MAP (Center → IIUM Gombak) ---- */
        const map = L.map('liveMap').setView([3.2535, 101.7324], 16);

        /* ---- 2. TILE LAYER (Google Maps Style) ---- */
        L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        }).addTo(map);

        /* ---- 3. ROUTE PATHS (Manual Coordinates) ---- */

        // === ROUTE A: RUQAYYAH (Full Loop) ===
        // Path: Maryam -> Halimah -> Asma -> Hafsa -> KOED -> Utara -> KIRKHS -> AIKOL -> Timur -> KENMS -> Safiyyah -> Selatan -> KAED -> KICT -> KOE -> Hafsa -> Maryam
        const ruqayyahPath = [
            [3.259669, 101.735477], // Maryam
            [3.259687, 101.735361],
            [3.259670, 101.735160],
            [3.259619, 101.735021],
            [3.259515, 101.734777],
            [3.259366, 101.734537],
            [3.259240, 101.734366], // Halimah
            [3.259099, 101.734179],
            [3.258957, 101.734032],
            [3.258753, 101.733918],
            [3.258455, 101.733799],
            [3.258193, 101.733715], //Asiah
            [3.257391, 101.733558], //Asma
            [3.257025, 101.733453],
            [3.256709, 101.733372],
            [3.256471, 101.733326],
            [3.255678, 101.733157], // Hafsa
            [3.255178, 101.733049],
            [3.254675, 101.732934],
            [3.254160, 101.732838],
            [3.254084, 101.733412], // KOED
            [3.254000, 101.734299], // Utara
            [3.253949, 101.734690],
            [3.253814, 101.735051],
            [3.253641, 101.735351],
            [3.253396, 101.735639],
            [3.253023, 101.735852], // KIRKHS
            [3.252739, 101.735955],
            [3.252538, 101.736053],
            [3.252350, 101.736145],
            [3.252033, 101.736369],
            [3.251903, 101.736505],
            [3.251699, 101.736827],
            [3.251601, 101.737219],
            [3.251671, 101.737596],
            [3.252030, 101.738098],
            [3.252137, 101.738322],
            [3.252151, 101.738554], // Aikol
            [3.252049, 101.738925],
            [3.251915, 101.739337],
            [3.251828, 101.739581],
            [3.251724, 101.739622],
            [3.251492, 101.739557],
            [3.251183, 101.739452],
            [3.250765, 101.739303],
            [3.250355, 101.739135],
            [3.249733, 101.738867], // Timur
            [3.249475, 101.738631],
            [3.249308, 101.738377],
            [3.249169, 101.737978],
            [3.249146, 101.737676],
            [3.249174, 101.737301],
            [3.249228, 101.736813], // Kenms
            [3.249275, 101.736424],
            [3.249328, 101.736052],
            [3.249362, 101.735700],
            [3.249437, 101.735144],
            [3.249466, 101.734956], // Safiyyah
            [3.249509, 101.734382],
            [3.249399, 101.734285],
            [3.249383, 101.734129],
            [3.249525, 101.734033],
            [3.249656, 101.733810], // Selatan
            [3.249865, 101.733384],
            [3.250219, 101.732515],
            [3.250553, 101.731656],
            [3.250706, 101.731265], // KAED
            [3.250947, 101.730790],
            [3.251244, 101.730466],
            [3.251694, 101.730243],
            [3.252259, 101.730224],
            [3.252374, 101.730208],
            [3.252428, 101.729878],
            [3.252546, 101.729567],
            [3.252773, 101.729261],
            [3.253154, 101.728945],
            [3.253427, 101.728832],
            [3.253562, 101.728914],
            [3.253792, 101.728807],
            [3.254060, 101.728750],
            [3.254384, 101.728839], // KICT
            [3.254625, 101.729018],
            [3.254748, 101.728925],
            [3.254885, 101.729075],
            [3.254992, 101.729386],
            [3.254872, 101.729657],
            [3.254818, 101.730086],
            [3.254703, 101.730502],
            [3.254494, 101.730735],
            [3.254175, 101.730963],
            [3.253862, 101.731113],
            [3.254047, 101.731491],
            [3.254215, 101.732143], // KOE
            [3.254189, 101.732543],
            [3.254151, 101.732819], // Back to Hafsa
        ];

        // === ROUTE B: SALAHUDDIN (Outer Loop) ===
        // Path: Salahuddin -> AIKOL -> Timur -> KENMS -> Safiyyah -> Selatan -> KAED -> KICT -> KOE -> KOED -> Utara -> Salahuddin
        const salahuddinPath = [
            [3.257349, 101.739452], // Salahuddin
            [3.256508, 101.739306],
            [3.256113, 101.739310],
            [3.255281, 101.739324],
            [3.254769, 101.739259],
            [3.253932, 101.738971],
            [3.253241, 101.738519],
            [3.252847, 101.738091],
            [3.252410, 101.737988],
            [3.252052, 101.738068],
            [3.252043, 101.738094],
            [3.252151, 101.738554], // Aikol
            [3.252049, 101.738925],
            [3.251915, 101.739337],
            [3.251828, 101.739581],
            [3.251724, 101.739622],
            [3.251492, 101.739557],
            [3.251183, 101.739452],
            [3.250765, 101.739303],
            [3.250355, 101.739135],
            [3.249733, 101.738867], // Timur
            [3.249475, 101.738631],
            [3.249308, 101.738377],
            [3.249169, 101.737978],
            [3.249146, 101.737676],
            [3.249174, 101.737301],
            [3.249228, 101.736813], // Kenms
            [3.249275, 101.736424],
            [3.249328, 101.736052],
            [3.249362, 101.735700],
            [3.249437, 101.735144],
            [3.249466, 101.734956], // Safiyyah
            [3.249509, 101.734382],
            [3.249399, 101.734285],
            [3.249383, 101.734129],
            [3.249525, 101.734033],
            [3.249656, 101.733810], // Selatan
            [3.249865, 101.733384],
            [3.250219, 101.732515],
            [3.250553, 101.731656],
            [3.250706, 101.731265], // KAED
            [3.250947, 101.730790],
            [3.251244, 101.730466],
            [3.251694, 101.730243],
            [3.252259, 101.730224],
            [3.252374, 101.730208],
            [3.252428, 101.729878],
            [3.252546, 101.729567],
            [3.252773, 101.729261],
            [3.253154, 101.728945],
            [3.253427, 101.728832],
            [3.253562, 101.728914],
            [3.253792, 101.728807],
            [3.254060, 101.728750],
            [3.254384, 101.728839], // KICT
            [3.254625, 101.729018],
            [3.254748, 101.728925],
            [3.254885, 101.729075],
            [3.254992, 101.729386],
            [3.254872, 101.729657],
            [3.254818, 101.730086],
            [3.254703, 101.730502],
            [3.254494, 101.730735],
            [3.254175, 101.730963],
            [3.253862, 101.731113],
            [3.254047, 101.731491],
            [3.254215, 101.732143], // KOE
            [3.254189, 101.732543],
            [3.254151, 101.732819], // Back to Hafsa
            [3.254160, 101.732838],
            [3.254084, 101.733412], // KOED
            [3.254000, 101.734299], // Utara
            [3.253949, 101.734690],
            [3.253814, 101.735051],
            [3.253641, 101.735351],
            [3.253396, 101.735639],
            [3.253023, 101.735852], // KIRKHS
            [3.252739, 101.735955],
            [3.252538, 101.736053],
            [3.252350, 101.736145],
            [3.252033, 101.736369],
            [3.251903, 101.736505],
            [3.251699, 101.736827],
            [3.251601, 101.737219],
            [3.251671, 101.737596],
            [3.252030, 101.738098],
        ];

        /* ---- 4. DRAW THE LINES ON MAP ---- */

        // Draw Ruqayyah (Teal)
        L.polyline(ruqayyahPath, {
            color: '#76153C', // Teal
            weight: 6,        // Thickness
            opacity: 0.8,
            lineJoin: 'round'
        }).addTo(map);

        // Draw Salahuddin (Orange)
        L.polyline(salahuddinPath, {
            color: '#E37434', // Orange
            weight: 5,
            opacity: 0.8,
            dashArray: '10, 10', // Dashed line to differentiate
            lineJoin: 'round'
        }).addTo(map);


        /* ---- 5. STATIC BUS STOPS ---- */
        const staticBusStops = [
            { name: "UIAM (Selatan)", lat: 3.2497554, lng: 101.7331624 },
            { name: "Kaed UIAM",      lat: 3.250655, lng: 101.731181 },
            { name: "Kict UIAM",      lat: 3.254308, lng: 101.728825 },
            { name: "Koe UIAM",       lat: 3.254285, lng: 101.732145 },
            { name: "Mahallah Hafsa", lat: 3.255683, lng: 101.733186 },
            { name: "Mahallah Asma",  lat: 3.257384, lng: 101.733556 },
            { name: "Mahallah Halimah", lat: 3.259256, lng: 101.734417 },
            { name: "Mahallah Maryam", lat: 3.259669, lng: 101.735477 },
            { name: "Koed UIAM",      lat: 3.254133, lng: 101.733453 },
            { name: "UIAM (Utara)",   lat: 3.254077, lng: 101.734271 },
            { name: "Kirkhs UIAM",    lat: 3.253024, lng: 101.735891 },
            { name: "Mahallah Sumayyah", lat: 3.255029, lng: 101.739296 },
            { name: "Mahallah Salahuddin", lat:3.257138, lng: 101.739433},
            { name: "Aikol UIAM",     lat: 3.252209, lng: 101.738562 },
            { name: "UIAM (Timur)",   lat: 3.249624, lng: 101.738826 },
            { name: "Kenms UIAM",     lat: 3.249154, lng: 101.736823 },
            { name: "Mahallah Safiyyah", lat: 3.249375, lng: 101.734785 },
        ];

        const stopIcon = L.icon({
            iconUrl: "{{ asset('asset/images/bus.png') }}",
            iconSize: [32, 32],
            iconAnchor: [16, 16],
            popupAnchor: [0, -16]
        });

        staticBusStops.forEach(stop => {
            L.marker([stop.lat, stop.lng], { icon: stopIcon })
             .addTo(map)
             .bindPopup(`<b>Bus Stop:</b><br>${stop.name}`);
        });

        /* ---- 6. LIVE BUS MARKERS (Dynamic) ---- */
        const busMarkers = {};

        function updateBusLocations() {
            fetch('/api/bus-location')
                .then(res => res.json())
                .then(buses => {
                    buses.forEach(bus => {

                        const lat = parseFloat(bus.latitude);
                        const lng = parseFloat(bus.longitude);

                        if (!lat || !lng || lat == 0 || lng == 0) return;

                        if (!busMarkers[bus.id]) {
                            busMarkers[bus.id] = L.marker([lat, lng], {
                                title: bus.plate_number,
                                zIndexOffset: 1000,

                                // --- USE DIVICON INSTEAD OF ICON ---
                                icon: L.divIcon({
                                    className: 'transparent-marker', // Uses our CSS to remove borders

                                    // We put the image INSIDE the div.
                                    // This lets us animate the IMG without breaking the map position.
                                    html: '<img src="{{ asset("asset/images/redbus.png") }}" class="moving-bus-img">',

                                    iconSize: [45, 45],
                                    iconAnchor: [22, 45],
                                    popupAnchor: [0, -40]
                                })
                                // -----------------------------------

                            }).addTo(map).bindPopup(`<b>Bus: ${bus.plate_number}</b><br>Passengers: ${bus.current_passengers}`);
                        }
                        else {
                            busMarkers[bus.id].setLatLng([lat, lng]);
                            busMarkers[bus.id].setPopupContent(`<b>Bus: ${bus.plate_number}</b><br>Passengers: ${bus.current_passengers}`);
                        }
                    });
                })
                .catch(err => console.error("Error fetching bus locations:", err));
        }

        setInterval(updateBusLocations, 2000);
        updateBusLocations();
    });
    </script>


    <div class="flex overflow-x-auto gap-2 pb-4 no-scrollbar">
      <button @click="selectedRoute='all'"
              :class="selectedRoute==='all' ? 'bg-primary text-black font-semibold' : 'bg-white dark:bg-white/10 dark:text-white'"
              class="px-4 py-2 rounded-full text-sm whitespace-nowrap">All Routes</button>

      <button @click="selectedRoute='ruqayyah'"
              :class="selectedRoute==='ruqayyah' ? 'bg-primary text-black font-semibold' : 'bg-white dark:bg-white/10 dark:text-white'"
              class="px-4 py-2 rounded-full text-sm whitespace-nowrap">Ruqayyah</button>

      <button @click="selectedRoute='salahuddin'"
              :class="selectedRoute==='salahuddin' ? 'bg-primary text-black font-semibold' : 'bg-white dark:bg-white/10 dark:text-white'"
              class="px-4 py-2 rounded-full text-sm whitespace-nowrap">Salahuddin</button>
    </div>


    <div class="flex flex-col gap-6 pb-4">

    @php
        // 1. Group the buses by their route name
        $groupedBuses = $buses->groupBy('route');
    @endphp

    @foreach($groupedBuses as $routeName => $busesInRoute)
        @php
            $cleanRouteID = strtolower(str_replace(' ', '', $routeName));

            // Define stops based on the Route Name
            $stops = [];
            if (str_contains($cleanRouteID, 'ruqayyah')) {
                $stops = ['Mahallah Maryam', 'Mahallah Halimah', 'Mahallah Asma', 'Mahallah Hafsa', 'Koed UIAM', 'UIAM (Utara)', 'Kirkhs UIAM', 'Aikol UIAM', 'UIAM (Timur)', 'Kenms UIAM', 'Mahallah Safiyyah', 'UIAM (Selatan)', 'Kaed UIAM', 'Kict UIAM', 'Koe UIAM'];
            } elseif (str_contains($cleanRouteID, 'salahuddin')) {
                $stops = ['Mahallah Salahuddin', 'Aikol UIAM', 'UIAM (Timur)', 'Kenms UIAM', 'Mahallah Safiyyah', 'UIAM (Selatan)', 'Kaed UIAM', 'Kict UIAM', 'Koe UIAM', 'Koed UIAM', 'UIAM (Utara)', 'Kirkhs UIAM'];
            } else {
                $stops = ['-', '-'];
            }
        @endphp

        <template x-if="selectedRoute === 'all' || selectedRoute === '{{ $cleanRouteID }}'">

            <div x-data="{ showStops: false }" class="bg-white dark:bg-background-dark rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">

                <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-white/5 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            {{ $routeName }}
                            <span class="text-xs font-normal text-gray-500 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 px-2 py-0.5 rounded-full">
                                {{ $busesInRoute->count() }} Active Bus{{ $busesInRoute->count() > 1 ? 'es' : '' }}
                            </span>
                        </h2>
                    </div>

                    <button @click="showStops = !showStops"
                            class="text-xs font-medium text-primary hover:text-primary/80 flex items-center gap-1 transition-colors">
                        <span x-text="showStops ? 'Hide Stops' : 'View Route Stops'"></span>
                        <span class="material-symbols-outlined text-base transition-transform duration-300"
                              :class="showStops ? 'rotate-180' : ''">expand_more</span>
                    </button>
                </div>

                <div x-show="showStops" x-collapse class="bg-gray-50 dark:bg-black/20 border-b border-gray-100 dark:border-gray-700 p-4">
                    <ul class="flex flex-wrap gap-y-3 gap-x-2">
                        @foreach($stops as $index => $stop)
                            <li class="flex items-center text-xs group">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400 dark:bg-gray-500 mr-2 group-hover:bg-primary transition-colors"></span>
                                <span class="text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">
                                    {{ $stop }}
                                </span>
                                @if(!$loop->last)
                                    <span class="material-symbols-outlined text-gray-300 text-sm mx-1">arrow_right_alt</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($busesInRoute as $bus)
                        @php
                            // Check status (case-insensitive)
                            $isActive = strtolower($bus->status) === 'active';
                        @endphp

                        <a href="{{ route('bus.show', $bus->id) }}" class="block p-4 hover:bg-gray-50 dark:hover:bg-white/5 transition group">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full transition-colors
                                        {{ $isActive
                                            ? 'bg-primary/10 text-primary group-hover:bg-primary group-hover:text-black'
                                            : 'bg-gray-100 text-gray-400 dark:bg-gray-700'
                                        }}">
                                        <span class="material-symbols-outlined text-xl">directions_bus</span>
                                    </div>

                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="font-bold {{ $isActive ? 'text-text-dark dark:text-white' : 'text-gray-500 dark:text-gray-400' }}">
                                                {{ $bus->plate_number }}
                                            </p>

                                            @if($isActive)
                                                <span class="block w-2 h-2 rounded-full bg-green-500 animate-pulse" title="Active"></span>
                                            @else
                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                                    INACTIVE
                                                </span>
                                            @endif
                                        </div>

                                        <p class="text-xs text-gray-500">Capacity: {{ $bus->capacity }} seats</p>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Passengers</p>
                                    <div class="flex items-center justify-end gap-1">
                                        <span class="material-symbols-outlined {{ $isActive ? 'text-gray-400' : 'text-gray-300' }} text-lg">group</span>

                                        @if($isActive)
                                            <span id="bus-count-{{ $bus->id }}" class="text-xl font-bold text-primary">
                                                {{ $bus->current_passengers }}
                                            </span>
                                        @else
                                            <span class="text-sm font-bold text-gray-400">
                                                OFF
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </template>
    @endforeach

    </div>

  </main>
</div>

<script>
    // Update passenger counts every 2 seconds
    setInterval(function() {
        fetch('/live-bus-count')
            .then(response => response.json())
            .then(data => {
                if (Array.isArray(data)) {
                    data.forEach(bus => {
                        let element = document.getElementById('bus-count-' + bus.id);
                        if (element) {
                            element.innerText = bus.current_passengers;
                        }
                    });
                }
            })
            .catch(error => console.error('Error fetching bus data:', error));
    }, 2000);
</script>

</body>
</html>
