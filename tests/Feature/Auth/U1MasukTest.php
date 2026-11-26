<?php

namespace Tests\Feature\Auth;

use App\Models\Admin;
use App\Models\AkunMahasiswa;
use App\Models\Mahasiswa;
use App\Models\PeriodeKkn;
use App\Models\PesertaPeriode;
use App\Models\ProgramStudi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * U1: Masuk dan U77: Keluar.
 */
class U1MasukTest extends TestCase
{
    use RefreshDatabase;

    private PeriodeKkn $periode;

    protected function setUp(): void
    {
        parent::setUp();
        $this->periode = PeriodeKkn::create(['tahun_akademik' => '2026/2027', 'tahap_periode' => 'persiapan']);
        Admin::create(['nama_admin' => 'Admin LPPM', 'email' => 'admin@kkn.test', 'password_hash' => Hash::make('Rahasia123')]);
    }

    private function mahasiswa(bool $lengkap = false, ?string $kataSandi = 'Rahasia123'): AkunMahasiswa
    {
        $prodi = ProgramStudi::create(['nama_prodi' => 'Teknik Informatika', 'fakultas' => 'Fakultas Teknik']);
        $mhs = Mahasiswa::create(['nim' => '2201001', 'nama_lengkap' => 'Alya Siregar', 'prodi_id' => $prodi->prodi_id, 'jenis_kelamin' => 'P']);
        PesertaPeriode::create(['mahasiswa_id' => $mhs->mahasiswa_id, 'periode_id' => $this->periode->periode_id, 'ukuran_baju' => $lengkap ? 'M' : null]);

        return AkunMahasiswa::create([
            'mahasiswa_id' => $mhs->mahasiswa_id,
            'password_hash' => $kataSandi ? Hash::make($kataSandi) : null,
            'no_whatsapp' => $lengkap ? '081234567890' : null,
        ]);
    }

    public function test_halaman_masuk_tampil_di_akar_dan_masuk(): void
    {
        $this->get('/')->assertOk()->assertSee('Masuk ke portal')->assertSee('Aktivasi akun')->assertSee('Lupa kata sandi?');
        $this->get('/masuk?peran=admin')->assertOk()->assertSee('Gunakan email dan kata sandi Admin.')->assertDontSee('Lupa kata sandi?');
    }

    public function test_admin_masuk_ke_dasbor(): void
    {
        $this->post('/masuk', ['peran' => 'admin', 'email' => 'admin@kkn.test', 'password' => 'Rahasia123'])
            ->assertRedirect(route('admin.dasbor'));
        $this->assertAuthenticated('admin');
        $this->get('/admin/dasbor')->assertOk()->assertSee('Admin LPPM')->assertSee('Keluar akun');
    }

    public function test_admin_salah_kata_sandi(): void
    {
        $this->from('/masuk')->post('/masuk', ['peran' => 'admin', 'email' => 'admin@kkn.test', 'password' => 'salah'])
            ->assertRedirect('/masuk')->assertSessionHasErrors(['email' => 'Email atau kata sandi tidak sesuai.']);
        $this->assertGuest('admin');
    }

    public function test_mahasiswa_profil_belum_lengkap_diarahkan_ke_profil(): void
    {
        $this->mahasiswa();
        $this->post('/masuk', ['peran' => 'mahasiswa', 'nim' => '2201001', 'password' => 'Rahasia123'])
            ->assertRedirect(route('mahasiswa.profil'));
        $this->assertAuthenticated('mahasiswa');
        $this->get('/mahasiswa/kelompok')->assertRedirect(route('mahasiswa.profil'));
    }

    public function test_mahasiswa_profil_lengkap_diarahkan_ke_kelompok(): void
    {
        $this->mahasiswa(lengkap: true);
        $this->post('/masuk', ['peran' => 'mahasiswa', 'nim' => '2201001', 'password' => 'Rahasia123'])
            ->assertRedirect(route('mahasiswa.kelompok'));
        $this->get('/mahasiswa/kelompok')->assertOk()->assertSee('Kelompok saya');
    }

    public function test_mahasiswa_salah_atau_belum_aktivasi_ditolak(): void
    {
        $pesan = 'NIM atau kata sandi tidak sesuai. Untuk akses pertama, pilih Aktivasi akun.';
        $this->mahasiswa(kataSandi: null);
        $this->post('/masuk', ['peran' => 'mahasiswa', 'nim' => '2201001', 'password' => 'apa saja'])
            ->assertSessionHasErrors(['nim' => $pesan]);
        $this->post('/masuk', ['peran' => 'mahasiswa', 'nim' => '9999999', 'password' => 'apa saja'])
            ->assertSessionHasErrors(['nim' => $pesan]);
        $this->assertGuest('mahasiswa');
    }

    public function test_percobaan_masuk_dibatasi(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/masuk', ['peran' => 'admin', 'email' => 'admin@kkn.test', 'password' => 'salah']);
        }
        $this->post('/masuk', ['peran' => 'admin', 'email' => 'admin@kkn.test', 'password' => 'Rahasia123'])
            ->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    }

    public function test_halaman_terlindungi_butuh_masuk_dan_peran_terpisah(): void
    {
        $this->get('/admin/dasbor')->assertRedirect(route('masuk', ['peran' => 'admin']));
        $this->get('/mahasiswa/profil')->assertRedirect(route('masuk'));

        $this->actingAs($this->mahasiswa(lengkap: true), 'mahasiswa');
        $this->get('/admin/dasbor')->assertRedirect();
        $this->assertGuest('admin');
        $this->get('/masuk')->assertRedirect(route('mahasiswa.kelompok'));
    }

    public function test_u77_keluar_mengakhiri_sesi(): void
    {
        $this->post('/masuk', ['peran' => 'admin', 'email' => 'admin@kkn.test', 'password' => 'Rahasia123']);
        $this->post('/keluar')->assertRedirect(route('masuk'))->assertSessionHas('toast', 'Anda sudah keluar.');
        $this->assertGuest('admin');
        $this->get('/admin/dasbor')->assertRedirect(route('masuk', ['peran' => 'admin']));
    }
}
