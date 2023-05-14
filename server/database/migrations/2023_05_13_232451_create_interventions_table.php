<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */


    //Create table interventions
    public function up(): void
    {
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->string('intitule_intervention');
            $table->string('annee__univ');
            $table->string('semestre');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->integer('nbr_heures');
            $table->unsignedBigInteger('enseignant_id');
            $table->unsignedBigInteger('etablissement_id');
            $table->foreign('enseignant_id')->references('id')->on('enseignants');
            $table->foreign('etablissement_id')->references('id')->on('etablissements');
            $table->integer('visa_etb');
            $table->integer('visa_uae');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};
