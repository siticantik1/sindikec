<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Fungsi ini akan dijalankan saat kita ketik 'php artisan migrate'
     */
    public function up(): void
    {
        // Rencana untuk tabel 'rooms'
        Schema::table('rooms', function (Blueprint $table) {
            // Tambahkan kolom 'lokasi' setelah kolom 'id',
            // dan beri nilai default 'tawang' untuk semua data yang sudah ada.
            $table->string('lokasi')->after('id')->default('tawang');
        });

        // Rencana untuk tabel 'rkls'
        Schema::table('rkls', function (Blueprint $table) {
            // Lakukan hal yang sama untuk tabel rkls
            $table->string('lokasi')->after('id')->default('tawang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * Fungsi ini akan dijalankan jika kita ingin membatalkan (rollback)
     */
    public function down(): void
    {
        // Rencana pembatalan untuk tabel 'rooms'
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('lokasi');
        });
        
        // Rencana pembatalan untuk tabel 'rkls'
        Schema::table('rkls', function (Blueprint $table) {
            $table->dropColumn('lokasi');
        });
    }
};

