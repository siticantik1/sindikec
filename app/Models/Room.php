<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini secara eksplisit.
     * @var string
     */
    protected $table = 'rooms';

    /**
     * The attributes that are mass assignable.
     * Ini adalah daftar kolom yang boleh diisi melalui form.
     *
     * @var array<int, string>
     */
    // ======================================================
    // PERBAIKAN: Menggunakan 'kode_ruangan' agar cocok
    // dengan nama kolom di database Anda.
    // ======================================================
    protected $fillable = [
        'name',
        'kode_ruangan',
        'lokasi',
    ];

    /**
     * Mendefinisikan relasi one-to-many ke model Inventaris.
     * Satu ruangan (Room) dapat memiliki banyak inventaris.
     */
    public function inventaris()
    {
        return $this->hasMany(Inventaris::class, 'room_id', 'id');
    }
}

