@extends('layouts.dashboard')

@section('title', __('Emploi du Temps'))
@section('page-title', __('Emploi du Temps Global'))

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
<style>
    :root {
        --fc-border-color: rgba(229, 231, 235, 0.1);
        --fc-daygrid-event-dot-width: 8px;
    }
    .dark .fc {
        --fc-page-bg-color: #1f2937;
        --fc-neutral-bg-color: #374151;
        --fc-list-event-hover-bg-color: #374151;
        --fc-border-color: #374151;
        color: #f3f4f6;
    }
    .fc-event { cursor: pointer; border-radius: 6px !important; padding: 2px 4px !important; border: none !important; }
    .fc-v-event { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
    .fc-theme-standard td, .fc-theme-standard th { border-color: rgba(229, 231, 235, 0.1) !important; }
</style>
@endpush

@section('content')
<div id="edt-container" x-data="{ modalOpen: false, date: '', heureDebut: '', heureFin: '' }">

    <!-- Legend -->
    <div class="flex flex-wrap items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm mb-6">
        <span class="text-xs font-bold text-gray-500 uppercase mr-2">{{ __('Légende :') }}</span>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-indigo-500"></span>
            <span class="text-xs font-medium dark:text-gray-300">{{ __('Cours') }}</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
            <span class="text-xs font-medium dark:text-gray-300">TD</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
            <span class="text-xs font-medium dark:text-gray-300">TP</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-red-500"></span>
            <span class="text-xs font-medium dark:text-gray-300">{{ __('Examen') }}</span>
        </div>
    </div>

    <!-- Calendar Card -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden p-6">
        <div id="calendar"></div>
    </div>

    <!-- Bouton ouvrir modal -->
    <button @click="modalOpen = true" class="fixed bottom-6 right-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full p-4 shadow-xl z-50">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
    </button>

    <!-- Overlay + Modal -->
    <div x-show="modalOpen" x-transition class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg p-6" @click.stop>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Nouvelle Séance') }}</h3>
                <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            <form @submit.prevent="submitSeance($event)" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Module') }}</label>
                        <select name="module_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <option value="">{{ __('-- Choisir --') }}</option>
                            @foreach($modules as $module)
                                <option value="{{ $module->id }}">{{ $module->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Professeur') }}</label>
                        <select name="professeur_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <option value="">{{ __('-- Choisir --') }}</option>
                            @foreach($professeurs as $prof)
                                <option value="{{ $prof->id }}">{{ $prof->user->prenom }} {{ $prof->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Groupe') }}</label>
                        <select name="groupe_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <option value="">{{ __('-- Choisir --') }}</option>
                            @foreach($groupes as $groupe)
                                <option value="{{ $groupe->id }}">{{ $groupe->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Salle') }}</label>
                        <select name="salle_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <option value="">{{ __('-- Optionnel --') }}</option>
                            @foreach($salles as $salle)
                                <option value="{{ $salle->id }}">{{ $salle->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Date') }}</label>
                        <input type="date" name="date" x-model="date" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Type') }}</label>
                        <select name="type" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <option value="cours">{{ __('Cours') }}</option>
                            <option value="td">TD</option>
                            <option value="tp">TP</option>
                            <option value="examen">{{ __('Examen') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Heure début') }}</label>
                        <input type="time" name="heure_debut" x-model="heureDebut" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Heure fin') }}</label>
                        <input type="time" name="heure_fin" x-model="heureFin" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                </div>

                <div class="flex gap-3 justify-end mt-6">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white rounded-lg">
                        {{ __('Annuler') }}
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium">
                        {{ __('Créer la séance') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: '{{ app()->getLocale() }}',
        firstDay: 1,
        slotMinTime: '08:00:00',
        slotMaxTime: '20:00:00',
        allDaySlot: false,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: '{{ route("admin.edt.data") }}',
        editable: true,
        selectable: true,

        select: function(info) {
            const container = document.getElementById('edt-container');
            const data = container._x_dataStack ? container._x_dataStack[0] : container.__x.$data;

            data.modalOpen = true;
            data.date = info.startStr.split('T')[0];
            data.heureDebut = info.startStr.split('T')[1]?.substring(0,5) || '08:00';
            data.heureFin = info.endStr?.split('T')[1]?.substring(0,5) || '10:00';
        },

        eventClick: function(info) {
            const props = info.event.extendedProps;
            Swal.fire({
                title: `<div class='text-left'><p class='text-lg font-black'>${info.event.title}</p></div>`,
                html: `
                    <div class="text-left space-y-2 mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-700">
                        <p class="text-sm"><span class="text-gray-400 font-bold uppercase text-[10px]">Type:</span> <span class="font-bold text-indigo-600">${props.type.toUpperCase()}</span></p>
                        <p class="text-sm"><span class="text-gray-400 font-bold uppercase text-[10px]">Salle:</span> <span class="font-bold">${props.salle || 'N/A'}</span></p>
                        <p class="text-sm"><span class="text-gray-400 font-bold uppercase text-[10px]">Professeur:</span> <span class="font-bold">${props.professeur}</span></p>
                        <p class="text-sm"><span class="text-gray-400 font-bold uppercase text-[10px]">Statut:</span> <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[10px] font-black uppercase">${props.statut}</span></p>
                    </div>
                `,
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: '{{ __('Supprimer') }}',
                confirmButtonColor: '#ef4444',
                cancelButtonText: '{{ __('Fermer') }}',
                customClass: { popup: 'rounded-3xl dark:bg-gray-800 dark:text-white' }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/edt/${info.event.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    }).then(res => res.json()).then(data => {
                        if(data.success) {
                            info.event.remove();
                            if(typeof notyf !== 'undefined') notyf.success('{{ __('Séances') }}');
                        }
                    });
                }
            });
        },

        eventDrop: function(info) {
            updateSeance(info.event);
        },
        eventResize: function(info) {
            updateSeance(info.event);
        }
    });

    calendar.render();

    function updateSeance(event) {
        fetch(`/admin/edt/${event.id}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                date: event.startStr.split('T')[0],
                heure_debut: event.startStr.split('T')[1].substring(0,5),
                heure_fin: event.endStr.split('T')[1].substring(0,5),
            })
        }).then(res => res.json()).then(data => {
            if(data.success && typeof notyf !== 'undefined') notyf.success('Planning mis à jour');
        });
    }

    window.submitSeance = function(e) {
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());

        fetch('{{ route("admin.edt.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                calendar.refetchEvents();
                document.querySelector('[x-data]').__x.$data.modalOpen = false;
                e.target.reset();
                if(typeof notyf !== 'undefined') notyf.success('{{ __('Créer la séance') }}');
            }
        });
    }
});
</script>
@endpush
