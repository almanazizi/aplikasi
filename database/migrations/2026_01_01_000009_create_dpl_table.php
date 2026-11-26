<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dpl', function (Blueprint $table) {
            $table->id('dpl_id');
            $table->string('nama_dpl', 100);
            $table->string('no_whatsapp', 16)->nullable();
            $table->string('nidn', 12)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dpl');
    }
};
