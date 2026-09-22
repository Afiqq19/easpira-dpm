<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TanggapanPengaduan extends Model
{
    use HasFactory;

    protected $table = 'tanggapan_pengaduan';

    protected $fillable = [
        'pengaduan_id',
        'user_id',
        'isi_tanggapan',
        'tipe',
        'is_internal',
        'lampiran',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
    ];

    // =====================================================================
    // RELASI
    // =====================================================================

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // =====================================================================
    // SCOPES
    // =====================================================================

    public function scopePublik($query)
    {
        return $query->where('is_internal', false);
    }

    public function scopeInternal($query)
    {
        return $query->where('is_internal', true);
    }

    public function scopeDariStaff($query)
    {
        return $query->where('tipe', 'staff');
    }

    public function scopeDariMahasiswa($query)
    {
        return $query->where('tipe', 'mahasiswa');
    }

    // =====================================================================
    // HELPERS
    // =====================================================================

    /**
     * Nama pengirim tanggapan (menyembunyikan nama asli jika perlu)
     */
    public function getNamaPengirimAttribute(): string
    {
        if ($this->tipe === 'sistem') {
            return 'Sistem e-Aspira';
        }

        // Cek jika ini dari staff / pengelola
        $isStaff = ($this->tipe === 'staff') || ($this->user && $this->user->hasAnyRole(['admin', 'staff_dewan', 'direktur', 'wakil_direktur']));

        if ($isStaff) {
            return $this->user?->nama ?? 'Staff DPM';
        }

        // Untuk mahasiswa / pelapor — jika tiket anonim, selalu sembunyikan nama asli!
        if ($this->pengaduan?->mode_privasi === 'anonim') {
            return 'Pelapor (Anonim)';
        }

        return $this->user?->nama ?? 'Mahasiswa';
    }
}
