<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function programKerja()
    {
        return $this->hasMany(ProgramKerja::class);
    }
}
