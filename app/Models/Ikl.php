<?php

namespace App\Models;

// ======================================================
// PERBAIKAN: Dua baris ini ditambahkan untuk memperbaiki error
// ======================================================
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ikl extends Model
{
    // Baris ini sekarang akan bekerja dengan benar
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     */
    protected $table = 'ikls'; // Sesuaikan jika nama tabelnya berbeda

    /**
     * The attributes that are mass assignable.
     * Ini WAJIB ada agar Ikl::create() berhasil.
     */
    protected $fillable = [
        'nama_barang',
        'rkl_id', // Pastikan foreign key ke tabel rkls ada
        'kode_barang',
        'merk_model',
        'bahan',
        'tahun_pembelian',
        'jumlah',
        'harga_perolehan',
        'kondisi',
        'keterangan',
    ];

    /**
     * Relasi ke model Rkl.
     * Fungsi ini memberitahu Laravel bahwa setiap 'Ikl' pasti "milik" satu 'Rkl'.
     */
    public function rkl()
    {
        return $this->belongsTo(Rkl::class);
    }
}
