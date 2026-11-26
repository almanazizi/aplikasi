<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TukarKodeRequest;
use App\Services\KodeTiketService;
use App\Support\BatasPercobaan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * U2: Aktifkan akun (memakai U4 dan U5).
 */
class AktivasiController extends Controller
{
    public function create(): View
    {
        return view('auth.kode', ['aktivasi' => true]);
    }

    public function store(TukarKodeRequest $request, KodeTiketService $tiket): RedirectResponse
    {
        $nim = $request->string('nim')->trim()->toString();
        $kunci = 'aktivasi|'.$nim.'|'.$request->ip();
        BatasPercobaan::cek($kunci, 'code');

        try {
            $akun = $tiket->tukarkan($nim, $request->input('code'), 'aktivasi', $request->input('password'), $request->input('gender'));
        } catch (ValidationException $e) {
            BatasPercobaan::catatGagal($kunci);
            throw $e;
        }

        BatasPercobaan::bersihkan($kunci);
        Auth::guard('mahasiswa')->login($akun);
        $request->session()->regenerate();

        return redirect()->route('mahasiswa.profil')->with('toast', 'Akun aktif. Lengkapi profil KKN.');
    }
}
