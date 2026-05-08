@extends('layouts.dashboard')

@section('title', 'Saisie des Notes')
@section('page-title', 'Saisie des Notes')

@section('content')
<div class="space-y-8">
    @foreach($modules as $module)
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            <h3 class="text-lg font-black text-gray-800 dark:text-white uppercase tracking-wider">{{ $module->nom }}</h3>
            <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mt-1">{{ $module->code }}</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @forelse($module->groupes as $groupe)
                <a href="{{ route('professeur.notes.saisir', [$module, $groupe]) }}" class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900/30 rounded-2xl hover:ring-2 hover:ring-indigo-500 transition-all group">
                    <div>
                        <span class="text-xs font-black text-gray-400 uppercase tracking-widest block mb-1">Groupe</span>
                        <span class="text-base font-black text-gray-800 dark:text-white">{{ $groupe->nom }}</span>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-2 rounded-xl shadow-sm text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                </a>
                @empty
                <p class="text-xs text-gray-400 italic">Aucun groupe assigné à ce module.</p>
                @endforelse
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
