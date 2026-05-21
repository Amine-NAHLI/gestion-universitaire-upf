@extends('layouts.dashboard')

@section('title', __('Dashboard Admin'))
@section('header_title', __('Bienvenue, ') . Auth::user()->prenom)
@section('header_subtitle', __('Voici ce qui se passe aujourd\'hui à l\'UPF.'))

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-dashboard.stat-card 
            title="{{ __('Étudiants Inscrits') }}" 
            value="{{ number_format($stats['etudiants_count']) }}" 
            icon="fa-user-graduate" 
            color="primary" 
            trend="12" 
            trendUp="true" />
            
        <x-dashboard.stat-card 
            title="{{ __('Corps Professoral') }}" 
            value="{{ number_format($stats['professeurs_count']) }}" 
            icon="fa-chalkboard-teacher" 
            color="indigo" 
            trend="2" 
            trendUp="true" />

        <x-dashboard.stat-card 
            title="{{ __('Séances ce jour') }}" 
            value="{{ $stats['seances_jour'] }}" 
            icon="fa-calendar-check" 
            color="emerald" />

        <x-dashboard.stat-card 
            title="{{ __('Demandes en attente') }}" 
            value="{{ $stats['demandes_attente'] }}" 
            icon="fa-file-invoice" 
            color="amber" 
            trend="5" 
            trendUp="false" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Notes Distribution Chart -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm" data-aos="fade-right">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">{{ __('Distribution des Notes') }}</h3>
                    <p class="text-sm text-slate-500">{{ __('Répartition des moyennes générales') }}</p>
                </div>
            </div>
            <div class="h-[300px]">
                <canvas id="notesChart"></canvas>
            </div>
        </div>

        <!-- Absences Donut Chart -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm" data-aos="fade-left">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">{{ __('Taux d\'Absentéisme') }}</h3>
                    <p class="text-sm text-slate-500">{{ __('Proportion des absences justifiées vs non justifiées') }}</p>
                </div>
            </div>
            <div class="h-[300px] flex justify-center">
                <canvas id="absencesChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Moyennes par Filière — Line Chart -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm" data-aos="fade-up">
            <div class="mb-8">
                <h3 class="text-xl font-bold text-slate-800 dark:text-white">{{ __('Moyennes par Filière') }}</h3>
                <p class="text-sm text-slate-500">{{ __('Moyenne générale des notes finales par filière') }}</p>
            </div>
            <div class="h-[280px]">
                <canvas id="filiereChart"></canvas>
            </div>
        </div>

        <!-- Top 5 Modules — Absences Table -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm" data-aos="fade-up" data-aos-delay="150">
            <div class="mb-8">
                <h3 class="text-xl font-bold text-slate-800 dark:text-white">{{ __('Top 5 Modules — Absences') }}</h3>
                <p class="text-sm text-slate-500">{{ __('Modules avec le plus d\'absences enregistrées') }}</p>
            </div>
            <div class="space-y-4">
                @forelse($topAbsencesModules as $index => $item)
                @php
                    $colors = ['rose', 'amber', 'orange', 'yellow', 'slate'];
                    $c = $colors[$index % 5];
                    $maxTotal = $topAbsencesModules->first()->total ?? 1;
                    $pct = $maxTotal > 0 ? round(($item->total / $maxTotal) * 100) : 0;
                @endphp
                <div class="flex items-center gap-4">
                    <span class="w-6 h-6 rounded-full bg-{{ $c }}-100 dark:bg-{{ $c }}-500/20 text-{{ $c }}-600 dark:text-{{ $c }}-400 text-xs font-black flex items-center justify-center">{{ $index + 1 }}</span>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-semibold text-slate-700 dark:text-slate-300 truncate">{{ $item->nom }}</span>
                            <span class="ml-2 font-bold text-{{ $c }}-500 shrink-0">{{ $item->total }}</span>
                        </div>
                        <div class="h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-{{ $c }}-500 rounded-full transition-all duration-700" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center text-slate-500 text-sm py-6">{{ __('Aucune absence enregistrée.') }}</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Bar Chart - Notes Distribution
        const notesCtx = document.getElementById('notesChart').getContext('2d');
        new Chart(notesCtx, {
            type: 'bar',
            data: {
                labels: ['{{ __('Excellentes (>=16)') }}', '{{ __('Bonnes (12-16)') }}', '{{ __('Passables (10-12)') }}', '{{ __('Échecs (<10)') }}'],
                datasets: [{
                    label: '{{ __('Nombre d\'étudiants') }}',
                    data: [
                        {{ $stats['notes_distribution']['excellentes'] }}, 
                        {{ $stats['notes_distribution']['bonnes'] }}, 
                        {{ $stats['notes_distribution']['passables'] }}, 
                        {{ $stats['notes_distribution']['echecs'] }}
                    ],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)', // emerald
                        'rgba(59, 130, 246, 0.8)', // blue
                        'rgba(245, 158, 11, 0.8)', // amber
                        'rgba(239, 68, 68, 0.8)'   // red
                    ],
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(148, 163, 184, 0.1)' }, ticks: { color: '#94a3b8' } },
                    x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
                }
            }
        });

        // Doughnut Chart - Absences
        const absencesCtx = document.getElementById('absencesChart').getContext('2d');
        new Chart(absencesCtx, {
            type: 'doughnut',
            data: {
                labels: ['{{ __('Justifiées') }}', '{{ __('Non Justifiées') }}'],
                datasets: [{
                    data: [
                        {{ $stats['absences']['justifiees'] }},
                        {{ $stats['absences']['non_justifiees'] }}
                    ],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom', labels: { color: '#94a3b8', padding: 20 } }
                }
            }
        });

        // Line Chart - Moyennes par Filière
        const filiereCtx = document.getElementById('filiereChart').getContext('2d');
        new Chart(filiereCtx, {
            type: 'bar',
            data: {
                labels: @json($moyennesParFiliere->pluck('nom')),
                datasets: [{
                    label: '{{ __('Moyenne /20') }}',
                    data: @json($moyennesParFiliere->pluck('moyenne')),
                    backgroundColor: 'rgba(99, 102, 241, 0.15)',
                    borderColor: 'rgba(99, 102, 241, 0.9)',
                    borderWidth: 2,
                    borderRadius: 8,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(99, 102, 241, 1)',
                    pointRadius: 5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ' ' + ctx.parsed.y + ' / 20'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 20,
                        grid: { color: 'rgba(148, 163, 184, 0.1)' },
                        ticks: { color: '#94a3b8' }
                    },
                    x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
                }
            }
        });
    });
</script>
@endpush
