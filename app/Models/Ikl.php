<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ikl extends Model
{
     use HasFactory;

    // Properti $fillable Anda mungkin sudah ada di sini
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
        'rkl_id',
    ];

    // ======================================================
    // TAMBAHKAN FUNGSI INI
    // ======================================================
    /**
     * Mendefinisikan relasi bahwa satu Inventaris "milik" satu Ruangan.
     */
    public function room()
    {
        // Laravel akan otomatis mencari foreign key 'room_id'
        return $this->belongsTo(Rkl::class);
    }
    // ======================================================
}
