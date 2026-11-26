<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelompok_kkn', function (Blueprint $table) {
            $table->id('kelompok_id');
            $table->unsignedBigInteger('periode_id');
            $table->unsignedBigInteger('dpl_id')->nullable();
            $table->unsignedInteger('nomor_kelompok');
            $table->enum('jenis_kkn', ['reguler', 'nonreguler']);
            $table->char('kode_gabung', 6)->unique();
            $table->enum('status_kelompok', ['draf', 'menunggu', 'perbaikan', 'disetujui']);
            $table->dateTime('waktu_pengajuan')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->string('alasan_pengecualian', 255)->nullable();
            $table->unsignedBigInteger('calon_ketua_id')->nullable();
            $table->enum('status_perubahan_anggota', ['diajukan', 'diizinkan'])->nullable();

            $table->unique(['periode_id', 'nomor_kelompok']);
            $table->unique(['periode_id', 'dpl_id']);
            $table->unique(['kelompok_id', 'periode_id']);

            $table->foreign('periode_id')
                ->references('periode_id')
                ->on('periode_kkn')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('dpl_id')
                ->references('dpl_id')
                ->on('dpl')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('calon_ketua_id')
                ->references('mahasiswa_id')
                ->on('mahasiswa')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelompok_kkn');
    }
};
