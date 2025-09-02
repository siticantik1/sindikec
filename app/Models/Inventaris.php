<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    use HasFactory;

    /**
     * Menentukan nama tabel database secara eksplisit.
     * Ini adalah praktik yang baik untuk kejelasan kode.
     */
    protected $table = 'inventaris';

    /**
     * Daftar kolom yang boleh diisi secara massal (mass assignable).
     * Ini penting agar method 'create()' dan 'update()' di controller berfungsi dengan aman.
     * Daftar ini sudah disesuaikan dengan kodemu yang terakhir.
     */
    protected $fillable = [
        'nama_barang',
        'merk_model',
        'bahan',
        'tahun_pembelian',
        'kode_barang',
        'jumlah',
        'harga_perolehan',
        'kondisi',
        'keterangan',
        'room_id', // Foreign key untuk relasi ke tabel rooms
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model Room.
     * Ini memberitahu Laravel bahwa setiap barang inventaris pasti "milik" satu ruangan.
     * Fungsi ini sangat penting agar Controller bisa mencari data berdasarkan lokasi ruangan.
     */
    public function room()
    {
        // Laravel akan otomatis mencari kolom 'room_id' di tabel 'inventaris'
        // untuk menghubungkannya dengan model Room.
        return $this->belongsTo(Room::class);
    }
}

