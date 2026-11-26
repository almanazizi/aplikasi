<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TukarKodeRequest;
use App\Services\KodeTiketService;
use App\Support\BatasPercobaan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * U3: Pulihkan akun / Lupa kata sandi (memakai U4 dan U5).
 */
class PemulihanController extends Controller
{
    public function create(): View
    {
        return view('auth.kode', ['aktivasi' => false]);
    }

    public function store(TukarKodeRequest $request, KodeTiketService $tiket): RedirectResponse
    {
        $nim = $request->string('nim')->trim()->toString();
        $kunci = 'pemulihan|'.$nim.'|'.$request->ip();
        BatasPercobaan::cek($kunci, 'code');

        try {
            $tiket->tukarkan($nim, $request->input('code'), 'pemulihan', $request->input('password'));
        } catch (ValidationException $e) {
            BatasPercobaan::catatGagal($kunci);
            throw $e;
        }

        BatasPercobaan::bersihkan($kunci);

        return redirect()->route('masuk')
            ->with('nim', $nim)
            ->with('toast', 'Kata sandi diperbarui. Silakan masuk dengan kata sandi baru.');
    }
}
