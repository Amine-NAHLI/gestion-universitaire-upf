@extends('layouts.dashboard')

@section('title', __('Dashboard Professeur'))
@section('header_title', __('Espace Enseignant'))
@section('header_subtitle', __('Gérez vos cours, notes et absences en toute simplicité.'))

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

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

    {{-- Charts row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Bar Chart — Moyennes par module -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm" data-aos="fade-up">
            <div class="mb-6">
                <h3 class="text-xl font-bold text-slate-800 dark:text-white">{{ __('Moyennes par module') }}</h3>
                <p class="text-sm text-slate-500">{{ __('Moyenne des notes finales des étudiants') }}</p>
            </div>
            <div class="h-[280px]">
                <canvas id="moyennesChart"></canvas>
            </div>
        </div>

        <!-- Bar Chart — Taux de présence par module -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm" data-aos="fade-up" data-aos-delay="150">
            <div class="mb-6">
                <h3 class="text-xl font-bold text-slate-800 dark:text-white">{{ __('Taux de présence par module') }}</h3>
                <p class="text-sm text-slate-500">{{ __('Pourcentage de présence aux séances effectuées') }}</p>
            </div>
            <div class="h-[280px]">
                <canvas id="presenceChart"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const moyennesData = @json(collect($moyennesGraphData));
    const presenceData = @json(collect($tauxPresenceGraphData));

    // Bar Chart — Moyennes par module
    new Chart(document.getElementById('moyennesChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: moyennesData.map(d => d.nom),
            datasets: [{
                label: '{{ __('Moyenne /20') }}',
                data: moyennesData.map(d => d.moyenne),
                backgroundColor: 'rgba(99, 102, 241, 0.75)',
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, max: 20, ticks: { color: '#94a3b8' }, grid: { color: 'rgba(148,163,184,0.1)' } },
                x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
            }
        }
    });

    // Bar Chart — Taux de présence
    new Chart(document.getElementById('presenceChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: presenceData.map(d => d.nom),
            datasets: [{
                label: '{{ __('Taux (%)') }}',
                data: presenceData.map(d => d.taux),
                backgroundColor: presenceData.map(d =>
                    d.taux >= 80 ? 'rgba(16,185,129,0.75)' :
                    d.taux >= 60 ? 'rgba(245,158,11,0.75)' :
                                   'rgba(239,68,68,0.75)'
                ),
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, max: 100, ticks: { color: '#94a3b8', callback: v => v + '%' }, grid: { color: 'rgba(148,163,184,0.1)' } },
                x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
            }
        }
    });
});
</script>
@endpush
