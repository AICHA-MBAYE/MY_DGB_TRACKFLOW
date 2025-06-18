<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('prenom'); // Supprimé ->require(), les champs sont requis par défaut sauf s'ils sont nullable()
            $table->string('nom');    // Supprimé ->require()
            $table->string('email')->unique(); // Supprimé ->require(), unique() implique la non-nullabilité
            $table->string('role');
            // Ajout du champ 'direction' comme string pour correspondre aux valeurs 'DAP', 'DCI', etc.
            $table->string('direction');
            $table->string('division');
            // Ajout du champ 'password', qui peut être nullable car il est attribué après inscription
            $table->string('password')->nullable();
            // Ajout du champ 'status' avec des valeurs prédéfinies et une valeur par défaut 'pending'
            $table->enum('status', ['pending', 'validated', 'rejected'])->default('pending');
            // Ajout du remember_token pour la fonctionnalité "se souvenir de moi" lors de la connexion
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};

