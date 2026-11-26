<?php

namespace App\Services;

use App\Models\PeriodeKkn;

class PeriodeService
{
    private ?PeriodeKkn $aktif = null;

    private bool $sudahDicari = false;

    /**
     * Periode yang sedang dibuka: periode dengan tahun akademik terbaru.
     */
    public function aktif(): ?PeriodeKkn
    {
        if (! $this->sudahDicari) {
            $this->aktif = PeriodeKkn::orderByDesc('tahun_akademik')->first();
            $this->sudahDicari = true;
        }

        return $this->aktif;
    }

    public function ditutup(?PeriodeKkn $periode): bool
    {
        return $periode?->tahap_periode === 'ditutup';
    }
}
