<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Organisasi extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'organisasi';

    protected $fillable = [
        'nama',
        'tipe',
        'prodi_terkait',
        'singkatan',
        'deskripsi',
        'logo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // =====================================================================
    // ACTIVITY LOG
    // =====================================================================

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Organisasi {$this->nama} di-{$eventName}");
    }

    // =====================================================================
    // RELASI
    // =====================================================================

    /**
     * User (akun) yang tergabung dalam organisasi ini
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Pengumuman yang dibuat oleh organisasi ini
     */
    public function pengumumans()
    {
        return $this->hasMany(Pengumuman::class);
    }

    /**
     * Kegiatan yang dibuat oleh organisasi ini
     */
    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class);
    }
    
    /**
     * Program Kerja yang dibuat oleh organisasi ini
     */
    public function programKerja()
    {
        return $this->hasMany(ProgramKerja::class);
    }

    /**
     * Periode kepengurusan yang sedang aktif untuk organisasi ini
     */
    public function activePeriode()
    {
        return $this->belongsTo(Periode::class, 'active_periode_id');
    }

    // =====================================================================
    // SCOPES
    // =====================================================================

    public function scopeHMPS($query)
    {
        return $query->where('tipe', 'HMPS');
    }

    public function scopeUKM($query)
    {
        return $query->where('tipe', 'UKM');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // =====================================================================
    // ACCESSORS
    // =====================================================================

    public function getNamaLengkapAttribute(): string
    {
        return $this->singkatan
            ? "{$this->singkatan} - {$this->nama}"
            : $this->nama;
    }

    /**
     * Mendapatkan palet warna berdasarkan tipe dan ID organisasi.
     * UKM & HMPS masing-masing punya warna unik.
     * DPM/Admin => Ungu
     */
    public function getWarnaAttribute(): array
    {
        // Warna untuk DPM (tidak ada organisasi)
        $defaultDPM = [
            'bg'     => 'bg-violet-700',
            'light'  => 'bg-violet-100',
            'text'   => 'text-violet-700',
            'badge'  => 'bg-violet-700 text-white',
            'border' => 'border-violet-300',
            'hex'    => '#6d28d9',
        ];

        if (!$this->id) return $defaultDPM;

        // Palet warna untuk HMPS (berbeda per ID)
        $hmpsColors = [
            ['bg'=>'bg-emerald-600','light'=>'bg-emerald-100','text'=>'text-emerald-700','badge'=>'bg-emerald-600 text-white','border'=>'border-emerald-300','hex'=>'#059669'],
            ['bg'=>'bg-sky-600',    'light'=>'bg-sky-100',    'text'=>'text-sky-700',    'badge'=>'bg-sky-600 text-white',    'border'=>'border-sky-300',    'hex'=>'#0284c7'],
            ['bg'=>'bg-amber-500',  'light'=>'bg-amber-100',  'text'=>'text-amber-700',  'badge'=>'bg-amber-500 text-white',  'border'=>'border-amber-300',  'hex'=>'#d97706'],
            ['bg'=>'bg-rose-600',   'light'=>'bg-rose-100',   'text'=>'text-rose-700',   'badge'=>'bg-rose-600 text-white',   'border'=>'border-rose-300',   'hex'=>'#e11d48'],
            ['bg'=>'bg-teal-600',   'light'=>'bg-teal-100',   'text'=>'text-teal-700',   'badge'=>'bg-teal-600 text-white',   'border'=>'border-teal-300',   'hex'=>'#0d9488'],
            ['bg'=>'bg-orange-500', 'light'=>'bg-orange-100', 'text'=>'text-orange-700', 'badge'=>'bg-orange-500 text-white', 'border'=>'border-orange-300', 'hex'=>'#ea580c'],
        ];

        // Palet warna untuk UKM (berbeda per ID)
        $ukmColors = [
            ['bg'=>'bg-fuchsia-600','light'=>'bg-fuchsia-100','text'=>'text-fuchsia-700','badge'=>'bg-fuchsia-600 text-white','border'=>'border-fuchsia-300','hex'=>'#c026d3'],
            ['bg'=>'bg-cyan-600',   'light'=>'bg-cyan-100',   'text'=>'text-cyan-700',   'badge'=>'bg-cyan-600 text-white',   'border'=>'border-cyan-300',   'hex'=>'#0891b2'],
            ['bg'=>'bg-lime-600',   'light'=>'bg-lime-100',   'text'=>'text-lime-700',   'badge'=>'bg-lime-600 text-white',   'border'=>'border-lime-300',   'hex'=>'#65a30d'],
            ['bg'=>'bg-pink-600',   'light'=>'bg-pink-100',   'text'=>'text-pink-700',   'badge'=>'bg-pink-600 text-white',   'border'=>'border-pink-300',   'hex'=>'#db2777'],
        ];

        if ($this->tipe === 'HMPS') {
            return $hmpsColors[($this->id - 1) % count($hmpsColors)];
        }

        if ($this->tipe === 'UKM') {
            return $ukmColors[($this->id - 1) % count($ukmColors)];
        }

        return $defaultDPM;
    }
}
