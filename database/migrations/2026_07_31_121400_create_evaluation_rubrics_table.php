<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_rubrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_config_id')->constrained()->cascadeOnDelete();
            $table->enum('submission_template', ['standard', 'approfondi']);
            $table->string('name', 150)->nullable();
            $table->timestamps();

            $table->unique(['event_config_id', 'submission_template']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_rubrics');
    }
};
