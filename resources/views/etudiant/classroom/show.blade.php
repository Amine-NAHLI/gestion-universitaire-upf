@extends('layouts.dashboard')

@section('title', $module->nom)
@section('page-title', $module->nom)

@section('content')
<div x-data="{ activeTab: 'annonces' }" class="space-y-6">
    
    <!-- Navigation Tabs -->
    <div class="flex gap-4 border-b border-gray-100 dark:border-gray-800">
        <button @click="activeTab = 'annonces'" 
                :class="activeTab === 'annonces' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-400'"
                class="px-6 py-4 border-b-4 text-xs font-black uppercase tracking-widest transition-all">
            Flux d'annonces
        </button>
        <button @click="activeTab = 'supports'" 
                :class="activeTab === 'supports' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-400'"
                class="px-6 py-4 border-b-4 text-xs font-black uppercase tracking-widest transition-all">
            Supports de cours
        </button>
    </div>

    <!-- Annonces Tab -->
    <div x-show="activeTab === 'annonces'" class="space-y-6" x-transition>
        @forelse($annonces as $annonce)
        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden {{ $annonce->epinglee ? 'ring-2 ring-indigo-500' : '' }}">
            <div class="p-8">
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 font-black">
                            {{ substr($annonce->professeur->user->prenom, 0, 1) }}{{ substr($annonce->professeur->user->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 dark:text-white">{{ $annonce->professeur->user->full_name }}</h4>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">{{ $annonce->created_at->locale('fr')->diffForHumans() }}</p>
                        </div>
                    </div>
                    @if($annonce->epinglee)
                        <span class="bg-indigo-100 text-indigo-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Épinglée 📌</span>
                    @endif
                </div>

                <h3 class="text-lg font-black text-gray-800 dark:text-white mb-4">{{ $annonce->titre }}</h3>
                <div class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed space-y-4">
                    {!! nl2br(e($annonce->contenu)) !!}
                </div>

                <!-- Comments Section -->
                <div class="mt-8 pt-8 border-t border-gray-50 dark:border-gray-700 space-y-6">
                    <h5 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Commentaires ({{ $annonce->commentaires->count() }})</h5>
                    
                    <div class="space-y-4">
                        @foreach($annonce->commentaires as $com)
                        <div class="flex gap-3">
                            <div class="w-6 h-6 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-[10px] font-bold text-gray-500">
                                {{ substr($com->user->prenom, 0, 1) }}{{ substr($com->user->name, 0, 1) }}
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded-2xl flex-1">
                                <div class="flex justify-between mb-1">
                                    <span class="text-[11px] font-black text-gray-800 dark:text-gray-200">{{ $com->user->full_name }}</span>
                                    <span class="text-[9px] text-gray-400">{{ $com->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $com->contenu }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Post Comment Form -->
                    <form action="{{ route('etudiant.classroom.commenter', $annonce) }}" method="POST" class="mt-4 flex gap-3">
                        @csrf
                        <input name="contenu" required minlength="2" maxlength="500" placeholder="Ajouter un commentaire..." 
                               class="flex-1 bg-gray-50 dark:bg-gray-900/50 border-transparent rounded-xl text-xs focus:ring-indigo-500 dark:text-white">
                        <button type="submit" class="bg-indigo-600 text-white p-2 rounded-xl hover:bg-indigo-700 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-20 bg-white dark:bg-gray-800 rounded-3xl border border-dashed border-gray-200 dark:border-gray-700">
            <p class="text-gray-400 italic">Aucune annonce pour ce module.</p>
        </div>
        @endforelse
    </div>

    <!-- Supports Tab -->
    <div x-show="activeTab === 'supports'" class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden" x-transition>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/50">
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Document</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Type / Taille</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Publié le</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($supports as $support)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg text-indigo-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $support->titre }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-[9px] font-black uppercase text-gray-500 mr-2">{{ strtoupper(pathinfo($support->fichier, PATHINFO_EXTENSION)) }}</span>
                            <span class="text-xs text-gray-400">{{ round(Storage::size('public/'.$support->fichier) / 1024, 2) }} KB</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs text-gray-500">{{ $support->created_at->format('d/m/Y') }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ asset('storage/'.$support->fichier) }}" target="_blank" class="text-indigo-600 hover:text-indigo-700 font-black text-xs uppercase tracking-widest">
                                Télécharger
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center text-gray-400 italic">
                            Aucun support de cours disponible pour le moment.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
