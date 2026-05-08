@extends('layouts.dashboard')

@section('title', 'Réservation de Salles')
@section('page-title', 'Réservation de Salles')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Reservation Form -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden sticky top-8">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <h3 class="text-sm font-black text-gray-800 dark:text-white uppercase tracking-widest">Nouvelle Réservation</h3>
            </div>
            <form action="{{ route('professeur.reservations.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Salle disponible</label>
                    <select name="salle_id" required class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500">
                        @foreach($salles as $salle)
                            <option value="{{ $salle->id }}">{{ $salle->nom }} (Cap : {{ $salle->capacite }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</label>
                    <input type="date" name="date" required min="{{ date('Y-m-d') }}"
                           class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Heure Début</label>
                        <input type="time" name="heure_debut" required
                               class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Heure Fin</label>
                        <input type="time" name="heure_fin" required
                               class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Motif de réservation</label>
                    <textarea name="motif" rows="3" required minlength="5" placeholder="Ex: Rattrapage, examen, séminaire..."
                              class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500"></textarea>
                </div>

                <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-indigo-100 dark:shadow-none">
                    Confirmer la réservation
                </button>
            </form>
        </div>
    </div>

    <!-- Reservations History -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-black text-gray-800 dark:text-white uppercase tracking-wider">Mes Réservations</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50">
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Salle</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date & Heures</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Motif</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($reservations as $res)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $res->salle->nom }}</span>
                                <p class="text-[10px] text-gray-400 uppercase font-bold">Capacité: {{ $res->salle->capacite }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($res->date)->format('d/m/Y') }}</span>
                                <p class="text-[10px] text-indigo-500 font-bold uppercase">{{ substr($res->heure_debut, 0, 5) }} - {{ substr($res->heure_fin, 0, 5) }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400 italic line-clamp-1">{{ $res->motif }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('professeur.reservations.destroy', $res) }}" method="POST" onsubmit="return confirm('Annuler cette réservation ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-black text-[10px] uppercase tracking-widest">
                                        Annuler
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 font-bold italic">
                                Aucune réservation effectuée.
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
