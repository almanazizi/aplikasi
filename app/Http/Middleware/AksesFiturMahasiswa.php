<?php

namespace App\Http\Middleware;

use App\Services\PeriodeService;
use App\Services\ProfilService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Halaman mahasiswa selain Profil: profil wajib lengkap dulu, lalu cek penguncian fitur
 * (spesifikasi bagian 6, kolom "Terkunci bila").
 */
class AksesFiturMahasiswa
{
    public function __construct(private PeriodeService $periode, private ProfilService $profil) {}

    public function handle(Request $request, Closure $next, string $fitur): Response
    {
        $akun = $request->user('mahasiswa');
        $periode = $this->periode->aktif();

        if ($fitur !== 'profil' && ! $this->periode->ditutup($periode) && ! $this->profil->lengkap($akun, $periode)) {
            return redirect()->route('mahasiswa.profil');
        }

        $alasan = $this->profil->alasanTerkunci($akun, $periode, $fitur);
        if ($alasan !== '') {
            return response()->view('mahasiswa.terkunci', ['alasan' => $alasan]);
        }

        return $next($request);
    }
}
