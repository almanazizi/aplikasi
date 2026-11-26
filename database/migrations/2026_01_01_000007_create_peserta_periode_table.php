<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peserta_periode', function (Blueprint $table) {
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('periode_id');
            $table->string('ukuran_baju', 10)->nullable();

            $table->primary(['mahasiswa_id', 'periode_id']);

            $table->foreign('mahasiswa_id')
                ->references('mahasiswa_id')
                ->on('mahasiswa')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('periode_id')
                ->references('periode_id')
                ->on('periode_kkn')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta_periode');
    }
};
