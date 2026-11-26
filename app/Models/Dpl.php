<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dpl extends Model
{
    protected $table = 'dpl';

    protected $primaryKey = 'dpl_id';

    public $timestamps = false;

    protected $fillable = [
        'nama_dpl',
        'no_whatsapp',
        'nidn',
    ];

    public function kelompok(): HasMany
    {
        return $this->hasMany(KelompokKkn::class, 'dpl_id', 'dpl_id');
    }
}
