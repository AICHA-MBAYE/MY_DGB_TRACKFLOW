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
       $table->unsignedBigInteger('user_id')->nullable();

        $table->date('date_debut');
        $table->date('date_fin');
        $table->text('motif');
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
