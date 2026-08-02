<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_configs', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('organizer');
            $table->string('logo_white_path')->nullable()->after('logo_path');
            $table->string('hero_image_path')->nullable()->after('logo_white_path');
            $table->string('primary_color', 7)->nullable()->after('hero_image_path');
        });
    }

    public function down(): void
    {
        Schema::table('event_configs', function (Blueprint $table) {
            $table->dropColumn(['logo_path', 'logo_white_path', 'hero_image_path', 'primary_color']);
        });
    }
};
