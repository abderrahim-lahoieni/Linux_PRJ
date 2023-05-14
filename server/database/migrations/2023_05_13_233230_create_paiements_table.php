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
            $table->string('vh');
            $table->integer('taux_h');
            $table->float('brut');
            $table->float('ir');//impot sur revenue
            $table->float('net');
            $table->string('annee_univ');
            $table->string('semestre');
            $table->unsignedBigInteger('enseignant_id');
            $table->unsignedBigInteger('etablissement_id');
            $table->foreign('enseignant_id')->references('id')->on('enseignants');
            $table->foreign('etablissement_id')->references('id')->on('etablissements');
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
