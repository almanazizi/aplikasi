<?php

namespace App\Services;

use App\Models\AkunMahasiswa;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Kode aktivasi (7 hari) dan kode pemulihan (30 menit) berformat XXXX-XXXX-XXXX.
 * Yang disimpan hanya hash-nya; satu tiket aktif per akun (spesifikasi 3.1 c).
 */
class KodeTiketService
{
    public const PESAN_KODE_SALAH = 'NIM atau kode tidak sesuai, sudah digunakan, atau sudah kedaluwarsa.';

    public static function normalisasi(string $kode): string
    {
        return strtoupper((string) preg_replace('/[\s\-]/', '', $kode));
    }

    /**
     * Menerbitkan kode baru (menimpa tiket lama) dan mengembalikan kode dalam bentuk terbaca.
     * Dipakai pada Tahap 4 (U34 kode pemulihan, U38 kode aktivasi).
     */
    public function terbitkan(AkunMahasiswa $akun, string $tujuan): string
    {
        $alfabet = config('kkn.alfabet_kode');
        $rahasia = '';
        for ($i = 0; $i < 12; $i++) {
            $rahasia .= $alfabet[random_int(0, strlen($alfabet) - 1)];
        }

        $akun->forceFill([
            'activation_ticket_hash' => Hash::make($rahasia),
            'ticket_purpose' => $tujuan,
            'ticket_expiry' => $tujuan === 'aktivasi'
                ? now()->addDays(config('kkn.masa_berlaku_aktivasi_hari'))
                : now()->addMinutes(config('kkn.masa_berlaku_pemulihan_menit')),
        ])->save();

        return implode('-', str_split($rahasia, 4));
    }

    /**
     * U4 + U5: memeriksa NIM dan kode, lalu menyimpan kata sandi baru.
     * Untuk aktivasi (U2) sekaligus menyimpan jenis kelamin. Kode langsung tidak berlaku lagi.
     */
    public function tukarkan(string $nim, string $kode, string $tujuan, string $kataSandi, ?string $jenisKelamin = null): AkunMahasiswa
    {
        return DB::transaction(function () use ($nim, $kode, $tujuan, $kataSandi, $jenisKelamin) {
            $mahasiswa = Mahasiswa::where('nim', trim($nim))->first();
            $akun = $mahasiswa
                ? AkunMahasiswa::where('mahasiswa_id', $mahasiswa->mahasiswa_id)->lockForUpdate()->first()
                : null;

            $statusSesuai = $tujuan === 'aktivasi'
                ? $akun?->password_hash === null
                : $akun?->password_hash !== null;

            if (! $akun
                || ! $statusSesuai
                || $akun->ticket_purpose !== $tujuan
                || ! $akun->ticket_expiry
                || $akun->ticket_expiry->isPast()
                || ! $akun->activation_ticket_hash
                || ! Hash::check(self::normalisasi($kode), $akun->activation_ticket_hash)) {
                throw ValidationException::withMessages(['code' => self::PESAN_KODE_SALAH]);
            }

            $akun->forceFill([
                'password_hash' => Hash::make($kataSandi),
                'activation_ticket_hash' => null,
                'ticket_purpose' => null,
                'ticket_expiry' => null,
            ])->save();

            if ($tujuan === 'aktivasi') {
                $mahasiswa->forceFill(['jenis_kelamin' => $jenisKelamin])->save();
            }

            return $akun->setRelation('mahasiswa', $mahasiswa);
        });
    }
}
