<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AkunMahasiswa extends Authenticatable
{
    protected $table = 'akun_mahasiswa';

    protected $primaryKey = 'akun_id';

    public $timestamps = false;

    protected $fillable = [
        'mahasiswa_id',
        'no_whatsapp',
        'password_hash',
        'activation_ticket_hash',
        'ticket_purpose',
        'ticket_expiry',
    ];

    protected $hidden = [
        'password_hash',
        'activation_ticket_hash',
    ];

    protected function casts(): array
    {
        return [
            'ticket_expiry' => 'datetime',
        ];
    }

    public function getAuthPassword(): string
    {
        return (string) $this->password_hash;
    }

    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    public function getRememberTokenName(): string
    {
        return '';
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id', 'mahasiswa_id');
    }
}
