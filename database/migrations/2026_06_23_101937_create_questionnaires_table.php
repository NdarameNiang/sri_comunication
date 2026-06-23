<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_config_id')->constrained()->cascadeOnDelete();
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->string('email')->nullable();
            $table->string('institution')->nullable();
            // Notes (1-5)
            $table->unsignedTinyInteger('note_organisation')->nullable();
            $table->unsignedTinyInteger('note_contenu')->nullable();
            $table->unsignedTinyInteger('note_logistique')->nullable();
            $table->unsignedTinyInteger('note_globale')->nullable();
            // Réponses ouvertes
            $table->text('points_positifs')->nullable();
            $table->text('points_amelioration')->nullable();
            $table->text('suggestions')->nullable();
            $table->boolean('recommanderait')->nullable(); // Recommanderiez-vous ?
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questionnaires');
    }
};
