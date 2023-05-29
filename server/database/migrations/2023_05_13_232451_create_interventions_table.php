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
        Schema::create('Intervention', function (Blueprint $table) {
            $table->id();
            $table->string('intitule_intervention');
            $table->string('annee_univ');
            $table->string('semestre');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->integer('nbr_heures')->default(0);
            $table->unsignedBigInteger('id_intervenant');
            $table->unsignedBigInteger('id_etab');
            $table->foreign('id_intervenant')->references('id')->on('Enseignant')->onDelete('cascade');
            $table->foreign('id_etab')->references('id')->on('Etablissement')->onDelete('cascade');
            $table->boolean('visa_etb')->default('false')->nullable();
            $table->boolean('visa_uae')->default('false')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Intervention');
    }
};
