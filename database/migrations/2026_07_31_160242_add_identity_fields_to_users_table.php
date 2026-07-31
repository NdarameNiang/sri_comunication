<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('numero_carte')->nullable()->after('structure_id');
            $table->string('matricule')->nullable()->after('numero_carte');
            $table->string('cin')->nullable()->after('matricule');
            $table->string('population_category')->nullable()->after('cin');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['numero_carte', 'matricule', 'cin', 'population_category']);
        });
    }
};
