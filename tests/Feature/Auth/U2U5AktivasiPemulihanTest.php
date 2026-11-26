<?php

namespace Tests\Feature\Auth;

use App\Models\AkunMahasiswa;
use App\Models\Mahasiswa;
use App\Models\PeriodeKkn;
use App\Models\ProgramStudi;
use App\Services\KodeTiketService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * U2 Aktifkan akun, U3 Pulihkan akun, U4 NIM + kode, U5 Buat kata sandi.
 */
class U2U5AktivasiPemulihanTest extends TestCase
{
    use RefreshDatabase;

    private const PESAN_KODE = 'NIM atau kode tidak sesuai, sudah digunakan, atau sudah kedaluwarsa.';

    protected function setUp(): void
    {
        parent::setUp();
        PeriodeKkn::create(['tahun_akademik' => '2026/2027', 'tahap_periode' => 'persiapan']);
    }

    private function akun(bool $aktif = false): AkunMahasiswa
    {
        $prodi = ProgramStudi::create(['nama_prodi' => 'Keperawatan', 'fakultas' => 'Fakultas Ilmu Kesehatan']);
        $mhs = Mahasiswa::create(['nim' => '2201002', 'nama_lengkap' => 'Dimas Lubis', 'prodi_id' => $prodi->prodi_id]);

        return AkunMahasiswa::create([
            'mahasiswa_id' => $mhs->mahasiswa_id,
            'password_hash' => $aktif ? Hash::make('SandiLama1') : null,
        ]);
    }

    private function aktivasi(array $ubah = []): \Illuminate\Testing\TestResponse
    {
        return $this->from('/aktivasi')->post('/aktivasi', $ubah + [
            'nim' => '2201002', 'gender' => 'L', 'password' => 'SandiBaru1', 'confirm' => 'SandiBaru1',
        ]);
    }

    public function test_halaman_aktivasi_dan_pemulihan_tampil(): void
    {
        $this->get('/aktivasi')->assertOk()->assertSee('Gunakan NIM dan kode aktivasi dari sekretaris prodi.')->assertSee('Pilih jenis kelamin');
        $this->get('/pemulihan')->assertOk()->assertSee('Lupa kata sandi')->assertSee('Berlaku 30 menit.')->assertDontSee('Pilih jenis kelamin');
    }

    public function test_u2_aktivasi_berhasil_langsung_masuk_ke_profil(): void
    {
        $akun = $this->akun();
        $kode = app(KodeTiketService::class)->terbitkan($akun, 'aktivasi');
        $this->assertMatchesRegularExpression('/^[A-HJ-NP-Z2-9]{4}-[A-HJ-NP-Z2-9]{4}-[A-HJ-NP-Z2-9]{4}$/', $kode);

        $this->aktivasi(['code' => strtolower($kode)])
            ->assertRedirect(route('mahasiswa.profil'))
            ->assertSessionHas('toast', 'Akun aktif. Lengkapi profil KKN.');

        $akun->refresh();
        $this->assertAuthenticatedAs($akun, 'mahasiswa');
        $this->assertTrue(Hash::check('SandiBaru1', $akun->password_hash));
        $this->assertNull($akun->activation_ticket_hash);
        $this->assertNull($akun->ticket_purpose);
        $this->assertSame('L', $akun->mahasiswa->jenis_kelamin);
    }

    public function test_u2_kode_tidak_bisa_dipakai_ulang(): void
    {
        $akun = $this->akun();
        $kode = app(KodeTiketService::class)->terbitkan($akun, 'aktivasi');
        $this->aktivasi(['code' => $kode]);
        $this->post('/keluar');

        $this->aktivasi(['code' => $kode])->assertSessionHasErrors(['code' => self::PESAN_KODE]);
    }

    public function test_u4_kode_salah_kedaluwarsa_atau_salah_tujuan_ditolak(): void
    {
        $akun = $this->akun();
        $kode = app(KodeTiketService::class)->terbitkan($akun, 'aktivasi');

        $this->aktivasi(['code' => 'AAAA-AAAA-AAAA'])->assertSessionHasErrors(['code' => self::PESAN_KODE]);
        $this->aktivasi(['code' => $kode, 'nim' => '9999999'])->assertSessionHasErrors(['code' => self::PESAN_KODE]);

        $this->travel(7)->days();
        $this->travel(1)->minutes();
        $this->aktivasi(['code' => $kode])->assertSessionHasErrors(['code' => self::PESAN_KODE]);
        $this->assertGuest('mahasiswa');
        $this->assertNull($akun->refresh()->password_hash);
    }

    public function test_u5_validasi_kata_sandi_dan_jenis_kelamin(): void
    {
        $akun = $this->akun();
        $kode = app(KodeTiketService::class)->terbitkan($akun, 'aktivasi');

        $this->aktivasi(['code' => $kode, 'gender' => ''])->assertSessionHasErrors(['gender' => 'Pilih jenis kelamin.']);
        $this->aktivasi(['code' => $kode, 'password' => 'pendek', 'confirm' => 'pendek'])
            ->assertSessionHasErrors(['password' => 'Gunakan kata sandi 8–128 karakter.']);
        $this->aktivasi(['code' => $kode, 'confirm' => 'BerbedaSekali'])
            ->assertSessionHasErrors(['confirm' => 'Konfirmasi kata sandi tidak sama.']);
        $this->assertNull($akun->refresh()->password_hash);
    }

    public function test_u3_pemulihan_berhasil_kembali_ke_halaman_masuk(): void
    {
        $akun = $this->akun(aktif: true);
        $kode = app(KodeTiketService::class)->terbitkan($akun, 'pemulihan');

        $this->post('/pemulihan', ['nim' => '2201002', 'code' => $kode, 'password' => 'SandiBaru2', 'confirm' => 'SandiBaru2'])
            ->assertRedirect(route('masuk'))
            ->assertSessionHas('toast', 'Kata sandi diperbarui. Silakan masuk dengan kata sandi baru.');
        $this->assertGuest('mahasiswa');

        $akun->refresh();
        $this->assertTrue(Hash::check('SandiBaru2', $akun->password_hash));
        $this->assertNull($akun->activation_ticket_hash);

        $this->post('/masuk', ['peran' => 'mahasiswa', 'nim' => '2201002', 'password' => 'SandiBaru2'])->assertRedirect();
        $this->assertAuthenticated('mahasiswa');
    }

    public function test_u3_kode_pemulihan_berlaku_30_menit_dan_tidak_untuk_aktivasi(): void
    {
        $akun = $this->akun(aktif: true);
        $kode = app(KodeTiketService::class)->terbitkan($akun, 'pemulihan');

        $this->aktivasi(['code' => $kode])->assertSessionHasErrors(['code' => self::PESAN_KODE]);

        $this->travel(31)->minutes();
        $this->post('/pemulihan', ['nim' => '2201002', 'code' => $kode, 'password' => 'SandiBaru2', 'confirm' => 'SandiBaru2'])
            ->assertSessionHasErrors(['code' => self::PESAN_KODE]);
        $this->assertTrue(Hash::check('SandiLama1', $akun->refresh()->password_hash));
    }
}
