<x-guest-layout>
    <div class="mb-8 text-center" data-aos="fade-down">
        <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ __('Validation de l\'email') }}</h2>
        <p class="text-slate-500 dark:text-slate-400 font-medium mt-2">{{ __('Dernière étape avant la soumission de votre dossier') }}</p>
    </div>

    <div class="bg-indigo-50/50 dark:bg-indigo-900/10 border border-indigo-100/50 dark:border-indigo-800/30 rounded-2xl p-6 mb-6">
        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
            {{ __('Merci pour votre inscription ! Avant de continuer, veuillez valider votre adresse email en cliquant sur le lien d\'activation envoyé sur votre boîte de réception. Si vous ne l\'avez pas reçu, vous pouvez en demander un nouveau ci-dessous.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-xl text-sm font-bold flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ __('Un nouveau lien de validation a été envoyé sur votre adresse email.') }}
        </div>
    @endif

    <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
            @csrf
            <button type="submit" class="btn-primary w-full px-6 py-3 text-sm">
                {{ __('Renvoyer l\'email de validation') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
            @csrf
            <button type="submit" class="w-full text-center text-sm font-bold text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors uppercase tracking-wider">
                {{ __('Se déconnecter') }}
            </button>
        </form>
    </div>
</x-guest-layout>
