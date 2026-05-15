@props(['title', 'value', 'icon' => 'chart-bar', 'color' => 'indigo', 'trend' => null, 'trendUp' => true])

@php
    $iconSvg = match(true) {
        str_starts_with($icon, '<path') || str_starts_with($icon, 'M') => $icon,
        str_contains($icon, 'user') => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />',
        str_contains($icon, 'chalkboard') || str_contains($icon, 'academic') => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />',
        str_contains($icon, 'calendar') => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />',
        str_contains($icon, 'file') || str_contains($icon, 'clipboard') => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />',
        str_contains($icon, 'building') => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />',
        default => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />',
    };

    $colors = [
        'primary' => 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400',
        'indigo' => 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400',
        'emerald' => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400',
        'amber' => 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400',
        'rose' => 'bg-rose-100 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400',
        'red' => 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400',
        'blue' => 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
        'green' => 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400',
    ];
    $colorClass = $colors[$color] ?? $colors['primary'];
@endphp

<div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm hover:shadow-xl transition-all duration-300 group" data-aos="fade-up">
    <div class="flex items-center justify-between mb-6">
        <div class="p-4 {{ $colorClass }} rounded-2xl transition-transform group-hover:scale-110">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $iconSvg !!}
            </svg>
        </div>
        @if($trend !== null)
            <div class="flex items-center space-x-1 px-2 py-1 rounded-full {{ $trendUp ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} text-[10px] font-black">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $trendUp ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}"/></svg>
                <span>{{ $trend }}%</span>
            </div>
        @endif
    </div>
    <div class="space-y-1">
        <h3 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">{{ $value }}</h3>
        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ $title }}</p>
    </div>
</div>
