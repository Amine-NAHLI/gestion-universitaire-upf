@extends('layouts.dashboard')

@section('title', __('Espace Classroom'))
@section('page-title', __('Mes Modules & Classroom'))

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($modules as $module)
        <div class="group bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
            <div class="h-3 bg-indigo-600"></div>
            <div class="p-8">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">{{ $module->code }}</span>
                    <span class="bg-gray-100 dark:bg-gray-700 text-gray-500 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase">{{ $module->type }}</span>
                </div>

                <h3 class="text-xl font-black text-gray-800 dark:text-white mb-2 leading-tight min-h-[3rem] line-clamp-2">
                    {{ $module->nom }}
                </h3>

                <div class="flex items-center gap-3 mb-6">
                    @php $prof = $module->professeurs->first(); @endphp
                    @if($prof)
                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 font-black text-xs">
                        {{ substr($prof->user->prenom, 0, 1) }}{{ substr($prof->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('Professeur') }}</p>
                        <p class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $prof->user->full_name }}</p>
                    </div>
                    @else
                    <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 font-black text-xs">?</div>
                    <div>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('Professeur') }}</p>
                        <p class="text-xs font-bold text-gray-400 italic">{{ __('Non assigné') }}</p>
                    </div>
                    @endif
                </div>

                @if($module->annonces->count() > 0)
                <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl mb-6">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Dernière annonce') }}</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 italic line-clamp-2">"{{ $module->annonces->first()->titre }}"</p>
                </div>
                @endif

                <a href="{{ route('etudiant.classroom.show', $module) }}" class="flex items-center justify-center w-full py-4 bg-indigo-600 group-hover:bg-indigo-700 text-white rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-indigo-100 dark:shadow-none">
                    {{ __('Accéder au cours') }}
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center">
            <p class="text-gray-500 font-bold italic">{{ __('Aucun module assigné à votre niveau pour le moment.') }}</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
