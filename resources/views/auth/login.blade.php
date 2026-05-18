<x-guest-layout>
    <div class="mb-8 text-center" data-aos="fade-down">
        <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ __('Bienvenue') }}</h2>
        <p class="text-slate-500 dark:text-slate-400 font-medium mt-2">{{ __('Connectez-vous à votre espace UPF') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @php
        $demoUsers = \App\Models\User::where('is_active', true)->orderBy('prenom')->get()->groupBy('role');
    @endphp

    <div x-data="{ 
        email: '{{ old('email') }}', 
        password: '', 
        showPassword: false,
        fillAccount(userEmail) { 
            this.email = userEmail; 
            this.password = 'password'; 
            Notyf.success('{{ __('Identifiants remplis !') }}');
        } 
    }" class="space-y-6">

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Adresse Email') }}</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                    </span>
                    <input id="email" x-model="email" type="email" name="email" required autofocus 
                           class="input-field pl-11" placeholder="{{ __('votre@email.com') }}">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <div class="flex justify-between mb-2">
                    <label for="password" class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Mot de passe') }}</label>
                    @if (Route::has('password.request'))
                        <a class="text-xs font-bold text-indigo-600 hover:text-indigo-500 transition-colors" href="{{ route('password.request') }}">
                            {{ __('Oublié ?') }}
                        </a>
                    @endif
                </div>
                
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                    <input id="password" 
                           x-model="password"
                           :type="showPassword ? 'text' : 'password'"
                           class="input-field pl-11"
                           name="password"
                           required autocomplete="current-password"
                           placeholder="••••••••">
                           
                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-indigo-500 transition-colors">
                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" name="remember">
                <label for="remember_me" class="ml-2 text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Se souvenir de moi') }}</label>
            </div>

            <button type="submit" class="btn-primary w-full py-4 text-base tracking-tight">
                {{ __('Se connecter') }}
            </button>

            <div class="text-center mt-4">
                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ __('Pas encore de compte ?') }}</span>
                <a href="{{ route('register') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-500 transition-colors ml-1">
                    {{ __('Créer un compte étudiant') }}
                </a>
            </div>
        </form>

        <!-- Mode Démo / Soutenance -->
        <div class="mt-10 pt-8 border-t border-slate-100 dark:border-slate-800">
            <div class="text-center mb-6">
                <span class="inline-flex items-center px-3 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border border-indigo-100 dark:border-indigo-800">
                    {{ __('Mode Soutenance') }}
                </span>
            </div>

            <div x-data="{ roleTab: 'admin' }">
                <div class="flex p-1 bg-slate-100 dark:bg-slate-800 rounded-xl mb-6">
                    @foreach(['admin' => 'Admins', 'professeur' => 'Profs', 'etudiant' => 'Étudiants'] as $key => $label)
                        @if(isset($demoUsers[$key]))
                        <button @click="roleTab = '{{ $key }}'" 
                                :class="roleTab === '{{ $key }}' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                                class="flex-1 py-2 text-[11px] font-bold rounded-lg transition-all">
                            {{ $label }}
                        </button>
                        @endif
                    @endforeach
                </div>

                <div class="space-y-2 max-h-56 overflow-y-auto pr-2 custom-scrollbar">
                    @foreach(['admin', 'professeur', 'etudiant'] as $role)
                        @if(isset($demoUsers[$role]))
                        <div x-show="roleTab === '{{ $role }}'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-2">
                            @foreach($demoUsers[$role] as $u)
                            <button @click="fillAccount('{{ addslashes($u->email) }}')" type="button" class="w-full flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 border border-slate-100 dark:border-slate-800 rounded-2xl transition-all text-left group">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 text-xs font-black">
                                        {{ substr($u->prenom, 0, 1) }}{{ substr($u->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-800 dark:text-white group-hover:text-indigo-600 transition-colors">{{ $u->prenom }} {{ strtoupper($u->name) }}</p>
                                        <p class="text-[9px] font-medium text-slate-500">{{ $u->email }}</p>
                                    </div>
                                </div>
                                <svg class="w-4 h-4 text-slate-300 group-hover:text-indigo-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </button>
                            @endforeach
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-guest-layout>
