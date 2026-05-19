<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Professeur\DashboardController as ProfesseurDashboard;
use App\Http\Controllers\Etudiant\DashboardController as EtudiantDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/registration-submitted', function () {
    return view('auth.registration-submitted');
})->name('registration.submitted');

Route::get('/dashboard', function () {
    $user = auth()->user();
    return match($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'professeur' => redirect()->route('professeur.dashboard'),
        'etudiant' => redirect()->route('etudiant.dashboard'),
        'parent' => redirect()->route('parent.dashboard'),
        default => view('dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'fr', 'ar'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang.switch');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'search'])->name('global.search');
});

// Routes Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::patch('users/{user}/toggle-active', [\App\Http\Controllers\Admin\UserController::class, 'toggleActive'])->name('users.toggle-active');
    Route::get('filieres/{filiere}/niveaux', [\App\Http\Controllers\Admin\FiliereController::class, 'niveaux'])->name('filieres.niveaux');
    Route::get('niveaux/{niveau}/groupes', [\App\Http\Controllers\Admin\FiliereController::class, 'groupesByNiveau'])->name('niveaux.groupes');
    Route::resource('filieres', \App\Http\Controllers\Admin\FiliereController::class);
    Route::resource('modules', \App\Http\Controllers\Admin\ModuleController::class);
    Route::resource('salles', \App\Http\Controllers\Admin\SalleController::class);
    Route::get('notes', [\App\Http\Controllers\Admin\NoteController::class, 'index'])->name('notes.index');
    Route::get('notes/export', [\App\Http\Controllers\Admin\NoteController::class, 'export'])->name('notes.export');
    Route::get('notes/{etudiant}/{module}', [\App\Http\Controllers\Admin\NoteController::class, 'edit'])->name('notes.edit');
    Route::put('notes/{etudiant}/{module}', [\App\Http\Controllers\Admin\NoteController::class, 'update'])->name('notes.update');
    
    Route::get('absences', [\App\Http\Controllers\Admin\AbsenceController::class, 'index'])->name('absences.index');
    Route::get('absences/export', [\App\Http\Controllers\Admin\AbsenceController::class, 'export'])->name('absences.export');
    Route::patch('absences/{absence}/toggle-justifiee', [\App\Http\Controllers\Admin\AbsenceController::class, 'toggleJustifiee'])->name('absences.toggle-justifiee');
    Route::get('justificatifs/{justificatif}/valider', [\App\Http\Controllers\Admin\AbsenceController::class, 'validerJustificatif'])->name('justificatifs.valider');
    Route::get('justificatifs/{justificatif}/refuser', [\App\Http\Controllers\Admin\AbsenceController::class, 'refuserJustificatif'])->name('justificatifs.refuser');

    Route::get('demandes', [\App\Http\Controllers\Admin\DemandeController::class, 'index'])->name('demandes.index');
    Route::get('demandes/{demande}', [\App\Http\Controllers\Admin\DemandeController::class, 'show'])->name('demandes.show');
    Route::patch('demandes/{demande}/valider', [\App\Http\Controllers\Admin\DemandeController::class, 'valider'])->name('demandes.valider');
    Route::patch('demandes/{demande}/refuser', [\App\Http\Controllers\Admin\DemandeController::class, 'refuser'])->name('demandes.refuser');
    Route::get('demandes/{demande}/pdf', [\App\Http\Controllers\Admin\DemandeController::class, 'genererPdf'])->name('demandes.pdf');

    Route::get('edt', [\App\Http\Controllers\Admin\EdtController::class, 'index'])->name('edt.index');
    Route::get('edt/data', [\App\Http\Controllers\Admin\EdtController::class, 'data'])->name('edt.data');
    Route::post('edt', [\App\Http\Controllers\Admin\EdtController::class, 'store'])->name('edt.store');
    Route::put('edt/{seance}', [\App\Http\Controllers\Admin\EdtController::class, 'update'])->name('edt.update');
    Route::delete('edt/{seance}', [\App\Http\Controllers\Admin\EdtController::class, 'destroy'])->name('edt.destroy');
    Route::get('statistiques', [\App\Http\Controllers\Admin\StatistiqueController::class, 'index'])->name('statistiques.index');
});

// Routes Professeur
Route::middleware(['auth', 'role:professeur'])->prefix('professeur')->name('professeur.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Professeur\DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('modules', [\App\Http\Controllers\Professeur\ModuleController::class, 'index'])->name('modules.index');
    
    Route::get('notes', [\App\Http\Controllers\Professeur\NoteController::class, 'index'])->name('notes.index');
    Route::get('notes/{module}/{groupe}', [\App\Http\Controllers\Professeur\NoteController::class, 'saisir'])->name('notes.saisir');
    Route::post('notes/{module}/{groupe}', [\App\Http\Controllers\Professeur\NoteController::class, 'enregistrer'])->name('notes.enregistrer');
    
    Route::get('absences', [\App\Http\Controllers\Professeur\AbsenceController::class, 'index'])->name('absences.index');
    Route::get('absences/{seance}', [\App\Http\Controllers\Professeur\AbsenceController::class, 'feuille'])->name('absences.feuille');
    Route::post('absences/{seance}', [\App\Http\Controllers\Professeur\AbsenceController::class, 'enregistrer'])->name('absences.enregistrer');
    
    Route::get('cahier', [\App\Http\Controllers\Professeur\CahierController::class, 'index'])->name('cahier.index');
    Route::get('cahier/{seance}', [\App\Http\Controllers\Professeur\CahierController::class, 'create'])->name('cahier.create');
    Route::post('cahier/{seance}', [\App\Http\Controllers\Professeur\CahierController::class, 'store'])->name('cahier.store');
    
    Route::get('edt', [\App\Http\Controllers\Professeur\EdtController::class, 'index'])->name('edt.index');
    
    Route::get('reservations', [\App\Http\Controllers\Professeur\ReservationController::class, 'index'])->name('reservations.index');
    Route::post('reservations', [\App\Http\Controllers\Professeur\ReservationController::class, 'store'])->name('reservations.store');
    Route::delete('reservations/{reservation}', [\App\Http\Controllers\Professeur\ReservationController::class, 'destroy'])->name('reservations.destroy');
    
    Route::get('demandes', [\App\Http\Controllers\Professeur\DemandeController::class, 'index'])->name('demandes.index');
    Route::post('demandes', [\App\Http\Controllers\Professeur\DemandeController::class, 'store'])->name('demandes.store');
    
    Route::get('classroom', [\App\Http\Controllers\Professeur\ClassroomController::class, 'index'])->name('classroom.index');
    Route::get('classroom/{module}', [\App\Http\Controllers\Professeur\ClassroomController::class, 'show'])->name('classroom.show');
    Route::post('classroom/{module}/annonces', [\App\Http\Controllers\Professeur\ClassroomController::class, 'publierAnnonce'])->name('classroom.annonce');
    Route::post('classroom/{module}/supports', [\App\Http\Controllers\Professeur\ClassroomController::class, 'uploadSupport'])->name('classroom.support');
});

// Routes Étudiant
Route::middleware(['auth', 'role:etudiant'])->prefix('etudiant')->name('etudiant.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Etudiant\DashboardController::class, 'index'])->name('dashboard');

    Route::get('notes', [\App\Http\Controllers\Etudiant\NoteController::class, 'index'])->name('notes.index');
    
    Route::get('edt', [\App\Http\Controllers\Etudiant\EdtController::class, 'index'])->name('edt.index');
    
    Route::get('absences', [\App\Http\Controllers\Etudiant\AbsenceController::class, 'index'])->name('absences.index');
    
    Route::get('justificatifs', [\App\Http\Controllers\Etudiant\JustificatifController::class, 'index'])->name('justificatifs.index');
    Route::post('justificatifs', [\App\Http\Controllers\Etudiant\JustificatifController::class, 'store'])->name('justificatifs.store');
    
    Route::get('demandes', [\App\Http\Controllers\Etudiant\DemandeController::class, 'index'])->name('demandes.index');
    Route::post('demandes', [\App\Http\Controllers\Etudiant\DemandeController::class, 'store'])->name('demandes.store');
    Route::get('demandes/{demande}/download', [\App\Http\Controllers\Etudiant\DemandeController::class, 'download'])->name('demandes.download');
    
    Route::get('classroom', [\App\Http\Controllers\Etudiant\ClassroomController::class, 'index'])->name('classroom.index');
    Route::get('classroom/{module}', [\App\Http\Controllers\Etudiant\ClassroomController::class, 'show'])->name('classroom.show');
    Route::post('classroom/annonces/{annonce}/commenter', [\App\Http\Controllers\Etudiant\ClassroomController::class, 'commenter'])->name('classroom.commenter');
    
    Route::get('documents', [\App\Http\Controllers\Etudiant\DocumentController::class, 'index'])->name('documents.index');
    
    Route::get('releve-notes/download', [\App\Http\Controllers\Etudiant\ReleveNotesController::class, 'download'])->name('releve-notes.download');
});

// Notifications system
Route::middleware('auth')->group(function () {
    Route::get('notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

// Routes Parent
Route::middleware(['auth', 'role:parent'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Parent\DashboardController::class, 'index'])->name('dashboard');
    Route::post('chatbot', [\App\Http\Controllers\Parent\ChatbotController::class, 'ask'])->name('chatbot')->middleware('throttle:20,1440');
    Route::get('chatbot/welcome', [\App\Http\Controllers\Parent\ChatbotController::class, 'welcome'])->name('chatbot.welcome');
    Route::get('chatbot/history', [\App\Http\Controllers\Parent\ChatbotController::class, 'history'])->name('chatbot.history');
    Route::post('chatbot/feedback', [\App\Http\Controllers\Parent\ChatbotController::class, 'saveFeedback'])->name('chatbot.feedback');
    Route::get('notifications', [\App\Http\Controllers\Parent\NotificationController::class, 'index'])->name('notifications');
    Route::post('notifications/{notification}/read', [\App\Http\Controllers\Parent\NotificationController::class, 'markRead'])->name('notifications.read');
});

require __DIR__.'/auth.php';
