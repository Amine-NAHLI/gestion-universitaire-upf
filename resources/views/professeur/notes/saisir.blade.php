@extends('layouts.dashboard')

@section('title', 'Saisie - ' . $module->nom)
@section('page-title', 'Notes — ' . $module->nom . ' | ' . $groupe->nom)

@section('content')
<div class="space-y-6">
    <!-- Header Info -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="bg-indigo-100 dark:bg-indigo-900/30 p-3 rounded-2xl text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-black text-gray-800 dark:text-white uppercase tracking-wider">Calcul de la Note Finale</h3>
                <p class="text-[10px] font-bold text-gray-400">Formule : ((CC1 + CC2) / 2 × 0.4) + (Examen × 0.6)</p>
            </div>
        </div>
        <div class="text-right">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Année Universitaire</span>
            <span class="text-sm font-black text-indigo-600">2025 - 2026</span>
        </div>
    </div>

    <!-- Notes Form -->
    <form action="{{ route('professeur.notes.enregistrer', [$module, $groupe]) }}" method="POST">
        @csrf
        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50">
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest w-1/3">Étudiant</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">CC1 (40%)</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">CC2 (40%)</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Examen (60%)</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Note Finale</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($etudiants as $etudiant)
                        @php $note = $etudiant->notes->first(); @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors" x-data="{ cc1: '{{ $note->cc1 ?? '' }}', cc2: '{{ $note->cc2 ?? '' }}', examen: '{{ $note->examen ?? '' }}' }">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 text-[10px] font-black">
                                        {{ substr($etudiant->user->prenom, 0, 1) }}{{ substr($etudiant->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800 dark:text-white leading-none">{{ $etudiant->user->full_name }}</p>
                                        <p class="text-[9px] text-gray-400 mt-1 uppercase font-bold">{{ $etudiant->cne }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <input type="number" step="0.25" min="0" max="20" name="notes[{{ $etudiant->id }}][cc1]" x-model="cc1"
                                       class="w-20 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-center text-sm font-bold focus:ring-indigo-500">
                            </td>
                            <td class="px-6 py-4 text-center">
                                <input type="number" step="0.25" min="0" max="20" name="notes[{{ $etudiant->id }}][cc2]" x-model="cc2"
                                       class="w-20 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-center text-sm font-bold focus:ring-indigo-500">
                            </td>
                            <td class="px-6 py-4 text-center">
                                <input type="number" step="0.25" min="0" max="20" name="notes[{{ $etudiant->id }}][examen]" x-model="examen"
                                       class="w-20 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-center text-sm font-bold focus:ring-indigo-500">
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-base font-black" 
                                      :class="(parseFloat(cc1) || parseFloat(cc2) || parseFloat(examen)) ? (((( (parseFloat(cc1)||0) + (parseFloat(cc2)||0) ) / 2) * 0.4) + ((parseFloat(examen)||0) * 0.6) >= 10 ? 'text-emerald-500' : 'text-red-500') : 'text-gray-300'"
                                      x-text="(parseFloat(cc1) || parseFloat(cc2) || parseFloat(examen)) ? (((( (parseFloat(cc1)||0) + (parseFloat(cc2)||0) ) / 2) * 0.4) + ((parseFloat(examen)||0) * 0.6)).toFixed(2) : '--'">
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-8 bg-gray-50 dark:bg-gray-900/50 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-4 rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-xl shadow-indigo-200 dark:shadow-none">
                    Enregistrer toutes les notes
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
