<header
    class="sticky top-0 z-20 flex h-20 w-full items-center bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm px-4 md:px-6 transition-colors duration-300">

    <!-- Sidebar Toggle -->
    <button @click="sidebarOpen = !sidebarOpen"
        class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Page Title & Global Search -->
    <div class="ml-4 flex-1 flex items-center justify-between">
        <h1 class="text-lg font-bold text-gray-800 dark:text-white tracking-tight hidden md:block">
            @yield('page-title', 'Tableau de bord')</h1>

        <!-- Global Search -->
        @if(!auth()->user()->isEtudiant())
            <div class="max-w-md w-full ml-0 md:ml-8 relative" x-data="{ 
                    query: '', 
                    results: [], 
                    showResults: false,
                    loading: false,
                    async search() {
                        if (this.query.length < 2) { this.results = []; this.showResults = false; return; }
                        this.loading = true;
                        try {
                            const res = await fetch('/search?q=' + encodeURIComponent(this.query));
                            this.results = await res.json();
                            this.showResults = true;
                        } catch(e) { this.results = []; }
                        this.loading = false;
                    }
                 }" @click.away="showResults = false" @keydown.escape="showResults = false">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg x-show="!loading" class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <svg x-show="loading" class="animate-spin h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24"
                            x-cloak>
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                            </path>
                        </svg>
                    </div>
                    <input type="text" x-model="query" @input.debounce.300ms="search()"
                        @focus="if(results.length) showResults = true" placeholder="@lang('nav.search')"
                        class="block w-full pl-10 pr-16 py-2 border border-gray-200 dark:border-gray-700 rounded-xl leading-5 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span
                            class="text-gray-400 sm:text-sm font-mono border border-gray-200 dark:border-gray-700 rounded px-1.5 text-[10px]">Ctrl+K</span>
                    </div>
                </div>
                <!-- Search Results Dropdown -->
                <div x-show="showResults" x-transition
                    class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden z-50 max-h-80 overflow-y-auto"
                    x-cloak>
                    <template x-if="results.length === 0 && query.length >= 2 && !loading">
                        <div class="px-4 py-6 text-center text-sm text-gray-400">
                            {{ __('Aucun résultat trouvé') }}
                        </div>
                    </template>
                    <template x-for="(item, i) in results" :key="i">
                        <a :href="item.link"
                            class="flex items-center gap-3 px-4 py-3 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors border-b border-gray-50 dark:border-gray-700/50 last:border-0">
                            <div
                                class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fas text-indigo-500 text-xs" :class="item.icon"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-800 dark:text-white truncate" x-text="item.title"></p>
                                <p class="text-[10px] text-gray-400 truncate" x-text="item.subtitle"></p>
                            </div>
                            <span
                                class="text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 shrink-0"
                                x-text="item.type"></span>
                        </a>
                    </template>
                </div>
            </div>
        @endif
    </div>

    <!-- Right Side Actions -->
    <div class="flex items-center space-x-2 md:space-x-4">

        <!-- Dark Mode Toggle -->
        <button @click="darkMode = !darkMode"
            class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
            :title="darkMode ? '{{ __('Mode clair') }}' : '{{ __('Mode sombre') }}'">
            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
            <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </button>

        <!-- Language Switcher -->
        <div x-data="{ open: false }" class="relative hidden sm:block">
            <button @click="open = !open"
                class="flex items-center gap-1 p-2 rounded-lg text-sm font-bold text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors uppercase">
                {{ app()->getLocale() }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" @click.away="open = false" x-transition
                class="absolute right-0 mt-2 w-32 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden z-50"
                x-cloak>
                <a href="{{ route('lang.switch', 'fr') }}"
                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 transition-colors">Français</a>
                <a href="{{ route('lang.switch', 'en') }}"
                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 transition-colors">English</a>
                <a href="{{ route('lang.switch', 'ar') }}"
                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-600 transition-colors">العربية</a>
            </div>
        </div>

        <!-- Notifications -->
        <div x-data="{
                open: false,
                notifications: [],
                fetching: false,
                fetchNotifications() {
                    if (this.fetching) return;
                    this.fetching = true;
                    fetch('{{ route('notifications.index') }}')
                        .then(r => r.json())
                        .then(data => {
                            this.notifications = data;
                            this.fetching = false;
                        })
                        .catch(err => {
                            console.error('Erreur notifications:', err);
                            this.fetching = false;
                        });
                },
                handleNotifClick(notif) {
                    fetch(`/notifications/${notif.id}/read`, {
                        method: 'POST',
                        headers: { 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(() => {
                        if(notif.lien) {
                            window.location.href = notif.lien;
                        } else {
                            this.fetchNotifications();
                        }
                    }).catch(() => {
                        // Redirect anyway if API fails
                        if(notif.lien) window.location.href = notif.lien;
                    });
                },
                markAllRead() {
                    fetch('{{ route('notifications.read-all') }}', {
                        method: 'POST',
                        headers: { 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(() => {
                        this.notifications = [];
                        this.open = false;
                    });
                }
            }" x-init="fetchNotifications(); setInterval(() => fetchNotifications(), 30000)" class="relative">

            <button @click="open = !open"
                class="relative p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <template x-if="notifications.length > 0">
                    <span
                        class="absolute top-2 right-2 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[8px] font-black text-white ring-2 ring-white dark:ring-gray-800">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span x-text="notifications.length"></span>
                    </span>
                </template>
            </button>

            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="absolute right-0 mt-3 w-80 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden z-50"
                x-cloak>
                <div
                    class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 font-bold text-gray-800 dark:text-white flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/20">
                    <span>{{ __('Notifications') }}</span>
                    <span class="text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-black"
                        x-text="notifications.length"></span>
                </div>
                <div class="max-h-80 overflow-y-auto">
                    <template x-for="notif in notifications" :key="notif.id">
                        <div @click="handleNotifClick(notif)"
                            class="p-4 border-b border-gray-50 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all cursor-pointer group">
                            <div class="flex justify-between items-start">
                                <span
                                    class="text-[9px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded"
                                    x-text="notif.type"></span>
                                <span class="text-[8px] text-gray-400"
                                    x-text="new Date(notif.created_at).toLocaleDateString()"></span>
                            </div>
                            <p class="text-sm font-bold text-gray-800 dark:text-white mt-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors"
                                x-text="notif.titre"></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2"
                                x-text="notif.message"></p>
                        </div>
                    </template>
                    <template x-if="notifications.length === 0">
                        <div class="p-10 text-center flex flex-col items-center">
                            <svg class="w-10 h-10 text-gray-200 dark:text-gray-700 mb-3" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 italic">{{ __('Aucune nouvelle notification') }}</p>
                        </div>
                    </template>
                </div>
                <template x-if="notifications.length > 0">
                    <button @click="markAllRead()"
                        class="w-full py-3 text-center text-[10px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors border-t border-gray-100 dark:border-gray-700">
                        {{ __('Tout marquer comme lu') }}
                    </button>
                </template>
            </div>
        </div>

        <!-- User Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                class="flex items-center gap-2 p-1 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all border border-transparent hover:border-gray-200 dark:hover:border-gray-600">
                <div class="flex flex-col items-end hidden sm:flex">
                    <span class="text-sm font-bold text-gray-700 dark:text-white">{{ Auth::user()->prenom }}</span>
                    <span
                        class="text-[10px] font-bold text-indigo-500 uppercase tracking-tighter">{{ Auth::user()->role }}</span>
                </div>
                @if(Auth::user()->photo)
                    <img src="{{ asset('storage/' . Auth::user()->photo) }}"
                        class="w-10 h-10 rounded-lg object-cover ring-2 ring-indigo-500/10">
                @else
                    <div
                        class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold ring-2 ring-indigo-500/10">
                        {{ substr(Auth::user()->prenom, 0, 1) }}
                    </div>
                @endif
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="absolute right-0 mt-3 w-48 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 py-2 overflow-hidden"
                x-cloak>
                <a href="{{ route('profile.edit') }}"
                    class="flex items-center px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    {{ __('Mon profil') }}
                </a>
                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors text-left">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        {{ __('Déconnexion') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>