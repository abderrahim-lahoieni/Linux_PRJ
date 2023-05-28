<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

     //Create table enseignants
    public function up(): void
    {
        Schema::create('Enseignant', function (Blueprint $table) {
            $table->id();
            $table->string('ppr')->unique;
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance');
            $table->unsignedBigInteger('etablissement');
            $table->unsignedBigInteger('id_grade');
            $table->unsignedBigInteger('id_user');
            $table->boolean('etat');
            $table->foreign('etablissement')->references('id')->on('Etablissement')->onDelete('cascade');
            $table->foreign('id_grade')->references('id')->on('Grade')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('Users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Enseignant');
    }
};
