@extends('layouts.dashboard')

@section('title', 'Gestion — ' . $module->nom)
@section('page-title', 'Classroom : ' . $module->nom)

@section('content')
<div x-data="{ activeTab: 'annonces' }" class="space-y-6">
    
    <!-- Navigation Tabs -->
    <div class="flex gap-4 border-b border-gray-100 dark:border-gray-800">
        <button @click="activeTab = 'annonces'" 
                :class="activeTab === 'annonces' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-400'"
                class="px-6 py-4 border-b-4 text-xs font-black uppercase tracking-widest transition-all">
            Annonces
        </button>
        <button @click="activeTab = 'supports'" 
                :class="activeTab === 'supports' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-400'"
                class="px-6 py-4 border-b-4 text-xs font-black uppercase tracking-widest transition-all">
            Supports de cours
        </button>
    </div>

    <!-- Annonces Tab -->
    <div x-show="activeTab === 'annonces'" class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-transition>
        <!-- Form -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden sticky top-8">
                <div class="p-6 border-b border-gray-50 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                    <h4 class="text-[10px] font-black text-gray-800 dark:text-white uppercase tracking-widest">Publier une annonce</h4>
                </div>
                <form action="{{ route('professeur.classroom.annonce', $module) }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Titre</label>
                        <input type="text" name="titre" required class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Contenu</label>
                        <textarea name="contenu" rows="5" required class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500"></textarea>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-xl">
                        <input type="checkbox" name="epinglee" value="1" class="rounded text-indigo-600 focus:ring-indigo-500">
                        <span class="text-xs font-bold text-gray-600 dark:text-gray-400">Épingler en haut du flux</span>
                    </div>
                    <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-indigo-100 dark:shadow-none">
                        Publier l'annonce
                    </button>
                </form>
            </div>
        </div>
        <!-- Feed -->
        <div class="lg:col-span-2 space-y-6">
            @foreach($annonces as $annonce)
            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden {{ $annonce->epinglee ? 'ring-2 ring-indigo-500' : '' }}">
                <div class="p-8">
                    <div class="flex justify-between items-start mb-4">
                        <h5 class="text-lg font-black text-gray-800 dark:text-white">{{ $annonce->titre }}</h5>
                        @if($annonce->epinglee)
                            <span class="bg-indigo-100 text-indigo-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Épinglée</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mb-6 whitespace-pre-line">{{ $annonce->contenu }}</p>
                    <div class="flex items-center justify-between pt-6 border-t border-gray-50 dark:border-gray-700">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Posté {{ $annonce->created_at->diffForHumans() }}</span>
                        <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">{{ $annonce->commentaires->count() }} commentaire(s)</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Supports Tab -->
    <div x-show="activeTab === 'supports'" class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-transition>
        <!-- Form -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden sticky top-8">
                <div class="p-6 border-b border-gray-50 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                    <h4 class="text-[10px] font-black text-gray-800 dark:text-white uppercase tracking-widest">Déposer un support</h4>
                </div>
                <form action="{{ route('professeur.classroom.support', $module) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Titre du document</label>
                        <input type="text" name="titre" required class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Description (optionnel)</label>
                        <input type="text" name="description" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Fichier (PDF, PPT, DOC, ZIP...)</label>
                        <input type="file" name="fichier" required class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
                    </div>
                    <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-emerald-100 dark:shadow-none">
                        Mettre en ligne
                    </button>
                </form>
            </div>
        </div>
        <!-- List -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-900/50">
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Document</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Type</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Taille</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($supports as $sup)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-gray-800 dark:text-white block">{{ $sup->titre }}</span>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase">Ajouté le {{ $sup->created_at->format('d/m/Y') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-[9px] font-black uppercase text-gray-500">{{ $sup->type_fichier }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-[10px] text-gray-400 font-bold">{{ round($sup->taille / 1024, 2) }} KB</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ asset('storage/' . $sup->fichier) }}" target="_blank" class="text-indigo-600 hover:text-indigo-700 font-black text-[10px] uppercase tracking-widest">Consulter</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
