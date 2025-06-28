<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\DemandeAbsenceController;
use App\Http\Controllers\Auth\ForcePasswordChangeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChefServiceController;
use App\Http\Controllers\ValidationDirecteurController;
use App\Http\Controllers\ValidationChefController;
use App\Models\DemandeAbsence;
use App\Models\ValidationHistorique;
use App\Models\Agent;
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

// Authentification et changement de mot de passe forcé
Route::middleware('auth')->group(function () {
    Route::get('/password/force-change', [ForcePasswordChangeController::class, 'showChangePasswordForm'])->name('password.force_change');
    Route::post('/password/force-change', [ForcePasswordChangeController::class, 'changePassword'])->name('password.change');
});

// Routes protégées par le middleware 'auth' (accès authentifié requis)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard'); // Ajouter 'verified' pour le tableau de bord

    // Routes du profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes pour la gestion des agents (CRUD de base via resource)
    // Exclut 'create' et 'store' car ils sont gérés par les routes publiques ci-dessus.
    Route::resource('agent', AgentController::class)->except(['create', 'store', 'show']); // Exclure show pour utiliser une route spécifique si besoin

    // Route spécifique pour afficher les détails d'un agent VALIDÉ
    // Doit être définie avant une route générique comme /agents/{agent} si elle existait.
    Route::get('/agents/validated/{agent}', [AgentController::class, 'validatedDetails'])->name('agent.validatedDetails');

    // Route pour la liste des agents validés
    Route::get('/agents/validated', [AgentController::class, 'validatedIndex'])->name('agent.validated_index');

    // Route générique pour l'affichage d'un agent (si distinct de validatedDetails, sinon supprimer)
    // Si agent.show existe, cette route peut être utile.
    Route::get('/agents/{agent}', [AgentController::class, 'show'])->name('agent.show');


    // Routes spécifiques d'administration pour les agents (validation et rejet)
    Route::post('/agents/{agent}/validate-password', [AgentController::class, 'validateAndAssignPassword'])->name('agent.validateAndAssignPassword');
    Route::put('/agents/{agent}/reject', [AgentController::class, 'reject'])->name('agent.reject');

    // Route pour l'attribution de rôle (une seule occurrence)
    Route::post('/agents/{agent}/assign-role', [AgentController::class, 'assignRole'])->name('agent.assignRole');

    // Route de téléchargement d'acte (une seule occurrence, utilisez le bon chemin)
    Route::get('/agent/acte/{id}/download', [AgentController::class, 'downloadActe'])->name('agent.download_acte');

    // Routes pour les demandes d'absence
    Route::get('/demande-absence/create', [DemandeAbsenceController::class, 'create'])->name('demande_absence.create');
    Route::post('/demande-absence', [DemandeAbsenceController::class, 'store'])->name('demande_absence.store');
    Route::get('/demande-absence', [DemandeAbsenceController::class, 'index'])->name('demande_absence.index');
    Route::get('/demande-absence/{id}', [DemandeAbsenceController::class, 'show'])->name('demande_absence.show'); // Déplacée ici car nécessite auth
    Route::get('/demande-absence/{id}/edit', [DemandeAbsenceController::class, 'edit'])->name('demande_absence.edit');
    Route::put('/demande-absence/{id}', [DemandeAbsenceController::class, 'update'])->name('demande_absence.update');
    Route::delete('/demande-absence/{id}', [DemandeAbsenceController::class, 'destroy'])->name('demande_absence.destroy');
    Route::post('/demande_absence/{id}/submit', [DemandeAbsenceController::class, 'submit'])->name('demande_absence.submit');
    Route::get('/demande-absence/statistiques', [DemandeAbsenceController::class, 'stats'])->name('demande_absence.stats');


    // Routes spécifiques aux rôles (Chef de service, Directeur)
    // Chef de service
    Route::get('/chef/validation', [ValidationChefController::class, 'index'])->name('chef.validation');
    Route::post('/chef/validation/{id}', [ValidationChefController::class, 'traiter'])->name('chef.traiter');
    Route::get('/chef/historique', [ChefServiceController::class, 'historiqueValidations'])->name('chef.historique'); // Déplacée ici car nécessite auth
    Route::get('/chef/agents', [ChefServiceController::class, 'agents'])->name('chef.agents');
    Route::get('/chef/agent/{id}/stats', [ChefServiceController::class, 'agentStats'])->name('chef.agent.stats');


    // Directeur
    Route::get('/directeur/validation', [ValidationDirecteurController::class, 'index'])->name('directeur.validation');
    Route::post('/directeur/validation/{id}', [ValidationDirecteurController::class, 'traiter'])->name('directeur.traiter');
    Route::get('/directeur/historique', [ValidationDirecteurController::class, 'historiqueValidations'])->name('directeur.historique'); // Déplacée ici car nécessite auth
});


// Routes d'authentification Laravel (login, register, reset password, etc.)
require __DIR__.'/auth.php';
