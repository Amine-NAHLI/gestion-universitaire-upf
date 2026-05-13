@extends('layouts.dashboard')

@section('title', __('Dashboard Professeur'))
@section('header_title', __('Espace Enseignant'))
@section('header_subtitle', __('Gérez vos cours, notes et absences en toute simplicité.'))

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-dashboard.stat-card 
            title="{{ __('Heures assurées') }}" 
            value="{{ $heuresAssurees }}h" 
            icon="fa-clock" 
            color="indigo" />
            
        <x-dashboard.stat-card 
            title="{{ __('Étudiants suivis') }}" 
            value="{{ $etudiantsSuivis }}" 
            icon="fa-users" 
            color="primary" />

        <x-dashboard.stat-card 
            title="{{ __('Modules actifs') }}" 
            value="{{ $modulesActifs }}" 
            icon="fa-layer-group" 
            color="emerald" />

        <x-dashboard.stat-card 
            title="{{ __('Taux de présence') }}" 
            value="92%" 
            icon="fa-chart-pie" 
            color="amber" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Next Classes -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm" data-aos="fade-up">
            <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-6">{{ __('Prochaines Séances') }}</h3>
            <div class="space-y-4">
                @forelse($prochainesSeances as $seance)
                @php
                    $isCours = $seance->type === 'Cours';
                    $colorName = $isCours ? 'primary' : 'emerald';
                @endphp
                <div class="flex items-center p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700">
                    <div class="bg-{{ $colorName }}-500 text-white p-3 rounded-xl text-center min-w-[60px]">
                        <span class="block text-xs font-bold uppercase">{{ \Carbon\Carbon::parse($seance->date)->translatedFormat('M') }}</span>
                        <span class="block text-xl font-black">{{ \Carbon\Carbon::parse($seance->date)->format('d') }}</span>
                    </div>
                    <div class="ml-4 flex-1">
                        <h4 class="font-bold text-slate-800 dark:text-white">{{ $seance->module->nom ?? 'Module' }}</h4>
                        <p class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }} • {{ $seance->salle->nom ?? 'À définir' }}</p>
                    </div>
                    <span class="bg-{{ $colorName }}-100 dark:bg-{{ $colorName }}-500/20 text-{{ $colorName }}-600 dark:text-{{ $colorName }}-400 text-[10px] font-bold px-2 py-1 rounded-full uppercase">{{ $seance->type ?? 'Cours' }}</span>
                </div>
                @empty
                <div class="text-center py-6 text-slate-500">
                    {{ __('Aucune séance programmée.') }}
                </div>
                @endforelse
            </div>
        </div>

        <!-- Grading Progress -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm" data-aos="fade-up" data-aos-delay="200">
            <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-6">{{ __('Saisie des Notes') }}</h3>
            <div class="space-y-6">
                @forelse($modulesProgress as $index => $mod)
                @php
                    $colors = ['primary', 'amber', 'emerald', 'rose', 'indigo'];
                    $color = $colors[$index % count($colors)];
                @endphp
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-bold text-slate-700 dark:text-slate-300">{{ $mod['nom'] }}</span>
                        <span class="text-{{ $color }}-500 font-bold">{{ $mod['progress'] }}%</span>
                    </div>
                    <div class="h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full bg-{{ $color }}-500 rounded-full" style="width: {{ $mod['progress'] }}%"></div>
                    </div>
                </div>
                @empty
                <div class="text-center text-sm text-slate-500 py-4">
                    {{ __('Aucun module assigné.') }}
                </div>
                @endforelse
            </div>
            <a href="{{ route('professeur.notes.index') }}"
               class="block text-center w-full mt-10 py-4 bg-slate-900 dark:bg-primary-600 text-white font-bold rounded-2xl hover:bg-slate-800 dark:hover:bg-primary-500 transition-all shadow-lg shadow-primary-500/20">
                {{ __('Accéder à la saisie') }}
            </a>
        </div>
    </div>
@endsection
