@extends('layouts.dashboard')

@section('title', __('Saisie des Notes'))
@section('page-title', __('Saisie des Notes'))

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.notes.index', ['groupe_id' => $etudiant->groupe_id, 'module_id' => $module->id]) }}" class="flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ __('Retour au groupe') }}
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden" x-data="{
        cc1: {{ $note->cc1 ?? 0 }},
        cc2: {{ $note->cc2 ?? 0 }},
        examen: {{ $note->examen ?? 0 }},
        get noteFinale() {
            if (this.cc1 === '' || this.cc2 === '' || this.examen === '') return '--';
            let res = ((parseFloat(this.cc1) + parseFloat(this.cc2)) / 2) * 0.4 + parseFloat(this.examen) * 0.6;
            return res.toFixed(2);
        }
    }">
        <div class="p-8 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-2xl font-bold">
                    {{ substr($etudiant->user->prenom, 0, 1) }}{{ substr($etudiant->user->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-gray-800 dark:text-white">{{ $etudiant->user->full_name }}</h2>
                    <p class="text-sm font-bold text-indigo-500 uppercase tracking-wider">{{ $module->nom }} ({{ $module->code }})</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.notes.update', [$etudiant, $module]) }}" method="POST" class="p-8 space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-3">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">CC1 (20%)</label>
                    <div class="relative">
                        <input type="number" step="0.01" min="0" max="20" name="cc1" x-model="cc1"
                               class="w-full px-4 py-4 rounded-2xl border-2 border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white text-xl font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-center">
                        <span class="absolute top-1/2 -translate-y-1/2 right-4 text-gray-400 font-bold text-xs">/20</span>
                    </div>
                    @error('cc1') <p class="text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-3">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">CC2 (20%)</label>
                    <div class="relative">
                        <input type="number" step="0.01" min="0" max="20" name="cc2" x-model="cc2"
                               class="w-full px-4 py-4 rounded-2xl border-2 border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white text-xl font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-center">
                        <span class="absolute top-1/2 -translate-y-1/2 right-4 text-gray-400 font-bold text-xs">/20</span>
                    </div>
                    @error('cc2') <p class="text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-3">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('Examen') }} (60%)</label>
                    <div class="relative">
                        <input type="number" step="0.01" min="0" max="20" name="examen" x-model="examen"
                               class="w-full px-4 py-4 rounded-2xl border-2 border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white text-xl font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-center">
                        <span class="absolute top-1/2 -translate-y-1/2 right-4 text-gray-400 font-bold text-xs">/20</span>
                    </div>
                    @error('examen') <p class="text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="p-6 rounded-3xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">{{ __('Note Finale Prévisionnelle') }}</h4>
                    <p class="text-xs text-gray-400 mt-1">Formule : (Moyenne CC * 0.4) + (Examen * 0.6)</p>
                </div>
                <div class="text-right">
                    <span class="text-4xl font-black" :class="noteFinale >= 10 ? 'text-green-500' : 'text-red-500'" x-text="noteFinale"></span>
                    <span class="text-sm font-bold text-gray-400 ml-1">/20</span>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('admin.notes.index', ['groupe_id' => $etudiant->groupe_id, 'module_id' => $module->id]) }}" class="btn-secondary">{{ __('Annuler') }}</a>
                <button type="submit" class="btn-primary px-10 py-4">
                    {{ __('Enregistrer les notes') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
