<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValidationHistoriquesTable extends Migration
{
    public function up()
    {
        Schema::create('validation_historiques', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('demande_absence_id');
            $table->unsignedBigInteger('user_id'); // chef ou directeur
            $table->string('role'); // 'chef' ou 'directeur'
            $table->string('action'); // 'acceptée', 'refusée', etc.
            $table->timestamp('validated_at');
            $table->timestamps();

            $table->foreign('demande_absence_id')->references('id')->on('demande_absences')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('agents')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('validation_historiques');
    }
}
