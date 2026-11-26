<?php

namespace Database\Seeders;

use App\Models\KetentuanKelompok;
use App\Models\PeriodeKkn;
use Illuminate\Database\Seeder;

class PeriodeSeeder extends Seeder
{
    public function run(): void
    {
        $periode = PeriodeKkn::firstOrCreate(
            ['tahun_akademik' => '2026/2027'],
            [
                'tahap_periode' => 'persiapan',
                'tanggal_mulai' => '2026-12-07',
                'tanggal_selesai' => '2027-01-06',
                'tenggat_kelompok' => '2026-11-30',
                'tenggat_pengajuan_desa' => null,
                'tenggat_laporan' => '2027-01-15',
                'maks_kelompok' => null,
                'urutan_kelompok_terakhir' => 0,
            ]
        );

        foreach (config('kkn.ketentuan_default') as $jenis => $aturan) {
            KetentuanKelompok::firstOrCreate(
                [
                    'periode_id' => $periode->periode_id,
                    'jenis_kkn' => $jenis,
                ],
                $aturan
            );
        }
    }
}
