<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Convertir ENUM -> VARCHAR pour permettre des rôles dynamiques
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) NOT NULL DEFAULT 'porteur_projet'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin','direction_recherche','point_focal','porteur_projet','comite_scientifique','secretaire') NOT NULL DEFAULT 'porteur_projet'");
    }
};
