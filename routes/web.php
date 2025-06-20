<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\DemandeAbsenceController;
use App\Http\Controllers\Auth\ForcePasswordChangeController; // NOUVEAU : Importe le contrôleur pour le changement de mot de passe forcé
use Illuminate\Support\Facades\Route;
// use Livewire\Volt\Volt; // Commenté car non utilisé pour les routes listées ici
use Illuminate\Support\Facades\Hash;

Route::get('/generate-password-hash', function () {
    $password = 'passer123'; // Remplacez par le mot de passe que vous voulez utiliser
    return Hash::make($password);
});

// Routes publiques (accessibles sans être connecté)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Routes d'inscription d'agent initiale (accessibles sans être connecté)
Route::get('/register-agent', [AgentController::class, 'create'])->name('agent.register');
Route::post('/agents', [AgentController::class, 'store'])->name('agent.store');

// Routes pour la modification d'inscription rejetée (accessibles sans être connecté)
Route::get('/inscription-rejetee/{agent}/modifier', [AgentController::class, 'editRejectedForm'])->name('agent.edit_rejected_form');
Route::post('/inscription-rejetee/{agent}/mettre-a-jour', [AgentController::class, 'updateRejectedRegistration'])->name('agent.update_rejected_registration');

// Dashboard (authentifié et vérifié)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// NOUVEAU : Routes pour le changement de mot de passe forcé à la première connexion
// Ces routes DOIVENT être protégées par 'auth' pour s'assurer que seul un utilisateur connecté peut y accéder,
// mais elles NE DOIVENT PAS être affectées par le middleware ForcePasswordChange lui-même,
// car c'est la destination de la redirection de ce middleware pour éviter une boucle.
Route::middleware('auth')->group(function () {
    Route::get('/password/force-change', [ForcePasswordChangeController::class, 'showChangePasswordForm'])->name('password.force_change');
    Route::post('/password/force-change', [ForcePasswordChangeController::class, 'changePassword'])->name('password.change'); // Le nom 'password.change' est utilisé dans le formulaire
});


// Routes protégées par le middleware 'auth' (accès authentifié requis)
// C'est ici que le middleware ForcePasswordChange agira pour rediriger si nécessaire
Route::middleware('auth')->group(function () {
    // Routes du profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes pour la gestion des agents (CRUD - Index, Show, Edit, Update, Destroy)
    // Les routes 'create' et 'store' sont exclues ici car elles sont maintenant publiques ci-dessus.
    Route::resource('agent', AgentController::class)->except(['create', 'store']);

    // Routes spécifiques d'administration pour les agents (validation et rejet)
    Route::post('/agents/{agent}/validate-password', [AgentController::class, 'validateAndAssignPassword'])->name('agent.validateAndAssignPassword');
    Route::put('/agents/{agent}/reject', [AgentController::class, 'reject'])->name('agent.reject');

    // Route pour l'attribution de rôle (utilisé dans le tableau de bord de l'admin)
    Route::post('/agents/{agent}/assign-role', [AgentController::class, 'assignRole'])->name('agent.assignRole');


    // ROUTE pour la liste des agents validés
    Route::get('/agents/validated', [AgentController::class, 'validatedIndex'])->name('agent.validated_index');

    // Route de téléchargement d'acte (vérifier si nécessaire et si la méthode existe)
    // J'ai gardé une seule occurrence de cette route.
    Route::get('/agent/acte/{id}/download', [AgentController::class, 'downloadActe'])->name('agent.download_acte');


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

    // Liste des agents du chef de service
    Route::get('/chef/agents', [\App\Http\Controllers\ChefServiceController::class, 'agents'])->name('chef.agents');

    // Statistiques d'un agent
    Route::get('/chef/agent/{id}/stats', [\App\Http\Controllers\ChefServiceController::class, 'agentStats'])->name('chef.agent.stats');
});


// Routes d'authentification Laravel (login, register, reset password, etc.)
require __DIR__.'/auth.php';