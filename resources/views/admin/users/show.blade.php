@extends('layouts.dashboard')

@section('title', __('Détails Utilisateur'))
@section('page-title', __('Profil de ') . $user->full_name)

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <!-- Back Button -->
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        {{ __('Retour à la liste') }}
    </a>

    <!-- User Card -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-10">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-lg">
                    {{ strtoupper(substr($user->prenom ?? 'U', 0, 1)) }}{{ strtoupper(substr($user->name ?? '', 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white">{{ $user->prenom }} {{ strtoupper($user->name) }}</h2>
                    <p class="text-indigo-200 text-sm">{{ $user->email }}</p>
                    @php
                        $roleColors = [
                            'admin' => 'bg-red-500',
                            'professeur' => 'bg-blue-500',
                            'etudiant' => 'bg-emerald-500',
                        ];
                    @endphp
                    <span class="inline-block mt-2 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest text-white {{ $roleColors[$user->role] ?? 'bg-gray-500' }}">
                        {{ $user->role }}
                    </span>
                </div>
            </div>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ __('Nom complet') }}</p>
                        <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $user->prenom }} {{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ __('Email') }}</p>
                        <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ __('Téléphone') }}</p>
                        <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $user->telephone ?? '---' }}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ __('Rôle') }}</p>
                        <p class="text-sm font-bold text-gray-800 dark:text-white capitalize">{{ $user->role }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ __('Statut') }}</p>
                        @if($user->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                {{ __('Actif') }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                {{ __('Inactif') }}
                            </span>
                        @endif
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ __('Date d\'inscription') }}</p>
                        <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>

            @if($user->isEtudiant() && $user->etudiant)
            <div class="mt-8 pt-8 border-t border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-black text-gray-800 dark:text-white mb-4 uppercase tracking-wider">{{ __('Informations Étudiant') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ __('N° Apogée') }}</p>
                        <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $user->etudiant->num_apogee ?? '---' }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ __('Filière') }}</p>
                        <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $user->etudiant->groupe->niveau->filiere->nom ?? '---' }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ __('Groupe') }}</p>
                        <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $user->etudiant->groupe->nom ?? '---' }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($user->isProfesseur() && $user->professeur)
            <div class="mt-8 pt-8 border-t border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-black text-gray-800 dark:text-white mb-4 uppercase tracking-wider">{{ __('Informations Professeur') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ __('Grade') }}</p>
                        <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $user->professeur->grade ?? '---' }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ __('Spécialité') }}</p>
                        <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $user->professeur->specialite ?? '---' }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ __('Date d\'embauche') }}</p>
                        <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $user->professeur->date_embauche ? $user->professeur->date_embauche->format('d/m/Y') : '---' }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-4">
        <a href="{{ route('admin.users.edit', $user) }}" class="flex-1 btn-primary text-center py-3 rounded-xl font-bold">
            {{ __('Modifier cet utilisateur') }}
        </a>
    </div>
</div>
@endsection
