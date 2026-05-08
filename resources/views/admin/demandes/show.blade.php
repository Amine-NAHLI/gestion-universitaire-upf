@extends('layouts.dashboard')

@section('title', 'Détails de la Demande')
@section('page-title', 'Détails de la Demande')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.demandes.index') }}" class="flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Retour à la liste
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Card -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
                <div class="p-8 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
                    <div class="flex justify-between items-start">
                        <div>
                            @php
                                $typeLabels = [
                                    'attestation_scolarite' => 'Attestation de scolarité',
                                    'releve_notes' => 'Relevé de notes',
                                    'certificat_inscription' => 'Certificat d\'inscription',
                                    'attestation_travail' => 'Attestation de travail',
                                    'ordre_mission' => 'Ordre de mission',
                                ];
                            @endphp
                            <h2 class="text-2xl font-extrabold text-gray-800 dark:text-white">{{ $typeLabels[$demande->type] ?? $demande->type }}</h2>
                            <p class="text-sm text-gray-500 mt-1">Demande déposée le {{ $demande->created_at->format('d F Y à H:i') }}</p>
                        </div>
                        @php
                            $statutColors = [
                                'en_attente' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                'validee' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                'refusee' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                            ];
                            $statutLabels = ['en_attente' => 'En attente', 'validee' => 'Validée', 'refusee' => 'Refusée'];
                        @endphp
                        <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest {{ $statutColors[$demande->statut] }}">
                            {{ $statutLabels[$demande->statut] }}
                        </span>
                    </div>
                </div>

                <div class="p-8 space-y-8">
                    <!-- Info Sections -->
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Demandeur</h4>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold">
                                    {{ substr($demande->user->prenom, 0, 1) }}{{ substr($demande->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $demande->user->full_name }}</p>
                                    <p class="text-xs text-gray-500 uppercase">{{ $demande->user->role }}</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Email Contact</h4>
                            <p class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $demande->user->email }}</p>
                        </div>
                    </div>

                    @if($demande->donnees_supplementaires)
                        <div class="p-6 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Informations Complémentaires</h4>
                            <div class="space-y-4">
                                @foreach($demande->donnees_supplementaires as $key => $value)
                                    <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-2 last:border-0">
                                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">{{ str_replace('_', ' ', $key) }}</span>
                                        <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($demande->statut === 'refusee')
                        <div class="p-6 rounded-2xl bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30">
                            <h4 class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-2">Motif du refus</h4>
                            <p class="text-sm font-medium text-red-700 dark:text-red-300">{{ $demande->motif_refus }}</p>
                        </div>
                    @endif

                    @if($demande->statut === 'validee' && $demande->fichier_pdf)
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-indigo-50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-900/30">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-indigo-500 text-white flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-indigo-700 dark:text-indigo-400">Document généré</p>
                                    <p class="text-[10px] text-indigo-500">Prêt pour téléchargement</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.demandes.pdf', $demande) }}" class="btn-primary py-2 px-4 text-xs">Télécharger</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div class="space-y-6">
            @if($demande->statut === 'en_attente')
                <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl space-y-4">
                    <h3 class="text-sm font-black text-gray-800 dark:text-white uppercase tracking-widest">Actions Administrateur</h3>
                    
                    <form action="{{ route('admin.demandes.valider', $demande) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="w-full btn-primary bg-green-600 hover:bg-green-700 flex justify-center py-4 rounded-2xl shadow-lg shadow-green-200 dark:shadow-none">
                            Valider la demande
                        </button>
                    </form>

                    <button @click="Swal.fire({
                        title: 'Refuser la demande',
                        input: 'textarea',
                        inputLabel: 'Motif du refus',
                        inputPlaceholder: 'Indiquez la raison du refus...',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        confirmButtonText: 'Confirmer le refus'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ route('admin.demandes.refuser', $demande) }}';
                            form.innerHTML = `<input type='hidden' name='_token' value='{{ csrf_token() }}'><input type='hidden' name='_method' value='PATCH'><input type='hidden' name='motif_refus' value='${result.value}'>`;
                            document.body.appendChild(form);
                            form.submit();
                        }
                    })" class="w-full px-6 py-4 rounded-2xl border-2 border-red-100 dark:border-red-900/30 text-red-600 dark:text-red-400 font-bold hover:bg-red-50 dark:hover:bg-red-900/10 transition-all">
                        Refuser la demande
                    </button>
                </div>
            @endif

            <div class="bg-indigo-900 rounded-3xl p-6 text-white overflow-hidden relative group">
                <div class="relative z-10">
                    <h4 class="text-xs font-bold text-indigo-300 uppercase mb-2">Besoin d'aide ?</h4>
                    <p class="text-sm font-medium opacity-80 mb-4">Vérifiez les données de l'étudiant avant de valider le document officiel.</p>
                    <a href="{{ route('admin.users.index', ['role' => 'etudiant']) }}" class="text-xs font-bold underline hover:text-indigo-200 transition-colors">Consulter le dossier</a>
                </div>
                <svg class="absolute -right-4 -bottom-4 w-24 h-24 text-white/10 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/></svg>
            </div>
        </div>
    </div>
</div>
@endsection
