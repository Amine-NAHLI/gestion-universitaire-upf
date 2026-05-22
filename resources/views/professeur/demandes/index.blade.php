@extends('layouts.dashboard')

@section('title', __('Mes Demandes'))
@section('page-title', __('Mes Demandes Administratives'))

@section('content')
<div class="space-y-8" x-data="{ type: 'attestation_travail' }">

    <!-- Action Card -->
    <div class="bg-indigo-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden">
        <div class="relative z-10">
            <h3 class="text-xl font-black uppercase tracking-wider mb-2">{{ __('Nouvelle demande') }}</h3>
            <p class="text-indigo-100 text-sm mb-8">{{ __('Sélectionnez le type de document dont vous avez besoin.') }}</p>

            <form action="{{ route('professeur.demandes.store') }}" method="POST" class="space-y-6 max-w-2xl">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-indigo-200 uppercase tracking-widest">{{ __('Type de document') }}</label>
                        <select name="type" x-model="type" required class="w-full rounded-xl bg-white/10 border-white/20 text-white text-sm focus:ring-white">
                            <option value="attestation_travail" class="text-gray-900">{{ __('Attestation de travail') }}</option>
                            <option value="ordre_mission" class="text-gray-900">{{ __('Ordre de mission') }}</option>
                        </select>
                    </div>
                </div>

                <!-- Mission Specific Fields -->
                <div x-show="type === 'ordre_mission'" x-transition class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-6 bg-white/5 rounded-2xl border border-white/10">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-indigo-200 uppercase tracking-widest">{{ __('Destination') }}</label>
                        <input type="text" name="donnees_supplementaires[destination]" class="w-full rounded-xl bg-white/10 border-white/20 text-white text-xs">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-indigo-200 uppercase tracking-widest">{{ __('Date Départ') }}</label>
                        <input type="date" name="donnees_supplementaires[date_depart]" class="w-full rounded-xl bg-white/10 border-white/20 text-white text-xs">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-indigo-200 uppercase tracking-widest">{{ __('Date Retour') }}</label>
                        <input type="date" name="donnees_supplementaires[date_retour]" class="w-full rounded-xl bg-white/10 border-white/20 text-white text-xs">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-indigo-200 uppercase tracking-widest">{{ __('Motif Mission') }}</label>
                        <input type="text" name="donnees_supplementaires[motif]" class="w-full rounded-xl bg-white/10 border-white/20 text-white text-xs">
                    </div>
                </div>

                <button type="submit" class="bg-white text-indigo-600 hover:bg-indigo-50 px-10 py-3 rounded-xl font-black uppercase tracking-widest text-xs transition-all shadow-xl">
                    {{ __('Soumettre la demande') }}
                </button>
            </form>
        </div>
    </div>

    <!-- History Table -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-black text-gray-800 dark:text-white uppercase tracking-wider">{{ __('Historique des demandes') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/50">
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Type') }}</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Date Demande') }}</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">{{ __('Statut') }}</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($demandes as $demande)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-gray-800 dark:text-white">
                                {{ $demande->type === 'attestation_travail' ? __('Attestation de travail') : __('Ordre de mission') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium text-gray-500">{{ $demande->created_at->format('d/m/Y') }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statuts = [
                                    'en_attente' => ['bg-amber-100', 'text-amber-600', __('En attente')],
                                    'validee' => ['bg-emerald-100', 'text-emerald-600', __('Traitée') . ' ✓'],
                                    'refusee' => ['bg-red-100', 'text-red-600', __('Refusée') . ' ✗'],
                                ];
                                $s = $statuts[$demande->statut] ?? ['bg-gray-100', 'text-gray-600', $demande->statut];
                            @endphp
                            <span class="px-3 py-1 rounded-full {{ $s[0] }} dark:bg-opacity-20 text-[10px] font-black uppercase tracking-widest">
                                {{ $s[2] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($demande->statut === 'validee' && $demande->fichier_pdf)
                                <a href="{{ route('professeur.demandes.download', $demande) }}" class="text-indigo-600 hover:text-indigo-700 font-black text-[10px] uppercase tracking-widest">{{ __('Télécharger PDF') }}</a>
                            @else
                                <span class="text-[10px] text-gray-400 font-bold uppercase">--</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 font-bold italic">
                            {{ __('Aucune demande en cours.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
