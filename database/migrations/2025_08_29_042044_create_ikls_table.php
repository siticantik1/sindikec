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
        Schema::create('ikl', function (Blueprint $table) {
            $table->id(); // Ini akan menjadi 'No Urut'
            // INI PERBAIKANNYA
            $table->foreignId('rkl_id')->nullable()->constrained('rkls');
            $table->string('nama_barang');
            $table->string('merk_model')->nullable();
            $table->string('bahan')->nullable();
            $table->year('tahun_pembelian');
            $table->string('kode_barang');
            $table->integer('jumlah')->default(1);
            $table->decimal('harga_perolehan', 15, 2);
            $table->enum('kondisi', ['B', 'KB', 'RB'])->comment('B: Baik, KB: Kurang Baik, RB: Rusak Berat');
            $table->text('keterangan')->nullable();
            $table->timestamps(); // Kolom created_at dan updated_at
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
