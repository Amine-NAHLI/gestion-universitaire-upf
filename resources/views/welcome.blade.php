<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('UPF Gestion') }} - {{ __('Plateforme Universitaire') }}</title>
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- AOS Library for animations -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Outfit', sans-serif; }
        .hero-gradient {
            background: radial-gradient(circle at 50% 50%, rgba(79, 70, 229, 0.08) 0%, transparent 50%);
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 min-h-screen hero-gradient selection:bg-indigo-500 selection:text-white">

    {{-- Navigation --}}
    <nav class="glass-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ url('/') }}" class="flex items-center space-x-3 group cursor-pointer">
                    <div class="w-12 h-12 bg-gradient-to-tr from-indigo-600 to-violet-600 rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-lg shadow-indigo-500/30 transform group-hover:rotate-12 transition-transform duration-300">
                        U
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-black text-slate-800 dark:text-white tracking-tight leading-none">{{ __('UPF Gestion') }}</span>
                        <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mt-1">{{ __('Université Privée de Fès') }}</span>
                    </div>
                </a>
                <div class="flex items-center space-x-6">
                    {{-- Language Switcher --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="text-slate-600 dark:text-slate-400 font-bold hover:text-indigo-600 transition-colors uppercase flex items-center gap-1">
                            {{ app()->getLocale() }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-32 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 overflow-hidden z-50">
                            <a href="{{ route('lang.switch', 'fr') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-indigo-50 dark:hover:bg-slate-700">Français</a>
                            <a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-indigo-50 dark:hover:bg-slate-700">English</a>
                            <a href="{{ route('lang.switch', 'ar') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-indigo-50 dark:hover:bg-slate-700">العربية</a>
                        </div>
                    </div>

                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-primary">{{ __('Tableau de bord') }}</a>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-600 dark:text-slate-400 font-bold hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">{{ __('Connexion') }}</a>
                        <a href="{{ route('register') }}" class="btn-primary">{{ __('Commencer') }}</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="relative pt-20 pb-32 overflow-hidden">
        {{-- Decorative Elements --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10 pointer-events-none opacity-20">
            <div class="absolute top-10 left-1/4 w-64 h-64 bg-indigo-500 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-20 right-1/4 w-96 h-96 bg-violet-500 rounded-full blur-[150px]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div data-aos="fade-up" data-aos-duration="1000">
                <span class="inline-flex items-center space-x-2 px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full text-xs font-black uppercase tracking-widest mb-8 border border-indigo-100 dark:border-indigo-800">
                    <span class="w-2 h-2 bg-indigo-600 rounded-full animate-pulse"></span>
                    <span>{{ __('Plateforme Universitaire 2.0') }}</span>
                </span>
                
                <h1 class="text-6xl md:text-8xl font-black text-slate-900 dark:text-white mb-8 tracking-tight leading-[1.1]">
                    {{ __('Gérez votre futur') }} <br>
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 via-violet-600 to-indigo-500">{{ __('avec excellence.') }}</span>
                </h1>
                
                <p class="text-xl text-slate-500 dark:text-slate-400 max-w-2xl mx-auto mb-12 font-medium leading-relaxed">
                    {{ __('Une expérience académique réinventée pour les étudiants et professeurs de l\'Université Privée de Fès. Notes, absences et supports de cours en un seul clic.') }}
                </p>
                
                <div class="flex flex-wrap gap-4 justify-center">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-primary text-lg px-10 py-4 group">
                            {{ __('Accéder à mon espace') }}
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary text-lg px-10 py-4 group">
                            {{ __('Accéder à mon espace') }}
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    @endauth
                    <a href="#features" class="btn-secondary text-lg px-10 py-4">
                        {{ __('En savoir plus') }}
                    </a>
                </div>
            </div>

            {{-- Stats / Trust Bar --}}
            <div class="mt-24 grid grid-cols-2 md:grid-cols-4 gap-8 max-w-4xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                <div class="flex flex-col">
                    <span class="text-3xl font-black text-slate-900 dark:text-white">100%</span>
                    <span class="text-sm font-bold text-slate-500 uppercase tracking-widest mt-1">Digitalisé</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-3xl font-black text-slate-900 dark:text-white">24/7</span>
                    <span class="text-sm font-bold text-slate-500 uppercase tracking-widest mt-1">Accessible</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-3xl font-black text-slate-900 dark:text-white">Fast</span>
                    <span class="text-sm font-bold text-slate-500 uppercase tracking-widest mt-1">Performance</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-3xl font-black text-slate-900 dark:text-white">Secure</span>
                    <span class="text-sm font-bold text-slate-500 uppercase tracking-widest mt-1">Encryption</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section id="features" class="py-24 bg-white dark:bg-slate-900/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20" data-aos="fade-up">
                <h2 class="text-4xl font-black text-slate-900 dark:text-white mb-4">{{ __('Écosystème Connecté') }}</h2>
                <p class="text-slate-500 dark:text-slate-400 font-medium">{{ __('Trois espaces dédiés pour une gestion optimale de la scolarité.') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Student --}}
                <div class="card group hover:-translate-y-2" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center mb-6 text-3xl group-hover:scale-110 transition-transform">📚</div>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">{{ __('Espace Étudiant') }}</h3>
                    <p class="text-slate-500 dark:text-slate-400 leading-relaxed mb-6 font-medium">{{ __('Consultez vos notes en temps réel, téléchargez vos supports de cours et suivez votre assiduité quotidiennement.') }}</p>
                    <ul class="space-y-3">
                        <li class="flex items-center text-sm font-bold text-slate-700 dark:text-slate-300">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Relevés de notes digitaux
                        </li>
                        <li class="flex items-center text-sm font-bold text-slate-700 dark:text-slate-300">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Emploi du temps interactif
                        </li>
                    </ul>
                </div>

                {{-- Professor --}}
                <div class="card group hover:-translate-y-2" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-indigo-100 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center mb-6 text-3xl group-hover:scale-110 transition-transform">👨‍🏫</div>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">{{ __('Espace Professeur') }}</h3>
                    <p class="text-slate-500 dark:text-slate-400 leading-relaxed mb-6 font-medium">{{ __('Saisissez les notes, gérez les absences avec simplicité et organisez vos séances de cours en toute fluidité.') }}</p>
                    <ul class="space-y-3">
                        <li class="flex items-center text-sm font-bold text-slate-700 dark:text-slate-300">
                            <svg class="w-4 h-4 mr-2 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Gestion du cahier de texte
                        </li>
                        <li class="flex items-center text-sm font-bold text-slate-700 dark:text-slate-300">
                            <svg class="w-4 h-4 mr-2 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Réservation de salles
                        </li>
                    </ul>
                </div>

                {{-- Admin --}}
                <div class="card group hover:-translate-y-2" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-violet-100 dark:bg-violet-900/30 rounded-2xl flex items-center justify-center mb-6 text-3xl group-hover:scale-110 transition-transform">⚙️</div>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">{{ __('Administration') }}</h3>
                    <p class="text-slate-500 dark:text-slate-400 leading-relaxed mb-6 font-medium">{{ __('Contrôlez l\'ensemble de l\'institution : gestion des utilisateurs, modules, filières et statistiques globales.') }}</p>
                    <ul class="space-y-3">
                        <li class="flex items-center text-sm font-bold text-slate-700 dark:text-slate-300">
                            <svg class="w-4 h-4 mr-2 text-violet-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Pilotage stratégique (BI)
                        </li>
                        <li class="flex items-center text-sm font-bold text-slate-700 dark:text-slate-300">
                            <svg class="w-4 h-4 mr-2 text-violet-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Validation des justificatifs
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-slate-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-black text-xl">U</div>
                <span class="text-xl font-black tracking-tight">UPF GESTION</span>
            </div>
            <div class="text-center md:text-right">
                <p class="text-slate-400 font-medium">&copy; {{ date('Y') }} Université Privée de Fès - Projet Académique</p>
                <p class="text-xs text-indigo-500 font-bold mt-1 uppercase tracking-widest">Réalisé par Amine NAHLI - GINFO3</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            duration: 800,
            easing: 'ease-out-quart'
        });
    </script>
</body>
</html>