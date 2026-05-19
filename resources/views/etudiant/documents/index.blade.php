@extends('layouts.dashboard')

@section('title', __('Mes Documents'))
@section('page-title', __('Mes Documents Officiels'))

@section('content')
<div class="space-y-8">
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 border border-gray-100 dark:border-gray-700 shadow-xl">
        <h3 class="text-xl font-black text-gray-800 dark:text-white mb-2 uppercase tracking-tight">{{ __('Votre coffre-fort numérique') }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">{{ __('Retrouvez ici tous vos documents officiels validés par l\'administration.') }}</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Carte Fixe : Relevé de Notes Certifié (Généré dynamiquement avec PKI) -->
            <div class="group bg-gradient-to-br from-green-50 to-emerald-100 dark:from-green-900/40 dark:to-emerald-900/20 rounded-2xl p-6 border border-green-200 dark:border-green-800 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-10">
                    <svg class="w-32 h-32 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div class="flex justify-between items-start mb-6 relative z-10">
                    <div class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm text-green-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span class="text-[9px] font-black text-green-600 dark:text-green-400 uppercase tracking-widest bg-green-200/50 dark:bg-green-800/50 px-2 py-1 rounded">PKI & QR CODE</span>
                </div>
                <h4 class="text-lg font-black text-gray-800 dark:text-white mb-2 relative z-10">{{ __('Relevé de Notes (Certifié)') }}</h4>
                <p class="text-xs text-gray-600 dark:text-gray-300 mb-6 relative z-10">{{ __('Généré en temps réel avec signature cryptographique asymétrique infalsifiable.') }}</p>
                <a href="{{ route('etudiant.releve-notes.download') }}" class="inline-flex items-center gap-2 text-green-700 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 font-black text-xs uppercase tracking-widest transition-all relative z-10">
                    {{ __('Générer le PDF Sécurisé') }}
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

            @forelse($demandes_validees as $doc)
            <div class="group bg-gray-50 dark:bg-gray-900/50 rounded-2xl p-6 border border-transparent hover:border-indigo-500 transition-all duration-300">
                <div class="flex justify-between items-start mb-6">
                    <div class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm text-indigo-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $doc->updated_at->format('d/m/Y') }}</span>
                </div>

                @php
                    $types = [
                        'attestation_scolarite' => __('Attestation de Scolarité'),
                        'releve_notes' => __('Relevé de Notes'),
                        'certificat_inscription' => __('Certificat d\'Inscription')
                    ];
                @endphp
                <h4 class="text-lg font-black text-gray-800 dark:text-white mb-2">{{ $types[$doc->type] ?? $doc->type }}</h4>
                <p class="text-xs text-gray-500 mb-6">{{ __('Document officiel signé électroniquement.') }}</p>

                @if($doc->type === 'releve_notes')
                    <a href="{{ route('etudiant.releve-notes.download') }}" class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 font-black text-xs uppercase tracking-widest transition-all">
                        {{ __('Télécharger le PDF Certifié (QR)') }}
                        <svg class="w-4 h-4 group-hover:translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('etudiant.demandes.download', $doc) }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 font-black text-xs uppercase tracking-widest transition-all">
                        {{ __('Télécharger le PDF') }}
                        <svg class="w-4 h-4 group-hover:translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>
                @endif
            </div>
            @empty
            <div class="col-span-full py-16 text-center bg-white dark:bg-gray-800 rounded-3xl border-2 border-dashed border-gray-100 dark:border-gray-700">
                <div class="text-gray-300 dark:text-gray-600 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <p class="text-gray-500 dark:text-gray-400 font-bold italic">{{ __("Aucun document officiel n'est disponible pour le moment.") }}</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
