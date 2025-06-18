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
// La route par défaut pour la page d'accueil avec un nom 'welcome'
Route::get('/', function () {
    return view('welcome');
})->name('welcome'); // Ajout d'un nom pour la route d'accueil pour faciliter les redirections

 // Route spécifique pour la page d'inscription d'un nouvel agent (accessible par l'admin)
Route::get('/register-agent', [AgentController::class, 'create'])->name('agent.register');
 // Route spécifique pour le traitement de l'inscription d'un nouvel agent
Route::post('/agents', [AgentController::class, 'store'])->name('agent.store');

Route::post('/demande_absence/{id}/submit', [DemandeAbsenceController::class, 'submit'])->name('demande_absence.submit');
Route::get('/demande-absence/statistiques', [\App\Http\Controllers\DemandeAbsenceController::class, 'stats'])->name('demande_absence.stats');

Route::get('/inscription-rejetee/{agent}/modifier', [AgentController::class, 'editRejectedForm'])->name('agent.edit_rejected_form');
// La méthode 'updateRejectedRegistration' traitera la soumission du formulaire.
Route::post('/inscription-rejetee/{agent}/mettre-a-jour', [AgentController::class, 'updateRejectedRegistration'])->name('agent.update_rejected_registration');

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
    

    Route::get('/password/change', [AgentController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/password/change', [AgentController::class, 'changePassword'])->name('password.change');

    
    // Routes pour la gestion des agents
    // Utilisez la ressource pour les opérations CRUD standards (index, show, edit, update, destroy)
    // Laravel génère les noms de route comme 'agent.index', 'agent.store', 'agent.edit', etc.
    Route::resource('agent', AgentController::class)->except(['create', 'store']);
    Route::get('/agent/actes/{id}/download' , [App\Http\Controllers\AgentController::class, 'downloadActe'])->name('agent.download_acte');
    Route::post('/agents/{agent}/assign-role', [AgentController::class, 'assignRole'])->name('agent.assignRole');

    Route::get('/agent/acte/{id}/download', [App\Http\Controllers\AgentController::class, 'downloadActe'])->name('agent.download_acte');


    // Nouvelles routes d'administration pour les agents (validation et rejet)
    Route::post('/agents/{agent}/validate-password', [AgentController::class, 'validateAndAssignPassword'])->name('agent.validateAndAssignPassword');
    Route::put('/agents/{agent}/reject', [AgentController::class, 'reject'])->name('agent.reject');

    // ROUTE pour la liste des agents validés
    Route::get('/agents/validated', [AgentController::class, 'validatedIndex'])->name('agent.validated_index');


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
