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
        Schema::table('personnels', function (Blueprint $table) {
            // Hash bcrypt renvoyé tel quel par l'API ENT (champ "password") — jamais généré ni
            // modifié localement, uniquement vérifié avec Hash::check() à la connexion.
            $table->string('password_hash')->nullable()->after('telephone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personnels', function (Blueprint $table) {
            $table->dropColumn('password_hash');
        });
    }
};
