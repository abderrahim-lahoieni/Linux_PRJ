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
        Schema::create('enseignants', function (Blueprint $table) {
            $table->id();
            $table->string('ppr')->unique;
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance');
            $table->unsignedBigInteger('etablissement');
            $table->unsignedBigInteger('id_grade');
            $table->unsignedBigInteger('id_user');
            $table->foreign('etablissement')->references('id')->on('etablissements')->onDelete('cascade');
            $table->foreign('id_grade')->references('id')->on('grades')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enseignants');
    }
};
