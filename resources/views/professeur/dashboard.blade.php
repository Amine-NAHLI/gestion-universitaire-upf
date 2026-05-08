@extends('layouts.dashboard')

@section('title', 'Dashboard Professeur')
@section('header_title', 'Espace Enseignant')
@section('header_subtitle', 'Gérez vos cours, notes et absences en toute simplicité.')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-dashboard.stat-card 
            title="Heures assurées" 
            value="124h" 
            icon="fa-clock" 
            color="indigo" />
            
        <x-dashboard.stat-card 
            title="Étudiants suivis" 
            value="320" 
            icon="fa-users" 
            color="primary" />

        <x-dashboard.stat-card 
            title="Modules actifs" 
            value="4" 
            icon="fa-layer-group" 
            color="emerald" />

        <x-dashboard.stat-card 
            title="Taux de présence" 
            value="92%" 
            icon="fa-chart-pie" 
            color="amber" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Next Classes -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm" data-aos="fade-up">
            <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-6">Prochaines Séances</h3>
            <div class="space-y-4">
                <div class="flex items-center p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700">
                    <div class="bg-primary-500 text-white p-3 rounded-xl text-center min-w-[60px]">
                        <span class="block text-xs font-bold uppercase">MAI</span>
                        <span class="block text-xl font-black">10</span>
                    </div>
                    <div class="ml-4 flex-1">
                        <h4 class="font-bold text-slate-800 dark:text-white">Technologie Web 2</h4>
                        <p class="text-xs text-slate-500">08:00 - 10:00 • Amphi Khaldoun</p>
                    </div>
                    <span class="bg-primary-100 dark:bg-primary-500/20 text-primary-600 dark:text-primary-400 text-[10px] font-bold px-2 py-1 rounded-full uppercase">Cours</span>
                </div>
                <div class="flex items-center p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700">
                    <div class="bg-emerald-500 text-white p-3 rounded-xl text-center min-w-[60px]">
                        <span class="block text-xs font-bold uppercase">MAI</span>
                        <span class="block text-xl font-black">10</span>
                    </div>
                    <div class="ml-4 flex-1">
                        <h4 class="font-bold text-slate-800 dark:text-white">Base de Données</h4>
                        <p class="text-xs text-slate-500">14:00 - 16:00 • Salle Info1</p>
                    </div>
                    <span class="bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold px-2 py-1 rounded-full uppercase">TP</span>
                </div>
            </div>
        </div>

        <!-- Grading Progress -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm" data-aos="fade-up" data-aos-delay="200">
            <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-6">Saisie des Notes</h3>
            <div class="space-y-6">
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-bold text-slate-700 dark:text-slate-300">Technologie Web 2 (CC1)</span>
                        <span class="text-primary-500 font-bold">85%</span>
                    </div>
                    <div class="h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full bg-primary-500 rounded-full w-[85%]"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-bold text-slate-700 dark:text-slate-300">Intelligence Artificielle (CC1)</span>
                        <span class="text-amber-500 font-bold">40%</span>
                    </div>
                    <div class="h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-500 rounded-full w-[40%]"></div>
                    </div>
                </div>
            </div>
            <button class="w-full mt-10 py-4 bg-slate-900 dark:bg-primary-600 text-white font-bold rounded-2xl hover:bg-slate-800 dark:hover:bg-primary-500 transition-all shadow-lg shadow-primary-500/20">
                Accéder à la saisie
            </button>
        </div>
    </div>
@endsection
