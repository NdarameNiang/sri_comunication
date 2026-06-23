<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_options', function (Blueprint $table) {
            $table->id();
            $table->string('group');        // scientific_domain, project_type, maturity_level, etc.
            $table->string('label');        // libellé affiché
            $table->string('value');        // valeur en base (slug)
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['group', 'value']);
            $table->index('group');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_options');
    }
};
