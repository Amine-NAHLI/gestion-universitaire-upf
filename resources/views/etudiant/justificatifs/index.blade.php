@extends('layouts.dashboard')

@section('title', __('Justificatifs d\'Absence'))
@section('page-title', __('Justificatifs d\'Absence'))

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Upload Section -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden sticky top-8">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <h3 class="text-sm font-black text-gray-800 dark:text-white uppercase tracking-widest">{{ __('Déposer un justificatif') }}</h3>
            </div>
            <div class="p-6">
                @if($absences_non_justifiees->count() > 0)
                <form action="{{ route('etudiant.justificatifs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Absence concernée') }}</label>
                        <select name="absence_id" required class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500">
                            @foreach($absences_non_justifiees as $abs)
                                <option value="{{ $abs->id }}">
                                    {{ $abs->seance->module->nom }} - {{ $abs->seance->date->format('d/m') }} ({{ $abs->seance->heure_debut->format('H:i') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Motif de l\'absence') }}</label>
                        <textarea name="motif" rows="4" required minlength="10"
                                  placeholder="{{ __('Expliquez brièvement la raison de votre absence...') }}"
                                  class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Document (PDF, JPG, PNG)') }}</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-700 border-dashed rounded-xl hover:border-indigo-500 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                    <label class="relative cursor-pointer bg-transparent rounded-md font-bold text-indigo-600 hover:text-indigo-500">
                                        <span>{{ __('Charger un fichier') }}</span>
                                        <input name="fichier" type="file" required class="sr-only">
                                    </label>
                                </div>
                                <p class="text-[10px] text-gray-400">Max 5 Mo</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-indigo-200 dark:shadow-none">
                        {{ __('Déposer le justificatif') }}
                    </button>
                </form>
                @else
                <div class="text-center py-8">
                    <div class="bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-gray-500">{{ __('Toutes vos absences sont déjà justifiées ou en cours de traitement.') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- History Section -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-black text-gray-800 dark:text-white uppercase tracking-wider">{{ __('Mes Justificatifs') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50">
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Absence / Module') }}</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Date Dépôt') }}</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Statut') }}</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($justificatifs as $justif)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $justif->absence->seance->module->nom }}</span>
                                <p class="text-[10px] text-gray-400">{{ __('Séance du') }} {{ $justif->absence->seance->date->format('d/m/Y') }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-medium text-gray-500">{{ $justif->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statuts = [
                                        'en_attente' => ['bg-amber-100', 'text-amber-600', __('En attente')],
                                        'validee' => ['bg-emerald-100', 'text-emerald-600', __('Accepté')],
                                        'refusee' => ['bg-red-100', 'text-red-600', __('Refusé')],
                                    ];
                                    $s = $statuts[$justif->statut] ?? ['bg-gray-100', 'text-gray-600', $justif->statut];
                                @endphp
                                <span class="px-3 py-1 rounded-full {{ $s[0] }} dark:bg-opacity-20 text-[10px] font-black uppercase tracking-widest">
                                    {{ $s[2] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ asset('storage/' . $justif->fichier) }}" target="_blank" class="text-indigo-600 hover:text-indigo-700 font-bold text-xs uppercase">
                                    {{ __('Voir fichier') }}
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 font-bold italic">
                                {{ __('Aucun justificatif déposé pour le moment.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
