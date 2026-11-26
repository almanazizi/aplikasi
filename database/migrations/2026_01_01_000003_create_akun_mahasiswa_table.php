<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('akun_mahasiswa', function (Blueprint $table) {
            $table->id('akun_id');
            $table->unsignedBigInteger('mahasiswa_id')->unique();
            $table->string('no_whatsapp', 16)->nullable();
            $table->string('password_hash', 255)->nullable();
            $table->string('activation_ticket_hash', 255)->nullable();
            $table->enum('ticket_purpose', ['aktivasi', 'pemulihan'])->nullable();
            $table->dateTime('ticket_expiry')->nullable();

            $table->foreign('mahasiswa_id')
                ->references('mahasiswa_id')
                ->on('mahasiswa')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akun_mahasiswa');
    }
};
