<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('numero_carte')->unique();
            $table->string('cin')->nullable()->index();
            $table->string('nom');
            $table->string('prenom');
            // Inscription courante (dénormalisée depuis l'API — pas besoin de la liste
            // complète des formations pour valider une inscription publique)
            $table->string('annee')->nullable();
            $table->string('cycle')->nullable();
            $table->string('formation')->nullable();
            $table->string('structure')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
