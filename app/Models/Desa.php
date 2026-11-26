<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Desa extends Model
{
    protected $table = 'desa';

    protected $primaryKey = 'desa_id';

    public $timestamps = false;

    protected $fillable = [
        'nama_desa',
        'kecamatan',
        'kabupaten_kota',
    ];

    public function proposal(): HasMany
    {
        return $this->hasMany(Proposal::class, 'desa_id', 'desa_id');
    }
}
