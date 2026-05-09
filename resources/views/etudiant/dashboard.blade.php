@extends('layouts.dashboard')

@section('title', __('Dashboard Étudiant'))
@section('header_title', __('Salut, ') . Auth::user()->prenom . ' ! 👋')
@section('header_subtitle', __('Voici ton résumé académique pour le semestre actuel.'))

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-dashboard.stat-card 
            title="{{ __('Moyenne Générale') }}" 
            value="{{ number_format($moyenneGenerale, 2) }}" 
            icon="fa-graduation-cap" 
            color="primary" />
            
        <x-dashboard.stat-card 
            title="{{ __('Absences') }}" 
            value="{{ $absencesCount }}" 
            icon="fa-user-clock" 
            color="rose" 
            trendUp="false" />

        <x-dashboard.stat-card 
            title="{{ __('Crédits validés') }}" 
            value="{{ $creditsValides }}/{{ $totalModules }}" 
            icon="fa-check-circle" 
            color="emerald" />

        <x-dashboard.stat-card 
            title="{{ __('Supports de cours') }}" 
            value="{{ $supportsCount }}" 
            icon="fa-file-download" 
            color="indigo" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Courses List -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm" data-aos="fade-right">
            <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-6">{{ __('Mes Modules') }}</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">
                            <th class="pb-4">{{ __('Module') }}</th>
                            <th class="pb-4">{{ __('Note CC1') }}</th>
                            <th class="pb-4">{{ __('Note CC2') }}</th>
                            <th class="pb-4">{{ __('Moyenne') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                        @forelse($notes as $note)
                        @php
                            $ccAvg = (($note->cc1 ?? 0) + ($note->cc2 ?? 0)) / 2;
                            $moyenne = ($ccAvg * 0.4) + (($note->examen ?? 0) * 0.6);
                            $colorClass = $moyenne >= 10 ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400' : 'bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400';
                            if ($moyenne < 8) $colorClass = 'bg-rose-100 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400';
                        @endphp
                        <tr class="group hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-4 font-bold text-slate-700 dark:text-white">{{ $note->module->nom }}</td>
                            <td class="py-4 text-slate-600 dark:text-slate-400">{{ $note->cc1 !== null ? number_format($note->cc1, 2) : '--' }}</td>
                            <td class="py-4 text-slate-600 dark:text-slate-400">{{ $note->cc2 !== null ? number_format($note->cc2, 2) : '--' }}</td>
                            <td class="py-4"><span class="{{ $colorClass }} font-bold px-2 py-1 rounded-lg">{{ number_format($moyenne, 2) }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-slate-500">{{ __('Aucune note enregistrée pour l\'instant.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Schedule Today -->
        <div class="bg-slate-900 text-white p-8 rounded-3xl shadow-xl shadow-primary-500/10 relative overflow-hidden" data-aos="fade-left">
            <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-primary-500/20 rounded-full blur-3xl"></div>
            <h3 class="text-xl font-bold mb-6 relative">{{ __('Planning du jour') }}</h3>
            <div class="space-y-6 relative">
                @forelse($planningJour as $seance)
                <div class="border-l-4 border-primary-500 pl-4 py-1">
                    <p class="text-xs font-bold text-primary-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }}</p>
                    <h4 class="font-bold text-lg mt-1">{{ $seance->module->nom ?? 'Module' }}</h4>
                    <p class="text-sm text-slate-400 mt-1"><i class="fas fa-map-marker-alt mr-2"></i>{{ $seance->salle->nom ?? 'À définir' }}</p>
                </div>
                @empty
                <div class="text-center py-4 text-slate-400">
                    <i class="fas fa-calendar-check text-4xl mb-3 opacity-50"></i>
                    <p>{{ __('Aucune séance prévue aujourd\'hui.') }}</p>
                </div>
                @endforelse
            </div>
            <button class="w-full mt-10 py-4 bg-white text-slate-900 font-black rounded-2xl hover:scale-105 transition-transform">
                {{ __('Emploi du temps complet') }}
            </button>
        </div>
    </div>
@endsection
