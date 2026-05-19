<x-guest-layout>
    <div class="mb-8 text-center" data-aos="fade-down">
        <span class="inline-flex items-center px-3 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full text-[10px] font-black uppercase tracking-[0.2em] border border-emerald-100 dark:border-emerald-800 mb-3">
            {{ __('Portail Famille') }}
        </span>
        <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ __('Espace Parents') }}</h2>
        <p class="text-slate-500 dark:text-slate-400 font-medium mt-2">{{ __('Suivez la scolarité et les résultats de votre enfant') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div x-data="{ 
        email: '{{ old('email') }}', 
        password: '', 
        showPassword: false
    }" class="space-y-6">

        <form method="POST" action="{{ route('login.parent') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Adresse Email Étudiant') }}</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                    </span>
                    <input id="email" x-model="email" type="email" name="email" required autofocus 
                           class="input-field pl-11 focus:border-emerald-500 focus:ring-emerald-500/20" placeholder="{{ __('votre@email.com') }}">
                </div>
                <p class="text-[10px] text-slate-400 mt-1">{{ __('Utilisez la même adresse email que votre enfant') }}</p>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <div class="flex justify-between mb-2">
                    <label for="password" class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Mot de passe Étudiant') }}</label>
                </div>
                
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                    <input id="password" 
                           x-model="password"
                           :type="showPassword ? 'text' : 'password'"
                           class="input-field pl-11 focus:border-emerald-500 focus:ring-emerald-500/20"
                           name="password"
                           required autocomplete="current-password"
                           placeholder="••••••••">
                           
                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-emerald-500 transition-colors">
                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" name="remember">
                <label for="remember_me" class="ml-2 text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Se souvenir de moi') }}</label>
            </div>

            <button type="submit" class="w-full py-4 text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-500/20 font-black rounded-2xl transition-all shadow-lg hover:shadow-emerald-500/20 active:scale-[0.98] text-base tracking-tight text-center">
                {{ __('Se connecter à l\'Espace Parents') }}
            </button>

            <div class="text-center mt-6 pt-6 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-200 dark:border-slate-700 hover:border-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14"/></svg>
                    {{ __('Retour à l\'Espace Étudiant / Enseignant / Admin') }}
                </a>
            </div>
        </form>

        <!-- Accès Rapide (Mode Soutenance) -->
        @if(isset($etudiants) && $etudiants->count() > 0)
            <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-center mb-4">{{ __('Accès Rapide (Parents d\'Étudiants)') }}</h3>
                <div class="flex flex-wrap gap-2 justify-center max-h-32 overflow-y-auto custom-scrollbar p-2">
                    @foreach($etudiants as $etu)
                        <button type="button" @click="email = '{{ $etu->email }}'; password = 'password';" class="text-xs font-bold px-3 py-1.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-lg hover:bg-emerald-100 dark:hover:bg-emerald-800/50 transition-colors border border-emerald-100 dark:border-emerald-800/30">
                            Parent de {{ trim(($etu->prenom ?? '') . ' ' . ($etu->name ?? '')) ?: $etu->email }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(16, 185, 129, 0.2); border-radius: 4px; }
    </style>
</x-guest-layout>
