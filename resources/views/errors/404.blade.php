<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — Page introuvable | UPF</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center font-sans antialiased">
    <div class="text-center px-6 py-12">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-indigo-50 dark:bg-indigo-950/30 rounded-3xl mb-6">
            <span class="text-5xl font-black text-indigo-400">404</span>
        </div>
        <h1 class="text-2xl font-extrabold text-gray-800 dark:text-white mb-2">Page introuvable</h1>
        <p class="text-gray-500 dark:text-slate-400 mb-8 max-w-sm mx-auto">
            La page que vous recherchez n'existe pas ou a été déplacée.
        </p>
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/' }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour
        </a>
    </div>
</body>
</html>
