@extends('layouts.dashboard')

@section('title', __('Mon Emploi du Temps'))
@section('page-title', __('Mon Emploi du Temps'))

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet'>
<style>
    .dark .fc { --fc-page-bg-color: #1f2937; --fc-neutral-bg-color: #374151; --fc-border-color: #374151; color: #f3f4f6; }
    .fc-event { cursor: default; border-radius: 6px !important; padding: 2px 4px !important; border: none !important; }
    .fc-theme-standard td, .fc-theme-standard th { border-color: rgba(229,231,235,0.15) !important; }
</style>
@endpush

@section('content')
<div x-data="{ view: 'calendar' }" class="space-y-6">

    {{-- Prochaines séances --}}
    @if($prochaines->count() > 0)
    <div class="bg-indigo-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden">
        <div class="relative z-10">
            <h3 class="text-xl font-black uppercase tracking-wider mb-6 flex items-center gap-3">
                <i class="fas fa-clock"></i> {{ __('Prochaines séances') }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                @foreach($prochaines as $p)
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-[10px] font-black uppercase bg-white/20 px-2 py-0.5 rounded-full">{{ $p->type }}</span>
                        <span class="text-[10px] font-bold text-indigo-200">{{ $p->date->format('d/m') }}</span>
                    </div>
                    <h4 class="font-bold text-sm mb-1 truncate">{{ $p->module->nom }}</h4>
                    <p class="text-xs text-indigo-100">{{ $p->heure_debut->format('H:i') }} — {{ $p->salle?->nom ?? 'N/A' }}</p>
                </div>
                @endforeach
            </div>
        </div>
        <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
    </div>
    @endif

    {{-- View toggle --}}
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-black text-gray-800 dark:text-white uppercase tracking-wider">{{ __('Planning') }}</h3>
        <div class="flex bg-gray-100 dark:bg-gray-800 rounded-xl p-1 gap-1">
            <button @click="view='calendar'"
                :class="view==='calendar' ? 'bg-white dark:bg-gray-700 shadow text-indigo-600 dark:text-indigo-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'"
                class="px-4 py-2 rounded-lg text-sm font-bold transition-all">
                <i class="fas fa-calendar mr-1"></i> {{ __('Calendrier') }}
            </button>
            <button @click="view='list'"
                :class="view==='list' ? 'bg-white dark:bg-gray-700 shadow text-indigo-600 dark:text-indigo-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'"
                class="px-4 py-2 rounded-lg text-sm font-bold transition-all">
                <i class="fas fa-list mr-1"></i> {{ __('Liste') }}
            </button>
        </div>
    </div>

    {{-- Calendar view --}}
    <div x-show="view==='calendar'" x-cloak>
        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl p-6">
            <div id="calendar-etudiant"></div>
        </div>
    </div>

    {{-- List view --}}
    <div x-show="view==='list'" x-cloak class="space-y-4">
        @forelse($seances as $date => $daySeances)
        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
            <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h4 class="text-sm font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">
                    {{ \Carbon\Carbon::parse($date)->locale(app()->getLocale())->isoFormat('dddd D MMMM YYYY') }}
                </h4>
                <span class="text-xs font-bold text-gray-400">{{ count($daySeances) }} {{ __('séance(s)') }}</span>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($daySeances as $s)
                @php $colors = ['cours'=>'indigo','td'=>'emerald','tp'=>'amber','examen'=>'red']; $c = $colors[$s->type] ?? 'gray'; @endphp
                <div class="p-6 flex flex-col md:flex-row md:items-center gap-4 hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                    <div class="w-24 shrink-0">
                        <span class="text-lg font-black text-gray-800 dark:text-white">{{ $s->heure_debut->format('H:i') }}</span>
                        <p class="text-[10px] font-bold text-gray-400">{{ $s->heure_fin->format('H:i') }}</p>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1">
                            <h5 class="text-base font-black text-gray-800 dark:text-white">{{ $s->module->nom }}</h5>
                            <span class="px-3 py-1 rounded-full bg-{{ $c }}-100 dark:bg-{{ $c }}-900/30 text-{{ $c }}-600 dark:text-{{ $c }}-400 text-[9px] font-black uppercase">{{ $s->type }}</span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-user-tie mr-1"></i>{{ $s->professeur->user->full_name }}
                        </p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-[10px] font-black text-gray-400 uppercase block mb-1">{{ __('Salle') }}</span>
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $s->salle?->nom ?? 'N/A' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-12 text-center border border-gray-100 dark:border-gray-700">
            <i class="fas fa-calendar-times text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
            <p class="text-gray-500 font-bold italic">{{ __('Aucune séance planifiée dans votre emploi du temps.') }}</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('calendar-etudiant');
    const calendar = new FullCalendar.Calendar(el, {
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
        events: '{{ route("etudiant.edt.data") }}',
        editable: false,
        selectable: false,
        eventClick: function (info) {
            const p = info.event.extendedProps;
            Swal.fire({
                title: info.event.title,
                html: `<div class="text-left space-y-2 mt-2">
                    <p><span class="text-gray-400 text-xs uppercase font-bold">Type :</span> <span class="font-bold text-indigo-600">${p.type.toUpperCase()}</span></p>
                    <p><span class="text-gray-400 text-xs uppercase font-bold">Salle :</span> <span class="font-bold">${p.salle}</span></p>
                    <p><span class="text-gray-400 text-xs uppercase font-bold">Professeur :</span> <span class="font-bold">${p.professeur}</span></p>
                </div>`,
                confirmButtonText: '{{ __('Fermer') }}',
                confirmButtonColor: '#4f46e5',
                customClass: { popup: 'rounded-3xl' }
            });
        }
    });
    calendar.render();

    // Re-render when tab becomes visible
    document.querySelectorAll('[\\@click]').forEach(btn => {
        btn.addEventListener('click', () => setTimeout(() => calendar.updateSize(), 50));
    });
});
</script>
@endpush
