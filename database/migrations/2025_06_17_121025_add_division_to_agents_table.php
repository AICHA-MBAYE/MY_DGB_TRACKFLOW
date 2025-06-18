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
        Schema::table('agents', function (Blueprint $table) {
            // Ajoute une nouvelle colonne 'division' après 'direction'.
            // Elle est nullable car elle pourrait ne pas être définie pour les agents existants ou dans certains flux.
            $table->string('division')->nullable()->after('direction');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            // Supprime la colonne 'division' si la migration est annulée.
            $table->dropColumn('division');
        });
    }
};

