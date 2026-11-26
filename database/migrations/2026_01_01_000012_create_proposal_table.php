<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal', function (Blueprint $table) {
            $table->id('proposal_id');
            $table->unsignedBigInteger('kelompok_id')->unique();
            $table->unsignedBigInteger('desa_id')->nullable();
            $table->string('nama_file_pdf', 255)->nullable();
            $table->string('id_penyimpanan_pdf', 255)->nullable();
            $table->enum('status_proposal', ['diperiksa', 'perbaikan', 'disetujui', 'dibatalkan'])->nullable();
            $table->text('catatan_perbaikan')->nullable();
            $table->text('alasan_pembatalan')->nullable();
            $table->dateTime('waktu_pengajuan')->nullable();
            $table->boolean('desa_dikunci_admin')->default(0);

            $table->foreign('kelompok_id')
                ->references('kelompok_id')
                ->on('kelompok_kkn')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('desa_id')
                ->references('desa_id')
                ->on('desa')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal');
    }
};
