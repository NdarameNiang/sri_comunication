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
        Schema::table('students', function (Blueprint $table) {
            // Temporaire : StudentCenter n'expose encore aucun mot de passe dans son API.
            // password_hash contient un SHA1(numero_carte) généré côté sync en attendant que
            // l'UCAD ajoute le vrai champ — à remplacer dès qu'il sera disponible, sans
            // changement de schéma (même colonne, juste une source différente).
            $table->string('password_hash')->nullable()->after('telephone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('password_hash');
        });
    }
};
