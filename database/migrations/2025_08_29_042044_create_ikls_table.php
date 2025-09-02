<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ikls', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            
            // Kolom ini akan terhubung ke tabel 'rkls'
            $table->foreignId('rkl_id')->constrained('rkls')->onDelete('cascade');
            
            $table->string('kode_barang')->unique();
            $table->string('merk_model')->nullable();
            $table->string('bahan')->nullable();
            $table->year('tahun_pembelian');
            $table->integer('jumlah')->default(1);
            $table->decimal('harga_perolehan', 15, 2)->default(0);
            $table->enum('kondisi', ['B', 'KB', 'RB'])->comment('B: Baik, KB: Kurang Baik, RB: Rusak Berat');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ikls');
    }
};
