<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_collaborators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('nom');
            $table->string('prenom')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('institution')->nullable();
            $table->string('role_collaborateur')->nullable(); // Co-auteur, Chercheur, Ingénieur...
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_collaborators');
    }
};
