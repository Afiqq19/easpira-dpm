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
        Schema::table('program_kerja', function (Blueprint $table) {
            $table->foreignId('periode_id')->nullable()->constrained('periodes')->nullOnDelete();
        });

        Schema::table('kegiatan', function (Blueprint $table) {
            $table->foreignId('program_kerja_id')->nullable()->constrained('program_kerja')->cascadeOnDelete();
        });

        // Insert default Periode and assign it to existing Program Kerja
        $periodeId = \Illuminate\Support\Facades\DB::table('periodes')->insertGetId([
            'nama' => '2023/2024',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        \Illuminate\Support\Facades\DB::table('program_kerja')->update(['periode_id' => $periodeId]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->dropForeign(['program_kerja_id']);
            $table->dropColumn('program_kerja_id');
        });

        Schema::table('program_kerja', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');
        });
    }
};
