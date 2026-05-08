<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\EdtController;
use App\Http\Controllers\Api\AbsenceController;
use App\Http\Controllers\Api\ModuleController;

// Auth publique
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Routes protégées par Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Notes
    Route::get('/notes', [NoteController::class, 'index']);
    
    // EDT
    Route::get('/edt', [EdtController::class, 'index']);
    
    // Absences
    Route::get('/absences', [AbsenceController::class, 'index']);
    
    // Modules
    Route::get('/modules', [ModuleController::class, 'index']);
    Route::get('/modules/{module}', [ModuleController::class, 'show']);
});
