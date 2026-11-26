<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeanggotaanKelompok extends Model
{
    protected $table = 'keanggotaan_kelompok';

    protected $primaryKey = null;

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'mahasiswa_id',
        'periode_id',
        'kelompok_id',
        'peran',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(KelompokKkn::class, 'kelompok_id', 'kelompok_id');
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeKkn::class, 'periode_id', 'periode_id');
    }
}
