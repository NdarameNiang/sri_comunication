<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_score_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_score_id')->constrained()->cascadeOnDelete();
            $table->foreignId('evaluation_criterion_id')->constrained('evaluation_criteria')->cascadeOnDelete();
            $table->decimal('points', 5, 2)->default(0);
            $table->timestamps();

            $table->unique(['project_score_id', 'evaluation_criterion_id'], 'score_criterion_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_score_details');
    }
};
