@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')
@section('header_title', 'Bienvenue, ' . Auth::user()->prenom)
@section('header_subtitle', 'Voici ce qui se passe aujourd\'hui à l\'UPF.')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-dashboard.stat-card 
            title="Étudiants Inscrits" 
            value="{{ number_format($stats['etudiants_count']) }}" 
            icon="fa-user-graduate" 
            color="primary" 
            trend="12" 
            trendUp="true" />
            
        <x-dashboard.stat-card 
            title="Corps Professoral" 
            value="{{ number_format($stats['professeurs_count']) }}" 
            icon="fa-chalkboard-teacher" 
            color="indigo" 
            trend="2" 
            trendUp="true" />

        <x-dashboard.stat-card 
            title="Séances ce jour" 
            value="{{ $stats['seances_jour'] }}" 
            icon="fa-calendar-check" 
            color="emerald" />

        <x-dashboard.stat-card 
            title="Demandes en attente" 
            value="{{ $stats['demandes_attente'] }}" 
            icon="fa-file-invoice" 
            color="amber" 
            trend="5" 
            trendUp="false" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Notes Distribution Chart -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm" data-aos="fade-right">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">Distribution des Notes</h3>
                    <p class="text-sm text-slate-500">Répartition des moyennes générales</p>
                </div>
            </div>
            <div class="h-[300px]">
                <canvas id="notesChart"></canvas>
            </div>
        </div>

        <!-- Absences Chart -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm" data-aos="fade-left">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">Taux d'Absentéisme</h3>
                    <p class="text-sm text-slate-500">Proportion des absences justifiées vs non justifiées</p>
                </div>
            </div>
            <div class="h-[300px] flex justify-center">
                <canvas id="absencesChart"></canvas>
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
                labels: ['Excellentes (>=16)', 'Bonnes (12-16)', 'Passables (10-12)', 'Échecs (<10)'],
                datasets: [{
                    label: 'Nombre d\'étudiants',
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
                labels: ['Justifiées', 'Non Justifiées'],
                datasets: [{
                    data: [
                        {{ $stats['absences']['justifiees'] }}, 
                        {{ $stats['absences']['non_justifiees'] }}
                    ],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)', // emerald
                        'rgba(239, 68, 68, 0.8)'   // red
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
    });
</script>
@endpush
