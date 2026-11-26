<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanAkhir extends Model
{
    protected $table = 'laporan_akhir';

    protected $primaryKey = 'laporan_id';

    public $timestamps = false;

    protected $fillable = [
        'kelompok_id',
        'nama_file_pdf',
        'id_penyimpanan_pdf',
        'tanggal_unggah',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_unggah' => 'datetime',
        ];
    }

    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(KelompokKkn::class, 'kelompok_id', 'kelompok_id');
    }
}
