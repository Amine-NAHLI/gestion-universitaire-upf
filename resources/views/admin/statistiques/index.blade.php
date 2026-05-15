@extends('layouts.dashboard')

@section('title', __('Statistiques'))
@section('page-title', __('Statistiques & Analyses'))

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="space-y-8">
    <!-- General Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl flex flex-col items-center justify-center text-center">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('Étudiants') }}</span>
            <span class="text-3xl font-black text-indigo-600 dark:text-indigo-400">{{ $stats['total_etudiants'] }}</span>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl flex flex-col items-center justify-center text-center">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('Professeurs') }}</span>
            <span class="text-3xl font-black text-emerald-600 dark:text-emerald-400">{{ $stats['total_professeurs'] }}</span>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl flex flex-col items-center justify-center text-center">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('Modules') }}</span>
            <span class="text-3xl font-black text-amber-600 dark:text-amber-400">{{ $stats['total_modules'] }}</span>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl flex flex-col items-center justify-center text-center">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('Séances') }}</span>
            <span class="text-3xl font-black text-purple-600 dark:text-purple-400">{{ $stats['total_seances'] }}</span>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl flex flex-col items-center justify-center text-center">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('Moyenne Générale') }}</span>
            <span class="text-3xl font-black text-blue-600 dark:text-blue-400">{{ $stats['moyenne_generale'] }}</span>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl flex flex-col items-center justify-center text-center">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('Absences') }}</span>
            <span class="text-3xl font-black text-red-600 dark:text-red-400">{{ $stats['taux_absence'] }}%</span>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl">
            <h3 class="text-lg font-black text-gray-800 dark:text-white mb-6 uppercase tracking-wider">{{ __('Distribution des Notes') }}</h3>
            <div class="h-64">
                <canvas id="chartDistribution"></canvas>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl">
            <h3 class="text-lg font-black text-gray-800 dark:text-white mb-6 uppercase tracking-wider">{{ __('Absences Mensuelles') }}</h3>
            <div class="h-64">
                <canvas id="chartAbsences"></canvas>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl">
            <h3 class="text-lg font-black text-gray-800 dark:text-white mb-6 uppercase tracking-wider">{{ __('Top 8 Moyennes par Module') }}</h3>
            <div class="h-80">
                <canvas id="chartModules"></canvas>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl">
            <h3 class="text-lg font-black text-gray-800 dark:text-white mb-6 uppercase tracking-wider">{{ __('Répartition par Filière') }}</h3>
            <div class="h-80 flex items-center justify-center">
                <canvas id="chartFilieres"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const colors = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#f97316'];
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#9ca3af' : '#4b5563';
    const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';

    Chart.defaults.color = textColor;
    Chart.defaults.font.family = "'Outfit', sans-serif";

    const distributionData = @json(array_values($distribution));
    const distributionLabels = @json(array_keys($distribution));
    const absencesData = @json(array_values($absencesParMois));
    const absencesLabels = @json(array_keys($absencesParMois));
    const modulesData = @json($moyennesModules->pluck('moyenne'));
    const modulesLabels = @json($moyennesModules->pluck('nom'));
    const filieresData = @json($repartitionFilieres->pluck('count'));
    const filieresLabels = @json($repartitionFilieres->pluck('nom'));

    new Chart(document.getElementById('chartDistribution'), {
        type: 'bar',
        data: {
            labels: distributionLabels,
            datasets: [{
                label: '{{ __('Étudiants') }}',
                data: distributionData,
                backgroundColor: colors,
                borderRadius: 12,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: gridColor }, beginAtZero: true },
                x: { grid: { display: false } }
            }
        }
    });

    new Chart(document.getElementById('chartAbsences'), {
        type: 'line',
        data: {
            labels: absencesLabels,
            datasets: [{
                label: '{{ __('Absences') }}',
                data: absencesData,
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239, 68, 68, 0.05)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#ef4444',
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } },
            scales: {
                y: { grid: { color: gridColor }, beginAtZero: true },
                x: { grid: { display: false } }
            }
        }
    });

    new Chart(document.getElementById('chartModules'), {
        type: 'bar',
        data: {
            labels: modulesLabels,
            datasets: [{
                label: '{{ __('Moyenne Générale') }} /20',
                data: modulesData,
                backgroundColor: '#6366f1',
                borderRadius: 8,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: gridColor }, beginAtZero: true, max: 20 },
                y: { grid: { display: false } }
            }
        }
    });

    new Chart(document.getElementById('chartFilieres'), {
        type: 'doughnut',
        data: {
            labels: filieresLabels,
            datasets: [{
                data: filieresData,
                backgroundColor: colors,
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 20 } }
            },
            cutout: '70%'
        }
    });
});
</script>
@endpush
