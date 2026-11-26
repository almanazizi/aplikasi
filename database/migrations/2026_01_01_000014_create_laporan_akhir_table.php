<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_akhir', function (Blueprint $table) {
            $table->id('laporan_id');
            $table->unsignedBigInteger('kelompok_id')->unique();
            $table->string('nama_file_pdf', 255);
            $table->string('id_penyimpanan_pdf', 255);
            $table->dateTime('tanggal_unggah');

            $table->foreign('kelompok_id')
                ->references('kelompok_id')
                ->on('kelompok_kkn')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_akhir');
    }
};
