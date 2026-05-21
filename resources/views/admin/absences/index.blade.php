@extends('layouts.dashboard')

@section('title', __('Gestion des Absences'))
@section('page-title', __('Gestion des Absences'))

@section('content')
<div class="space-y-6">
    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-dashboard.stat-card
            title="{{ __('Total Absences') }}"
            value="{{ $stats['total'] }}"
            icon="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            color="indigo"
        />
        <x-dashboard.stat-card
            title="{{ __('Justifiées') }}"
            value="{{ $stats['justifiees'] }}"
            icon="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
            color="green"
        />
        <x-dashboard.stat-card
            title="{{ __('Non Justifiées') }}"
            value="{{ $stats['non_justifiees'] }}"
            icon="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
            color="red"
        />
        <x-dashboard.stat-card
            title="{{ __('En attente') }}"
            value="{{ $stats['en_attente'] }}"
            icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
            color="orange"
        />
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
        <form action="{{ route('admin.absences.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-4">
            <div class="flex-1 w-full">
                <select name="groupe_id" class="w-full px-4 py-2 rounded-xl bg-gray-100 dark:bg-gray-700 border-none text-sm font-bold text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500">
                    <option value="">{{ __('Tous les groupes') }}</option>
                    @foreach($groupes as $groupe)
                        <option value="{{ $groupe->id }}" {{ $groupe_id == $groupe->id ? 'selected' : '' }}>{{ $groupe->nom }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary w-full md:w-auto px-8">{{ __('Filtrer') }}</button>
            <a href="{{ route('admin.absences.export', ['groupe_id' => $groupe_id]) }}" class="flex items-center gap-2 px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-emerald-100 dark:shadow-none w-full md:w-auto justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                {{ __('Exporter Excel') }}
            </a>
            @if($groupe_id)
                <a href="{{ route('admin.absences.index') }}" class="btn-secondary w-full md:w-auto">{{ __('Réinitialiser') }}</a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Étudiant') }}</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Module') }}</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Date & Heure') }}</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">{{ __('Statut') }}</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">{{ __('Justificatif') }}</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($absences as $absence)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                    {{ substr($absence->etudiant->user->prenom, 0, 1) }}{{ substr($absence->etudiant->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $absence->etudiant->user->full_name }}</p>
                                    <p class="text-[10px] text-gray-500">{{ $absence->etudiant->groupe->nom ?? __('Sans groupe') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $absence->seance->module->nom }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">{{ $absence->seance->date->format('d/m/Y') }}</p>
                            <p class="text-[10px] text-gray-400">{{ $absence->seance->heure_debut->format('H:i') }} - {{ $absence->seance->heure_fin->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($absence->justifiee)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">{{ __('Justifiée') }}</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">{{ __('Non Justifiée') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($absence->justificatif)
                                @php
                                    $statutColors = [
                                        'en_attente' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/20 dark:text-orange-400',
                                        'accepte' => 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400',
                                        'refuse' => 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400',
                                    ];
                                @endphp
                                <div class="flex flex-col items-center gap-1">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase {{ $statutColors[$absence->justificatif->statut] }}">
                                        {{ $absence->justificatif->statut }}
                                    </span>
                                    <a href="{{ asset('storage/' . $absence->justificatif->fichier) }}" target="_blank" class="text-[10px] text-indigo-500 hover:underline font-bold">{{ __('Voir le doc') }}</a>
                                </div>
                            @else
                                <span class="text-gray-300 dark:text-gray-600 text-[10px]">{{ __('Aucun') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('admin.absences.toggle-justifiee', $absence) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="p-2 rounded-lg {{ $absence->justifiee ? 'text-red-500 hover:bg-red-100' : 'text-green-500 hover:bg-green-100' }} transition-all">
                                        @if($absence->justifiee)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </button>
                                </form>

                                @if($absence->justificatif && $absence->justificatif->statut === 'en_attente')
                                    <div class="flex items-center gap-1 border-l border-gray-100 dark:border-gray-700 pl-2">
                                        <form action="{{ route('admin.justificatifs.valider', $absence->justificatif) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="p-2 rounded-lg text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </button>
                                        </form>
                                        <form id="form-refuser-{{ $absence->justificatif->id }}" action="{{ route('admin.justificatifs.refuser', $absence->justificatif) }}" method="POST" style="display:none;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="motif_refus" id="motif-{{ $absence->justificatif->id }}">
                                        </form>
                                        <button type="button" @click="Swal.fire({
                                            title: '{{ __('Refuser la demande') }}',
                                            input: 'text',
                                            inputLabel: '{{ __('Motif du refus') }}',
                                            inputPlaceholder: '{{ __('Ex: Document illisible') }}',
                                            showCancelButton: true,
                                            confirmButtonColor: '#ef4444',
                                            confirmButtonText: '{{ __('Confirmer') }}',
                                            cancelButtonText: '{{ __('Annuler') }}'
                                        }).then((result) => {
                                            if (result.isConfirmed && result.value) {
                                                document.getElementById('motif-{{ $absence->justificatif->id }}').value = result.value;
                                                document.getElementById('form-refuser-{{ $absence->justificatif->id }}').submit();
                                            }
                                        })" class="p-2 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400 font-medium">
                            {{ __('Aucune absence enregistrée.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $absences->links() }}
    </div>
</div>
@endsection
