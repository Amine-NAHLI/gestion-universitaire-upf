@extends('layouts.dashboard')

@section('title', 'Demandes Administratives')
@section('page-title', 'Demandes Administratives')

@section('content')
<div class="space-y-6">
    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-dashboard.stat-card title="Total Demandes" value="{{ $stats['total'] }}" icon="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" color="indigo" />
        <x-dashboard.stat-card title="En attente" value="{{ $stats['en_attente'] }}" icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" color="orange" />
        <x-dashboard.stat-card title="Validées" value="{{ $stats['validees'] }}" icon="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" color="green" />
        <x-dashboard.stat-card title="Refusées" value="{{ $stats['refusees'] }}" icon="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" color="red" />
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap items-center gap-2 bg-white dark:bg-gray-800 p-2 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
        @foreach(['tous' => 'Tous', 'en_attente' => 'En attente', 'validee' => 'Validées', 'refusee' => 'Refusées'] as $key => $label)
            <a href="{{ route('admin.demandes.index', ['statut' => $key]) }}" 
               class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ $statut === $key ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200 dark:shadow-none' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Demandeur</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type de document</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">Date Demande</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">Statut</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @php
                    $typeLabels = [
                        'attestation_scolarite' => 'Attestation de scolarité',
                        'releve_notes' => 'Relevé de notes',
                        'certificat_inscription' => 'Certificat d\'inscription',
                        'attestation_travail' => 'Attestation de travail',
                        'ordre_mission' => 'Ordre de mission',
                    ];
                @endphp
                @forelse($demandes as $demande)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold uppercase">
                                    {{ substr($demande->user->prenom, 0, 1) }}{{ substr($demande->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $demande->user->full_name }}</p>
                                    <p class="text-[10px] text-gray-500 uppercase font-bold">{{ $demande->user->role }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $typeLabels[$demande->type] ?? $demande->type }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">{{ $demande->created_at->format('d/m/Y') }}</p>
                            <p class="text-[10px] text-gray-400">{{ $demande->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @php
                                $statutColors = [
                                    'en_attente' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                    'validee' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                    'refusee' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                ];
                                $statutLabels = ['en_attente' => 'En attente', 'validee' => 'Validée', 'refusee' => 'Refusée'];
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statutColors[$demande->statut] }}">
                                {{ $statutLabels[$demande->statut] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.demandes.show', $demande) }}" class="p-2 rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-indigo-600" title="Détails">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>

                                @if($demande->statut === 'en_attente')
                                    <form action="{{ route('admin.demandes.valider', $demande) }}" method="POST" class="inline-block">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="p-2 rounded-lg text-green-500 hover:bg-green-100 dark:hover:bg-green-900/20" title="Valider">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                    </form>
                                    <button type="button" @click="Swal.fire({
                                        title: 'Refuser la demande',
                                        input: 'textarea',
                                        inputLabel: 'Motif du refus',
                                        inputPlaceholder: 'Expliquez pourquoi la demande est refusée...',
                                        showCancelButton: true,
                                        confirmButtonColor: '#ef4444',
                                        confirmButtonText: 'Refuser',
                                        cancelButtonText: 'Annuler'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            const form = document.createElement('form');
                                            form.method = 'POST';
                                            form.action = '{{ route('admin.demandes.refuser', $demande) }}';
                                            form.innerHTML = `<input type='hidden' name='_token' value='{{ csrf_token() }}'><input type='hidden' name='_method' value='PATCH'><input type='hidden' name='motif_refus' value='${result.value}'>`;
                                            document.body.appendChild(form);
                                            form.submit();
                                        }
                                    })" class="p-2 rounded-lg text-red-500 hover:bg-red-100 dark:hover:bg-red-900/20" title="Refuser">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                @endif

                                @if($demande->statut === 'validee')
                                    <a href="{{ route('admin.demandes.pdf', $demande) }}" class="p-2 rounded-lg text-blue-500 hover:bg-blue-100 dark:hover:bg-blue-900/20" title="Télécharger PDF">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400 font-medium">
                            Aucune demande dans cette catégorie.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $demandes->links() }}
    </div>
</div>
@endsection
