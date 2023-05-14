<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

     // //Create table administrateurs
    public function up(): void
    {
        Schema::create('administrateurs', function (Blueprint $table) {
            $table->id();
            $table->integer('ppr');//Numero national pour l'enseignant
            $table->string('nom');
            $table->string('prenom');
            $table->unsignedBigInteger('etablissement_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('etablissement_id')->references('id')->on('etablissements');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administrateurs');
    }
};
