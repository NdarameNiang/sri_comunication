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
        Schema::table('project_approfondi_details', function (Blueprint $table) {
            // Lien externe (YouTube, Drive…) vers les photos/vidéos du prototype — la case
            // "Photos / vidéos du prototype" de la checklist annexes n'avait aucun moyen de
            // fournir le contenu réel, seulement de déclarer son existence.
            $table->string('media_link')->nullable()->after('annexes_autres_texte');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_approfondi_details', function (Blueprint $table) {
            $table->dropColumn('media_link');
        });
    }
};
