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
         Schema::table('demande_absences', function (Blueprint $table) {
        $table->timestamp('date_traitement_chef')->nullable();
        $table->timestamp('date_traitement_directeur')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    Schema::table('demande_absences', function (Blueprint $table) {
        $table->dropColumn(['date_traitement_chef', 'date_traitement_directeur']);
    });
    }
};
