@extends('layouts.dashboard')

@section('title', 'Mes Modules')
@section('page-title', 'Mes Modules')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($modules as $module)
        <div class="group bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
            <div class="h-3 bg-indigo-600"></div>
            <div class="p-8">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">{{ $module->code }}</span>
                    <div class="flex flex-wrap gap-1 justify-end">
                        @foreach($module->groupes as $groupe)
                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-500 px-2 py-0.5 rounded-full text-[8px] font-bold uppercase">{{ $groupe->nom }}</span>
                        @endforeach
                    </div>
                </div>
                
                <h3 class="text-xl font-black text-gray-800 dark:text-white mb-2 leading-tight min-h-[3rem] line-clamp-2">
                    {{ $module->nom }}
                </h3>
                
                <div class="mb-6">
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Niveau & Filière</p>
                    <p class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $module->niveau->nom }} — {{ $module->niveau->filiere->nom }}</p>
                </div>

                <div class="grid grid-cols-3 gap-2 mb-8 bg-gray-50 dark:bg-gray-900/50 p-3 rounded-2xl">
                    <div class="text-center">
                        <span class="text-[9px] font-black text-gray-400 uppercase">Cours</span>
                        <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $module->heures_cours }}h</p>
                    </div>
                    <div class="text-center border-x border-gray-100 dark:border-gray-700">
                        <span class="text-[9px] font-black text-gray-400 uppercase">TD</span>
                        <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $module->heures_td }}h</p>
                    </div>
                    <div class="text-center">
                        <span class="text-[9px] font-black text-gray-400 uppercase">TP</span>
                        <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $module->heures_tp }}h</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('professeur.notes.index') }}" class="flex items-center justify-center py-3 bg-gray-100 dark:bg-gray-700 hover:bg-indigo-600 hover:text-white text-gray-600 dark:text-gray-300 rounded-xl font-black uppercase tracking-widest text-[9px] transition-all">
                        Saisir Notes
                    </a>
                    <a href="{{ route('professeur.absences.index') }}" class="flex items-center justify-center py-3 bg-gray-100 dark:bg-gray-700 hover:bg-indigo-600 hover:text-white text-gray-600 dark:text-gray-300 rounded-xl font-black uppercase tracking-widest text-[9px] transition-all">
                        Absences
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center">
            <p class="text-gray-500 font-bold italic">Vous n'avez aucun module assigné pour le moment.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
