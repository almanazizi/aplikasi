<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id('admin_id');
            $table->string('nama_admin', 100);
            $table->string('email', 255)->unique();
            $table->string('password_hash', 255);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
