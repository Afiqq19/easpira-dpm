<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\CausesActivity;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, CausesActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'username',
        'nim',
        'prodi',
        'nama',
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'organisasi_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // =====================================================================
    // RELASI
    // =====================================================================

    /**
     * Organisasi tempat user bernaung (HMPS/UKM)
     */
    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class);
    }

    /**
     * Pengaduan yang dibuat user ini
     */
    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class);
    }

    /**
     * Tanggapan yang diberikan user ini
     */
    public function tanggapanPengaduans()
    {
        return $this->hasMany(TanggapanPengaduan::class);
    }

    /**
     * Pengumuman yang dibuat user ini
     */
    public function pengumumans()
    {
        return $this->hasMany(Pengumuman::class);
    }

    /**
     * Kegiatan yang dibuat user ini
     */
    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class);
    }

    // =====================================================================
    // HELPERS
    // =====================================================================

    /**
     * Cek apakah user adalah Admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Cek apakah user adalah Staff Dewan
     */
    public function isStaffDewan(): bool
    {
        return $this->hasRole('staff_dewan');
    }

    /**
     * Cek apakah user adalah HMPS
     */
    public function isHMPS(): bool
    {
        return $this->hasRole('hmps');
    }

    /**
     * Cek apakah user adalah UKM
     */
    public function isUKM(): bool
    {
        return $this->hasRole('ukm');
    }

    /**
     * Cek apakah user adalah Mahasiswa
     */
    public function isMahasiswa(): bool
    {
        return $this->hasRole('mahasiswa');
    }

    /**
     * Cek apakah user punya permission penanganan kasus sensitif
     */
    public function dapatMenanganiKasusSensitif(): bool
    {
        return $this->can('penanganan_kasus_sensitif');
    }

    /**
     * Cek apakah user adalah Direktur
     */
    public function isDirektur(): bool
    {
        return $this->hasRole('direktur');
    }

    /**
     * Cek apakah user adalah Wakil Direktur
     */
    public function isWakilDirektur(): bool
    {
        return $this->hasRole('wakil_direktur');
    }

    /**
     * Cek apakah user adalah pimpinan eksekutif
     */
    public function isEksekutif(): bool
    {
        return $this->hasAnyRole(['direktur', 'wakil_direktur']);
    }

    /**
     * Scope untuk user aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }
}
