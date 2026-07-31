<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('average_score', 5, 2)->nullable()->after('email_sent_at');
            $table->unsignedInteger('rank_position')->nullable()->after('average_score');
            $table->enum('evaluation_status', ['pending', 'included', 'excluded', 'tied_pending'])
                ->default('pending')->after('rank_position');
            $table->timestamp('evaluation_finalized_at')->nullable()->after('evaluation_status');
            $table->foreignId('evaluation_decided_by')->nullable()->after('evaluation_finalized_at')
                ->constrained('users')->nullOnDelete();
            $table->timestamp('evaluation_decided_at')->nullable()->after('evaluation_decided_by');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropConstrainedForeignId('evaluation_decided_by');
            $table->dropColumn([
                'average_score', 'rank_position', 'evaluation_status',
                'evaluation_finalized_at', 'evaluation_decided_at',
            ]);
        });
    }
};
