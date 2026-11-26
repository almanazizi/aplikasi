<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ketentuan_kelompok', function (Blueprint $table) {
            $table->id('ketentuan_id');
            $table->unsignedBigInteger('periode_id');
            $table->enum('jenis_kkn', ['reguler', 'nonreguler']);
            $table->unsignedSmallInteger('min_anggota');
            $table->unsignedSmallInteger('maks_anggota');
            $table->unsignedSmallInteger('min_prodi')->nullable();
            $table->unsignedSmallInteger('maks_laki_laki')->nullable();

            $table->unique(['periode_id', 'jenis_kkn']);

            $table->foreign('periode_id')
                ->references('periode_id')
                ->on('periode_kkn')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ketentuan_kelompok');
    }
};
