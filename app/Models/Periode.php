<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Periode extends Model
{
    use HasFactory;

    protected $fillable = ['nama'];

    public function programKerja()
    {
        return $this->hasMany(ProgramKerja::class);
    }
}
