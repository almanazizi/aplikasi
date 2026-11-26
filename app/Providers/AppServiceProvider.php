<?php

namespace App\Providers;

use App\Services\PeriodeService;
use App\View\Composers\TataLetakAplikasi;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Satu instans per permintaan agar periode aktif cukup dicari sekali.
        $this->app->scoped(PeriodeService::class);
    }

    public function boot(): void
    {
        View::composer('layouts.app', TataLetakAplikasi::class);
        View::composer('layouts.auth', function ($view) {
            $view->with('periodePublik', app(PeriodeService::class)->aktif());
        });
    }
}
