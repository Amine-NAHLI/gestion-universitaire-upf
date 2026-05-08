@extends('layouts.dashboard')

@section('title', 'Mes Absences')
@section('page-title', 'Mes Absences')

@section('content')
<div class="space-y-6">
    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl">
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Total Absences</p>
            <span class="text-4xl font-black text-gray-800 dark:text-white">{{ $stats['total'] }}</span>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl">
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Justifiées</p>
            <span class="text-4xl font-black text-emerald-500">{{ $stats['justifiees'] }}</span>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl">
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Non Justifiées</p>
            <span class="text-4xl font-black text-red-500">{{ $stats['non_justifiees'] }}</span>
        </div>
    </div>

    <!-- Exclusion Alert -->
    @if($stats['total'] > 3)
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl flex items-center gap-4 animate-pulse">
        <div class="bg-red-500 text-white rounded-full p-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div>
            <p class="text-red-800 font-black uppercase text-xs tracking-wider">Alerte Exclusion</p>
            <p class="text-red-700 text-sm">Attention : vous avez dépassé 3 absences. Risque d'exclusion majeur.</p>
        </div>
    </div>
    @endif

    <!-- Absences Table -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-black text-gray-800 dark:text-white uppercase tracking-wider">Historique des Absences</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/50">
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Module</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date & Heure</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Justifiée</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Statut Justificatif</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($absences as $absence)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $absence->seance->module->nom }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $absence->seance->date->format('d/m/Y') }}</span>
                            <p class="text-[10px] text-gray-400">{{ $absence->seance->heure_debut->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($absence->justifiee)
                                <span class="text-emerald-500 font-black text-xl">✓</span>
                            @else
                                <span class="text-red-500 font-black text-xl">✗</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($absence->justificatif)
                                @php
                                    $statuts = [
                                        'en_attente' => ['bg-amber-100', 'text-amber-600', 'En attente'],
                                        'validee' => ['bg-emerald-100', 'text-emerald-600', 'Accepté'],
                                        'refusee' => ['bg-red-100', 'text-red-600', 'Refusé'],
                                    ];
                                    $s = $statuts[$absence->justificatif->statut] ?? ['bg-gray-100', 'text-gray-600', $absence->justificatif->statut];
                                @endphp
                                <span class="px-3 py-1 rounded-full {{ $s[0] }} dark:bg-opacity-20 text-xs font-bold">
                                    {{ $s[2] }}
                                </span>
                            @else
                                <span class="text-xs text-gray-400 italic">Aucun document</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(!$absence->justifiee && (!$absence->justificatif || $absence->justificatif->statut === 'refusee'))
                                <a href="{{ route('etudiant.justificatifs.index') }}" class="text-indigo-600 hover:text-indigo-700 text-xs font-black uppercase tracking-widest border-b-2 border-indigo-100 hover:border-indigo-600 transition-all">
                                    Justifier
                                </a>
                            @else
                                <span class="text-[10px] text-gray-400 font-bold uppercase">Clôturé</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 font-bold italic">
                            Félicitations ! Vous n'avez aucune absence enregistrée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
