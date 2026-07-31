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
        Schema::create('project_approfondi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->unique()->constrained('projects')->cascadeOnDelete();

            // Section A : carte d'identité
            $table->string('laboratoire_nom')->nullable();
            $table->string('laboratoire_acronyme', 50)->nullable();
            $table->string('laboratoire_site_web')->nullable();
            $table->string('responsable_titre')->nullable();
            $table->string('responsable_fonction')->nullable();
            $table->text('titre_complet')->nullable();
            $table->string('acronyme_projet', 50)->nullable();
            $table->json('sous_domaines')->nullable();
            $table->json('mots_cles')->nullable();
            $table->date('date_demarrage')->nullable();
            $table->string('duree_prevue', 100)->nullable();

            // Section B : description scientifique et innovation
            $table->text('contexte_etat_art')->nullable();      // B1
            $table->text('approche_methodologique')->nullable(); // B3
            $table->text('caractere_innovant')->nullable();      // B4

            // Section C : résultats consolidés
            $table->text('resultats_scientifiques')->nullable(); // C1
            $table->text('resultats_techniques')->nullable();    // C2
            $table->text('indicateurs_chiffres')->nullable();    // C3

            // Section D : maturité et valorisation
            $table->string('trl_level', 100)->nullable();
            $table->json('voies_valorisation')->nullable();
            $table->text('propriete_intellectuelle')->nullable(); // D1
            $table->text('modele_economique')->nullable();        // D2
            $table->text('partenariats_financement')->nullable(); // D3

            // Section E : impact
            $table->json('dimensions_impact')->nullable();
            $table->text('beneficiaires')->nullable();            // E1
            $table->text('indicateurs_impact')->nullable();       // E2
            $table->text('contribution_odd')->nullable();         // E3
            $table->text('pertinence_senegal_afrique')->nullable(); // E4

            // Section F : présentation SRI (F2 réutilise projects.logistic_needs)
            $table->text('public_cible_vise')->nullable();  // F1
            $table->text('supports_prevus')->nullable();    // F3

            // Section G : annexes
            $table->json('annexes_checklist')->nullable();
            $table->string('annexes_autres_texte', 500)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_approfondi_details');
    }
};
