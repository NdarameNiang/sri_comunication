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
            $table->boolean('evaluation_enabled')->default(true)->after('allow_public_submission');
        });

        // MySQL n'applique pas toujours le DEFAULT aux lignes déjà existantes lors d'un ADD
        // COLUMN — on force explicitement la valeur pour ne rien changer au comportement
        // historique (évaluation active) des événements créés avant ce chantier.
        DB::table('event_configs')->update(['evaluation_enabled' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_configs', function (Blueprint $table) {
            $table->dropColumn('evaluation_enabled');
        });
    }
};
