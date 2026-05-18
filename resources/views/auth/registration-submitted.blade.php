<x-guest-layout>
    <div class="mb-8 text-center" data-aos="fade-down">
        <!-- Animated Checkmark Icon -->
        <div class="inline-flex items-center justify-center w-20 h-20 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-500 dark:text-emerald-400 rounded-full mb-6 border-4 border-emerald-100 dark:border-emerald-900/50 animate-bounce">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        
        <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ __('Email Validé ! 🎉') }}</h2>
        <p class="text-slate-500 dark:text-slate-400 font-medium mt-2">{{ __('Votre adresse email a été validée avec succès.') }}</p>
    </div>

    <div class="bg-indigo-50/50 dark:bg-indigo-900/10 border border-indigo-100/50 dark:border-indigo-800/30 rounded-2xl p-6 mb-8 text-center leading-relaxed">
        <p class="text-sm font-bold text-indigo-900 dark:text-indigo-300 uppercase tracking-wider mb-2">{{ __('Dossier en cours d\'examen') }}</p>
        <p class="text-sm text-slate-600 dark:text-slate-300">
            {{ __('Votre demande d\'inscription est désormais transmise à l\'administration de l\'université. Elle sera examinée dans les plus brefs délais.') }}
        </p>
    </div>

    <div class="bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800 rounded-2xl p-5 mb-8 flex items-start gap-4">
        <div class="p-2 bg-indigo-100 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 rounded-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 012-2V7a2 2 0 01-2-2H5a2 2 0 01-2 2v10a2 2 0 012 2z"/></svg>
        </div>
        <div>
            <p class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider mb-1">{{ __('Notification par Email') }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-normal">
                {{ __('Dès que l\'administrateur aura validé ou rejeté votre demande, vous recevrez automatiquement un email de notification de décision.') }}
            </p>
        </div>
    </div>

    <a href="{{ url('/') }}" class="btn-primary w-full py-4 text-center block text-base font-bold tracking-tight">
        {{ __('Retourner à la page d\'accueil') }}
    </a>
</x-guest-layout>
