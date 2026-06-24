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
        Schema::table('event_configs', function (Blueprint $table) {
            $table->timestamp('inscription_open_at')->nullable()->after('submission_close_at');
            $table->timestamp('inscription_close_at')->nullable()->after('inscription_open_at');
        });
    }

    public function down(): void
    {
        Schema::table('event_configs', function (Blueprint $table) {
            $table->dropColumn(['inscription_open_at', 'inscription_close_at']);
        });
    }
};
