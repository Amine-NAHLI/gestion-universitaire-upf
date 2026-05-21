@extends('layouts.dashboard')

@section('title', 'Emploi du Temps - E-UPF')

@section('content')
<div class="space-y-6" data-aos="fade-up">

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- HEADER                                                      --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-slate-700/60 rounded-2xl p-5 shadow-md overflow-hidden">
        <div class="absolute -top-12 -right-12 w-48 h-48 bg-indigo-500/10 dark:bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 -left-12 w-40 h-40 bg-purple-500/10 dark:bg-purple-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="relative flex-shrink-0">
                <span class="absolute inset-0 rounded-full bg-indigo-400 opacity-20 animate-ping"></span>
                <div class="relative w-12 h-12 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/25">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>

            <div class="flex-1 min-w-0">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/50 mb-1">
                    {{ __('Portail Famille · UPF') }}
                </span>
                <h1 class="text-lg font-extrabold text-gray-900 dark:text-white tracking-tight">
                    {{ __('Bienvenue sur votre espace parent') }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-slate-400 mt-0.5">
                    {{ __('Suivez la scolarité de votre enfant') }}
                </p>
            </div>

            <div class="flex gap-3 flex-shrink-0">
                <div class="px-4 py-2.5 bg-gray-50 dark:bg-slate-800/60 border border-gray-100 dark:border-slate-700 rounded-xl text-center">
                    <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">Statut</p>
                    <span class="inline-flex items-center text-xs font-bold text-emerald-600 dark:text-emerald-400 mt-0.5">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-ping"></span>
                        {{ __('Actif & Lié') }}
                    </span>
                </div>
                <div class="px-4 py-2.5 bg-gray-50 dark:bg-slate-800/60 border border-gray-100 dark:border-slate-700 rounded-xl text-center">
                    <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">Étudiant</p>
                    <span class="text-xs font-bold text-gray-700 dark:text-slate-300 mt-0.5 block">
                        {{ $studentName ?? auth()->user()->name }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- STATS + GRAPHIQUE (null guard)                             --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    @if($student)

        {{-- Prochaine séance --}}
        <div class="grid grid-cols-1 max-w-xs gap-4 mb-4">
            {{-- Card 3 : Prochaine séance --}}
            <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-slate-700/50 rounded-2xl p-5 shadow-sm overflow-hidden group hover:shadow-md transition-shadow duration-200">
                <div class="absolute -top-8 -right-8 w-28 h-28 bg-purple-500/5 rounded-full blur-2xl pointer-events-none group-hover:bg-purple-500/10 transition-colors"></div>
                <div class="flex items-start justify-between mb-3">
                    <div class="w-9 h-9 bg-purple-50 dark:bg-purple-950/40 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-purple-500 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-1">
                    @if($prochaineSeance)
                        <p class="text-sm font-black text-gray-800 dark:text-white leading-tight truncate">
                            {{ $prochaineSeance->module?->nom ?? 'Module N/A' }}
                        </p>
                        <p class="text-xs text-purple-600 dark:text-purple-400 font-semibold mt-1">
                            {{ $prochaineSeance->date->format('d/m/Y') }}
                            · {{ $prochaineSeance->heure_debut->format('H:i') }}
                        </p>
                        <p class="text-[11px] text-gray-400 dark:text-slate-500 mt-0.5 truncate">
                            {{ $prochaineSeance->salle?->nom ?? 'Salle N/A' }}
                        </p>
                    @else
                        <p class="text-sm font-bold text-gray-400 dark:text-slate-500 leading-none">Aucune séance prévue</p>
                    @endif
                    <p class="text-xs text-gray-500 dark:text-slate-400 font-medium mt-1.5">Prochaine séance</p>
                </div>
            </div>
        </div>

    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-slate-700/50 rounded-2xl shadow-sm overflow-hidden">
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-b border-gray-100 dark:border-slate-700/60">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-gray-900 dark:text-white text-sm tracking-tight">Emploi du Temps — Semaine en cours</h2>
                        <p class="text-xs text-gray-400 dark:text-slate-500">
                            {{ $edtTotal }} séance{{ $edtTotal !== 1 ? 's' : '' }} cette semaine
                        </p>
                    </div>
                </div>
                @if($lundiFmt && $vendrediFmt)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-300 rounded-xl border border-indigo-100 dark:border-indigo-800/40 text-xs font-bold self-start sm:self-auto flex-shrink-0">
                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Semaine du {{ $lundiFmt }} au {{ $vendrediFmt }}
                </span>
                @endif
            </div>

            @if($edtTotal > 0)
                {{-- Grille 5 jours --}}
                <div class="overflow-x-auto">
                    <div class="grid grid-cols-5 divide-x divide-gray-100 dark:divide-slate-700/50" style="min-width: 580px;">
                        @foreach($jours as $jour)
                            @php $isToday = ($jour === $jourActuel); @endphp
                            <div class="flex flex-col {{ $isToday ? 'bg-indigo-50/40 dark:bg-indigo-950/20' : '' }}">

                                {{-- Entête jour --}}
                                <div class="px-2 py-2.5 text-center border-b
                                    {{ $isToday
                                        ? 'bg-gradient-to-r from-indigo-600 to-purple-700 border-transparent'
                                        : 'bg-gray-50 dark:bg-slate-800/70 border-gray-100 dark:border-slate-700/50' }}">
                                    <p class="text-[11px] font-black uppercase tracking-wider
                                        {{ $isToday ? 'text-white' : 'text-gray-500 dark:text-slate-400' }}">
                                        {{ $jour }}
                                    </p>
                                    @if($isToday)
                                        <p class="text-[9px] font-semibold text-indigo-200 mt-0.5">Aujourd'hui</p>
                                    @endif
                                </div>

                                {{-- Séances --}}
                                <div class="flex-1 p-2 space-y-2" style="min-height: 140px;">
                                    @forelse($emploiDuTemps[$jour] as $seance)
                                        @php $dot = $dotColors[$seance->module->id % count($dotColors)]; @endphp
                                        <div class="rounded-xl p-2.5 border transition-shadow hover:shadow-sm
                                            {{ $isToday
                                                ? 'bg-white dark:bg-slate-800 border-indigo-200 dark:border-indigo-700/50 shadow-sm'
                                                : 'bg-white dark:bg-slate-800/60 border-gray-100 dark:border-slate-700/40' }}">
                                            {{-- Nom module --}}
                                            <div class="flex items-start gap-1.5 mb-1">
                                                <span class="w-2 h-2 rounded-full flex-shrink-0 mt-0.5"
                                                      style="background-color: {{ $dot }}"></span>
                                                <p class="text-[11px] font-bold text-gray-800 dark:text-slate-100 leading-tight"
                                                   style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                    {{ $seance->module?->nom ?? 'Module' }}
                                                </p>
                                            </div>
                                            {{-- Horaire --}}
                                            <p class="text-[10px] font-semibold text-indigo-500 dark:text-indigo-400">
                                                {{ $seance->heure_debut->format('H:i') }} → {{ $seance->heure_fin->format('H:i') }}
                                            </p>
                                            {{-- Salle --}}
                                            @if($seance->salle)
                                                <p class="text-[10px] text-gray-400 dark:text-slate-500 truncate mt-0.5">
                                                    <i class="fa-solid fa-location-dot text-[8px] mr-0.5"></i>{{ $seance->salle->nom }}
                                                </p>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="flex items-center justify-center" style="min-height: 100px;">
                                            <span class="text-2xl text-gray-200 dark:text-slate-700 select-none font-light">—</span>
                                        </div>
                                    @endforelse
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                {{-- Aucune séance --}}
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-slate-400">Aucune séance programmée cette semaine</p>
                    <p class="text-xs text-gray-400 dark:text-slate-500 mt-1">Les séances apparaîtront ici dès leur planification</p>
                </div>
            @endif

        </div>{{-- /edt --}}
    @else
        {{-- ── Aucun étudiant lié ───────────────────────────────────── --}}
        <div class="bg-white dark:bg-gray-800 border border-amber-200 dark:border-amber-700/40 rounded-2xl p-8 shadow-sm text-center">
            <div class="w-14 h-14 bg-amber-50 dark:bg-amber-950/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-base font-extrabold text-gray-800 dark:text-white mb-1">{{ __('Aucun étudiant associé') }}</h3>
            <p class="text-sm text-gray-500 dark:text-slate-400 max-w-sm mx-auto">
                {{ __('Votre compte parent n\'est pas encore lié') }}
            </p>
        </div>
    @endif
</div>
@endsection
