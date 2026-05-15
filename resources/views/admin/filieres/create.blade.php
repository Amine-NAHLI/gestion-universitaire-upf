@extends('layouts.dashboard')

@section('title', __('Gestion des Filières'))
@section('page-title', isset($filiere) ? __('Modifier la salle') : __('Créer une filière'))

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.filieres.index') }}" class="flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('Retour à la liste') }}
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
        <div class="p-8 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
            <h2 class="text-2xl font-extrabold text-gray-800 dark:text-white">
                {{ isset($filiere) ? __('Modifier') . ' : ' . $filiere->nom : __('Créer une filière') }}
            </h2>
        </div>

        <form action="{{ isset($filiere) ? route('admin.filieres.update', $filiere) : route('admin.filieres.store') }}"
              method="POST" class="p-8 space-y-6">
            @csrf
            @if(isset($filiere)) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="nom" class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('Nom de la filière') }}</label>
                    <input type="text" name="nom" id="nom" value="{{ old('nom', $filiere->nom ?? '') }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 @error('nom') border-red-500 @enderror">
                    @error('nom') <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="code" class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('Code (ex: GI, TM...)') }}</label>
                    <input type="text" name="code" id="code" value="{{ old('code', $filiere->code ?? '') }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 uppercase @error('code') border-red-500 @enderror">
                    @error('code') <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label for="description" class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500">{{ old('description', $filiere->description ?? '') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('admin.filieres.index') }}" class="btn-secondary">{{ __('Annuler') }}</a>
                <button type="submit" class="btn-primary px-8">
                    {{ isset($filiere) ? __('Mettre à jour') : __('Enregistrer') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
