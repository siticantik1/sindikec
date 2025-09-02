<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rkls', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // ======================================================
            // PERBAIKAN: Diubah dari 'code' menjadi 'kode_ruangan'
            // ======================================================
            $table->string('kode_ruangan')->unique();
            $table->string('lokasi')->default('lengkongsari');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rkls');
    }
};
