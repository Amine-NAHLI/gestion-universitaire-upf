@extends('layouts.dashboard')

@section('title', 'Tableau de Bord Parent')

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
                    Portail Famille · UPF
                </span>
                <h1 class="text-lg font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Bienvenue sur votre espace parent
                </h1>
                <p class="text-sm text-gray-500 dark:text-slate-400 mt-0.5">
                    Suivez la scolarité de votre enfant grâce à l'Assistant E-UPF.
                </p>
            </div>

            <div class="flex gap-3 flex-shrink-0">
                <div class="px-4 py-2.5 bg-gray-50 dark:bg-slate-800/60 border border-gray-100 dark:border-slate-700 rounded-xl text-center">
                    <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">Statut</p>
                    <span class="inline-flex items-center text-xs font-bold text-emerald-600 dark:text-emerald-400 mt-0.5">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-ping"></span>
                        Actif & Lié
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

        {{-- ── 4 Cards statistiques ────────────────────────────────── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Card 1 : Moyenne générale --}}
            <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-slate-700/50 rounded-2xl p-5 shadow-sm overflow-hidden group hover:shadow-md transition-shadow duration-200">
                <div class="absolute -top-8 -right-8 w-28 h-28 bg-indigo-500/5 rounded-full blur-2xl pointer-events-none group-hover:bg-indigo-500/10 transition-colors"></div>
                <div class="flex items-start justify-between mb-3">
                    <div class="w-9 h-9 bg-indigo-50 dark:bg-indigo-950/50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-4.5 h-4.5 text-indigo-500 dark:text-indigo-400 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    @if($mention)
                        @if($mentionCouleur === 'green')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-50 dark:bg-green-950/40 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800/50">
                                {{ $mention }}
                            </span>
                        @elseif($mentionCouleur === 'yellow')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-yellow-50 dark:bg-yellow-950/40 text-yellow-700 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800/50">
                                {{ $mention }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-50 dark:bg-red-950/40 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800/50">
                                {{ $mention }}
                            </span>
                        @endif
                    @endif
                </div>
                <div class="mt-1">
                    @if($moyenne !== null)
                        @if($mentionCouleur === 'green')
                            <p class="text-3xl font-black text-green-600 dark:text-green-400 leading-none">{{ $moyenne }}<span class="text-base font-bold text-gray-400 dark:text-slate-500">/20</span></p>
                        @elseif($mentionCouleur === 'yellow')
                            <p class="text-3xl font-black text-yellow-600 dark:text-yellow-400 leading-none">{{ $moyenne }}<span class="text-base font-bold text-gray-400 dark:text-slate-500">/20</span></p>
                        @else
                            <p class="text-3xl font-black text-red-600 dark:text-red-400 leading-none">{{ $moyenne }}<span class="text-base font-bold text-gray-400 dark:text-slate-500">/20</span></p>
                        @endif
                    @else
                        <p class="text-3xl font-black text-gray-400 dark:text-slate-500 leading-none">N/A</p>
                    @endif
                    <p class="text-xs text-gray-500 dark:text-slate-400 font-medium mt-1.5">Moyenne générale</p>
                </div>
            </div>

            {{-- Card 2 : Absences ce mois --}}
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm overflow-hidden group hover:shadow-md transition-shadow duration-200
                {{ $absencesAlert
                    ? 'border-2 border-red-400 dark:border-red-500/70'
                    : 'border border-gray-200 dark:border-slate-700/50' }}">
                <div class="absolute -top-8 -right-8 w-28 h-28 rounded-full blur-2xl pointer-events-none {{ $absencesAlert ? 'bg-red-500/10' : 'bg-emerald-500/5 group-hover:bg-emerald-500/10' }} transition-colors"></div>
                <div class="flex items-start justify-between mb-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0
                        {{ $absencesAlert ? 'bg-red-50 dark:bg-red-950/40' : 'bg-emerald-50 dark:bg-emerald-950/40' }}">
                        @if($absencesAlert)
                            <svg class="w-5 h-5 text-red-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        @endif
                    </div>
                    @if($absencesAlert)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-50 dark:bg-red-950/40 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800/50">
                            Attention
                        </span>
                    @endif
                </div>
                <div class="mt-1">
                    <p class="text-3xl font-black leading-none {{ $absencesAlert ? 'text-red-600 dark:text-red-400' : 'text-gray-800 dark:text-white' }}">
                        {{ $absencesNonJustifiees }}
                        <span class="text-base font-bold text-gray-400 dark:text-slate-500">/ {{ $absencesTotal }}</span>
                    </p>
                    <p class="text-xs text-gray-500 dark:text-slate-400 font-medium mt-1.5">
                        Non justifiées / Total ce mois
                    </p>
                </div>
            </div>

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

            {{-- Card 4 : Demandes en attente --}}
            <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-slate-700/50 rounded-2xl p-5 shadow-sm overflow-hidden group hover:shadow-md transition-shadow duration-200">
                <div class="absolute -top-8 -right-8 w-28 h-28 bg-indigo-500/5 rounded-full blur-2xl pointer-events-none group-hover:bg-indigo-500/10 transition-colors"></div>
                <div class="flex items-start justify-between mb-3">
                    <div class="w-9 h-9 bg-indigo-50 dark:bg-indigo-950/50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-1">
                    @if($demandesEnAttente === 0)
                        <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 leading-none">✓</p>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 font-bold mt-1">Tout est traité</p>
                    @else
                        <p class="text-3xl font-black text-amber-600 dark:text-amber-400 leading-none">{{ $demandesEnAttente }}</p>
                        <p class="text-xs text-amber-600 dark:text-amber-400 font-semibold mt-1">en attente</p>
                    @endif
                    <p class="text-xs text-gray-500 dark:text-slate-400 font-medium mt-1">Demandes administratives</p>
                </div>
            </div>

        </div>{{-- /grid cards --}}

        {{-- ── Notifications & Alertes ─────────────────────────────────── --}}
        <div
            x-data="{
                notifications: [],
                notifLoading: true,

                async init() {
                    try {
                        const res = await fetch('{{ route('parent.notifications') }}', {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                        });
                        this.notifications = await res.json();
                    } catch (e) {}
                    finally { this.notifLoading = false; }
                },

                async markRead(notif) {
                    notif.lue = true;
                    try {
                        await fetch('{{ url('/parent/notifications') }}/' + notif.id + '/read', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                        });
                    } catch (e) {}
                },

                isNew(notif) {
                    return !notif.lue && (new Date() - new Date(notif.created_at)) < 86400000;
                },

                relativeTime(dateStr) {
                    const diff = Math.floor((new Date() - new Date(dateStr)) / 1000);
                    if (diff < 60) return 'à l\'instant';
                    if (diff < 3600) return 'il y a ' + Math.floor(diff / 60) + ' min';
                    if (diff < 86400) return 'il y a ' + Math.floor(diff / 3600) + 'h';
                    if (diff < 172800) return 'hier';
                    return new Date(dateStr).toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit' });
                },

                faIcon(type) {
                    const map = { info: 'fa-circle-info', warning: 'fa-triangle-exclamation', urgent: 'fa-bell', error: 'fa-circle-exclamation', success: 'fa-circle-check' };
                    return 'fa-solid ' + (map[type] || 'fa-bell');
                }
            }"
            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-slate-700/50 rounded-2xl shadow-sm overflow-hidden"
        >
            {{-- Header --}}
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-slate-700/60">
                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-bell text-white text-sm"></i>
                </div>
                <div class="flex-1">
                    <h2 class="font-extrabold text-gray-900 dark:text-white text-sm tracking-tight">Notifications & Alertes</h2>
                    <p class="text-xs text-gray-400 dark:text-slate-500">Alertes IA sur le suivi de votre enfant</p>
                </div>
                <span
                    x-show="notifications.filter(n => !n.lue).length > 0"
                    x-cloak
                    x-text="notifications.filter(n => !n.lue).length"
                    class="inline-flex items-center justify-center w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full"
                ></span>
            </div>

            <div class="px-5 py-4">
                {{-- Loading --}}
                <div x-show="notifLoading" class="flex items-center gap-3 py-2">
                    <div class="w-5 h-5 border-2 border-indigo-400 border-t-transparent rounded-full animate-spin flex-shrink-0"></div>
                    <span class="text-sm text-gray-400 dark:text-slate-500">Chargement des alertes...</span>
                </div>

                {{-- Empty state --}}
                <div x-show="!notifLoading && notifications.length === 0" class="flex items-center gap-3 py-2">
                    <div class="w-8 h-8 bg-emerald-50 dark:bg-emerald-950/30 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                    </div>
                    <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">Aucune alerte — tout va bien ✓</p>
                </div>

                {{-- Notification list --}}
                <template x-if="!notifLoading && notifications.length > 0">
                    <div class="space-y-2">
                        <template x-for="notif in notifications" :key="notif.id">
                            <div
                                :class="{
                                    'opacity-55': notif.lue,
                                    'bg-blue-50 dark:bg-blue-950/20 border-blue-200 dark:border-blue-800/40': notif.type === 'info',
                                    'bg-amber-50 dark:bg-amber-950/20 border-amber-200 dark:border-amber-800/40': notif.type === 'warning',
                                    'bg-red-50 dark:bg-red-950/20 border-red-200 dark:border-red-800/40': notif.type === 'urgent' || notif.type === 'error',
                                    'bg-emerald-50 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-800/40': notif.type === 'success',
                                }"
                                class="flex items-start gap-3 px-4 py-3 rounded-xl border transition-all duration-150"
                            >
                                {{-- Icon --}}
                                <div
                                    :class="{
                                        'bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400': notif.type === 'info',
                                        'bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400': notif.type === 'warning',
                                        'bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400': notif.type === 'urgent' || notif.type === 'error',
                                        'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400': notif.type === 'success',
                                    }"
                                    class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center mt-0.5"
                                >
                                    <i :class="faIcon(notif.type)" class="text-sm"></i>
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap mb-0.5">
                                        <span class="text-xs font-extrabold text-gray-800 dark:text-slate-200" x-text="notif.titre"></span>
                                        <template x-if="isNew(notif)">
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-indigo-500 text-white">Nouveau</span>
                                        </template>
                                    </div>
                                    <p class="text-xs text-gray-600 dark:text-slate-400 leading-relaxed" x-text="notif.message"></p>
                                    <p class="text-[11px] text-gray-400 dark:text-slate-500 mt-1" x-text="relativeTime(notif.created_at)"></p>
                                </div>

                                {{-- Mark as read --}}
                                <template x-if="!notif.lue">
                                    <button
                                        x-on:click="markRead(notif)"
                                        class="flex-shrink-0 w-7 h-7 rounded-lg flex items-center justify-center text-gray-400 hover:text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-950/30 transition-all duration-150 mt-0.5"
                                        title="Marquer comme lu"
                                    >
                                        <i class="fa-solid fa-check text-[11px]"></i>
                                    </button>
                                </template>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        {{-- ── Emploi du temps ────────────────────────────────────────── --}}
        @php
            $edtTotal  = array_sum(array_map('count', $emploiDuTemps));
            $dotColors = ['#818cf8','#a78bfa','#60a5fa','#c084fc','#e879f9','#22d3ee'];
            $jours     = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
        @endphp
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

        {{-- ── Graphique des notes ──────────────────────────────────── --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-slate-700/50 rounded-2xl shadow-sm overflow-hidden">
            {{-- En-tête card --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-slate-700/60">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-gray-900 dark:text-white text-sm tracking-tight">Notes par module</h2>
                        <p class="text-xs text-gray-400 dark:text-slate-500">Seuil de validation : 10/20</p>
                    </div>
                </div>
                @if($notesMoyenne !== null)
                    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 dark:bg-indigo-950/40 rounded-xl border border-indigo-100 dark:border-indigo-800/40">
                        <span class="text-xs font-bold text-indigo-600 dark:text-indigo-300">Moyenne :</span>
                        <span class="text-sm font-black text-indigo-700 dark:text-indigo-200">{{ $notesMoyenne }}/20</span>
                    </div>
                @endif
            </div>

            {{-- Corps du graphique --}}
            <div class="px-6 py-5">
                @if(count($notesLabels) > 0)
                    <div style="height: 250px; position: relative;">
                        <canvas id="notesChart"></canvas>
                    </div>

                    {{-- Légende manuelle --}}
                    <div class="flex flex-wrap items-center gap-4 mt-4 justify-center">
                        <span class="inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-slate-400 font-medium">
                            <span class="w-8 h-0.5 bg-red-400 rounded" style="border-top: 2px dashed rgb(248,113,113); display:inline-block; width:24px;"></span>
                            Seuil de validation (10)
                        </span>
                        @if($notesMoyenne !== null)
                        <span class="inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-slate-400 font-medium">
                            <span class="inline-block w-6 h-0.5 bg-purple-400 rounded"></span>
                            Votre moyenne ({{ $notesMoyenne }})
                        </span>
                        @endif
                        <span class="inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-slate-400 font-medium">
                            <span class="inline-block w-3 h-3 rounded-sm bg-green-400 dark:bg-green-500"></span>
                            ≥ 14 — Bien
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-slate-400 font-medium">
                            <span class="inline-block w-3 h-3 rounded-sm bg-indigo-400"></span>
                            ≥ 10 — Passable
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-slate-400 font-medium">
                            <span class="inline-block w-3 h-3 rounded-sm bg-red-400"></span>
                            &lt; 10 — Insuffisant
                        </span>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="w-14 h-14 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-4">
                            <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-500 dark:text-slate-400">Aucune note disponible pour le moment</p>
                        <p class="text-xs text-gray-400 dark:text-slate-500 mt-1">Les notes apparaîtront ici dès leur saisie</p>
                    </div>
                @endif
            </div>
        </div>

    @else
        {{-- ── Aucun étudiant lié ───────────────────────────────────── --}}
        <div class="bg-white dark:bg-gray-800 border border-amber-200 dark:border-amber-700/40 rounded-2xl p-8 shadow-sm text-center">
            <div class="w-14 h-14 bg-amber-50 dark:bg-amber-950/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-base font-extrabold text-gray-800 dark:text-white mb-1">Aucun étudiant associé</h3>
            <p class="text-sm text-gray-500 dark:text-slate-400 max-w-sm mx-auto">
                Votre compte parent n'est pas encore lié à un profil étudiant. Contactez l'administration de l'UPF.
            </p>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- CHATBOT (toujours visible)                                 --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div
        x-data="{
            messages: [],
            input: '',
            loading: false,
            limit: false,
            welcomeLoading: true,

            async init() {
                // 1. Try history first
                try {
                    const histRes = await fetch('{{ route('parent.chatbot.history') }}', {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                    });
                    const hist = await histRes.json();
                    if (hist.length > 0) {
                        this.messages = hist.map(m => ({
                            id: m.id,
                            role: m.role,
                            content: m.content,
                            feedback: m.feedback,
                            isWelcome: m.is_welcome,
                        }));
                        this.welcomeLoading = false;
                        this.$nextTick(() => {
                            const el = this.$refs.messageArea;
                            if (el) el.scrollTop = el.scrollHeight;
                        });
                        return;
                    }
                } catch (e) {}

                // 2. No history — fetch dynamic welcome
                try {
                    const res = await fetch('{{ route('parent.chatbot.welcome') }}', {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                        },
                    });
                    const data = await res.json();
                    this.messages.push({ id: data.id ?? null, role: 'assistant', content: data.message, feedback: null, isWelcome: true });
                } catch (e) {
                    this.messages.push({ id: null, role: 'assistant', content: 'Bonjour ! Je suis votre Assistant E-UPF. Comment puis-je vous aider concernant la scolarité de votre enfant ?', feedback: null, isWelcome: true });
                } finally {
                    this.welcomeLoading = false;
                    this.$nextTick(() => {
                        const el = this.$refs.messageArea;
                        if (el) el.scrollTop = el.scrollHeight;
                    });
                }
            },

            async saveFeedback(msgId, fb) {
                if (!msgId) return;
                try {
                    await fetch('{{ route('parent.chatbot.feedback') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                        },
                        body: JSON.stringify({ message_id: msgId, feedback: fb }),
                    });
                } catch (e) {}
            },

            async sendMessage() {
                if (!this.input.trim() || this.loading || this.limit) return;

                const text = this.input.trim();
                this.messages.push({ id: null, role: 'user', content: text });
                this.input = '';
                this.loading = true;

                const history = this.messages
                    .slice(0, -1)
                    .slice(-6)
                    .map(m => ({ role: m.role, content: m.content }));

                this.$nextTick(() => {
                    const el = this.$refs.messageArea;
                    if (el) el.scrollTop = el.scrollHeight;
                });

                try {
                    const res = await fetch('{{ route('parent.chatbot') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                        },
                        body: JSON.stringify({ message: text, history }),
                    });

                    const data = await res.json();

                    if (res.status === 429) {
                        this.limit = true;
                        this.messages.push({ id: null, role: 'assistant', content: data.reply, feedback: null, isWelcome: false });
                    } else {
                        this.messages.push({ id: data.message_id ?? null, role: 'assistant', content: data.reply, feedback: null, isWelcome: false });
                    }
                } catch (e) {
                    this.messages.push({ id: null, role: 'assistant', content: 'L\'assistant est indisponible. Réessayez plus tard.', feedback: null, isWelcome: false });
                } finally {
                    this.loading = false;
                    this.$nextTick(() => {
                        const el = this.$refs.messageArea;
                        if (el) el.scrollTop = el.scrollHeight;
                    });
                }
            },

            quickQuestion(text) {
                this.input = text;
                this.sendMessage();
            }
        }"
        class="flex flex-col bg-white dark:bg-gray-900 border border-gray-200 dark:border-slate-700/50 rounded-2xl shadow-lg overflow-hidden"
    >
        {{-- Chat header --}}
        <div class="relative flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-indigo-900 via-indigo-800 to-purple-900 overflow-hidden">
            <div class="absolute -top-6 -right-6 w-32 h-32 bg-purple-400/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-indigo-400/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative flex-shrink-0">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-purple-400 rounded-xl flex items-center justify-center text-white shadow-md shadow-indigo-900/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
                <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-400 rounded-full border-2 border-indigo-900"></span>
            </div>

            <div class="flex-1 min-w-0">
                <h2 class="font-extrabold text-white text-sm tracking-tight leading-none">Assistant E-UPF</h2>
                <p class="text-indigo-200 text-xs font-medium mt-0.5">Assistant Scolaire · UPF</p>
            </div>

            <div class="flex-shrink-0 flex items-center gap-2">
                <span x-show="!limit" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white/10 text-indigo-100 rounded-full text-[10px] font-bold border border-white/10 backdrop-blur-sm">
                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                    En ligne
                </span>
                <span x-show="limit" x-cloak class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-400/20 text-amber-200 rounded-full text-[10px] font-bold border border-amber-400/20">
                    <i class="fa-solid fa-clock-rotate-left text-[9px]"></i>
                    Limite atteinte
                </span>
            </div>
        </div>

        {{-- Suggestions rapides (contextuelles) --}}
        <div class="px-5 pt-4 pb-2 flex flex-wrap gap-2 border-b border-gray-100 dark:border-slate-800">
            @foreach($suggestionsChat as $suggestion)
                <button
                    x-on:click="quickQuestion('{{ $suggestion }}')"
                    :disabled="loading || limit"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold border transition-all duration-150 disabled:opacity-40 disabled:cursor-not-allowed
                           {{ $loop->index % 2 === 0
                               ? 'border-indigo-200 dark:border-indigo-700/60 text-indigo-600 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 hover:border-indigo-400 dark:hover:border-indigo-500'
                               : 'border-purple-200 dark:border-purple-700/60 text-purple-600 dark:text-purple-300 bg-purple-50 dark:bg-purple-950/30 hover:bg-purple-100 dark:hover:bg-purple-900/40 hover:border-purple-400 dark:hover:border-purple-500' }}"
                >
                    <i class="fa-solid {{ $loop->index % 2 === 0 ? 'fa-graduation-cap' : 'fa-calendar-xmark' }} text-[10px]"></i>
                    {{ $suggestion }}
                </button>
            @endforeach
        </div>

        {{-- Zone de messages --}}
        <div
            x-ref="messageArea"
            class="overflow-y-auto px-5 py-5 space-y-5 scroll-smooth bg-gray-50 dark:bg-gray-950/40"
            style="height: 450px;"
        >
            {{-- Chargement du message de bienvenue --}}
            <div x-show="welcomeLoading" x-cloak class="flex justify-start">
                <div class="flex items-end gap-2.5">
                    <div class="flex-shrink-0 w-7 h-7 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white shadow-sm">
                        <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>
                    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700/60 border-l-4 border-l-indigo-500 rounded-2xl rounded-bl-sm px-4 py-3 shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <div class="flex gap-1">
                                <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                                <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                                <span class="w-1.5 h-1.5 bg-purple-500 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                            </div>
                            <span class="text-xs text-gray-400 dark:text-slate-500 font-medium italic">L'Assistant E-UPF prépare votre résumé...</span>
                        </div>
                    </div>
                </div>
            </div>

            <template x-for="(msg, idx) in messages" :key="idx">
                <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <template x-if="msg.role === 'assistant'">
                        <div class="flex items-start gap-2.5 max-w-[85%] sm:max-w-[72%]">
                            <div class="flex-shrink-0 w-7 h-7 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white shadow-sm shadow-indigo-500/30 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700/60 border-l-4 border-l-indigo-500 rounded-2xl rounded-bl-sm px-4 py-3 shadow-sm">
                                    <p class="text-sm text-gray-800 dark:text-slate-200 whitespace-pre-line leading-relaxed" x-text="msg.content"></p>
                                </div>
                                <template x-if="!msg.isWelcome">
                                    <div class="flex items-center gap-1.5 mt-1.5 ml-1">
                                        <div x-show="msg.feedback === null" class="flex gap-1">
                                            <button
                                                x-on:click="msg.feedback = 'up'; saveFeedback(msg.id, 'up')"
                                                class="p-1 rounded-md text-gray-400 dark:text-slate-600 hover:text-green-500 hover:bg-green-50 dark:hover:bg-green-950/30 transition-all duration-150 text-sm leading-none"
                                                title="Réponse utile"
                                            >👍</button>
                                            <button
                                                x-on:click="msg.feedback = 'down'; saveFeedback(msg.id, 'down')"
                                                class="p-1 rounded-md text-gray-400 dark:text-slate-600 hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 transition-all duration-150 text-sm leading-none"
                                                title="Réponse non utile"
                                            >👎</button>
                                        </div>
                                        <span
                                            x-show="msg.feedback !== null"
                                            x-cloak
                                            x-text="msg.feedback === 'up' ? '👍 Merci pour votre retour !' : '👎 Merci, nous en tenons compte.'"
                                            class="text-[11px] text-gray-400 dark:text-slate-500 font-medium"
                                        ></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                    <template x-if="msg.role === 'user'">
                        <div class="max-w-[85%] sm:max-w-[72%]">
                            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl rounded-br-sm px-4 py-3 shadow-md shadow-indigo-600/25">
                                <p class="text-sm text-white whitespace-pre-line leading-relaxed" x-text="msg.content"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            {{-- Chargement --}}
            <div x-show="loading" x-cloak class="flex justify-start">
                <div class="flex items-end gap-2.5">
                    <div class="flex-shrink-0 w-7 h-7 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white shadow-sm">
                        <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>
                    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700/60 border-l-4 border-l-indigo-500 rounded-2xl rounded-bl-sm px-4 py-3 shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <div class="flex gap-1">
                                <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                                <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                                <span class="w-1.5 h-1.5 bg-purple-500 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                            </div>
                            <span class="text-xs text-gray-400 dark:text-slate-500 font-medium italic">L'assistant analyse le dossier...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bannière limite --}}
        <div x-show="limit" x-cloak class="px-5 pt-3">
            <div class="flex items-center gap-2 px-4 py-2.5 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/50 rounded-xl text-amber-700 dark:text-amber-400 text-xs font-semibold">
                <i class="fa-solid fa-triangle-exclamation"></i>
                Vous avez atteint la limite de 20 questions par jour. Revenez demain.
            </div>
        </div>

        {{-- Zone de saisie --}}
        <div class="px-5 py-4 border-t border-gray-100 dark:border-slate-800 bg-white dark:bg-gray-900">
            <form x-on:submit.prevent="sendMessage()" class="flex items-end gap-3">
                <div class="flex-1 relative">
                    <textarea
                        x-model="input"
                        x-on:keydown.enter.prevent="if(!$event.shiftKey) sendMessage()"
                        :disabled="loading || limit"
                        placeholder="Posez votre question sur la scolarité de votre enfant..."
                        rows="1"
                        class="w-full resize-none rounded-xl border text-sm font-medium transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed
                               bg-gray-50 dark:bg-slate-800/80
                               border-gray-200 dark:border-slate-700
                               text-gray-900 dark:text-slate-100
                               placeholder-gray-400 dark:placeholder-slate-500
                               px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-indigo-400/40 focus:border-indigo-500 dark:focus:border-indigo-500"
                        style="max-height: 120px; overflow-y: auto;"
                        x-on:input="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 120) + 'px'"
                    ></textarea>
                </div>
                <button
                    type="submit"
                    :disabled="!input.trim() || loading || limit"
                    class="flex-shrink-0 w-11 h-11 rounded-xl flex items-center justify-center text-white transition-all duration-150 shadow-md disabled:opacity-40 disabled:cursor-not-allowed disabled:shadow-none
                           bg-gradient-to-br from-indigo-500 to-purple-600
                           hover:from-indigo-400 hover:to-purple-500
                           shadow-indigo-500/30 hover:shadow-indigo-500/50"
                >
                    <svg x-show="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                    </svg>
                    <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </button>
            </form>
            <p class="mt-2 text-center text-[10px] text-gray-400 dark:text-slate-600 font-medium">
                Entrée pour envoyer · Shift+Entrée pour nouvelle ligne · 20 questions/jour
            </p>
        </div>

    </div>{{-- /chatbot --}}

</div>
@endsection

@push('scripts')
@if(isset($student) && $student && count($notesLabels) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@3"></script>
<script>
(function () {
    'use strict';

    const labels  = @json($notesLabels);
    const data    = @json($notesData);
    const moyenne = @json($notesMoyenne);

    // Couleur par valeur
    const barColors = data.map(function (v) {
        if (v >= 14) return 'rgba(74, 222, 128, 0.85)';   // green-400
        if (v >= 10) return 'rgba(99, 102, 241, 0.85)';   // indigo-500
        return 'rgba(248, 113, 113, 0.85)';               // red-400
    });
    const borderColors = data.map(function (v) {
        if (v >= 14) return 'rgb(34, 197, 94)';
        if (v >= 10) return 'rgb(79, 70, 229)';
        return 'rgb(239, 68, 68)';
    });

    const isDark = document.documentElement.classList.contains('dark');
    const textColor   = isDark ? '#94a3b8' : '#6b7280';
    const gridColor   = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
    const tooltipBg   = isDark ? '#1e293b' : '#ffffff';
    const tooltipText = isDark ? '#e2e8f0' : '#111827';

    const annotations = {
        seuil: {
            type: 'line',
            scaleID: 'x',
            value: 10,
            borderColor: 'rgba(248, 113, 113, 0.8)',
            borderWidth: 2,
            borderDash: [6, 4],
            label: {
                display: true,
                content: 'Seuil (10)',
                position: 'end',
                color: 'rgba(248, 113, 113, 0.9)',
                font: { size: 10, weight: 'bold', family: 'Outfit, sans-serif' },
                backgroundColor: 'rgba(248, 113, 113, 0.1)',
                borderRadius: 4,
                padding: { x: 6, y: 3 },
            }
        }
    };

    if (moyenne !== null) {
        annotations.moyenne = {
            type: 'line',
            scaleID: 'x',
            value: moyenne,
            borderColor: 'rgba(167, 139, 250, 0.9)',
            borderWidth: 2,
            label: {
                display: true,
                content: 'Moy. ' + moyenne,
                position: 'start',
                color: 'rgba(167, 139, 250, 1)',
                font: { size: 10, weight: 'bold', family: 'Outfit, sans-serif' },
                backgroundColor: 'rgba(167, 139, 250, 0.12)',
                borderRadius: 4,
                padding: { x: 6, y: 3 },
            }
        };
    }

    new Chart(document.getElementById('notesChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Note /20',
                data: data,
                backgroundColor: barColors,
                borderColor: borderColors,
                borderWidth: 1.5,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: tooltipBg,
                    titleColor: textColor,
                    bodyColor: tooltipText,
                    borderColor: isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.08)',
                    borderWidth: 1,
                    padding: 10,
                    cornerRadius: 10,
                    callbacks: {
                        label: function (ctx) {
                            return ' ' + ctx.parsed.x + ' / 20';
                        }
                    }
                },
                annotation: { annotations: annotations }
            },
            scales: {
                x: {
                    min: 0,
                    max: 20,
                    grid: { color: gridColor },
                    ticks: {
                        color: textColor,
                        font: { size: 11, family: 'Outfit, sans-serif' },
                        callback: function (v) { return v + ''; }
                    },
                    border: { color: gridColor }
                },
                y: {
                    grid: { display: false },
                    ticks: {
                        color: textColor,
                        font: { size: 11, family: 'Outfit, sans-serif' }
                    },
                    border: { color: gridColor }
                }
            }
        }
    });
}());
</script>
@endif
@endpush
