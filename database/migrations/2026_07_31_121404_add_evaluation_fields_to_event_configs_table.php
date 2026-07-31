<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_configs', function (Blueprint $table) {
            $table->unsignedInteger('selection_quota')->nullable()->after('max_projects_per_structure');
            $table->timestamp('evaluation_open_at')->nullable()->after('selection_quota');
            $table->timestamp('evaluation_close_at')->nullable()->after('evaluation_open_at');
        });
    }

    public function down(): void
    {
        Schema::table('event_configs', function (Blueprint $table) {
            $table->dropColumn(['selection_quota', 'evaluation_open_at', 'evaluation_close_at']);
        });
    }
};
