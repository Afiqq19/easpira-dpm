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
        Schema::table('organisasi', function (Blueprint $table) {
            $table->foreignId('active_periode_id')->nullable()->constrained('periodes')->nullOnDelete();
        });

        Schema::table('periodes', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periodes', function (Blueprint $table) {
            $table->boolean('is_active')->default(false);
        });

        Schema::table('organisasi', function (Blueprint $table) {
            $table->dropForeign(['active_periode_id']);
            $table->dropColumn('active_periode_id');
        });
    }
};
