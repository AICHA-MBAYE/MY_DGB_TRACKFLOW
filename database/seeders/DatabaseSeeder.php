<?php

namespace Database\Seeders;

use App\Models\Agent; // Importe le modèle Agent au lieu de User
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Importe la façade Hash pour hacher le mot de passe

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Supprime l'appel à User::factory()->create()
        // User::factory(10)->create(); // Commenté ou supprimé si cette ligne existait

        // Crée un agent administrateur par défaut
        Agent::create([
            'prenom' => 'Admin', // Prénom de l'administrateur
            'nom' => 'Principal', // Nom de l'administrateur
            'email' => 'admin@example.com', // Adresse email de l'administrateur
            'password' => Hash::make('password'), // Mot de passe haché (changez 'password' pour un MDP sécurisé en production)
            'role' => 'super_admin', // Attribue le rôle 'super_admin'
            'direction' => 'DAP', // Attribue une direction (doit correspondre à vos valeurs définies)
            'status' => 'validated', // Statut 'validated' pour un compte admin qui peut se connecter immédiatement
        ]);

        // Vous pouvez ajouter d'autres créations d'agents ici si nécessaire
        // Agent::factory(5)->create(); // Exemple si vous avez une AgentFactory
    }
}
