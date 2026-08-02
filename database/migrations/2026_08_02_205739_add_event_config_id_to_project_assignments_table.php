<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_assignments', function (Blueprint $table) {
            $table->foreignId('event_config_id')->nullable()->after('structure_id')
                ->constrained()->restrictOnDelete();
        });

        // Rattache les affectations existantes à l'événement actif (ou, à défaut, au plus
        // récent) avant de verrouiller la colonne en NOT NULL — round-trip déjà utilisé dans
        // ce projet (ex. make_content_blocks_sections_generic).
        $eventId = DB::table('event_configs')->where('is_active', true)->value('id')
            ?? DB::table('event_configs')->orderByDesc('id')->value('id');

        if ($eventId) {
            DB::table('project_assignments')->whereNull('event_config_id')->update(['event_config_id' => $eventId]);
        }

        DB::statement('ALTER TABLE project_assignments MODIFY event_config_id BIGINT UNSIGNED NOT NULL');
    }

    public function down(): void
    {
        Schema::table('project_assignments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('event_config_id');
        });
    }
};
