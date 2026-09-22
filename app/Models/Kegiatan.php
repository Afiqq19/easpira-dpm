<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Kegiatan extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'kegiatan';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'organisasi_id',
        'program_kerja_id',
        'user_id',
        'poster',
        'kontak_penanggung_jawab',
        'no_kontak',
        'is_published',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'is_published' => 'boolean',
    ];

    // =====================================================================
    // ACTIVITY LOG
    // =====================================================================

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['judul', 'tanggal_mulai', 'tanggal_selesai', 'lokasi', 'is_published'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Kegiatan \"{$this->judul}\" di-{$eventName}");
    }

    // =====================================================================
    // RELASI
    // =====================================================================

    public function programKerja()
    {
        return $this->belongsTo(ProgramKerja::class);
    }

    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // =====================================================================
    // SCOPES
    // =====================================================================

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeAkanDatang($query)
    {
        return $query->where('tanggal_mulai', '>=', now());
    }

    public function scopeSudahLewat($query)
    {
        return $query->where('tanggal_mulai', '<', now());
    }

    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal_mulai', now()->month)
            ->whereYear('tanggal_mulai', now()->year);
    }

    // =====================================================================
    // HELPERS - FORMAT UNTUK FULLCALENDAR.JS
    // =====================================================================

    /**
     * Format data kegiatan untuk FullCalendar.js API
     */
    public function toCalendarEvent(): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->judul,
            'start'          => $this->tanggal_mulai?->toIso8601String(),
            'end'            => $this->tanggal_selesai?->toIso8601String(),
            'description'    => $this->deskripsi,
            'location'       => $this->lokasi,
            'organisasi'     => $this->organisasi?->nama,
            'organisasi_id'  => $this->organisasi_id,
            'color'          => $this->getWarna(),
            'url'            => route('kegiatan.show', $this->id),
        ];
    }

    /**
     * Warna event berdasarkan jenis organisasi
     */
    private function getWarna(): string
    {
        if (!$this->organisasi_id) {
            return '#1e40af'; // DPM - biru tua
        }
        return match ($this->organisasi?->tipe) {
            'HMPS' => '#059669', // hijau
            'UKM'  => '#7c3aed', // ungu
            default => '#6b7280', // abu
        };
    }
}
