<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('event_configs', function (Blueprint $table) {
            $table->string('deliberation_mode', 20)->default('individuelle')->after('evaluation_enabled');
        });

        // Comportement historique préservé pour les événements déjà créés : chaque évaluateur
        // note indépendamment et la moyenne est calculée (comme aujourd'hui), le mode "globale"
        // (une note unique partagée par projet) est un nouveau choix opt-in.
        DB::table('event_configs')->update(['deliberation_mode' => 'individuelle']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_configs', function (Blueprint $table) {
            $table->dropColumn('deliberation_mode');
        });
    }
};
