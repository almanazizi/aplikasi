<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratPengantar extends Model
{
    protected $table = 'surat_pengantar';

    protected $primaryKey = 'surat_id';

    public $timestamps = false;

    protected $fillable = [
        'proposal_id',
        'nama_file_pdf',
        'id_penyimpanan_pdf',
        'status_surat',
        'tanggal_unggah',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_unggah' => 'datetime',
        ];
    }

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class, 'proposal_id', 'proposal_id');
    }
}
