<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AkunMahasiswa;
use App\Models\Desa;
use App\Models\Dpl;
use App\Models\KeanggotaanKelompok;
use App\Models\KelompokKkn;
use App\Models\KetentuanKelompok;
use App\Models\LaporanAkhir;
use App\Models\Mahasiswa;
use App\Models\PeriodeKkn;
use App\Models\PesertaPeriode;
use App\Models\ProgramStudi;
use App\Models\Proposal;
use App\Models\SuratPengantar;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class Tahap1DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_hanya_ada_14_tabel_spesifikasi_plus_tabel_migrations(): void
    {
        $expectedTables = [
            'admin',
            'akun_mahasiswa',
            'desa',
            'dpl',
            'keanggotaan_kelompok',
            'kelompok_kkn',
            'ketentuan_kelompok',
            'laporan_akhir',
            'mahasiswa',
            'migrations',
            'periode_kkn',
            'peserta_periode',
            'program_studi',
            'proposal',
            'surat_pengantar',
        ];

        $actualTables = collect(Schema::getTableListing(schemaQualified: false))
            ->sort()
            ->values()
            ->all();

        $this->assertSame($expectedTables, $actualTables);
    }

    public function test_kolom_14_tabel_persis_spesifikasi_tanpa_timestamps(): void
    {
        $expectedColumns = [
            'program_studi' => ['prodi_id', 'nama_prodi', 'fakultas'],
            'mahasiswa' => ['mahasiswa_id', 'nim', 'nama_lengkap', 'jenis_kelamin', 'prodi_id'],
            'akun_mahasiswa' => [
                'akun_id',
                'mahasiswa_id',
                'no_whatsapp',
                'password_hash',
                'activation_ticket_hash',
                'ticket_purpose',
                'ticket_expiry',
            ],
            'admin' => ['admin_id', 'nama_admin', 'email', 'password_hash'],
            'periode_kkn' => [
                'periode_id',
                'tahun_akademik',
                'tahap_periode',
                'tanggal_mulai',
                'tanggal_selesai',
                'tenggat_kelompok',
                'tenggat_pengajuan_desa',
                'tenggat_laporan',
                'maks_kelompok',
                'urutan_kelompok_terakhir',
            ],
            'ketentuan_kelompok' => [
                'ketentuan_id',
                'periode_id',
                'jenis_kkn',
                'min_anggota',
                'maks_anggota',
                'min_prodi',
                'maks_laki_laki',
            ],
            'peserta_periode' => ['mahasiswa_id', 'periode_id', 'ukuran_baju'],
            'kelompok_kkn' => [
                'kelompok_id',
                'periode_id',
                'dpl_id',
                'nomor_kelompok',
                'jenis_kkn',
                'kode_gabung',
                'status_kelompok',
                'waktu_pengajuan',
                'catatan_admin',
                'alasan_pengecualian',
                'calon_ketua_id',
                'status_perubahan_anggota',
            ],
            'desa' => ['desa_id', 'nama_desa', 'kecamatan', 'kabupaten_kota'],
            'keanggotaan_kelompok' => ['mahasiswa_id', 'periode_id', 'kelompok_id', 'peran'],
            'proposal' => [
                'proposal_id',
                'kelompok_id',
                'desa_id',
                'nama_file_pdf',
                'id_penyimpanan_pdf',
                'status_proposal',
                'catatan_perbaikan',
                'alasan_pembatalan',
                'waktu_pengajuan',
                'desa_dikunci_admin',
            ],
            'dpl' => ['dpl_id', 'nama_dpl', 'no_whatsapp', 'nidn'],
            'surat_pengantar' => [
                'surat_id',
                'proposal_id',
                'nama_file_pdf',
                'id_penyimpanan_pdf',
                'status_surat',
                'tanggal_unggah',
            ],
            'laporan_akhir' => [
                'laporan_id',
                'kelompok_id',
                'nama_file_pdf',
                'id_penyimpanan_pdf',
                'tanggal_unggah',
            ],
        ];

        foreach ($expectedColumns as $table => $columns) {
            $this->assertSame($columns, Schema::getColumnListing($table), "Kolom tabel {$table} tidak sesuai.");
        }
    }

    public function test_semua_model_tanpa_timestamps_dan_auth_menggunakan_password_hash(): void
    {
        $models = [
            Admin::class,
            AkunMahasiswa::class,
            Desa::class,
            Dpl::class,
            KeanggotaanKelompok::class,
            KelompokKkn::class,
            KetentuanKelompok::class,
            LaporanAkhir::class,
            Mahasiswa::class,
            PeriodeKkn::class,
            PesertaPeriode::class,
            ProgramStudi::class,
            Proposal::class,
            SuratPengantar::class,
        ];

        foreach ($models as $modelClass) {
            $model = new $modelClass;
            $this->assertFalse($model->usesTimestamps(), "{$modelClass} harus tanpa timestamps.");
        }

        $admin = new Admin(['password_hash' => 'hash-admin']);
        $this->assertSame('password_hash', $admin->getAuthPasswordName());
        $this->assertSame('hash-admin', $admin->getAuthPassword());
        $this->assertSame('', $admin->getRememberTokenName());

        $akun = new AkunMahasiswa(['password_hash' => 'hash-mhs']);
        $this->assertSame('password_hash', $akun->getAuthPasswordName());
        $this->assertSame('hash-mhs', $akun->getAuthPassword());
        $this->assertSame('', $akun->getRememberTokenName());
    }

    public function test_seeder_mengisi_admin_prodi_dan_periode_awal_secara_idempoten(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('admin', 1);
        $admin = Admin::where('email', 'admin@kkn.test')->first();
        $this->assertNotNull($admin);
        $this->assertTrue(Hash::check('KknDemo!2026', $admin->password_hash));

        $this->assertDatabaseCount('program_studi', 17);
        $this->assertDatabaseHas('program_studi', [
            'nama_prodi' => 'Teknik Informatika',
            'fakultas' => 'Fakultas Teknik',
        ]);

        $this->assertDatabaseCount('periode_kkn', 1);
        $this->assertDatabaseHas('periode_kkn', [
            'tahun_akademik' => '2026/2027',
            'tahap_periode' => 'persiapan',
        ]);
        $this->assertDatabaseCount('ketentuan_kelompok', 2);
    }
}
