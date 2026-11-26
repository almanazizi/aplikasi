<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramStudi extends Model
{
    protected $table = 'program_studi';

    protected $primaryKey = 'prodi_id';

    public $timestamps = false;

    protected $fillable = [
        'nama_prodi',
        'fakultas',
    ];

    public function mahasiswa(): HasMany
    {
        return $this->hasMany(Mahasiswa::class, 'prodi_id', 'prodi_id');
    }
}
