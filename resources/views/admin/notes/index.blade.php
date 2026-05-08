@extends('layouts.dashboard')

@section('title', 'Gestion des Notes')
@section('page-title', 'Gestion des Notes')

@section('content')
<div class="space-y-6">
    <!-- Selection Filters -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl">
        <form action="{{ route('admin.notes.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
            <!-- Groupe Selection -->
            <div class="space-y-2">
                <label for="groupe_id" class="text-sm font-bold text-gray-700 dark:text-gray-300">Sélectionner un Groupe</label>
                <select name="groupe_id" id="groupe_id" onchange="this.form.submit()"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Choisir un groupe --</option>
                    @foreach($filieres as $filiere)
                        <optgroup label="{{ $filiere->nom }}">
                            @foreach($filiere->niveaux as $niveau)
                                @foreach($niveau->groupes as $g)
                                    <option value="{{ $g->id }}" {{ $groupe_id == $g->id ? 'selected' : '' }}>
                                        {{ $niveau->nom }} - {{ $g->nom }}
                                    </option>
                                @endforeach
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <!-- Module Selection -->
            <div class="space-y-2">
                <label for="module_id" class="text-sm font-bold text-gray-700 dark:text-gray-300">Sélectionner un Module</label>
                <select name="module_id" id="module_id" {{ $modules->isEmpty() ? 'disabled' : '' }}
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 disabled:opacity-50">
                    <option value="">-- Choisir un module --</option>
                    @foreach($modules as $module)
                        <option value="{{ $module->id }}" {{ $module_id == $module->id ? 'selected' : '' }}>
                            {{ $module->nom }} ({{ $module->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit -->
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1 justify-center py-3">
                    Afficher les notes
                </button>
                @if($groupe_id || $module_id)
                    <a href="{{ route('admin.notes.index') }}" class="btn-secondary py-3" title="Réinitialiser">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Notes Table -->
    @if($groupe_id && $module_id)
        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden overflow-x-auto">
            <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-900/30 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <div>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white">{{ $groupe->nom }}</h3>
                    <p class="text-xs text-gray-500">{{ $notes->count() }} étudiants inscrits</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-[10px] font-bold uppercase tracking-widest">Saisie autorisée</span>
            </div>
            
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/50 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-4">Étudiant</th>
                        <th class="px-6 py-4 text-center">CC1 (20%)</th>
                        <th class="px-6 py-4 text-center">CC2 (20%)</th>
                        <th class="px-6 py-4 text-center">Examen (60%)</th>
                        <th class="px-6 py-4 text-center">Note Finale</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @php
                        // On récupère tous les étudiants du groupe, même s'ils n'ont pas de note encore
                        $etudiants = $groupe->etudiants()->with('user')->get();
                    @endphp
                    @foreach($etudiants as $etudiant)
                        @php
                            $note = $notes->where('etudiant_id', $etudiant->id)->first();
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                        {{ substr($etudiant->user->prenom, 0, 1) }}{{ substr($etudiant->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $etudiant->user->full_name }}</p>
                                        <p class="text-[10px] text-gray-500">ID: {{ $etudiant->num_apogee ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-medium text-gray-600 dark:text-gray-300 italic">
                                {{ $note ? number_format($note->cc1, 2) : '--' }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-medium text-gray-600 dark:text-gray-300 italic">
                                {{ $note ? number_format($note->cc2, 2) : '--' }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-medium text-gray-600 dark:text-gray-300 italic">
                                {{ $note ? number_format($note->examen, 2) : '--' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($note && !is_null($note->note_finale))
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $note->note_finale >= 10 ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                        {{ number_format($note->note_finale, 2) }}
                                    </span>
                                @else
                                    <span class="text-gray-300 font-bold">--</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.notes.edit', [$etudiant->id, $module_id]) }}" class="p-2 rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-indigo-600 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-dashed border-gray-300 dark:border-gray-600 p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Prêt à saisir les notes ?</h3>
            <p class="text-gray-500 dark:text-gray-400 max-w-xs mx-auto">Sélectionnez un groupe et un module dans les filtres ci-dessus pour afficher la liste des étudiants.</p>
        </div>
    @endif
</div>
@endsection
