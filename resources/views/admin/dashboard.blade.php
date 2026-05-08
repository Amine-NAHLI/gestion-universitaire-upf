@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')
@section('header_title', 'Bienvenue, ' . Auth::user()->prenom)
@section('header_subtitle', 'Voici ce qui se passe aujourd\'hui à l\'UPF.')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-dashboard.stat-card 
            title="Étudiants Inscrits" 
            value="1,284" 
            icon="fa-user-graduate" 
            color="primary" 
            trend="12" 
            trendUp="true" />
            
        <x-dashboard.stat-card 
            title="Corps Professoral" 
            value="86" 
            icon="fa-chalkboard-teacher" 
            color="indigo" 
            trend="2" 
            trendUp="true" />

        <x-dashboard.stat-card 
            title="Séances ce jour" 
            value="24" 
            icon="fa-calendar-check" 
            color="emerald" />

        <x-dashboard.stat-card 
            title="Demandes en attente" 
            value="15" 
            icon="fa-file-invoice" 
            color="amber" 
            trend="5" 
            trendUp="false" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Stats Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm" data-aos="fade-right">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">Fréquentation des Salles</h3>
                    <p class="text-sm text-slate-500">Taux d'occupation par type de salle</p>
                </div>
                <select class="bg-slate-100 dark:bg-slate-800 border-none rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 focus:ring-primary-500">
                    <option>Cette semaine</option>
                    <option>Mois dernier</option>
                </select>
            </div>
            <div class="h-[300px]">
                <canvas id="mainChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm" data-aos="fade-left">
            <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-6">Activités Récentes</h3>
            <div class="space-y-6">
                @for($i=1; $i<=5; $i++)
                <div class="flex items-start space-x-4">
                    <div class="p-2 bg-slate-100 dark:bg-slate-800 rounded-lg">
                        <i class="fas fa-check text-emerald-500"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-700 dark:text-white">Nouvelle inscription</p>
                        <p class="text-xs text-slate-500">Un nouvel étudiant a rejoint la filière GINFO.</p>
                        <span class="text-[10px] text-slate-400 font-medium">Il y a 5 min</span>
                    </div>
                </div>
                @endfor
            </div>
            <button class="w-full mt-8 py-3 text-sm font-bold text-primary-500 bg-primary-50 dark:bg-primary-500/10 rounded-xl hover:bg-primary-500 hover:text-white transition-all">
                Voir tout l'historique
            </button>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('mainChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                datasets: [{
                    label: 'Taux d\'occupation (%)',
                    data: [65, 78, 52, 91, 74, 30],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { display: false }, ticks: { color: '#94a3b8' } },
                    x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
                }
            }
        });
    });
</script>
@endpush
