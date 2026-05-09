<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="h-full" 
      x-data="{ 
        sidebarOpen: window.innerWidth >= 1024, 
        darkMode: localStorage.getItem('darkMode') === 'true' 
      }" 
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | UPF Gestion</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Scripts & Styles -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Custom scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
    </style>
</head>
<body class="h-full bg-gray-100 dark:bg-gray-900 transition-colors duration-300 antialiased overflow-hidden">
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        @include('components.dashboard.sidebar')

        <!-- Content Area -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
            
            <!-- Topbar -->
            @include('components.dashboard.topbar')

            <!-- Main Content -->
            <main class="p-4 md:p-6 lg:p-8">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="py-6 px-8 mt-auto border-t border-gray-200 dark:border-gray-800 bg-white/50 dark:bg-gray-900/50 backdrop-blur-sm">
                <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-500 dark:text-gray-400">
                    <p>&copy; {{ date('Y') }} Université Privée de Fès (UPF). Tous droits réservés.</p>
                    <div class="flex space-x-6 mt-4 md:mt-0 font-semibold">
                        <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Support Technique</a>
                        <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Confidentialité</a>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen && window.innerWidth < 1024" 
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-20 bg-gray-900/50 backdrop-blur-sm lg:hidden"
             x-cloak></div>
    </div>



    <!-- Notyf Flash Messages -->
    @if(session('success') || session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const notyf = new Notyf({
                duration: 5000,
                position: { x: 'right', y: 'top' },
                ripple: true,
                dismissible: true
            });
            @if(session('success'))
                notyf.success("{{ session('success') }}");
            @endif
            @if(session('error'))
                notyf.error("{{ session('error') }}");
            @endif
        });
    </script>
    @endif

    @stack('scripts')
</body>
</html>
