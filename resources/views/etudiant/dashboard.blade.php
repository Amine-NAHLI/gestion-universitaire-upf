@extends('layouts.dashboard')

@section('title', __('Dashboard Étudiant'))
@section('header_title', __('Salut, ') . Auth::user()->prenom . ' ! 👋')
@section('header_subtitle', __('Voici ton résumé académique pour le semestre actuel.'))

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-dashboard.stat-card 
            title="{{ __('Moyenne Générale') }}" 
            value="15.42" 
            icon="fa-graduation-cap" 
            color="primary" />
            
        <x-dashboard.stat-card 
            title="{{ __('Absences') }}" 
            value="3" 
            icon="fa-user-clock" 
            color="rose" 
            trend="1" 
            trendUp="false" />

        <x-dashboard.stat-card 
            title="{{ __('Crédits validés') }}" 
            value="24/30" 
            icon="fa-check-circle" 
            color="emerald" />

        <x-dashboard.stat-card 
            title="{{ __('Supports de cours') }}" 
            value="12" 
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
                        <tr class="group hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-4 font-bold text-slate-700 dark:text-white">Technologie Web 2</td>
                            <td class="py-4 text-slate-600 dark:text-slate-400">16.00</td>
                            <td class="py-4 text-slate-600 dark:text-slate-400">14.50</td>
                            <td class="py-4"><span class="bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 font-bold px-2 py-1 rounded-lg">15.25</span></td>
                        </tr>
                        <tr class="group hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-4 font-bold text-slate-700 dark:text-white">Intelligence Artificielle</td>
                            <td class="py-4 text-slate-600 dark:text-slate-400">12.00</td>
                            <td class="py-4 text-slate-600 dark:text-slate-400">--</td>
                            <td class="py-4"><span class="bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 font-bold px-2 py-1 rounded-lg">12.00</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Schedule Today -->
        <div class="bg-slate-900 text-white p-8 rounded-3xl shadow-xl shadow-primary-500/10 relative overflow-hidden" data-aos="fade-left">
            <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-primary-500/20 rounded-full blur-3xl"></div>
            <h3 class="text-xl font-bold mb-6 relative">{{ __('Planning du jour') }}</h3>
            <div class="space-y-6 relative">
                <div class="border-l-4 border-primary-500 pl-4 py-1">
                    <p class="text-xs font-bold text-primary-400 uppercase tracking-widest">08:00 - 10:00</p>
                    <p class="font-bold">Cybersécurité</p>
                    <p class="text-xs text-slate-400">Salle Info2</p>
                </div>
                <div class="border-l-4 border-slate-700 pl-4 py-1 opacity-50">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">10:00 - 12:00</p>
                    <p class="font-bold">Pause</p>
                </div>
                <div class="border-l-4 border-emerald-500 pl-4 py-1">
                    <p class="text-xs font-bold text-emerald-400 uppercase tracking-widest">14:00 - 16:00</p>
                    <p class="font-bold">Technologie Web 2</p>
                    <p class="text-xs text-slate-400">Salle TD1</p>
                </div>
            </div>
            <button class="w-full mt-10 py-4 bg-white text-slate-900 font-black rounded-2xl hover:scale-105 transition-transform">
                {{ __('Emploi du temps complet') }}
            </button>
        </div>
    </div>
@endsection
