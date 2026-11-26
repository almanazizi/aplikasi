<?php

use App\Http\Controllers\Auth\AktivasiController;
use App\Http\Controllers\Auth\KeluarController;
use App\Http\Controllers\Auth\MasukController;
use App\Http\Controllers\Auth\PemulihanController;
use Illuminate\Support\Facades\Route;

// Publik: Masuk (U1), Aktivasi akun (U2, U4, U5), Lupa kata sandi (U3, U4, U5).
Route::middleware('guest:admin,mahasiswa')->group(function () {
    Route::get('/', [MasukController::class, 'create']);
    Route::get('/masuk', [MasukController::class, 'create'])->name('masuk');
    Route::post('/masuk', [MasukController::class, 'store'])->name('masuk.kirim');

    Route::get('/aktivasi', [AktivasiController::class, 'create'])->name('aktivasi');
    Route::post('/aktivasi', [AktivasiController::class, 'store'])->name('aktivasi.kirim');
    Route::redirect('/daftar', '/aktivasi');

    Route::get('/pemulihan', [PemulihanController::class, 'create'])->name('pemulihan');
    Route::post('/pemulihan', [PemulihanController::class, 'store'])->name('pemulihan.kirim');
});

// U77: Keluar.
Route::post('/keluar', KeluarController::class)->middleware('auth:admin,mahasiswa')->name('keluar');

// Mahasiswa. Isi halaman dibangun pada tahap berikutnya (Profil: Tahap 5, Kelompok: Tahap 6).
Route::prefix('mahasiswa')->name('mahasiswa.')->middleware('auth:mahasiswa')->group(function () {
    Route::view('/profil', 'mahasiswa.profil')->middleware('fitur.mahasiswa:profil')->name('profil');
    Route::view('/kelompok', 'mahasiswa.kelompok')->middleware('fitur.mahasiswa:kelompok')->name('kelompok');
});

// Admin. Isi Dashboard dibangun pada Tahap 9.
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::view('/dasbor', 'admin.dasbor')->name('dasbor');
});

// Alamat lain dialihkan ke halaman masuk (atau halaman awal bila sudah masuk).
Route::fallback(fn () => redirect('/'));
