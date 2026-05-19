<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 — Erreur serveur | UPF</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center font-sans antialiased">
    <div class="text-center px-6 py-12">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-amber-50 dark:bg-amber-950/30 rounded-3xl mb-6">
            <span class="text-5xl font-black text-amber-400">500</span>
        </div>
        <h1 class="text-2xl font-extrabold text-gray-800 dark:text-white mb-2">Erreur serveur</h1>
        <p class="text-gray-500 dark:text-slate-400 mb-8 max-w-sm mx-auto">
            Une erreur inattendue s'est produite. L'équipe technique a été notifiée.
        </p>
        <a href="/"
           class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Accueil
        </a>
    </div>
</body>
</html>
