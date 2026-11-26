<?php

namespace App\Services;

use App\Models\AkunMahasiswa;
use App\Models\KeanggotaanKelompok;
use App\Models\KelompokKkn;
use App\Models\PeriodeKkn;
use App\Models\PesertaPeriode;
use App\Models\Proposal;

class ProfilService
{
    public function __construct(private PeriodeService $periode) {}

    public function pesertaPeriode(AkunMahasiswa $akun, ?PeriodeKkn $periode): ?PesertaPeriode
    {
        if (! $periode) {
            return null;
        }

        return PesertaPeriode::where('mahasiswa_id', $akun->mahasiswa_id)
            ->where('periode_id', $periode->periode_id)
            ->first();
    }

    public static function whatsappValid(?string $nomor): bool
    {
        $bersih = preg_replace('/[\s()\-]/', '', (string) $nomor);

        return (bool) preg_match('/^\+?\d{8,16}$/', $bersih);
    }

    /**
     * Profil lengkap = jenis kelamin + ukuran baju + WhatsApp valid (spesifikasi 4.1).
     */
    public function lengkap(AkunMahasiswa $akun, ?PeriodeKkn $periode): bool
    {
        $peserta = $this->pesertaPeriode($akun, $periode);

        return $peserta !== null
            && filled($peserta->ukuran_baju)
            && in_array($akun->mahasiswa?->jenis_kelamin, ['L', 'P'], true)
            && self::whatsappValid($akun->no_whatsapp);
    }

    /**
     * Halaman tujuan mahasiswa setelah masuk (U1 langkah e).
     */
    public function halamanAwal(AkunMahasiswa $akun): string
    {
        $periode = $this->periode->aktif();

        return $this->lengkap($akun, $periode) || $this->periode->ditutup($periode)
            ? route('mahasiswa.kelompok')
            : route('mahasiswa.profil');
    }

    /**
     * Alasan sebuah fitur mahasiswa terkunci; string kosong bila terbuka.
     * Mengikuti studentFeatureLock() di prototipe (workflow.js).
     */
    public function alasanTerkunci(AkunMahasiswa $akun, ?PeriodeKkn $periode, string $fitur): string
    {
        if ($fitur === 'profil') {
            return '';
        }

        $peserta = $this->pesertaPeriode($akun, $periode);
        if (! $peserta) {
            return 'NIM belum tercatat pada periode ini.';
        }

        $ditutup = $this->periode->ditutup($periode);
        if (blank($peserta->ukuran_baju) && ! $ditutup) {
            return 'Lengkapi ukuran baju pada profil.';
        }
        if (! in_array($akun->mahasiswa?->jenis_kelamin, ['L', 'P'], true) && ! $ditutup) {
            return 'Lengkapi jenis kelamin pada profil.';
        }
        if (! in_array($fitur, ['kelompok', 'desa', 'laporan'], true)) {
            return '';
        }

        $keanggotaan = KeanggotaanKelompok::where('mahasiswa_id', $akun->mahasiswa_id)
            ->where('periode_id', $periode->periode_id)
            ->first();
        $kelompok = $keanggotaan ? KelompokKkn::find($keanggotaan->kelompok_id) : null;

        if ($fitur === 'kelompok' || ($fitur === 'desa' && $kelompok)) {
            return '';
        }
        if ($kelompok?->status_kelompok === 'menunggu') {
            return 'Menunggu persetujuan Admin.';
        }
        if (! $kelompok || $kelompok->status_kelompok !== 'disetujui') {
            return $periode->tahap_periode === 'berjalan'
                ? 'Ketua perlu mengajukan kelompok yang memenuhi ketentuan.'
                : 'Admin perlu menyelesaikan penempatan atau persetujuan kelompok.';
        }
        if ($fitur === 'laporan') {
            $status = Proposal::where('kelompok_id', $kelompok->kelompok_id)->value('status_proposal');
            if ($status !== 'disetujui') {
                return 'Menunggu persetujuan proposal oleh Admin.';
            }
        }

        return '';
    }
}
