@extends('layouts.dashboard')

@section('title', 'Mon Emploi du Temps')
@section('page-title', 'Mon Emploi du Temps')

@section('content')
<div class="space-y-8">
    <!-- Upcoming Sessions -->
    @if($prochaines->count() > 0)
    <div class="bg-indigo-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden">
        <div class="relative z-10">
            <h3 class="text-xl font-black uppercase tracking-wider mb-6 flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Prochaines séances
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                @foreach($prochaines as $p)
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-[10px] font-black uppercase bg-white/20 px-2 py-0.5 rounded-full">{{ $p->type }}</span>
                        <span class="text-[10px] font-bold text-indigo-200">{{ $p->date->format('d/m') }}</span>
                    </div>
                    <h4 class="font-bold text-sm mb-1 truncate">{{ $p->module->nom }}</h4>
                    <p class="text-xs text-indigo-100">{{ $p->heure_debut->format('H:i') }} - {{ $p->salle?->nom ?? 'N/A' }}</p>
                </div>
                @endforeach
            </div>
        </div>
        <!-- Decorative Circle -->
        <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
    </div>
    @endif

    <!-- Full Schedule -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-black text-gray-800 dark:text-white uppercase tracking-wider">Planning Complet</h3>
        </div>

        @forelse($seances as $date => $daySeances)
        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h4 class="text-sm font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">
                    {{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
                </h4>
                <span class="text-xs font-bold text-gray-400">{{ count($daySeances) }} séance(s)</span>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($daySeances as $s)
                <div class="p-6 flex flex-col md:flex-row md:items-center gap-4 md:gap-8 hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                    <div class="w-24 shrink-0">
                        <span class="text-lg font-black text-gray-800 dark:text-white">{{ $s->heure_debut->format('H:i') }}</span>
                        <p class="text-[10px] font-bold text-gray-400">{{ $s->heure_fin->format('H:i') }}</p>
                    </div>
                    
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1">
                            <h5 class="text-base font-black text-gray-800 dark:text-white">{{ $s->module->nom }}</h5>
                            @php
                                $colors = ['cours' => 'indigo', 'td' => 'emerald', 'tp' => 'amber', 'examen' => 'red'];
                                $color = $colors[$s->type] ?? 'gray';
                            @endphp
                            <span class="px-3 py-1 rounded-full bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 text-{{ $color }}-600 dark:text-{{ $color }}-400 text-[9px] font-black uppercase tracking-widest">
                                {{ $s->type }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $s->professeur->user->full_name }}
                        </p>
                    </div>

                    <div class="flex items-center gap-6">
                        <div class="text-right">
                            <span class="text-xs font-black text-gray-400 uppercase tracking-widest block mb-1">Salle</span>
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $s->salle?->nom ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-12 text-center border border-gray-100 dark:border-gray-700 shadow-xl">
            <p class="text-gray-500 dark:text-gray-400 font-bold italic">Aucune séance planifiée dans votre emploi du temps.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
