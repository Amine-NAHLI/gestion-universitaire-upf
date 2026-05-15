@extends('layouts.dashboard')

@section('title', __('Gestion des Modules'))
@section('page-title', __('Liste des Modules'))

@section('content')
<div class="space-y-6">
    <!-- Header & Filter -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
        <form action="{{ route('admin.modules.index') }}" method="GET" class="flex items-center gap-3 w-full md:w-auto">
            <select name="filiere_id" onchange="this.form.submit()" class="px-4 py-2 rounded-xl bg-gray-100 dark:bg-gray-700 border-none text-sm font-bold text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500">
                <option value="">{{ __('Toutes les filières') }}</option>
                @foreach($filieres as $filiere)
                    <option value="{{ $filiere->id }}" {{ request('filiere_id') == $filiere->id ? 'selected' : '' }}>{{ $filiere->nom }}</option>
                @endforeach
            </select>
        </form>
        
        <a href="{{ route('admin.modules.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            {{ __('Ajouter') }}
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Module') }}</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Code') }}</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Filière / Niveau') }}</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">{{ __('Coeff / Sem.') }}</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">{{ __('Heures (C/TD/TP)') }}</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($modules as $module)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors text-sm">
                        <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">{{ $module->nom }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-[10px] font-mono font-bold">{{ $module->code }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-indigo-600 dark:text-indigo-400 font-bold text-xs uppercase">{{ $module->niveau->filiere->code }}</p>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400">{{ $module->niveau->nom }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-col items-center">
                                <span class="font-bold text-gray-800 dark:text-white">{{ $module->coefficient }}</span>
                                <span class="text-[10px] text-gray-500">S{{ $module->semestre }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2 text-[10px] font-bold">
                                <span class="text-blue-500">C: {{ $module->heures_cours }}h</span>
                                <span class="text-purple-500">TD: {{ $module->heures_td }}h</span>
                                <span class="text-pink-500">TP: {{ $module->heures_tp }}h</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.modules.edit', $module) }}" class="p-2 rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.modules.destroy', $module) }}" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-delete p-2 rounded-lg text-gray-400 hover:bg-red-100 dark:hover:bg-red-900/30 hover:text-red-600 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400 font-medium">
                            {{ __('Aucun module trouvé.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $modules->links() }}
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('form');
        Swal.fire({
            title: '{{ __('Supprimer ce module ?') }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: '{{ __('Oui, supprimer') }}',
            cancelButtonText: '{{ __('Annuler') }}',
            customClass: { popup: 'rounded-3xl dark:bg-gray-800 dark:text-white' }
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endpush
@endsection
