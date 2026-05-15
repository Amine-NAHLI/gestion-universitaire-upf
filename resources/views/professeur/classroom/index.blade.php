@extends('layouts.dashboard')

@section('title', __('Gestion Classroom'))
@section('page-title', __('Gestion Classroom'))

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($modules as $module)
        <div class="group bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
            <div class="h-3 bg-indigo-600"></div>
            <div class="p-8">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">{{ $module->code }}</span>
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ $module->annonces->count() }} annonces | {{ $module->supportsCours->count() }} supports</span>
                </div>

                <h3 class="text-xl font-black text-gray-800 dark:text-white mb-6 leading-tight min-h-[3rem] line-clamp-2">
                    {{ $module->nom }}
                </h3>

                <a href="{{ route('professeur.classroom.show', $module) }}" class="flex items-center justify-center w-full py-4 bg-indigo-600 group-hover:bg-indigo-700 text-white rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-indigo-100 dark:shadow-none">
                    {{ __('Gérer le cours') }}
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center">
            <p class="text-gray-500 font-bold italic">{{ __('Aucun module assigné pour la gestion Classroom.') }}</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
