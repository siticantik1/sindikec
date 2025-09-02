<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rkl extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini secara eksplisit.
     * @var string
     */
    protected $table = 'rkls';

    /**
     * The attributes that are mass assignable.
     * Ini adalah daftar kolom yang boleh diisi melalui form.
     *
     * @var array<int, string>
     */
    // ======================================================
    // PERBAIKAN: Menggunakan 'kode_ruangan' agar konsisten 
    // dengan nama kolom di database dan Controller.
    // ======================================================
    protected $fillable = [
        'name',
        'kode_ruangan',
        'lokasi',
    ];

    /**
     * Mendefinisikan relasi one-to-many ke model Ikl.
     * Satu ruangan (Rkl) dapat memiliki banyak inventaris (Ikl).
     */
    public function ikls()
    {
        return $this->hasMany(Ikl::class, 'rkl_id', 'id');
    }
}

