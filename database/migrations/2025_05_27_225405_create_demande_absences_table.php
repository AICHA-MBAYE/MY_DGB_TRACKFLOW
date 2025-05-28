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
    Schema::create('demande_absences', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('type_absence_id')->constrained()->onDelete('cascade');
        $table->date('date_debut');
        $table->date('date_fin');
        $table->text('description')->nullable();
        $table->string('statut')->default('en attente');
        $table->string('justificatif')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_absences');
    }
};
