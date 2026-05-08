@extends('layouts.dashboard')

@section('title', 'Modifier l\'utilisateur')
@section('page-title', 'Modifier : ' . $user->full_name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Retour à la liste
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
        <div class="p-8 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30 flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white text-xl font-bold shrink-0 
                {{ $user->role === 'admin' ? 'bg-red-500' : ($user->role === 'professeur' ? 'bg-blue-500' : 'bg-green-500') }}">
                {{ strtoupper(substr($user->prenom ?? 'U', 0, 1)) }}{{ strtoupper(substr($user->name ?? '', 0, 1)) }}
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-gray-800 dark:text-white">Modifier : {{ $user->full_name }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">ID Utilisateur: #{{ $user->id }} • Inscrit le {{ $user->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom -->
                <div class="space-y-2">
                    <label for="name" class="text-sm font-bold text-gray-700 dark:text-gray-300">Nom</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror">
                    @error('name') <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Prénom -->
                <div class="space-y-2">
                    <label for="prenom" class="text-sm font-bold text-gray-700 dark:text-gray-300">Prénom</label>
                    <input type="text" name="prenom" id="prenom" value="{{ old('prenom', $user->prenom) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('prenom') border-red-500 @enderror">
                    @error('prenom') <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="text-sm font-bold text-gray-700 dark:text-gray-300">Email professionnel</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('email') border-red-500 @enderror">
                    @error('email') <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Téléphone -->
                <div class="space-y-2">
                    <label for="telephone" class="text-sm font-bold text-gray-700 dark:text-gray-300">Téléphone</label>
                    <input type="text" name="telephone" id="telephone" value="{{ old('telephone', $user->telephone) }}"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('telephone') border-red-500 @enderror">
                    @error('telephone') <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Rôle -->
                <div class="space-y-2">
                    <label for="role" class="text-sm font-bold text-gray-700 dark:text-gray-300">Rôle au sein de l'UPF</label>
                    <select name="role" id="role" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('role') border-red-500 @enderror">
                        <option value="etudiant" {{ old('role', $user->role) == 'etudiant' ? 'selected' : '' }}>Étudiant</option>
                        <option value="professeur" {{ old('role', $user->role) == 'professeur' ? 'selected' : '' }}>Professeur</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrateur</option>
                    </select>
                    @error('role') <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Statut Actif -->
                <div class="flex items-center h-full pt-8">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                        <span class="ml-3 text-sm font-bold text-gray-700 dark:text-gray-300">Compte activé</span>
                    </label>
                </div>
            </div>

            <div class="mt-8 p-6 bg-indigo-50/50 dark:bg-indigo-900/10 rounded-2xl border border-indigo-100 dark:border-indigo-900/30">
                <div class="flex items-center gap-3 mb-4">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <h3 class="text-sm font-bold text-indigo-800 dark:text-indigo-300">Sécurité (Optionnel)</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="password" class="text-sm font-bold text-gray-700 dark:text-gray-300">Nouveau mot de passe</label>
                        <input type="password" name="password" id="password" placeholder="Laisser vide pour ne pas changer"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        @error('password') <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="text-sm font-bold text-gray-700 dark:text-gray-300">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">Annuler</a>
                <button type="submit" class="btn-primary px-8">
                    Mettre à jour l'utilisateur
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
