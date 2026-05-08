@extends('layouts.dashboard')

@section('title', 'Gestion des Salles')
@section('page-title', isset($salle) ? 'Modifier la salle' : 'Nouvelle salle')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.salles.index') }}" class="flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Retour à la liste
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
        <div class="p-8 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
            <h2 class="text-2xl font-extrabold text-gray-800 dark:text-white">
                {{ isset($salle) ? 'Modifier : ' . $salle->nom : 'Créer une salle' }}
            </h2>
        </div>

        <form action="{{ isset($salle) ? route('admin.salles.update', $salle) : route('admin.salles.store') }}" 
              method="POST" class="p-8 space-y-6">
            @csrf
            @if(isset($salle)) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="nom" class="text-sm font-bold text-gray-700 dark:text-gray-300">Nom / Numéro de salle</label>
                    <input type="text" name="nom" id="nom" value="{{ old('nom', $salle->nom ?? '') }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 @error('nom') border-red-500 @enderror">
                </div>

                <div class="space-y-2">
                    <label for="capacite" class="text-sm font-bold text-gray-700 dark:text-gray-300">Capacité (places)</label>
                    <input type="number" name="capacite" id="capacite" value="{{ old('capacite', $salle->capacite ?? '') }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="space-y-2">
                    <label for="type" class="text-sm font-bold text-gray-700 dark:text-gray-300">Type de salle</label>
                    <select name="type" id="type" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500">
                        <option value="cours" {{ old('type', $salle->type ?? '') == 'cours' ? 'selected' : '' }}>Salle de cours</option>
                        <option value="td" {{ old('type', $salle->type ?? '') == 'td' ? 'selected' : '' }}>Salle de TD</option>
                        <option value="tp" {{ old('type', $salle->type ?? '') == 'tp' ? 'selected' : '' }}>Salle de TP (Labo)</option>
                        <option value="amphi" {{ old('type', $salle->type ?? '') == 'amphi' ? 'selected' : '' }}>Amphithéâtre</option>
                    </select>
                </div>

                <div class="flex items-center h-full pt-8">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_disponible" value="1" class="sr-only peer" {{ old('is_disponible', $salle->is_disponible ?? true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                        <span class="ml-3 text-sm font-bold text-gray-700 dark:text-gray-300">Salle opérationnelle</span>
                    </label>
                </div>
            </div>

            <div class="space-y-2">
                <label for="equipements" class="text-sm font-bold text-gray-700 dark:text-gray-300">Équipements (vidéoprojecteur, PC, clim...)</label>
                <textarea name="equipements" id="equipements" rows="3" 
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500">{{ old('equipements', $salle->equipements ?? '') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('admin.salles.index') }}" class="btn-secondary">Annuler</a>
                <button type="submit" class="btn-primary px-8">
                    {{ isset($salle) ? 'Mettre à jour' : 'Créer la salle' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
