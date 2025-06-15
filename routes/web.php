<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\DemandeAbsenceController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt; 
use Illuminate\Support\Facades\Hash; // Ajoutez cette ligne en haut du fichier si elle n'y est pas

Route::get('/generate-password-hash', function () {
    $password = 'passer123'; // Remplacez par le mot de passe que vous voulez utiliser
    return Hash::make($password);
});// Assurez-vous que Volt est utilisé si nécessaire, sinon vous pouvez le retirer

// Routes publiques
Route::get('/', function () {
    return view('welcome');
});

// Dashboard (auth + verified)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes protégées par le middleware 'auth' (accès authentifié requis)
Route::middleware('auth')->group(function () {
    // Routes du profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/agents/validated', [AgentController::class, 'validatedIndex'])->name('agent.validated_index');

    // Routes pour la gestion des agents
    // Utilisez la ressource pour les opérations CRUD standards (index, show, edit, update, destroy)
    // Laravel génère les noms de route comme 'agent.index', 'agent.store', 'agent.edit', etc.
    // L'ajout de cette ligne ici remplace les routes individuelles que vous aviez.
    Route::resource('agent', AgentController::class)->except(['create', 'store']);
    // Nous excluons 'create' et 'store' de la ressource car nous allons les définir spécifiquement
    // pour le processus d'inscription avec des noms de route clairs.

    // Route spécifique pour la page d'inscription d'un nouvel agent (accessible par l'admin)
    // Cette route utilise la méthode 'create' de AgentController pour afficher le formulaire.
    Route::get('/register-agent', [AgentController::class, 'create'])->name('agent.register');

    // Route spécifique pour le traitement de l'inscription d'un nouvel agent
    // Cette route utilise la méthode 'store' de AgentController pour sauvegarder les données.
    Route::post('/agents', [AgentController::class, 'store'])->name('agent.store');


    // Nouvelles routes d'administration pour les agents (validation et rejet)
    // Ces routes sont des actions spécifiques et ne font pas partie de la ressource standard.
    // Elles nécessitent un agent spécifique pour être traitées (d'où le {agent} dans l'URL).
    // Utilisez POST pour la validation (changement d'état et création de données/mot de passe)
    Route::post('/agents/{agent}/validate-password', [AgentController::class, 'validateAndAssignPassword'])->name('agent.validateAndAssignPassword');
    // Utilisez PUT pour le rejet (mise à jour de l'état d'un agent existant)
    Route::put('/agents/{agent}/reject', [AgentController::class, 'reject'])->name('agent.reject');


    // Routes pour les demandes d'absence
    Route::get('/demande-absence/create', [DemandeAbsenceController::class, 'create'])->name('demande_absence.create');
    Route::post('/demande-absence', [DemandeAbsenceController::class, 'store'])->name('demande_absence.store');
    Route::get('/demande-absence', [DemandeAbsenceController::class, 'index'])->name('demande_absence.index');
    Route::get('/demande-absence/{id}/edit', [DemandeAbsenceController::class, 'edit'])->name('demande_absence.edit');
    Route::put('/demande-absence/{id}', [DemandeAbsenceController::class, 'update'])->name('demande_absence.update');
    Route::delete('/demande-absence/{id}', [DemandeAbsenceController::class, 'destroy'])->name('demande_absence.destroy');

    // Routes spécifiques aux rôles (Chef de service, Directeur)
    // Chef de service
    Route::get('/chef/validation', [\App\Http\Controllers\ValidationChefController::class, 'index'])->name('chef.validation');
    Route::post('/chef/validation/{id}', [\App\Http\Controllers\ValidationChefController::class, 'traiter'])->name('chef.traiter');

    // Directeur
    Route::get('/directeur/validation', [\App\Http\Controllers\ValidationDirecteurController::class, 'index'])->name('directeur.validation');
    Route::post('/directeur/validation/{id}', [\App\Http\Controllers\ValidationDirecteurController::class, 'traiter'])->name('directeur.traiter');
});

// Routes d'authentification Laravel (login, register, reset password, etc.)
require __DIR__.'/auth.php';
