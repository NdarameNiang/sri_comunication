<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('project_assignments')->cascadeOnDelete();
            $table->foreignId('porteur_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('structure_id')->constrained()->cascadeOnDelete();
            // Informations générales
            $table->string('responsable_nom');
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            // Identification du projet
            $table->string('scientific_domain');
            $table->json('project_types');
            // Description synthétique
            $table->text('summary');
            $table->text('problematic');
            $table->text('solution');
            // Résultats et valorisation
            $table->text('results')->nullable();
            $table->string('maturity_level')->nullable();
            $table->json('protection_types')->nullable();
            $table->string('protection_autres')->nullable();
            $table->json('valorisation_types')->nullable();
            $table->string('valorisation_autres')->nullable();
            // Impact
            $table->json('impact_types')->nullable();
            // Présentation
            $table->json('presentation_formats')->nullable();
            $table->string('presentation_autres')->nullable();
            $table->text('logistic_needs')->nullable();
            // Statut
            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->boolean('selected')->default(false);
            $table->timestamp('selected_at')->nullable();
            $table->foreignId('selected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('email_sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
