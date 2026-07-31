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
        Schema::table('content_blocks', function (Blueprint $table) {
            $table->string('title')->nullable()->after('key');
        });

        // key devient nullable : les sections libres (type CMS, sans clé fixe côté code)
        // n'en ont pas besoin — évite d'ajouter doctrine/dbal juste pour un ->change().
        DB::statement('ALTER TABLE content_blocks MODIFY `key` VARCHAR(100) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE content_blocks MODIFY `key` VARCHAR(100) NOT NULL');

        Schema::table('content_blocks', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
};
