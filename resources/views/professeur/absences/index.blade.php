@extends('layouts.dashboard')

@section('title', 'Gestion des Absences')
@section('page-title', 'Gestion des Absences')

@section('content')
<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-sm font-black text-gray-800 dark:text-white uppercase tracking-widest">Liste des Séances</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/50">
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Module</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Groupe</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date & Heure</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Statut</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($seances as $seance)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $seance->module->nom }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase">
                                {{ $seance->groupe->nom }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $seance->date->format('d/m/Y') }}</span>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">{{ $seance->heure_debut->format('H:i') }} - {{ $seance->heure_fin->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($seance->statut === 'effectuee')
                                <span class="px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[9px] font-black uppercase">Effectuée</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-[9px] font-black uppercase">À traiter</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('professeur.absences.feuille', $seance) }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                                {{ $seance->statut === 'effectuee' ? 'Modifier' : 'Saisir présences' }}
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 font-bold italic">
                            Aucune séance programmée pour aujourd'hui.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
