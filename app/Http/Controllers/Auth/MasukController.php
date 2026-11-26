<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\MasukRequest;
use App\Models\AkunMahasiswa;
use App\Models\Mahasiswa;
use App\Services\ProfilService;
use App\Support\BatasPercobaan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * U1: Masuk (Login) untuk dua peran.
 */
class MasukController extends Controller
{
    public function create(Request $request): View
    {
        $peran = old('peran', $request->query('peran')) === 'admin' ? 'admin' : 'mahasiswa';

        return view('auth.masuk', ['peran' => $peran]);
    }

    public function store(MasukRequest $request, ProfilService $profil): RedirectResponse
    {
        $peran = $request->peran();
        $identitas = $peran === 'admin' ? strtolower($request->string('email')->trim()) : $request->string('nim')->trim();
        $kunci = 'masuk|'.$peran.'|'.$identitas.'|'.$request->ip();
        $field = $peran === 'admin' ? 'email' : 'nim';

        BatasPercobaan::cek($kunci, $field);

        if ($peran === 'admin') {
            $berhasil = Auth::guard('admin')->attempt([
                'email' => $identitas,
                'password' => $request->input('password'),
            ]);
            if (! $berhasil) {
                BatasPercobaan::catatGagal($kunci);
                throw ValidationException::withMessages(['email' => 'Email atau kata sandi tidak sesuai.']);
            }

            BatasPercobaan::bersihkan($kunci);
            $request->session()->regenerate();

            return redirect()->route('admin.dasbor');
        }

        $akun = $this->akunMahasiswa((string) $identitas);
        if (! $akun || ! $akun->password_hash || ! Hash::check($request->input('password'), $akun->password_hash)) {
            BatasPercobaan::catatGagal($kunci);
            throw ValidationException::withMessages([
                'nim' => 'NIM atau kata sandi tidak sesuai. Untuk akses pertama, pilih Aktivasi akun.',
            ]);
        }

        BatasPercobaan::bersihkan($kunci);
        Auth::guard('mahasiswa')->login($akun);
        $request->session()->regenerate();

        return redirect()->to($profil->halamanAwal($akun));
    }

    private function akunMahasiswa(string $nim): ?AkunMahasiswa
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        return $mahasiswa?->akun?->setRelation('mahasiswa', $mahasiswa);
    }
}
