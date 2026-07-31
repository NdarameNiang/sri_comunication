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
        Schema::table('roles', function (Blueprint $table) {
            $table->boolean('is_system')->default(false)->after('label');
        });

        \Illuminate\Support\Facades\DB::table('roles')
            ->whereIn('name', ['superadmin', 'direction_recherche', 'comite_scientifique', 'secretaire', 'point_focal', 'porteur_projet'])
            ->update(['is_system' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('is_system');
        });
    }
};
