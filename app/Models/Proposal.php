<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Proposal extends Model
{
    protected $table = 'proposal';

    protected $primaryKey = 'proposal_id';

    public $timestamps = false;

    protected $fillable = [
        'kelompok_id',
        'desa_id',
        'nama_file_pdf',
        'id_penyimpanan_pdf',
        'status_proposal',
        'catatan_perbaikan',
        'alasan_pembatalan',
        'waktu_pengajuan',
        'desa_dikunci_admin',
    ];

    protected function casts(): array
    {
        return [
            'waktu_pengajuan' => 'datetime',
            'desa_dikunci_admin' => 'boolean',
        ];
    }

    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(KelompokKkn::class, 'kelompok_id', 'kelompok_id');
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class, 'desa_id', 'desa_id');
    }

    public function suratPengantar(): HasOne
    {
        return $this->hasOne(SuratPengantar::class, 'proposal_id', 'proposal_id');
    }
}
