<aside 
    class="fixed inset-y-0 left-0 z-30 flex flex-col transition-all duration-300 ease-in-out bg-gradient-to-b from-indigo-900 via-indigo-800 to-purple-900 text-white shadow-2xl lg:static lg:inset-auto"
    x-bind:class="sidebarOpen ? 'w-72' : 'w-20'"
    :class="{ '-translate-x-full': !sidebarOpen && window.innerWidth < 1024, 'translate-x-0': sidebarOpen || window.innerWidth >= 1024 }">
    
    <!-- SECTION A - Header -->
    <div class="flex items-center gap-3 p-4 border-b border-white/10 shrink-0 overflow-hidden">
        <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-purple-400 rounded-xl flex items-center justify-center text-white font-bold text-lg shrink-0">U</div>
        <span x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="text-white font-bold text-lg whitespace-nowrap">UPF Gestion</span>
    </div>

    <!-- SECTION B - Profil utilisateur -->
    <div class="p-4 border-b border-white/10 shrink-0 overflow-hidden">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold shrink-0 shadow-lg">
                {{ strtoupper(substr(auth()->user()->prenom ?? 'U', 0, 1)) }}{{ strtoupper(substr(auth()->user()->name ?? '', 0, 1)) }}
            </div>
            <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="overflow-hidden">
                <p class="text-white text-sm font-semibold truncate">{{ auth()->user()->prenom }} {{ auth()->user()->name }}</p>
                <span class="text-[10px] px-2 py-0.5 rounded-full bg-white/20 text-indigo-100 font-bold uppercase tracking-wider">
                    @if(auth()->user()->isAdmin()) Administrateur
                    @elseif(auth()->user()->isProfesseur()) Professeur
                    @else Étudiant @endif
                </span>
            </div>
        </div>
    </div>

    <!-- SECTION C - Navigation -->
    <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto sidebar-scroll">
        
        @if(auth()->user()->isAdmin())
            <!-- Admin Links -->
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 text-white shadow-lg' : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Dashboard</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Utilisateurs</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Filières & Modules</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Emploi du temps</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Notes</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Absences</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Demandes</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Salles</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Statistiques</span>
            </a>
        @elseif(auth()->user()->isProfesseur())
            <!-- Prof Links -->
            <a href="{{ route('professeur.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group {{ request()->routeIs('professeur.dashboard') ? 'bg-white/20 text-white shadow-lg' : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Dashboard</span>
            </a>
            
            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Mes modules</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Saisie des notes</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Gestion absences</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Cahier de textes</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Mon EDT</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Réservation salles</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Mes demandes</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Espace Classroom</span>
            </a>
        @else
            <!-- Student Links -->
            <a href="{{ route('etudiant.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group {{ request()->routeIs('etudiant.dashboard') ? 'bg-white/20 text-white shadow-lg' : 'text-indigo-100 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Dashboard</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Mes notes</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Mon EDT</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Mes absences</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Justificatifs</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Demandes admin</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Espace Classroom</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl transition-all duration-300 group text-indigo-100 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Mes documents</span>
            </a>
        @endif
    </nav>

    <!-- SECTION D - Footer sidebar -->
    <div class="p-4 border-t border-white/10 mt-auto shrink-0 overflow-hidden">
        <div class="flex items-center gap-3">
            <!-- Toggle dark mode -->
            <button @click="darkMode = !darkMode"
                    class="p-2 rounded-lg text-indigo-200 hover:bg-white/10 transition-all shrink-0">
                <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M16.95 16.95l.707.707M7.05 7.05l.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>
            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                @csrf
                <button type="submit" class="p-2 rounded-lg text-indigo-200 hover:bg-red-500/20 hover:text-red-300 transition-all" title="Déconnexion">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
            <span x-show="sidebarOpen" x-transition class="text-xs text-indigo-300 font-medium whitespace-nowrap overflow-hidden">Déconnexion</span>
        </div>
    </div>
</aside>
