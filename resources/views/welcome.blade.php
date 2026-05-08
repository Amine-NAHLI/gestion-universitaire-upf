<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - UPF</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-primary-50 via-white to-primary-100 min-h-screen">

    {{-- Navigation --}}
    <nav class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">
                        U
                    </div>
                    <span class="text-xl font-bold text-gray-800">UPF Gestion</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="#" class="btn-secondary">Connexion</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        <div data-aos="fade-up">
            <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 mb-6">
                Plateforme Universitaire
                <span class="block text-primary-600 mt-2">Université Privée de Fès</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-10">
                Une application moderne pour gérer toute votre vie universitaire : notes, emploi du temps, absences, demandes administratives.
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="#" class="btn-primary text-lg px-8 py-3">
                    Se connecter
                </a>
                <a href="#features" class="btn-secondary text-lg px-8 py-3">
                    Découvrir
                </a>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section id="features" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="card text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">📚</div>
                <h3 class="text-xl font-bold mb-2">Espace Étudiant</h3>
                <p class="text-gray-600">Notes, supports de cours, emploi du temps et demandes administratives.</p>
            </div>
            <div class="card text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">👨‍🏫</div>
                <h3 class="text-xl font-bold mb-2">Espace Professeur</h3>
                <p class="text-gray-600">Saisie des notes, gestion des absences, cahier de textes et réservation de salles.</p>
            </div>
            <div class="card text-center" data-aos="fade-up" data-aos-delay="300">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">⚙️</div>
                <h3 class="text-xl font-bold mb-2">Administration</h3>
                <p class="text-gray-600">Gestion complète des utilisateurs, modules, emplois du temps et validations.</p>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-white py-8 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; {{ date('Y') }} Université Privée de Fès - Projet TW2</p>
            <p class="text-sm text-gray-400 mt-2">Réalisé par Amine NAHLI - GINFO3</p>
        </div>
    </footer>

</body>
</html>