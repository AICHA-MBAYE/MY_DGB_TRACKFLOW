<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\DemandeAbsenceController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Routes publiques
Route::get('/', function () {
    return view('welcome');
});

// Dashboard (auth + verified)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes protégées par auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes pour l'agent
    //Route::resource('agent', AgentController::class);
}); // <-- fermeture correcte du group
// Routes pour l'agent
    Route::resource('agent', AgentController::class);
// Autres routes spécifiques
Route::get('/agent/list', [AgentController::class, 'index'])->name('agent.list');
Route::get('/agents/create', [AgentController::class, 'create'])->name('agents.create');
Route::get('/agents', [AgentController::class, 'index'])->name('agents.index');

Route::get('/demande-absence/create', [DemandeAbsenceController::class, 'create'])->name('demande_absence.create');
    Route::post('/demande-absence', [DemandeAbsenceController::class, 'store'])->name('demande_absence.store');
    Route::get('/demande-absence', [DemandeAbsenceController::class, 'index'])->name('demande_absence.index');
    Route::get('/demande-absence/{id}/edit', [DemandeAbsenceController::class, 'edit'])->name('demande_absence.edit');
    Route::put('/demande-absence/{id}', [DemandeAbsenceController::class, 'update'])->name('demande_absence.update');
    Route::delete('/demande-absence/{id}', [DemandeAbsenceController::class, 'destroy'])->name('demande_absence.destroy');
    // Chef de service
Route::get('/chef/validation', [\App\Http\Controllers\ValidationChefController::class, 'index'])->name('chef.validation');
Route::post('/chef/validation/{id}', [\App\Http\Controllers\ValidationChefController::class, 'traiter'])->name('chef.traiter');

// Directeur
Route::get('/directeur/validation', [\App\Http\Controllers\ValidationDirecteurController::class, 'index'])->name('directeur.validation');
Route::post('/directeur/validation/{id}', [\App\Http\Controllers\ValidationDirecteurController::class, 'traiter'])->name('directeur.traiter');

// Auth routes
require __DIR__.'/auth.php';
