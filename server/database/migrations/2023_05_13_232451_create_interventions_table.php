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
            $table->foreignId('enseignant_id');
            $table->foreignId('etablissement_id');
            $table->integer('visa_etb');//
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
