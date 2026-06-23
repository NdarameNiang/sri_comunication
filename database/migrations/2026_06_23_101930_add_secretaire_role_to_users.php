<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin','direction_recherche','point_focal','porteur_projet','comite_scientifique','secretaire') DEFAULT 'porteur_projet'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin','direction_recherche','point_focal','porteur_projet','comite_scientifique') DEFAULT 'porteur_projet'");
    }
};
