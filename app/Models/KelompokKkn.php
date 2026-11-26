<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class KelompokKkn extends Model
{
    protected $table = 'kelompok_kkn';

    protected $primaryKey = 'kelompok_id';

    public $timestamps = false;

    protected $fillable = [
        'periode_id',
        'dpl_id',
        'nomor_kelompok',
        'jenis_kkn',
        'kode_gabung',
        'status_kelompok',
        'waktu_pengajuan',
        'catatan_admin',
        'alasan_pengecualian',
        'calon_ketua_id',
        'status_perubahan_anggota',
    ];

    protected function casts(): array
    {
        return [
            'nomor_kelompok' => 'integer',
            'waktu_pengajuan' => 'datetime',
        ];
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeKkn::class, 'periode_id', 'periode_id');
    }

    public function dpl(): BelongsTo
    {
        return $this->belongsTo(Dpl::class, 'dpl_id', 'dpl_id');
    }

    public function calonKetua(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'calon_ketua_id', 'mahasiswa_id');
    }

    public function keanggotaan(): HasMany
    {
        return $this->hasMany(KeanggotaanKelompok::class, 'kelompok_id', 'kelompok_id');
    }

    public function proposal(): HasOne
    {
        return $this->hasOne(Proposal::class, 'kelompok_id', 'kelompok_id');
    }

    public function laporanAkhir(): HasOne
    {
        return $this->hasOne(LaporanAkhir::class, 'kelompok_id', 'kelompok_id');
    }
}
