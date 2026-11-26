<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $primaryKey = 'mahasiswa_id';

    public $timestamps = false;

    protected $fillable = [
        'nim',
        'nama_lengkap',
        'jenis_kelamin',
        'prodi_id',
    ];

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id', 'prodi_id');
    }

    public function akun(): HasOne
    {
        return $this->hasOne(AkunMahasiswa::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    public function pesertaPeriode(): HasMany
    {
        return $this->hasMany(PesertaPeriode::class, 'mahasiswa_id', 'mahasiswa_id');
    }
}
