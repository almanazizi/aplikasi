<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodeKkn extends Model
{
    protected $table = 'periode_kkn';

    protected $primaryKey = 'periode_id';

    public $timestamps = false;

    protected $fillable = [
        'tahun_akademik',
        'tahap_periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'tenggat_kelompok',
        'tenggat_pengajuan_desa',
        'tenggat_laporan',
        'maks_kelompok',
        'urutan_kelompok_terakhir',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'tenggat_kelompok' => 'date',
            'tenggat_pengajuan_desa' => 'date',
            'tenggat_laporan' => 'date',
            'maks_kelompok' => 'integer',
            'urutan_kelompok_terakhir' => 'integer',
        ];
    }

    public function ketentuanKelompok(): HasMany
    {
        return $this->hasMany(KetentuanKelompok::class, 'periode_id', 'periode_id');
    }

    public function peserta(): HasMany
    {
        return $this->hasMany(PesertaPeriode::class, 'periode_id', 'periode_id');
    }

    public function kelompok(): HasMany
    {
        return $this->hasMany(KelompokKkn::class, 'periode_id', 'periode_id');
    }
}
