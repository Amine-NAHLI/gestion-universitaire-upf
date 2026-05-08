@extends('layouts.dashboard')

@section('title', 'Mes Notes')
@section('page-title', 'Mes Notes')

@section('content')
<div class="space-y-6">
    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl">
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Moyenne Générale</p>
            <div class="flex items-baseline gap-2">
                <span class="text-4xl font-black {{ $moyenne >= 10 ? 'text-emerald-500' : 'text-red-500' }}">{{ number_format($moyenne, 2) }}</span>
                <span class="text-sm font-bold text-gray-400">/ 20.00</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl">
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Modules Validés</p>
            <div class="flex items-baseline gap-2">
                <span class="text-4xl font-black text-indigo-500">{{ $modules_valides }}</span>
                <span class="text-sm font-bold text-gray-400">/ {{ $modules_total }} modules</span>
            </div>
            <div class="w-full bg-gray-100 dark:bg-gray-700 h-2 rounded-full mt-4 overflow-hidden">
                <div class="bg-indigo-500 h-full rounded-full" style="width: {{ $modules_total > 0 ? ($modules_valides / $modules_total) * 100 : 0 }}%"></div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl flex flex-col justify-center">
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Année Universitaire</p>
            <span class="text-xl font-black text-gray-800 dark:text-white">2025 - 2026</span>
        </div>
    </div>

    <!-- Notes Table -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-black text-gray-800 dark:text-white uppercase tracking-wider">Relevé de Notes Détaillé</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/50">
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Module</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">CC1</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">CC2</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Examen</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Note Finale</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($notes as $note)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $note->module->nom }}</span>
                            <p class="text-[10px] text-gray-400">{{ $note->module->code }}</p>
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-medium text-gray-600 dark:text-gray-400">
                            {{ $note->cc1 ?? '--' }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-medium text-gray-600 dark:text-gray-400">
                            {{ $note->cc2 ?? '--' }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-medium text-gray-600 dark:text-gray-400">
                            {{ $note->examen ?? '--' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($note->note_finale !== null)
                                <span class="text-base font-black {{ $note->note_finale >= 10 ? 'text-emerald-500' : 'text-red-500' }}">
                                    {{ number_format($note->note_finale, 2) }}
                                </span>
                            @else
                                <span class="text-sm font-bold text-gray-300 dark:text-gray-600">--</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($note->note_finale !== null)
                                @if($note->note_finale >= 10)
                                    <span class="px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest">
                                        Validé ✓
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-[10px] font-black uppercase tracking-widest">
                                        Ajourné ✗
                                    </span>
                                @endif
                            @else
                                <span class="px-3 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-[10px] font-black uppercase tracking-widest">
                                    En attente
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            Aucune note disponible pour le moment.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50/50 dark:bg-gray-900/20">
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-right text-xs font-black text-gray-400 uppercase tracking-widest">Moyenne du semestre</td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-lg font-black {{ $moyenne >= 10 ? 'text-emerald-500' : 'text-red-500' }}">
                                {{ number_format($moyenne, 2) }}
                            </span>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
