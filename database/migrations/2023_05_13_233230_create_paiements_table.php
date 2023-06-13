<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

     //Create table paiements
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->integer('vh'); //Volume horaire 
            $table->integer('taux_h');//Taux Horaire
            $table->integer('brut');
            $table->integer('ir');//impot sur le revenue
            $table->integer('net');
            $table->integer('annee_univ');
            $table->char('semestre',2);
            $table->unsignedBigInteger('id_intervenant');
            $table->unsignedBigInteger('id_etab');
            $table->foreign('id_intervenant')->references('id')->on('enseignants')->onDelete('cascade');
            $table->foreign('id_etab')->references('id')->on('etablissements')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
