@extends('layouts.dashboard')

@section('title', __('Feuille de Présence'))
@section('page-title', __('Feuille de Présence') . ' — ' . $seance->module->nom)

@section('content')
<div class="space-y-6">
    <!-- Session Info Card -->
    <div class="bg-indigo-600 rounded-3xl p-8 text-white shadow-2xl flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <h3 class="text-2xl font-black uppercase tracking-tight mb-2">{{ $seance->groupe->nom }}</h3>
            <p class="text-indigo-100 font-bold flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ __('Séance du') }} {{ $seance->date->format('d/m/Y') }} ({{ $seance->heure_debut->format('H:i') }} - {{ $seance->heure_fin->format('H:i') }})
            </p>
        </div>
        <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-2xl border border-white/20 text-center">
            <span class="text-[10px] font-black uppercase tracking-widest block mb-1">{{ __('Effectif total') }}</span>
            <span class="text-3xl font-black">{{ $etudiants->count() }}</span>
        </div>
    </div>

    <!-- Attendance Form -->
    <form action="{{ route('professeur.absences.enregistrer', $seance) }}" method="POST">
        @csrf
        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('Liste des Étudiants') }}</h4>
                <p class="text-[10px] text-gray-400 italic">{{ __('Cochez les étudiants absents') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 divide-y md:divide-y-0 md:gap-px bg-gray-100 dark:bg-gray-700">
                @foreach($etudiants as $etu)
                <div class="bg-white dark:bg-gray-800 p-6 flex items-center justify-between group hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 font-black text-sm">
                            {{ substr($etu->user->prenom, 0, 1) }}{{ substr($etu->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800 dark:text-white leading-none">{{ $etu->user->full_name }}</p>
                            <p class="text-[9px] text-gray-400 mt-1 font-bold uppercase tracking-tighter">{{ $etu->cne }}</p>
                        </div>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="absents[]" value="{{ $etu->id }}"
                               {{ $etu->absences->count() > 0 ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-red-500 after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                    </label>
                </div>
                @endforeach
            </div>

            <div class="p-8 bg-gray-50 dark:bg-gray-900/50 flex justify-end gap-4 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('professeur.absences.index') }}" class="px-8 py-4 text-xs font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 transition-colors">{{ __('Annuler') }}</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-4 rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-xl shadow-indigo-100 dark:shadow-none">
                    {{ __('Enregistrer la feuille de présence') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
