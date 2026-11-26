<?php

namespace App\View\Composers;

use App\Services\PeriodeService;
use App\Services\ProfilService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Data kerangka halaman setelah masuk: bilah atas, menu samping (laptop), navigasi bawah (HP).
 */
class TataLetakAplikasi
{
    public function __construct(
        private Request $request,
        private PeriodeService $periode,
        private ProfilService $profil,
    ) {}

    public function compose(View $view): void
    {
        $periode = $this->periode->aktif();
        $admin = Auth::guard('admin')->user();
        $akun = $admin ? null : Auth::guard('mahasiswa')->user();

        $nama = $admin?->nama_admin ?? $akun?->mahasiswa?->nama_lengkap ?? 'Mahasiswa';
        $inisial = collect(preg_split('/\s+/', trim($nama)))
            ->filter()->take(2)->map(fn ($kata) => mb_substr($kata, 0, 1))->implode('');

        $data = [
            'peran' => $admin ? 'admin' : 'mahasiswa',
            'periode' => $periode,
            'namaTampil' => $nama,
            'inisial' => $inisial,
        ];

        $view->with($admin ? $data + $this->menuAdmin() : $data + $this->menuMahasiswa($akun, $periode));
    }

    private function aktif(string $url): bool
    {
        [$path, $query] = array_pad(explode('?', ltrim($url, '/'), 2), 2, null);
        if (! $this->request->is($path, $path.'/*')) {
            return false;
        }
        if ($query === null) {
            return true;
        }
        parse_str($query, $param);

        return ($this->request->query('tab') ?? 'ketentuan') === ($param['tab'] ?? null);
    }

    private function tautan(string $url, string $ikon, string $judul): array
    {
        return ['url' => url($url), 'ikon' => $ikon, 'judul' => $judul, 'aktif' => $this->aktif($url)];
    }

    private function menuAdmin(): array
    {
        $master = [
            $this->tautan('/admin/master/peserta', 'users', 'Data Peserta'),
            $this->tautan('/admin/master/prodi', 'book', 'Data Prodi'),
            $this->tautan('/admin/master/desa', 'pin', 'Data Desa'),
            $this->tautan('/admin/master/dpl', 'user', 'Data DPL'),
        ];
        $pengaturan = [
            $this->tautan('/admin/periode?tab=ketentuan', 'shield', 'Ketentuan'),
            $this->tautan('/admin/periode?tab=jadwal', 'calendar', 'Jadwal'),
        ];
        $laporan = $this->tautan('/admin/laporan', 'book', 'Laporan akhir');

        $menuSamping = [
            $this->tautan('/admin/dasbor', 'grid', 'Dashboard'),
            ['bagian' => 'Data Master', 'ikon' => 'database', 'anak' => array_map(
                fn ($t) => ['judul' => substr($t['judul'], 5)] + $t, $master
            )],
            ['bagian' => 'Administrasi', 'ikon' => 'briefcase', 'anak' => [
                $this->tautan('/admin/kelompok', 'users', 'Kelompok'),
                $this->tautan('/admin/desa', 'pin', 'Desa & proposal'),
                $this->tautan('/admin/dpl', 'user', 'Penugasan DPL'),
                $this->tautan('/admin/surat', 'mail', 'Surat pengantar'),
                $laporan,
            ]],
            ['bagian' => 'Pengaturan', 'ikon' => 'calendar', 'anak' => $pengaturan],
        ];

        $menuLainnya = [$laporan, ...$master, ...$pengaturan];

        return [
            'judulMenu' => 'Menu pengelolaan',
            'menuSamping' => $menuSamping,
            'tabBawah' => [
                $this->tautan('/admin/dasbor', 'grid', 'Dasbor'),
                $this->tautan('/admin/kelompok', 'users', 'Kelompok'),
                $this->tautan('/admin/desa', 'pin', 'Desa'),
                $this->tautan('/admin/dpl', 'user', 'DPL'),
                $this->tautan('/admin/surat', 'mail', 'Surat'),
            ],
            'menuLainnya' => $menuLainnya,
            'lainnyaAktif' => collect($menuLainnya)->contains('aktif', true),
        ];
    }

    private function menuMahasiswa($akun, $periode): array
    {
        $fitur = [
            ['kelompok', 'users', 'Kelompok saya', 'Kelompok'],
            ['profil', 'user', 'Profil saya', 'Profil'],
            ['desa', 'pin', 'Desa & proposal', 'Desa'],
            ['laporan', 'book', 'Laporan akhir', 'Laporan'],
        ];

        $menu = [];
        foreach ($fitur as [$id, $ikon, $judul, $pendek]) {
            $menu[$id] = $this->tautan('/mahasiswa/'.$id, $ikon, $judul) + [
                'pendek' => $pendek,
                'kunci' => $akun ? $this->profil->alasanTerkunci($akun, $periode, $id) : '',
            ];
        }

        return [
            'judulMenu' => 'Menu mahasiswa',
            'menuSamping' => array_values($menu),
            // Urutan tab HP: Kelompok · Desa · Laporan · Profil.
            'tabBawah' => [$menu['kelompok'], $menu['desa'], $menu['laporan'], $menu['profil']],
            'profilBelumLengkap' => $akun && ! $this->profil->lengkap($akun, $periode),
        ];
    }
}
