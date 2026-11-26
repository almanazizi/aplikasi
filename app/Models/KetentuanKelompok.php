<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KetentuanKelompok extends Model
{
    protected $table = 'ketentuan_kelompok';

    protected $primaryKey = 'ketentuan_id';

    public $timestamps = false;

    protected $fillable = [
        'periode_id',
        'jenis_kkn',
        'min_anggota',
        'maks_anggota',
        'min_prodi',
        'maks_laki_laki',
    ];

    protected function casts(): array
    {
        return [
            'min_anggota' => 'integer',
            'maks_anggota' => 'integer',
            'min_prodi' => 'integer',
            'maks_laki_laki' => 'integer',
        ];
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeKkn::class, 'periode_id', 'periode_id');
    }
}
