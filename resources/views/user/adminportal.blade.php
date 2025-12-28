<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="icon" type="image/png" href="{{ asset('asset/images/logo-iium.png') }}">
  <title>Admin Portal Login</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <script>
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        colors: {
          primary: "#1fa38a",             /* Darker teal for buttons */
          "background-light": "#d1d5db",  /* Darker light mode bg */
          "background-dark": "#0a0f1a",   /* Darker dark mode bg */
          "card-light": "#e5e7eb",        /* Slightly darker card bg in light mode */
          "card-dark": "#101c2b",         /* Darker card bg in dark mode */
          "text-light": "#111827",        /* Darker text on light background */
          "text-dark": "#e5e7eb",         /* Text on dark background */
        },
        fontFamily: {
          display: ["Poppins", "sans-serif"],
        },
      },
    },
  };
</script>



  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
</head>

<body class="bg-background-light dark:bg-background-dark font-display">
  <div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">

    <!-- Header with dropdown menu -->
  <header class="sticky top-0 z-50 flex items-center justify-between border-b border-white/10 bg-background-light/[.85] p-4 backdrop-blur-sm dark:bg-background-dark/[.85]">

    <button onclick="window.location.href='{{ url('/') }}'"
            class="flex size-12 shrink-0 items-center justify-center rounded-full hover:bg-primary/10 transition">
      <span class="material-symbols-outlined text-text-light dark:text-text-dark text-3xl">arrow_back</span>
    </button>

    <header class="flex items-center justify-center w-full p-4">
      <h1 class="text-lg sm:text-xl font-semibold whitespace-nowrap"></h1>
    </header>

    <!-- Dropdown Menu -->
    <div x-data="{ open: false }" class="relative">
      <!-- Menu Button -->
      <button @click="open = !open" class="flex h-10 w-10 items-center justify-center rounded-full bg-transparent text-slate-800 dark:text-white">
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
          class="absolute right-0 mt-2 w-48 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-lg z-50">
        <a href="/" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Home</a>
        <a href="/track" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Live Map & Routes</a>
        <a href="/schedule" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Schedule</a>
        <a href="/adminportal" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Admin Portal</a>
      </div>
    </div>
  </header>

    <!-- Main content -->
    <div class="font-display bg-background-light dark:bg-background-dark min-h-screen flex items-center justify-center p-4">
      <div class="w-full max-w-sm mx-auto">

        <!-- Title -->
        <div class="text-center mb-8">
          <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Admin Portal</h1>
          <p class="mt-2 text-gray-500 dark:text-gray-400">Please enter your administrator credentials.</p>
        </div>

        <!-- Error message -->
        @if(session('error'))
          <div class="mb-4 text-red-500 text-sm text-center">
            {{ session('error') }}
          </div>
        @endif

        <!-- Login Form -->
        <div class="bg-white dark:bg-[#121c33] p-8 rounded-2xl shadow-lg">
          <form action="{{ route('admin.login') }}" method="POST" class="space-y-6">
            @csrf
            <div>
              <label for="username" class="block text-sm font-medium text-gray-600 dark:text-gray-300">Username</label>
              <div class="mt-1">
                <input type="text" name="username" id="username" required
                  placeholder=" bus@iium"
                  class="form-input block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-[#1a2641] text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 shadow-sm focus:ring-0 focus:outline-none sm:text-sm"/>
              </div>
            </div>

            <div>
              <label for="password" class="block text-sm font-medium text-gray-600 dark:text-gray-300">Password</label>
              <div class="mt-1">
                <input type="password" name="password" id="password" required
                  placeholder="bustracker"
                  class="form-input block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-[#1a2641] text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 shadow-sm focus:ring-0 focus:outline-none sm:text-sm"/>
              </div>
            </div>

            <div class="text-right">
              <a href="#" class="text-sm font-medium text-primary hover:brightness-110">Forgot Password?</a>
            </div>

            <div>
              <button type="submit"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-gray-900 bg-primary hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-background-dark focus:ring-primary transition-all duration-200">
                Login
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</body>
</html>
