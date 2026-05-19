@extends('layouts.dashboard')

@section('title', __('Créer un utilisateur'))
@section('page-title', __('Nouvel Utilisateur'))

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('Retour à la liste') }}
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
        <div class="p-8 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
            <h2 class="text-2xl font-extrabold text-gray-800 dark:text-white">{{ __('Ajouter un collaborateur') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Créez un nouvel accès pour un administrateur, professeur ou étudiant.') }}</p>
        </div>

        {{-- Données Alpine passées via script pour éviter les problèmes d'échappement HTML --}}
        <script>
            window._userCreateData = {
                modulesParFiliere: {!! json_encode($modulesParFiliere) !!},
                niveaux: {!! $niveaux->toJson() !!},
                groupes: {!! $groupes->toJson() !!},
                modulesSelectionnes: {!! json_encode($modulesSelectionnes) !!},
            };
        </script>

        <div x-data="{
                role: '{{ old('role', 'etudiant') }}',
                filiereId: '{{ old('filiere_id', '') }}',
                niveaux: window._userCreateData.niveaux,
                niveauId: '{{ old('niveau_id', '') }}',
                groupes: window._userCreateData.groupes,
                groupeId: '{{ old('groupe_id', '') }}',
                modulesParFiliere: window._userCreateData.modulesParFiliere,
                modulesSelectionnes: window._userCreateData.modulesSelectionnes,
                searchModule: '',
                loadingNiveaux: false,
                loadingGroupes: false,

                get modulesCount() { return this.modulesSelectionnes.length; },

                filteredGroups() {
                    if (!this.searchModule) return this.modulesParFiliere;
                    const q = this.searchModule.toLowerCase();
                    return this.modulesParFiliere.map(g => ({
                        filiere: g.filiere,
                        modules: g.modules.filter(m =>
                            m.nom.toLowerCase().includes(q) || m.code.toLowerCase().includes(q)
                        )
                    })).filter(g => g.modules.length > 0);
                },

                async onFiliereChange() {
                    if (!this.filiereId) {
                        this.niveaux = []; this.niveauId = '';
                        this.groupes = []; this.groupeId = '';
                        return;
                    }
                    this.loadingNiveaux = true;
                    this.niveauId = ''; this.groupes = []; this.groupeId = '';
                    const res = await fetch('/admin/filieres/' + this.filiereId + '/niveaux');
                    this.niveaux = await res.json();
                    this.loadingNiveaux = false;
                },

                async onNiveauChange() {
                    if (!this.niveauId) { this.groupes = []; this.groupeId = ''; return; }
                    this.loadingGroupes = true;
                    this.groupeId = '';
                    const res = await fetch('/admin/niveaux/' + this.niveauId + '/groupes');
                    this.groupes = await res.json();
                    this.loadingGroupes = false;
                }
            }">

            <form action="{{ route('admin.users.store') }}" method="POST" class="p-8 space-y-6">
                @csrf

                {{-- Champs communs --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('Nom') }}</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('Prénom') }}</label>
                        <input type="text" name="prenom" value="{{ old('prenom') }}" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('prenom') border-red-500 @enderror">
                        @error('prenom') <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('Email professionnel') }}</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('Téléphone') }}</label>
                        <input type="text" name="telephone" value="{{ old('telephone') }}"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ __("Rôle au sein de l'UPF") }}</label>
                        <select name="role" x-model="role" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('role') border-red-500 @enderror">
                            <option value="etudiant">{{ __('Étudiant') }}</option>
                            <option value="professeur">{{ __('Professeur') }}</option>
                            <option value="admin">{{ __('Administrateur') }}</option>
                        </select>
                        @error('role') <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center h-full pt-8">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', true) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                            <span class="ml-3 text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('Compte activé') }}</span>
                        </label>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('Mot de passe') }}</label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('password') border-red-500 @enderror">
                        @error('password') <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('Confirmer le mot de passe') }}</label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                    </div>
                </div>

                {{-- ─── Section Étudiant ───────────────────────────────────── --}}
                <div x-show="role === 'etudiant'" x-cloak
                     class="p-6 bg-green-50/60 dark:bg-green-900/10 rounded-2xl border border-green-200 dark:border-green-800/40 space-y-5">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-green-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-extrabold text-green-800 dark:text-green-300">{{ __('Affectation académique') }}</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Filière --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 dark:text-gray-300">
                                {{ __('Filière') }} <span class="text-red-500">*</span>
                            </label>
                            <select name="filiere_id" x-model="filiereId" @change="onFiliereChange()"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('filiere_id') border-red-500 @enderror">
                                <option value="">-- {{ __('Choisir une filière') }} --</option>
                                @foreach($filieres as $filiere)
                                    <option value="{{ $filiere->id }}">{{ $filiere->nom }}</option>
                                @endforeach
                            </select>
                            @error('filiere_id') <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Niveau --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 dark:text-gray-300">
                                {{ __('Niveau') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="niveau_id" x-model="niveauId" @change="onNiveauChange()"
                                        :disabled="niveaux.length === 0"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all disabled:opacity-50 @error('niveau_id') border-red-500 @enderror">
                                    <option value="">-- {{ __('Choisir un niveau') }} --</option>
                                    <template x-for="n in niveaux" :key="n.id">
                                        <option :value="String(n.id)" x-text="n.nom"></option>
                                    </template>
                                </select>
                                <div x-show="loadingNiveaux" class="absolute right-3 top-1/2 -translate-y-1/2">
                                    <svg class="animate-spin w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('niveau_id') <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Groupe --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 dark:text-gray-300">
                                {{ __('Groupe') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="groupe_id" x-model="groupeId"
                                        :disabled="groupes.length === 0"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all disabled:opacity-50 @error('groupe_id') border-red-500 @enderror">
                                    <option value="">-- {{ __('Choisir un groupe') }} --</option>
                                    <template x-for="g in groupes" :key="g.id">
                                        <option :value="String(g.id)" x-text="g.nom"></option>
                                    </template>
                                </select>
                                <div x-show="loadingGroupes" class="absolute right-3 top-1/2 -translate-y-1/2">
                                    <svg class="animate-spin w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('groupe_id') <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- ─── Section Professeur ─────────────────────────────────── --}}
                <div x-show="role === 'professeur'" x-cloak
                     class="p-6 bg-blue-50/60 dark:bg-blue-900/10 rounded-2xl border border-blue-200 dark:border-blue-800/40 space-y-5">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-extrabold text-blue-800 dark:text-blue-300">{{ __('Profil enseignant') }}</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('Spécialité') }}</label>
                            <input type="text" name="specialite" value="{{ old('specialite') }}"
                                   placeholder="{{ __('ex: Docteur en Informatique') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('specialite') border-red-500 @enderror">
                            @error('specialite') <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 dark:text-gray-300">
                                {{ __('Grade') }} <span class="text-red-500">*</span>
                            </label>
                            <select name="grade"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('grade') border-red-500 @enderror">
                                <option value="assistant" {{ old('grade') == 'assistant' ? 'selected' : '' }}>{{ __('Assistant') }}</option>
                                <option value="maitre_assistant" {{ old('grade') == 'maitre_assistant' ? 'selected' : '' }}>{{ __('Maître Assistant') }}</option>
                                <option value="professeur" {{ old('grade') == 'professeur' ? 'selected' : '' }}>{{ __('Professeur') }}</option>
                                <option value="vacataire" {{ old('grade') == 'vacataire' ? 'selected' : '' }}>{{ __('Vacataire') }}</option>
                            </select>
                            @error('grade') <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Modules --}}
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-bold text-gray-700 dark:text-gray-300">
                                {{ __('Modules enseignés') }} <span class="text-red-500">*</span>
                            </label>
                            <span class="text-xs font-bold px-2 py-1 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300"
                                  x-text="modulesCount + ' {{ __('sélectionné(s)') }}'"></span>
                        </div>

                        @error('modules') <p class="text-xs font-bold text-red-500">{{ $message }}</p> @enderror

                        <div class="relative">
                            <input type="text" x-model="searchModule" placeholder="{{ __('Rechercher un module...') }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 text-sm">
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>

                        <div class="max-h-72 overflow-y-auto rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 divide-y divide-gray-100 dark:divide-gray-600">
                            <template x-for="groupe in filteredGroups()" :key="groupe.filiere">
                                <div>
                                    <div class="px-4 py-2 bg-gray-50 dark:bg-gray-800/50 sticky top-0">
                                        <span class="text-xs font-extrabold uppercase tracking-wider text-gray-500 dark:text-gray-400" x-text="groupe.filiere"></span>
                                    </div>
                                    <template x-for="module in groupe.modules" :key="module.id">
                                        <label class="flex items-center gap-3 px-4 py-3 cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors">
                                            <input type="checkbox"
                                                   name="modules[]"
                                                   :value="String(module.id)"
                                                   x-model="modulesSelectionnes"
                                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <div>
                                                <span class="text-sm font-semibold text-gray-800 dark:text-white" x-text="module.nom"></span>
                                                <span class="ml-2 text-xs text-gray-400 dark:text-gray-500" x-text="'(' + module.code + ')'"></span>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </template>
                            <template x-if="filteredGroups().length === 0">
                                <div class="px-4 py-8 text-center text-sm text-gray-400">{{ __('Aucun module trouvé') }}</div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary">{{ __('Annuler') }}</a>
                    <button type="submit" class="btn-primary px-8">{{ __("Créer l'utilisateur") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
