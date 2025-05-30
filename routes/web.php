<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AgentController;  // Ajout de l'import du contrôleur AgentController
use Illuminate\Support\Facades\Route;
<<<<<<< HEAD

Route::get('/agent/list', [AgentController::class, 'index'])->name('agent.list');
=======
use Livewire\Volt\Volt;
use App\Http\Controllers\DemandeAbsenceController;

Route::get('/absence/create', [DemandeAbsenceController::class, 'create'])->name('absence.create');
Route::post('/absence', [DemandeAbsenceController::class, 'store'])->name('absence.store');

>>>>>>> ec613682e7ffedec615ae68f20038536cd59a636

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Ajout des routes resource pour Agent
    Route::resource('agent', AgentController::class);
});

require __DIR__.'/auth.php';
