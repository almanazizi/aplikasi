<?php

namespace App\Support;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

/**
 * Membatasi percobaan masuk dan penukaran kode (5 kali gagal per menit).
 */
class BatasPercobaan
{
    public const MAKS = 5;

    public static function cek(string $kunci, string $field): void
    {
        if (RateLimiter::tooManyAttempts($kunci, self::MAKS)) {
            $detik = RateLimiter::availableIn($kunci);
            throw ValidationException::withMessages([
                $field => "Terlalu banyak percobaan. Coba lagi dalam {$detik} detik.",
            ]);
        }
    }

    public static function catatGagal(string $kunci): void
    {
        RateLimiter::hit($kunci, 60);
    }

    public static function bersihkan(string $kunci): void
    {
        RateLimiter::clear($kunci);
    }
}
