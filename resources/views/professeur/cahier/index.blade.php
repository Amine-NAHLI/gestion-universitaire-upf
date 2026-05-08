@extends('layouts.dashboard')

@section('title', 'Cahier de Textes')
@section('page-title', 'Cahier de Textes')

@section('content')
<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-sm font-black text-gray-800 dark:text-white uppercase tracking-widest">Suivi des Séances</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/50">
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Module / Groupe</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">État</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($seances as $seance)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $seance->date->format('d/m/Y') }}</span>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">{{ $seance->heure_debut->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $seance->module->nom }}</span>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[9px] font-black text-indigo-500 uppercase tracking-widest">{{ $seance->groupe->nom }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($seance->cahierTexte)
                                <span class="px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[9px] font-black uppercase tracking-widest flex items-center gap-1 w-max">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                    </svg>
                                    Rempli
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-[9px] font-black uppercase tracking-widest flex items-center gap-1 w-max">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.828a1 1 0 101.414-1.414L11 9.586V6z"/>
                                    </svg>
                                    À compléter
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('professeur.cahier.create', $seance) }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                                {{ $seance->cahierTexte ? 'Modifier' : 'Remplir le cahier' }}
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 font-bold italic">
                            Aucune séance effectuée trouvée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
