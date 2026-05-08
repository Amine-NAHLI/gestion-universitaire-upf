@extends('layouts.dashboard')

@section('title', 'Mes Documents')
@section('page-title', 'Mes Documents Officiels')

@section('content')
<div class="space-y-8">
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 border border-gray-100 dark:border-gray-700 shadow-xl">
        <h3 class="text-xl font-black text-gray-800 dark:text-white mb-2 uppercase tracking-tight">Votre coffre-fort numérique</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">Retrouvez ici tous vos documents officiels validés par l'administration.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                        'attestation_scolarite' => 'Attestation de Scolarité',
                        'releve_notes' => 'Relevé de Notes',
                        'certificat_inscription' => 'Certificat d\'Inscription'
                    ];
                @endphp
                <h4 class="text-lg font-black text-gray-800 dark:text-white mb-2">{{ $types[$doc->type] ?? $doc->type }}</h4>
                <p class="text-xs text-gray-500 mb-6">Document officiel signé électroniquement.</p>

                <a href="{{ route('etudiant.demandes.download', $doc) }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 font-black text-xs uppercase tracking-widest transition-all">
                    Télécharger le PDF
                    <svg class="w-4 h-4 group-hover:translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </a>
            </div>
            @empty
            <div class="col-span-full py-16 text-center bg-white dark:bg-gray-800 rounded-3xl border-2 border-dashed border-gray-100 dark:border-gray-700">
                <div class="text-gray-300 dark:text-gray-600 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <p class="text-gray-500 dark:text-gray-400 font-bold italic">Aucun document officiel n'est disponible pour le moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
