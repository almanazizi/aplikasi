<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periode_kkn', function (Blueprint $table) {
            $table->id('periode_id');
            $table->char('tahun_akademik', 9)->unique();
            $table->enum('tahap_periode', ['persiapan', 'berjalan', 'ditutup']);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->date('tenggat_kelompok')->nullable();
            $table->date('tenggat_pengajuan_desa')->nullable();
            $table->date('tenggat_laporan')->nullable();
            $table->unsignedInteger('maks_kelompok')->nullable();
            $table->unsignedInteger('urutan_kelompok_terakhir')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periode_kkn');
    }
};
