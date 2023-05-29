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
            $table->string('vh'); //Volume horaire 
            $table->integer('taux_h');//Taux Horaire
            $table->float('brut');
            $table->float('ir');//impot sur le revenue
            $table->float('net');
            $table->string('annee_univ');
            $table->string('semestre');
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
