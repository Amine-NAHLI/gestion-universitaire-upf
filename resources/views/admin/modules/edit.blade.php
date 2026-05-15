@extends('layouts.dashboard')

@section('title', __('Gestion des Modules'))
@section('page-title', isset($module) ? __('Modifier le module') : __('Nouveau module'))

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.modules.index') }}" class="flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('Retour à la liste') }}
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
        <div class="p-8 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
            <h2 class="text-2xl font-extrabold text-gray-800 dark:text-white">
                {{ isset($module) ? __('Modifier') . ' : ' . $module->nom : __('Créer un module') }}
            </h2>
        </div>

        <form action="{{ isset($module) ? route('admin.modules.update', $module) : route('admin.modules.store') }}"
              method="POST" class="p-8 space-y-6">
            @csrf
            @if(isset($module)) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="nom" class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('Nom du module') }}</label>
                    <input type="text" name="nom" id="nom" value="{{ old('nom', $module->nom ?? '') }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 @error('nom') border-red-500 @enderror">
                </div>

                <div class="space-y-2">
                    <label for="code" class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('Code (ex: M11, GI-01...)') }}</label>
                    <input type="text" name="code" id="code" value="{{ old('code', $module->code ?? '') }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 @error('code') border-red-500 @enderror">
                </div>

                <div class="space-y-2">
                    <label for="niveau_id" class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('Filière / Niveau') }}</label>
                    <select name="niveau_id" id="niveau_id" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500">
                        @foreach($niveaux->groupBy('filiere.nom') as $filiereNom => $niveauxFiliere)
                            <optgroup label="{{ $filiereNom }}">
                                @foreach($niveauxFiliere as $niveau)
                                    <option value="{{ $niveau->id }}" {{ old('niveau_id', $module->niveau_id ?? '') == $niveau->id ? 'selected' : '' }}>
                                        {{ $niveau->nom }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="semestre" class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('Semestre') }}</label>
                    <select name="semestre" id="semestre" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500">
                        @foreach(range(1, 6) as $s)
                            <option value="{{ $s }}" {{ old('semestre', $module->semestre ?? '') == $s ? 'selected' : '' }}>{{ __('Semestre') }} {{ $s }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2 md:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-4 bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-500 uppercase">{{ __('Coefficient') }}</label>
                        <input type="number" step="0.5" name="coefficient" value="{{ old('coefficient', $module->coefficient ?? 1) }}" required class="w-full px-3 py-2 rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-blue-500 uppercase">{{ __('Cours') }} (H)</label>
                        <input type="number" name="heures_cours" value="{{ old('heures_cours', $module->heures_cours ?? 0) }}" required class="w-full px-3 py-2 rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-purple-500 uppercase">TD (H)</label>
                        <input type="number" name="heures_td" value="{{ old('heures_td', $module->heures_td ?? 0) }}" required class="w-full px-3 py-2 rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-pink-500 uppercase">TP (H)</label>
                        <input type="number" name="heures_tp" value="{{ old('heures_tp', $module->heures_tp ?? 0) }}" required class="w-full px-3 py-2 rounded-lg border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <label for="description" class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('Description / Objectifs') }}</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500">{{ old('description', $module->description ?? '') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('admin.modules.index') }}" class="btn-secondary">{{ __('Annuler') }}</a>
                <button type="submit" class="btn-primary px-8">
                    {{ isset($module) ? __('Mettre à jour') : __('Enregistrer') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
