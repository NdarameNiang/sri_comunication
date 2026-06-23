<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_configs', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');                        // "SRI 2026"
            $table->string('event_slug')->unique();             // "sri-2026"
            $table->text('event_description')->nullable();
            $table->string('organizer')->nullable();            // "DRI – UCAD"
            $table->date('event_start_date')->nullable();
            $table->date('event_end_date')->nullable();
            $table->datetime('submission_open_at')->nullable();  // ouverture dépôts
            $table->datetime('submission_close_at')->nullable(); // clôture dépôts
            $table->boolean('is_active')->default(false);        // config active
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_configs');
    }
};
