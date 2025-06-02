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
    Route::resource('agent', AgentController::class);
}); // <-- fermeture correcte du group

// Autres routes spécifiques
Route::get('/agent/list', [AgentController::class, 'index'])->name('agent.list');
Route::get('/absence/create', [DemandeAbsenceController::class, 'create'])->name('absence.create');
Route::post('/absence', [DemandeAbsenceController::class, 'store'])->name('absence.store');

// Auth routes
require __DIR__.'/auth.php';
