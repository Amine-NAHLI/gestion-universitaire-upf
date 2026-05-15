<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | UPF Gestion</title>

    <!-- Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons: FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- AOS Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Notyf for notifications -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
        
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }

        #loading-bar {
            position: fixed;
            top: 0; left: 0; height: 3px;
            background: linear-gradient(to right, #4f46e5, #9333ea);
            z-index: 9999; width: 0;
            transition: width 0.4s ease-in-out, opacity 0.3s;
            pointer-events: none;
        }
        .loading #loading-bar { width: 70%; opacity: 1; }
        .loaded #loading-bar { width: 100%; opacity: 0; }
    </style>
</head>
<body
    class="h-full bg-gray-100 dark:bg-gray-900 transition-colors duration-300 antialiased overflow-hidden"
    x-data="{
        // Default true then sync with breakpoint in init for stability.
        sidebarOpen: true,
        windowWidth: window.innerWidth,
        isDesktop() { return this.windowWidth >= 1024; },
        toggleSidebar() { this.sidebarOpen = !this.sidebarOpen; },
        darkMode: localStorage.getItem('darkMode') === 'true',
        closeSidebarAndForwardClick(e) {
            if (this.isDesktop()) return;
            if (!this.sidebarOpen) return;

            const { clientX, clientY } = e;

            // Ensure the overlay doesn't still intercept hit-testing during transition.
            if (e.currentTarget && e.currentTarget.style) {
                e.currentTarget.style.pointerEvents = 'none';
                setTimeout(() => { e.currentTarget.style.pointerEvents = ''; }, 400);
            }

            this.sidebarOpen = false;

            this.$nextTick(() => {
                const el = document.elementFromPoint(clientX, clientY);
                if (!el) return;
                const clickable = el.closest?.('a,button,[role=button],input,select,textarea');
                if (clickable && clickable !== e.target) clickable.click();
            });
        }
    }"
    x-init="
        // Dark mode: apply on <html> for Tailwind.
        document.documentElement.classList.toggle('dark', darkMode);
        $watch('darkMode', val => {
            localStorage.setItem('darkMode', val);
            document.documentElement.classList.toggle('dark', val);
        });

        const mq = window.matchMedia('(min-width: 1024px)');
        
        // Initial sync
        sidebarOpen = mq.matches;

        // Sync on resize only when crossing breakpoint
        mq.addEventListener('change', e => {
            if (e.matches) sidebarOpen = true;
            else sidebarOpen = false;
        });

        window.addEventListener('resize', () => {
            windowWidth = window.innerWidth;
        }, { passive: true });
    "
    x-cloak>
    <div id="loading-bar"></div>
    
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
                    <p>&copy; {{ date('Y') }} {{ __('Université Privée de Fès (UPF). Tous droits réservés.') }}</p>
                    <div class="flex space-x-6 mt-4 md:mt-0 font-semibold">
                        <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">{{ __('Support Technique') }}</a>
                        <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">{{ __('Confidentialité') }}</a>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen && !isDesktop()" 
             @click="closeSidebarAndForwardClick($event)"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-20 bg-gray-900/50 backdrop-blur-sm lg:hidden"
             x-cloak></div>
    </div>



    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        // Global Notyf
        var Notyf = new Notyf({
            duration: 4000,
            position: { x: 'right', y: 'top' },
            types: [
                { type: 'success', background: '#4f46e5', icon: false },
                { type: 'error', background: '#e11d48', icon: false }
            ]
        });

        // AOS Init
        AOS.init({ duration: 800, once: true });

        // Loading Bar
        window.addEventListener('beforeunload', () => document.body.classList.add('loading'));
        document.addEventListener('DOMContentLoaded', () => {
            document.body.classList.add('loaded');
            setTimeout(() => document.body.classList.remove('loading', 'loaded'), 500);

            // Handle Flash Messages
            @if(session('success'))
                Notyf.success("{{ session('success') }}");
            @endif
            @if(session('error'))
                Notyf.error("{{ session('error') }}");
            @endif
        });

        // Global Delete Confirmation
        function confirmDelete(formId) {
            Swal.fire({
                title: "{{ __('Êtes-vous sûr de vouloir supprimer cet élément ?') }}",
                text: "{{ __('Cette action est irréversible.') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#e11d48',
                confirmButtonText: "{{ __('Oui, supprimer !') }}",
                cancelButtonText: "{{ __('Non, annuler') }}",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
