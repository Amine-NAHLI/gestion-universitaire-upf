<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts: Outfit -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- AOS Library -->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        
        <!-- Notyf for notifications -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Outfit', sans-serif; }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="bg-slate-50 dark:bg-slate-950 min-h-screen selection:bg-indigo-500 selection:text-white">
        {{-- Decorative Background --}}
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none opacity-20">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-indigo-500 rounded-full blur-[120px]"></div>
            <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-violet-500 rounded-full blur-[120px]"></div>
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center py-12 px-4">
            <div data-aos="zoom-in" data-aos-duration="600">
                <a href="/" class="group flex flex-col items-center">
                    <div class="w-16 h-16 bg-gradient-to-tr from-indigo-600 to-violet-600 rounded-2xl flex items-center justify-center text-white font-black text-3xl shadow-xl shadow-indigo-500/20 group-hover:rotate-12 transition-transform duration-300">
                        U
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-8 card" data-aos="fade-up" data-aos-delay="100">
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-center" data-aos="fade-up" data-aos-delay="200">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">© {{ date('Y') }} UPF Gestion</p>
            </div>
        </div>

        <!-- Scripts -->
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
        <script>
            var Notyf = new Notyf({
                duration: 3000,
                position: { x: 'right', y: 'bottom' },
                types: [{ type: 'success', background: '#4f46e5', icon: false }]
            });
            AOS.init({ once: true });
        </script>
    </body>
</html>
