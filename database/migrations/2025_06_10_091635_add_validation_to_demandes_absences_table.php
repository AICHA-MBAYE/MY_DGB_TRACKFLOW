<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

public function up()
{
    Schema::table('demande_absences', function (Blueprint $table) {
        $table->enum('etat_chef', ['en_attente', 'acceptée', 'rejetée'])->default('en_attente');
        $table->enum('etat_directeur', ['en_attente', 'acceptée', 'rejetée'])->default('en_attente');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demande_absences', function (Blueprint $table) {
            //
        });
    }
};
