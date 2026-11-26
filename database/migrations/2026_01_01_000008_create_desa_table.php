<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('desa', function (Blueprint $table) {
            $table->id('desa_id');
            $table->string('nama_desa', 100);
            $table->string('kecamatan', 100);
            $table->string('kabupaten_kota', 100);

            $table->unique(['nama_desa', 'kecamatan', 'kabupaten_kota']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desa');
    }
};
