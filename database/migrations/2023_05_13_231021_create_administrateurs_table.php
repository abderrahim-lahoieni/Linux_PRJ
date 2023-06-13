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
            $table->string('ppr',100)->unique;//Numero national pour l'enseignant
            $table->string('nom',200);
            $table->string('prenom',200);
            $table->unsignedBigInteger('etablissement');
            $table->unsignedBigInteger('id_user');
            $table->foreign('etablissement')->references('id')->on('etablissements')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
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
