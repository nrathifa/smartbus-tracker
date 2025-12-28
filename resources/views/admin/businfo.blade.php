<!DOCTYPE html>
<html lang="en" x-data="busApp()">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<link rel="icon" type="image/png" href="{{ asset('asset/images/logo-iium.png') }}">
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

<style>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
body { min-height: max(884px, 100dvh); }
</style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display">

<div class="relative flex min-h-screen flex-col overflow-x-hidden">

  <!-- Top App Bar -->
  <header class="sticky top-0 z-10 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm">
    <div class="flex items-center p-4 justify-between border-b border-gray-200 dark:border-gray-700">

      <button onclick="window.location.href='{{ url('/admin') }}'"
              class="flex size-12 shrink-0 items-center justify-center rounded-full hover:bg-primary/10 transition">
        <span class="material-symbols-outlined text-text-light dark:text-text-dark text-3xl">arrow_back</span>
      </button>

      <h1 class="text-lg sm:text-xl font-semibold whitespace-nowrap">Manage Bus Info</h1>

      <button class="flex w-12 h-12 items-center justify-end" @click="openAddForm()">
        <span class="material-symbols-outlined text-primary text-3xl">add_circle</span>
      </button>

    </div>
  </header>

  <main class="flex-1 p-4 space-y-4">

    <!-- Search Bar -->
    <div class="px-0 py-2">
      <label class="flex flex-col min-w-40 h-12 w-full">
        <div class="flex w-full items-stretch rounded-xl shadow-sm bg-white dark:bg-white/10">
          <div class="text-subtext-light dark:text-subtext-dark flex items-center justify-center pl-4">
            <span class="material-symbols-outlined">search</span>
          </div>
          <input class="form-input flex-1 rounded-r-xl border-none bg-transparent text-text-light dark:text-text-dark focus:ring-0 pl-2"
                 placeholder="Search by plate number..."/>
        </div>
      </label>
    </div>

    <div class="flex flex-col gap-3 pb-4">
  @forelse($buses as $bus)
    <div
      class="bg-white dark:bg-background-dark p-4 rounded-xl flex items-center justify-between border border-gray-200 dark:border-gray-700 hover:border-primary transition-colors duration-200 shadow-sm">

      <div class="flex items-center gap-4">
        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-primary shrink-0">
          <span class="material-symbols-outlined text-black text-2xl">directions_bus</span>
        </div>

        <div class="flex flex-col gap-1">
          <p class="text-text-dark dark:text-white text-base font-bold">{{ $bus->plate_number }}</p>

          <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $bus->route }}</p>

          <p class="text-gray-600 dark:text-gray-400 text-sm flex items-center">
            <span class="material-symbols-outlined text-base mr-1">group</span>
            {{ $bus->current_passengers ?? 0 }}/{{ $bus->capacity ?? 'N/A' }}
          </p>
        </div>
      </div>

      <div class="flex items-center gap-2">
        @php
            $statusColor = match(strtolower($bus->status)) {
                'active' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                'inactive' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
            };
        @endphp

        <span class="{{ $statusColor }} text-xs font-bold px-2.5 py-1 rounded-full w-max uppercase tracking-wide">
          {{ $bus->status ?? 'Unknown' }}
        </span>

        <button @click="openEditForm({{ $bus->id }})"
                class="text-primary size-9 flex items-center justify-center rounded-full hover:bg-primary/10 transition">
          <span class="material-symbols-outlined">edit</span>
        </button>

        <button @click="busToDelete = {{ $bus->id }}; plateToDelete = '{{ $bus->plate_number }}'; showDelete = true"
                class="text-red-600 size-9 flex items-center justify-center rounded-full hover:bg-red-100 transition">
          <span class="material-symbols-outlined">delete</span>
        </button>
      </div>

    </div>
  @empty
    <div class="flex flex-col items-center justify-center text-center py-16">
      <span class="material-symbols-outlined text-5xl text-gray-400">upcoming</span>
      <h3 class="mt-4 text-lg font-semibold text-text-dark dark:text-white">No buses found</h3>
      <p class="text-sm text-gray-500 dark:text-gray-400">Get started by adding the first bus.</p>
    </div>
  @endforelse
</div>


  </main>

  <!-- Slide-in Panel (Add/Edit Bus Form) -->
  <div class="fixed inset-0 z-30 bg-black/50"
       x-show="openForm"
       x-transition
       @click.away="openForm=false"
       style="display:none">

    <div class="absolute bottom-0 left-0 right-0 max-h-[90vh] overflow-y-auto rounded-t-2xl bg-background-light dark:bg-background-dark p-4">

      <div class="flex justify-between items-center pb-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-bold text-text-light dark:text-text-dark" x-text="editing ? 'Edit Bus' : 'Add New Bus'"></h2>
        <button @click="openForm=false" class="text-subtext-light dark:text-subtext-dark">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <form :action="editing ? '/businfo/' + bus.id : '{{ route('bus.store') }}'" method="POST" class="space-y-6 pt-6">
        @csrf
        <template x-if="editing">
            <input type="hidden" name="_method" value="PUT"/>
        </template>

        <div>
          <label class="block text-sm font-medium mb-1 dark:text-white">Plate Number</label>
          <input type="text" name="plate_number" required class="form-input w-full rounded-lg bg-card-light dark:bg-card-dark" x-model="bus.plate_number"/>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1 dark:text-white">Bus Model</label>
          <input type="text" name="model" required class="form-input w-full rounded-lg bg-card-light dark:bg-card-dark" x-model="bus.model"/>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1 dark:text-white">Capacity</label>
          <input type="number" name="capacity" required class="form-input w-full rounded-lg bg-card-light dark:bg-card-dark" x-model="bus.capacity"/>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1 dark:text-white">Driver Name</label>
          <input type="text" name="driver_name" required class="form-input w-full rounded-lg bg-card-light dark:bg-card-dark" x-model="bus.driver_name"/>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1 dark:text-white">Driver Contact</label>
          <input type="text" name="driver_contact" required class="form-input w-full rounded-lg bg-card-light dark:bg-card-dark" x-model="bus.driver_contact"/>
        </div>

        <div>
          <label class="block text-sm font-medium mb-2 dark:text-white">Assigned Route</label>
          <div class="flex space-x-6">
            <label class="inline-flex items-center">
              <input type="radio" name="route" value="Ruqayyah" required class="form-radio" x-model="bus.route"/>
              <span class="ml-2 dark:text-white">Ruqayyah</span>
            </label>
            <label class="inline-flex items-center">
              <input type="radio" name="route" value="Salahuddin" class="form-radio" x-model="bus.route"/>
              <span class="ml-2 dark:text-white">Salahuddin</span>
            </label>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-2 dark:text-white">Status</label>
          <div class="flex space-x-4">
            <label class="flex items-center">
              <input type="radio" name="status" value="Active" class="form-radio" x-model="bus.status"/>
              <span class="ml-2 dark:text-white">Active</span>
            </label>
            <label class="flex items-center">
              <input type="radio" name="status" value="Inactive" class="form-radio" x-model="bus.status"/>
              <span class="ml-2 dark:text-white">Inactive</span>
            </label>
          </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
          <button @click="openForm=false" type="button"
                  class="rounded-full px-6 py-3 bg-gray-200 dark:bg-gray-700">
            Cancel
          </button>
          <button type="submit"
                  class="rounded-full bg-primary px-6 py-3 text-white shadow-lg">
            Save Bus
          </button>
        </div>

      </form>
    </div>

  </div>

  <!-- Delete Popup -->
<div x-show="showDelete" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" x-transition style="display:none;">
  <div class="w-full max-w-sm rounded-xl bg-white dark:bg-white/10 p-6 shadow-lg">
    <div class="text-center">
      <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
        <span class="material-symbols-outlined text-red-600 text-3xl">warning</span>
      </div>
      <h3 class="mt-4 text-lg font-semibold">Delete Bus?</h3>
      <p class="mt-2 text-sm text-subtext-light dark:text-subtext-dark">
        Are you sure you want to delete <span class="font-bold" x-text="plateToDelete"></span>?
      </p>
    </div>

    <div class="mt-6 grid grid-cols-2 gap-3">

      <button @click="showDelete=false"
              class="rounded-full bg-gray-200 dark:bg-gray-700 px-4 py-2.5 font-bold">
        Cancel
      </button>

      <form :action="`/businfo/${busToDelete}`" method="POST">
        @csrf @method('DELETE')
        <button type="submit"
                class="w-full rounded-full bg-red-600 hover:bg-red-700 px-4 py-2.5 text-white font-bold">
          Delete
        </button>
      </form>

    </div>
  </div>
</div>


</div>

<script>
function busApp() {
    return {
        openForm: false,
        editing: false,
        bus: {},
        showDelete: false,
        busToDelete: null,
        plateToDelete: '',

        openAddForm() {
            this.editing = false;
            this.bus = {};
            this.openForm = true;
        },

        openEditForm(busId) {
            fetch(`/businfo/${busId}/edit`)
                .then(res => res.json())
                .then(data => {
                    this.bus = data;
                    this.editing = true;
                    this.openForm = true;
                });
        }
    }
}
</script>

</body>
</html>
