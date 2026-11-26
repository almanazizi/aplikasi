<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    public function run(): void
    {
        $daftarProdi = [
            ['nama_prodi' => 'Teknik Informatika', 'fakultas' => 'Fakultas Teknik'],
            ['nama_prodi' => 'Teknik Sipil', 'fakultas' => 'Fakultas Teknik'],
            ['nama_prodi' => 'Teknik Industri', 'fakultas' => 'Fakultas Teknik'],
            ['nama_prodi' => 'Manajemen', 'fakultas' => 'Fakultas Ekonomi dan Bisnis'],
            ['nama_prodi' => 'Akuntansi', 'fakultas' => 'Fakultas Ekonomi dan Bisnis'],
            ['nama_prodi' => 'Kewirausahaan', 'fakultas' => 'Fakultas Ekonomi dan Bisnis'],
            ['nama_prodi' => 'Pendidikan Guru Sekolah Dasar', 'fakultas' => 'Fakultas Keguruan dan Ilmu Pendidikan'],
            ['nama_prodi' => 'Pendidikan Bahasa Inggris', 'fakultas' => 'Fakultas Keguruan dan Ilmu Pendidikan'],
            ['nama_prodi' => 'Pendidikan Matematika', 'fakultas' => 'Fakultas Keguruan dan Ilmu Pendidikan'],
            ['nama_prodi' => 'Pendidikan Jasmani', 'fakultas' => 'Fakultas Keguruan dan Ilmu Pendidikan'],
            ['nama_prodi' => 'Pendidikan Guru PAUD', 'fakultas' => 'Fakultas Keguruan dan Ilmu Pendidikan'],
            ['nama_prodi' => 'Keperawatan', 'fakultas' => 'Fakultas Ilmu Kesehatan'],
            ['nama_prodi' => 'Kebidanan', 'fakultas' => 'Fakultas Ilmu Kesehatan'],
            ['nama_prodi' => 'Kesehatan Masyarakat', 'fakultas' => 'Fakultas Ilmu Kesehatan'],
            ['nama_prodi' => 'Gizi', 'fakultas' => 'Fakultas Ilmu Kesehatan'],
            ['nama_prodi' => 'Hukum', 'fakultas' => 'Fakultas Hukum'],
            ['nama_prodi' => 'Peternakan', 'fakultas' => 'Fakultas Pertanian dan Peternakan'],
        ];

        foreach ($daftarProdi as $prodi) {
            ProgramStudi::firstOrCreate(
                ['nama_prodi' => $prodi['nama_prodi']],
                ['fakultas' => $prodi['fakultas']]
            );
        }
    }
}
