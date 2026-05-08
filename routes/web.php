<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Professeur\DashboardController as ProfesseurDashboard;
use App\Http\Controllers\Etudiant\DashboardController as EtudiantDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    return match($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'professeur' => redirect()->route('professeur.dashboard'),
        'etudiant' => redirect()->route('etudiant.dashboard'),
        default => view('dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::patch('users/{user}/toggle-active', [\App\Http\Controllers\Admin\UserController::class, 'toggleActive'])->name('users.toggle-active');
    Route::resource('filieres', \App\Http\Controllers\Admin\FiliereController::class);
    Route::resource('modules', \App\Http\Controllers\Admin\ModuleController::class);
    Route::resource('salles', \App\Http\Controllers\Admin\SalleController::class);
    Route::get('notes', [\App\Http\Controllers\Admin\NoteController::class, 'index'])->name('notes.index');
    Route::get('notes/{etudiant}/{module}', [\App\Http\Controllers\Admin\NoteController::class, 'edit'])->name('notes.edit');
    Route::put('notes/{etudiant}/{module}', [\App\Http\Controllers\Admin\NoteController::class, 'update'])->name('notes.update');
    
    Route::get('absences', [\App\Http\Controllers\Admin\AbsenceController::class, 'index'])->name('absences.index');
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
    Route::get('/dashboard', [ProfesseurDashboard::class, 'index'])->name('dashboard');
});

// Routes Etudiant
Route::middleware(['auth', 'role:etudiant'])->prefix('etudiant')->name('etudiant.')->group(function () {
    Route::get('/dashboard', [EtudiantDashboard::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
