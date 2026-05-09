@extends('layouts.dashboard')

@section('title', __('Demandes Administratives'))
@section('page-title', __('Demandes Administratives'))

@section('content')
<div class="space-y-8">
    
    <!-- New Request Section -->
    <div class="bg-indigo-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden">
        <div class="relative z-10 max-w-2xl">
            <h3 class="text-xl font-black uppercase tracking-wider mb-2">{{ __('Nouvelle demande') }}</h3>
            <p class="text-indigo-100 text-sm mb-6">{{ __('Sélectionnez le document dont vous avez besoin. Le délai moyen de traitement est de 24h à 48h.') }}</p>
            
            <form action="{{ route('etudiant.demandes.store') }}" method="POST" class="flex flex-col md:flex-row gap-4">
                @csrf
                <select name="type" required class="flex-1 rounded-xl bg-white/10 border-white/20 text-white text-sm focus:ring-white placeholder-indigo-200">
                    <option value="" disabled selected>-- {{ __('Type de document') }} --</option>
                    <option value="attestation_scolarite" class="text-gray-900">{{ __('Attestation de Scolarité') }}</option>
                    <option value="releve_notes" class="text-gray-900">{{ __('Relevé de Notes') }}</option>
                    <option value="certificat_inscription" class="text-gray-900">{{ __("Certificat d'Inscription") }}</option>
                </select>
                <select name="langue_document" required class="flex-1 rounded-xl bg-white/10 border-white/20 text-white text-sm focus:ring-white placeholder-indigo-200">
                    <option value="fr" class="text-gray-900">{{ __('En Français') }}</option>
                    <option value="en" class="text-gray-900">{{ __('In English') }}</option>
                    <option value="ar" class="text-gray-900">{{ __('بالعربية') }}</option>
                </select>
                <button type="submit" class="bg-white text-indigo-600 hover:bg-indigo-50 px-8 py-3 rounded-xl font-black uppercase tracking-widest text-xs transition-all shadow-xl shrink-0">
                    {{ __('Soumettre la demande') }}
                </button>
            </form>
        </div>
        <!-- Decorative Icon -->
        <div class="absolute right-8 top-1/2 -translate-y-1/2 opacity-10 hidden lg:block">
            <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 2l5 5h-5V4zM6 20V4h5v7h7v9H6z"/>
            </svg>
        </div>
    </div>

    <!-- History Section -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-black text-gray-800 dark:text-white uppercase tracking-wider">{{ __('Suivi de mes demandes') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/50">
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Document') }}</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Langue') }}</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Date de Demande') }}</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Statut') }}</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">{{ __('Actions / Notes') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($demandes as $demande)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-6 py-4">
                            @php
                                $types = [
                                    'attestation_scolarite' => __('Attestation de Scolarité'),
                                    'releve_notes' => __('Relevé de Notes'),
                                    'certificat_inscription' => __("Certificat d'Inscription")
                                ];
                            @endphp
                            <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $types[$demande->type] ?? $demande->type }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $langLabels = ['fr' => '🇫🇷 FR', 'en' => '🇬🇧 EN', 'ar' => '🇲🇦 AR'];
                            @endphp
                            <span class="px-2 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300 text-[10px] font-black uppercase">{{ $langLabels[$demande->langue_document] ?? 'FR' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium text-gray-500">{{ $demande->created_at->format('d/m/Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
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
                                <a href="{{ route('etudiant.demandes.download', $demande) }}" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-1.5 rounded-lg text-xs font-bold transition-all shadow-lg shadow-emerald-100 dark:shadow-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    {{ __('Télécharger') }}
                                </a>
                            @elseif($demande->statut === 'refusee')
                                <span class="text-[10px] text-red-500 font-bold uppercase italic">{{ __('Motif') }} : {{ $demande->motif_refus ?? __('Non spécifié') }}</span>
                            @else
                                <span class="text-[10px] text-gray-400 font-bold uppercase">{{ __('En cours de traitement...') }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 font-bold italic">
                            {{ __("Vous n'avez effectué aucune demande pour le moment.") }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
