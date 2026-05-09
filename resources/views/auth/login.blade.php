<x-guest-layout>
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
        } 
    }">

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" x-model="email" class="block mt-1 w-full" type="email" name="email" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                
                <div class="relative">
                    <input id="password" 
                           x-model="password"
                           :type="showPassword ? 'text' : 'password'"
                           class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                           name="password"
                           required autocomplete="current-password" />
                           
                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                    </button>
                </div>

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4 flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('LOG IN') }}
                </button>
            </div>
        </form>

        <!-- Mode Démo / Soutenance -->
        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="text-center mb-4">
                <span class="bg-indigo-100 text-indigo-800 text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full">Mode Soutenance (Comptes de test)</span>
            </div>

            <div x-data="{ roleTab: 'admin' }">
                <div class="flex gap-2 mb-4 justify-center">
                    @foreach(['admin' => 'Admins', 'professeur' => 'Profs', 'etudiant' => 'Étudiants'] as $key => $label)
                        @if(isset($demoUsers[$key]))
                        <button @click="roleTab = '{{ $key }}'" 
                                :class="roleTab === '{{ $key }}' ? 'bg-gray-800 text-white dark:bg-gray-100 dark:text-gray-900' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-400'"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                            {{ $label }} ({{ count($demoUsers[$key]) }})
                        </button>
                        @endif
                    @endforeach
                </div>

                <div class="space-y-2 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                    @foreach(['admin', 'professeur', 'etudiant'] as $role)
                        @if(isset($demoUsers[$role]))
                        <div x-show="roleTab === '{{ $role }}'" x-transition class="space-y-2">
                            @foreach($demoUsers[$role] as $u)
                            <button @click="fillAccount('{{ addslashes($u->email) }}')" type="button" class="w-full flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 border border-gray-100 dark:border-gray-700 rounded-xl transition-colors text-left group">
                                <div>
                                    <p class="text-sm font-bold text-gray-800 dark:text-white group-hover:text-indigo-600 transition-colors">{{ $u->prenom }} {{ strtoupper($u->name) }}</p>
                                    <p class="text-[10px] text-gray-500">{{ $u->email }}</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                            @endforeach
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <p class="text-center text-[10px] text-gray-400 mt-4 italic">Cliquez sur un compte pour auto-remplir le formulaire. Mot de passe par défaut : 'password'.</p>
        </div>

    </div>
    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; }
    </style>
</x-guest-layout>
