@extends('layouts.dashboard')

@section('title', 'Absences & Suivi - E-UPF')

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

        {{-- Absences ce mois --}}
        <div class="grid grid-cols-1 max-w-xs gap-4 mb-4">
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
        </div>

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

</div>
@endsection

