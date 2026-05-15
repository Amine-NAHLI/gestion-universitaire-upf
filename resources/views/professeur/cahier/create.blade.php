@extends('layouts.dashboard')

@section('title', __('Saisie Cahier de Textes'))
@section('page-title', __('Cahier de Textes') . ' — ' . $seance->module->nom)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Info Banner -->
    <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 flex flex-wrap gap-8">
        <div>
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">{{ __('Date') }}</span>
            <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $seance->date->format('d/m/Y') }}</span>
        </div>
        <div>
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">{{ __('Horaire') }}</span>
            <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $seance->heure_debut->format('H:i') }} - {{ $seance->heure_fin->format('H:i') }}</span>
        </div>
        <div>
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">{{ __('Groupe') }}</span>
            <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $seance->groupe->nom }}</span>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
        <div class="p-8 border-b border-gray-50 dark:border-gray-700">
            <h3 class="text-lg font-black text-gray-800 dark:text-white uppercase tracking-wider">{{ __('Compte-rendu de la séance') }}</h3>
        </div>
        <form action="{{ route('professeur.cahier.store', $seance) }}" method="POST" class="p-8 space-y-6">
            @csrf

            <div class="space-y-1">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Nature de la séance') }}</label>
                <div class="grid grid-cols-3 gap-4 mt-2">
                    @foreach(['cours', 'td', 'tp'] as $nature)
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="nature" value="{{ $nature }}"
                               {{ (old('nature', $cahier->nature) == $nature) || ($nature == 'cours' && !$cahier->nature) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="bg-gray-50 dark:bg-gray-900/50 border border-transparent peer-checked:border-indigo-600 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 p-4 rounded-2xl text-center transition-all">
                            <span class="text-xs font-black uppercase tracking-widest peer-checked:text-indigo-600 dark:text-gray-400 dark:peer-checked:text-indigo-400">{{ strtoupper($nature) }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="space-y-1">
                <label for="objectif" class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Objectif pédagogique') }}</label>
                <textarea name="objectif" id="objectif" rows="3" required minlength="10"
                          placeholder="{{ __('Décrivez l\'objectif principal de cette séance...') }}"
                          class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500">{{ old('objectif', $cahier->objectif) }}</textarea>
            </div>

            <div class="space-y-1">
                <label for="contenu" class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Détails du contenu (optionnel)') }}</label>
                <textarea name="contenu" id="contenu" rows="6"
                          placeholder="{{ __('Chapitres abordés, exercices effectués, outils utilisés...') }}"
                          class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500">{{ old('contenu', $cahier->contenu) }}</textarea>
            </div>

            <div class="pt-6 flex justify-end gap-4">
                <a href="{{ route('professeur.cahier.index') }}" class="px-8 py-4 text-xs font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 transition-colors">{{ __('Annuler') }}</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-4 rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-xl shadow-indigo-100 dark:shadow-none">
                    {{ __('Enregistrer le compte-rendu') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
