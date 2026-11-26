<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keanggotaan_kelompok', function (Blueprint $table) {
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('periode_id');
            $table->unsignedBigInteger('kelompok_id');
            $table->enum('peran', ['ketua', 'anggota']);

            $table->primary(['mahasiswa_id', 'periode_id']);

            $table->foreign(['mahasiswa_id', 'periode_id'])
                ->references(['mahasiswa_id', 'periode_id'])
                ->on('peserta_periode')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign(['kelompok_id', 'periode_id'])
                ->references(['kelompok_id', 'periode_id'])
                ->on('kelompok_kkn')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keanggotaan_kelompok');
    }
};
