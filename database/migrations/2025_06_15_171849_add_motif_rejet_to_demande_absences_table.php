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
            $table->string('motif_rejet_chef')->nullable()->after('etat_chef');
            $table->string('motif_rejet_directeur')->nullable()->after('etat_directeur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demande_absences', function (Blueprint $table) {
            $table->dropColumn('motif_rejet_chef');
            $table->dropColumn('motif_rejet_directeur');
        });
    }
};
