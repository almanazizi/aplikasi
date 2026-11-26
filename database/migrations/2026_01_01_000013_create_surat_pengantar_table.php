<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_pengantar', function (Blueprint $table) {
            $table->id('surat_id');
            $table->unsignedBigInteger('proposal_id')->unique();
            $table->string('nama_file_pdf', 255)->nullable();
            $table->string('id_penyimpanan_pdf', 255)->nullable();
            $table->enum('status_surat', ['disiapkan', 'siap']);
            $table->dateTime('tanggal_unggah')->nullable();

            $table->foreign('proposal_id')
                ->references('proposal_id')
                ->on('proposal')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_pengantar');
    }
};
