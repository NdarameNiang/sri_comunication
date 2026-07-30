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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('scientific_domain_autre')->nullable()->after('scientific_domain');
            $table->string('project_types_autres', 500)->nullable()->after('project_types');
            $table->string('maturity_level_autre')->nullable()->after('maturity_level');
            $table->string('impact_types_autres', 500)->nullable()->after('impact_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'scientific_domain_autre',
                'project_types_autres',
                'maturity_level_autre',
                'impact_types_autres',
            ]);
        });
    }
};
