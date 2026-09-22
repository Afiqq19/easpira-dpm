<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramKerja extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'program_kerja';

    protected $fillable = [
        'nama',
        'deskripsi',
        'organisasi_id',
        'user_id',
        'periode_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'kategori',
        'kategori_lainnya',
        'is_active',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
    ];

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class);
    }

    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class);
    }

    public function pendaftar()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function evaluasi()
    {
        return $this->hasMany(EvaluasiProker::class, 'program_kerja_id');
    }
}
