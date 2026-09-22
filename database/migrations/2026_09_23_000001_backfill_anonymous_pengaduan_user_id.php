<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Pengaduan;
use App\Models\IdentitasTerenkripsi;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            $pengaduans = Pengaduan::whereNull('user_id')->get();
            foreach ($pengaduans as $pengaduan) {
                $identitas = IdentitasTerenkripsi::where('pengaduan_id', $pengaduan->id)->first();
                if ($identitas && !empty($identitas->data_terenkripsi)) {
                    try {
                        $decryptedJson = Crypt::decryptString($identitas->data_terenkripsi);
                        $data = json_decode($decryptedJson, true);
                        if (is_array($data) && !empty($data['user_id'])) {
                            $pengaduan->user_id = $data['user_id'];
                        }
                    } catch (\Throwable $e) {
                        Log::warning("Gagal decrypt identitas saat backfill tiket ID {$pengaduan->id}: " . $e->getMessage());
                    }
                }

                if (empty($pengaduan->kode_anonim)) {
                    $pengaduan->kode_anonim = 'ANON-' . strtoupper(substr(md5(uniqid()), 0, 8));
                }

                $pengaduan->saveQuietly();
            }
        } catch (\Throwable $e) {
            Log::error("Error on backfill_anonymous_pengaduan_user_id migration: " . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
