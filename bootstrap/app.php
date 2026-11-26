<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\AksesFiturMahasiswa;
use App\Services\ProfilService;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'fitur.mahasiswa' => AksesFiturMahasiswa::class,
        ]);

        // Belum masuk: ke halaman masuk, dengan tab Admin bila membuka halaman Admin.
        $middleware->redirectGuestsTo(fn (Request $request) => $request->is('admin', 'admin/*')
            ? route('masuk', ['peran' => 'admin'])
            : route('masuk'));

        // Sudah masuk: ke halaman awal sesuai peran (U1 langkah e).
        $middleware->redirectUsersTo(function () {
            if (Auth::guard('admin')->check()) {
                return route('admin.dasbor');
            }

            return app(ProfilService::class)->halamanAwal(Auth::guard('mahasiswa')->user());
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
