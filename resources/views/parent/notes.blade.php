@extends('layouts.dashboard')

@section('title', 'Notes & Moyennes - E-UPF')

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

        {{-- Moyenne générale --}}
        <div class="grid grid-cols-1 max-w-xs gap-4 mb-4">

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
        </div>



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
