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
        Schema::table('personnels', function (Blueprint $table) {
            $table->string('email_ucad')->nullable()->after('categorie');
            $table->string('email_personnel')->nullable()->after('email_ucad');
            $table->string('telephone', 20)->nullable()->after('email_personnel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personnels', function (Blueprint $table) {
            $table->dropColumn(['email_ucad', 'email_personnel', 'telephone']);
        });
    }
};
